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
    <title>Error 404 - Página no encontrada</title>
    <!-- Enlace a Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="icon" href="../img/saat.ico" type="image/x-icon">
</head>
<body>


     <!-- Custom CSS -->
     <style>

        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .message {
            font-size: 1.5rem;
            font-weight: 500;
        }
        .error-code {
            font-size: 4rem;
            font-weight: bold;
            color: #dc3545;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 10px;
            background-color: #343a40;
            color: white;
            text-align: center;
        }

        .bounce-animation {
        animation: bounce 1.5s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }

    </style>

    <div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
    <img src="/img/logo_saat.jpg" alt="Logo SAAT" class="logo ">
        <div class="text-center">
            <!-- Encabezado de la página -->
            <h1 class="display-4 text-danger">Error 404</h1>
            <h1 class="display-8 text-danger">Página No Encontrada</h1>
            <p class="lead">Lo sentimos, la página que buscas no se encuentra disponible.</p>
            <p class="lead">Es posible que la dirección esté mal escrita o la página haya sido movida.</p>
            
<!-- Icono moderno con animación -->
<div class="my-4 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" 
         class="bi bi-exclamation-circle text-danger bounce-animation" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0-1A6 6 0 1 1 8 2a6 6 0 0 1 0 12z"/>
        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm.93-6.418a.5.5 0 0 1 .998 0l-.35 4.5a.5.5 0 0 1-.998 0l-.35-4.5z"/>
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
