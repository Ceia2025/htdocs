
<?php
// ------------------------------------------------------------
// SISTEMA DE GESTIÓN Y ADMINISTRACIÓN ESCOLAR
// ------------------------------------------------------------
// Autor: Felipe Gutierrez Alfaro
// Fecha de creación: 2025-03-27
// Versión: 1.0
// 
// Copyright (c) 2025 Felipe Gutierrez Alfaro. Todos los derechos reservados.
// 
// Este código fuente está sujeto a derechos de autor y ha sido facilitado 
// de manera gratuita y de por vida a la Escuela Juanita Zuñiga Fuentes CEIA PARRAL.
// 
// Restricciones y condiciones:
// - Este código NO puede ser vendido, ni distribuido sin autorización del autor.
// - Solo la Escuela Juanita Zuñiga Fuentes tiene derecho a usar este código sin costo y modificarlo.
// - Además cualquier actualización futura también será proporcionada de forma gratuita si la 
//   escuela así lo acepta.
// - No se otorga ninguna garantía implícita o explícita. Uso bajo su propia responsabilidad.
// - El establecimiento puede utilizar imágenes a su gusto y asignar el nombre que estime conveniente al sistema.
// 
// Contacto: 
// LinkedIn: https://www.linkedin.com/in/felipegutierrezalfaro/
// Email: felipe.gutierrez.alfaro@gmail.com
?><?php
include_once '../../includes/config.php';

if (isset($_POST['curso'])) {
    $curso_id = $_POST['curso'];

    $sql = "
    SELECT DISTINCT asignaturas.id AS asignatura_id, asignaturas.nombre AS asignatura_nombre, 
           docentes.nombre AS docente_nombre, docentes.apellido AS docente_apellido
    FROM asignaturas_cursos
    JOIN asignaturas ON asignaturas.id = asignaturas_cursos.asignatura_id
    JOIN docentes ON docentes.id = asignaturas_cursos.docentes_id
    WHERE asignaturas_cursos.curso_id = '$curso_id'";

    $result = $conn->query($sql);
    
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
