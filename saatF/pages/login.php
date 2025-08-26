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
include '../includes/conexion.php'; // Archivo donde se conecta a la base de datos


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Crear una nueva instancia de la conexión
    $database = new Connection();
    $conn = $database->open();

    // Consulta para verificar el usuario utilizando PDO
    $query = "SELECT * FROM usuarios WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    // Verificar si el usuario existe
    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica la contraseña utilizando password_verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            

            /*
             $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            */


            // Redirigir a diferentes páginas según el rol
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../dashboard_admin.php");
                    break;

                case 'docente':
                    header("Location: ../dashboard_docente.php");
                    break;

                    case 'administrativo':
                        header("Location: ../dashboard_administrativo.php");
                        break;

                        case 'soporte':
                            header("Location: ../dashboard_soporte.php");
                            break;
    

                case 'asistente_social':
                    header("Location: ../dashboard_asistente_social.php");
                    break;

                case 'consulta':
                    header("Location: ../dashboard_consulta.php");
                    break;

                case 'reg_anotacion':
                    header("Location: ../dashboard_reg_anotacion.php");
                    break;

                case 'reg_asistencia':
                    header("Location: ../dashboard_reg_asistencia.php");
                    break;

                case 'reg_porteria':
                    header("Location: ../dashboard_reg_porteria.php");
                    break;

                case 'inspector':
                    header("Location: ../dashboard_inspector.php");
                    break;

                case 'director':
                    header("Location: ../dashboard_director.php");
                    break;   
                     
                default:
                    header("Location: ../index.php");
                    break;
            }
            exit;
        } else {
            $error = "Nombre de usuario o contraseña incorrectos.";
        }
    } else {
        $error = "Nombre de usuario o contraseña incorrectos.";
    }

    // Cerrar la conexión
    $database->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="icon" href="../img/saat.ico" type="image/x-icon">
</head>
<body>
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
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>

    <!-- Encabezado de la página -->
    <header class="bg-primary text-white text-center py-1 mb-1">
        <div class="container">
            <h4 class="display-5">Sistema de Acompañamiento y Alerta Temprana</h4>
            <p class="lead">Iniciar sesión para acceder al sistema</p>
        </div>
    </header>

    <!-- Formulario de inicio de sesión -->
    <section class="py-1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                <p></p>
                    <div class="card">
                    <div class="d-flex justify-content-center">
                    <p></p>
                <!-- Logo con animación -->
                <img src="../img/logo_saat.jpg" alt="Logo SAAT" class="logo bounce-animation">
</div>
                        <div class="card-body">


                            <h2 class="card-title text-center mb-1">Iniciar Sesión</h2>

                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger text-center" role="alert">
                                    <?= $error; ?>
                                </div>
                            <?php endif; ?>

                            <form action="login.php" method="POST">
                                <div class="mb-2">
                                    <label for="username" class="form-label">Nombre de usuario</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Ingresar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
            <?php include '../includes/footer.php'; ?>
        </div>
    </section>
</body>
</html>
