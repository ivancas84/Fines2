<?php

//Definir sql para transferir alumnos aprobados


require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Context;
use Fines2\Model\Calificacion_;
use Fines2\Model\Comision_;
use Fines2\DataAccess\ComisionDAO;
use Fines2\Model\AlumnoComision_;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\Db;

try {

    /** @var Db */ $dbf = Context::getFinesDb();
    /** @var DataProvider */ $dataProvider =  $dbf->CreateDataProvider();
    /** @var Comision_[] */ $comisionesSemestreAnterior = ComisionDAO::comisionesConSiguienteSin32ByCalendario(CALENDARIO_ID_ANTERIOR);
    
    echo "<h3>Comisiones a procesar: " . count($comisionesSemestreAnterior) . "</h3>";
    
    
    foreach($comisionesSemestreAnterior as $comision){
        echo "<h2>Procesando " . $comision->pfid . " " . $comision->calendario_->getLabel() . "</h2>";

        /** @var AlumnoComision_[] */ $alumnosComisionActivos =  $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision" => $comision->id, "activo" => true]);
        /** @var AlumnoComision_[] */ $alumnosComisionNoActivos =  $dataProvider->fetchAllEntitiesByParams("alumno_comision", ["comision" => $comision->id, "activo" => false]);

        echo "cantidad de alumnos Activos " . count($alumnosComisionActivos) . " - No activos " . count($alumnosComisionNoActivos) . "<br>";

        /** @var AlumnoComision_[] */ $idAlumnoComisionSiguiente =  $dataProvider->fetchPairs("alumno_comision", "id", "alumno", ["comision" => $comision->comision_siguiente]);
        echo "cantidad de alumnos Activos  comision siguiente " . $comision->comision_siguiente . " = " . count($idAlumnoComisionSiguiente) . " - No activos " . count($alumnosComisionNoActivos) . "<br>";

        echo "<pre>";
        print_r($idAlumnoComisionSiguiente);
        $modifyQueries = $dbf->CreateModifyQueries();

        foreach($alumnosComisionActivos as $aca){
            /** @var AlumnoComision_ */ $alumnoComision = new AlumnoComision_();

            $alumnoComision->activo = true;
            $alumnoComision->alumno = $aca->alumno;
            $alumnoComision->comision = $comision->comision_siguiente;
            $alumnoComision->estado = "Regular";
            if(!in_array($alumnoComision->alumno, $idAlumnoComisionSiguiente))
                $modifyQueries->insertSql($alumnoComision);
        }

        $modifyQueries->process();
    }   





    

    //$dataProvider = $db->CreateDataProvider();
    //$modifyQueries = $db->CreateModifyQueries();

    //echo "<p>Estado modificado correctamente, cierre esta pantalla y presione F5 para refrescar</p>";

} catch (Exception $ex){
  echo $ex->getMessage();

}