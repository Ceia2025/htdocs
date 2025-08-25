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
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrativo') {
    header('Location: acceso_denegado.php');
    exit();
}
?>


<?php
//include('./includes/functions.php');
//checkRole(['admin']); // Solo el administrador puede acceder
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
    <title>Panel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="./img/saat.ico" type="image/x-icon">
</head>
<body>

<?php 
//include './includes/navbar.php'; 
?>

    <div class="container mt-5">
        <h1 class="text-center">Bienvenido al Panel de Gestión</h1>
        <p class="text-center">Gestiona de Notas y Actualizaciones.</p>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Notas</h5>
                        <p class="card-text">Agregar, editar o eliminar.</p>
                        <a href="./pages/ingresos/in.notas.php"" class="btn btn-primary">Ir a Notas</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Ficha</h5>
                        <p class="card-text">Ver y editar la Ficha del estudiantes.</p>
                        <a href="./pages/mantenedor/m.alum.php" class="btn btn-primary">Ir a Ficha</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gestion Anotaciones</h5>
                        <p class="card-text">Edición de Anotaciones.</p>
                        <a href="./pages/mantenedor/m.alum.reg.php" class="btn btn-primary">Ir Anotaciones</a>
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
