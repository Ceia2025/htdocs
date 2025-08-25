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
//header("Content-Type: text/html;charset=utf-8");

/**
 * $usuario  = "root";
 * $password = "ceia2025";
 * $servidor = "localhost";
 * $basededatos = "saatdevtest";
 * $puerto = 3308;
 * $conn = mysqli_connect($servidor, $usuario, $password,$puerto ) or die("No se ha podido conectar al Servidor");
 * mysqli_query($conn,"SET SESSION collation_connection ='utf8_unicode_ci'");
 * $db = mysqli_select_db($conn, $basededatos) or die("Upps! Error en conectar a la Base de Datos");
*/
 
require_once __DIR__ . '/conexion.php';

$database = new Connection();
$conn = $database->open();

?>
