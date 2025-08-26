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
        <h2 class="text-center">Agregar Horario</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form action="./agregar_horario.php" method="post" class="mt-4">
                    <div class="mb-3">
                        <label for="dia" class="form-label">Día</label>
                        <select class="form-select" id="dia" name="dia" required>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jornada" class="form-label">Jornada</label>
                        <select class="form-select" id="jornada" name="jornada" required>
                            <option value="Mañana">Mañana</option>
                            <option value="Tarde">Tarde</option>
                            <option value="Vespertina">Vespertina</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="inicio" class="form-label">Hora de inicio</label>
                        <input type="time" class="form-control" id="inicio" name="inicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="fin" class="form-label">Hora de fin</label>
                        <input type="time" class="form-control" id="fin" name="fin" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Agregar Horario</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
