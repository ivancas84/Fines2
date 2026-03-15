<div class="wrap">
    <h1>Transferir Persona</h1>
    <form method="POST">
        <table class="form-table">
		<tr>
                <th><label>DNI origen (se eliminará):</label></th>
                <td><input type="text" name="dni_origen" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label>DNI destino (se transfiere todo el origen):</label></th>
                <td><input type="text" name="dni_destino" class="regular-text" required></td>
            </tr>
        </table>
        <p><input type="submit" name="submit" class="button button-primary" value="Guardar"></p>
    </form>
</div>