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
	include_once('conexion.php');

	if(isset($_POST['retiro'])){
		$database = new Connection();
		$db = $database->open();
		try{
			// Hacer uso de una declaración preparada para prevenir la inyección de SQL
			$stmt = $db->prepare("INSERT INTO retiros (fretiro, hretiro, jretiro, factual, alum_idalum, alum_run) VALUES (:fecha_retiro, :hora_retiro, :justifica_retiro, :fecha_actual_retiro, :id, :run)");

			// Instrucción if-else en la ejecución de nuestra declaración preparada
			$inserted = $stmt->execute(array(':fecha_retiro' => $_POST['fretiro'] , ':hora_retiro' => $_POST['hretiro'] , ':justifica_retiro' => $_POST['jretiro'], ':fecha_actual_retiro' => $_POST['factual'], ':id' => $_POST['id'], ':run' => $_POST['run']));

			if ($inserted) {
				// Actualizar la columna fretiro en la tabla alumno
				$stmt_update = $db->prepare("UPDATE alum SET fretiro = :fecha_retiro WHERE run = :run");
				$stmt_update->execute(array(':fecha_retiro' => $_POST['fretiro'], ':run' => $_POST['run']));
				$_SESSION['message'] = 'Fecha de Retiro Actualizada!!!';
			} else {
				$_SESSION['message'] = 'Algo salió mal. No se puede guardar';
			}
		} catch(PDOException $e){
			$_SESSION['message'] = $e->getMessage();
		}

		// Cerrar la conexión
		$database->close();
	} else {
		$_SESSION['message'] = 'Ingrese el retiro';
	}

	header('location:in.retiro.php');
?>
