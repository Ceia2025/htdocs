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
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "saat2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$curso = explode(",", $_POST['curso']);
$descgrado = $curso[0];
$codtipoense = $curso[1];
$codgrado = $curso[2];
$fecha = $_POST['fecha_seleccionada']; // Usa la fecha seleccionada del formulario

$asistencias = $_POST['asistencia'];

foreach ($asistencias as $run => $estado) {
    $sql = "INSERT INTO alum_asiste (run_alum_asiste, fecha, estado, codtipoense, codgrado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $run, $fecha, $estado, $codtipoense, $codgrado);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Asistencia guardada correctamente";
?>
