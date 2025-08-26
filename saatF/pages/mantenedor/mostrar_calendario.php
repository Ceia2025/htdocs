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
include '../../includes/navbar.php';
include_once '../../includes/config.php';
?>
<?php
// Inicializar variables
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
$meses_dias = array_fill(3, 10, '');

// Obtener datos de la base de datos
$result = $conn->query("SELECT * FROM m_escolar WHERE anio='$anio'");
while ($row = $result->fetch_assoc()) {
    if ($row['mes'] >= 3 && $row['mes'] <= 12) {
        $meses_dias[$row['mes']] = $row['dias'];
    }
}

// Función para generar el calendario de un mes con opciones para seleccionar el tipo de día
function generar_calendario($mes, $anio, $dias_trabajados) {
    $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
    $primer_dia = date('N', strtotime("$anio-$mes-01"));
    $dias_semana = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'];

    $calendario = '<table class="table table-bordered">';
    $calendario .= '<thead><tr>';
    foreach ($dias_semana as $dia) {
        $calendario .= "<th>$dia</th>";
    }
    $calendario .= '</tr></thead>';
    $calendario .= '<tbody><tr>';

    // Espacios en blanco antes del primer día del mes
    for ($i = 1; $i < $primer_dia; $i++) {
        $calendario .= '<td></td>';
    }

    // Días del mes
    for ($dia = 1; $dia <= $dias_mes; $dia++) {
        $calendario .= '<td>';
        $calendario .= "$dia<br>";
        $calendario .= '<select class="form-select" name="tipo_dia[' . $mes . '][' . $dia . ']">';
        $calendario .= '<option value="normal">Normal</option>';
        $calendario .= '<option value="clase">Clase</option>';
        $calendario .= '<option value="feriado">Feriado</option>';
        $calendario .= '</select>';
        $calendario .= '</td>';
        if (($dia + $primer_dia - 1) % 7 == 0) {
            $calendario .= '</tr><tr>';
        }
    }

    // Espacios en blanco después del último día del mes
    while (($dia + $primer_dia - 1) % 7 != 0) {
        $calendario .= '<td></td>';
        $dia++;
    }

    $calendario .= '</tr></tbody></table>';
    $calendario .= '<div class="alert alert-warning" role="alert">Días Trabajados: ' . $dias_trabajados . '</div>';
    return $calendario;
}
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Calendario Escolar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Modificar Calendario Escolar - <?php echo $anio; ?></h2>
        <form action="guardar_calendario.php" method="POST">
            <input type="hidden" name="anio" value="<?php echo $anio; ?>">
            <div class="row">
                <?php
                    $months = [
                        3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio",
                        7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre",
                        11 => "Noviembre", 12 => "Diciembre"
                    ];
                    foreach ($months as $index => $month) {
                        echo '<div class="col-md-4">';
                        echo '<h5>' . $month . '</h5>';
                        echo generar_calendario($index, $anio, $meses_dias[$index]);
                        echo '</div>';
                    }
                ?>
            </div>
            <button type="submit" class="btn btn-success mt-4">Guardar Cambios</button>
        </form>
        <a href="m.calendario_escolar.php" class="btn btn-primary mt-4">Volver</a>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>

<?php $con->close(); ?>
