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
include '../includes/config.php'; // Conexión a la base de datos
include '../includes/navbar.php'; // Navbar

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: acceso_denegado.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener los detalles del usuario
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) !== 1) {
    echo "<div class='alert alert-danger'>Usuario no encontrado.</div>";
    exit;
}

$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Procesar la actualización de la contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_clave'])) {
    $password_actual = trim($_POST['password_actual']);
    $password_nueva = trim($_POST['password_nueva']);
    $password_confirmacion = trim($_POST['password_confirmacion']);

    // Verificar que la contraseña actual coincida
    if (password_verify($password_actual, $user['password'])) {
        // Verificar que la nueva contraseña y la confirmación coincidan
        if ($password_nueva === $password_confirmacion) {
            $password_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
            $query = "UPDATE usuarios SET password = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'si', $password_hash, $user_id);

            if (mysqli_stmt_execute($stmt)) {
                echo "<div class='alert alert-success'>Contraseña actualizada con éxito.</div>";
                echo "<script>setTimeout(function(){ window.location.href = '..//index.php'; }, 1000);</script>"; // Redirigir al inicio
            } else {
                echo "<div class='alert alert-danger'>Error al actualizar la contraseña.</div>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<div class='alert alert-danger'>Las nuevas contraseñas no coinciden.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>La contraseña actual es incorrecta.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Cambiar Contraseña</h2>
        <form action="cambiar_clave.php" method="POST">
            <div class="mb-3">
                <label for="password_actual" class="form-label">Contraseña Actual:</label>
                <input type="password" class="form-control" name="password_actual" required>
            </div>
            <div class="mb-3">
                <label for="password_nueva" class="form-label">Nueva Contraseña:</label>
                <input type="password" class="form-control" name="password_nueva" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmacion" class="form-label">Confirmar Nueva Contraseña:</label>
                <input type="password" class="form-control" name="password_confirmacion" required>
            </div>
            <button type="submit" name="cambiar_clave" class="btn btn-primary">Actualizar Contraseña</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
