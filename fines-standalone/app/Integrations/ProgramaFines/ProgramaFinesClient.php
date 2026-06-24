<?php

declare(strict_types=1);

namespace FinesApp\Integrations\ProgramaFines;

final class ProgramaFinesClient
{
    private const BASE_URL = 'https://www.programafines.ar/inicial/';

    /** @var \CurlHandle */
    private $curl;

    public function __construct(
        private readonly string $sessionId,
        private readonly int $timeout = 20,
    ) {
        if (!function_exists('curl_init')) {
            throw new ProgramaFinesException('La extensión cURL de PHP no está habilitada.');
        }

        $this->curl = curl_init();
        curl_setopt_array($this->curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_ENCODING => '',
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/145 Safari/537.36',
            CURLOPT_COOKIE => 'PHPSESS=' . $this->sessionId,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
    }

    public function __destruct()
    {
        if ($this->curl instanceof \CurlHandle) {
            curl_close($this->curl);
        }
    }

    public function testConnection(): void
    {
        $html = $this->get('index4.php', ['a' => 7]);
        if (!$this->looksAuthenticated($html)) {
            throw new ProgramaFinesException('La sesión PHPSESS no es válida, venció o ProgramaFines solicitó iniciar sesión nuevamente.');
        }
    }

    /** @return array<int, array<string, mixed>> */
    public function students(string $pfid, int $periodo): array
    {
        $html = $this->get('index4.php', [
            'a' => 12,
            'nom_comision' => $pfid,
            'mi_periodo' => $periodo,
        ]);
        $this->assertAuthenticated($html);

        return $this->parseStudentList($html);
    }

    public function student(string $dni): array
    {
        $html = $this->get('index4.php', [
            'a' => 8,
            'b' => 1,
            'dni_cargar' => ProgramaFinesMapper::digits($dni),
        ]);
        $this->assertAuthenticated($html);

        $lower = mb_strtolower($html, 'UTF-8');
        $hasStudentForm = preg_match('/name=["\']apellido["\']/i', $html) === 1
            && preg_match('/name=["\']nombre["\']/i', $html) === 1;
        if (!$hasStudentForm
            || str_contains($lower, 'ingrese dni del estudiante')
            || str_contains($lower, 'dni del estudiante')) {
            throw new AlumnoNoExisteException('El alumno no existe en ProgramaFines.');
        }

        return $this->parseStudentForm($html);
    }

    public function updateStudent(array $data): void
    {
        $html = $this->post('index4.php?a=8&b=2', [
            'nombre' => $this->required($data, 'nombre'),
            'dni_cargar' => $this->required($data, 'dni_cargar'),
            'apellido' => (string) ($data['apellido'] ?? $data['nombre']),
            'mi_periodo' => (string) ($data['mi_periodo'] ?? ''),
            'cuil1' => (string) ($data['cuil1'] ?? '0'),
            'cuil2' => (string) ($data['cuil2'] ?? '0'),
            'nacionalidad' => (string) ($data['nacionalidad'] ?? 'Argentina'),
            'sexo' => (string) ($data['sexo'] ?? '2'),
            'dia_nac' => (string) ($data['dia_nac'] ?? '1'),
            'mes_nac' => (string) ($data['mes_nac'] ?? '1'),
            'ano_nac' => (string) ($data['ano_nac'] ?? '1999'),
            'cod_area' => (string) ($data['cod_area'] ?? ''),
            'nro_telefono' => (string) ($data['nro_telefono'] ?? ''),
            'direccion' => (string) ($data['direccion'] ?? ''),
        ]);
        $this->assertAuthenticated($html);
    }

    public function createStudent(array $data): void
    {
        $this->assertAuthenticated($this->get('index4.php', ['a' => 7]));

        $stepOne = $this->post('index4.php?a=7&b=1', [
            'nombre' => $this->required($data, 'nombre'),
            'dni_cargar' => $this->required($data, 'dni_cargar'),
            'subcategory' => $this->required($data, 'subcategory'),
            'mi_periodo' => (string) ($data['mi_periodo'] ?? ''),
            'apellido' => (string) ($data['apellido'] ?? $data['nombre']),
            'cuil1' => (string) ($data['cuil1'] ?? '0'),
            'cuil2' => (string) ($data['cuil2'] ?? '0'),
            'nacionalidad' => (string) ($data['nacionalidad'] ?? 'Argentina'),
            'sexo' => (string) ($data['sexo'] ?? '2'),
            'dia_nac' => (string) ($data['dia_nac'] ?? '1'),
            'mes_nac' => (string) ($data['mes_nac'] ?? '1'),
            'ano_nac' => (string) ($data['ano_nac'] ?? '1999'),
        ]);
        $this->assertAuthenticated($stepOne);

        $stepTwo = $this->post('index4.php?a=7&b=2', [
            'direccion' => (string) ($data['direccion'] ?? ''),
            'departamento' => (string) ($data['departamento'] ?? ''),
            'localidad' => (string) ($data['localidad'] ?? ''),
            'partido' => (string) ($data['partido'] ?? ''),
            'email' => (string) ($data['email'] ?? ''),
            'cod_area' => (string) ($data['cod_area'] ?? ''),
            'nro_telefono' => (string) ($data['nro_telefono'] ?? ''),
        ]);
        $this->assertAuthenticated($stepTwo);
    }

    public function transferStudent(string $dni, string $pfid): void
    {
        $this->assertAuthenticated($this->post('index4.php?a=22&b=1', [
            'dni_cargar' => ProgramaFinesMapper::digits($dni),
            'comision_destino' => $pfid,
        ]));
        $this->assertAuthenticated($this->post('index4.php?a=22&b=2', ['button' => 'Aceptar']));
    }

    public function removeStudent(string $pfid, int $periodo, string $dni): void
    {
        $dni = ProgramaFinesMapper::digits($dni);
        foreach ($this->students($pfid, $periodo) as $student) {
            if (ProgramaFinesMapper::digits((string) ($student['numero_documento'] ?? '')) !== $dni) {
                continue;
            }

            $path = (string) ($student['eliminar'] ?? '');
            if ($path === '') {
                throw new ProgramaFinesException('ProgramaFines no informó la acción para quitar al alumno.');
            }

            $this->assertAuthenticated($this->getRelative($path));
            return;
        }

        throw new ProgramaFinesException('El alumno ya no figura en esa comisión de ProgramaFines.');
    }

    private function get(string $path, array $params = []): string
    {
        $url = self::BASE_URL . ltrim($path, '/');
        if ($params !== []) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($params);
        }

        return $this->request($url);
    }

