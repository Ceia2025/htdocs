<?php
// ------------------------------------------------------------

// Autor: Felipe Gutierrez Alfaro
// Fecha de creación: 2024-11-01
// Versión: 1.0
// 
// Copyright (c) 2024 Felipe Gutierrez Alfaro. Todos los derechos reservados.
// 
// Este código fuente está sujeto a derechos de autor y ha sido facilitado 
// de manera gratuita y de por vida a la Escuela Juanita Zuñiga Fuentes CEIA PARRAL.
// 
// Restricciones y condiciones:
// - Este código NO puede ser vendido, ni redistribuido.
// - Solo la Escuela Juanita Zuñiga Fuentes tiene derecho a usar este código sin costo y modificarlo a su gusto.
// - No se otorga ninguna garantía implícita o explícita. Uso bajo su propia responsabilidad.
// - El establecimiento puede utilizar imágenes a su gusto y asignar el nombre que estime conveniente al sistema.
// 
// Contacto: 
// Email: felipe.gutierrez.alfaro@gmail.comS
?>

<?php
function calcularEdad($fecha_nacimiento) {
    // Convertir la fecha de nacimiento en un objeto de fecha
    $fecha_nacimiento = new DateTime($fecha_nacimiento);
    // Obtener la fecha actual
    $fecha_actual = new DateTime();
    // Obtener la fecha del 30 de junio del año en curso
    $fecha_fin_periodo = new DateTime(date('Y') . '-06-30');

    // Calcular la diferencia entre la fecha actual y la fecha de nacimiento
    $diferencia_edad = $fecha_nacimiento->diff($fecha_fin_periodo);

    // Obtener la edad en años
    $edad = $diferencia_edad->y;

    return $edad;
}
?>