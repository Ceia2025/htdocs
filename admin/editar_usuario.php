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
include '../includes/config.php';

// Verificar permisos ANTES de cargar navbar
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "No tienes permisos para acceder a esta página.";
    header('Location: acceso_denegado.php');
    exit;
}

// Validar ID antes de incluir navbar
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de usuario no especificado o inválido.";
    header('Location: gestion_usuarios.php');
    exit;
}



$user_id = (int) $_GET['id'];


// Obtener los detalles del usuario
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) !== 1) {
    $_SESSION['error'] = "Usuario no encontrado.";
    header('Location: gestion_usuarios.php');
    exit;
}

$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Actualizar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (empty($username) || empty($role)) {
        $_SESSION['error'] = "El nombre de usuario y el rol son obligatorios.";
        header("Location: editar_usuario2.php?id=$user_id");
        exit;
    }

    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE usuarios SET username = ?, password = ?, role = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssi', $username, $password, $role, $user_id);
    } else {
        $query = "UPDATE usuarios SET username = ?, role = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssi', $username, $role, $user_id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Usuario actualizado con éxito.";
        header("Location: gestion_usuarios.php");
        exit;
    } else {
        $_SESSION['error'] = "Error al actualizar usuario: " . mysqli_error($conn);
        header("Location: editar_usuario2.php?id=$user_id");
        exit;
    }

    //mysqli_stmt_close($stmt);
}

mysqli_close($conn);
include '../includes/navbar.php'; 
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2><i class="fas fa-user-edit"></i> Editar Usuario</h2>
        <hr>

        <!-- Mensajes de éxito/error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Formulario para editar usuario -->
        <form action="editar_usuario2.php?id=<?= htmlspecialchars($user_id); ?>" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label"><i class="fas fa-user"></i> Nombre de Usuario:</label>
                <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Contraseña (dejar en blanco si no se desea cambiar):</label>
                <input type="password" class="form-control" name="password">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label"><i class="fas fa-user-tag"></i> Rol:</label>
                <select name="role" class="form-select" required>
                    <?php
                    $roles = [
                        "admin" => "Administrador",
                        "soporte" => "Soporte",
                        "director" => "Dirección",
                        "inspector" => "Inspector General",
                        "docente" => "Docente",
                        "asistente_social" => "Asistente Social",
                        "consulta" => "Solo Visualización",
                        "reg_anotacion" => "Registro de Anotaciones",
                        "reg_asistencia" => "Registro de Asistencia",
                        "reg_porteria" => "Registro Atrasos y Salidas"
                    ];
                    foreach ($roles as $key => $value) {
                        echo "<option value='$key' " . ($user['role'] === $key ? 'selected' : '') . ">$value</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="actualizar_usuario" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar Usuario
            </button>
        </form>

        <a href="gestion_usuarios.php" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i> Volver a la gestión de usuarios
        </a>
    </div>

    <!-- Pie de página -->
    <footer class="bg-primary text-white text-center py-3 mt-4">
        <div class="container">
                <!-- Pie de página -->
    <?php include '../includes/footer.php'; ?>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
