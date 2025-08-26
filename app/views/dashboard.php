<?php
require_once __DIR__ . "/../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user']; // 👈 sacamos todo el usuario de sesión
$rol = $user['rol'];
$nombre = $user['nombre'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body>
    <h1>Bienvenido, <?= htmlspecialchars($nombre) ?></h1>
    <h3>Rol: <?= htmlspecialchars($rol) ?></h3>

    <nav>
        <a href="index.php?action=logout">Cerrar sesión</a> |
        <a href="index.php?action=users">Gestión de usuarios</a>
    </nav>

    <?php if ($rol === "admin"): ?>
        <a href="#">Gestión de usuarios</a><br>
        <a href="#">Reportes</a><br>
    <?php elseif ($rol === "docente"): ?>
        <a href="#">Ingresar Notas</a><br>
        <a href="#">Planificaciones</a><br>
    <?php elseif ($rol === "registro"): ?>
        <a href="#">Registro de Anotaciones</a><br>
    <?php elseif ($rol === "asistencia"): ?>
        <a href="#">Control de Asistencia</a><br>
    <?php endif; ?>

    <br><a href="index.php?action=logout">Cerrar sesión</a>
</body>

</html>
