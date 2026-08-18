<?php

declare(strict_types=1);

namespace ConstanciasApp\Services;

use ConstanciasApp\Core\Config;
use ConstanciasApp\Repositories\ConstanciaRepository;
use ConstanciasApp\Repositories\EstablecimientoRepository;
use PDO;
use TCPDF;

/**
 * Genera PDF de toma de posesión (tipo constancia) y opcionalmente envía email.
 */
final class TomaPosesionService
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly Config $config,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     * @return array{
     *   constancia_id: int,
     *   clave: string,
     *   validation_url: string,
     *   download_url: string,
     *   archivo_nombre: string,
     *   email_sent: bool,
     *   email_error: ?string
     * }
     */
    public function generate(array $payload, bool $enviarEmail = false): array
    {
        $docente = is_array($payload['docente'] ?? null) ? $payload['docente'] : $this->docenteFromFlat($payload);
        $cargo = is_array($payload['cargo'] ?? null) ? $payload['cargo'] : $this->cargoFromFlat($payload);
        $contenidoHtml = trim((string) ($payload['contenido_html'] ?? ''));

        $nombres = trim((string) ($docente['nombres'] ?? ''));
        $apellidos = trim((string) ($docente['apellidos'] ?? ''));
        $dni = preg_replace('/\D+/', '', (string) ($docente['numero_documento'] ?? '')) ?? '';
        if ($nombres === '' || $dni === '') {
            throw new \InvalidArgumentException('Faltan datos del docente (nombres y documento).');
        }

        $emails = $this->parseEmails($payload['emails'] ?? null, $docente);

        $establecimiento = $this->resolveEstablecimiento(
            isset($payload['establecimiento_id']) ? (int) $payload['establecimiento_id'] : null,
        );

        $repository = new ConstanciaRepository($this->pdo);
        $clave = $this->uniqueClave($repository);
        $titulo = 'Toma de posesión';
        $docenteNombre = trim($apellidos . ', ' . $nombres);
        $descripcion = sprintf(
            'Toma de posesión de %s (DNI %s) en %s / comisión %s',
            $docenteNombre,
            $dni,
            trim((string) ($cargo['asignatura'] ?? '')),
            trim((string) ($cargo['pfid'] ?? '')),
        );

        $incluirFirmas = array_key_exists('incluir_firmas', $payload)
            ? !empty($payload['incluir_firmas'])
            : true;

        $datos = [
            'clave' => $clave,
            'docente' => $docente,
            'cargo' => $cargo,
            'contenido_html' => $contenidoHtml,
            'emails' => implode(', ', $emails),
            'establecimiento_nombre' => (string) ($establecimiento['nombre'] ?? 'Establecimiento'),
            'localidad' => (string) ($establecimiento['localidad'] ?? 'La Plata'),
            'incluir_firmas' => $incluirFirmas ? 1 : 0,
            'toma_id' => $payload['toma_id'] ?? null,
            'comision_id' => $payload['comision_id'] ?? null,
            'curso_id' => $payload['curso_id'] ?? null,
        ];

        $createPayload = [
            'establecimiento_id' => $establecimiento['id'] ?? null,
            'tipo' => 'toma_posesion',
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'clave' => $clave,
            'nombres' => $nombres,
            'apellidos' => $apellidos !== '' ? $apellidos : '-',
            'numero_documento' => $dni,
            'datos' => $datos,
            'creado_por' => $payload['creado_por'] ?? null,
        ];

        $this->pdo->beginTransaction();
        try {
            $id = $repository->create($createPayload);
            [$absolutePath, $relativePath, $fileName] = $this->pdfPath($id, $dni);
            $this->renderPdf(
                $absolutePath,
                $this->validationUrl($id, $clave),
                $datos,
                $establecimiento,
            );
            $repository->updateFile($id, $relativePath, $fileName);
            $this->pdo->commit();
        } catch (\Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $throwable;
        }

        $emailSent = false;
        $emailError = null;
        if ($enviarEmail) {
            try {
                $this->sendEmail($emails, $docente, $cargo, $absolutePath, $fileName);
                $emailSent = true;
            } catch (\Throwable $throwable) {
                $emailError = $throwable->getMessage();
            }
        }

        return [
            'constancia_id' => $id,
            'clave' => $clave,
            'validation_url' => $this->validationUrl($id, $clave),
            'download_url' => $this->downloadUrl($id, $clave),
            'archivo_nombre' => $fileName,
            'email_sent' => $emailSent,
            'email_error' => $emailError,
        ];
    }

    /**
     * @param array<string, mixed> $datos
     * @param array<string, mixed>|null $establecimiento
     */
    private function renderPdf(string $path, string $validationUrl, array $datos, ?array $establecimiento): void
    {
        $dir = dirname($path);
        if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new \RuntimeException('No se pudo crear el directorio de almacenamiento.');
        }

        $docente = is_array($datos['docente'] ?? null) ? $datos['docente'] : [];
        $cargo = is_array($datos['cargo'] ?? null) ? $datos['cargo'] : [];
        $establecimientoNombre = (string) ($datos['establecimiento_nombre'] ?? 'Establecimiento');
        $localidad = (string) ($datos['localidad'] ?? 'La Plata');

        $contenidoHtml = trim((string) ($datos['contenido_html'] ?? ''));
        if ($contenidoHtml === '') {
            $contenidoHtml = $this->defaultContenidoHtml($docente, $cargo);
        }

        $sello = $this->storageFilePath((string) ($establecimiento['sello_oval_path'] ?? ''));
        $firma = $this->storageFilePath((string) ($establecimiento['firma_director_path'] ?? ''));

        $pdf = new TCPDF('P', 'mm', 'A4');
        $pdf->SetCreator('Constancias');
        $pdf->SetAuthor($establecimientoNombre);
        $pdf->SetTitle('Toma de Posesión');
        $pdf->SetMargins(15, 20, 15);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->Rect(10, 10, 190, 270);

        $logo = $this->commonLogoPath();
        if ($logo !== null) {
            $pdf->Image($logo, 15, 15, 100, 0, '', '', '', false, 300, '', false, false, 0, true);
        } else {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetXY(15, 18);
            $pdf->Cell(100, 8, $establecimientoNombre . ' - Programa Fines 2', 0, 0, 'L');
        }

        $pdf->write2DBarcode($validationUrl, 'QRCODE,H', 165, 15, 28, 28);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetXY(163, 43);
        $pdf->Cell(32, 4, (string) ($datos['clave'] ?? ''), 0, 0, 'C');

        if (!empty($datos['incluir_firmas'])) {
            $pdf->SetAlpha(0.9);
            if ($sello !== null) {
                $pdf->Image($sello, 85, 210, 30, 40);
            }
            if ($firma !== null) {
                $pdf->Image($firma, 120, 220, 60, 35);
            }
            $pdf->SetAlpha(1);
        }

        $pdf->Ln(28);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'TOMA DE POSESION', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', '', 12);
        $intro = '<p>La Dirección de ' . $this->e($establecimientoNombre) . ' de ' . $this->e($localidad)
            . ' realiza la toma de posesión docente con el siguiente detalle:</p>';
        $pdf->writeHTMLCell(0, 0, '', '', $intro, 0, 1, false, true, 'J');
        $pdf->Ln(10);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->writeHTML($contenidoHtml, true, false, false, false, '');

        $pdf->Output($path, 'F');
    }

    /**
     * @param list<string> $to
     * @param array<string, mixed> $docente
     * @param array<string, mixed> $cargo
     */
    private function sendEmail(array $to, array $docente, array $cargo, string $pdfPath, string $fileName): void
    {
        $to = array_values(array_filter(array_map('trim', $to), static fn (string $value): bool => $value !== ''));
        if ($to === []) {
            throw new \InvalidArgumentException('Indicá al menos un email para el envío.');
        }

        $nombre = trim(
            (string) ($docente['apellidos'] ?? '') . ' ' . (string) ($docente['nombres'] ?? ''),
        );
        $cuil = trim((string) ($docente['cuil'] ?? ''));
        $asignatura = trim((string) ($cargo['asignatura'] ?? ''));
        $sede = trim((string) ($cargo['sede'] ?? ''));
        $pfid = trim((string) ($cargo['pfid'] ?? ''));

        $fechaLimite = $this->config->string('TOMA_EMAIL_FECHA_LIMITE_NOTAS', '20/7/2026');
        $recuperacion = $this->config->string('TOMA_EMAIL_RECUPERACION', '13/07/2026 a 17/07/2026');
        $whatsapp = $this->config->string('TOMA_EMAIL_WHATSAPP', '2216713326');
        $instruccionesUrl = $this->config->string(
            'TOMA_EMAIL_INSTRUCCIONES_URL',
            'https://planfines2.com.ar/wp/finalizacion-de-semestre/',
        );
        $cobroUrl = $this->config->string(
            'TOMA_EMAIL_COBRO_URL',
            'https://planfines2.com.ar/wp/2025/09/09/cobro-de-haberes-cens-462-periodo-2025-2/',
        );
        $webUrl = $this->config->string('TOMA_EMAIL_WEB_URL', 'https://planfines2.com.ar');
        $firmaEquipo = $this->config->string(
            'TOMA_EMAIL_FIRMA',
            'Equipo de Coordinadores del Plan Fines 2 CENS 462',
        );

        $subject = 'Toma de Posesión ' . $nombre
            . ($cuil !== '' ? ' CUIL ' . $cuil : '')
            . ' en cargo ' . trim($pfid . ' ' . $asignatura);

        $body = "
<p>Hola {$this->e($nombre)}, usted ha recibido este email porque ha tomado posesión en la asignatura
<strong>{$this->e($asignatura)}</strong> de sede {$this->e($sede)}.</p>
<p>Se recuerda que usted asumió el compromiso de:</p>
<ul>
  <li>Completar las planillas de finalización en tiempo y forma:
    <strong>FECHA LIMITE DE ENTREGA DE NOTAS {$this->e($fechaLimite)}</strong></li>
  <li>Completar las planillas siguiendo instrucciones:
    <a href=\"{$this->e($instruccionesUrl)}\" target=\"_blank\">Ver instrucciones</a></li>
  <li>Utilizar la última semana de recuperación o intensificación de la enseñanza,
    {$this->e($recuperacion)}: El objetivo es que el alumno aprenda los contenidos mínimos</li>
  <li>Participar de las mesas de examen cuando se lo requiera.</li>
  <li>Atender a la brevedad cualquier solicitud indicada por el CENS.</li>
</ul>
<p>Consultar consideraciones sobre
  <a href=\"{$this->e($cobroUrl)}\" target=\"_blank\">Cobro de Háberes</a></p>
<p><strong>Para cualquier duda comuníquese vía mensaje o audio de WhatsApp al número
  <a href=\"https://wa.me/{$this->e(preg_replace('/\D+/', '', $whatsapp) ?? $whatsapp)}\" target=\"_blank\">{$this->e($whatsapp)}</a></strong></p>
<br>
Saluda a Usted muy atentamente:
<br>
{$this->e($firmaEquipo)}
<br><a href=\"{$this->e($webUrl)}\">{$this->e($webUrl)}</a>
";

        $bcc = array_filter(array_map('trim', explode(',', $this->config->string('SMTP_BCC', ''))));

        (new MailService($this->config))->sendHtml(
            $to,
            $subject,
            $body,
            $bcc,
            [['path' => $pdfPath, 'name' => $fileName]],
        );
    }

    /** @return array<string, mixed>|null */
    private function resolveEstablecimiento(?int $id): ?array
    {
        $repo = new EstablecimientoRepository($this->pdo);
        if ($id !== null && $id > 0) {
            $found = $repo->byId($id);
            if ($found !== null) {
                return $found;
            }
        }

        $configured = (int) $this->config->string('DEFAULT_ESTABLECIMIENTO_ID', '0');
        if ($configured > 0) {
            $found = $repo->byId($configured);
            if ($found !== null) {
                return $found;
            }
        }

        return $repo->firstActive();
    }

    private function pdfPath(int $id, string $dni): array
    {
        $relativeDirectory = date('Y') . '/' . date('m');
        $safeDni = preg_replace('/[^0-9A-Za-z_-]/', '', $dni) ?: 'sin-dni';
        $fileName = sprintf('constancia_%d_toma_posesion_%s.pdf', $id, $safeDni);
        $relativePath = $relativeDirectory . '/' . $fileName;

        return [
            $this->config->storagePath() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath),
            $relativePath,
            $fileName,
        ];
    }

    private function validationUrl(int $id, string $clave): string
    {
        return $this->config->publicUrl() . '/validar-constancia?clave=' . rawurlencode($clave);
    }

    private function downloadUrl(int $id, string $clave): string
    {
        return $this->config->publicUrl() . '/validar-constancia/descargar?clave=' . rawurlencode($clave);
    }

    private function uniqueClave(ConstanciaRepository $repository): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        do {
            $clave = '';
            for ($i = 0; $i < 6; $i++) {
                $clave .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
        } while ($repository->claveExists($clave));

        return $clave;
    }

    private function commonLogoPath(): ?string
    {
        $path = $this->config->rootPath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR
            . 'assets' . DIRECTORY_SEPARATOR . 'constancias-header-logo.jpg';

        return is_file($path) ? $path : null;
    }

    private function storageFilePath(string $relativePath): ?string
    {
        if ($relativePath === '') {
            return null;
        }
        $path = $this->config->storagePath() . DIRECTORY_SEPARATOR . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath), DIRECTORY_SEPARATOR);

        return is_file($path) ? $path : null;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function docenteFromFlat(array $payload): array
    {
        return [
            'nombres' => (string) ($payload['nombres'] ?? ''),
            'apellidos' => (string) ($payload['apellidos'] ?? ''),
            'numero_documento' => (string) ($payload['numero_documento'] ?? ''),
            'cuil' => (string) ($payload['cuil'] ?? ''),
            'fecha_nacimiento' => (string) ($payload['fecha_nacimiento'] ?? ''),
            'email' => (string) ($payload['email'] ?? ''),
            'email_abc' => (string) ($payload['email_abc'] ?? ''),
            'descripcion_domicilio' => (string) ($payload['descripcion_domicilio'] ?? ''),
            'telefono' => (string) ($payload['telefono'] ?? ''),
        ];
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function cargoFromFlat(array $payload): array
    {
        return [
            'sede' => (string) ($payload['sede'] ?? ''),
            'domicilio_sede' => (string) ($payload['domicilio_sede'] ?? ''),
            'pfid' => (string) ($payload['pfid'] ?? ''),
            'horario' => (string) ($payload['horario'] ?? ''),
            'fecha_toma' => (string) ($payload['fecha_toma'] ?? ''),
            'fecha_fin' => (string) ($payload['fecha_fin'] ?? ''),
            'asignatura' => (string) ($payload['asignatura'] ?? ''),
            'horas_catedra' => (string) ($payload['horas_catedra'] ?? ''),
            'tramo' => (string) ($payload['tramo'] ?? ''),
            'resolucion' => (string) ($payload['resolucion'] ?? ''),
        ];
    }

    /**
     * @param mixed $emails
     * @param array<string, mixed> $docente
     * @return list<string>
     */
    private function parseEmails(mixed $emails, array $docente): array
    {
        $raw = [];
        if (is_array($emails)) {
            $raw = $emails;
        } elseif (is_string($emails) && trim($emails) !== '') {
            $raw = preg_split('/[,;]+/', $emails) ?: [];
        } else {
            $raw = [
                (string) ($docente['email_abc'] ?? ''),
                (string) ($docente['email'] ?? ''),
            ];
        }

        $parsed = [];
        foreach ($raw as $email) {
            $email = strtolower(trim((string) $email));
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            $parsed[$email] = $email;
        }

        return array_values($parsed);
    }

    /**
     * @param array<string, mixed> $docente
     * @param array<string, mixed> $cargo
     */
    private function defaultContenidoHtml(array $docente, array $cargo): string
    {
        $nombre = trim(
            (string) ($docente['apellidos'] ?? '') . ', ' . (string) ($docente['nombres'] ?? ''),
            " \t\n\r\0\x0B,",
        );
        $emails = trim(implode(' / ', array_filter([
            trim((string) ($docente['email_abc'] ?? '')),
            trim((string) ($docente['email'] ?? '')),
        ])));

        return '
<table border="1" cellpadding="5">
    <tr><th colspan="4" bgcolor="#cccccc"><b>Datos del Docente</b></th></tr>
    <tr>
        <td><b>Nombre</b></td>
        <td colspan="3">' . $this->e($nombre) . '</td>
    </tr>
    <tr>
        <td><b>CUIL</b></td><td>' . $this->e((string) ($docente['cuil'] ?? '')) . '</td>
        <td><b>Fecha de Nacimiento</b></td><td>' . $this->e((string) ($docente['fecha_nacimiento'] ?? '')) . '</td>
    </tr>
    <tr>
        <td><b>Email</b></td><td colspan="3">' . $this->e($emails) . '</td>
    </tr>
    <tr>
        <td><b>Domicilio</b></td><td colspan="3">' . $this->e((string) ($docente['descripcion_domicilio'] ?? '')) . '</td>
    </tr>
    <tr>
        <td><b>Teléfono</b></td><td colspan="3">' . $this->e((string) ($docente['telefono'] ?? '')) . '</td>
    </tr>
</table>
<br>
<table border="1" cellpadding="5">
    <tr><th colspan="4" bgcolor="#cccccc"><b>Datos del Cargo</b></th></tr>
    <tr>
        <td><b>Sede</b></td><td>' . $this->e((string) ($cargo['sede'] ?? '')) . '</td>
        <td><b>Comisión</b></td><td>' . $this->e((string) ($cargo['pfid'] ?? '')) . '</td>
    </tr>
    <tr>
        <td><b>Domicilio</b></td><td colspan="3">' . $this->e((string) ($cargo['domicilio_sede'] ?? '')) . '</td>
    </tr>
    <tr>
        <td><b>Horario</b></td><td colspan="3">' . $this->e((string) ($cargo['horario'] ?? '')) . '</td>
    </tr>
    <tr>
        <td><b>Fecha Toma</b></td><td>' . $this->e((string) ($cargo['fecha_toma'] ?? '')) . '</td>
        <td><b>Fecha Fin</b></td><td>' . $this->e((string) ($cargo['fecha_fin'] ?? '')) . '</td>
    </tr>
    <tr>
        <td><b>Asignatura</b></td><td>' . $this->e((string) ($cargo['asignatura'] ?? '')) . '</td>
        <td><b>Hs Cát</b></td><td>' . $this->e((string) ($cargo['horas_catedra'] ?? '')) . '</td>
    </tr>
    <tr>
        <td><b>Tramo</b></td><td>' . $this->e((string) ($cargo['tramo'] ?? '')) . '</td>
        <td><b>Resolución</b></td><td>' . $this->e((string) ($cargo['resolucion'] ?? '')) . '</td>
    </tr>
</table>';
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
