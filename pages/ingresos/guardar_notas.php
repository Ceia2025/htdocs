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
?><?php
session_start();
include_once '../../includes/config.php';

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $curso = $_POST['curso'];
    $asignatura = $_POST['asignatura'];

    // Preparar las consultas para evitar inyección SQL
    $sql_verificar = $conn->prepare("SELECT * FROM alum_notas WHERE run_alum_nota = ? AND asignaturas_id = ? AND num_nota = ? AND semestre = ?");
    $sql_insertar = $conn->prepare("INSERT INTO alum_notas (run_alum_nota, fecha, nota, asignaturas_id, num_nota, semestre) VALUES (?, NOW(), ?, ?, ?, ?)");

    foreach ($_POST['alumno'] as $index => $run_alumno) {
        // Notas del primer semestre
        foreach ($_POST["nota_1s_$run_alumno"] as $num_nota => $nota) {
            if (strtolower($nota) === 'p') {
                $nota = 0.0;
            }
            if ($nota === '0' || $nota == 0 || !empty($nota)) {
                $semestre = 1;
                $num_nota_real = $num_nota + 1;

                $sql_verificar->bind_param('siii', $run_alumno, $asignatura, $num_nota_real, $semestre);
                $sql_verificar->execute();
                $result_verificar = $sql_verificar->get_result();

                if ($result_verificar->num_rows == 0) {
                    $sql_insertar->bind_param('ssiii', $run_alumno, $nota, $asignatura, $num_nota_real, $semestre);
                    $sql_insertar->execute();
                }
            }
        }

        // Notas del segundo semestre
        foreach ($_POST["nota_2s_$run_alumno"] as $num_nota => $nota) {
            if (strtolower($nota) === 'p') {
                $nota = 0.0;
            }
            if ($nota === '0' || $nota == 0 || !empty($nota)) {
                $semestre = 2;
                $num_nota_real = $num_nota + 1;

                $sql_verificar->bind_param('siii', $run_alumno, $asignatura, $num_nota_real, $semestre);
                $sql_verificar->execute();
                $result_verificar = $sql_verificar->get_result();

                if ($result_verificar->num_rows == 0) {
                    $sql_insertar->bind_param('ssiii', $run_alumno, $nota, $asignatura, $num_nota_real, $semestre);
                    $sql_insertar->execute();
                }
            }
        }
    }

    // Cerrar las consultas preparadas
    $sql_verificar->close();
    $sql_insertar->close();

    // Redirigir con un mensaje de éxito
    $_SESSION['mensaje'] = "Notas guardadas exitosamente.";
    header("Location: in.notas.php");
    exit();
}

$conn->close();
?>
