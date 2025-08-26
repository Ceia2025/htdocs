<?php
require_once __DIR__ . '/../controllers/AuthController.php';
$auth = new AuthController();

// Solo deja pasar a rol_id = 1 (admin)
$auth->requireRole(1);


require_once "../config/Connection.php";

$db = new Connection();
$conn = $db->open();

// 1️⃣ Leer roles de la base de datos
$roles = [];
try {
    $stmt = $conn->query("SELECT id, nombre FROM roles2");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener roles: " . $e->getMessage());
}

// 2️⃣ Procesar registro si se envía formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username   = trim($_POST["username"]);
    $password   = trim($_POST["password"]);
    $rol_id     = intval($_POST["rol_id"]);
    $nombre     = trim($_POST["nombre"]);
    $ape_paterno= trim($_POST["ape_paterno"]);
    $ape_materno= trim($_POST["ape_materno"]);
    $run        = trim($_POST["run"]);
    $email      = trim($_POST["email"]);
    $telefono   = trim($_POST["telefono"]);

    if (empty($username) || empty($password) || empty($rol_id) || empty($nombre) || empty($run) || empty($telefono)) {
        die("⚠️ Todos los campos obligatorios deben completarse.");
    }

    try {
        // Validar duplicados
        $check = $conn->prepare("SELECT id FROM usuarios2 WHERE username = :username OR run = :run OR email = :email");
        $check->execute([
            ":username" => $username,
            ":run"      => $run,
            ":email"    => $email
        ]);

        if ($check->rowCount() > 0) {
            die("⚠️ Usuario ya registrado con ese username, run o email.");
        }

        // Hashear contraseña
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertar
        $stmt = $conn->prepare("
            INSERT INTO usuarios2 
            (username, password, rol_id, nombre, ape_paterno, ape_materno, run, email, numero_telefonico) 
            VALUES (:username, :password, :rol_id, :nombre, :ape_paterno, :ape_materno, :run, :email, :telefono)
        ");

        $stmt->execute([
            ":username"    => $username,
            ":password"    => $hashedPassword,
            ":rol_id"      => $rol_id,
            ":nombre"      => $nombre,
            ":ape_paterno" => $ape_paterno,
            ":ape_materno" => $ape_materno,
            ":run"         => $run,
            ":email"       => $email,
            ":telefono"    => $telefono
        ]);

        echo "✅ Usuario registrado correctamente";

    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>

<!-- Formulario de registro -->
<form method="POST" action="">
    <input type="text" name="username" placeholder="Usuario" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>

    <!-- Select dinámico de roles -->
    <label for="rol_id">Rol:</label>
    <select name="rol_id" required>
        <option value="">-- Selecciona un rol --</option>
        <?php foreach ($roles as $rol): ?>
            
            <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre'], ENT_QUOTES, 'UTF-8') ?></option>

        <?php endforeach; ?>
    </select>
    <br>

    <input type="text" name="nombre" placeholder="Nombre" required><br>
    <input type="text" name="ape_paterno" placeholder="Apellido Paterno"><br>
    <input type="text" name="ape_materno" placeholder="Apellido Materno"><br>
    <input type="text" name="run" placeholder="RUN" required><br>
    <input type="email" name="email" placeholder="Correo"><br>
    <input type="text" name="telefono" placeholder="Teléfono" required><br>
    <button type="submit">Registrar</button>
</form>
