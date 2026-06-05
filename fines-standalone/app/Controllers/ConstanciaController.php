<?php

declare(strict_types=1);

namespace FinesApp\Controllers;

use FinesApp\Core\Request;
use FinesApp\Core\Response;
use FinesApp\Core\Session;
use FinesApp\Repositories\AlumnoComisionRepository;
use FinesApp\Repositories\AlumnoRepository;
use FinesApp\Repositories\ConstanciaRepository;
use FinesApp\Repositories\EstablecimientoRepository;
use FinesApp\Repositories\PersonaRepository;
use TCPDF;

final class ConstanciaController extends Controller
{
    public function newAlumnoRegular(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        [$persona, $alumno, $ultimaComision] = $this->alumnoContext((string) ($vars['id'] ?? ''));

        $this->view->render('constancias/alumno_regular_form', [
            'title' => 'Constancia de alumno regular',
            'persona' => $persona,
            'alumno' => $alumno,
            'ultimaComision' => $ultimaComision,
            'defaults' => $this->alumnoRegularDefaults($persona, $alumno, $ultimaComision),
            'error' => flash('error'),
        ]);
    }

    public function createAlumnoRegular(Request $request, array $vars = []): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $personaId = (string) ($vars['id'] ?? '');
        [$persona, $alumno] = $this->alumnoContext($personaId);
        $data = $this->alumnoRegularInput($request);
        $clave = bin2hex(random_bytes(12));
        $repository = new ConstanciaRepository($this->pdo);
        $user = $this->auth->user();
        $establecimiento = $user === null ? null : (new EstablecimientoRepository($this->pdo))->byUser((int) $user['id']);
        $data['establecimiento_nombre'] = (string) ($establecimiento['nombre'] ?? 'CENS Nro 462');

        $this->pdo->beginTransaction();
        try {
            $id = $repository->create([
                'establecimiento_id' => $establecimiento['id'] ?? null,
                'tipo' => 'alumno_regular',
                'titulo' => 'Constancia de alumno regular',
                'descripcion' => $this->descripcionAlumnoRegular($data),
                'clave' => $clave,
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'numero_documento' => $data['numero_documento'],
                'datos' => $data,
                'origen_sistema' => 'fines-standalone',
                'origen_referencia' => 'alumno:' . (string) $alumno['id'],
                'creado_por' => $this->auth->user()['id'] ?? null,
            ]);

            [$absolutePath, $relativePath, $fileName] = $this->pdfPath($id, (string) $persona['numero_documento']);
            $this->renderAlumnoRegularPdf($absolutePath, $this->validationUrl($id, $clave), $data, $establecimiento);
            $repository->updateFile($id, $relativePath, $fileName);
            $this->pdo->commit();
        } catch (\Throwable $throwable) {
            $this->pdo->rollBack();
            throw $throwable;
        }

