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
include_once '../../includes/conexion.php';
include '../../includes/config.php';
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.E.I.A -> S.A.A.T. </title>
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
        <h2 class="text-center">JORNADA DEL CURSO</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4"></div>


        <form action="listar_curso_horario.php" method="post" class="mt-4">
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
            <button type="submit" class="btn btn-primary">VER JORNADA</button>

        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

            $curso_id = $_POST['curso_id'];

            // Consulta SQL para obtener los horarios del curso seleccionado
            $sql = "SELECT h.dia, h.inicio, h.fin
                    FROM horarios h
                    INNER JOIN curso_horario ch ON h.id = ch.horario_id
                    WHERE ch.curso_id = ?
                    ORDER BY FIELD(h.dia, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'), h.inicio";

            // Preparar la consulta y verificar si fue exitosa
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $curso_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Verificar si se encontró la información de los horarios
                if ($result->num_rows > 0) {
                    echo "<div class='mt-5'>";
                    echo "<h3 class='text-center'>JORNADA DEL CURSO</h3>";
               
                    echo "<button id='printButton' class='btn btn-success' onclick='window.print()'>
                                <i class='bi bi-printer'></i> Imprimir
                            </button>
                            <p></p>";
                    echo "<table class='table table-bordered'>";
                    echo "<thead class='thead-light'><tr><th>DÍA</th><th>HORA INICIO</th><th>HORA FIN</th></tr></thead>";
                    echo "<tbody>";

                    // Mostrar las filas con los horarios
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['dia']}</td>";
                        echo "<td>{$row['inicio']}</td>";
                        echo "<td>{$row['fin']}</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<div class='mt-5 alert alert-warning' role='alert'>No hay horarios asignados para este curso.</div>";
                }

                $stmt->close();
            } else {
                // Mostrar el error de SQL si la preparación falla
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
