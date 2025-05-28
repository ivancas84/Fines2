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
    
        public function __construct()
        {
            try {
                $this->pdo = new PDO("mysql:host=" . DB_HOST_FINES . ";dbname=" . DB_NAME_FINES, DB_USER_FINES, DB_PASS_FINES, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                $this->pdo->exec("SET NAMES 'utf8mb3'");

            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }


        //********** METODOS GENERALES **********/
        function existeValor($fieldName, $array){
            return array_key_exists($fieldName, $array) && !empty($array[$fieldName]);
        }


        function compareAndUpdateField($fieldName, $original, &$nuevo, &$actualizaciones, $nullIfEmpty = true) {
            if(!array_key_exists($fieldName, $nuevo))
                $nuevo[$fieldName] = $original[$fieldName];
            else if(empty($nuevo[$fieldName])){
                if($nullIfEmpty)
                    $nuevo[$fieldName] = null;
                else
                    $nuevo[$fieldName] = $original[$fieldName];
            }
            else if($original[$fieldName] != $nuevo[$fieldName])
                $actualizaciones[$fieldName] = $original[$fieldName] . " -> " . $nuevo[$fieldName];
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

        function alumnosByComision($comision_id, $fetchMode = PDO::FETCH_OBJ) {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT *, alumno.id AS alumno_id, persona.id AS persona_id
                FROM alumno_comision
                INNER JOIN comision ON (comision.id = alumno_comision.comision)
                INNER JOIN alumno ON (alumno.id = alumno_comision.alumno)
                INNER JOIN persona ON (persona.id = alumno.persona)
                WHERE comision.id = :idComision");
            $stmt->bindParam(':idComision', $comision_id, PDO::PARAM_STR); // Bind as a string
            $stmt->execute();

            return $stmt->fetchAll($fetchMode);
        }

        function calificacionesByCursoArray(array $curso, $fetchMode = PDO::FETCH_OBJ) {
            //curso.disposicion
            

            //el alumno puede tener una calificacion con esa disposicion que pertenece a la comision pero no tiene curso
            //el alumno puede tener una calificacion con ese curso

            
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT *, alumno.id AS alumno_id, persona.id AS persona_id
                FROM calificacion
                INNER JOIN disposicion ON (calificacion.disposicion = disposicion.id)
                INNER JOIN curso ON (disposicion.curso = curso.id)
                INNER JOIN comision ON (curso.comision = comision.id)
                INNER JOIN alumno ON (calificacion.alumno = alumno.id)
                INNER JOIN persona ON (persona.id = alumno.persona)
                WHERE curso.id = :idCurso");
            $stmt->bindParam(':idCurso', $curso_id, PDO::PARAM_STR); // Bind as a string
            $stmt->execute();

            return $stmt->fetchAll($fetchMode);
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

        
        

        

        function comisionesByAlumno($alumno_id, $fetchMode = PDO::FETCH_OBJ){
            $stmt = $this->pdo->prepare("
                SELECT alumno_comision.id, alumno_comision.estado,
                comision.pfid, 
                comision.division,
                sede.nombre AS sede_nombre,
                CONCAT(calendario.anio, '-', calendario.semestre) AS periodo,
                CONCAT(planificacion.anio, '°', planificacion.semestre, 'c') AS tramo
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
            return $stmt->fetchAll($fetchMode);
    }

    public function tomasAprobadasByCalendario($calendario_id){
            $stmt = $this->pdo->prepare("
                SELECT 
                    toma.id,
                    toma.archivo,
                    toma.fecha_toma,  
                    persona.telefono, persona.numero_documento, persona.nombres, persona.apellidos, persona.cuil, persona.fecha_nacimiento, persona.email, persona.email_abc, persona.descripcion_domicilio,
                    sede.nombre AS sede_nombre, 
                    comision.pfid,
                    CONCAT(planificacion.anio, '°', planificacion.semestre, 'C') AS tramo,
                    CONCAT(
                                'Calle ', COALESCE(domicilio.calle, '-'), ' ',
                                'e/ ', COALESCE(domicilio.entre, '-'), ', ',
                                'N° ', COALESCE(domicilio.numero, '-'), ', ',
                                COALESCE(domicilio.barrio, '-'), ', ',
                                COALESCE(domicilio.localidad, '-')
                            ) AS domicilio_detalle,
                    asignatura.nombre AS asignatura_nombre, asignatura.codigo AS asignatura_codigo,
                    curso.descripcion_horario,
                    disposicion.horas_catedra,
                    calendario.inicio AS fecha_inicio,
                    calendario.fin AS fecha_fin,
                    plan.orientacion,
                    plan.resolucion
                FROM toma 
                INNER JOIN curso ON toma.curso = curso.id
                INNER JOIN disposicion ON curso.disposicion = disposicion.id
                INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
                INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
                INNER JOIN plan ON planificacion.plan = plan.id
                INNER JOIN comision ON curso.comision = comision.id
                INNER JOIN calendario ON comision.calendario = calendario.id
                INNER JOIN sede ON comision.sede = sede.id
                INNER JOIN domicilio ON sede.domicilio = domicilio.id
                INNER JOIN persona ON (toma.docente = persona.id)
                WHERE comision.calendario = :calendario
                AND (toma.estado = 'Aprobada') 
                AND toma.estado_contralor != 'Modificar'
            ");
            $stmt->bindParam(':calendario', $$calendario_id, PDO::PARAM_STR); // Bind as a string
        
            $stmt->execute();
        
            return $stmt->fetchAll() ?? [];
    }
    public function contralorByCalendario($calendario_id, $fetchMode = PDO::FETCH_OBJ){
            $stmt = $this->pdo->prepare("
            SELECT 
                toma.id,
                toma.archivo,
                toma.fecha_toma,  toma.tipo_movimiento, 
                persona.telefono, persona.numero_documento, persona.nombres, persona.apellidos, persona.cuil, persona.fecha_nacimiento, persona.email, persona.email_abc, persona.descripcion_domicilio,
                sede.nombre AS sede_nombre, 
                comision.pfid,
                calendario.fin AS calendario_fecha_fin,
                planificacion.anio AS planificacion_anio,
                planificacion.semestre AS planificacion_semestre,
                CONCAT(planificacion.anio, '°', planificacion.semestre, 'C') AS tramo,
                CONCAT(
                            'Calle ', COALESCE(domicilio.calle, '-'), ' ',
                            'e/ ', COALESCE(domicilio.entre, '-'), ', ',
                            'N° ', COALESCE(domicilio.numero, '-'), ', ',
                            COALESCE(domicilio.barrio, '-'), ', ',
                            COALESCE(domicilio.localidad, '-')
                        ) AS domicilio_detalle,
                asignatura.nombre AS asignatura_nombre, asignatura.codigo AS asignatura_codigo,
                curso.descripcion_horario,
                curso.horas_catedra AS curso_horas_catedra,
                disposicion.horas_catedra AS disposicion_horas_catedra,
                calendario.inicio AS fecha_inicio,
                calendario.fin AS fecha_fin,
                plan.orientacion,
                plan.resolucion,
                comision.turno
            FROM toma 
            INNER JOIN curso ON toma.curso = curso.id
            INNER JOIN disposicion ON curso.disposicion = disposicion.id
            INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
            INNER JOIN planificacion ON disposicion.planificacion = planificacion.id
            INNER JOIN plan ON planificacion.plan = plan.id
            INNER JOIN comision ON curso.comision = comision.id
            INNER JOIN calendario ON comision.calendario = calendario.id
            INNER JOIN sede ON comision.sede = sede.id
            INNER JOIN domicilio ON sede.domicilio = domicilio.id
            INNER JOIN persona ON (toma.docente = persona.id)
            WHERE comision.calendario = :calendario
            AND (toma.estado = 'Aprobada') 
            AND toma.estado_contralor = 'Pasar'
            AND toma.id NOT IN (
                SELECT toma
                FROM asignacion_planilla_docente
                WHERE reclamo = false
            )
            ORDER BY persona.numero_documento ASC;
        ");
        $stmt->bindParam(':calendario', $calendario_id, PDO::PARAM_STR); // Bind as a string
    
        $stmt->execute();
    
        return $stmt->fetchAll($fetchMode) ?? [];
    }


    //********** BASE ***********/
    function updateKey($table, $key, $value, $id, $typeValue = PDO::PARAM_STR, $typeId = PDO::PARAM_STR) {
        $stmt = $this->pdo->prepare("UPDATE $table SET $key = :value WHERE id = :id");
        $stmt->bindParam(':value', $value, $typeValue); // Bind CUIL as a string
        $stmt->bindParam(':id', $id, $typeId); // Bind ID as an integer
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true; //echo "CUIL updated successfully.";
        } else {
            return false; //echo "No record updated (ID may not exist or CUIL is the same).";
        }
    }
        

    

     //********** ALUMNO_COMISION **********/
     public function insertAlumnoComisionPrincipalArray($alumno_comision){
        // Insert new person

        $sql = "INSERT INTO alumno_comision (id, alumno, comision, estado, observaciones) 
                VALUES (?, ?, ?, ?, ?)";
        
        return $this->pdo->prepare($sql)->execute([
            $alumno_comision['id'],
            $alumno_comision['alumno'], 
            $alumno_comision['comision'],
            $alumno_comision['estado'],
            $alumno_comision['observaciones']
        ]);
    }

    

    
    //********** CALENDARIO **********/
    function calendarios($fetchMode = PDO::FETCH_OBJ){
        $stmt = $this->pdo->prepare("
            SELECT id, anio, semestre, descripcion 
            FROM calendario ORDER BY anio DESC, semestre DESC
            "
        );
    
        $stmt->execute();

        return $stmt->fetchAll($fetchMode) ?? [];
    }


    //********** CALIFICACION **********/
    //Calificaciones por disposicion y dnis
    function calificacionesAprobadasByDisposicionAndDnis($disposicion_id, $numeros_documento, $fetchMode = PDO::FETCH_OBJ) {
        // Step 1: Create placeholders
        $placeholders = [];
        for ($i = 0; $i < count($numeros_documento); $i++)
            $placeholders[] = ":doc$i";

        $stmt = $this->pdo->prepare("
            SELECT DISTINCT calificacion.id, calificacion.nota_final, calificacion.crec, calificacion.curso, calificacion.id AS calificacion_id, alumno.id AS alumno_id, persona.id AS persona_id, persona.numero_documento AS numero_documento
            FROM calificacion
            INNER JOIN alumno ON (calificacion.alumno = alumno.id)
            INNER JOIN persona ON (persona.id = alumno.persona)
            WHERE (nota_final >= 7 OR crec >= 4)
            AND calificacion.disposicion = :idDisposicion
            AND persona.numero_documento IN (" . implode(',', $placeholders) . ")");

        $stmt->bindParam(':idDisposicion', $disposicion_id, PDO::PARAM_STR); // Bind as a string

        for ($i = 0; $i < count($numeros_documento); $i++)
            $stmt->bindValue(":doc$i", $numeros_documento[$i], PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll($fetchMode);
    }

    public function insertCalificacionArray($calificacion){
        $sql = "INSERT INTO calificacion (id, alumno, curso, nota_final, disposicion) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $insert = $stmt->execute([
            $calificacion['id'],
            $calificacion['alumno'], 
            $calificacion['curso'],
            $calificacion['nota_final'],
            $calificacion['disposicion'],
        ]);

        if(!$insert) throw new Exception("No se pudo insertar la calificacion: " . $stmt->errorInfo());
    }



    //********** COMISION **********/
    function comisionesByParams($calendario_id, $filter_autorizada, $selected_order, $fetchMode = PDO::FETCH_OBJ) {

        $sql = "SELECT
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
                LEFT JOIN persona ON designacion.persona = persona.id
                WHERE calendario = :idCalendario";

        if ($filter_autorizada) {
            $sql .= " AND comision.autorizada = true";
        }

        $sql .= " GROUP BY comision.id ";

            // Append order by clause
        $sql .= " ORDER BY " . $selected_order;

        $stmt = $this->pdo->prepare($sql);              
        $stmt->bindParam(':idCalendario', $calendario_id, PDO::PARAM_STR); // Bind as a string
        $stmt->execute();
        return $stmt->fetchAll($fetchMode);
    }



    function comisionById($comision_id, $fetchMode = PDO::FETCH_OBJ) {
        $stmt = $this->pdo->prepare("
            SELECT
                comision.id as comision_id,
                planificacion.id as planificacion_id,
                plan.id as plan_id,
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
        return $stmt->fetch($fetchMode);
    }


    function pfidsComisionesByCalendario($idCalendario) {
        $stmt = $this->pdo->prepare("SELECT DISTINCT pfid FROM comision WHERE calendario = :idCalendario");
        $stmt->bindParam(':idCalendario', $idCalendario, PDO::PARAM_STR); // Bind as a string
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch as a simple array of pfid values
    }





    //********** CURSO **********/
    function idCursoByParams($pfid_comision, $codigo_asignatura, $id_calendario) {
        $stmt = $this->pdo->prepare("
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

    function updateDescripcionHorarioById($descripcion_horario, $id)
    {
        $stmt = $this->pdo->prepare("UPDATE curso SET descripcion_horario = :descripcion_horario WHERE id = :id");
        $stmt->bindParam(':descripcion_horario', $descripcion_horario, PDO::PARAM_STR); // Bind CUIL as a string
        $stmt->bindParam(':id', $id, PDO::PARAM_STR); // Bind ID as an integer
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true; //echo "CUIL updated successfully.";
        } else {
            return false; //echo "No record updated (ID may not exist or CUIL is the same).";
        }
    }

    function cursosConTomasActivas($calendario_id, $order_by, $fetchMode = PDO::FETCH_OBJ){
        // Get course details by ID
        $stmt = $this->pdo->prepare("
                SELECT curso.*,
                CONCAT(toma_activa.nombres, ' ', toma_activa.apellidos, ' ', toma_activa.numero_documento) AS docente_detalle,
                toma_activa.fecha_toma,
                CONCAT(asignatura.nombre, ' ', asignatura.codigo) AS asignatura_detalle,
                CONCAT(planificacion.anio, ' ', planificacion.semestre) AS tramo,
                comision.pfid AS comision_pfid,
                asignatura.codigo AS asignatura_codigo,
                sede.nombre AS sede_nombre,
                persona.telefono,
                CONCAT(
                    'Calle ', COALESCE(domicilio.calle, '-'), ' ',
                    'e/ ', COALESCE(domicilio.entre, '-'), ', ',
                    'N° ', COALESCE(domicilio.numero, '-'), ', ',
                    COALESCE(domicilio.barrio, '-'), ', ',
                    COALESCE(domicilio.localidad, '-')
                ) AS domicilio_detalle
                FROM curso 
                INNER JOIN disposicion ON (curso.disposicion = disposicion.id) 
                INNER JOIN asignatura ON (disposicion.asignatura = asignatura.id) 
                INNER JOIN comision ON (comision.id = curso.comision)
                INNER JOIN planificacion ON (planificacion.id = comision.planificacion)
                INNER JOIN sede ON (sede.id = comision.sede)
                INNER JOIN domicilio ON (domicilio.id = sede.domicilio)
                LEFT JOIN (
                    SELECT persona.id AS persona_id, toma.fecha_toma, toma.curso, persona.nombres, persona.apellidos, persona.numero_documento 
                    FROM toma 
                    INNER JOIN persona ON (toma.docente = persona.id)
                    WHERE estado = 'Aprobada' 
                    AND estado_contralor != 'Modificar'
                ) AS toma_activa ON (toma_activa.curso = curso.id)
                LEFT JOIN persona ON (toma_activa.persona_id = persona.id) 
                WHERE comision.autorizada = true 
                AND comision.calendario = :idCalendario
                {$order_by}"
        );
        $stmt->bindParam(':idCalendario', $calendario_id, PDO::PARAM_STR); // Bind as a string
        $stmt->execute();
        return $stmt->fetchAll($fetchMode);
    }

    

    function cursoById($curso_id, $fetchMode = PDO::FETCH_OBJ) {
        // Get course details by ID
    $stmt = $this->pdo->prepare("
        SELECT
            curso.id as curso_id,
            asignatura.nombre as asignatura_nombre,
            comision.id as comision_id,
            sede.id as sede_id,
            disposicion.id as disposicion_id,
            sede.nombre,
            CONCAT(
                'Calle ', COALESCE(domicilio.calle, '-'), ' ',
                'e/ ', COALESCE(domicilio.entre, '-'), ', ',
                'N° ', COALESCE(domicilio.numero, '-'), ', ',
                COALESCE(domicilio.barrio, '-'), ', ',
                COALESCE(domicilio.localidad, '-')
            ) AS domicilio,
            CONCAT(planificacion.anio,'°',planificacion.semestre,'C') AS tramo,
            plan.id AS plan_id,
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
        FROM curso
        INNER JOIN disposicion ON curso.disposicion = disposicion.id
        INNER JOIN asignatura ON disposicion.asignatura = asignatura.id
        INNER JOIN comision ON curso.comision = comision.id
        INNER JOIN sede ON comision.sede = sede.id
        LEFT JOIN domicilio ON sede.domicilio = domicilio.id
        INNER JOIN planificacion ON comision.planificacion = planificacion.id
        INNER JOIN plan ON planificacion.plan = plan.id
        LEFT JOIN designacion ON comision.sede = designacion.sede AND designacion.cargo = 1 AND designacion.hasta IS NULL
        LEFT JOIN persona ON designacion.persona = persona.id WHERE curso.id = :idCurso;
        ");
    $stmt->bindParam(':idCurso', $curso_id, PDO::PARAM_STR); // Bind as a string
    $stmt->execute();
    return $stmt->fetch($fetchMode);
}



    //********** PERSONA **********/
    

    


    //********** SELECT TOMA **********/
    function tomaActivaByParams($pfid_comision, $codigo_asignatura, $id_calendario){
        $stmt = $this->pdo->prepare("
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


    //********** INSERT / UPDATE ASIGNACION PLANILLA DOCENTE **********/
    public function insertAsignacionPlanillaDocente($toma, $planilla_docente){
        $id = uniqid(); // Or generate your own unique ID

        // Prepare SQL with placeholders
        $sql = "INSERT INTO asignacion_planilla_docente (id, planilla_docente, toma)
                VALUES (:id, :planilla_docente, :toma)";

        $stmt = $this->pdo->prepare($sql);

        // Bind values to the placeholders
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':planilla_docente', $planilla_docente);
        $stmt->bindParam(':toma', $toma);

        // Execute the statement
        $stmt->execute();
    }


    //********** INSERT TOMA **********/
    public function insertTomaPendienteAI($id_curso, $id_persona){
        $id_toma = uniqid();

        // Insert new person
        $sql = "INSERT INTO toma (id, estado, tipo_movimiento, estado_contralor, 
                                    curso, docente) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        return $this->pdo->prepare($sql)->execute([
            $id_toma,
            'Pendiente', 'AI', 'Pasar',
            $id_curso, $id_persona
        ]);
    }
}
