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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $curso_id = $_POST['curso_id'];
    $horario_id = $_POST['horario_id'];
    $asignatura_id = $_POST['asignatura_id'];
    $docente_id = $_POST['docente_id'];

    // Consulta SQL para insertar los datos en la tabla asignaturas_cursos
    $sql = "INSERT INTO asignaturas_cursos (curso_id, asignatura_id, horario_id, docentes_id) VALUES (?, ?, ?, ?)";

    // Preparar la consulta y verificar si fue exitosa
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiii", $curso_id, $asignatura_id, $horario_id, $docente_id);
        if ($stmt->execute()) {
            // Redirigir a una página de éxito o mostrar un mensaje de éxito
            echo "<div class='mt-5 alert alert-success' role='alert'>Asignación realizada con éxito.</div>";
                 // Redirigir a index.php después de 2 segundos
     header("Refresh: 1; url=f.asignar_clase.php");
     echo "Redirigiendo a la página principal...";
        } else {
            echo "<div class='mt-5 alert alert-danger' role='alert'>Error al realizar la asignación: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='mt-5 alert alert-danger' role='alert'>Error en la consulta SQL: " . $conn->error . "</div>";
    }

    $conn->close();
}
?>
