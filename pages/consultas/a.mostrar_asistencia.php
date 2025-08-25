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

session_start();
include '../../includes/navbar.php';
//include_once '../../includes/conexion.php';

include '../../includes/config.php';
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.E.I.A -> S.A.A.T.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
   
        /* Estilos personalizados */
        .input-group {
            margin-bottom: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            cursor: pointer;
            padding: 10px;
        }
        .icon-fixed {
            font-size: 1.5em;
            color: red;
        }
        .cell-absent i {
            color: red;
        }
        .cell-present i {
            color: green;
        }
        .cell-retired i {
            color: blue;
        }
        .cell-weekend {
            background-color: #f5c6cb; /* Rojo oscuro */
        }
        .cell-default {
            background-color: #ffffff; /* Blanco */
        }
        .cell-weekend {
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 id="titulo-asistencia">INFORME DE ASISTENCIA MENSUAL</h2>
        <form method="POST" action="">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="curso" class="form-label">Curso:</label>
                    <select class="form-select" id="curso" name="curso" required>
                        <option value="">Seleccione un curso</option>
                        <?php
                        // Conexión a la base de datos y consulta para cargar cursos
                        include 'conexion2.php';
                        $sql_cursos = "SELECT id, nombre, jornada, descgrado, letra FROM cursos";
                        $result_cursos = $conn->query($sql_cursos);
                        if ($result_cursos->num_rows > 0) {
                            while($row_curso = $result_cursos->fetch_assoc()) {
                                echo "<option value='".$row_curso['id']."'>".$row_curso['nombre']." - ".$row_curso['jornada']." - ".$row_curso['descgrado']." - ".$row_curso['letra']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="mes" class="form-label">Mes:</label>
                    <select class="form-select" id="mes" name="mes" required>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end"></div>
            <button type="submit" class="btn btn-primary">Cargar</button>
            <button type="button" class="btn btn-secondary no-print align-items-center" onclick="window.print();">Imprimir</button>
            </div>    
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['curso']) && !empty($_POST['mes'])) {
            $curso = $_POST['curso'];
            $mes = $_POST['mes'];

            // Obtener nombre del curso
            $sql_curso_nombre = "SELECT nombre FROM cursos WHERE id = '$curso'";
            $result_curso_nombre = $conn->query($sql_curso_nombre);
            $nombre_curso = $result_curso_nombre->fetch_assoc()['nombre'];

            // Consultar estudiantes
            $sql_estudiantes = "
                SELECT alum.run, alum.nombres, alum.apaterno, alum.amaterno, alum.codtipoense, alum.codgrado 
                FROM alum 
                JOIN cursos ON alum.descgrado = cursos.descgrado 
                AND alum.codtipoense = cursos.codtipoense 
                AND alum.codgrado = cursos.codgrado 
                WHERE cursos.id = '$curso'";
            $result_estudiantes = $conn->query($sql_estudiantes);

            // Verificar si ya existen registros de asistencia para el curso y mes seleccionado
            $anio_actual = date('Y');
            $sql_verificar_asistencia = "
                SELECT run_alum_asiste, estado, fecha, dia
                FROM alum_asiste
                WHERE codtipoense IN (
                    SELECT codtipoense FROM cursos WHERE id = '$curso'
                ) AND codgrado IN (
                    SELECT codgrado FROM cursos WHERE id = '$curso'
                ) AND MONTH(fecha) = '$mes' AND YEAR(fecha) = '$anio_actual'
            ";
            $result_asistencia = $conn->query($sql_verificar_asistencia);

            // Si ya existen registros de asistencia, cargar datos
            $asistencia_existente = [];
            if ($result_asistencia->num_rows > 0) {
                while ($row_asistencia = $result_asistencia->fetch_assoc()) {
                    $asistencia_existente[$row_asistencia['run_alum_asiste']][$row_asistencia['dia']] = $row_asistencia['estado'];
                }
            }

            // Obtener días del mes
            $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio_actual); 
            $dias_semana = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
            $dias_codigos = ['do', 'lu', 'ma', 'mi', 'ju', 'vi', 'sa'];

            echo "<h3>Curso: $nombre_curso | Mes: ".date('F', mktime(0, 0, 0, $mes, 10))."</h3>";

            if ($result_estudiantes->num_rows > 0) {
                echo '<form method="POST" action="guardar_asiste.php">';
                echo '<input type="hidden" name="curso" value="'.$curso.'">';
                echo '<input type="hidden" name="mes" value="'.$mes.'">';
                echo '<table class="table table-bordered">';
                echo '<thead>';
                echo '<tr><th>Alumnos</th>';


                    // Encabezado con los días y nombres de la semana
for ($i = 1; $i <= $dias_mes; $i++) {
    $dia_semana = $dias_semana[date('w', strtotime("2024-$mes-$i"))];
    $class = ($dia_semana == 'S' || $dia_semana == 'D') ? 'cell-weekend' : 'cell-default';

    // Mostrar el día y deshabilitar la interacción en sábados y domingos
    if ($dia_semana == 'S' || $dia_semana == 'D') {
        $content = $dia_semana;
        echo "<th class='$class' data-day='$i' data-type='default'>
                $content<br>$i
              </th>";
    } else {
        echo "<th class='$class' data-day='$i' data-type='default'>
                $dia_semana<br>$i
              </th>";
    }
}


                    // Columna de total de presentes
                    echo '<th>Total Presentes</th>';
                    echo '</tr>';
                    echo '</thead>';

                echo '<tbody>';

            // Rellenar las filas con los estudiantes y los días
            while($row_estudiante = $result_estudiantes->fetch_assoc()) {
                $nombre_completo = $row_estudiante['nombres'] . ' ' . $row_estudiante['apaterno'] . ' ' . $row_estudiante['amaterno'];
                echo '<tr>';
                echo "<td>$nombre_completo</td>";

                    echo "<input type='hidden' name='alumno[]' value='".$row_estudiante['run']."'>";
                    echo "<input type='hidden' name='codtipoense[]' value='".$row_estudiante['codtipoense']."'>";
                    echo "<input type='hidden' name='codgrado[]' value='".$row_estudiante['codgrado']."'>";

                    $total_presentes = 0;

                    for ($i = 1; $i <= $dias_mes; $i++) {
                        $dia_semana = $dias_semana[date('w', strtotime("2024-$mes-$i"))];
                        $dia_codigo = $dias_codigos[date('w', strtotime("2024-$mes-$i"))];
                    
                        // Saltar sábados y domingos
                        if ($dia_semana == 'S' || $dia_semana == 'D') {
                            echo "<td class='cell-weekend' data-day='$i' data-type='$dia_codigo'>
                                    <i class='bi bi-x-octagon icon-fixed'></i>
                                  </td>";
                            continue;
                        }

                        // Cargar datos de asistencia ya guardados
                        $fecha = "2024-$mes-$i";
                        $sql_asistencia = "SELECT estado FROM alum_asiste WHERE run_alum_asiste = '".$row_estudiante['run']."' AND fecha = '$fecha'";
                        $result_asistencia = $conn->query($sql_asistencia);

                        if ($result_asistencia && $result_asistencia->num_rows > 0) {
                            $row_asistencia = $result_asistencia->fetch_assoc();
                            $estado = $row_asistencia['estado'];
                        } else {
                            $estado = '';
                        }

                        switch ($estado) {
                            case 'P':
                                $content = "<i class='bi bi-check-circle-fill'></i>";
                                $class = 'cell-present';
                                $total_presentes++;
                                break;
                            case 'A':
                                $content = "<i class='bi bi-x-circle-fill'></i>";
                                $class = 'cell-absent';
                                break;
                            case 'R':
                                $content = "<i class='bi bi-info-circle-fill'></i>";
                                $class = 'cell-retired';
                                break;
                            default:
                                $content = "<i class='bi bi-dash-circle-fill'></i>";
                                $class = 'cell-default';
                        }

    
                         echo "<td class='$class' data-day='$i' data-type='$dia_codigo'>
                                $content
                                <input type='hidden' name='asistencia[".$row_estudiante['run']."][$i]' class='attendance-status' value='$estado'>
                                <input type='hidden' name='dia[]' value='$dia_codigo'>
                            </td>";
                    }

                        // Columna de total de días presentes
                        echo "<td class='total-present'>$total_presentes</td>";

                        echo '</tr>';
                    }

                echo '</tbody>';
                echo '</table>';
              //  echo '<button type="submit" class="btn btn-success">Guardar</button>';
                echo '</form>';
            } else {
                echo "<div class='alert alert-warning'>No hay estudiantes para este curso.</div>";
            }
        }
        ?>
           
           <p></p>

    </div>
    </div>
    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  
        function toggleAttendance(cell) {
            if (cell.classList.contains('cell-weekend')) return;

            let icon = cell.querySelector('i');
            let input = cell.querySelector('.attendance-status');
            
            if (cell.classList.contains('cell-present')) {
                cell.classList.remove('cell-present');
                cell.classList.add('cell-absent');
                icon.classList.replace('bi-check-circle-fill', 'bi-x-circle-fill');
                input.value = 'ausente';
            } else if (cell.classList.contains('cell-absent')) {
                cell.classList.remove('cell-absent');
                cell.classList.add('cell-retired');
                icon.classList.replace('bi-x-circle-fill', 'bi-info-circle-fill');
                input.value = 'retirado';
            } else if (cell.classList.contains('cell-retired')) {
                cell.classList.remove('cell-retired');
                cell.classList.add('cell-default');
                icon.classList.replace('bi-info-circle-fill', 'bi-dash-circle-fill');
                input.value = '';
            } else {
                cell.classList.remove('cell-default');
                cell.classList.add('cell-present');
                icon.classList.replace('bi-dash-circle-fill', 'bi-check-circle-fill');
                input.value = 'presente';
            }

            updateTotalPresent(cell.closest('tr'));
        }

        function updateTotalPresent(row) {
            let total = 0;
            row.querySelectorAll('.attendance-status').forEach(function(input) {
                if (input.value === 'presente') {
                    total++;
                }
            });
            row.querySelector('.total-present').innerText = total;
        }
    </script>
</body>
</html>
