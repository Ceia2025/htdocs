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
session_start();
include_once '../../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['curso_id']) && isset($_POST['horario_id'])) {
    $curso_id = $_POST['curso_id'];
    $horarios = $_POST['horario_id']; // Array de horarios seleccionados

    // Preparar la inserción de cada horario para el curso
    foreach ($horarios as $horario_id) {
        $sql = "INSERT INTO curso_horario (curso_id, horario_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $curso_id, $horario_id);

        if ($stmt->execute()) {
            $mensaje = "exito";
        } else {
            $mensaje = "error";
            break;
        }
    }
    $stmt->close();
} else {
    $mensaje = "error";
}

$conn->close();

header("Location: f.asignar_horario_curso.php?mensaje=$mensaje");
exit();
?>
