<?php
require_once '../fines-config.php';

$db = \App\Context::getFinesDb();
$toma = $db->createDataProvider()->fetchEntityByParams($_REQUEST["entity_name"], ["id" => $_REQUEST["toma_id"]]);
$toma->ssetFromArray($_REQUEST);
echo "<h3>Modificar " . $_REQUEST["entity_name"] . "</h3>";
if($toma->_status < 1){
    $toma->update();
    echo $toma->htmlChangeLog();
    echo "<p>Registro actualizado.</p>";
} else {
    echo "<p>No se realizaron modificaciones.</p>";
}

echo "
<p>Esta pestaña se cerrará automáticamente.</p>
<script>
setTimeout(() => {
    window.close();
}, 3000);
</script>
";
?>