    private function getRelative(string $path): string
    {
        $path = html_entity_decode($path, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (preg_match('#^https?://#i', $path) === 1) {
            $parts = parse_url($path);
            $host = strtolower((string) ($parts['host'] ?? ''));
            if (!in_array($host, ['programafines.ar', 'www.programafines.ar'], true)) {
                throw new ProgramaFinesException('ProgramaFines devolvió una URL externa no permitida.');
            }
            return $this->request($path);
        }

        return $this->get($path);
    }

    private function post(string $path, array $data): string
    {
        return $this->request(self::BASE_URL . ltrim($path, '/'), $data);
    }

    private function request(string $url, ?array $postData = null): string
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, []);
        if ($postData === null) {
            curl_setopt($this->curl, CURLOPT_HTTPGET, true);
            curl_setopt($this->curl, CURLOPT_POST, false);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, null);
        } else {
            curl_setopt($this->curl, CURLOPT_POST, true);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        }

        $body = curl_exec($this->curl);
        $status = (int) curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $error = curl_error($this->curl);

        if (!is_string($body)) {
            throw new ProgramaFinesException($error !== '' ? $error : 'ProgramaFines no devolvió una respuesta.');
        }
        if ($status < 200 || $status >= 400) {
            throw new ProgramaFinesException("ProgramaFines respondió HTTP {$status}.");
        }

        return $this->toUtf8($body);
    }

    private function assertAuthenticated(string $html): void
    {
        if (!$this->looksAuthenticated($html)) {
            throw new ProgramaFinesException('La sesión de ProgramaFines venció. Volvé a cargar la cookie PHPSESS.');
        }
    }

    private function looksAuthenticated(string $html): bool
    {
        $lower = mb_strtolower(strip_tags($html), 'UTF-8');
        $hasLogin = preg_match('/type=["\']password["\']/i', $html) === 1
            || (str_contains($lower, 'iniciar sesión') && str_contains($lower, 'usuario'));
        return trim($html) !== '' && !$hasLogin;
    }

    /** @return array<int, array<string, mixed>> */
    private function parseStudentList(string $html): array
    {
        $result = [];
        $blocks = preg_split('/<h2[^>]*>/i', $html) ?: [];

        foreach ($blocks as $block) {
            $plain = trim(html_entity_decode(strip_tags($block), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if (preg_match('/^(\d+)\s+(.+?)\s+DNI\s+(\d+)/su', $plain, $matches) !== 1) {
                continue;
            }

            $student = [
                'posicion' => $matches[1],
                'nombre' => trim($matches[2]),
                'numero_documento' => $matches[3],
                'fecha_nacimiento' => null,
                'email' => null,
                'telefono' => null,
                'historial' => null,
                'cambiar_comision' => null,
                'eliminar' => null,
                'modificar' => null,
            ];

            if (preg_match('/Fecha\s+Nacimiento:\s*(.*?)\s+Email:\s*([^\r\n<]*)/isu', $block, $matches) === 1) {
                $student['fecha_nacimiento'] = trim(strip_tags($matches[1]));
                $email = trim(strip_tags($matches[2]));
                $student['email'] = str_contains($email, '@') ? $email : null;
            }
            if (preg_match('/Tel(?:é|Ã©)fono:\s*([^<\r\n]+)/iu', $block, $matches) === 1) {
                $phone = trim($matches[1]);
                $student['telefono'] = strlen($phone) > 6 ? $phone : null;
            }

            $links = [
                'historial' => '/href=["\']([^"\']*a=15[^"\']*)/i',
                'cambiar_comision' => '/href=["\']([^"\']*a=12(?:&amp;|&)b=2[^"\']*)/i',
                'eliminar' => '/href=["\']([^"\']*a=12(?:&amp;|&)b=1[^"\']*)/i',
                'modificar' => '/href=["\']([^"\']*a=8[^"\']*)/i',
            ];
            foreach ($links as $key => $pattern) {
                if (preg_match($pattern, $block, $matches) === 1) {
                    $student[$key] = html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }
            }

            $result[] = $student;
        }

        return $result;
    }

    private function parseStudentForm(string $html): array
    {
        $data = [
            'apellido' => null,
            'nombre' => null,
            'dni' => null,
            'cuil1' => null,
            'cuil2' => null,
            'sexo' => null,
            'dia_nac' => null,
            'mes_nac' => null,
            'ano_nac' => null,
            'direccion' => null,
            'departamento' => null,
            'localidad' => null,
            'partido' => null,
            'email' => null,
            'nacionalidad' => null,
            'cod_area' => null,
            'nro_telefono' => null,
        ];

        foreach (['apellido', 'nombre', 'cuil1', 'cuil2', 'direccion', 'departamento', 'localidad', 'partido', 'email', 'cod_area', 'nro_telefono'] as $field) {
            if (preg_match('/name=["\']' . preg_quote($field, '/') . '["\'][^>]*value=["\']([^"\']*)/i', $html, $matches) === 1) {
                $data[$field] = $this->decode($matches[1]);
            }
        }
        if (preg_match('/-\s*(\d{6,9})\s*-/u', strip_tags($html), $matches) === 1) {
            $data['dni'] = $matches[1];
        }
        if (preg_match('/name=["\']sexo["\'][^>]*value=["\'](\d+)["\'][^>]*checked/i', $html, $matches) === 1
            || preg_match('/value=["\'](\d+)["\'][^>]*name=["\']sexo["\'][^>]*checked/i', $html, $matches) === 1) {
            $data['sexo'] = $matches[1];
        }
        foreach (['dia_nac', 'mes_nac', 'ano_nac', 'nacionalidad'] as $field) {
            if (preg_match('/name=["\']' . preg_quote($field, '/') . '["\'][^>]*>.*?<option[^>]*value=["\']([^"\']+)["\'][^>]*selected/is', $html, $matches) === 1) {
                $data[$field] = $this->decode($matches[1]);
            }
        }

        return $data;
    }

    private function required(array $data, string $key): string
    {
        $value = trim((string) ($data[$key] ?? ''));
        if ($value === '') {
            throw new ProgramaFinesException("Falta el dato obligatorio {$key} para ProgramaFines.");
        }
        return $value;
    }

    private function decode(string $value): string
    {
        return trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    private function toUtf8(string $body): string
    {
        if (mb_check_encoding($body, 'UTF-8')) {
            return $body;
        }

        return mb_convert_encoding($body, 'UTF-8', 'ISO-8859-1');
    }
}
