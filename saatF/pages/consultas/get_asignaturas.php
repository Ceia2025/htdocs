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

include_once '../../includes/config.php';

if (isset($_POST['curso'])) {
    $curso_id = $_POST['curso'];

    $stmt = $conn->prepare("
    SELECT DISTINCT asignaturas.id AS asignatura_id, asignaturas.nombre AS asignatura_nombre, 
           docentes.nombre AS docente_nombre, docentes.apellido AS docente_apellido
    FROM asignaturas_cursos
    JOIN asignaturas ON asignaturas.id = asignaturas_cursos.asignatura_id
    JOIN docentes ON docentes.id = asignaturas_cursos.docentes_id
    WHERE asignaturas_cursos.curso_id = ?");
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$result = $stmt->get_result();

    
    if ($result->num_rows > 0) {
        echo '<option value="">Seleccione una asignatura</option>';
        while ($row = $result->fetch_assoc()) {
            echo "<option value='".$row['asignatura_id']."'>".$row['asignatura_nombre']." (".$row['docente_nombre']." ".$row['docente_apellido'].")</option>";
        }
    } else {
        echo '<option value="">No hay asignaturas asignadas</option>';
    }
}
?>
