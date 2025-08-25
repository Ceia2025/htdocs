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

if (isset($_POST['curso_id'])) {
    $curso_id = $_POST['curso_id'];
    
    // Obtener alumnos según el curso seleccionado
    $sql_alumnos = "
        SELECT run, nombres, apaterno, amaterno 
        FROM alum 
        WHERE codtipoense = (SELECT codtipoense FROM cursos WHERE id = '$curso_id')
        AND codgrado = (SELECT codgrado FROM cursos WHERE id = '$curso_id')
        ORDER BY apaterno ASC";
    
    $result_alumnos = $conn->query($sql_alumnos);
    
    if ($result_alumnos->num_rows > 0) {
        while ($row_alumno = $result_alumnos->fetch_assoc()) {
            echo "<option value='" . $row_alumno['run'] . "'>" . $row_alumno['apaterno'] . " " . $row_alumno['amaterno'] . " " . $row_alumno['nombres'] . "</option>";
        }
    } else {
        echo "<option value=''>No se encontraron alumnos</option>";
    }
}
?>
