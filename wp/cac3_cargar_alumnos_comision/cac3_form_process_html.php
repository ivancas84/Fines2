<form method="POST" action="admin-post.php">
    <?php wp_html_init_form("cac2_process", "comision_id", $comision->id); ?>
    <input type="hidden" name="data" value="<?=esc_attr($rawData);?>"></textarea>
    
    <p>
        <input type="submit" name="submit" value="Guardar" class="button button-primary">
    </p>
    
</form>

