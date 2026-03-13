<?php



///no se incluye codigo de menu porque se utiliza como shortcode

use Fines2\DataAccess\CursoDAO;

function tp2_shortcode() {
    $cursos = CursoDAO::CursosAutorizadosPublicadosByCalendario(CALENDARIO_ID_ACTUAL);  
    echo "<div class=\"wrap\">";
            
    if ($cursos) {
        include plugin_dir_path(__FILE__) . 'tp2_tabla_html.php';
    } else {
            echo "<p>No se encontraron cursos para tomar posesión.</p>";
    }

    echo "</div>";
}