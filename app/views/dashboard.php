<?php
require_once __DIR__ . "/../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

//var_dump($_SESSION['user']);//debugger

$user = $_SESSION['user']; // sacamos todo el usuario de sesión
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
        <a href="index.php?action=logout">Cerrar sesión</a>
    </nav>
    <br><br><br><br><br>
    <?php if ($rol === "administrador"): ?>
        <a href="index.php?action=users">Gestión de usuarios(funcionando)</a><br>
        <a href="#">Reportes(Crear)</a><br>
        <a href="index.php?action=roles">Edicion de Roles(Funcionando)</a><br>
        <a href="index.php?action=anios">Edicion de Años(Funcionando)</a><br>
        <a href="index.php?action=cursos">Edicion de Cursos(Funcionando)</a><br>
        <a href="index.php?action=alumnos">Gestión de Alumnos(Proceso)</a><br>
        <a href="index.php?action=asignaturas">Gestión de Asignaturas(Funcionando)</a><br>
        <a href="index.php?action=curso_asignaturas">CUrso asignaturas(Funcionando)</a><br>
        <a href="index.php?action=alum_emergencia">Alumno Emergencia(Reparar)</a><br>

    <?php elseif ($rol === "docente"): ?>
        <a href="#">Ingresar Notas</a><br>
        <a href="#">Planificaciones</a><br>
    <?php elseif ($rol === "registro"): ?>
        <a href="#">Registro de Anotaciones</a><br>
    <?php elseif ($rol === "asistencia"): ?>
        <a href="#">Control de Asistencia</a><br>
    <?php endif; ?>

    <br><br>
    <br><br>
    <a href="index.php?action=logout">Cerrar sesión</a>
</body>

</html>