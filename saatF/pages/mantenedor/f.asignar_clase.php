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
        <h2 class="text-center">Asignar Asignatura a Curso</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form action="procesar_asignacion.php" method="post" class="mt-4">
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
                    <div class="mb-3">
                        <label for="horario_id" class="form-label">Horario</label>
                        <select name="horario_id" id="horario_id" class="form-select" required>
                            <option value=''>Seleccione un curso primero</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="asignatura_id" class="form-label">Asignatura</label>
                        <select name="asignatura_id" id="asignatura_id" class="form-select" required>
                            <?php
                            $sql = "SELECT id, nombre FROM asignaturas";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay asignaturas disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="docente_id" class="form-label">Docente</label>
                        <select name="docente_id" id="docente_id" class="form-select" required>
                            <option value=''>Seleccione una asignatura primero</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Asignar Asignatura a Horario</button>
                </form>


            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script>
        document.getElementById('curso_id').addEventListener('change', function() {
            var cursoId = this.value;
            console.log('Curso seleccionado: ', cursoId); // Depuración
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'cargar_horarios.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status == 200) {
                    console.log('Respuesta del servidor: ', this.responseText); // Depuración
                    document.getElementById('horario_id').innerHTML = this.responseText;
                } else {
                    console.error('Error al cargar horarios');
                }
            };
            xhr.onerror = function() {
                console.error('Error de red al cargar horarios');
            };
            xhr.send('curso_id=' + cursoId);
        });


        document.getElementById('asignatura_id').addEventListener('change', function() {
            var asignaturaId = this.value;
            console.log('Asignatura seleccionada: ', asignaturaId); // Depuración
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'cargar_docentes.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status == 200) {
                    console.log('Respuesta del servidor: ', this.responseText); // Depuración
                    document.getElementById('docente_id').innerHTML = this.responseText;
                } else {
                    console.error('Error al cargar docentes');
                }
            };
            xhr.onerror = function() {
                console.error('Error de red al cargar docentes');
            };
            xhr.send('asignatura_id=' + asignaturaId);
        });
    </script>
</body>
</html>