        Session::flash('notice', 'Constancia generada.');
        Response::redirect(url("/personas/{$personaId}/alumno"));
    }

    public function validateConstancia(Request $request): void
    {
        $id = (string) $request->query('id', '');
        $clave = (string) $request->query('clave', '');
        $constancia = $id !== '' && $clave !== ''
            ? (new ConstanciaRepository($this->pdo))->findValid($id, $clave)
            : null;

        $this->view->render('constancias/validar', [
            'title' => 'Validar constancia',
            'constancia' => $constancia,
            'id' => $id,
            'clave' => $clave,
        ], $constancia === null ? 404 : 200);
    }

    public function download(Request $request): void
    {
        $constancia = (new ConstanciaRepository($this->pdo))->findValid(
            (string) $request->query('id', ''),
            (string) $request->query('clave', ''),
        );

        if ($constancia === null || empty($constancia['archivo_path'])) {
            Response::text('Constancia no encontrada', 404);
        }

        $storageRootConfig = $this->constanciasStorageRoot();
        $absolutePath = $storageRootConfig . DIRECTORY_SEPARATOR . ltrim((string) $constancia['archivo_path'], '/\\');
        $realPath = realpath($absolutePath);
        $storageRoot = realpath($storageRootConfig);
        $storagePrefix = $storageRoot === false ? '' : rtrim($storageRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if ($realPath === false || $storageRoot === false || !str_starts_with($realPath, $storagePrefix)) {
            Response::text('Archivo no disponible', 404);
        }

        Response::file(
            $realPath,
            (string) ($constancia['archivo_nombre'] ?: 'constancia.pdf'),
            (string) ($constancia['mime_type'] ?: 'application/pdf'),
        );
    }

    private function alumnoContext(string $personaId): array
    {
        $persona = (new PersonaRepository($this->pdo))->find($personaId);
        if ($persona === null) {
            throw new \RuntimeException('No se encontro la persona solicitada.');
        }

        $alumno = (new AlumnoRepository($this->pdo))->byPersona($personaId);
        if ($alumno === null) {
            throw new \RuntimeException('No hay registro de alumno para generar constancias.');
        }

        $ultimaComision = (new AlumnoComisionRepository($this->pdo))->latestByAlumno((string) $alumno['id']);

        return [$persona, $alumno, $ultimaComision];
    }

    private function alumnoRegularDefaults(array $persona, array $alumno, ?array $ultimaComision): array
    {
        return [
            'nombres' => mb_strtoupper((string) ($persona['nombres'] ?? ''), 'UTF-8'),
            'apellidos' => mb_strtoupper((string) ($persona['apellidos'] ?? ''), 'UTF-8'),
            'numero_documento' => (string) ($persona['numero_documento'] ?? ''),
            'anio' => $this->anioEnLetras($ultimaComision['planificacion_anio'] ?? $alumno['anio_ingreso'] ?? ''),
            'orientacion' => (string) ($ultimaComision['plan_orientacion'] ?? $alumno['plan_orientacion'] ?? 'Ciencias Sociales'),
            'resolucion' => (string) ($ultimaComision['plan_resolucion'] ?? $alumno['plan_resolucion'] ?? '2993/22'),
            'fecha' => $this->fechaActual(),
            'presentado' => 'Quien Corresponda',
            'observaciones' => '',
            'incluir_firmas' => '1',
        ];
    }

    private function alumnoRegularInput(Request $request): array
    {
        $fields = ['nombres', 'apellidos', 'numero_documento', 'anio', 'orientacion', 'resolucion', 'fecha', 'presentado'];
        $data = [];
        foreach ($fields as $field) {
            $value = $request->input($field);
            if ($value === null || $value === '') {
                throw new \InvalidArgumentException("El campo {$field} es obligatorio.");
            }
            $data[$field] = $value;
        }
        $data['observaciones'] = $request->input('observaciones', '') ?? '';
        $data['incluir_firmas'] = $request->input('incluir_firmas') === '1';

        return $data;
    }

    private function descripcionAlumnoRegular(array $data): string
    {
        return sprintf(
            '%s deja constancia que %s, %s, DNI %s, es alumno/a regular de %s ano del Programa Fines 2 Trayecto Secundario con orientacion en %s, resolucion %s.',
            $data['establecimiento_nombre'] ?? 'El establecimiento',
            $data['apellidos'],
            $data['nombres'],
            $data['numero_documento'],
            $data['anio'],
            $data['orientacion'],
            $data['resolucion'],
        );
    }

    private function renderAlumnoRegularPdf(string $path, string $validationUrl, array $data, ?array $establecimiento = null): void
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $pdf = new TCPDF('L', 'mm', 'A5');
        $pdf->SetCreator('Fines Standalone');
        $pdf->SetAuthor('CENS 462');
        $pdf->SetTitle('Constancia de Alumno Regular');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(20, 20, 20);
        $pdf->AddPage();
        $pdf->Rect(10, 10, 190, 125);
        $this->addHeader($pdf, $validationUrl, $data, $establecimiento);
        $pdf->SetY(48);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'CONSTANCIA DE ALUMNO REGULAR', 0, 1, 'C');
        $pdf->Ln(5);

        $body = $this->pdfBody($data);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->writeHTMLCell(0, 0, '', '', $body, 0, 1, false, true, 'J');

        if (!empty($data['incluir_firmas'])) {
            $this->addInstitutionImages($pdf, $establecimiento);
        }

        $pdf->Output($path, 'F');
    }

    private function addHeader(TCPDF $pdf, string $validationUrl, array $data, ?array $establecimiento): void
    {
        $logo = $this->storageFilePath((string) ($establecimiento['logo_path'] ?? ''));
        if ($logo !== null) {
            $pdf->Image($logo, 20, 15, 122, 0, '', '', '', false, 300, '', false, false, 0, true);
        } else {
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetXY(20, 20);
            $pdf->Cell(122, 8, (string) ($data['establecimiento_nombre'] ?? 'CENS Nro 462') . ' - Programa Fines 2', 0, 0, 'L');
        }

        $pdf->write2DBarcode($validationUrl, 'QRCODE,H', 164, 15, 28, 28);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetXY(162, 44);
        $pdf->Cell(32, 4, 'VERIFICACION', 0, 0, 'C');
    }

    private function pdfBody(array $data): string
    {
        $apellidos = $this->pdfEscape($data['apellidos']);
        $nombres = $this->pdfEscape($data['nombres']);
        $documento = $this->pdfEscape($data['numero_documento']);
        $anio = $this->pdfEscape($data['anio']);
        $orientacion = $this->pdfEscape($data['orientacion']);
        $resolucion = $this->pdfEscape($data['resolucion']);
        $fecha = $this->pdfEscape($data['fecha']);
        $presentado = $this->pdfEscape($data['presentado']);
        $establecimiento = $this->pdfEscape((string) ($data['establecimiento_nombre'] ?? 'CENS Nro 462'));
        $body = "
            <p>La Direccion de {$establecimiento} hace constar por la presente que
            <strong><u><i>&nbsp;&nbsp;{$apellidos}, {$nombres}&nbsp;&nbsp;</i></u></strong>,
            DNI Nro <strong><u><i>&nbsp;&nbsp;{$documento}&nbsp;&nbsp;</i></u></strong>,
            es alumno/a regular de <strong><u><i>&nbsp;&nbsp;{$anio}&nbsp;&nbsp;</i></u></strong> ano
            del <strong><u><i>Programa Fines 2 Trayecto Secundario</i></u></strong>, con orientacion en
            <strong><u><i>&nbsp;&nbsp;{$orientacion}&nbsp;&nbsp;</i></u></strong>, resolucion
            <strong><u><i>&nbsp;&nbsp;{$resolucion}&nbsp;&nbsp;</i></u></strong>.</p>
            <p>Se extiende la presente a pedido del interesado en La Plata el dia
            <strong><u><i>&nbsp;&nbsp;{$fecha}&nbsp;&nbsp;</i></u></strong> para ser presentada ante
            <strong><u><i>&nbsp;&nbsp;{$presentado}&nbsp;&nbsp;</i></u></strong>.</p>
        ";

        if ($data['observaciones'] !== '') {
            $body .= '<p>Observaciones: <strong><u><i>&nbsp;&nbsp;' . $this->pdfEscape($data['observaciones']) . '&nbsp;&nbsp;</i></u></strong></p>';
        }

        return $body;
    }

    private function pdfPath(int $id, string $dni): array
    {
        $relativeDirectory = date('Y') . '/' . date('m');
        $safeDni = preg_replace('/[^0-9A-Za-z_-]/', '', $dni) ?: 'sin-dni';
        $fileName = sprintf('constancia_%d_alumno_regular_%s.pdf', $id, $safeDni);
        $relativePath = $relativeDirectory . '/' . $fileName;

        return [$this->constanciasStorageRoot() . DIRECTORY_SEPARATOR . $relativePath, $relativePath, $fileName];
    }

    private function validationUrl(int $id, string $clave): string
    {
        return $this->constanciasPublicUrl() . '/validar-constancia?id=' . $id . '&clave=' . rawurlencode($clave);
    }

    private function constanciasStorageRoot(): string
    {
        $path = (string) ($_ENV['CONSTANCIAS_STORAGE_PATH'] ?? '');
        if ($path !== '') {
            return rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
        }

        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'constancias';
    }

    private function constanciasPublicUrl(): string
    {
        $url = rtrim((string) ($_ENV['CONSTANCIAS_PUBLIC_URL'] ?? ''), '/');
        if ($url !== '') {
            return $url;
        }

        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? '') === '443');

        return rtrim(($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . url('/'), '/');
    }

    private function addInstitutionImages(TCPDF $pdf, ?array $establecimiento): void
    {
        $sello = $this->storageFilePath((string) ($establecimiento['sello_oval_path'] ?? ''));
        $firma = $this->storageFilePath((string) ($establecimiento['firma_director_path'] ?? ''));

        if ($sello !== null) {
            $pdf->Image($sello, 86, 90, 28, 36);
        }
        if ($firma !== null) {
            $pdf->Image($firma, 122, 96, 54, 28);
        }

        if ($sello === null && $firma === null) {
            $pdf->SetY(114);
            $pdf->Cell(0, 6, 'Firma y sello', 0, 1, 'R');
            $pdf->Line(135, 118, 185, 118);
        }
    }

    private function storageFilePath(string $relativePath): ?string
    {
        if ($relativePath === '') {
            return null;
        }

        $path = $this->constanciasStorageRoot() . DIRECTORY_SEPARATOR . ltrim($relativePath, '/\\');
        if (!is_file($path)) {
            return null;
        }

        return $this->isTcpdfSafeImage($path) ? $path : null;
    }

    private function isTcpdfSafeImage(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($extension, ['jpg', 'jpeg'], true)) {
            return true;
        }

        return extension_loaded('gd') || extension_loaded('imagick');
    }

    private function fechaActual(): string
    {
        $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        return date('j') . ' de ' . $months[(int) date('n') - 1] . ' de ' . date('Y');
    }

    private function anioEnLetras(mixed $value): string
    {
        return match ((string) $value) {
            '1' => 'primer',
            '2' => 'segundo',
            '3' => 'tercer',
            default => (string) $value,
        };
    }

    private function pdfEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
