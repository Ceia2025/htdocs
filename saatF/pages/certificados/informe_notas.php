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
// Email: felipe.gutierrez.alfaro@gmail.comS
?>
<?php
session_start();
include '../../includes/navbar.php';
include_once '../../includes/config.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.E.I.A -> Informe de Notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Estilos personalizados -->
    <style>
        /* Ajusta los logos para que estén en los extremos al imprimir */
        .header-logos {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            width: 100%;
        }

        .header-logos img {
            width: 180px;
            /* Puedes ajustar el tamaño si deseas que los logos sean más grandes o pequeños */
            height: auto;
        }

        /* Ajuste de texto y ancho completo */
        .text-container,
        .tabla-notas {
            width: 100%;
            text-align: center;
        }

        /* Ajusta la tabla para que ocupe todo el ancho */
        .tabla-notas table {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla-notas th,
        .tabla-notas td {
            padding: 5px;
            border: 1px solid black;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
            padding: 5px;
        }

        .table td.alumno {
            white-space: nowrap;
            text-align: left;
        }

        .table thead th {
            vertical-align: middle;
        }

        .table td,
        .table th {
            text-align: center;
        }

        .nota-input {
            width: 50px;
            /* Ajusta el tamaño de la celda según lo necesites */
            text-align: center;
            /* Centra el texto dentro de la celda */
            background-color: #f9f9f9;
            /* Color de fondo de las celdas */
            color: #333;
            /* Color del texto */
            border: 1px solid #ccc;
            /* Borde de las celdas */
            font-size: 18px;
            /* Tamaño de la fuente */
        }

        .nota-input.semestre-1 {
            background-color: #e9f7fa;
            /* Color diferente para las notas del primer semestre */
        }

        .nota-input.semestre-2 {
            background-color: #f9f7e9;
            /* Color diferente para las notas del segundo semestre */
        }

        .promedio-1s,
        .promedio-2s,
        .promedio-final {
            background-color: #f0f0f0;
            /* Fondo para los promedios */
            text-align: center;
            font-weight: bold;
            /* Hace que los promedios se vean más destacados */
        }

        /* Estilos de ajuste para impresión */
        @media print {
            body * {
                visibility: hidden;
            }

            #printable,
            #printable * {
                visibility: visible;
            }

            #printable {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            /* Mostrar pie de firma y footer en impresión */
            .signature,
            #footer {
                display: block !important;
            }

            /* Reducción de tamaño de fuente y ajustes para tabla */
            .table {
                font-size: 10px;
                width: 100%;
            }

            .table th,
            .table td {
                padding: 2px;
            }

            .navbar,
            #form-consulta-notas {
                display: none;
            }

            /* Pies de firma y footer para impresión */
            .signature {
                display: flex;
                justify-content: space-between;
                margin-top: 50px;
            }

            .signature div {
                width: 40%;
                text-align: center;
            }

            #footer {
                position: fixed;
                bottom: 10px;
                width: 100%;
                text-align: center;
                font-size: 10px;
            }


            /* Ajustes de ancho para reducir tabla */
            .table-responsive {
                overflow: visible;
            }
        }

        /* Estilo del timbre del director */
        .director-stamp {
            width: 200px;
            margin-bottom: 10px;
        }

        /* Footer con fecha y hora de impresión */
        #footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 10px;
        }

        /* Clase para texto pequeño y líneas ajustadas */
        .small-text {
            font-size: 15px;
            /* Tamaño de la fuente ajustable */
            line-height: 1;
            /* Espacio mínimo entre líneas */
            /*   margin: 1;        Elimina márgenes */
        }

        /* Estilos para la tabla de resumen de asistencia */
        .tabla-asistencia {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .tabla-asistencia th,
        .tabla-asistencia td {
            border: 1px solid black;
            padding: 5px;
            font-size: 12px;
            text-align: center;
        }

        .tabla-asistencia th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        /* Estilos para hacer la tabla más pequeña y ajustada */
        .tabla-asistencia table,
        .table-responsive table {
            width: 100%;
            max-width: 600px;
            /* Ajusta el ancho de la tabla para que sea más estrecha */
            font-size: 18px;
            /* Tamaño de fuente reducido */<
            border-collapse: collapse;
            margin: 0 auto;
        }

        .tabla-asistencia th,
        .table-responsive th,
        .tabla-asistencia td,
        .table-responsive td {
            padding: 4px 8px;
            /* Reducir el espacio interno de las celdas */
            border: 1px solid #ddd;
            text-align: left;
        }

        .tabla-asistencia th {
            font-weight: bold;
            text-align: center;
            background-color: #f4f4f4;
        }

        .table-responsive th {
            text-align: center;
            background-color: #f7f7f7;
        }

        .tabla-asistencia {
            margin-top: 20px;
        }

        .text-center {
            text-align: center;
        }

        .texto-reglamentos {
            text-align: left;
            /* Alinea el texto a la izquierda */
            width: 100%;
            /* Asegura que ocupe todo el ancho disponible */
            margin: 20px 0;
            /* Agrega un espacio vertical entre el texto y otros elementos */
            font-size: 15px;
            /* Ajusta el tamaño del texto */
            line-height: 1;
            /* Espaciado entre líneas */
        }
    </style>

</head>

<body>
    <div id="printable">
        <div class="header-logos">
            <img src="/img/logo.png" alt="CEIA">
            <!--   <img src="/img/logo_saat.png" alt="Logo SAAT"> -->
        </div>

        <div class="text-container">
            <h1>Informe de Notas</h1>
            <p>Año Escolar 2024</p>
            <p>CEIA Juanita Zúñiga Fuentes</p>
        </div>

        <p></p>

        <div class="texto-reglamentos">
            <p>Decreto Exento de educación que aprueba el Reglamento de Evaluación y Promoción Escolar N° 2169 de 2007
            </p>
            <p>Decreto Exento o Resolución Exenta de Educación que aprueba Plan y Programas de Estudio N° 584 de 2007
            </p>
            <p>Decreto Supremo, Resolución Exenta de Educación N° 3290 de 1981</p>
        </div>

        <form id="form-consulta-notas" method="POST" action="">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="curso" class="form-label">Curso:</label>
                    <select class="form-select" id="curso" name="curso" required>
                        <option value="">Seleccione un curso</option>
                        <?php
                        // Cargar cursos desde la base de datos
                        $sql_cursos = "SELECT id, nombre, jornada, descgrado FROM cursos";
                        $result_cursos = $conn->query($sql_cursos);
                        if ($result_cursos->num_rows > 0) {
                            while ($row_curso = $result_cursos->fetch_assoc()) {
                                echo "<option value='" . $row_curso['id'] . "'>" . $row_curso['nombre'] . " - " . $row_curso['jornada'] . " - " . $row_curso['descgrado'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="alumno" class="form-label">Alumno:</label>
                    <select class="form-select" id="alumno" name="alumno" required>
                        <option value="">Seleccione un alumno</option>
                        <!-- Aquí se cargarán los alumnos con AJAX -->
                    </select>
                </div>
            </div>
            <button id="cargar" type="submit" class="btn btn-primary">Cargar</button>
            <button type="button" class="btn btn-secondary" onclick="window.print();">Imprimir</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['curso']) && !empty($_POST['alumno'])) {
            $curso_id = $_POST['curso'];
            $alumno_id = $_POST['alumno'];

            // Obtener el nombre completo del alumno y el curso
            $sql_alumno = "SELECT CONCAT(alum.nombres, ' ', alum.apaterno, ' ', alum.amaterno) AS nombre_completo, cursos.nombre AS curso_nombre
                           FROM alum
                           JOIN cursos ON cursos.id = '$curso_id'
                           WHERE alum.run = '$alumno_id'";
            $result_alumno = $conn->query($sql_alumno);

            if ($result_alumno->num_rows > 0) {
                $row_alumno = $result_alumno->fetch_assoc();
                $nombre_completo = $row_alumno['nombre_completo'];
                $curso_nombre = $row_alumno['curso_nombre'];

                // Mostrar título de la tabla con el nombre y curso del alumno
                echo "<h5 class='text-center'>Alumno: $nombre_completo - Curso: $curso_nombre</h5>";
            }

            // Consultar asignaturas del curso
            $sql_asignaturas = "
                SELECT DISTINCT asignaturas.id, asignaturas.nombre 
                FROM asignaturas 
                JOIN asignaturas_cursos ON asignaturas.id = asignaturas_cursos.asignatura_id 
                WHERE asignaturas_cursos.curso_id = '$curso_id'";
            $result_asignaturas = $conn->query($sql_asignaturas);

            if ($result_asignaturas->num_rows > 0) {
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered table-striped" style="table-layout: auto; width: 100%;">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Asignatura</th>';
                echo '<th colspan="6" class="text-center">Primer Semestre</th>';
                echo '<th>Prom. 1S</th>';
                echo '<th colspan="6" class="text-center">Segundo Semestre</th>';
                echo '<th>Prom. 2S</th>';
                echo '<th>Prom. Final</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                echo '<tbody>';

                // Iterar sobre las asignaturas y obtener las notas del alumno
// Inicializar arrays para almacenar los promedios de cada asignatura por semestre
                $promedios_1s = [];
                $promedios_2s = [];

                // Bucle para mostrar cada asignatura y sus notas
                while ($row_asignatura = $result_asignaturas->fetch_assoc()) {
                    $asignatura_id = $row_asignatura['id'];
                    $asignatura_nombre = $row_asignatura['nombre'];

                    // Variables para almacenar el promedio por semestre de cada asignatura
                    $promedio_asignatura_1s = 0;
                    $promedio_asignatura_2s = 0;
                    $num_notas_1s = 0;
                    $num_notas_2s = 0;

                    // Obtener notas del alumno para esta asignatura
                    $sql_notas = "SELECT * FROM alum_notas WHERE run_alum_nota = '$alumno_id' AND asignaturas_id = '$asignatura_id'";
                    $result_notas = $conn->query($sql_notas);

                    // Inicializar arrays para notas del primer y segundo semestre
                    $notas_1s = array_fill(0, 6, '');
                    $notas_2s = array_fill(0, 6, '');

                    // Rellenar arrays con notas obtenidas
                    if ($result_notas->num_rows > 0) {
                        while ($row_nota = $result_notas->fetch_assoc()) {
                            if ($row_nota['semestre'] == 1) {
                                $notas_1s[$row_nota['num_nota'] - 1] = htmlspecialchars($row_nota['nota']);
                                $promedio_asignatura_1s += $row_nota['nota'];
                                $num_notas_1s++;
                            } elseif ($row_nota['semestre'] == 2) {
                                $notas_2s[$row_nota['num_nota'] - 1] = htmlspecialchars($row_nota['nota']);
                                $promedio_asignatura_2s += $row_nota['nota'];
                                $num_notas_2s++;
                            }
                        }
                    }

                    // Calcular los promedios por semestre de la asignatura si existen notas
                    if ($num_notas_1s > 0)
                        $promedio_asignatura_1s /= $num_notas_1s;
                    if ($num_notas_2s > 0)
                        $promedio_asignatura_2s /= $num_notas_2s;

                    // Guardar los promedios de cada asignatura en el array de promedios
                    if ($num_notas_1s > 0)
                        $promedios_1s[] = $promedio_asignatura_1s;
                    if ($num_notas_2s > 0)
                        $promedios_2s[] = $promedio_asignatura_2s;

                    // Mostrar asignatura y notas en la tabla
                    echo '<tr>';
                    echo "<td>$asignatura_nombre</td>";

                    // Notas del primer semestre
                    for ($i = 0; $i < 6; $i++) {
                        echo "<td><input type='text' class='form-control nota-input semestre-1' value='" . $notas_1s[$i] . "' readonly></td>";
                    }

                    // Promedio primer semestre
                    echo "<td><input type='text' class='form-control promedio-1s' value='" . round($promedio_asignatura_1s, 2) . "' readonly></td>";

                    // Notas del segundo semestre
                    for ($i = 0; $i < 6; $i++) {
                        echo "<td><input type='text' class='form-control nota-input semestre-2' value='" . $notas_2s[$i] . "' readonly></td>";
                    }

                    // Promedio segundo semestre
                    echo "<td><input type='text' class='form-control promedio-2s' value='" . round($promedio_asignatura_2s, 2) . "' readonly></td>";

                    // Promedio final de la asignatura
                    $promedio_final_asignatura = ($promedio_asignatura_1s + $promedio_asignatura_2s) / 2;
                    echo "<td><input type='text' class='form-control promedio-final' value='" . round($promedio_final_asignatura, 2) . "' readonly></td>";
                    echo '</tr>';
                }

                // Calcular el promedio general de cada semestre y el promedio final
                $promedio_total_1s = !empty($promedios_1s) ? array_sum($promedios_1s) / count($promedios_1s) : 0;
                $promedio_total_2s = !empty($promedios_2s) ? array_sum($promedios_2s) / count($promedios_2s) : 0;
                $promedio_final_total = ($promedio_total_1s + $promedio_total_2s) / 2;

                // Mostrar los promedios generales y el promedio final en una nueva fila al final de la tabla
                echo '<tr>';
                echo '<td><strong>Promedio General</strong></td>';

                // Espacios vacíos para las columnas de notas
                for ($i = 0; $i < 6; $i++)
                    echo '<td></td>';

                // Promedio general primer semestre
                $promedio_total_1s = number_format(round($promedio_total_1s, 1), 1, '.', '');
                echo "<td><strong>$promedio_total_1s</strong></td>";

                // Espacios vacíos para las columnas de notas del segundo semestre
                for ($i = 0; $i < 6; $i++)
                    echo '<td></td>';

                // Promedio general segundo semestre
                $promedio_total_2s = number_format(round($promedio_total_2s, 1), 1, '.', '');
                echo "<td><strong>$promedio_total_2s</strong></td>";

                // Promedio final total
                $promedio_final_total = number_format(round($promedio_final_total, 1), 1, '.', '');
                echo "<td><strong>$promedio_final_total</strong></td>";
                echo '</tr>';

                echo '</tbody></table>
</div>';

            } else {
                echo "<p class='text-center'>No se encontraron asignaturas para el curso seleccionado.</p>";
            }
        }
        ?>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center">Resumen de Asistencia, Anotaciones y Atrasos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Días Trabajados</strong></td>
                        <td id="dias-trabajados"></td>
                    </tr>
                    <tr>
                        <td><strong>Días de Inasistencia</strong></td>
                        <td id="dias-inasistencia"></td>
                    </tr>
                    <tr>
                        <td><strong>Porcentaje de Asistencia</strong></td>
                        <td id="porcentaje-asistencia"></td>
                    </tr>
                    <tr>
                        <td><strong>Anotaciones Positivas</strong></td>
                        <td id="anotaciones-positivas"></td>
                    </tr>
                    <tr>
                        <td><strong>Anotaciones Negativas</strong></td>
                        <td id="anotaciones-negativas"></td>
                    </tr>
                    <tr>
                        <td><strong>Otras Anotaciones</strong></td>
                        <td id="otras-anotaciones"></td>
                    </tr>
                    <tr>
                        <td><strong>Atrasos</strong></td>
                        <td id="cantidad-atrasos"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['curso']) && !empty($_POST['alumno'])) {
            $curso_id = $_POST['curso'];
            $alumno_run = $_POST['alumno'];
            $fechaLimite = date("Y-m-d");

            // Obtener el primer día de clase en marzo (tipo 'C')
            $queryPrimerDiaClase = "SELECT CONCAT(anio, '-', LPAD(mes, 2, '0'), '-', LPAD(dia, 2, '0')) AS primer_dia_clase FROM m_dias WHERE tipo = 'C' AND mes = 3 ORDER BY anio, mes, dia LIMIT 1";
            $resultPrimerDiaClase = $conn->query($queryPrimerDiaClase);
            $primerDiaClase = ($resultPrimerDiaClase->num_rows > 0) ? $resultPrimerDiaClase->fetch_assoc()['primer_dia_clase'] : "1970-01-01";

            // Calcular días trabajados
            $queryDiasTrabajados = "SELECT COUNT(*) AS dias_trabajados FROM m_dias WHERE tipo = 'C' AND CONCAT(anio, '-', LPAD(mes, 2, '0'), '-', LPAD(dia, 2, '0')) BETWEEN '$primerDiaClase' AND '$fechaLimite'";
            $diasTrabajados = ($resultDiasTrabajados = $conn->query($queryDiasTrabajados)) && ($resultDiasTrabajados->num_rows > 0) ? (int) $resultDiasTrabajados->fetch_assoc()['dias_trabajados'] : 0;

            // Calcular días de asistencia
            $queryDiasAsistencia = "SELECT COUNT(*) AS dias_asistencia FROM alum_asiste WHERE estado = 'P' AND run_alum_asiste = '$alumno_run' AND fecha BETWEEN '$primerDiaClase' AND '$fechaLimite'";
            $diasAsistencia = ($resultDiasAsistencia = $conn->query($queryDiasAsistencia)) && ($resultDiasAsistencia->num_rows > 0) ? (int) $resultDiasAsistencia->fetch_assoc()['dias_asistencia'] : 0;

            $diasInasistencia = $diasTrabajados - $diasAsistencia;
            $porcentajeAsistencia = ($diasTrabajados > 0) ? ($diasAsistencia / $diasTrabajados) * 100 : 0;

            // Obtener cantidad de anotaciones por tipo
            $sql_anotaciones = "SELECT tipo, COUNT(*) as cantidad FROM anotaciones WHERE alum_run = '$alumno_run' GROUP BY tipo";
            $positivas = $negativas = $otras = 0;
            if ($result_anotaciones = $conn->query($sql_anotaciones)) {
                while ($row_anotacion = $result_anotaciones->fetch_assoc()) {
                    if ($row_anotacion['tipo'] == 'P')
                        $positivas = $row_anotacion['cantidad'];
                    elseif ($row_anotacion['tipo'] == 'N')
                        $negativas = $row_anotacion['cantidad'];
                    else
                        $otras = $row_anotacion['cantidad'];
                }
            }

            // Obtener cantidad de atrasos
            $sql_atrasos = "SELECT COUNT(*) as cantidad_atrasos FROM atrasos WHERE alum_run = '$alumno_run'";
            $cantidad_atrasos = ($result_atrasos = $conn->query($sql_atrasos)) && ($result_atrasos->num_rows > 0) ? $result_atrasos->fetch_assoc()['cantidad_atrasos'] : 0;

            // Pasar los valores al HTML usando JavaScript
            echo "<script>
        document.getElementById('dias-trabajados').innerText = '$diasTrabajados';
        document.getElementById('dias-inasistencia').innerText = '$diasInasistencia';
        document.getElementById('porcentaje-asistencia').innerText = '" . round($porcentajeAsistencia, 2) . "%';
        document.getElementById('anotaciones-positivas').innerText = '$positivas';
        document.getElementById('anotaciones-negativas').innerText = '$negativas';
        document.getElementById('otras-anotaciones').innerText = '$otras';
        document.getElementById('cantidad-atrasos').innerText = '$cantidad_atrasos';
    </script>";
        }
        ?>


        <!-- Pie de firma para impresión -->
        <div class="row mb-3">
        </div>
        <div class="row mb-3">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <div class="signature">
                    <strong>Firma Profesor Jefe</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="signature">
                    <img src="/img/firma_director.png" alt="Timbre Director" class="director-stamp">
                    <strong>Firma Director</strong>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

        <!-- Footer con fecha y hora de impresión -->
        <div id="footer">
            <p>Impreso el: <span id="print-date"></span> a las <span id="print-time"></span></p>
        </div>
    </div>



    <!-- Script para fecha y hora de impresión -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const printDate = new Date();
            document.getElementById('print-date').textContent = printDate.toLocaleDateString();
            document.getElementById('print-time').textContent = printDate.toLocaleTimeString();
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Capturar el evento de cambio del curso
            $('#curso').on('change', function () {
                var cursoId = $(this).val(); // Obtener el ID del curso seleccionado
                if (cursoId) {
                    $.ajax({
                        url: 'cargar_alumnos.php', // Archivo PHP para cargar los alumnos
                        type: 'POST',
                        data: { curso_id: cursoId },
                        success: function (response) {
                            // Poner el HTML de los alumnos en el select
                            $('#alumno').html(response);
                        },
                        error: function () {
                            alert('Error al cargar los alumnos.');
                        }
                    });
                } else {
                    $('#alumno').html('<option value="">Seleccione un alumno</option>');
                }
            });

            // Inicializar promedios al cargar la página
            actualizarPromedios();
        });





        // Evento para restringir la entrada de notas
        document.querySelectorAll('.nota-input').forEach(function (input) {
            input.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9.]/g, ''); // Permitir solo números y puntos

                if ((this.value.match(/\./g) || []).length > 1) {
                    this.value = this.value.substring(0, this.value.length - 1);
                    alert("Solo se permite un punto decimal.");
                }

                // Asegurarse de que el valor esté entre 1.0 y 7.0
                const valor = parseFloat(this.value);
                if (this.value && (valor < 1.0 || valor > 7.0 || this.value.includes('.') && this.value.split('.')[1].length > 1)) {
                    this.value = '';
                    alert("La nota debe estar entre 1.0 y 7.0 y no debe contener letras ni un segundo decimal.");
                }

                actualizarPromedios(); // Actualizar promedios al cambiar el valor
            });


            // Evento para agregar .0 al salir de la caja de texto
            input.addEventListener('blur', function () {
                const valor = this.value;
                if (valor && !valor.includes('.')) {
                    this.value = valor + '.0'; // Completar con un decimal al salir
                }
                actualizarPromedios();
            });

            // Inicializar promedios al cargar la página
            actualizarPromedios();
        });


        // Función para calcular el promedio
        function calcularPromedio(notas) {
            let total = 0;
            let count = 0;

            notas.forEach(function (nota) {
                const valor = parseFloat(nota.value);
                if (!isNaN(valor)) {
                    total += valor;
                    count++;
                }
            });

            if (count > 0) {
                let promedio = total / count;
                // Redondear a un decimal y asegurarse de agregar .0 si es entero
                return promedio % 1 === 0 ? promedio.toFixed(1) : promedio.toFixed(1);
            }
            return '';
        }




        // Función para aplicar estilo a los promedios
        function aplicarEstiloPromedio(promedioElement) {
            promedioElement.classList.remove('promedio-bajo', 'promedio-alto'); // Limpiar clases antes de aplicar
            if (promedioElement.value && parseFloat(promedioElement.value) < 4.0) {
                promedioElement.classList.add('promedio-bajo'); // Aplica rojo si es menor a 4
            } else if (promedioElement.value && parseFloat(promedioElement.value) >= 4.0) {
                promedioElement.classList.add('promedio-alto'); // Aplica azul si es mayor o igual a 4
            }
        }


        // Función para aplicar estilo a las notas
        function aplicarEstiloNotas(notas) {
            notas.forEach(function (nota) {
                const valor = parseFloat(nota.value);
                nota.classList.remove('nota-roja', 'nota-azul'); // Limpiar clases antes de aplicar

                if (!isNaN(valor)) {
                    if (valor < 4.0) {
                        nota.classList.add('nota-roja'); // Aplica rojo si es menor a 4
                    } else if (valor >= 4.0 && valor <= 7.0) {
                        nota.classList.add('nota-azul'); // Aplica azul si está entre 4 y 7
                    }
                }
            });
        }

        // Función para actualizar los promedios
        function actualizarPromedios() {
            let promedioGeneralTotal = 0;
            let asignaturasCount = 0;

            document.querySelectorAll('tr').forEach(function (row) {
                const notas1S = row.querySelectorAll('.semestre-1'); // Notas del primer semestre
                const notas2S = row.querySelectorAll('.semestre-2'); // Notas del segundo semestre
                const promedio1S = row.querySelector('.promedio-1s');
                const promedio2S = row.querySelector('.promedio-2s');
                const promedioFinal = row.querySelector('.promedio-final');

                // Calcular promedios
                if (promedio1S) {
                    promedio1S.value = calcularPromedio(notas1S);
                    aplicarEstiloPromedio(promedio1S);
                }

                if (promedio2S) {
                    promedio2S.value = calcularPromedio(notas2S);
                    aplicarEstiloPromedio(promedio2S);
                }

                // Calcular promedio final si ambos semestres tienen promedios
                if (promedio1S && promedio2S && promedioFinal) {
                    const p1 = parseFloat(promedio1S.value);
                    const p2 = parseFloat(promedio2S.value);
                    if (!isNaN(p1) && !isNaN(p2)) {
                        let promedioFinalValue = (p1 + p2) / 2;
                        promedioFinal.value = promedioFinalValue % 1 === 0 ? promedioFinalValue.toFixed(1) : promedioFinalValue.toFixed(1); // Agregar .0 si es entero
                        aplicarEstiloPromedio(promedioFinal);
                        promedioGeneralTotal += parseFloat(promedioFinal.value);
                        asignaturasCount++;
                    } else {
                        promedioFinal.value = '';
                        promedioFinal.classList.remove('promedio-bajo', 'promedio-alto');
                    }
                }



                // Aplicar colores a las notas
                aplicarEstiloNotas(notas1S);
                aplicarEstiloNotas(notas2S);
            });


            // Calcular y mostrar el promedio general
            const promedioGeneral = document.querySelector('.promedio-general');
            if (promedioGeneral && asignaturasCount > 0) {
                let promedioFinalGeneral = promedioGeneralTotal / asignaturasCount;
                promedioGeneral.value = promedioFinalGeneral % 1 === 0 ? promedioFinalGeneral.toFixed(1) : promedioFinalGeneral.toFixed(1); // Agregar .0 si es entero
                aplicarEstiloPromedio(promedioGeneral);
            }
        }

        // Llamar a la función para actualizar los promedios después de cargar las notas
        document.addEventListener('DOMContentLoaded', function () {
            actualizarPromedios();
        });


        document.addEventListener('DOMContentLoaded', function () {
            // Calcula promedios de las filas y la fila de total de promedios
            function calcularPromedios() {
                let sum1S = 0, sum2S = 0, totalAsignaturas = 0;

                document.querySelectorAll('tbody tr').forEach(row => {
                    const notas1S = [...row.querySelectorAll('.nota-input.semestre-1')].map(input => parseFloat(input.value) || 0);
                    const promedio1S = notas1S.length ? (notas1S.reduce((a, b) => a + b, 0) / notas1S.length).toFixed(2) : 0;
                    row.querySelector('.promedio-1s').value = promedio1S;

                    const notas2S = [...row.querySelectorAll('.nota-input.semestre-2')].map(input => parseFloat(input.value) || 0);
                    const promedio2S = notas2S.length ? (notas2S.reduce((a, b) => a + b, 0) / notas2S.length).toFixed(2) : 0;
                    row.querySelector('.promedio-2s').value = promedio2S;

                    const promedioFinal = ((parseFloat(promedio1S) + parseFloat(promedio2S)) / 2).toFixed(2);
                    row.querySelector('.promedio-final').value = promedioFinal;

                    // Sumar los promedios para calcular el promedio total
                    sum1S += parseFloat(promedio1S);
                    sum2S += parseFloat(promedio2S);
                    totalAsignaturas++;
                });

                // Calcular promedios generales
                document.querySelector('.promedio-total-1s').value = (sum1S / totalAsignaturas).toFixed(2);
                document.querySelector('.promedio-total-2s').value = (sum2S / totalAsignaturas).toFixed(2);
                document.querySelector('.promedio-total-final').value = ((sum1S + sum2S) / (2 * totalAsignaturas)).toFixed(2);
            }

            calcularPromedios();
        });

    </script>
</body>

</html>