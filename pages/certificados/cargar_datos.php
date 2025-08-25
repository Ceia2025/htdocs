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
include '../../includes/config.php';

$curso_id = $_GET['curso_id'];

// Consultar asignaturas únicas del curso
$sql_asignaturas = "
    SELECT DISTINCT asignaturas.id, asignaturas.nombre 
    FROM asignaturas 
    JOIN asignaturas_cursos ON asignaturas.id = asignaturas_cursos.asignatura_id 
    WHERE asignaturas_cursos.curso_id = '$curso_id'";
$result_asignaturas = $conn->query($sql_asignaturas);

$asignaturas = [];
while ($row = $result_asignaturas->fetch_assoc()) {
    $asignaturas[] = $row;
}

// Consultar alumnos del curso
$sql_alumnos = "
    SELECT alum.run, 
           CONCAT(alum.nombres, ' ', alum.apaterno, ' ', alum.amaterno) AS nombre_completo
    FROM alum
    WHERE alum.codgrado = (
        SELECT codgrado FROM cursos WHERE id = '$curso_id'
    ) AND alum.codtipoense = (
        SELECT codtipoense FROM cursos WHERE id = '$curso_id'
    )";
$result_alumnos = $conn->query($sql_alumnos);

$alumnos = [];
while ($row = $result_alumnos->fetch_assoc()) {
    $alumnos[] = $row;
}

// Respuesta JSON
echo json_encode([
    "asignaturas" => $asignaturas,
    "alumnos" => $alumnos
]);
?>