<?php

class ProgramaFines
{
    /**
     * Inicia sesión en el sistema ProgramaFines
     * 
     * @param object $config Objeto de configuración con pfUser y pfPassword
     * @return object Cliente con la sesión iniciada
     */
    public static function PF_Login($pfUser, $pfPassword)
    {
        // Inicializar cURL
        $client = curl_init();
        
        $cookieFile = tempnam(TEMP_PATH, "cookie");
        curl_setopt($client, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($client, CURLOPT_COOKIEFILE, $cookieFile);

        // Configurar opciones de cURL para mantener cookies
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_FOLLOWLOCATION, true);
        
        // Primera solicitud GET para obtener cookies iniciales
        curl_setopt($client, CURLOPT_URL, "https://www.programafines.ar/");
        $response = curl_exec($client);
        
        if ($response === false) {
            throw new Exception("Error en la solicitud inicial: " . curl_error($client));
        }
        
        // Preparar datos del formulario para el inicio de sesión
        $formData = [
            'usuario' => $pfUser,
            'password' => $pfPassword,
            'button' => 'Entrar'
        ];
        
        
        // Configurar la solicitud POST para el inicio de sesión
        curl_setopt($client, CURLOPT_URL, "https://www.programafines.ar/validar.php");
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($formData));
        
        $response = curl_exec($client);
        
