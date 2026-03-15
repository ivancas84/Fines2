<?php

namespace ProgramaFines\DataAccess;

use Exception;
use DateTime;


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

    private function request(string $url, array $headers = [], array $postData = null)
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
     */
    public function getListaAlumnos($pfid, $periodo = 6){
        return $this->getPage(
            "https://www.programafines.ar/inicial/index4.php",
            [
                "a" => 12,
                "nom_comision" => $pfid,
                "mi_periodo" => $periodo
            ]
        );
    }

    public function openFormAgregarAlumnoPCI()
    {
        return $this->getPage(
            "https://www.programafines.ar/inicial/index4.php",
            ["a" => 711]
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

