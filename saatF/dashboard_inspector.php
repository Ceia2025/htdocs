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
// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'inspector') {
    header('Location: acceso_denegado.php');
    exit();
}
?>


<?php

include './includes/navbar.php';
include './includes/config.php';
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../img/saat.ico" type="image/x-icon">
</head>
<body>

<?php 
//include './includes/navbar.php'; 
?>

    <div class="container mt-5">
        <h1 class="text-center">Bienvenido al Panel de Inspector General</h1>
        <p class="text-center">Gestiona Alumnos, Anotaciones, Suspenciones y Reportes.</p>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Consulta Anotaciones y Atrasos</h5>
                        <p class="card-text">Reporte Conductual de los Alumnos</p>
                        <a href="./pages/consultas/l.alum.reg.php" class="btn btn-primary">Ir a Anotaciones y Atrasos </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Consulta Asistencia</h5>
                        <p class="card-text">Ver la asistencia de los estudiantes.</p>
                        <a href="./pages/consultas/a.mostrar_asistencia.php" class="btn btn-primary">Ir a Asistencia</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Consulta Notas</h5>
                        <p class="card-text">Ver notas de los Alumnos.</p>
                        <a href="./pages/consultas/c.mostrar_notas.php" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
        </div>

    
        </div>
    <p></p>
    <!-- Pie de página -->
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
