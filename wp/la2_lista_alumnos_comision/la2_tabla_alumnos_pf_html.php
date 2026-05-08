<?php
/** @var array<int, array<string,mixed>> $alumnos_pf */ 
?>

<h2>Programa Fines <?=$comision->getLabel() ?> - Cantidad = <?=count($alumnos_pf)?></h2>

<table class="wp-list-table widefat striped">   
    <thead>
        <tr>            
            <th>Nombre</th>
            <th>DNI</th>
            <th>Fecha Nacimiento</th>
            <th>Email</th>

        </tr>
    </thead>
    <tbody>
        <? foreach ($alumnos_pf as $apf): ?>
            <tr>
                <td><?= esc_html($apf["nombre"] ?? "?"); ?></td>
                <td><?= esc_html($apf["numero_documento"] ?? "?"); ?></td>
                <td><?= esc_html($apf["fecha_nacimiento"] ?? "?"); ?></td>
                <td><?= esc_html($apf["email"] ?? "?"); ?></td>
                <td>
                     <form method="post" action="<?=MAIN_URL?>script/pf_agregar_alumno_comision.php" style="display:inline;" 
                            onsubmit="return confirm('Desea agregar alumno a la comisión de planfines2.com.ar?');">
                            <input type="hidden" name="comision_id" value="<?= esc_attr($comision->id) ?>"/>
                            <input type="hidden" name="numero_documento" value="<?= esc_attr($apf["numero_documento"]) ?>"/>
                            <button type="submit" title="Agregar a planfines2.com.ar" >
                                <span class="dashicons dashicons-plus"></span>
                            </button>
                    </form>
                    
                    <a target="_blank" href="<?= esc_url(PF_URL. $apf["historial"])?>" 
                        title="Ver Historial desde Programafines">
                        <button >
                            <span class="dashicons dashicons-admin-multisite"></span>
                        </button>
                    </a>
                   
                    <a target="_blank" href="<?= esc_url(PF_URL. $apf["modificar"])?>" 
                        title="Modificar desde Programafines">
                        <button >
                            <span class="dashicons dashicons-edit"></span>
                        </button>
                    </a>

                    <a href="<?= esc_url(PF_URL. $apf["eliminar"])?>" 
                        title="Eliminar de la lista en PF" 
                        onclick="return confirm('¿Eliminar alumno de la comisión?');">
                        <button >
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </a>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>
<p>
<form method="post" action="<?=MAIN_URL?>script/pf_agregar_alumnos.php" style="display:inline;" 
                            onsubmit="return confirm('Desea agregar todos los alumnos de la base planfines2.com.ar a programafines.ar?');">
                            <input type="hidden" name="comision_id" value="<?= esc_attr($comision->id) ?>"/>
                            <button type="submit" title="Agregar todo a programafines.ar" >
                                AGREGAR TODO A PROGRAMAFINES
                            </button>
                    </form>
</p>