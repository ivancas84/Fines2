<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/ProgramaFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

class PersonaDAO
{
    public static function personaByNumeroDocumento($numero_documento, $fetchMode = PDO::FETCH_OBJ)
    {
        $pdo = new PdoFines();
        $stmt = $pdo->pdo->prepare("
            SELECT * FROM persona WHERE numero_documento = :numero_documento
        ");
        $stmt->bindParam(':numero_documento', $numero_documento, PDO::PARAM_STR); // Bind as a string
        $stmt->execute();
        return $stmt->fetch($fetchMode);
    }


    public static function idPersonaByDni($dni)
    {
        $pdo = new PdoFines();
        $stmt = $pdo->pdo->prepare("SELECT id FROM persona WHERE numero_documento = :numero_documento");
        $stmt->bindParam(':numero_documento', $dni, PDO::PARAM_STR); // Bind as a string
        $stmt->execute();
    
        return $stmt->fetchColumn() ?? null;
    }


    public static function insertPersonaArray($persona){
        $pdo = new PdoFines();

        if(!array_key_exists("nombres", $persona) || empty($persona["nombres"]))
            throw new Exception("No se ha definido nombres de la persona");


        if(!array_key_exists("cuil", $persona) || empty($persona["cuil"]))
            $persona["cuil"] = null;
        else{
            $cuilDni = Tools::cuilDni($persona["cuil"]);
            $persona["cuil"] = $cuilDni["cuil"];
            $persona["cuil1"] = $cuilDni["cuil1"];
            $persona["cuil2"] = $cuilDni["cuil2"];
            if(array_key_exists("numero_documento", $persona) || !empty($persona["numero_documento"]) && $cuilDni["dni"] != $persona["numero_documento"])
                throw new Exception("El CUIL no coincide con el DNI");
            else 
                $persona["numero_documento"] = $cuilDni["dni"];
        }

        if(!array_key_exists("numero_documento", $persona) || empty($persona["numero_documento"]))
            throw new Exception("No se ha definido el numero documento de la persona");


        if(!array_key_exists("genero", $persona) || empty($persona["genero"]))
            $persona["genero"] = null;
        else {
            $genero = strtolower(trim($persona["genero"]));

            if (strpos($genero, 'f') !== false) {
                $persona["genero"] = "Femenino";
            } elseif (strpos($genero, 'x') !== false || strpos($genero, 'ot') !== false) {
                $persona["genero"] = "Otro";
            } else {
                $persona["genero"] = "Masculino";
            }
        }

        if(!array_key_exists("apellidos", $persona) || empty($persona["apellidos"]))
            $persona["apellidos"] = null;

        if(!array_key_exists("descripcion_domicilio", $persona) || empty($persona["descripcion_domicilio"]))
            $persona["descripcion_domicilio"] = null;

        if (!array_key_exists("fecha_nacimiento", $persona) || empty($persona["fecha_nacimiento"])) {
            $persona["fecha_nacimiento"] = null;
        } else {
            $fecha = DateTime::createFromFormat('Y-m-d', $persona["fecha_nacimiento"]);
            
            // Check if the date is valid
            if ($fecha && $fecha->format('Y-m-d') === $persona["fecha_nacimiento"]) {
                $persona["anio_nacimiento"] = $fecha->format('Y');
                $persona["mes_nacimiento"] = $fecha->format('m');
                $persona["dia_nacimiento"] = $fecha->format('d');
            } else {
                // Invalid date format
                $persona["fecha_nacimiento"] = null;
            }
        }

        if(empty($persona["fecha_nacimiento"])){
            if(!array_key_exists("anio_nacimiento", $persona) || empty($persona["anio_nacimiento"]))
                $persona["anio_nacimiento"] = null;
            
            if(!array_key_exists("mes_nacimiento", $persona) || empty($persona["mes_nacimiento"]))
                $persona["mes_nacimiento"] = null;

            if(!array_key_exists("dia_nacimiento", $persona) || empty($persona["dia_nacimiento"]))
                $persona["dia_nacimiento"] = null;

            if(!empty($persona["anio_nacimiento"]) && !empty($persona["mes_nacimiento"]) && !empty($persona["dia_nacimiento"])){
                $fecha = DateTime::createFromFormat('Y-m-d', $persona["anio_nacimiento"] . "-" . $persona["mes_nacimiento"] . "-" . $persona["dia_nacimiento"]);
            
                // Check if the date is valid
                if ($fecha){
                    $persona["fecha_nacimiento"] = $fecha->format('Y-m-d');
                } else {
                    // Invalid date format
                    $persona["anio_nacimiento"] = null;
                    $persona["mes_nacimiento"] = null;
                    $persona["dia_nacimiento"] = null;
                }
            } else {
                $persona["anio_nacimiento"] = null;
                $persona["mes_nacimiento"] = null;
                $persona["dia_nacimiento"] = null;
            }
        }

        if(!array_key_exists("telefono", $persona) || empty($persona["telefono"]))
            $persona["telefono"] = null;    
        
        if(!array_key_exists("email", $persona) || empty($persona["email"]))
            $persona["email"] = null;    

        if(!array_key_exists("email_abc", $persona) || empty($persona["email_abc"]))
            $persona["email_abc"] = null;    


        $sql = "INSERT INTO persona (id, cuil, cuil1, cuil2, numero_documento, nombres, apellidos, descripcion_domicilio, 
                                    dia_nacimiento, mes_nacimiento, anio_nacimiento, 
                                    fecha_nacimiento, telefono, email, email_abc) 
                VALUES (?,
                        ?, ?, ?, ?, ?, ?, ?, 
                        ?, ?, ?, 
                        ?, ?, ?, ?)";

                        $stmt = $pdo->pdo->prepare($sql);
        $insert = $stmt->execute([
            $persona['id'],
            $persona['cuil'], 
            $persona['cuil1'], 
            $persona['cuil2'], 
            $persona['numero_documento'], 
            $persona['nombres'], 
            $persona['apellidos'], 
            $persona['descripcion_domicilio'],
            $persona['dia_nacimiento'], 
            $persona['mes_nacimiento'], 
            $persona['anio_nacimiento'],
            $persona['fecha_nacimiento'],
            $persona['telefono'], 
            $persona["email"], 
            $persona["email_abc"]
        ]);
        if(!$insert) throw new Exception("No se pudo insertar la persona: " . $stmt->errorInfo());
    }

