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

        function alumnosByComisionPfid($comision_pfid) {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT *, alumno.id AS alumno_id, persona.id AS persona_id
                FROM alumno_comision
                INNER JOIN comision ON (comision.id = alumno_comision.comision)
                INNER JOIN alumno ON (alumno.id = alumno_comision.alumno)
                INNER JOIN persona ON (persona.id = alumno.persona)
                WHERE comision.pfid = :pfidComision");
            $stmt->bindParam(':pfidComision', $comision_pfid, PDO::PARAM_INT); // Bind as a string
            $stmt->execute();

            return $stmt->fetchAll();
        }


}