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
include_once('../../includes/conexion.php');

if(isset($_POST['atraso'])){
    $database = new Connection();
    $db = $database->open();
    try{
        // Hacer uso de una declaración preparada para prevenir la inyección de SQL
        $stmt = $db->prepare("INSERT INTO atrasos(fatraso, hatraso, jatraso, alum_idalum, alum_run) VALUES (:fecha_atraso, :hora_atraso, :justifica_atraso, :id, :run)");
        
        // Instrucción if-else en la ejecución de nuestra declaración preparada
        $_SESSION['message'] = ( $stmt->execute(array(':fecha_atraso' => $_POST['fatraso'] , ':hora_atraso' => $_POST['hatraso'] , ':justifica_atraso' => $_POST['jatraso'], ':id' => $_POST['id'], ':run' => $_POST['run'] ))) ? 'Atraso ingresado exitosamente!' : 'Algo salió mal. No se puede guardar';
    }
    catch(PDOException $e){
        $_SESSION['message'] = $e->getMessage();
    }
    $database->close();
}
else{
    $_SESSION['message'] = 'Ingrese el Atraso';
}

header('Location: in.atraso.php');
exit();
?>
