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
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.E.I.A -> S.A.A.T.</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="../img/saat.ico" type="image/x-icon">

    <!-- Custom CSS -->
    <style>
        .bounce-animation {
            animation: bounce 1.5s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-15px);
            }
            60% {
                transform: translateY(-7px);
            }
        }

        .logo {
            max-width: 250px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
  <!-- Encabezado de la página -->
  <header class="bg-primary text-white text-center py-1 mb-1">
        <div class="container">
            <h4 class="display-5">Sistema de Acompañamiento y Alerta Temprana</h4>
            <p class="lead">Iniciar sesión para acceder al sistema</p>
        </div>
    </header>
<p></p>
    <!-- Sección de Bienvenida -->
    <section class="py-1">
        <div class="container text-center">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <!-- Logo con animación -->
                <img src="img/logo_saat.jpg" alt="Logo SAAT" class="logo bounce-animation">
               
                <p class="lead">Por favor, <a href="pages/login.php" class="text-primary">inicia sesión</a> para acceder a las funcionalidades.</p>
            <?php else: ?>
                <h2>Bienvenido, <?= $_SESSION['username']; ?></h2>
                <p class="lead">Accede a las funciones según tu rol.</p>
                <div class="row mt-4">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_admin.php" class="btn btn-primary btn-lg">Panel de Administración</a>
                        </div>
                    <?php elseif ($_SESSION['role'] === 'docente'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_docente.php" class="btn btn-primary btn-lg">Panel de Docente</a>
                        </div>
                    <?php elseif ($_SESSION['role'] === 'reg_anotacion'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_reg_anotacion.php" class="btn btn-primary btn-lg">Panel de Registro Anotaciones</a>
                        </div>
                    <?php elseif ($_SESSION['role'] === 'reg_asistencia'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_reg_asistencia.php" class="btn btn-primary btn-lg">Panel de Registro de Asistencia</a>
                        </div>
                    <?php elseif ($_SESSION['role'] === 'asistente_social'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_asistente_social.php" class="btn btn-primary btn-lg">Panel de Registro de Asistente Social</a>
                        </div>
                    <?php elseif ($_SESSION['role'] === 'reg_porteria'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_reg_porteria.php" class="btn btn-primary btn-lg">Panel de Registro Atrasos y Salidas</a>
                        </div>
                    <?php elseif ($_SESSION['role'] === 'consulta'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_consulta.php" class="btn btn-primary btn-lg">Panel de Información</a>
                        </div>
                    <?php elseif ($_SESSION['role'] === 'director'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_director.php" class="btn btn-primary btn-lg">Panel de Dirección</a>
                        </div>
                    <?php elseif ($_SESSION['role'] === 'administrativo'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_administrativo.php" class="btn btn-primary btn-lg">Panel de Administrativo</a>
                        </div>
                        <?php elseif ($_SESSION['role'] === 'soporte'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_soporte.php" class="btn btn-primary btn-lg">Panel de Soporte</a>
                        </div>
                    <?php elseif ($_SESSION['role'] === 'inspector'): ?>
                        <div class="col-md-4">
                            <a href="dashboard_inspector.php" class="btn btn-primary btn-lg">Panel de Inspector General</a>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <a href="pages/logout.php" class="btn btn-secondary btn-lg">Cerrar Sesión</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
