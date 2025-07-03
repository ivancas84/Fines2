<?php

namespace ProgramaFines;

use Exception;
use DateTime;

class PfUtils
{
    private $client;

    public static function parseFirstColumnCalificacionPF($inputString) {

        // Eliminar el número al principio (hasta el primer espacio)
        $spacePos = strpos($inputString, ' ');
        if ($spacePos === false) return null; // línea no válida

        $inputString = substr($inputString, $spacePos + 1); // todo después del primer espacio

        // Separar por "DNI"
        $parts = explode('DNI', $inputString);
        if (count($parts) !== 2) return null; // no contiene DNI

        $namePart = trim($parts[0]);
        $dniPart = trim($parts[1]);

        // Obtener número de documento
        if (!is_numeric($dniPart)) return null;

        // Separar apellidos y nombres por la coma
        $nameSplit = explode(',', $namePart);
        if (count($nameSplit) !== 2) return null;

        $apellidos = trim($nameSplit[0]);
        $nombres = trim($nameSplit[1]);

        return [
            'apellidos' => $apellidos,
            'nombres' => $nombres,
            'numero_documento' => $dniPart
        ];
    }

    /**
     * parsear un elemnto de la planilla de calificaciones del programa fines para dividir el contenido en
     * nombres, apellidos y nota
     */
    public static function parseRowCalificacionPF($row)
    {
        $data = array();
        foreach($row as $key => $value) {
            if(str_contains($key, "Nombre")){
                $data = array_merge($data, self::parseFirstColumnCalificacionPF($value));
            } else if(str_contains($key, "Final")){
                $value = intval(trim($value));
                if($value < 7){
                    throw new Exception("Calificación vacía o menor a 7");
                }

                $data["nota"] = $value;

            }
        }

        if(empty($data["nombres"]) || empty($data["apellidos"]) || empty($data["calificacion"])){
            throw new Exception("Datos incompletos en la fila.");
        }

        return $data;

    }

    
    

    public function __construct($pfUser, $pfPassword)
    {
        $this->client = curl_init();

        $cookieFile = tempnam(sys_get_temp_dir(), "cookie");
        curl_setopt($this->client, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($this->client, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->client, CURLOPT_FOLLOWLOCATION, true);

        // Obtener cookies iniciales
        curl_setopt($this->client, CURLOPT_URL, "https://www.programafines.ar/");
        $response = curl_exec($this->client);

        if ($response === false) {
            throw new Exception("Error en la solicitud inicial: " . curl_error($this->client));
        }

        // Login
        $formData = [
            'usuario' => $pfUser,
            'password' => $pfPassword,
            'button' => 'Entrar'
        ];

        curl_setopt($this->client, CURLOPT_URL, "https://www.programafines.ar/validar.php");
        curl_setopt($this->client, CURLOPT_POST, true);
        curl_setopt($this->client, CURLOPT_POSTFIELDS, http_build_query($formData));

        $response = curl_exec($this->client);

        if ($response === false) {
            throw new Exception("Error de inicio de sesión: " . curl_error($this->client));
        }

        $httpCode = curl_getinfo($this->client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error de inicio de sesión: Código HTTP " . $httpCode);
        }
    }

    public function infoListaAlumnos($pfid)
    {
        $url = "https://www.programafines.ar/inicial/index4.php?a=12&&nom_comision={$pfid}&mi_periodo=4";

        curl_setopt($this->client, CURLOPT_URL, $url);
        curl_setopt($this->client, CURLOPT_HTTPGET, true);

        $response = curl_exec($this->client);

        if ($response === false) {
            throw new Exception("Error al obtener lista de alumnos: " . curl_error($this->client));
        }

        $httpCode = curl_getinfo($this->client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error al obtener lista de alumnos: Código HTTP " . $httpCode);
        }

        // Eliminar toda la primera parte del html hasta '<h2 align="left">'.
        // Cada alumno está delimitado por el string '<h2 align="left">'
       
        $data = explode("<h2 align='left'>", $response);
        unset($data[0]);

        // Reset indexes
        $data = array_values($data);
        $students = [];

        foreach ($data as $block) {
            $student = [];

            // Extraer nombre completo y DNI
            if (preg_match("/^\d+\s+(.+?)\s{2,}(.+?)\s+DNI\s+(\d+)/", $block, $matches)) {
                $student['apellidos'] = trim($matches[1]);
                $student['nombres'] = trim($matches[2]);
                $student['dni'] = $matches[3];
            }

            // Fecha de nacimiento
            if (preg_match("/Fecha Nacimiento:\s*([\d\/]*)/", $block, $matches)) {
                $fecha_raw = trim($matches[1]);
                $partes = explode('/', $fecha_raw);
            
                if (count($partes) === 3) {
                    $dia = intval($partes[0]);
                    $mes = intval($partes[1]);
                    $anio = intval($partes[2]);
            
                    // Crear fecha usando DateTime
                    $fecha_obj = DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $anio, $mes, $dia));
                    $student['fecha_nacimiento'] = $fecha_obj ? $fecha_obj->format('Y-m-d') : null;
                } else {
                    $student['fecha_nacimiento'] = null;
                }
            }

            // Dirección
            if (preg_match("/Dirección:\s*(.*?)<br>/", $block, $matches)) {
                $student['direccion'] = trim($matches[1]);
            }

            // Teléfono
            if (preg_match("/Teléfono:\s*(.*?)<br>/", $block, $matches)) {
                $student['telefono'] = trim($matches[1]);
                if(strlen($student['telefono']) < 6) {
                    $student['telefono'] = null;
                }
            }

            // Email (puede estar vacío)
            if (preg_match("/Email:\s*(.*?)<br>/", $block, $matches)) {
                $student['email'] = trim($matches[1]);
            }

            $students[] = $student;
        }

