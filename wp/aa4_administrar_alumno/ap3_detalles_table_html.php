<h2>Detalle</h2>
<p>Los nuevos archivos se almacenan en el <a href="https://drive.google.com/drive/folders/1XobFOQFsI3f5rRi_Gk8lnSu5j23cWrUB" target="_blank">Drive</a></p>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Descripción</th>
            

        </tr>
    </thead>
    <tbody>
        <?php foreach ($detalles as $detalle): ?>
            <tr>
                <td>
                    <a href="https://planfines2.com.ar/upload/<?=$detalle->archivo_?->content?>" download="<?=$detalle->descripcion?>"><?= $detalle->descripcion ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
