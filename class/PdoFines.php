<?php

/**
 * @example
 * $pdoFines = new PdoFines($db_host, $db_name, $db_user, $db_pass);
 * $pdoFines->pdo_fines->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 * $pdoFines->pdo_fines->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
 */
class PdoFines
{
    
        public $pdo;
    
        public function __construct($db_host, $db_name, $db_user, $db_pass)
        {
            try {
                $this->pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                $this->pdo->exec("SET NAMES 'utf8mb3'");

            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
 
        function pfidsComisionesByCalendario($idCalendario) {
            $stmt = $this->pdo->prepare("SELECT DISTINCT pfid FROM comision WHERE calendario = :idCalendario");
            $stmt->bindParam(':idCalendario', $idCalendario, PDO::PARAM_STR); // Bind as a string
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch as a simple array of pfid values
        }

        function alumnosByComisionPfidAndCalendario($comision_pfid, $calendario_id) {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT *, alumno.id AS alumno_id, persona.id AS persona_id
                FROM alumno_comision
                INNER JOIN comision ON (comision.id = alumno_comision.comision)
                INNER JOIN alumno ON (alumno.id = alumno_comision.alumno)
                INNER JOIN persona ON (persona.id = alumno.persona)
                WHERE comision.pfid = :pfidComision AND comision.calendario = :calendario_id");
            $stmt->bindParam(':pfidComision', $comision_pfid, PDO::PARAM_INT); 
            $stmt->bindParam(':calendario_id', $calendario_id, PDO::PARAM_STR); 
            $stmt->execute();

            return $stmt->fetchAll();
        }

        function alumnosByComision($comision_id) {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT *, alumno.id AS alumno_id, persona.id AS persona_id
                FROM alumno_comision
                INNER JOIN comision ON (comision.id = alumno_comision.comision)
                INNER JOIN alumno ON (alumno.id = alumno_comision.alumno)
                INNER JOIN persona ON (persona.id = alumno.persona)
                WHERE comision.id = :idComision");
            $stmt->bindParam(':idComision', $comision_id, PDO::PARAM_STR); // Bind as a string
            $stmt->execute();

            return $stmt->fetchAll();
        }

        function alumnosConCantidadCalificacionesAprobadasByComisionJoinAlumnoPlan($comision_id){
            $stmt = $this->pdo->prepare("
                SELECT alumno_comision.estado,
                    alumno.id, alumno.anio_ingreso, alumno.tiene_dni, alumno.tiene_certificado, alumno.tiene_constancia, alumno.previas_completas, alumno.confirmado_direccion, alumno.tiene_partida, 
                    persona.id AS persona_id, persona.nombres, persona.apellidos, persona.numero_documento, 
                    calificacion_aprobada.tramo, calificacion_aprobada.cantidad_aprobadas
                FROM alumno
                INNER JOIN persona ON (alumno.persona = persona.id)
                INNER JOIN alumno_comision ON alumno.id = alumno_comision.alumno
                LEFT JOIN (
                        SELECT calificacion.alumno, planificacion.plan,
                        CONCAT(planificacion.anio, '°', planificacion.semestre, 'C') AS tramo, COUNT(*) as cantidad_aprobadas 
                        FROM calificacion
                        INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
                        INNER JOIN planificacion ON (disposicion.planificacion = planificacion.id)					
                        WHERE (calificacion.nota_final >= 7 OR calificacion.crec >= 4)
                        GROUP BY calificacion.alumno, planificacion.plan, tramo
			) AS calificacion_aprobada ON calificacion_aprobada.alumno = alumno.id AND calificacion_aprobada.plan = alumno.plan
            WHERE alumno_comision.comision = :idComision LIMIT 100");
            $stmt->bindParam(':idComision', $comision_id, PDO::PARAM_STR); // Bind as a string
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        function comisionById($comision_id) {
            $stmt = $this->pdo->prepare("
                SELECT
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
                    comision.autorizada, comision.apertura, comision.publicada, comision.turno,
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
                LEFT JOIN persona ON designacion.persona = persona.id WHERE comision.id = :idComision;
                ");
            $stmt->bindParam(':idComision', $comision_id, PDO::PARAM_STR); // Bind as a string
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }

        function alumnoByNumeroDocumento($numero_documento){
            $stmt = $this->pdo->prepare("
                SELECT alumno.id, persona.id AS persona_id, persona.nombres, persona.apellidos, persona.numero_documento
                FROM alumno
                INNER JOIN persona ON alumno.persona = persona.id
                WHERE persona.numero_documento = :numero_documento
            ");
            $stmt->bindParam(':numero_documento', $numero_documento, PDO::PARAM_STR); // Bind as a string
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }

        function personaByNumeroDocumento($numero_documento){
            $stmt = $this->pdo->prepare("
                SELECT * FROM persona WHERE numero_documento = :numero_documento
            ");
            $stmt->bindParam(':numero_documento', $numero_documento, PDO::PARAM_STR); // Bind as a string
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }

        function comisionesByAlumno($alumno_id){
            $stmt = $this->pdo->prepare("
                SELECT alumno_comision.id, alumno_comision.estado,
                comision.pfid, 
                sede.nombre AS sede_nombre,
                CONCAT(calendario.anio, '-', calendario.semestre) AS periodo,
                CONCAT(planificacion.anio, '-', planificacion.semestre) AS tramo
                FROM alumno_comision 
                INNER JOIN comision ON alumno_comision.comision = comision.id
                INNER JOIN planificacion ON comision.planificacion = planificacion.id
                INNER JOIN plan ON planificacion.plan = plan.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN domicilio ON sede.domicilio = domicilio.id
                WHERE alumno = :alumno_id
                ORDER BY CONCAT(calendario.anio, '-', calendario.semestre) DESC
            ");
            $stmt->bindParam(':alumno_id', $alumno_id, PDO::PARAM_STR); // Bind as a string
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        

}
