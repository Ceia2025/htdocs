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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Color de fondo suave */
        }
        .certificado {
            border: 2px solid #007bff; /* Color del borde */
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            background-color: white; /* Fondo blanco para el certificado */
        }
        .logo {
            width: 100px; /* Ajusta el tamaño del logo */
        }
        .titulo {
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .subtitulo {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .informacion {
            margin-bottom: 15px;
        }
        .firmas {
            margin-top: 30px;
            text-align: right; /* Alinear a la derecha */
        }
        .observaciones {
            margin-top: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="certificado">
        <img src="logo_colegio.png" alt="Logo del Colegio" class="logo"> <!-- Cambia por la ruta del logo -->
        <h1 class="titulo">Informe de Notas</h1>
        <h2 class="subtitulo">Primer Semestre Año Escolar 2024</h2>
        <h3 class="subtitulo">CEIA Juanita Zúñiga Fuentes</h3>

        <div class="informacion">
            <p>Decreto Exento de educación que aprueba el Reglamento de Evaluación y Promoción Escolar N° 2169 de 2007</p>
            <p>Decreto Exento o Resolución Exenta de Educación que aprueba Plan y Programas de Estudio N° 584 de 2007</p>
            <p>Decreto Supremo, Resolución Exenta de Educación N° 3290 de 1981</p>
        </div>

        <div class="row">
            <div class="col-sm">
                <strong>Alumno:</strong> <span id="nombre-alumno">[Nombre del Alumno]</span>
            </div>
            <div class="col-sm">
                <strong>Curso:</strong> <span id="curso-alumno">[Curso del Alumno]</span>
            </div>
            <div class="col-sm">
                <strong>Profesora Jefe:</strong> <span id="profesora-jefe">[Nombre de la Profesora Jefe]</span>
            </div>
        </div>

        <hr>

        <table class="table table-bordered">
        <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['curso']) && !empty($_POST['alumno'])) {
    $curso_id = $_POST['curso'];
    $alumno_id = $_POST['alumno'];

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
        echo '<th>Promedio 1S</th>';
        echo '<th colspan="6" class="text-center">Segundo Semestre</th>';
        echo '<th>Promedio 2S</th>';
        echo '<th>Promedio Final</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Inicializar variables para el total de días trabajados y de inasistencia
        $total_dias_trabajados = 0;
        $total_dias_inasistencia = 0;

        // Iterar sobre las asignaturas y obtener las notas del alumno
        while ($row_asignatura = $result_asignaturas->fetch_assoc()) {
            $asignatura_id = $row_asignatura['id'];
            $asignatura_nombre = $row_asignatura['nombre'];

            // Obtener notas del alumno para esta asignatura
            $sql_notas = "SELECT * FROM alum_notas WHERE run_alum_nota = '$alumno_id' AND asignaturas_id = '$asignatura_id'";
            $result_notas = $conn->query($sql_notas);

            // Inicializar arrays para notas del primer y segundo semestre
            $notas_1s = array_fill(0, 6, '-');
            $notas_2s = array_fill(0, 6, '-');

            // Rellenar arrays con notas obtenidas
            if ($result_notas->num_rows > 0) {
                while ($row_nota = $result_notas->fetch_assoc()) {
                    if ($row_nota['semestre'] == 1) {
                        $notas_1s[$row_nota['num_nota'] - 1] = htmlspecialchars($row_nota['nota']);
                    } elseif ($row_nota['semestre'] == 2) {
                        $notas_2s[$row_nota['num_nota'] - 1] = htmlspecialchars($row_nota['nota']);
                    }
                }
            }

            // Mostrar asignatura y notas en la tabla
            echo '<tr>';
            echo "<td>$asignatura_nombre</td>";

            // Notas del primer semestre
            for ($i = 0; $i < 6; $i++) {
                echo "<td><input type='text' class='form-control nota-input' value='" . $notas_1s[$i] . "' readonly></td>";
            }

            // Promedio primer semestre (automático)
            echo "<td><input type='text' class='form-control promedio-1s' readonly></td>";

            // Notas del segundo semestre
            for ($i = 0; $i < 6; $i++) {
                echo "<td><input type='text' class='form-control nota-input' value='" . $notas_2s[$i] . "' readonly></td>";
            }

            // Promedio segundo semestre (automático)
            echo "<td><input type='text' class='form-control promedio-2s' readonly></td>";

            // Promedio final (automático)
            echo "<td><input type='text' class='form-control promedio-final' readonly></td>";
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';

        // Cálculo de asistencia y datos adicionales
        // Supongamos que tienes funciones o consultas que devuelven estos datos
       // $total_dias_trabajados = /* consulta o cálculo para días trabajados */;
       // $total_dias_inasistencia = /* consulta o cálculo para días de inasistencia */;
        //$porcentaje_asistencia = ($total_dias_trabajados / ($total_dias_trabajados + $total_dias_inasistencia)) * 100;

        // Mostrar información adicional
        echo '<div class="row mt-4">';
        echo '<div class="col-sm"><strong>Días trabajados:</strong> ' . $total_dias_trabajados . '</div>';
        echo '<div class="col-sm"><strong>Días Inasistencia:</strong> ' . $total_dias_inasistencia . '</div>';
        echo '<div class="col-sm"><strong>% Asistencia:</strong> ' . round($porcentaje_asistencia, 2) . '%</div>';
        echo '</div>';

        // Observaciones y fechas
        echo '<div class="observaciones mt-3">';
        echo '<strong>Observaciones:</strong> DOCUMENTO CEIA<br>';
        echo '<strong>Pendiente:</strong> <br>';
        echo 'Asistencia registrada hasta el día <span id="fecha-asistencia">' . date('d/m/Y') . '</span><br>';
        echo 'Calificaciones registradas al <span id="fecha-calificaciones">' . date('d/m/Y') . '</span><br>';
        echo '</div>';

        // Pie de firma
        echo '<div class="firmas mt-4">';
        echo '<p>Fecha del Informe: <span id="fecha-informe">' . date('d/m/Y') . '</span></p>';
        echo '<p>__________________________</p>';
        echo '<p>Betsabe Gonzalez Bustos</p>';
        echo '<p>Profesora Jefe</p>';
        echo '<p class="text-right">__________________________</p>';
        echo '<p class="text-right">Firma del Director del Establecimiento</p>';
        echo '</div>';

    } else {
        echo "<p>No se encontraron asignaturas para el curso seleccionado.</p>";
    }
}
?>

            <thead>
                <tr>
                    <th>Asignatura</th>
                    <th>Nota 1° Semestre</th>
                    <th>Nota 2° Semestre</th>
                    <th>Promedio Final</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se generarán las filas con las notas -->
                <tr>
                    <td>Matemáticas</td>
                    <td><input type="text" class="form-control nota-input" placeholder="Nota" /></td>
                    <td><input type="text" class="form-control nota-input" placeholder="Nota" /></td>
                    <td><input type="text" class="form-control promedio-final" readonly /></td>
                </tr>
                <tr>
                    <td>Lenguaje</td>
                    <td><input type="text" class="form-control nota-input" placeholder="Nota" /></td>
                    <td><input type="text" class="form-control nota-input" placeholder="Nota" /></td>
                    <td><input type="text" class="form-control promedio-final" readonly /></td>
                </tr>
                <!-- Agregar más asignaturas según sea necesario -->
            </tbody>
        </table>

        <div class="row">
            <div class="col-sm">
                <strong>Días trabajados:</strong> <span id="dias-trabajados">[Dato]</span>
            </div>
            <div class="col-sm">
                <strong>Días Inasistencia:</strong> <span id="dias-inasistencia">[Dato]</span>
            </div>
            <div class="col-sm">
                <strong>% Asistencia:</strong> <span id="porcentaje-asistencia">[Dato]</span>
            </div>
        </div>

        <div class="observaciones">
            <strong>Observaciones:</strong> DOCUMENTO CEIA<br>
            <strong>Pendiente:</strong> <br>
            asistencia registrada hasta el día <span id="fecha-asistencia">[Fecha]</span><br>
            Calificaciones registradas al <span id="fecha-calificaciones">[Fecha]</span><br>
        </div>

        <div class="firmas">
            <p>Fecha del Informe: <span id="fecha-informe"><?php echo date('d/m/Y'); ?></span></p>
            <p>__________________________</p>
            <p>Betsabe Gonzalez Bustos</p>
            <p>Profesora Jefe</p>
            <p class="text-right">__________________________</p>
            <p class="text-right">Firma del Director del Establecimiento</p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>


</script>
</body>
</html>
