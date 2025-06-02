<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/ProgramaFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

class DisposicionDAO
{

  
    public static function getFields()
    {
        return [
        ];
    }

    public static function disposicionesByPlanTramo($plan_id, $tramo){
        $sql = "
            SELECT DISTINCT disposicion.*, disposicion.id AS disposicion_id, CONCAT(asignatura.codigo, planificacion.anio, planificacion.semestre) AS detalle_asignatura
            FROM disposicion
            INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
            INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
            WHERE planificacion.plan = ? AND CONCAT(planificacion.anio, planificacion.semestre) >= ?";
        $pdo = new PdoFines();
        $stmt = $pdo->pdo->prepare($sql);
        $stmt->execute([$plan_id, $tramo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}