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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <!-- Enlace a Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="icon" href="../img/saat.ico" type="image/x-icon">
</head>
<body>

    <div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="text-center">
            <!-- Encabezado de la página -->
            <h1 class="display-4 text-danger">Acceso Denegado</h1>
            <p class="lead">Lo sentimos, no tienes permiso para acceder a esta página.</p>
            
            <!-- Icono de advertencia -->
            <div class="my-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-exclamation-triangle text-danger" viewBox="0 0 16 16">
                    <path d="M7.938 2.016a.13.13 0 0 1 .125 0l6.857 3.945c.11.064.179.182.179.312v7.906a.374.374 0 0 1-.18.312L8.063 14.987a.13.13 0 0 1-.125 0L1.08 11.49a.374.374 0 0 1-.18-.312V3.978a.374.374 0 0 1 .18-.312l6.857-3.945zM8 4.667v4.867a.5.5 0 0 0 1 0V4.667a.5.5 0 1 0-1 0zm1 7a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>
            </div>

            <!-- Botón para volver al inicio de sesión -->
            <a href="../pages/login.php" class="btn btn-primary btn-lg">Volver al Inicio de Sesión</a>
        </div>
        <P></P>
        <P></P>
                <!-- Pie de página -->
                <footer class="text-black text-center py-3">
        <div class="container">
            <p>&copy; 2024 Escuela Juanita Zuñiga Fuentes C.E.I.A. Parral</p>
        </div>
    </footer>
    </div>



    <!-- Script de Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeo1deRBk7ikA5A6M7amFQNIB1cAVPnhE4L8zSmwrNfIMq6Y" crossorigin="anonymous"></script>
</body>
</html>
