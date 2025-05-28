<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db_config.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/PdoFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/ProgramaFines.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/class/Tools.php');

class CalendarioDAO
{
    public static function calendarios($fetchMode = PDO::FETCH_ASSOC)
    {
        $pdo = new PdoFines();
        $query = "SELECT * FROM calendario";
        $stmt = $pdo->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll($fetchMode);
    }

}