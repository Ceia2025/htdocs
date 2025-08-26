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

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $curso = $_POST['curso'];
    $asignatura = $_POST['asignatura'];

    // Preparar la consulta para verificar la existencia de notas
    $sql_verificar = $conn->prepare("SELECT * FROM alum_notas WHERE run_alum_nota = ? AND asignaturas_id = ? AND num_nota = ? AND semestre = ?");
    if (!$sql_verificar) {
        die("Error en la preparación de la consulta verificar: " . $conn->error);
    }

    // Preparar la consulta para actualizar las notas
    $sql_actualizar = $conn->prepare("UPDATE alum_notas SET nota = ?, fecha = NOW() WHERE run_alum_nota = ? AND asignaturas_id = ? AND num_nota = ? AND semestre = ?");
    if (!$sql_actualizar) {
        die("Error en la preparación de la consulta actualizar: " . $conn->error);
    }

    // Preparar la consulta para eliminar notas
    $sql_eliminar = $conn->prepare("DELETE FROM alum_notas WHERE run_alum_nota = ? AND asignaturas_id = ? AND num_nota = ? AND semestre = ?");
    if (!$sql_eliminar) {
        die("Error en la preparación de la consulta eliminar: " . $conn->error);
    }

    foreach ($_POST['alumno'] as $index => $run_alumno) {
        // Notas del primer semestre
        foreach ($_POST["nota_1s_$run_alumno"] as $num_nota => $nota) {
            $semestre = 1;
            $num_nota_real = $num_nota + 1;

            $sql_verificar->bind_param('siii', $run_alumno, $asignatura, $num_nota_real, $semestre);
            $sql_verificar->execute();
            $result_verificar = $sql_verificar->get_result();

            // Si la nota ya existe
            if ($result_verificar->num_rows > 0) {
                if (!empty($nota)) {
                    // Actualizar la nota si no está vacía
                    $sql_actualizar->bind_param('ssiii', $nota, $run_alumno, $asignatura, $num_nota_real, $semestre);
                    if (!$sql_actualizar->execute()) {
                        echo "Error al actualizar la nota: " . $conn->error;
                    }
                } else {
                    // Eliminar la nota si está vacía
                    $sql_eliminar->bind_param('siii', $run_alumno, $asignatura, $num_nota_real, $semestre);
                    if (!$sql_eliminar->execute()) {
                        echo "Error al eliminar la nota: " . $conn->error;
                    }
                }
            }
        }

        // Notas del segundo semestre
        foreach ($_POST["nota_2s_$run_alumno"] as $num_nota => $nota) {
            $semestre = 2;
            $num_nota_real = $num_nota + 1;

            $sql_verificar->bind_param('siii', $run_alumno, $asignatura, $num_nota_real, $semestre);
            $sql_verificar->execute();
            $result_verificar = $sql_verificar->get_result();

            // Si la nota ya existe
            if ($result_verificar->num_rows > 0) {
                if (!empty($nota)) {
                    // Actualizar la nota si no está vacía
                    $sql_actualizar->bind_param('ssiii', $nota, $run_alumno, $asignatura, $num_nota_real, $semestre);
                    if (!$sql_actualizar->execute()) {
                        echo "Error al actualizar la nota: " . $conn->error;
                    }
                } else {
                    // Eliminar la nota si está vacía
                    $sql_eliminar->bind_param('siii', $run_alumno, $asignatura, $num_nota_real, $semestre);
                    if (!$sql_eliminar->execute()) {
                        echo "Error al eliminar la nota: " . $conn->error;
                    }
                }
            }
        }
    }

    // Cerrar las consultas preparadas
    $sql_verificar->close();
    $sql_actualizar->close();
    $sql_eliminar->close();

    // Redirigir con un mensaje de éxito
    $_SESSION['mensaje'] = "Notas actualizadas/eliminadas exitosamente.";
    header("Location: m.notas.php");
    exit();
}

// Cerrar la conexión
$conn->close();
?>