        if ($response === false) {
            throw new Exception("Error de inicio de sesión: " . curl_error($client));
        }
        
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error de inicio de sesión: Código HTTP " . $httpCode);
        }
        
        return $client;
    }
    
    /**
     * Obtiene información de la lista de alumnos de una comisión
     * 
     * @param object $client Cliente con sesión iniciada
     * @param string $pfid ID de la comisión
     * @return array Lista de alumnos
     */
    public static function PF_InfoListaAlumnos($client, $pfid)
    {

        $url = "https://www.programafines.ar/inicial/index4.php?a=12&&nom_comision={$pfid}&mi_periodo=4";

        echo $url;
        curl_setopt($client, CURLOPT_URL, $url);
        curl_setopt($client, CURLOPT_HTTPGET, true);
        
        $response = curl_exec($client);
        
        if ($response === false) {
            throw new Exception("Error al obtener lista de alumnos: " . curl_error($client));
        }
        
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
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
    
    

    /**
     * Obtiene información del formulario de modificación de un alumno
     * 
     * @param object $client Cliente con sesión iniciada
     * @param string $dni DNI del alumno
     * @return array Datos del formulario
     */
    public static function PF_InfoAlumnoFormularioModificacion($client, $dni)
    {
        $formData = [
            'dni_cargar' => $dni
        ];
        
        curl_setopt($client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=8&b=1");
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($formData));
        
        $response = curl_exec($client);
        
        if ($response === false) {
            throw new Exception("Error al obtener formulario de alumno: " . curl_error($client));
        }
        
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error al obtener formulario de alumno: Código HTTP " . $httpCode);
        }
        
        // Cargar el HTML con DOMDocument
        $doc = new DOMDocument();
        @$doc->loadHTML($response);
        
        $xpath = new DOMXPath($doc);
        
        $inputFields = [];
        $radioGroups = [];
        
        // Procesar inputs
        $inputs = $xpath->query("//form//input");
        foreach ($inputs as $input) {
            $type = $input->getAttribute('type');
            $name = $input->getAttribute('name');
            $value = $input->getAttribute('value');
            
            if (empty($name)) {
                continue;
            }
            
            if (strtolower($type) === "radio") {
                if ($input->hasAttribute('checked')) {
                    $radioGroups[$name] = $value;
                } elseif (!isset($radioGroups[$name])) {
                    $radioGroups[$name] = '';
                }
            } else {
                $inputFields[$name] = html_entity_decode($value);
            }
        }
        
        // Añadir los grupos de radio buttons al resultado final
        foreach ($radioGroups as $name => $value) {
            $inputFields[$name] = $value;
        }
        
        // Procesar selects
        $selects = $xpath->query("//form//select");
        foreach ($selects as $select) {
            $name = $select->getAttribute('name');
            
            if (!empty($name)) {
                $selectedOption = $xpath->query(".//option[@selected]", $select)->item(0);
                
                if ($selectedOption) {
                    $value = $selectedOption->getAttribute('value');
                } else {
                    $firstOption = $xpath->query(".//option", $select)->item(0);
                    $value = $firstOption ? $firstOption->getAttribute('value') : '';
                }
                
                $inputFields[$name] = $value;
            }
        }
        
        return $inputFields;
    }
    
    /**
     * Actualiza los datos de un alumno
     * 
     * @param object $client Cliente con sesión iniciada
     * @param array $formData Datos del formulario
     * @return void
     */
    public static function PF_ActualizarFormularioAlumno($client, $formData)
    {
        curl_setopt($client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=8&b=2");
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($formData));
        
        $response = curl_exec($client);
        
        if ($response === false) {
            throw new Exception("Error al actualizar formulario: " . curl_error($client));
        }
        
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error al actualizar formulario: Código HTTP " . $httpCode);
        }
    }
    
    /**
     * Inscribe un estudiante utilizando valores de un objeto persona
     * 
     * @param object $client Cliente con sesión iniciada
     * @param string $comisionPfid ID de la comisión
     * @param object $persona Objeto con datos de la persona
     * @return void
     */
    public static function PF_InscribirEstudianteValues($client, $comisionPfid, $persona)
    {
        $dataForm = [
            'nombre' => isset($persona->nombres) ? $persona->nombres : '',
            'apellido' => isset($persona->apellidos) ? $persona->apellidos : '',
            'cuil1' => isset($persona->cuil1) ? $persona->cuil1 : '',
            'dni_cargar' => isset($persona->numero_documento) ? $persona->numero_documento : '',
            'cuil2' => isset($persona->cuil2) ? $persona->cuil2 : '',
            'direccion' => isset($persona->descripcion_domicilio) ? $persona->descripcion_domicilio : '',
            'departamento' => isset($persona->departamento) ? $persona->departamento : '',
            'localidad' => isset($persona->localidad) ? $persona->localidad : '',
            'partido' => isset($persona->partido) ? $persona->partido : '',
            'email' => isset($persona->email) ? $persona->email : '',
            'cod_area' => isset($persona->codigo_area) ? $persona->codigo_area : '',
            'telefono' => isset($persona->telefono) ? $persona->telefono : '',
            'nacionalidad' => isset($persona->nacionalidad) ? $persona->nacionalidad : 'Argentina',
            'sexo' => isset($persona->sexo) ? $persona->sexo : '1',
            'subcategory' => $comisionPfid
        ];
        
        self::PF_InscribirEstudiante($client, $dataForm);
    }
    
    /**
     * Inscribe un estudiante utilizando datos de formulario
     * 
     * @param object $client Cliente con sesión iniciada
     * @param array $formData Datos del formulario
     * @return void
     */
    public static function PF_InscribirEstudiante($client, $formData)
    {
        // Primera solicitud POST
        curl_setopt($client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=7&b=1");
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($formData));
        
        $response = curl_exec($client);
        
        if ($response === false) {
            throw new Exception("Error al inscribir estudiante (paso 1): " . curl_error($client));
        }
        
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error al inscribir estudiante (paso 1): Código HTTP " . $httpCode);
        }
        
        // Segunda solicitud POST
        curl_setopt($client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=7&b=2");
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($formData));
        
        $response = curl_exec($client);
        
        if ($response === false) {
            throw new Exception("Error al inscribir estudiante (paso 2): " . curl_error($client));
        }
        
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error al inscribir estudiante (paso 2): Código HTTP " . $httpCode);
        }
        
        if (strpos($response, "existe un estudiante con ese dni en esta") !== false) {
            throw new Exception("Estudiante en otra comisión, revisar desde PF");
        }
    }
    
    /**
     * Cambia un estudiante de comisión
     * 
     * @param object $client Cliente con sesión iniciada
     * @param string $dni DNI del estudiante
     * @param string $comisionDestino ID de la comisión destino
     * @return void
     */
    public static function PF_CambiarComision($client, $dni, $comisionDestino)
    {
        $formData = [
            'dni_cargar' => $dni,
            'comision_destino' => $comisionDestino
        ];
        
        // Primera solicitud POST
        curl_setopt($client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=22&b=1");
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($formData));
        
        $response = curl_exec($client);
        
        if ($response === false) {
            throw new Exception("Error al cambiar comisión (paso 1): " . curl_error($client));
        }
        
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error al cambiar comisión (paso 1): Código HTTP " . $httpCode);
        }
        
        // Segunda solicitud POST
        curl_setopt($client, CURLOPT_URL, "https://www.programafines.ar/inicial/index4.php?a=22&b=2");
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($formData));
        
        $response = curl_exec($client);
        
        if ($response === false) {
            throw new Exception("Error al cambiar comisión (paso 2): " . curl_error($client));
        }
        
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Error al cambiar comisión (paso 2): Código HTTP " . $httpCode);
        }
        
        if (strpos($response, "existe un estudiante con ese dni en esta") !== false) {
            throw new Exception("Estudiante en otra comisión, revisar desde PF");
        }
    }
    
    /**
     * Cierra la conexión del cliente
     * 
     * @param object $client Cliente con sesión iniciada
     * @return void
     */
    public static function Dispose($client)
    {
        curl_close($client);
    }
}
?>