        return $students;
    }

    public function infoAlumnoFormularioModificacion($dni)
    {
        $formData = ['dni_cargar' => $dni];

        curl_setopt($this->client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=8&b=1");
        curl_setopt($this->client, CURLOPT_POST, true);
        curl_setopt($this->client, CURLOPT_POSTFIELDS, http_build_query($formData));

        $response = curl_exec($this->client);

        if ($response === false) {
            throw new Exception("Error al obtener formulario de alumno: " . curl_error($this->client));
        }

        $httpCode = curl_getinfo($this->client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error al obtener formulario de alumno: Código HTTP " . $httpCode);
        }

        // ... (copiar el parseo DOMDocument igual que ya lo tienes)
    }

    public function actualizarFormularioAlumno($formData)
    {
        curl_setopt($this->client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=8&b=2");
        curl_setopt($this->client, CURLOPT_POST, true);
        curl_setopt($this->client, CURLOPT_POSTFIELDS, http_build_query($formData));

        $response = curl_exec($this->client);

        if ($response === false) {
            throw new Exception("Error al actualizar formulario: " . curl_error($this->client));
        }

        $httpCode = curl_getinfo($this->client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error al actualizar formulario: Código HTTP " . $httpCode);
        }
    }

    public function inscribirEstudianteValues($comisionPfid, $persona)
    {
        $dataForm = [
            'nombre' => $persona->nombres ?? '',
            'apellido' => $persona->apellidos ?? '',
            'cuil1' => $persona->cuil1 ?? '',
            'dni_cargar' => $persona->numero_documento ?? '',
            'cuil2' => $persona->cuil2 ?? '',
            'direccion' => $persona->descripcion_domicilio ?? '',
            'departamento' => $persona->departamento ?? '',
            'localidad' => $persona->localidad ?? '',
            'partido' => $persona->partido ?? '',
            'email' => $persona->email ?? '',
            'cod_area' => $persona->codigo_area ?? '',
            'telefono' => $persona->telefono ?? '',
            'nacionalidad' => $persona->nacionalidad ?? 'Argentina',
            'sexo' => $persona->sexo ?? '1',
            'subcategory' => $comisionPfid
        ];

        $this->inscribirEstudiante($dataForm);
    }

    public function inscribirEstudiante($formData)
    {
        curl_setopt($this->client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=7&b=1");
        curl_setopt($this->client, CURLOPT_POST, true);
        curl_setopt($this->client, CURLOPT_POSTFIELDS, http_build_query($formData));
        $response = curl_exec($this->client);

        if ($response === false) {
            throw new Exception("Error al inscribir estudiante (paso 1): " . curl_error($this->client));
        }

        curl_setopt($this->client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=7&b=2");
        $response = curl_exec($this->client);

        if ($response === false) {
            throw new Exception("Error al inscribir estudiante (paso 2): " . curl_error($this->client));
        }

        if (strpos($response, "existe un estudiante con ese dni en esta") !== false) {
            throw new Exception("Estudiante en otra comisión, revisar desde PF");
        }
    }

    public function cambiarComision($dni, $comisionDestino)
    {
        $formData = [
            'dni_cargar' => $dni,
            'comision_destino' => $comisionDestino
        ];

        curl_setopt($this->client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=22&b=1");
        curl_setopt($this->client, CURLOPT_POST, true);
        curl_setopt($this->client, CURLOPT_POSTFIELDS, http_build_query($formData));
        curl_exec($this->client);

        curl_setopt($this->client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=22&b=2");
        curl_exec($this->client);

        if (strpos($response, "existe un estudiante con ese dni en esta") !== false) {
            throw new Exception("Estudiante en otra comisión, revisar desde PF");
        }
    }

    public function dispose()
    {
        curl_close($this->client);
    }
}
