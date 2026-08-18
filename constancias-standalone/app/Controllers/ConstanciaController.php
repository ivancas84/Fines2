<?php

declare(strict_types=1);

namespace ConstanciasApp\Controllers;

use ConstanciasApp\Core\Request;
use ConstanciasApp\Core\Response;
use ConstanciasApp\Core\Session;
use ConstanciasApp\Repositories\ConstanciaRepository;
use ConstanciasApp\Repositories\EstablecimientoRepository;
use ConstanciasApp\Services\TomaPosesionService;
use HTMLPurifier;
use HTMLPurifier_Config;
use TCPDF;

final class ConstanciaController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireEdit();
        $this->view->render('constancias/index', ['title' => 'Constancias']);
    }

    public function newAlumnoRegular(Request $request): void
    {
        $this->requireEdit();
        $this->view->render('constancias/alumno_regular_form', [
            'title' => 'Constancia de alumno regular',
            'defaults' => array_merge($this->academicDefaults(), [
                'nombres' => '',
                'apellidos' => '',
                'numero_documento' => '',
                'anio' => '',
                'fecha' => $this->fechaActual(),
                'presentado' => 'Quien Corresponda',
                'observaciones' => '',
                'incluir_firmas' => '1',
            ]),
            'error' => flash('error'),
        ]);
    }

    public function newTituloTramite(Request $request): void
    {
        $this->requireEdit();
        $this->view->render('constancias/titulo_tramite_form', [
            'title' => 'Constancia de título en trámite',
            'defaults' => array_merge($this->academicDefaults(), [
                'nombres' => '',
                'apellidos' => '',
                'numero_documento' => '',
                'anio' => '',
                'fecha' => $this->fechaActual(),
                'presentado' => 'Quien Corresponda',
                'observaciones' => '',
                'incluir_firmas' => '1',
            ]),
            'error' => flash('error'),
        ]);
    }

    public function newVacante(Request $request): void
    {
        $this->requireEdit();
        $this->view->render('constancias/vacante_form', [
            'title' => 'Constancia de vacante',
            'defaults' => [
                'nombres' => '',
                'apellidos' => '',
                'numero_documento' => '',
                'fecha' => $this->fechaActual(),
                'presentado' => 'Quien Corresponda',
                'observaciones' => '',
                'incluir_firmas' => '1',
            ],
            'error' => flash('error'),
        ]);
    }

    public function newPase(Request $request): void
    {
        $this->requireEdit();
        $this->view->render('constancias/pase_form', [
            'title' => 'Constancia de pase',
            'defaults' => array_merge($this->academicDefaults(), [
                'nombres' => '',
                'apellidos' => '',
                'numero_documento' => '',
                'anio' => '',
                'fecha' => $this->fechaActual(),
                'presentado' => 'Quien Corresponda',
                'materias_aprobadas_html' => '',
                'materias_desaprobadas_html' => '',
                'observaciones' => '',
                'incluir_firmas' => '1',
            ]),
            'error' => flash('error'),
        ]);
    }

    public function newGeneral(Request $request): void
    {
        $this->requireEdit();
        $this->view->render('constancias/general_form', [
            'title' => 'Constancia general',
            'defaults' => [
                'nombres' => '',
                'apellidos' => '',
                'numero_documento' => '',
                'fecha' => $this->fechaActual(),
                'presentado' => 'Quien Corresponda',
                'texto' => '',
                'incluir_firmas' => '1',
            ],
            'error' => flash('error'),
        ]);
    }

    public function createAlumnoRegular(Request $request): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $data = $this->inputFields($request, ['nombres', 'apellidos', 'numero_documento', 'anio', 'modalidad', 'orientacion', 'resolucion', 'fecha', 'presentado']);

        $this->issueConstancia(
            'alumno_regular',
            'Constancia de alumno regular',
            'CONSTANCIA DE ALUMNO REGULAR',
            'alumno_regular',
            $data,
            fn (array $prepared): string => $this->alumnoRegularBody($prepared),
        );
    }

    public function createTituloTramite(Request $request): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $data = $this->inputFields($request, ['nombres', 'apellidos', 'numero_documento', 'anio', 'modalidad', 'orientacion', 'resolucion', 'fecha', 'presentado']);

        $this->issueConstancia(
            'titulo_tramite',
            'Constancia de título en trámite',
            'CONSTANCIA DE CERTIFICADO DE ESTUDIO EN TRÁMITE',
            'titulo_tramite',
            $data,
            fn (array $prepared): string => $this->tituloTramiteBody($prepared),
        );
    }

    public function createVacante(Request $request): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $data = $this->inputFields($request, ['nombres', 'apellidos', 'numero_documento', 'fecha', 'presentado']);

        $this->issueConstancia(
            'vacante',
            'Constancia de vacante',
            'CONSTANCIA DE VACANTE',
            'vacante',
            $data,
            fn (array $prepared): string => $this->vacanteBody($prepared),
        );
    }

    public function createPase(Request $request): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $data = $this->inputFields($request, [
            'nombres',
            'apellidos',
            'numero_documento',
            'anio',
            'modalidad',
            'orientacion',
            'resolucion',
            'fecha',
            'presentado',
        ]);
        $data['materias_aprobadas_html'] = $this->sanitizeTableHtml((string) ($request->input('materias_aprobadas_html', '') ?? ''));
        $data['materias_desaprobadas_html'] = $this->sanitizeTableHtml((string) ($request->input('materias_desaprobadas_html', '') ?? ''));

        if ($data['materias_aprobadas_html'] === '' && $data['materias_desaprobadas_html'] === '') {
            throw new \InvalidArgumentException('Debe cargar al menos una tabla de materias.');
        }

        $this->issueConstancia(
            'pase',
            'Constancia de pase',
            'CONSTANCIA DE PASE',
            'pase',
            $data,
            fn (array $prepared): string => $this->paseBody($prepared),
            function (string $absolutePath, string $validationUrl, array $prepared, ?array $establecimiento, string $heading, string $body, string $titulo): void {
                $this->renderPasePdf($absolutePath, $validationUrl, $prepared, $establecimiento, $heading, $body, $titulo);
            },
        );
    }

    public function createGeneral(Request $request): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));
        $data = $this->inputFields($request, ['nombres', 'apellidos', 'numero_documento', 'fecha', 'presentado']);
        $data['texto'] = trim((string) ($request->input('texto', '') ?? ''));

        if ($data['texto'] === '') {
            throw new \InvalidArgumentException('Debe cargar el texto de la constancia.');
        }

        $this->issueConstancia(
            'general',
            'Constancia general',
            'CONSTANCIA GENERAL',
            'general',
            $data,
            fn (array $prepared): string => $this->generalBody($prepared),
        );
    }

    public function newTomaPosesion(Request $request): void
    {
        $this->requireEdit();
        $this->view->render('constancias/toma_posesion_form', [
            'title' => 'Toma de posesión',
            'defaults' => $this->tomaPosesionDefaults(),
            'error' => flash('error'),
        ]);
    }

    public function createTomaPosesion(Request $request): void
    {
        $this->requireEdit();
        $this->csrf->validate($request->input('_token'));

        $defaults = $this->tomaPosesionValuesFromRequest($request);
        $enviarEmail = $request->input('enviar_email') === '1';

        try {
            if (trim($defaults['nombres']) === '' || trim($defaults['numero_documento']) === '') {
                throw new \InvalidArgumentException('Nombres y DNI del docente son obligatorios.');
            }
            if ($enviarEmail && trim($defaults['emails']) === '') {
                throw new \InvalidArgumentException('Indicá uno o más emails separados por coma para enviar la toma.');
            }

            $defaults['contenido_html'] = $this->sanitizeTableHtml($defaults['contenido_html']);
            $defaults['incluir_firmas'] = $request->input('incluir_firmas') === '1' ? 1 : 0;
            $defaults['creado_por'] = $this->auth->user()['id'] ?? null;

            $result = (new TomaPosesionService($this->pdo, $this->config))->generate(
                $defaults,
                $enviarEmail,
            );

            $notice = 'Toma de posesión generada.';
            if ($enviarEmail) {
                if (!empty($result['email_sent'])) {
                    $notice .= ' Email enviado a ' . $defaults['emails'] . '.';
                } else {
                    $emailError = trim((string) ($result['email_error'] ?? ''));
                    $notice .= ' El PDF se generó, pero el email no se envió'
                        . ($emailError !== '' ? ': ' . $emailError : '.');
                }
            }
            Session::flash('notice', $notice);
            Response::redirect(url('/'));
        } catch (\Throwable $exception) {
            $this->view->render('constancias/toma_posesion_form', [
                'title' => 'Toma de posesión',
                'defaults' => $defaults,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function validateConstancia(Request $request): void
    {
        $id = (string) $request->query('id', '');
        $clave = (string) $request->query('clave', '');
        $repository = new ConstanciaRepository($this->pdo);
        $constancia = $clave !== ''
            ? ($id !== '' ? $repository->findValid($id, $clave) : $repository->findValidByClave($clave))
            : null;

        $status = $constancia !== null || ($id === '' && $clave === '') ? 200 : 404;
        $this->view->render('constancias/validar', [
            'title' => 'Validar constancia',
            'constancia' => $constancia,
            'vencida' => $this->isExpired($constancia),
            'id' => $id,
            'clave' => $clave,
        ], $status);
    }

    public function download(Request $request): void
    {
        $repository = new ConstanciaRepository($this->pdo);
        $id = (string) $request->query('id', '');
        $clave = (string) $request->query('clave', '');
        $constancia = $clave !== ''
            ? ($id !== '' ? $repository->findValid($id, $clave) : $repository->findValidByClave($clave))
            : null;
        if ($constancia === null || empty($constancia['archivo_path'])) {
            Response::text('Constancia no encontrada', 404);
        }
        if ($this->isExpired($constancia)) {
            Response::text('Constancia vencida', 410);
        }

        $absolutePath = $this->config->storagePath() . DIRECTORY_SEPARATOR . ltrim((string) $constancia['archivo_path'], '/\\');
        $realPath = realpath($absolutePath);
        $storageRoot = realpath($this->config->storagePath());
        $storagePrefix = $storageRoot === false ? '' : rtrim($storageRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if ($realPath === false || $storageRoot === false || !str_starts_with($realPath, $storagePrefix)) {
            Response::text('Archivo no disponible', 404);
        }

        Response::file($realPath, (string) ($constancia['archivo_nombre'] ?: 'constancia.pdf'), (string) ($constancia['mime_type'] ?: 'application/pdf'));
    }

    private function inputFields(Request $request, array $fields): array
    {
        $data = [];
        foreach ($fields as $field) {
            $value = $request->input($field);
            if ($value === null || $value === '') {
                throw new \InvalidArgumentException("El campo {$field} es obligatorio.");
            }
            $data[$field] = $value;
        }
        if (isset($data['apellidos'])) {
            $data['apellidos'] = mb_strtoupper($data['apellidos'], 'UTF-8');
        }
        if (isset($data['nombres'])) {
            $data['nombres'] = $this->titleCaseName($data['nombres']);
        }
        $data['observaciones'] = $request->input('observaciones', '') ?? '';
        $data['incluir_firmas'] = $request->input('incluir_firmas') === '1';

        return $data;
    }

    private function titleCaseName(string $value): string
    {
        return mb_convert_case(mb_strtolower($value, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

    private function academicDefaults(): array
    {
        $user = $this->auth->user();
        $establecimiento = $user === null ? null : (new EstablecimientoRepository($this->pdo))->byUser((int) $user['id']);

        return [
            'modalidad' => (string) ($establecimiento['modalidad_principal'] ?? 'Programa Fines 2 Trayecto Secundario'),
            'orientacion' => (string) ($establecimiento['orientacion_principal'] ?? 'Ciencias Sociales'),
            'resolucion' => (string) ($establecimiento['resolucion_principal'] ?? '2993/22'),
        ];
    }

    /** @return array<string, string> */
    private function tomaPosesionDefaults(): array
    {
        return [
            'nombres' => '',
            'apellidos' => '',
            'numero_documento' => '',
            'cuil' => '',
            'fecha_nacimiento' => '',
            'telefono' => '',
            'descripcion_domicilio' => '',
            'email' => '',
            'email_abc' => '',
            'emails' => '',
            'sede' => '',
            'domicilio_sede' => '',
            'pfid' => '',
            'horario' => '',
            'fecha_toma' => '',
            'fecha_fin' => '',
            'asignatura' => '',
            'horas_catedra' => '',
            'tramo' => '',
            'resolucion' => '',
            'contenido_html' => '',
            'incluir_firmas' => '1',
            'enviar_email' => '1',
        ];
    }

    /** @return array<string, string> */
    private function tomaPosesionValuesFromRequest(Request $request): array
    {
        $values = $this->tomaPosesionDefaults();
        foreach (array_keys($values) as $key) {
            if ($key === 'incluir_firmas' || $key === 'enviar_email') {
                $values[$key] = $request->input($key) === '1' ? '1' : '';
                continue;
            }
            $values[$key] = (string) $request->input($key, '');
        }

        return $values;
    }

    private function issueConstancia(string $tipo, string $titulo, string $heading, string $slug, array $data, callable $bodyFactory, ?callable $pdfRenderer = null): void
    {
        $user = $this->auth->user();
        $establecimiento = $user === null ? null : (new EstablecimientoRepository($this->pdo))->byUser((int) $user['id']);
        $data['establecimiento_nombre'] = (string) ($establecimiento['nombre'] ?? 'Establecimiento');
        $data['localidad'] = (string) ($establecimiento['localidad'] ?? 'La Plata');

        $repository = new ConstanciaRepository($this->pdo);
        $clave = $this->uniqueClave($repository);
        $data['clave'] = $clave;
        $body = $bodyFactory($data);
        $payload = [
            'establecimiento_id' => $establecimiento['id'] ?? null,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'descripcion' => $body,
            'clave' => $clave,
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'numero_documento' => $data['numero_documento'],
            'datos' => $data,
            'creado_por' => $user['id'] ?? null,
        ];

        if ($repository->recentDuplicate($payload) !== null) {
            Session::flash('notice', 'La constancia ya habia sido generada.');
            Response::redirect(url('/'));
        }

        $this->pdo->beginTransaction();
        try {
            $id = $repository->create($payload);
            [$absolutePath, $relativePath, $fileName] = $this->pdfPath($id, $data['numero_documento'], $slug);
            if ($pdfRenderer !== null) {
                $pdfRenderer($absolutePath, $this->validationUrl($id, $clave), $data, $establecimiento, $heading, $body, $titulo);
            } else {
                $this->renderConstanciaPdf($absolutePath, $this->validationUrl($id, $clave), $data, $establecimiento, $heading, $body, $titulo);
            }
            $repository->updateFile($id, $relativePath, $fileName);
            $this->pdo->commit();
        } catch (\Throwable $throwable) {
            $this->pdo->rollBack();
            throw $throwable;
        }

        Session::flash('notice', 'Constancia generada.');
        Response::redirect(url('/'));
    }

    private function renderConstanciaPdf(string $path, string $validationUrl, array $data, ?array $establecimiento, string $heading, string $body, string $title): void
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }

        $pdf = new TCPDF('L', 'mm', 'A5');
        $pdf->SetCreator('Constancias');
        $pdf->SetAuthor((string) ($data['establecimiento_nombre'] ?? 'Establecimiento'));
        $pdf->SetTitle($title);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(20, 20, 20);
        $pdf->AddPage();
        $pdf->Rect(10, 10, 190, 125);
        $this->addHeader($pdf, $validationUrl, $data, $establecimiento);
        $pdf->SetY(48);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, $heading, 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->writeHTMLCell(0, 0, '', '', $body, 0, 1, false, true, 'J');
        if (!empty($data['incluir_firmas'])) {
            $this->addInstitutionImages($pdf, $establecimiento);
        }
        $pdf->Output($path, 'F');
    }

    private function renderPasePdf(string $path, string $validationUrl, array $data, ?array $establecimiento, string $heading, string $body, string $title): void
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }

        $sello = !empty($data['incluir_firmas']) ? $this->storageFilePath((string) ($establecimiento['sello_oval_path'] ?? '')) : null;
        $firma = !empty($data['incluir_firmas']) ? $this->storageFilePath((string) ($establecimiento['firma_director_path'] ?? '')) : null;
        $pdf = new class('P', 'mm', 'A4', !empty($data['incluir_firmas']), $sello, $firma) extends TCPDF {
            public function __construct(
                string $orientation,
                string $unit,
                string $format,
                private readonly bool $includeFirmas,
                private readonly ?string $selloPath,
                private readonly ?string $firmaPath,
            ) {
                parent::__construct($orientation, $unit, $format);
            }

            public function Footer(): void
            {
                $this->Rect(10, 10, 190, 277);
                if (!$this->includeFirmas) {
                    return;
                }

                if ($this->selloPath !== null) {
                    $this->Image($this->selloPath, 78, 242, 28, 36);
                }
                if ($this->firmaPath !== null) {
                    $this->Image($this->firmaPath, 116, 248, 54, 28);
                }
                if ($this->selloPath === null && $this->firmaPath === null) {
                    $this->Text(148, 266, 'Firma y sello');
                    $this->Line(128, 270, 178, 270);
                }
            }
        };
        $pdf->SetCreator('Constancias');
        $pdf->SetAuthor((string) ($data['establecimiento_nombre'] ?? 'Establecimiento'));
        $pdf->SetTitle($title);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetFooterMargin(0);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, !empty($data['incluir_firmas']) ? 52 : 22);
        $pdf->AddPage();
        $this->addHeader($pdf, $validationUrl, $data, $establecimiento);
        $pdf->SetY(48);
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->Cell(0, 8, $heading, 0, 1, 'C');
        $pdf->Ln(2);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->writeHTMLCell(0, 0, '', '', $body, 0, 1, false, true, 'J');

        $pdf->Output($path, 'F');
    }

    private function addHeader(TCPDF $pdf, string $validationUrl, array $data, ?array $establecimiento): void
    {
        $logo = $this->commonLogoPath();
        if ($logo !== null) {
            $pdf->Image($logo, 20, 15, 122, 0, '', '', '', false, 300, '', false, false, 0, true);
        } else {
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetXY(20, 20);
            $pdf->Cell(122, 8, (string) $data['establecimiento_nombre'] . ' - Programa Fines 2', 0, 0, 'L');
        }

        $pdf->write2DBarcode($validationUrl, 'QRCODE,H', 164, 15, 28, 28);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetXY(162, 43);
        $pdf->Cell(32, 4, (string) ($data['clave'] ?? ''), 0, 0, 'C');
    }

    private function commonLogoPath(): ?string
    {
        $path = $this->config->rootPath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'constancias-header-logo.jpg';

        return is_file($path) ? $path : null;
    }

    private function alumnoRegularBody(array $data): string
    {
        $establecimiento = $this->pdfEscape((string) $data['establecimiento_nombre']);
        $localidad = $this->pdfEscape((string) ($data['localidad'] ?? 'La Plata'));
        $body = "
            <p>La Dirección de {$establecimiento} de {$localidad} hace constar por la presente que
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['apellidos'])}, {$this->pdfEscape($data['nombres'])}&nbsp;&nbsp;</i></u></strong>,
            DNI <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['numero_documento'])}&nbsp;&nbsp;</i></u></strong>,
            es alumno/a regular de <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['anio'])}&nbsp;&nbsp;</i></u></strong> año
            del <strong><u><i>{$this->pdfEscape($data['modalidad'])}</i></u></strong>, con orientación en
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['orientacion'])}&nbsp;&nbsp;</i></u></strong>, resolución
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['resolucion'])}&nbsp;&nbsp;</i></u></strong>.</p>
            <p>Se extiende la presente a pedido del interesado el día
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['fecha'])}&nbsp;&nbsp;</i></u></strong> para ser presentada ante
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['presentado'])}&nbsp;&nbsp;</i></u></strong>.</p>
        ";

        return $this->appendObservaciones($body, $data);
    }

    private function tituloTramiteBody(array $data): string
    {
        $establecimiento = $this->pdfEscape((string) $data['establecimiento_nombre']);
        $localidad = $this->pdfEscape((string) ($data['localidad'] ?? 'La Plata'));
        $body = "
            <p>La Dirección de {$establecimiento} de {$localidad} hace constar por la presente que
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['apellidos'])}, {$this->pdfEscape($data['nombres'])}&nbsp;&nbsp;</i></u></strong>,
            DNI <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['numero_documento'])}&nbsp;&nbsp;</i></u></strong>,
            tiene en trámite un CERTIFICADO ANALÍTICO DE ESTUDIOS <strong><u><i>&nbsp;&nbsp;COMPLETO&nbsp;&nbsp;</i></u></strong>
            de <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['anio'])}&nbsp;&nbsp;</i></u></strong> año
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['modalidad'])}&nbsp;&nbsp;</i></u></strong> con orientación en
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['orientacion'])}&nbsp;&nbsp;</i></u></strong>, resolución
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['resolucion'])}&nbsp;&nbsp;</i></u></strong>, adeudando
            <strong><u><i>&nbsp;&nbsp;Ninguna Materia&nbsp;&nbsp;</i></u></strong>.</p>
            <p>Se extiende la presente a pedido del interesado el día
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['fecha'])}&nbsp;&nbsp;</i></u></strong> para ser presentada ante
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['presentado'])}&nbsp;&nbsp;</i></u></strong>.</p>
        ";

        return $this->appendObservaciones($body, $data);
    }

    private function vacanteBody(array $data): string
    {
        $establecimiento = $this->pdfEscape((string) $data['establecimiento_nombre']);
        $localidad = $this->pdfEscape((string) ($data['localidad'] ?? 'La Plata'));
        $body = "
            <p>La Dirección de {$establecimiento} de {$localidad} hace constar por la presente que
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['apellidos'])}, {$this->pdfEscape($data['nombres'])}&nbsp;&nbsp;</i></u></strong>,
            DNI <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['numero_documento'])}&nbsp;&nbsp;</i></u></strong>,
            tiene una vacante para continuar sus estudios en este establecimiento.</p>
            <p>Se extiende la presente a pedido del interesado el día
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['fecha'])}&nbsp;&nbsp;</i></u></strong> para ser presentada ante
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['presentado'])}&nbsp;&nbsp;</i></u></strong>.</p>
        ";

        return $this->appendObservaciones($body, $data);
    }

    private function paseBody(array $data): string
    {
        $establecimiento = $this->pdfEscape((string) $data['establecimiento_nombre']);
        $localidad = $this->pdfEscape((string) ($data['localidad'] ?? 'La Plata'));

        $body = "
            <p>La Direcci&oacute;n de {$establecimiento} de {$localidad} deja constancia que
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['apellidos'])}, {$this->pdfEscape($data['nombres'])}&nbsp;&nbsp;</i></u></strong>,
            DNI <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['numero_documento'])}&nbsp;&nbsp;</i></u></strong>,
            ha cursado los a&ntilde;os <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['anio'])}&nbsp;&nbsp;</i></u></strong> del
            <strong><u><i>{$this->pdfEscape($data['modalidad'])}</i></u></strong>, orientaci&oacute;n
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['orientacion'])}&nbsp;&nbsp;</i></u></strong>, resoluci&oacute;n
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['resolucion'])}&nbsp;&nbsp;</i></u></strong> bajo el siguiente detalle:</p>
        ";

        if (($data['materias_aprobadas_html'] ?? '') !== '') {
            $body .= '<h3>Materias aprobadas</h3>' . (string) $data['materias_aprobadas_html'];
        }
        if (($data['materias_desaprobadas_html'] ?? '') !== '') {
            $body .= '<h3>Materias desaprobadas / pendientes</h3>' . (string) $data['materias_desaprobadas_html'];
        }

        $body .= "
            <p>Se extiende la presente a pedido del interesado el d&iacute;a
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['fecha'])}&nbsp;&nbsp;</i></u></strong> para ser presentada ante
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['presentado'])}&nbsp;&nbsp;</i></u></strong>.</p>
        ";

        return $this->appendObservaciones($body, $data);
    }

    private function generalBody(array $data): string
    {
        $establecimiento = $this->pdfEscape((string) $data['establecimiento_nombre']);
        $localidad = $this->pdfEscape((string) ($data['localidad'] ?? 'La Plata'));
        $texto = $this->formatUserText((string) ($data['texto'] ?? ''));

        return "
            <p>La Direcci&oacute;n del establecimiento {$establecimiento} del distrito de {$localidad}, Provincia de Buenos Aires, hace constar que
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['apellidos'])}, {$this->pdfEscape($data['nombres'])}&nbsp;&nbsp;</i></u></strong>,
            DNI <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['numero_documento'])}&nbsp;&nbsp;</i></u></strong>:</p>
            {$texto}
            <p>Se extiende la presente a pedido del interesado el d&iacute;a
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['fecha'])}&nbsp;&nbsp;</i></u></strong> para ser presentada ante
            <strong><u><i>&nbsp;&nbsp;{$this->pdfEscape($data['presentado'])}&nbsp;&nbsp;</i></u></strong>.</p>
        ";
    }

    private function appendObservaciones(string $body, array $data): string
    {
        if (($data['observaciones'] ?? '') !== '') {
            $body .= '<p>Observaciones: <strong><u><i>&nbsp;&nbsp;' . $this->pdfEscape($data['observaciones']) . '&nbsp;&nbsp;</i></u></strong></p>';
        }

        return $body;
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
            $pdf->Text(148, 114, 'Firma y sello');
            $pdf->Line(135, 118, 185, 118);
        }
    }

    private function sanitizeTableHtml(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'table[border|cellpadding|cellspacing|width],thead,tbody,tfoot,tr,th[colspan|rowspan],td[colspan|rowspan],p,br,strong,b,em,i,u');
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('Cache.DefinitionImpl', null);
        $purifier = new HTMLPurifier($config);
        $clean = trim($purifier->purify($html));

        if ($clean === '') {
            return '';
        }

        $clean = preg_replace('/<table\b[^>]*>/i', '<table border="1" cellpadding="3" cellspacing="0" width="100%">', $clean) ?? $clean;
        $clean = preg_replace('/<th\b([^>]*)>/i', '<th$1 style="font-weight:bold;">', $clean) ?? $clean;

        return $clean;
    }

    private function formatUserText(string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        return '<p><strong><u><i>&nbsp;&nbsp;' . nl2br($this->pdfEscape($text), false) . '&nbsp;&nbsp;</i></u></strong></p>';
    }

    private function storageFilePath(string $relativePath): ?string
    {
        if ($relativePath === '') {
            return null;
        }
        $path = $this->config->storagePath() . DIRECTORY_SEPARATOR . ltrim($relativePath, '/\\');
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

    private function pdfPath(int $id, string $dni, string $slug): array
    {
        $relativeDirectory = date('Y') . '/' . date('m');
        $safeDni = preg_replace('/[^0-9A-Za-z_-]/', '', $dni) ?: 'sin-dni';
        $safeSlug = preg_replace('/[^0-9A-Za-z_-]/', '', $slug) ?: 'constancia';
        $fileName = sprintf('constancia_%d_%s_%s.pdf', $id, $safeSlug, $safeDni);
        $relativePath = $relativeDirectory . '/' . $fileName;

        return [$this->config->storagePath() . DIRECTORY_SEPARATOR . $relativePath, $relativePath, $fileName];
    }

    private function validationUrl(int $id, string $clave): string
    {
        return $this->config->publicUrl() . '/validar-constancia?clave=' . rawurlencode($clave);
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

    private function isExpired(?array $constancia): bool
    {
        if ($constancia === null || empty($constancia['creado_en'])) {
            return false;
        }

        try {
            $createdAt = new \DateTimeImmutable((string) $constancia['creado_en']);
        } catch (\Throwable) {
            return false;
        }

        return $createdAt < (new \DateTimeImmutable())->sub(new \DateInterval('P30D'));
    }

    private function fechaActual(): string
    {
        $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        return date('j') . ' de ' . $months[(int) date('n') - 1] . ' de ' . date('Y');
    }

    private function pdfEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
