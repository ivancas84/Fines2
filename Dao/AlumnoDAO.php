<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/ProgramaFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

class AlumnoDAO
{
    public static function insertAlumnoArray($alumno){
        $pdo = new PdoFines();
        if(!array_key_exists("persona", $alumno) || empty($alumno["persona"]))
            throw new Exception("No se ha definido la persona del alumno");

        if(!array_key_exists("plan", $alumno) || empty($alumno["plan"]))
            throw new Exception("No se ha definido la persona del alumno");
        
        if(!array_key_exists("observaciones", $alumno) || empty($alumno["observaciones"])){
            $alumno["observaciones"] = null;
        }

        if(!array_key_exists("anio_ingreso", $alumno) || empty($alumno["anio_ingreso"])){
            $alumno["anio_ingreso"] = null;
            $alumno["confirmado_direccion"] = 0;
            $alumno["estado_inscripcion"] = "Indeterminado";
        } else {
            $alumno["confirmado_direccion"] = 1;
            $alumno["estado_inscripcion"] = "Correcto";            
        }

        if(!array_key_exists("semestre_ingreso", $alumno) || empty($alumno["semestre_ingreso"]))
            $alumno["semestre_ingreso"] = 1;

        
        $alumno["tiene_dni"] = (array_key_exists("tiene_dni", $alumno) || Tools::toBool($alumno["tiene_dni"])) ? 1 : 0;
        $alumno["tiene_constancia"] = (array_key_exists("tiene_constancia", $alumno) || Tools::toBool($alumno["tiene_constancia"])) ? 1 : 0;
        $alumno["tiene_certificado"] = (array_key_exists("tiene_certificado", $alumno) || Tools::toBool($alumno["tiene_certificado"])) ? 1 : 0;
        $alumno["tiene_partida"] = (array_key_exists("tiene_partida", $alumno) || Tools::toBool($alumno["tiene_partida"])) ? 1 : 0;
        $alumno["previas_completas"] = (array_key_exists("previas_completas", $alumno) || Tools::toBool($alumno["previas_completas"])) ? 1 : 0;

        $sql = "INSERT INTO alumno (id, persona, plan, anio_ingreso, semestre_ingreso, confirmado_direccion, estado_inscripcion, tiene_dni, tiene_constancia, tiene_certificado, tiene_partida, previas_completas, observaciones) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->pdo->prepare($sql);
        $insert = $stmt->execute([
            $alumno['id'],
            $alumno['persona'],
            $alumno['plan'],
            $alumno['anio_ingreso'],
            $alumno['semestre_ingreso'],
            $alumno['confirmado_direccion'],
            $alumno['estado_inscripcion'],
            $alumno['tiene_dni'],
            $alumno['tiene_constancia'],
            $alumno['tiene_certificado'],
            $alumno['tiene_partida'],
            $alumno['previas_completas'],
            $alumno['observaciones']
        ]);

        if(!$insert) throw new Exception("No se pudo insertar al alumno: " . $stmt->errorInfo());
    }


