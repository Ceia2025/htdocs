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
    $curso_id = $_POST['curso_id'];

    // Verificar si curso_id no está vacío
    if (empty($curso_id)) {
        echo "<option value=''>Curso no seleccionado</option>";
        exit();
    }

    $sql = "SELECT h.id, h.dia, h.inicio, h.fin 
            FROM horarios h 
            INNER JOIN curso_horario ch ON h.id = ch.horario_id 
            WHERE ch.curso_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $curso_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['dia'] . " " . $row['inicio'] . "-" . $row['fin'] . "</option>";
                }
            } else {
                echo "<option value=''>No hay horarios disponibles</option>";
            }
            $stmt->close();
        } else {
            echo "<option value=''>Error al ejecutar la consulta: " . $stmt->error . "</option>";
        }
    } else {
        echo "<option value=''>Error al preparar la consulta: " . $conn->error . "</option>";
    }
    $conn->close();
} else {
    echo "<option value=''>Método de solicitud no permitido</option>";
}
?>
