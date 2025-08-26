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
        /* Estilos para la impresión */
        @media print {
            #printButton, 
            .navbar {
                display: none;
            }
            .no-print {
                display: none;
            }
            .asignatura {
                color: white; /* Asegúrate de que el texto sea legible al imprimir */
                font-weight: bold;
                text-align: center;
            }
        }

        /* Asignar colores dinámicos a las asignaturas */
        .asignatura {
            padding: 5px;
            color: white; /* Color de texto para la vista normal */
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">HORARIO DEL CURSO</h2>
        <form action="ver_horario_curso.php" method="post" class="mt-4 no-print">
            <div class="mb-3">
                <label for="curso_id" class="form-label">Curso</label>
                <select name="curso_id" id="curso_id" class="form-select" required>
                    <?php
                    $sql = "SELECT id, nombre FROM cursos";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No hay cursos disponibles</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">VER HORARIO</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $curso_id = $_POST['curso_id'];

            // Obtener el nombre del curso
            $sql_curso = "SELECT nombre FROM cursos WHERE id = ?";
            if ($stmt_curso = $conn->prepare($sql_curso)) {
                $stmt_curso->bind_param("i", $curso_id);
                $stmt_curso->execute();
                $result_curso = $stmt_curso->get_result();

                if ($result_curso->num_rows > 0) {
                    $curso_data = $result_curso->fetch_assoc();
                    $curso_nombre = $curso_data['nombre'];
                } else {
                    $curso_nombre = "Curso no encontrado";
                }
                $stmt_curso->close();
            }

            // Obtener el año actual
            $year = date('Y');

            // Mostrar el curso y el año sobre la tabla
            echo "<h3 class='text-center'>Curso: $curso_nombre - Año: $year</h3>";

            // Botón de impresión
            echo "<div class='text-center no-print'>
                    <button id='printButton' class='btn btn-success' onclick='window.print()'>
                        <i class='bi bi-printer'></i> Imprimir Horario
                    </button>
                  </div>";

            // Consulta SQL para obtener los horarios
            $sql = "SELECT h.dia, h.inicio, h.fin, a.nombre AS asignatura, CONCAT(d.nombre, ' ', d.apellido) AS docente
                    FROM horarios h
                    INNER JOIN asignaturas_cursos ac ON h.id = ac.horario_id
                    INNER JOIN asignaturas a ON ac.asignatura_id = a.id
                    LEFT JOIN docentes d ON ac.docentes_id = d.id
                    WHERE ac.curso_id = ?
                    ORDER BY FIELD(h.dia, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'), h.inicio";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $curso_id);
                $stmt->execute();
                $result = $stmt->get_result();

                $horarios = [];
                $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

                foreach ($dias as $dia) {
                    $horarios[$dia] = [];
                }

                // Almacenar colores únicos para cada docente
                $colores_docentes = [];
                $index_color = 0;
                $colores = ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6']; // Colores predeterminados

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $asignatura = $row['asignatura'];
                        $docente = $row['docente'];

                        // Asignar color único al docente si no tiene uno ya
                        if (!isset($colores_docentes[$docente])) {
                            $colores_docentes[$docente] = $colores[$index_color % count($colores)];
                            $index_color++;
                        }

                        $horarios[$row['dia']][] = [
                            'inicio' => $row['inicio'],
                            'fin' => $row['fin'],
                            'asignatura' => $asignatura,
                            'docente' => $docente,
                            'color' => $colores_docentes[$docente]
                        ];
                    }
                }

                // Crear tabla de horarios
                $intervalos = [];
                foreach ($horarios as $dia => $horas) {
                    foreach ($horas as $hora) {
                        if (!in_array($hora['inicio'], $intervalos)) {
                            $intervalos[] = $hora['inicio'];
                        }
                    }
                }
                sort($intervalos);

                echo "<div class='mt-5'>";
                echo "<table class='table table-bordered'>";
                echo "<thead class='thead-light'><tr><th>Hora</th><th>Inicio</th><th>Fin</th>";

                foreach ($dias as $dia) {
                    echo "<th>$dia</th>";
                }
                echo "</tr></thead>";
                echo "<tbody>";

                foreach ($intervalos as $index => $inicio) {
                    echo "<tr>";
                    echo "<td>" . ($index + 1) . "</td>";

                    $fin = '';
                    foreach ($horarios as $dia => $horas) {
                        foreach ($horas as $hora) {
                            if ($hora['inicio'] == $inicio) {
                                $fin = $hora['fin'];
                                break 2;
                            }
                        }
                    }
                    echo "<td>$inicio</td>";
                    echo "<td>$fin</td>";

                    foreach ($dias as $dia) {
                        $asignatura_docente = '';
                        foreach ($horarios[$dia] as $hora) {
                            if ($hora['inicio'] == $inicio) {
                                $asignatura_docente = "<div class='asignatura' style='background-color: " . $hora['color'] . ";'>" . 
                                                      $hora['asignatura'] . "<br><small>" . $hora['docente'] . "</small></div>";
                                break;
                            }
                        }
                        echo "<td>$asignatura_docente</td>";
                    }
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                echo "</div>";

                $stmt->close();
            } else {
                echo "<div class='mt-5 alert alert-danger' role='alert'>Error en la consulta SQL: " . $conn->error . "</div>";
            }

            $conn->close();
        }
        ?>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
