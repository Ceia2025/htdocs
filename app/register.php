<?php
//DESCOMENTAR LINEAS 3 Y 188
/*
require_once __DIR__ . '/config/Connection.php';

$db = new Connection();
$conn = $db->open();

$roles = [];
try {
    $stmt = $conn->query("SELECT id, nombre FROM roles2");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener roles: " . $e->getMessage());
}

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username    = trim($_POST["username"]);
    $password    = trim($_POST["password"]);
    $rol_id      = intval($_POST["rol_id"]);
    $nombre      = trim($_POST["nombre"]);
    $ape_paterno = trim($_POST["ape_paterno"]);
    $ape_materno = trim($_POST["ape_materno"]);
    $run         = trim($_POST["run"]);
    $email       = trim($_POST["email"]);
    $telefono    = trim($_POST["telefono"]);

    if (empty($username) || empty($password) || empty($rol_id) || empty($nombre) || empty($run) || empty($telefono)) {
        $mensaje = "Todos los campos obligatorios deben completarse.";
        $tipo_mensaje = "error";
    } else {
        try {
            $check = $conn->prepare("SELECT id FROM usuarios2 WHERE username = :username OR run = :run OR email = :email");
            $check->execute([":username" => $username, ":run" => $run, ":email" => $email]);

            if ($check->rowCount() > 0) {
                $mensaje = "Ya existe un usuario con ese username, RUN o email.";
                $tipo_mensaje = "error";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
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

                // ✅ Redirige al login después de registrar
                header("Location: http://localhost:8080/app/public/index.php?action=login");
                exit;
            }
        } catch (PDOException $e) {
            $mensaje = "Error: " . $e->getMessage();
            $tipo_mensaje = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario — SAAT</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-900 flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-2xl">

        <!-- Logo / Título -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white tracking-tight">Registro de Usuario</h1>
            <p class="text-gray-400 text-sm mt-1">Sistema SAAT · C.E.I.A. Parral</p>
        </div>

        <!-- Alerta -->
        <?php if ($mensaje): ?>
            <div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium
                <?= $tipo_mensaje === 'error' ? 'bg-red-900/40 border border-red-600 text-red-300' : 'bg-green-900/40 border border-green-600 text-green-300' ?>">
                <?= $tipo_mensaje === 'error' ? '⚠️' : '✅' ?> <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-6">

            <!-- Datos personales -->
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 space-y-4">
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Datos Personales</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Nombre <span class="text-red-400">*</span></label>
                        <input type="text" name="nombre" required placeholder="Ej: Juan"
                            class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Apellido Paterno</label>
                        <input type="text" name="ape_paterno" placeholder="Ej: González"
                            class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Apellido Materno</label>
                        <input type="text" name="ape_materno" placeholder="Ej: Pérez"
                            class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">RUN <span class="text-red-400">*</span></label>
                        <input type="text" name="run" required placeholder="Ej: 12345678-9"
                            class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Teléfono <span class="text-red-400">*</span></label>
                        <input type="text" name="telefono" required placeholder="Ej: +56912345678"
                            class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" placeholder="Ej: juan@correo.cl"
                        class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
            </div>

            <!-- Acceso al sistema -->
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 space-y-4">
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Acceso al Sistema</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Usuario <span class="text-red-400">*</span></label>
                        <input type="text" name="username" required placeholder="Ej: jgonzalez"
                            class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Contraseña <span class="text-red-400">*</span></label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Rol <span class="text-red-400">*</span></label>
                    <select name="rol_id" required
                        class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">-- Selecciona un rol --</option>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-4">
                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white font-semibold rounded-xl py-3 shadow-lg transition text-sm">
                    Registrar Usuario
                </button>
                <a href="http://localhost:8080/app/public/index.php?action=login"
                    class="w-full text-center bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl py-3 transition text-sm">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</body>
</html>

*/