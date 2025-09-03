<?php


require_once($_SERVER['DOCUMENT_ROOT'] . '/db-config.php');

use Fines2\Comision_;
use Fines2\Calificacion_;
use Fines2\Curso_;
use Fines2\Persona_;
use Fines2\Alumno_;
use Fines2\AlumnoComision_;
use Fines2\AlumnoComisionDAO;
use Fines2\AlumnoDAO;
use Fines2\CalificacionDAO;
use Fines2\CursoDAO;
use Fines2\PersonaDAO;
use Fines2\Toma_;
use Fines2\TomaDAO;
use ProgramaFines\PfUtils;
use SqlOrganize\Sql\DataProvider;
use SqlOrganize\Sql\DbMy;
use SqlOrganize\Sql\ModifyQueries;
use SqlOrganize\Utils\ValueTypesUtils;


///no se incluye codigo de menu porque se utiliza como shortcode

function tp_toma_posesion_shortcode() {
      /** @var DataProvider */ $dataProvider = \App\Context::getFinesDb()->CreateDataProvider();

  $cursos = CursoDAO::CursosAutorizadosPublicadosByCalendario(CALENDARIO_ID_ACTUAL);  
    echo "<div class=\"wrap\">";
            
    if ($cursos) {
        include plugin_dir_path(__FILE__) . 'tp_tabla_html.php';
    } else {
            echo "<p>No se encontraron cursos para tomar posesión.</p>";
    }

    echo "</div>";
}