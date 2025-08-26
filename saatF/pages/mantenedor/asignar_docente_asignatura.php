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
include './funciones.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $docente_id = $_POST['docente_id'];
    $asignatura_id = $_POST['asignatura_id'];

    asignarAsignaturaADocente($docente_id, $asignatura_id);
         // Redirigir a index.php después de 2 segundos
         header("Refresh: 2; url=f.asignar_docente_asignatura.php");
         echo "Redirigiendo a la página principal en 2 segundos...";
         exit();
}
?>
