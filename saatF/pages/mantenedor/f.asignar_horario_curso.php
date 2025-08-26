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
    <title>C.E.I.A -> S.A.A.T.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Asignar Horario a Curso</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form action="f.asignar_horario_curso.php" method="post" class="mt-4">
                    <div class="mb-3">
                        <label for="curso_id" class="form-label">Selecciona un Curso:</label>
                        <select name="curso_id" id="curso_id" class="form-select" required>
                            <?php
                         
                            // Consulta SQL para obtener los cursos
                            $sql_cursos = "SELECT id, nombre, jornada FROM cursos";
                            $result_cursos = $conn->query($sql_cursos);
                            if ($result_cursos->num_rows > 0) {
                                while($row = $result_cursos->fetch_assoc()) {
                                    echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay cursos disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Seleccionar Curso</button>
                </form>

                <?php
                // Verificar si se ha enviado el formulario y se ha seleccionado un curso
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['curso_id'])) {
                    $curso_id = $_POST['curso_id'];

                    // Consulta SQL para obtener la jornada del curso seleccionado
                    $sql_jornada_curso = "SELECT jornada FROM cursos WHERE id = $curso_id";
                    $result_jornada_curso = $conn->query($sql_jornada_curso);

                    // Verificar si se encontró la jornada del curso
                    if ($result_jornada_curso->num_rows > 0) {
                        $jornada_curso = $result_jornada_curso->fetch_assoc()['jornada'];

                        // Consulta SQL para obtener los horarios según la jornada del curso seleccionado
                        $sql_horarios = "SELECT id, CONCAT(dia, ' - ', jornada, ' (', inicio, ' - ', fin, ')') AS horario_completo 
                                         FROM horarios WHERE jornada = '$jornada_curso'";
                        $result_horarios = $conn->query($sql_horarios);

                        echo "<form action='asignar_horario_curso.php' method='post' class='mt-4'>";
                        echo "<input type='hidden' name='curso_id' value='$curso_id'>";
                        echo "<div class='mb-3'>";
                        echo "<label for='horarios' class='form-label'>Horarios disponibles para la jornada $jornada_curso:</label><br>";
                        if ($result_horarios->num_rows > 0) {
                            while($row = $result_horarios->fetch_assoc()) {
                                echo "<div class='form-check'>";
                                echo "<input class='form-check-input' type='checkbox' name='horario_id[]' value='" . $row["id"] . "'>";
                                echo "<label class='form-check-label'>" . $row["horario_completo"] . "</label>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No hay horarios disponibles para la jornada $jornada_curso</p>";
                        }
                        echo "</div>";
                        echo "<button type='submit' class='btn btn-primary w-100'>Asignar Horario a Curso</button>";
                        echo "</form>";
                    } else {
                        echo "<div class='alert alert-danger mt-4' role='alert'>Error al obtener la jornada del curso</div>";
                    }
                }

                $conn->close();
                ?>

                <?php
                if (isset($_GET['mensaje'])) {
                    if ($_GET['mensaje'] == 'exito') {
                        echo "<div class='alert alert-success mt-4' role='alert'>Horario asignado exitosamente.</div>";
                    } elseif ($_GET['mensaje'] == 'error') {
                        echo "<div class='alert alert-danger mt-4' role='alert'>Hubo un error al asignar el horario.</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
