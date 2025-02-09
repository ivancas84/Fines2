<?php

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

function sqlSelectComision__With_referentes(){
	return "SELECT
					comision.id as comision_id,
					sede.id as sede_id,
					sede.nombre,
                    CONCAT(
                        'Calle ', COALESCE(domicilio.calle, '-'), ' ',
                        'e/ ', COALESCE(domicilio.entre, '-'), ', ',
                        'N° ', COALESCE(domicilio.numero, '-'), ', ',
                        COALESCE(domicilio.barrio, '-'), ', ',
                        COALESCE(domicilio.localidad, '-')
                    ) AS domicilio,
                    CONCAT(planificacion.anio,'°',planificacion.semestre,'C') AS tramo,
                    plan.orientacion, plan.resolucion,
                    comision.autorizada, comision.apertura, comision.publicada,
                    comision.pfid,
                    GROUP_CONCAT(
                        DISTINCT '* ', persona.nombres, ' ',
                        COALESCE(persona.apellidos, '-'), ' ',
                        COALESCE(persona.telefono, '-'), ' ',
                        COALESCE(persona.email, '-') 
                        SEPARATOR '<br/>'
                    ) AS referentes
                FROM comision     
                INNER JOIN sede ON comision.sede = sede.id
                LEFT JOIN domicilio ON sede.domicilio = domicilio.id
                INNER JOIN planificacion ON comision.planificacion = planificacion.id
                INNER JOIN plan ON planificacion.plan = plan.id
                LEFT JOIN designacion ON comision.sede = designacion.sede AND designacion.cargo = 1 AND designacion.hasta IS NULL
                LEFT JOIN persona ON designacion.persona = persona.id";
}

function sqlSelectComision_autorizada__By_calendario__Without_tramo32(){
	return "SELECT
                comision.*
                CONCAT(planificacion.anio,planificacion.semestre) AS tramo
                
            FROM comision     
            INNER JOIN planificacion ON comision.planificacion = planificacion.id
            INNER JOIN plan ON planificacion.plan = plan.id
            WHERE tramo != '32' AND autorizada = true
    ";
}
?>