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
include('../includes/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); 
    $role = $_POST['role'];

    $database = new Connection();
    $conn = $database->open();

    try {
        // Verificar si el usuario ya existe
        $check_query = "SELECT COUNT(*) FROM usuarios WHERE username = :username";
        $stmt = $conn->prepare($check_query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $exists = $stmt->fetchColumn();

        if ($exists > 0) {
            echo "Error: El nombre de usuario ya existe.";
        } else {
            // Insertar nuevo usuario
            $query = "INSERT INTO usuarios (username, password, role) VALUES (:username, :password, :role)";
            $stmt_insert = $conn->prepare($query);
            $stmt_insert->bindParam(':username', $username);
            $stmt_insert->bindParam(':password', $password);
            $stmt_insert->bindParam(':role', $role);

            if ($stmt_insert->execute()) {
                echo "Usuario agregado con éxito.";
            } else {
                echo "Error al insertar el usuario.";
            }
        }
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }

    $database->close();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Registrar Usuario</h2>
        <form action="register.php" method="POST" class="form-group">
            <div class="mb-3">
                <label for="username">Nombre de usuario:</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="role">Rol:</label>
                <select name="role" class="form-select" required>
                    <option value="admin">Administrador</option>
                    <option value="soporte">Soporte</option>
                    <option value="administrativo">Administrativo</option>
                    <option value="docente">Docente</option>
                    <option value="asistente_social">Asistente Social</option>
                    <option value="consulta">Solo Visualización</option>
                    <option value="reg_anotacion">Registro de Anotaciones</option>
                    <option value="reg_asistencia">Registro de Asistencias</option>
                    <option value="reg_porteria">Registro de Salidas o Atrasos</option>
                    <option value="inspector">Registro de Asistencias</option>
                    <option value="director">Registro de Salidas o Atrasos</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>
</body>
</html>
