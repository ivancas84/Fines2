<?php
// Ejemplo 2: Obtener información de un alumno para modificación
    $dni = "30123456";
    echo "Obteniendo información del alumno con DNI {$dni}...\n";
    $datosAlumno = ProgramaFines::PF_InfoAlumnoFormularioModificacion($client, $dni);
    echo "Información obtenida: \n";
    print_r($datosAlumno);
    echo "\n";
    
    // Ejemplo 3: Actualizar datos de un alumno
    echo "Actualizando datos del alumno...\n";
    $datosAlumno['email'] = 'nuevo_email@ejemplo.com';
    $datosAlumno['telefono'] = '1122334455';
    ProgramaFines::PF_ActualizarFormularioAlumno($client, $datosAlumno);
    echo "Datos actualizados correctamente.\n\n";
    
    // Ejemplo 4: Inscribir un nuevo estudiante
    echo "Inscribiendo un nuevo estudiante...\n";
    
    $persona = new stdClass();
    $persona->nombres = "Juan Carlos";
    $persona->apellidos = "Pérez";
    $persona->numero_documento = "33456789";
    $persona->cuil1 = "20";
    $persona->cuil2 = "7";
    $persona->descripcion_domicilio = "Av. Siempreviva 742";
    $persona->departamento = "3B";
    $persona->localidad = "Springfield";
    $persona->partido = "Springfield";
    $persona->email = "jperez@ejemplo.com";
    $persona->codigo_area = "011";
    $persona->telefono = "45678901";
    $persona->nacionalidad = "Argentina";
    $persona->sexo = "1";
    
    ProgramaFines::PF_InscribirEstudianteValues($client, $comisionId, $persona);
    echo "Estudiante inscripto correctamente.\n\n";
    
    // Ejemplo 5: Cambiar un estudiante de comisión
    $comisionNueva = "COMISION456";
    echo "Cambiando al estudiante con DNI {$dni} a la comisión {$comisionNueva}...\n";
    ProgramaFines::PF_CambiarComision($client, $dni, $comisionNueva);
    echo "Estudiante cambiado a nueva comisión correctamente.\n\n";
    