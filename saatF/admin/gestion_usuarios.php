<!--

Todo este codigo debe ir en corchetes de php

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
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: acceso_denegado.php');
    exit();
}

include '../includes/navbar.php';

include '../includes/config.php';

// Agregar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_usuario'])) {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    // Insertar el nuevo usuario en la base de datos
    $query = "INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sss', $username, $password, $role);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success'>Usuario agregado con éxito.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al agregar usuario: " . mysqli_error($conn) . "</div>";
    }
    
    mysqli_stmt_close($stmt);
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $user_id = $_GET['eliminar'];
    
    $query = "DELETE FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success'>Usuario eliminado con éxito.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar usuario: " . mysqli_error($conn) . "</div>";
    }
    
    mysqli_stmt_close($stmt);
}

// Obtener todos los usuarios
$query = "SELECT * FROM usuarios";
$result = mysqli_query($conn, $query);



-->

<?php
session_start();
// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: acceso_denegado.php');
    exit();
}

include '../includes/navbar.php';
include '../includes/config.php'; // aquí ya tienes $conn como PDO

// Agregar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_usuario'])) {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = $_POST['role'];

    try {
        $query = "INSERT INTO usuarios (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        echo "<div class='alert alert-success'>Usuario agregado con éxito.</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error al agregar usuario: " . $e->getMessage() . "</div>";
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $user_id = $_GET['eliminar'];

    try {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "<div class='alert alert-success'>Usuario eliminado con éxito.</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error al eliminar usuario: " . $e->getMessage() . "</div>";
    }
}

// Obtener todos los usuarios
try {
    $query = "SELECT * FROM usuarios";
    $stmt = $conn->query($query);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error al obtener usuarios: " . $e->getMessage() . "</div>";
}
?>


<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="icon" href="/saat3/img/saat.ico" type="image/x-icon">
</head>

<body>
    <div class="container mt-4">
        <h2>Gestión de Usuarios</h2>


        <!-- Formulario para agregar nuevo usuario -->
        <form action="gestion_usuarios.php" method="POST" class="mt-3">
            <h4>Agregar Usuario</h4>
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de Usuario:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rol:</label>
                <select name="role" class="form-select" required>
                    <option value="admin">Administrador</option>
                    <option value="soporte">Soporte</option>
                    <option value="direccion">Dirección</option>
                    <option value="administrativo">Administrativo</option>
                    <option value="inspectorgral">Inspector General</option>
                    <option value="docente">Docente</option>
                    <option value="asistente_social">Asistente Social</option>
                    <option value="consulta">Solo Visualización</option>
                    <option value="reg_anotacion">Registro de Anotaciones</option>
                    <option value="reg_asistencia">Registro de Asistencia</option>
                    <option value="reg_porteria">Registro Atrasos y Salidas</option>
                </select>
            </div>
            <button type="submit" name="agregar_usuario" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Agregar Usuario
            </button>
        </form>

        <hr>

        <!-- Tabla de usuarios existentes -->

        <h4>Lista de Usuarios</h4>
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                <?php foreach ($usuarios as $row): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= ucfirst(htmlspecialchars($row['role'])); ?></td>
                        <td>
                            <a href="editar_usuario2.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="gestion_usuarios.php?eliminar=<?= $row['id']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </tbody>
        </table>


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