<?php
// ------------------------------------------------------------
// SISTEMA DE GESTIÓN Y ADMINISTRACIÓN ESCOLAR
// ------------------------------------------------------------
// Autor: Felipe Gutierrez Alfaro
// Fecha de creación: 2025-03-27
// Versión: 1.0
// 
// Copyright (c) 2025 Felipe Gutierrez Alfaro. Todos los derechos reservados.
// 
// Este código fuente está sujeto a derechos de autor y ha sido facilitado 
// de manera gratuita y de por vida a la Escuela Juanita Zuñiga Fuentes CEIA PARRAL.
// 
// Restricciones y condiciones:
// - Este código NO puede ser vendido, ni distribuido sin autorización del autor.
// - Solo la Escuela Juanita Zuñiga Fuentes tiene derecho a usar este código sin costo y modificarlo.
// - Además cualquier actualización futura también será proporcionada de forma gratuita si la 
//   escuela así lo acepta.
// - No se otorga ninguna garantía implícita o explícita. Uso bajo su propia responsabilidad.
// - El establecimiento puede utilizar imágenes a su gusto y asignar el nombre que estime conveniente al sistema.
// 
// Contacto: 
// LinkedIn: https://www.linkedin.com/in/felipegutierrezalfaro/
// Email: felipe.gutierrez.alfaro@gmail.com
?>
<?php
include '../../includes/config.php';

if (isset($_POST['curso_id'])) {
    $curso_id = $_POST['curso_id'];

    // Consulta para obtener las asignaturas únicas del curso seleccionado con los docentes
    $sql_asignaturas = "SELECT DISTINCT a.id AS asignatura_id, a.nombre AS asignatura_nombre, 
                               d.nombre AS docente_nombre, d.apellido AS docente_apellido
                        FROM asignaturas a
                        JOIN asignaturas_cursos ac ON a.id = ac.asignatura_id
                        JOIN docentes d ON d.id = ac.docentes_id
                        WHERE ac.curso_id = ?";
    
    $stmt = $conn->prepare($sql_asignaturas);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Devolver las asignaturas junto con los docentes en formato JSON
    $asignaturas = [];
    while ($row = $result->fetch_assoc()) {
        $asignaturas[] = [
            'id' => $row['asignatura_id'], 
            'nombre' => $row['asignatura_nombre'], 
            'docente' => $row['docente_nombre'] . " " . $row['docente_apellido']

            
        ];
    }
    
    echo json_encode($asignaturas);
}
?>
