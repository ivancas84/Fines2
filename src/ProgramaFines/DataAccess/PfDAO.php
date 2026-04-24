<?php

namespace ProgramaFines\DataAccess;

use Exception;
use DateTime;

class AlumnoNoExisteException extends Exception {}

class PfDAO
{
    private $client;
    private $sessionId;

    public function __construct(string $sessionId)
    {
        $this->sessionId = $sessionId;

        $this->client = curl_init();

        curl_setopt_array($this->client, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/145 Safari/537.36",
            CURLOPT_COOKIE => "PHPSESS={$this->sessionId}"
        ]);
    }

    private function request(string $url, array $headers = [], ?array $postData = null)
    {
        curl_setopt($this->client, CURLOPT_URL, $url);

        if ($postData !== null) {
            curl_setopt($this->client, CURLOPT_POST, true);
            curl_setopt($this->client, CURLOPT_POSTFIELDS, http_build_query($postData));
        } else {
            curl_setopt($this->client, CURLOPT_POST, false);
            curl_setopt($this->client, CURLOPT_HTTPGET, true);
        }

        if (!empty($headers)) {
            curl_setopt($this->client, CURLOPT_HTTPHEADER, $headers);
        }

        return curl_exec($this->client);
    }





    

    /* =========================
       GET genérico
       ========================= */

    public function getPage(string $url, array $params = [])
    {
        if (!empty($params)) {
            $url .= (strpos($url,'?')===false?'?':'&') . http_build_query($params);
        }

        return $this->request($url);
    }

    public function getSubCategorias(int $categoryId)
    {
        return $this->request(
            "https://www.programafines.ar/inicial/subcategorias5.php",
            [
                "Accept: */*",
                "Accept-Language: es-419,es;q=0.9",
                "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
                "Origin: https://www.programafines.ar",
                "X-Requested-With: XMLHttpRequest"
            ],
            [
                "id_category" => $categoryId
            ]
        );
    }

    /**
     * @param string $pfid comision
     * @param string $periodo 6 = 2026-1
     * @return array<int, array<string,mixed>> 
     */
    public function getListaAlumnos($pfid, $periodo = 6): array{
        $htmlListaAlumnos = $this->getPage(
            "https://www.programafines.ar/inicial/index4.php",
            [
                "a" => 12,
                "nom_comision" => $pfid,
                "mi_periodo" => $periodo
            ]
        );

        return $this->parseListaAlumnos($htmlListaAlumnos);
    }

    protected function parseListaAlumnos($html)
    {
        $result = [];

        // Separar por cada alumno
        $bloques = preg_split('/<h2[^>]*>/', $html);

        foreach ($bloques as $bloque) {
            $bloque = trim($bloque);
            if ($bloque === '') continue;

            $alumno = [];

            // POSICION + NOMBRE + DNI
            if (preg_match('/^(\d+)\s+(.+?)\s+DNI\s+(\d+)/s', $bloque, $m)) {
                $alumno['posicion'] = $m[1];
                $alumno['nombre']   = trim($m[2]);
                $alumno['numero_documento']      = $m[3];
            } else {
                continue; // si no matchea, no es un alumno
            }

            //inicializar campos opcionales
            $alumno["fecha_nacimiento"] = null;
            $alumno["email"] = null;
            $alumno["telefono"] = null;
            $alumno["historial"] = null;
            $alumno["cambiar_comision"] = null;
            $alumno["eliminar"] = null;
            $alumno["modificar"] = null;

            // Fecha nacimiento + email
            if (preg_match('/Fecha Nacimiento:\s*([^E<]+)\s*Email:\s*([^<]*)/s', $bloque, $m)) {
                $alumno['fecha_nacimiento'] = trim($m[1]);
                if (strpos($m[2], '@') !== false) {
                    $alumno['email'] = trim($m[2]);
                }
            }

            // Teléfono
            if (preg_match('/Teléfono:\s*([^<]+)/', $bloque, $m) && strlen($m[1])> 6) {
                $alumno['telefono'] = trim($m[1]);
            }

            // HISTORIAL
            if (preg_match('/href="([^"]*a=15[^"]*)"/', $bloque, $m)) {
                $alumno["historial"] = html_entity_decode($m[1]);
            }

            // CAMBIAR COMISION (a=12 & b=2)
            if (preg_match('/href="([^"]*a=12(?:&amp;|&)b=2[^"]*)"/', $bloque, $m)) {
                $alumno["cambiar_comision"] = html_entity_decode($m[1]);
            }

            // ELIMINAR (a=12 & b=1)
            if (preg_match('/href="([^"]*a=12(?:&amp;|&)b=1[^"]*)"/', $bloque, $m)) {
                $alumno["eliminar"] = html_entity_decode($m[1]);
            }

            // MODIFICAR (a=8)
            if (preg_match('/href="([^"]*a=8[^"]*)"/', $bloque, $m)) {
                $alumno["modificar"] = html_entity_decode($m[1]);
            }

            $result[] = $alumno;
        }

  
        return $result;
    }


 


    public function openFormModificarAlumno(string $dni){
        $html =  $this->getPage(
            "https://www.programafines.ar/inicial/index4.php?a=8&b=1",
            ["dni_cargar" => $dni]
        );

        $htmlLower =  strtolower($html);
        $noExisteAlumno = str_contains($htmlLower, 'ingrese dni del estudiante') ||
                   str_contains($htmlLower, 'dni del estudiante') ||
                   !str_contains($htmlLower, 'datos necesarios para la inscripción');
        if($noExisteAlumno) throw new AlumnoNoExisteException("No existe el alumno, no se puede modificar");
        return $this->parseFormModificarAlumno($html);
    }

    /**
     * @example Similar a sendDataForm1AgregarAlumno pero no tiene "subcategory" ni "dni_cargar"
     * (Si se manda dni_cargar de todas formas, permitirá modificar el dni?)
     * apellido PEREZ
     * nombre JUAN PABLO
     * cuil1 0
     * cuil2 0
     * nacionalidad Argentina
     * sexo 2 (1 Masculino - 2 Femenino - 3 No Binario)
     * dia_nac 7
     * mes_nac 6
     * ano_nac 1997
     * mi_periodo 6
     * cod_area 221
     * nro_telefono 4556677
     * direccion 4556677
     */
    public function sendDataFormModificarAlumno(array $data)
    {
        return $this->request(
            "https://www.programafines.ar/inicial/index4.php?a=8&b=2",
            [],
            [
                "nombre"=> $data["nombre"] ?? throw new Exception("No está definido el nombre"),
                "dni_cargar" => $data["dni_cargar"] ?? throw new Exception("El dni no se encuentra definido"),
                "apellido"=> $data["apellido"] ?? $data["nombre"],
                "mi_periodo" => $data["mi_periodo"] ?? PF_PERIODO,
                "cuil1" => $data["cuil1"] ?? "0",
                "cuil2" => $data["cuil2"] ?? "0",
                "nacionalidad" => $data["nacionalidad"] ?? "Argentina",
                "sexo" => $data["sexo"] ?? "2",
                "dia_nac" => $data["dia_nac"] ?? "1",
                "mes_nac" => $data["mes_nac"] ?? "2",
                "ano_nac" => $data["ano_nac"] ?? "1999",
                "cod_area" => $data["cod_area"] ?? "221", 
                "nro_telefono" => $data["nro_telefono"] ?? "", 
                "direccion" => $data["direccion"] ?? ""                
            ]
        );
    } 

    protected function parseFormModificarAlumno($html): array
    {
        $data = [
            'apellido'      => null,
            'nombre'        => null,
            'dni'           => null,
            'cuil1'         => null,
            'cuil2'         => null,
            'sexo'          => null,        // 1 = Masculino, 2 = Femenino, 3 = No Binario
            'dia_nac'       => null,
            'mes_nac'       => null,
            'ano_nac'       => null,
            'direccion'     => null,
            'departamento'  => null,
            'localidad'     => null,
            'partido'       => null,
            'email'         => null,
            'nacionalidad'  => null,
            'cod_area'      => null,
            'nro_telefono'  => null,
        ];

        // Apellido y Nombre
        if (preg_match('/name="apellido"[^>]*value="([^"]*)"/i', $html, $m)) {
            $data['apellido'] = html_entity_decode(trim($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if (preg_match('/name="nombre"[^>]*value="([^"]*)"/i', $html, $m)) {
            $data['nombre'] = html_entity_decode(trim($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        // CUIL (partes) y DNI (el número que está entre los dos inputs)
        if (preg_match('/name="cuil1"[^>]*value="([^"]*)"/i', $html, $m)) {
            $data['cuil1'] = trim($m[1]);
        }
        if (preg_match('/-\s*(\d{6,9})\s*-/', $html, $m)) {   // DNI central
            $data['dni'] = $m[1];
        }
        if (preg_match('/name="cuil2"[^>]*value="([^"]*)"/i', $html, $m)) {
            $data['cuil2'] = trim($m[1]);
        }

        // Sexo (radio con checked)
        if (preg_match('/name="sexo"[^>]*value="(\d+)"[^>]*checked/i', $html, $m)) {
            $data['sexo'] = (int)$m[1];
        }

        // Fecha de nacimiento (selects)
        if (preg_match('/name="dia_nac"[^>]*>.*?<option[^>]*value="(\d+)"\s*selected="selected"/is', $html, $m)) {
            $data['dia_nac'] = (int)$m[1];
        }
        if (preg_match('/name="mes_nac"[^>]*>.*?<option[^>]*value="(\d+)"\s*selected="selected"/is', $html, $m)) {
            $data['mes_nac'] = (int)$m[1];
        }
        if (preg_match('/name="ano_nac"[^>]*>.*?<option[^>]*value="(\d+)"\s*selected="selected"/is', $html, $m)) {
            $data['ano_nac'] = (int)$m[1];
        }

        // Campos de texto simples
        $camposTexto = ['direccion', 'departamento', 'localidad', 'partido', 'email', 'cod_area', 'nro_telefono'];
        foreach ($camposTexto as $campo) {
            if (preg_match('/name="' . preg_quote($campo, '/') . '"[^>]*value="([^"]*)"/i', $html, $m)) {
                $valor = trim($m[1]);
                $data[$campo] = ($valor !== '') ? html_entity_decode($valor, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;
            }
        }

        // Nacionalidad (select)
        if (preg_match('/name="nacionalidad"[^>]*>.*?<option[^>]*value="([^"]+)"\s*selected="selected"/is', $html, $m)) {
            $data['nacionalidad'] = html_entity_decode(trim($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $data;
    }



    /**
     * Pantalla raiz para agregar alumno a una comision
     * 
     * 
     */
    public function openFormAgregarAlumno()
    {
        return $this->getPage(
            "https://www.programafines.ar/inicial/index4.php",
            ["a" => 7]
        );
    }

   

    
    
        /**
         * @example 
         * apellido PEREZ
         * nombre JUAN PABLO
         * cuil1 0
         * dni_cargar 31234567
         * cuil2 0
         * nacionalidad Argentina
         * sexo 2
         * dia_nac 7
         * mes_nac 6
         * ano_nac 1997
         * mi_periodo 6
         * subcategory 10142
         */
    public function sendDataForm1AgregarAlumno(array $data)
    {
        return $this->request(
            "https://www.programafines.ar/inicial/index4.php?a=7&b=1",
            [],
            [
                "nombre" => $data["nombre"] ?? throw new Exception("El nombre no se encuentra definido"),
                "dni_cargar" => $data["dni_cargar"] ?? throw new Exception("El dni no se encuentra definido"),
                "subcategory" => $data["subcategory"] ?? throw new Exception("El número de comisión no se encuentra definido"),
                "mi_periodo" => $data["mi_periodo"] ?? PF_PERIODO,
                "apellido" => $data["apellido"] ?? $data["nombre"],
                "cuil1" => $data["cuil1"] ?? "0",
                "cuil2" => $data["cuil2"] ?? "0",
                "nacionalidad" => $data["nacionalidad"] ?? "Argentina",
                "sexo" => $data["sexo"] ?? "2",
                "dia_nac" => $data["dia_nac"] ?? "1",
                "mes_nac" => $data["mes_nac"] ?? "2",
                "ano_nac" => $data["ano_nac"] ?? "1999",

            ]
        );
    } 

    public function sendDataForm2AgregarAlumno(array $data)
    {
        return $this->request(
            "https://www.programafines.ar/inicial/index4.php?a=7&b=2",
            [],
            [
                "direccion" => $data["direccion"] ?? "",
                "departamento" => $data["departamento"] ?? "",
                "localidad" => $data["localidad"] ?? "",
                "partido" => $data["partido"] ?? "",
                "email" => $data["email"] ?? "",
                "cod_area" => $data["cod_area"] ?? "",
                "nro_telefono" => $data["telefono"] ?? ""
            ]
        );
    }


    /**
     * Pantalla raiz para agregar alumno PCI
     */
    public function openFormAgregarAlumnoPCI()
    {
        return $this->getPage(
            "https://www.programafines.ar/inicial/index4.php",
            ["a" => 711]
        );
    }
    public function openFormCambiarComisionAlumno(){
        return $this->getPage(
            "https://programafines.ar/inicial/index4.php?a=22",
            ["a" => 22]
        );
    }

    /**
         * @example 
         * dni_cargar 31234567
         * comision_destino 10142
         */
    public function sendDataCambiarComisionAlumno(array $data)
    {
        return $this->request(
            "https://www.programafines.ar/inicial/index4.php?a=7&b=1",
            [],
            $data
        );
    } 


    public function sendDataForm1AgregarAlumnoPCI(array $data)
    {
        return $this->request(
            "https://www.programafines.ar/inicial/index4.php?a=711&b=1",
            [],
            [
                "apellido" => $data["apellido"],
                "nombre" => $data["nombre"],
                "cuil1" => $data["cuil1"],
                "dni_cargar" => $data["dni"],
                "cuil2" => $data["cuil2"],
                "nacionalidad" => $data["nacionalidad"] ?? "Argentina",
                "sexo" => $data["sexo"],
                "dia_nac" => $data["dia"],
                "mes_nac" => $data["mes"],
                "ano_nac" => $data["ano"],
                "mi_periodo" => $data["periodo"],
                "subcategory" => $data["comision"],
                "cuatrim_inscripcion" => $data["cuatrimestre"]
            ]
        );
    }

    public function sendDataForm2AgregarAlumnoPCI(array $data)
    {
        return $this->request(
            "https://www.programafines.ar/inicial/index4.php?a=711&b=2",
            [],
            [
                "direccion" => $data["direccion"] ?? "",
                "departamento" => $data["departamento"] ?? "",
                "localidad" => $data["localidad"] ?? "",
                "partido" => $data["partido"] ?? "",
                "email" => $data["email"] ?? "",
                "cod_area" => $data["cod_area"] ?? "",
                "nro_telefono" => $data["telefono"] ?? ""
            ]
        );
    }

    public function agregarAlumnoPCI(array $alumno)
    {
        // 1 abrir
        $this->openFormAgregarAlumnoPCI();

        // 2 enviar datos alumno
        $this->sendDataForm1AgregarAlumnoPCI($alumno);

        // 3 finalizar
        return $this->sendDataForm2AgregarAlumnoPCI($alumno);
    }

    public function close()
    {
        curl_close($this->client);
    }
}

