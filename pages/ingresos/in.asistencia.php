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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Estilos personalizados */
        .input-group {
            margin-bottom: 20px;
        }

        .table th,
        .table td {
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
            background-color: #f5c6cb;
            /* Rojo oscuro */
        }

        .cell-default {
            background-color: #ffffff;
            /* Blanco */
        }

        .cell-weekend {
            cursor: not-allowed;
        }

        .cell-holiday {
            color: red;
        }

        .disabled-cell {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2 id="titulo-asistencia" <i class="bi bi-clipboard2-check-fill"></i> </i> INGRESO DE ASISTENCIA MENSUAL</h2>
        <P></P>
        <form method="POST" action="">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="curso" class="form-label">Curso:</label>

                    <select class="form-select" id="curso" name="curso" required>
                        <option value="">Seleccione un curso</option>
                        <?php
                        try {
                            $sql_cursos = "SELECT id, nombre, jornada, descgrado, letra FROM cursos";
                            $stmt = $conn->prepare($sql_cursos);
                            $stmt->execute();
                            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if ($cursos) {
                                foreach ($cursos as $row_curso) {
                                    echo "<option value='" . $row_curso['id'] . "'>"
                                        . $row_curso['nombre'] . " - "
                                        . $row_curso['jornada'] . " - "
                                        . $row_curso['descgrado'] . " - "
                                        . $row_curso['letra'] .
                                        "</option>";
                                }
                            }
                        } catch (PDOException $e) {
                            echo "<option value=''>Error: " . $e->getMessage() . "</option>";
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
            <button type="submit" class="btn btn-success btn-lg">Cargar</button>

            <button type="button" class="btn btn-warning" onclick="marcarTodoAusente()">Todos Ausentes</button>
    </div>

    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['curso']) && !empty($_POST['mes'])) {
        $curso = $_POST['curso'];
        $mes = $_POST['mes'];

        // Obtener nombre del curso
        //$sql_curso_nombre = "SELECT nombre FROM cursos WHERE id = '$curso'";
        //$result_curso_nombre = $conn->query($sql_curso_nombre);
        //$nombre_curso = $result_curso_nombre->fetch_assoc()['nombre'];
        $sql_curso_nombre = "SELECT nombre FROM cursos WHERE id = :curso";
        $stmt_curso = $conn->prepare($sql_curso_nombre);
        $stmt_curso->execute(['curso' => $curso]);
        $nombre_curso = $stmt_curso->fetch(PDO::FETCH_ASSOC)['nombre'];

        // Consultar estudiantes
        /**$sql_estudiantes = "
                SELECT alum.run, alum.nombres, alum.apaterno, alum.amaterno, alum.codtipoense, alum.codgrado 
                FROM alum 
                JOIN cursos ON alum.descgrado = cursos.descgrado 
                AND alum.codtipoense = cursos.codtipoense 
                AND alum.codgrado = cursos.codgrado 
                WHERE cursos.id = '$curso' ORDER BY alum.apaterno ASC";

        $result_estudiantes = $conn->query($sql_estudiantes);
        */
        $sql_estudiantes = "
            SELECT alum.run, alum.nombres, alum.apaterno, alum.amaterno, alum.codtipoense, alum.codgrado 
            FROM alum 
            JOIN cursos ON alum.descgrado = cursos.descgrado 
            AND alum.codtipoense = cursos.codtipoense 
            AND alum.codgrado = cursos.codgrado 
            WHERE cursos.id = :curso ORDER BY alum.apaterno ASC";
        $stmt_estudiantes = $conn->prepare($sql_estudiantes);
        $stmt_estudiantes->execute(['curso' => $curso]);
        $estudiantes = $stmt_estudiantes->fetchAll(PDO::FETCH_ASSOC);


        // Verificar si ya existen registros de asistencia para el curso y mes seleccionado
        /**$anio_actual = date('Y');
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
        */
        $anio_actual = date('Y');
        $sql_verificar_asistencia = "
            SELECT run_alum_asiste, estado, fecha, dia
            FROM alum_asiste
            WHERE codtipoense IN (
                SELECT codtipoense FROM cursos WHERE id = :curso
            ) AND codgrado IN (
                SELECT codgrado FROM cursos WHERE id = :curso
            ) AND MONTH(fecha) = :mes AND YEAR(fecha) = :anio";
        $stmt_asistencia = $conn->prepare($sql_verificar_asistencia);
        $stmt_asistencia->execute([
            'curso' => $curso,
            'mes' => $mes,
            'anio' => $anio_actual
        ]);
        $asistencias = $stmt_asistencia->fetchAll(PDO::FETCH_ASSOC);


        // Si ya existen registros de asistencia, cargar datos
        /**$asistencia_existente = [];
        if ($result_asistencia->num_rows > 0) {
            while ($row_asistencia = $result_asistencia->fetch_assoc()) {
                $asistencia_existente[$row_asistencia['run_alum_asiste']][$row_asistencia['dia']] = $row_asistencia['estado'];
            }
        }*/

        $asistencia_existente = [];
        foreach ($asistencias as $row) {
            $asistencia_existente[$row['run_alum_asiste']][$row['dia']] = $row['estado'];
        }


        // Arreglo para convertir los números de mes a nombres en español
        $meses_espanol = [
            1 => "Enero",
            2 => "Febrero",
            3 => "Marzo",
            4 => "Abril",
            5 => "Mayo",
            6 => "Junio",
            7 => "Julio",
            8 => "Agosto",
            9 => "Septiembre",
            10 => "Octubre",
            11 => "Noviembre",
            12 => "Diciembre"
        ];

        // Asignar el nombre del mes en español usando el arreglo
        $nombre_mes_espanol = $meses_espanol[$mes];

        // Obtener días del mes
        $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio_actual);
        $dias_semana = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
        $dias_codigos = ['do', 'lu', 'ma', 'mi', 'ju', 'vi', 'sa'];

        // Obtener feriados
        $feriados = [];
        $sql_feriados = "SELECT dia FROM m_dias WHERE mes = '$mes' AND tipo = 'F'";
        $result_feriados = $conn->query($sql_feriados);
        /**while ($row_feriado = $result_feriados->fetch_assoc()) {
            $feriados[] = (int) $row_feriado['dia'];
        }*/

        foreach ($estudiantes as $row_estudiante) {
            $feriados[] = (int) $row_feriado['dia'];
        }

        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');
        echo "<h3>Curso: $nombre_curso | Mes: $nombre_mes_espanol</h3>";
        // echo "<h3>Curso: $nombre_curso | Mes: ".date('F', mktime(0, 0, 0, $mes, 10))."</h3>";
    

        
        if ($result_estudiantes->num_rows > 0) {
            echo '<form method="POST" action="guardar_asiste.php">';
            echo '<input type="hidden" name="curso" value="' . $curso . '">';
            echo '<input type="hidden" name="mes" value="' . $mes . '">';
            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr><th>Alumnos</th>';


            // Encabezado con los días y nombres de la semana
            for ($i = 1; $i <= $dias_mes; $i++) {
                $dia_semana = $dias_semana[date('w', strtotime("2024-$mes-$i"))];
                $class = ($dia_semana == 'S' || $dia_semana == 'D') ? 'cell-weekend' : 'cell-default';


                // Marcar en rojo si es feriado, pero sin crear nuevas columnas
                if (in_array($i, $feriados)) {
                    $class = 'cell-holiday'; // Color rojo para feriados
                }
                // Mostrar el día y deshabilitar la interacción en sábados, domingos y días futuros
                $fecha_dia = date('Y-m-d', strtotime("2024-$mes-$i"));
                if ($fecha_dia > $fecha_actual) {
                    $class = 'disabled-cell';
                }

                echo "<th class='$class'>$dia_semana<br>$i</th>";
            }


            // Columna de total de presentes
            echo '<th>Total Presentes</th>';
            echo '</tr>';
            echo '</thead>';

            echo '<tbody>';

            // Rellenar las filas con los estudiantes y los días
            while ($row_estudiante = $result_estudiantes->fetch_assoc()) {
                $nombre_completo = $row_estudiante['apaterno'] . ' ' . $row_estudiante['amaterno'] . ' ' . $row_estudiante['nombres'];
                echo '<tr>';
                // Cambiar el td de alumnos para que esté alineado a la derecha
                echo "<td class='text-start'>$nombre_completo</td>";

                echo "<input type='hidden' name='alumno[]' value='" . $row_estudiante['run'] . "'>";
                echo "<input type='hidden' name='codtipoense[]' value='" . $row_estudiante['codtipoense'] . "'>";
                echo "<input type='hidden' name='codgrado[]' value='" . $row_estudiante['codgrado'] . "'>";

                $total_presentes = 0;

                for ($i = 1; $i <= $dias_mes; $i++) {
                    $estado = isset($asistencia_existente[$row_estudiante['run']][$i]) ? $asistencia_existente[$row_estudiante['run']][$i] : null;
                    $dia_semana = $dias_semana[date('w', strtotime("2024-$mes-$i"))];
                    $fecha_dia = date('Y-m-d', strtotime("2024-$mes-$i"));

                    // Verificar si el día es feriado
                    if (in_array($i, $feriados)) {
                        // Marcar como feriado
                        echo "<td class='cell-holiday' style='color:red;'>
                    <i class='bi bi-calendar-x'></i>
                  </td>"; // Feriado
                    } elseif ($dia_semana == 'S' || $dia_semana == 'D') {
                        // Mostrar fin de semana
                        echo "<td class='cell-weekend'>
                    <i class='bi bi-x-octagon icon-fixed'></i>
                  </td>";

                    } elseif ($fecha_dia > $fecha_actual) {
                        // Celda deshabilitada para días futuros
                        echo "<td class='disabled-cell'>
                                    <i class='bi bi-lock-fill'></i>
                                  </td>";





                    } else {
                        // Cargar datos de asistencia ya guardados
                        $fecha = "2024-$mes-$i";
                        $sql_asistencia = "SELECT estado FROM alum_asiste WHERE run_alum_asiste = '" . $row_estudiante['run'] . "' AND fecha = '$fecha'";
                        $result_asistencia = $conn->query($sql_asistencia);

                        if ($result_asistencia && $result_asistencia->num_rows > 0) {
                            $row_asistencia = $result_asistencia->fetch_assoc();
                            $estado = $row_asistencia['estado'];
                        } else {
                            $estado = '';
                        }

                        // Determinar el contenido basado en el estado
                        switch ($estado) {


                            case 'A':
                                $content = "<i class='bi bi-x-circle-fill'></i>";
                                $class = 'cell-absent';
                                break;
                            case 'P':
                                $content = "<i class='bi bi-check-circle-fill'></i>";
                                $class = 'cell-present';
                                $total_presentes++;
                                break;

                            case 'R':
                                $content = "<i class='bi bi-r-square'></i>";
                                $class = 'cell-retired';
                                break;

                            default:
                                $content = "<i class='bi bi-dash-circle-fill'></i>";
                                $class = 'cell-default';
                        }

                        echo "<td class='$class' onclick='toggleAttendance(this)'>
                    $content
                    <input type='hidden' name='asistencia[" . $row_estudiante['run'] . "][$i]' class='attendance-status' value='$estado'>
                  </td>";
                    }
                }

                // Columna de total de días presentes
                echo "<td class='total-present'>$total_presentes</td>";

                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '<button type="submit" class="btn btn-success">Guardar</button>';
            echo '</form>';
        } else {
            echo "<div class='alert alert-warning'>No hay estudiantes para este curso.</div>";
        }
    }
    ?>



    <!-- Modal -->
    <div class="modal fade" id="guardarModal" tabindex="-1" aria-labelledby="guardarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guardarModalLabel">Guardar Asistencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Asistencia guardada exitosamente.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>

        </div>


        <!-- Bootstrap core JavaScript -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>


        <!-- Script para mostrar modal antes de enviar el formulario -->
        <script>

            function mostrarModalGuardar() {
                var myModal = new bootstrap.Modal(document.getElementById('guardarModal'));
                myModal.show();
            }

            document.querySelector('.btn-primary').addEventListener('click', function (event) {
                event.preventDefault(); // Prevenir el envío automático del formulario para fines de prueba
                mostrarModalGuardar();
            });

            document.getElementById('formularioAsistencia').addEventListener('submit', function (event) {
                event.preventDefault(); // Prevenir el envío automático
                $('#confirmarModal').modal('show'); // Mostrar el modal de confirmación
            });

            document.getElementById('confirmarGuardar').addEventListener('click', function () {
                document.getElementById('formularioAsistencia').submit(); // Enviar el formulario si se confirma
            });


            function toggleAttendance(cell) {
                if (cell.classList.contains('cell-weekend')) return; // Evitar cambios en fines de semana

                let icon = cell.querySelector('i');
                let input = cell.querySelector('.attendance-status');

                if (cell.classList.contains('cell-absent')) {
                    // De 'A' (Ausente) a 'P' (Presente)
                    cell.classList.remove('cell-absent');
                    cell.classList.add('cell-present');
                    icon.classList.replace('bi-x-circle-fill', 'bi-check-circle-fill'); // Icono verde con ticket
                    input.value = 'P';

                } else if (cell.classList.contains('cell-present')) {
                    // De 'P' (Presente) a 'R' (Retirado)
                    cell.classList.remove('cell-present');
                    cell.classList.add('cell-retired');
                    icon.classList.replace('bi-check-circle-fill', 'bi-info-circle-fill'); // Icono informativo
                    input.value = 'R';

                } else if (cell.classList.contains('cell-retired')) {
                    // De 'R' (Retirado) a '' (Vacío)
                    cell.classList.remove('cell-retired');
                    cell.classList.add('cell-default');
                    icon.classList.replace('bi-info-circle-fill', 'bi-dash-circle-fill'); // Icono por defecto
                    input.value = '';

                } else {
                    // De '' (Vacío) a 'A' (Ausente)
                    cell.classList.remove('cell-default');
                    cell.classList.add('cell-absent');
                    icon.classList.replace('bi-dash-circle-fill', 'bi-x-circle-fill'); // Icono de ausente
                    input.value = 'A';
                }

                updateTotalPresent(cell.closest('tr')); // Actualizar el total de presentes
            }


            function updateTotalPresent(row) {
                let total = 0;

                // Contar celdas marcadas como 'P' (Presente)
                row.querySelectorAll('.attendance-status').forEach(function (input) {
                    if (input.value === 'P') {
                        total++;
                    }
                });

                // Actualizar el total de presentes en la fila
                row.querySelector('.total-present').innerText = total;
            }


            function marcarTodoAusente() {
                const celdas = document.querySelectorAll('td.cell-default, td.cell-present');
                celdas.forEach(function (cell) {
                    let icon = cell.querySelector('i');
                    let input = cell.querySelector('.attendance-status');

                    // Cambiar estado a "Ausente (A)"
                    cell.classList.remove('cell-present', 'cell-default');
                    cell.classList.add('cell-absent');
                    icon.classList.replace('bi-check-circle-fill', 'bi-x-circle-fill'); // Ícono de ausente (A)
                    input.value = 'A';
                });
            }

        </script>
</body>

</html>