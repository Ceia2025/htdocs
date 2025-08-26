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
include_once '../../includes/config.php';


// Validar que se haya enviado el mes y el año, si no, usar el actual
$mes = isset($_POST['mes']) ? (int)$_POST['mes'] : date('m');
$anio = isset($_POST['anio']) ? (int)$_POST['anio'] : date('Y');

// Validar que exista asistencia enviada
if (!isset($_POST['asistencia']) || empty($_POST['asistencia'])) {
    die("Error: No se ha enviado ningún dato de asistencia.");
}

// Obtener los códigos de enseñanza y grado de los alumnos en un solo paso para evitar múltiples consultas
$runs = array_keys($_POST['asistencia']);
$runs_placeholder = implode(',', array_fill(0, count($runs), '?'));
$sql_alumnos = "SELECT run, codtipoense, codgrado FROM alum WHERE run IN ($runs_placeholder)";
$stmt_alumnos = $conn->prepare($sql_alumnos);
if (!$stmt_alumnos) {
    die("Error en la preparación de la consulta de alumnos: " . $conn->error);
}

// Vincular los valores de los RUNs al prepared statement
$stmt_alumnos->bind_param(str_repeat('s', count($runs)), ...$runs);
$stmt_alumnos->execute();
$result_alumnos = $stmt_alumnos->get_result();

// Almacenar los datos de los alumnos en un array para acceso rápido
$alumnos_data = [];
while ($alumno = $result_alumnos->fetch_assoc()) {
    $alumnos_data[$alumno['run']] = $alumno;
}
$stmt_alumnos->close();

// Preparar la consulta para verificar si ya existe asistencia para ese día
$sql_check = "SELECT idasistencia FROM alum_asiste WHERE run_alum_asiste = ? AND fecha = ?";
$stmt_check = $conn->prepare($sql_check);

// Verificar si la preparación de la consulta fue exitosa
if (!$stmt_check) {
    die("Error en la preparación de la consulta de verificación: " . $conn->error);
}

// Preparar la consulta para actualizar la asistencia
$sql_update = "UPDATE alum_asiste SET estado = ?, codtipoense = ?, codgrado = ?, dia = ? WHERE run_alum_asiste = ? AND fecha = ?";
$stmt_update = $conn->prepare($sql_update);
if (!$stmt_update) {
    die("Error en la preparación de la consulta de actualización: " . $conn->error);
}

// Preparar la consulta para insertar nueva asistencia
$sql_insert = "INSERT INTO alum_asiste (run_alum_asiste, estado, fecha, codtipoense, codgrado, dia) VALUES (?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
if (!$stmt_insert) {
    die("Error en la preparación de la consulta de inserción: " . $conn->error);
}

// Iterar sobre los datos enviados
foreach ($_POST['asistencia'] as $run_alum => $dias) {
    if (!isset($alumnos_data[$run_alum])) {
        continue; // Si no se encuentra el alumno, saltar
    }

    foreach ($dias as $dia => $estado) {
        // Construir la fecha en formato YYYY-MM-DD
        $fecha = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-' . str_pad($dia, 2, '0', STR_PAD_LEFT);
        
        // Verificar si el día es sábado (6) o domingo (7), para evitar guardarlos
        $dia_semana = date('N', strtotime($fecha)); // 1 = Lunes, 7 = Domingo
        if ($dia_semana >= 6) {
            continue; // Saltar el sábado o domingo
        }

        // Obtener codtipoense y codgrado para el alumno
        $codtipoense = $alumnos_data[$run_alum]['codtipoense'];
        $codgrado = $alumnos_data[$run_alum]['codgrado'];

        // Determinar el código del día de la semana en formato 'lu', 'ma', etc.
        $dias_semana = ['Mon' => 'lu', 'Tue' => 'ma', 'Wed' => 'mi', 'Thu' => 'ju', 'Fri' => 'vi'];
        $dia_codigo = isset($dias_semana[date('D', strtotime($fecha))]) ? $dias_semana[date('D', strtotime($fecha))] : '';

        // Verificar si ya existe un registro de asistencia para el alumno y la fecha
        $stmt_check->bind_param('ss', $run_alum, $fecha);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            // Si ya existe, actualizar el registro
            $stmt_update->bind_param('ssssss', $estado, $codtipoense, $codgrado, $dia_codigo, $run_alum, $fecha);
            $stmt_update->execute();
        } else {
            // Si no existe, insertar un nuevo registro
            $stmt_insert->bind_param('ssssss', $run_alum, $estado, $fecha, $codtipoense, $codgrado, $dia_codigo);
            $stmt_insert->execute();
        }
    }
}

$stmt_check->close();
$stmt_update->close();
$stmt_insert->close();
$conn->close();

// Mostrar mensaje de confirmación de éxito
echo '<div class="alert alert-success">Asistencia guardada/actualizada exitosamente.</div>';

// Redirigir después de 3 segundos
header("refresh:3;url=in.asistencia.php");
exit();
?>
