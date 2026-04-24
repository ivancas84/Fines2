<?php

use Fines2\DataAccess\AlumnoComisionDAO;
use Fines2\DataAccess\DesignacionDAO;
use Fines2\Model\AlumnoComision_;
use Fines2\Model\Comision;
use Fines2\Model\Comision_;
use ProgramaFines\DataAccess\PfDAO;
use SqlOrganize\Sql\Db;
use SqlOrganize\Utils\ValueTypesUtils;
session_start();

add_submenu_page(
    null, //debe coincidir con el slug del menu
    'Comisiones', // Título de la página
    'Comisiones', //Título del menú
    'edit_posts', // Permisos
    'fines6-plugin-la2',  // Slug del submenú
    'la2_page' // Función que muestra la página del submenu
);

function la2_page() {

    wp_page_message();
    /** @var string */ $pf_session = !empty($_SESSION["PHPSESS"]) ? $_SESSION["PHPSESS"] : null;

    /** @var Db */ $db = \App\Context::getFinesDb();

    /** @var DataProvider */ $dataProvider = $db->CreateDataProvider();

    $comision_id = $_GET["comision_id"];

    /** @var Comision_ */  $comision = $db->createEntityById("comision", $comision_id);

	/** @var AlumnoComision_[] */ $alumnos_comision = AlumnoComisionDAO::alumnosComision($comision_id);

    if (!empty($alumnos_comision)) {
        include plugin_dir_path(__FILE__) . 'la2_tabla_alumnos_comision_html.php';
    } else {
        echo "<p>No se encontraron alumnos para esta comision.</p>";
    }

    if(!empty($pf_session)){
        $pf = new PfDAO($pf_session);

        /** @var array<int, array<string,mixed>> */ $alumnos_pf = $pf->getListaAlumnos($comision->pfid, PF_PERIODO);

        if (!empty($alumnos_pf)) {
            // =============================================
            // 1. EXTRAER los números de documento de ambos arrays
            // =============================================

            // Del primer array ($alumnos_comision - objetos)
            $docs_comision = [];
            foreach ($alumnos_comision as $ac) {
                $doc = $ac->alumno_->persona_->get("numero_documento");
                if ($doc !== null && $doc !== '') {
                    $docs_comision[] = trim((string) $doc);   // trim + cast a string por seguridad
                }
            }
            $docs_comision = array_unique($docs_comision);   // por si hubiera duplicados (aunque no debería)

            // Del segundo array ($alumnos_pf - arrays asociativos)
            $docs_pf = [];
            foreach ($alumnos_pf as $alumno) {
                $doc = $alumno["numero_documento"] ?? null;
                if ($doc !== null && $doc !== '') {
                    $docs_pf[] = trim((string) $doc);
                }
            }
            $docs_pf = array_unique($docs_pf);

            // =============================================
            // 2. COMPARACIONES
            // =============================================

            $solo_en_comision = array_diff($docs_comision, $docs_pf);   // están en el primero, NO en el segundo
            $solo_en_pf       = array_diff($docs_pf, $docs_comision);   // están en el segundo, NO en el primero
            $en_ambas         = array_intersect($docs_comision, $docs_pf); // están en ambos

            // =============================================
            // 3. MOSTRAR RESULTADOS
            // =============================================

            echo "📌 Documentos SOLO en planfines2: " . count($solo_en_comision) . " alumnos\n";
            echo implode(", ", $solo_en_comision) . "<br><br>";

            echo "\n📌 Documentos SOLO en programafines: " . count($solo_en_pf) . " alumnos\n";
            echo implode(", ", $solo_en_pf) . "<br><br>";

            echo "\n📌 Documentos en AMBAS listas: " . count($en_ambas) . " alumnos\n";
            echo implode(", ", $en_ambas) . "<br><br>";

            include plugin_dir_path(__FILE__) . 'la2_tabla_alumnos_pf_html.php';
        } else {
            echo "<p>No se encontraron alumnos en programafines para esta comision en el período indicado.</p>";
        }


    }
}