    public static function compareAndUpdatePersonaArray($original, $nueva, $nullIfEmpty = false): array {
        $pdo = new PdoFines();
        $actualizaciones = [];
        $pdo->compareAndUpdateField("nombres", $original, $nueva, $actualizaciones, $nullIfEmpty);
        $pdo->compareAndUpdateField("apellidos", $original, $nueva, $actualizaciones, $nullIfEmpty);
        $pdo->compareAndUpdateField("descripcion_domicilio", $original, $nueva, $actualizaciones, $nullIfEmpty);
        $pdo->compareAndUpdateField("cuil", $original, $nueva, $actualizaciones, $nullIfEmpty);
        $cuilDni = Tools::cuilDni($nueva["cuil"]);
        $nueva["cuil1"] = $cuilDni["cuil1"];
        $nueva["cuil2"] = $cuilDni["cuil2"];

        if(!empty($nueva["numero_documento"]))
            if($cuilDni["dni"] != $nueva["numero_documento"])
                throw new Exception("El CUIL no coincide con el DNI");

        $pdo->compareAndUpdateField("numero_documento", $original, $nueva, $actualizaciones, $nullIfEmpty);
        
        if(!array_key_exists("genero", $nueva) || empty($nueva["genero"]))
            $nueva["genero"] = null;
        else {
            $genero = strtolower(trim($nueva["genero"]));

            if (strpos($genero, 'f') !== false) {
                $nueva["genero"] = "Femenino";
            } elseif (strpos($genero, 'x') !== false || strpos($genero, 'ot') !== false) {
                $nueva["genero"] = "Otro";
            } else {
                $nueva["genero"] = "Masculino";
            }
        }
        $pdo->compareAndUpdateField("genero", $original, $nueva, $actualizaciones, $nullIfEmpty);
        

        $pdo->compareAndUpdateField("telefono", $original, $nueva, $actualizaciones, $nullIfEmpty);
        $pdo->compareAndUpdateField("email", $original, $nueva, $actualizaciones, $nullIfEmpty);
        $pdo->compareAndUpdateField("email_abc", $original, $nueva, $actualizaciones, $nullIfEmpty);
        if (array_key_exists("fecha_nacimiento", $nueva) || !empty($nueva["fecha_nacimiento"])) {
            $fecha = DateTime::createFromFormat('Y-m-d', $nueva["fecha_nacimiento"]);
            
            // Check if the date is valid
            if ($fecha && $fecha->format('Y-m-d') === $nueva["fecha_nacimiento"]) {
                $nueva["anio_nacimiento"] = $fecha->format('Y');
                $nueva["mes_nacimiento"] = $fecha->format('m');
                $nueva["dia_nacimiento"] = $fecha->format('d');
                
            } else {
                // Invalid date format
                $nueva["fecha_nacimiento"] = null;
                $pdo->compareAndUpdateField("fecha_nacimiento", $original, $nueva, $actualizaciones, $nullIfEmpty);
        
            }
        }
        $pdo->compareAndUpdateField("fecha_nacimiento", $original, $nueva, $actualizaciones, $nullIfEmpty);
        $pdo->compareAndUpdateField("dia_nacimiento", $original, $nueva, $actualizaciones, $nullIfEmpty);
        $pdo->compareAndUpdateField("mes_nacimiento", $original, $nueva, $actualizaciones, $nullIfEmpty);
        $pdo->compareAndUpdateField("anio_nacimiento", $original, $nueva, $actualizaciones, $nullIfEmpty);

        $sql = "UPDATE persona 
                SET nombres = ?, apellidos = ?, descripcion_domicilio = ?, 
                    dia_nacimiento = ?, mes_nacimiento = ?, anio_nacimiento = ?, 
                    fecha_nacimiento = ?,
                    telefono = ?, email_abc = ?, cuil = ?, cuil1 = ?, cuil2 = ?, numero_documento = ?,
                    email = ?, genero = ?
                WHERE id = ?";

        $stmt = $pdo->pdo->prepare($sql);
        $update = $stmt->execute([
            $nueva['nombres'], $nueva['apellidos'], $nueva['descripcion_domicilio'],
            $nueva['dia_nacimiento'], $nueva['mes_nacimiento'], $nueva['anio_nacimiento'],
            $nueva['fecha_nacimiento'],
            $nueva['telefono'], $nueva["email_abc"], $nueva["cuil"], 
            $nueva["cuil1"], $nueva["cuil2"], $nueva["numero_documento"],
            $nueva["email"], $nueva["genero"],
            $original['id']
        ]);
        
        if(!$update) throw new Exception("No se pudo actualizar la persona: " . $stmt->errorInfo());

        return $actualizaciones;

    }

