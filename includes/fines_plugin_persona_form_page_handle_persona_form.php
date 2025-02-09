<?php
add_action('wp_ajax_handle_persona_form', 'handle_persona_form'); //procesar formulario en el backend
add_action('wp_ajax_nopriv_handle_persona_form', 'handle_persona_form'); //permitir el envío desde usuarios no autenticados

// Procesar el formulario en el backend
function handle_persona_form() {
    if (!isset($_POST['custom_form_nonce']) || !wp_verify_nonce($_POST['custom_form_nonce'], 'custom_form_action')) {
        echo json_encode(['success' => false, 'message' => 'Error de seguridad.']);
        die();
    }

    if (!empty($_POST['honeypot'])) {
        echo json_encode(['success' => false, 'message' => 'Detección de spam.']);
        die();
    }

    $name = sanitize_text_field($_POST['nombres']);
    $email = sanitize_email($_POST['apellidos']);
    $message = sanitize_text_field($_POST['numero_documento']);


    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        die();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido.']);
        die();
    }

    // Aquí podrías guardar los datos en la base de datos o enviar un correo
/*
    $persona_id = intval($_POST['id']);
    // Sanitize and update the form data
	$nombres = sanitize_text_field($_POST['nombres']);
	$apellidos = sanitize_text_field($_POST['apellidos']);
	$numero_documento = sanitize_text_field($_POST['numero_documento']);
	$telefono = sanitize_text_field($_POST['telefono']);

	// Update the database
	$updated = $wpdb->update(
		'persona',
		[
			'nombres' => $nombres,
			'apellidos' => $apellidos,
			'numero_documento' => $numero_documento,
			'telefono' => $telefono
		],
		['id' => $persona_id],
		['%s', '%s', '%s', '%s'],
		['%d']
	);

*/
    echo json_encode(['success' => true, 'message' => 'Formulario enviado con éxito.']);
    die();
}