    public static function alumnoByNumeroDocumento($numero_documento, $fetchMode = PDO::FETCH_OBJ) {
        $pdo = new PdoFines();
        $stmt = $pdo->pdo->prepare("
            SELECT alumno.id AS alumno_id, alumno.*, persona.id AS persona_id, persona.nombres, persona.apellidos, persona.numero_documento
            FROM alumno
            INNER JOIN persona ON alumno.persona = persona.id
            WHERE persona.numero_documento = :numero_documento
        ");
        $stmt->bindParam(':numero_documento', $numero_documento, PDO::PARAM_STR); // Bind as a string
        $stmt->execute();
        return $stmt->fetch($fetchMode);
    }



    public static function compareAndUpdateAlumnoArray($original, $nuevo, $nullIfEmpty = false): array {
        $pdo = new PdoFines();

        $actualizaciones = [];

        if($pdo->existeValor("anio_ingreso", $nuevo)) {
            $nuevo_anio_ingreso = intval($nuevo["anio_ingreso"]); 
            if(intval($original["anio_ingreso"]) > $nuevo_anio_ingreso || $nuevo_anio_ingreso > 3){
                $nuevo["anio_ingreso"] = $original["anio_ingreso"];
                $nuevo["semestre_ingreso"] = $original["semestre_ingreso"];
                $nuevo["confirmado_direccoin"] = true;

            } else {
                if(!$pdo->existeValor("semestre_ingreso", $nuevo))
                    $nuevo["semestre_ingreso"] = 1;
            }
        } else {
            $nuevo["anio_ingreso"] = $original["anio_ingreso"];
            $nuevo["semestre_ingreso"] = $original["semestre_ingreso"];
        }
        $pdo->compareAndUpdateField("anio_ingreso", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("semestre_ingreso", $original, $nuevo, $actualizaciones, false);

        if($pdo->existeValor("observaciones", $nuevo)) {
            if($pdo->existeValor("observaciones", $original)) {
                if(strpos($original["observaciones"],  $nuevo["observaciones"]) !== false){
                    $nuevo["observaciones"] = $original["observaciones"];
                } else {
                    $nuevo["observaciones"] = $original["observaciones"] . " " . $nuevo["observaciones"];
                }
            }
        } else {
            $nuevo["observaciones"] = $original["observaciones"];
        }
        $pdo->compareAndUpdateField("observaciones", $original, $nuevo, $actualizaciones, false);

        if($pdo->existeValor("estado_inscripcion", $original) && $pdo->existeValor("estado_inscripcion", $nuevo)) {

            if($original["estado_inscripcion"] == "Titulado" || $nuevo["estado_inscripcion"] == "Titulado") {
                $nuevo["estado_inscripcion"] = "Titulado";
            } else if($original["estado_inscripcion"] == "Correcto" || $nuevo["estado_inscripcion"] == "Correcto") {
                $nuevo["estado_inscripcion"] = "Correcto";
            } 
        }        
        $pdo->compareAndUpdateField("estado_inscripcion", $original, $nuevo, $actualizaciones, false);


        if(array_key_exists("tiene_dni", $nuevo) && !Tools::toBool($nuevo["tiene_dni"])){
            $nuevo["tiene_dni"] = $original["tiene_dni"];
        } else {
            $nuevo["tiene_dni"] = 1;
        }
        $pdo->compareAndUpdateField("tiene_dni", $original, $nuevo, $actualizaciones, false);


        if(array_key_exists("tiene_constancia", $nuevo) && !Tools::toBool($nuevo["tiene_constancia"])){
            $nuevo["tiene_constancia"] = $original["tiene_constancia"];
        } else {
            $nuevo["tiene_constancia"] = 1;
        }
        $pdo->compareAndUpdateField("tiene_constancia", $original, $nuevo, $actualizaciones, false);

        if(array_key_exists("tiene_certificado", $nuevo) && !Tools::toBool($nuevo["tiene_certificado"])){
            $nuevo["tiene_certificado"] = $original["tiene_certificado"];
        } else {
            $nuevo["tiene_certificado"] = 1;
        }
        $pdo->compareAndUpdateField("tiene_certificado", $original, $nuevo, $actualizaciones, false);


        if(array_key_exists("tiene_partida", $nuevo) && !Tools::toBool($nuevo["tiene_partida"])){
            $nuevo["tiene_partida"] = $original["tiene_partida"];
        } else {
            $nuevo["tiene_partida"] = 1;
        }
        $pdo->compareAndUpdateField("tiene_partida", $original, $nuevo, $actualizaciones, false);


        if(array_key_exists("previas_completas", $nuevo) && !Tools::toBool($nuevo["previas_completas"])){
            $nuevo["previas_completas"] = $original["previas_completas"];
        } else {
            $nuevo["previas_completas"] = 1;
        }
        $pdo->compareAndUpdateField("previas_completas", $original, $nuevo, $actualizaciones, false);


        if(array_key_exists("confirmado_direccion", $nuevo) && !Tools::toBool($nuevo["confirmado_direccion"])){
            $nuevo["confirmado_direccion"] = $original["confirmado_direccion"];
        } else {
            $nuevo["confirmado_direccion"] = 1;
        }
        $pdo->compareAndUpdateField("confirmado_direccion", $original, $nuevo, $actualizaciones, false);

        $pdo->compareAndUpdateField("estado_inscripcion", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("persona", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("fecha_titulacion", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("plan", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("resolucion_inscripcion", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("anio_inscripcion", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("semestre_inscripcion", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("adeuda_legajo", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("adeuda_deudores", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("documentacion_inscripcion", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("anio_inscripcion_completo", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("establecimiento_inscripcion", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("libro_folio", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("libro", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("folio", $original, $nuevo, $actualizaciones, false);
        $pdo->compareAndUpdateField("comentarios", $original, $nuevo, $actualizaciones, false);


        $sql = "UPDATE alumno SET 
            anio_ingreso = ?, semestre_ingreso = ?, estado_inscripcion = ?, persona = ?, fecha_titulacion = ?, 
            plan = ?, resolucion_inscripcion = ?, anio_inscripcion = ?, semestre_inscripcion = ?, adeuda_legajo = ?,
            adeuda_deudores = ?, documentacion_inscripcion = ?, anio_inscripcion_completo = ?, establecimiento_inscripcion = ?,
            libro_folio = ?, libro = ?, folio = ?, comentarios = ?, observaciones = ?,
            tiene_dni = ?, tiene_constancia = ?, tiene_certificado = ?, tiene_partida = ?, previas_completas = ?, confirmado_direccion = ?
            WHERE id = ?";

        $stmt = $pdo->pdo->prepare($sql);

        $update = $stmt->execute([
            $nuevo['anio_ingreso'], $nuevo['semestre_ingreso'], $nuevo['estado_inscripcion'], 
            $nuevo['persona'], $nuevo['fecha_titulacion'], $nuevo['plan'],
            $nuevo['resolucion_inscripcion'], $nuevo['anio_inscripcion'], $nuevo['semestre_inscripcion'],
            $nuevo['adeuda_legajo'], $nuevo['adeuda_deudores'], $nuevo['documentacion_inscripcion'],
            $nuevo['anio_inscripcion_completo'], $nuevo['establecimiento_inscripcion'],
            $nuevo['libro_folio'], $nuevo['libro'], $nuevo['folio'],
            $nuevo["comentarios"], $nuevo["observaciones"],
            $nuevo["tiene_dni"], $nuevo["tiene_constancia"], $nuevo["tiene_certificado"],
            $nuevo["tiene_partida"], $nuevo["previas_completas"], $nuevo["confirmado_direccion"],
            $original['id']
        ]);
        
        if(!$update) throw new Exception("No se pudo actualizar la persona: " . $stmt->errorInfo());

        return $actualizaciones;

    }



}