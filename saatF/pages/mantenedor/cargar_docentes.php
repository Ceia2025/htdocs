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
include_once '../../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $asignatura_id = $_POST['asignatura_id'];

    $sql = "SELECT d.id, d.nombre, d.apellido FROM docentes d INNER JOIN docente_asignatura da ON d.id = da.docente_id WHERE da.asignatura_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $asignatura_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . " " . $row['apellido'] . "</option>";
            }
        } else {
            echo "<option value=''>No hay docentes disponibles</option>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
