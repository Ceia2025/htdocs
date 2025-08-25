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
	include_once '../../includes/conexion.php';

	if(isset($_POST['otroregistro'])){
		$database = new Connection();
		$db = $database->open();
		try{
			//hacer uso de una declaración preparada para prevenir la inyección de sql
			$stmt = $db->prepare("INSERT INTO anotaciones(anotacion, tipo, fanota, hanota, alum_idalum, alum_run) VALUES (:anotacion, :tipo, :fanota, :hora, :id, :run)");


			//instrucción if-else en la ejecución de nuestra declaración preparada
			$_SESSION['message'] = ( $stmt->execute(array(':anotacion' => $_POST['anota'] , ':tipo' => $_POST['tipoanota'] , ':fanota' => $_POST['fanota'], ':hora' => $_POST['hanota'], ':id' => $_POST['id'], ':run' => $_POST['run']  ))) ? 'Anotación agregada!!!' : 'Algo salió mal. No se puede guardar';
			
			//if-else statement in executing our query
			}
		catch(PDOException $e){
			$_SESSION['message'] = $e->getMessage();
		}

		//Cerrar la conexión
		$database->close();
	}
	else{
		$_SESSION['message'] = 'Ingrese la anotación';
	}

	header('location: in.anota.php');

?>
