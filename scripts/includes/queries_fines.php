<?php


function pdoFines_pfidComisionesById($pdo_fines, $id_calendario)
{
    $stmt = $pdo_fines->prepare("SELECT DISTINCT pfid FROM comision WHERE calendario = :id_calendario");
    $stmt->bindParam(':id_calendario', $id_calendario, PDO::PARAM_STR); // Bind as a string
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch as a simple array of pfid values
}

function pdoFines_idPersonaByDni($pdo_fines, $dni)
{
    $stmt = $pdo_fines->prepare("SELECT id FROM persona WHERE numero_documento = :numero_documento");
    $stmt->bindParam(':numero_documento', $dni, PDO::PARAM_STR); // Bind as a string
    $stmt->execute();

    return $stmt->fetchColumn() ?? null;
}

function pdoFines_updateCuilById($pdo_fines, $cuil, $id)
{
    $stmt = $pdo_fines->prepare("UPDATE persona SET cuil = :cuil WHERE id = :id");
    $stmt->bindParam(':cuil', $cuil, PDO::PARAM_STR); // Bind CUIL as a string
    $stmt->bindParam(':id', $id, PDO::PARAM_STR); // Bind ID as an integer
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return true; //echo "CUIL updated successfully.";
    } else {
        return false; //echo "No record updated (ID may not exist or CUIL is the same).";
    }
}


function pdoFines_updateDescripcionHorarioById($pdo_fines, $descripcion_horario, $id)
{
    $stmt = $pdo_fines->prepare("UPDATE curso SET descripcion_horario = :descripcion_horario WHERE id = :id");
    $stmt->bindParam(':descripcion_horario', $descripcion_horario, PDO::PARAM_STR); // Bind CUIL as a string
    $stmt->bindParam(':id', $id, PDO::PARAM_STR); // Bind ID as an integer
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return true; //echo "CUIL updated successfully.";
    } else {
        return false; //echo "No record updated (ID may not exist or CUIL is the same).";
    }
}

function pdoFines_idCurso__By_ComisionPfid_AsignaturaCodigo_calendario($pdo_fines, $pfid_comision, $codigo_asignatura, $id_calendario) {
    $stmt = $pdo_fines->prepare("
        SELECT curso.id 
        FROM curso
        INNER JOIN disposicion ON curso.disposicion = disposicion.id
        INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
        INNER JOIN comision ON curso.comision = comision.id
        WHERE comision.pfid = :pfid
        AND comision.calendario = :calendario
        AND asignatura.codigo LIKE :codigo"
    );
    $stmt->bindParam(':calendario', $id_calendario, PDO::PARAM_STR); // Bind as a string
    $stmt->bindParam(':pfid', $pfid_comision, PDO::PARAM_STR); // Bind as a string
    $stmt->bindValue(':codigo', "%".$codigo_asignatura."%", PDO::PARAM_STR); // Use bindValue for LIKE wildcard

    $stmt->execute();

    return $stmt->fetchColumn() ?? null;
}

function pdoFines_tomaActiva__By_comisionPfid_asignaturaCodigo_calendario($pdo_fines, $pfid_comision, $codigo_asignatura, $id_calendario){
    $stmt = $pdo_fines->prepare("
        SELECT toma.* FROM toma 
        INNER JOIN curso ON toma.curso = curso.id
        INNER JOIN disposicion ON curso.disposicion = disposicion.id
        INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
        INNER JOIN comision ON curso.comision = comision.id
        INNER JOIN persona ON (toma.docente = persona.id)
        WHERE comision.pfid = :pfid
        AND comision.calendario = :calendario
        AND asignatura.codigo LIKE :codigo
        AND (toma.estado = 'Aprobada' OR toma.estado = 'Pendiente') 
        AND toma.estado_contralor != 'Modificar'
    ");
    $stmt->bindParam(':calendario', $id_calendario, PDO::PARAM_STR); // Bind as a string
    $stmt->bindParam(':pfid', $pfid_comision, PDO::PARAM_STR); // Bind as a string
    $stmt->bindValue(':codigo', "%".$codigo_asignatura."%", PDO::PARAM_STR); // Use bindValue for LIKE wildcard

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC) ?? null;
}
