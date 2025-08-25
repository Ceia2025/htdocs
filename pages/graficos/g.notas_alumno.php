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

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.E.I.A -> Consulta de Notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .table-container {
            background-color: white !important;
            padding: 15px;
            border-radius: 10px;
        }

        .table {
            background-color: white !important;
            border-collapse: collapse;
            width: 100%;
        }

        .table th, .table td {
            background-color: white !important;
            color: black;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .table th {
            text-align: center;
        }

        .chart-container {
            margin-top: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container-fluid mt-5">
    <h2>CONSULTA DE NOTAS</h2>
    <form method="POST" action="">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="curso" class="form-label">Curso:</label>
                <select class="form-select" id="curso" name="curso" required>
                    <option value="">Seleccione un curso</option>
                    <?php
                    $sql_cursos = "SELECT id, nombre FROM cursos";
                    $result_cursos = $conn->query($sql_cursos);
                    if ($result_cursos->num_rows > 0) {
                        while ($row = $result_cursos->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="asignatura" class="form-label">Asignatura:</label>
                <select class="form-select" id="asignatura" name="asignatura" required>
                    <option value="">Seleccione una asignatura</option>
                    <?php
                    $sql_asignaturas = "SELECT id, nombre FROM asignaturas";
                    $result_asignaturas = $conn->query($sql_asignaturas);
                    if ($result_asignaturas->num_rows > 0) {
                        while ($row = $result_asignaturas->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Cargar</button>
        <button type="button" class="btn btn-secondary no-print" onclick="window.print();">Imprimir</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['curso']) && !empty($_POST['asignatura'])) {
        $curso = $_POST['curso'];
        $asignatura = $_POST['asignatura'];

        $sql_estudiantes = "
            SELECT alum.run, CONCAT(alum.apaterno, ' ', alum.amaterno, ' ', alum.nombres) AS nombre 
            FROM alum 
            WHERE alum.descgrado = (SELECT descgrado FROM cursos WHERE id = '$curso') 
            ORDER BY alum.apaterno ASC";
        $result_estudiantes = $conn->query($sql_estudiantes);

        $sql_curso_info = "SELECT nombre FROM cursos WHERE id = '$curso'";
        $sql_asignatura_info = "SELECT nombre FROM asignaturas WHERE id = '$asignatura'";
        $curso_nombre = $conn->query($sql_curso_info)->fetch_assoc()['nombre'];
        $asignatura_nombre = $conn->query($sql_asignatura_info)->fetch_assoc()['nombre'];

        echo "<h3>Curso: $curso_nombre | Asignatura: $asignatura_nombre</h3>";

        if ($result_estudiantes->num_rows > 0) {
            echo '<div class="table-container">';
            echo '<table class="table table-bordered">';
            echo '<thead><tr>';
            echo '<th>Nombre</th>';
            echo '<th>Nota 1</th>';
            echo '<th>Nota 2</th>';
            echo '<th>Nota 3</th>';
            echo '<th>Promedio</th>';
            echo '</tr></thead><tbody>';

            while ($row = $result_estudiantes->fetch_assoc()) {
                $run = $row['run'];
                $nombre = $row['nombre'];

                $sql_notas = "SELECT * FROM alum_notas WHERE run_alum_nota = '$run' AND asignaturas_id = '$asignatura'";
                $result_notas = $conn->query($sql_notas);

                $notas = [0, 0, 0];
                if ($result_notas->num_rows > 0) {
                    while ($row_nota = $result_notas->fetch_assoc()) {
                        $notas[$row_nota['num_nota'] - 1] = $row_nota['nota'];
                    }
                }
                $promedio = round(array_sum($notas) / count(array_filter($notas)), 1);

                echo "<tr>";
                echo "<td>$nombre</td>";
                foreach ($notas as $nota) {
                    echo "<td>$nota</td>";
                }
                echo "<td>$promedio</td>";
                echo "</tr>";
            }

            echo '</tbody></table></div>';
        } else {
            echo "<p>No se encontraron alumnos en el curso seleccionado.</p>";
        }
    }
    ?>

    <!-- Espacio para gráficos -->
    <div class="chart-container">
        <canvas id="graficoNotas"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Crear datos para el gráfico (deberás pasar los datos dinámicamente desde PHP si es necesario)
        const labels = ['Nota 1', 'Nota 2', 'Nota 3'];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Promedio del curso',
                data: [7, 5, 6], // Ejemplo: reemplazar con datos reales
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const myChart = new Chart(
            document.getElementById('graficoNotas'),
            config
        );
    });
</script>
</body>
</html>
