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
$dias_tipo = array_fill(3, 10, array_fill(1, 31, 'N')); // N = Normal, C = Clases, F = Feriado

// Crear la tabla dias_trabajados si no existe
$conn->query("
    CREATE TABLE IF NOT EXISTS dias_trabajados (
        id INT AUTO_INCREMENT PRIMARY KEY,
        anio YEAR NOT NULL,
        mes INT NOT NULL,
        dias INT NOT NULL,
        UNIQUE KEY (anio, mes)
    )
");

// Obtener datos de la base de datos
$result = $conn->query("SELECT * FROM m_dias WHERE anio='$anio'");
while ($row = $result->fetch_assoc()) {
    if ($row['mes'] >= 3 && $row['mes'] <= 12) {
        $dias_tipo[$row['mes']][$row['dia']] = $row['tipo'];
    }
}

// Guardar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anio = $_POST['anio'];
    $dias_tipo_post = isset($_POST['dias_tipo']) ? $_POST['dias_tipo'] : [];
    $error = false;

    foreach ($dias_tipo_post as $mes => $dias) {
        if (!isset($dias_tipo[$mes])) {
            $dias_tipo[$mes] = array_fill(1, 31, 'N');
        }
        foreach ($dias as $dia => $tipo) {
            $result = $conn->query("SELECT * FROM m_dias WHERE anio='$anio' AND mes='$mes' AND dia='$dia'");
            if ($result->num_rows > 0) {
                if ($tipo !== 'N') {
                    if (!$conn->query("UPDATE m_dias SET tipo='$tipo' WHERE anio='$anio' AND mes='$mes' AND dia='$dia'")) {
                        $error = true;
                        break 2;
                    }
                } else {
                    if (!$conn->query("DELETE FROM m_dias WHERE anio='$anio' AND mes='$mes' AND dia='$dia'")) {
                        $error = true;
                        break 2;
                    }
                }
            } else {
                if ($tipo !== 'N') {
                    if (!$conn->query("INSERT INTO m_dias (anio, mes, dia, tipo) VALUES ('$anio', '$mes', '$dia', '$tipo')")) {
                        $error = true;
                        break 2;
                    }
                }
            }
        }

        // Calcular días trabajados
        $dias_trabajados = calcular_dias_trabajados($mes, $anio, $dias_tipo_post[$mes]);

        // Guardar o actualizar días trabajados
        $result = $conn->query("SELECT * FROM dias_trabajados WHERE anio='$anio' AND mes='$mes'");
        if ($result->num_rows > 0) {
            $conn->query("UPDATE dias_trabajados SET dias='$dias_trabajados' WHERE anio='$anio' AND mes='$mes'");
        } else {
            $conn->query("INSERT INTO dias_trabajados (anio, mes, dias) VALUES ('$anio', '$mes', '$dias_trabajados')");
        }
    }

    if ($error) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var modal = new bootstrap.Modal(document.getElementById("errorModal"));
                document.getElementById("modalMessageError").textContent = "Error al actualizar el calendario.";
                modal.show();
            });
        </script>';
    } else {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var modal = new bootstrap.Modal(document.getElementById("successModal"));
                document.getElementById("modalMessage").textContent = "Calendario Actualizado";
                modal.show();
            });
        </script>';
    }
}

// Función para calcular días trabajados en un mes
function calcular_dias_trabajados($mes, $anio, $dias_tipo) {
    $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
    $dias_trabajados = 0;

    for ($dia = 1; $dia <= $dias_mes; $dia++) {
        $dia_semana = date('N', strtotime("$anio-$mes-$dia"));
        if (isset($dias_tipo[$dia]) && $dias_tipo[$dia] !== 'F' && $dia_semana < 6) {
            $dias_trabajados++;
        }
    }
    return $dias_trabajados;
}

// Función para generar el calendario de un mes
function generar_calendario($mes, $anio, $dias_tipo) {
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
        $tipo = isset($dias_tipo[$dia]) ? $dias_tipo[$dia] : 'N';
        $dia_semana = date('N', strtotime("$anio-$mes-$dia"));
        if ($tipo === 'N') {
            $tipo = ($dia_semana == 6 || $dia_semana == 7) ? 'F' : 'N';
        }
        $bg_color = $tipo === 'C' ? 'bg-success' : ($tipo === 'F' ? 'bg-danger' : 'bg-light');
        $calendario .= "<td class='$bg_color' data-mes='$mes' data-dia='$dia' data-tipo='$tipo' onclick='cambiarTipo(this)'>$dia</td>";
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
    $calendario .= '<div class="alert alert-warning dias-trabajados" role="alert" data-mes="' . $mes . '">Días Trabajados: ' . calcular_dias_trabajados($mes, $anio, $dias_tipo) . '</div>';
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
        <form action="" method="POST" id="calendarioForm">
            <div class="mb-3">
                <label for="anio" class="form-label">Año:</label>
                <input type="number" class="form-control" id="anio" name="anio" value="<?php echo $anio; ?>" readonly>
            </div>
            <div class="row">
                <?php
                for ($mes = 3; $mes <= 12; $mes++) {
                    echo '<div class="col-md-6">';
                    echo '<h5>' . date('F', mktime(0, 0, 0, $mes, 1)) . '</h5>';
                    echo generar_calendario($mes, $anio, $dias_tipo[$mes]);
                    echo '</div>';
                }
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <!-- Modal success -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Éxito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalMessage">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal error -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalMessageError">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script>
        function cambiarTipo(td) {
            const tipoActual = td.classList.contains('bg-success') ? 'C' : (td.classList.contains('bg-danger') ? 'F' : 'N');
            let nuevoTipo;
            if (tipoActual === 'N') {
                nuevoTipo = 'C';
                td.classList.remove('bg-light', 'bg-danger');
                td.classList.add('bg-success');
            } else if (tipoActual === 'C') {
                nuevoTipo = 'F';
                td.classList.remove('bg-success', 'bg-light');
                td.classList.add('bg-danger');
            } else {
                nuevoTipo = 'N';
                td.classList.remove('bg-success', 'bg-danger');
                td.classList.add('bg-light');
            }

            const mes = td.getAttribute('data-mes');
            const dia = td.getAttribute('data-dia');
            td.setAttribute('data-tipo', nuevoTipo);
            document.querySelector(`#calendarioForm`).insertAdjacentHTML('beforeend', `<input type='hidden' name='dias_tipo[${mes}][${dia}]' value='${nuevoTipo}'>`);

            // Actualizar la cantidad de días trabajados
            actualizarDiasTrabajados(mes);
        }

        function actualizarDiasTrabajados(mes) {
            const diasMes = document.querySelectorAll(`td[data-mes='${mes}']`);
            let diasTrabajados = 0;

            diasMes.forEach(td => {
                const tipo = td.getAttribute('data-tipo');
                const dia = td.getAttribute('data-dia');
                const diaSemana = new Date(document.querySelector('input[name="anio"]').value, mes - 1, dia).getDay();

                if (tipo !== 'F' && diaSemana !== 6 && diaSemana !== 0) {
                    diasTrabajados++;
                }
            });

            document.querySelector(`.dias-trabajados[data-mes='${mes}']`).textContent = `Días Trabajados: ${diasTrabajados}`;
        }

        // Calcular días trabajados iniciales
        document.addEventListener("DOMContentLoaded", function() {
            const meses = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            meses.forEach(mes => actualizarDiasTrabajados(mes));
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
