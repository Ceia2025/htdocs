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

// Verificar si el usuario está autenticado y tiene el rol de portería
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'reg_porteria') {
    header('Location: acceso_denegado.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Portería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../img/saat.ico" type="image/x-icon">
</head>
<body>
<?php include './includes/navbar.php'; 
include './includes/verificar_licencia_local.php';
?>

    <!-- Contenido principal -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h1 class="display-4">Bienvenido al Panel de Portería</h1>
                <p class="lead">Este es tu panel de control para la gestión de entradas y salidas.</p>
            </div>
        </div>

        <!-- Tarjetas de opciones -->
        <div class="row">
            <!-- Tarjeta 1 -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Registrar Atraso de Estudiantes</h5>
                        <p class="card-text">Accede al módulo para registrar un atraso de los estudiantes.</p>
                        <a href="./pages/ingresos/in.atraso.php" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2 -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Registrar Salida de Estudiantes</h5>
                        <p class="card-text">Accede al módulo para registrar las salidas de los estudiantes.</p>
                        <a href="./pages/ingresos/in.salida.php" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3 -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Ver Historial de Conducta</h5>
                        <p class="card-text">Registro conductual de los estudiantes.</p>
                        <a href="./pages/consultas/l.alum.reg.php" class="btn btn-primary">Acceder</a>
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