    public function updatePersonaArray($persona){

        $pdo = new PdoFines();
        // Update existing person
        $sql = "UPDATE persona 
                SET nombres = ?, apellidos = ?, descripcion_domicilio = ?, 
                    dia_nacimiento = ?, mes_nacimiento = ?, anio_nacimiento = ?, 
                    fecha_nacimiento = ?,
                    telefono = ?, email_abc = ?
                WHERE numero_documento = ?";
        
        return $pdo->pdo->prepare($sql)->execute([
            $persona['nombres'], $persona['apellidos'], $persona['descripcion_domicilio'],
            $persona['dia_nacimiento'], $persona['mes_nacimiento'], $persona['anio_nacimiento'],
            $persona['fecha_nacimiento'],
            $persona['telefono'], $persona["email_abc"], $persona['numero_documento']
        ]);
    }

    function updateCuilById($cuil, $id)
    {
        $pdo = new PdoFines();
        $stmt = $pdo->pdo->prepare("UPDATE persona SET cuil = :cuil WHERE id = :id");
        $stmt->bindParam(':cuil', $cuil, PDO::PARAM_STR); // Bind CUIL as a string
        $stmt->bindParam(':id', $id, PDO::PARAM_STR); // Bind ID as an integer
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true; //echo "CUIL updated successfully.";
        } else {
            return false; //echo "No record updated (ID may not exist or CUIL is the same).";
        }
    }

    public static function personaById($id, $fetchMode = PDO::FETCH_ASSOC)
    {
        $pdo = new PdoFines();
        $stmt = $pdo->pdo->prepare("SELECT * FROM persona WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_STR); // Bind ID as a string
        $stmt->execute();
        return $stmt->fetch($fetchMode);
    }

}