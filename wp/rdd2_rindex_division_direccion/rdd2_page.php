<?php

use Fines2\DataAccess\AlumnoDAO;
use Fines2\DataAccess\CalificacionDAO;
use Fines2\DataAccess\CollectComisionIdsResult;
use Fines2\DataAccess\ComisionDAO;
use Fines2\DataAccess\DisposicionDAO;
use Fines2\Model\Alumno_;
use Fines2\Model\Disposicion_;
use SqlOrganize\Utils\ValueTypesUtils;

add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Rindex Comisión', // Título de la página
    'Rindex Comisión', //Título del menú
    'edit_posts', // Permisos
    FINES_PLUGIN.'-rdd2',  // Slug del submenú
    'rdd2_page' // Función que muestra la página del submenu
);


function rdd2_page() {
    
    if (!isset($_GET['comision_pfid']) || empty($_GET['comision_pfid'])) {
        echo "<p>Error: No se especificó el pfid.</p>";
        return;
    }

    $pfid = $_GET['comision_pfid'];

    
    /** @var Disposicion_[] */
    $disposiciones = DisposicionDAO::disposicionesDivision($pfid);

    /** @var array<string, Disposicion_> */
    $disposicionesPorId = ValueTypesUtils::dictOfObjByPropertyNames($disposiciones, "id");

    /** @var CollectComisionIdsResult */
    $ccir = ComisionDAO::collectComisionIdsByPfid($pfid);

    /** @var Alumno_[] */
    $alumnos = AlumnoDAO::alumnosComisiones($ccir->id_comisiones);

    /** @var array<string, Alumno_> */
    $alumnosPorId = ValueTypesUtils::dictOfObjByPropertyNames($alumnos, "id");

    /** @var array<string, array> */
    $calificaciones = CalificacionDAO::calificacionesAprobadasAlumnosDisposiciones(
        array_keys($alumnosPorId),
        array_keys($disposicionesPorId)
    );

    // construir matriz alumno-disposición
    $tabla = [];

    foreach ($calificaciones as $r) {

        $alumno = $r['alumno'];
        $disp   = $r['disposicion'];

        $nota = "";

        if ((float)$r['nota_final'] >= 7) {
            $nota = (int)$r['nota_final'];
        } elseif ((float)$r['crec'] > 4) {
            $nota = (int)$r['crec'] . "c";
        }

        $tabla[$alumno][$disp] = $nota;
    }


    include plugin_dir_path(__FILE__) . 'rdd2_table_html.php';

}
?>
