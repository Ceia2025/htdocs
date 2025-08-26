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
// Email: felipe.gutierrez.alfaro@gmail.com
?>
<?php
// Incluir el archivo de conexión
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el estado civil seleccionado del formulario
    $estado_civil_seleccionado = $_POST["estado_civil"];

// Obtener el valor de la columna run del formulario
        $idAlumno = $_POST["run"];

    // Consulta SQL para actualizar el estado civil en la tabla alum
    $consulta_update = "UPDATE alum SET estado_civil = '$estado_civil_seleccionado' WHERE run = '$idAlumno'";
    if ($con->query($consulta_update) === TRUE) {
        echo "Estado civil actualizado correctamente";
    } else {
        echo "Error al actualizar el estado civil: " . $con->error;
    }
}

// Cerrar la conexión
$con->close();
?>