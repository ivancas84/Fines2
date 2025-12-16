 <?php

use SqlOrganize\Utils\ValueTypesUtils;

   /**
     * parsear un elemnto de la planilla de calificaciones del programa fines para dividir el contenido en
     * nombres, apellidos y nota
     */
    function ppc3_parse_xlsx($row)
    {
        $data = array();
        foreach($row as $key => $value) {
            $k = strtolower($key);
            $value = trim($value);
            if(str_contains($k, "nom")){
                $data["nombres"] = $value;
            } else if(str_contains($k, "ape")){
                $data["apellidos"] = $value;
            } else if(str_contains($k, "dni") || (str_contains($k, "doc"))){
                $data["numero_documento"] = ValueTypesUtils::cleanStringOfNonDigits($value);
            } else if(str_contains($key, "final") || str_contains($k, "nota") || str_contains($k, "calif")){
                $value = ValueTypesUtils::cleanStringOfNonDigits($value);
                $data["nota"] = empty($value) ? 0 : intval($value);
            }
        }

        if(empty($data["nombres"]) 
            || empty($data["apellidos"]) 
            || empty($data["numero_documento"]) 
            || empty($data["nota"])){
            throw new Exception("Datos incompletos en la fila.");
        }

        if($data["nota"] < 7){
            throw new Exception("Calificación vacía o menor a 7");
        }

        return $data;

    }

    
   /**
     * parsear un elemnto de la planilla de calificaciones del programa fines para dividir el contenido en
     * nombres, apellidos y nota
     */
    function ppc3_parse_pf($row)
    {
        $data = array();
        foreach($row as $key => $value) {
            if(str_contains($key, "Nombre")){
                $data = array_merge($data, ppc3_parse_first_column_pf($value));
            } else if(str_contains($key, "Final")){
                $value = intval(trim($value));
                if($value < 7){
                    throw new Exception("Calificación vacía o menor a 7");
                }

                $data["nota"] = $value;

            }
        }

        if(empty($data["nombres"]) || empty($data["apellidos"]) || empty($data["nota"])){
            throw new Exception("Datos incompletos en la fila.");
        }

        return $data;

    }

    function ppc3_parse_pf2(array $row): array
{
    $data = [];

    // DNI
    if (empty($row['DNI'])) {
        throw new Exception("DNI vacío o inexistente");
    }

    $dni = intval(trim($row['DNI']));
    if ($dni <= 0) {
        throw new Exception("DNI inválido");
    }

    $data['numero_documento'] = $dni;

    // Alumno: "APELLIDO, Nombre"
    if (empty($row['Alumno'])) {
        throw new Exception("Alumno vacío");
    }

    $alumno = trim($row['Alumno']);

    if (!str_contains($alumno, ',')) {
        throw new Exception("Formato de alumno inválido: se espera 'APELLIDO, Nombre'");
    }

    [$apellidos, $nombres] = array_map('trim', explode(',', $alumno, 2));

    if ($apellidos === '' || $nombres === '') {
        throw new Exception("Nombre o apellido incompleto");
    }

    $data['apellidos'] = $apellidos;
    $data['nombres']   = $nombres;

    // Nota = Promedio
    if (!isset($row['Promedio']) || trim($row['Promedio']) === '') {
        throw new Exception("Promedio vacío");
    }

    $nota = intval(trim($row['Promedio']));
    if ($nota < 7) {
        throw new Exception("Calificación menor a 7");
    }

    $data['nota'] = $nota;

    return $data;
}



    
    /**
     * parsear la primer columna de la planilla de calificaciones del programa fines para dividir el contenido en
     * nombres, apellidos y numero de documento
     */
    function ppc3_parse_first_column_pf($inputString) {
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

