<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<header class="page-header">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Crear Usuario</h1>
    </div>
</header>

<main class="flex items-center justify-center px-6 py-12">

    <div class="panel w-full max-w-2xl rounded-2xl shadow-xl p-10">

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-strong font-display">Nuevo usuario</h2>
            <p class="text-muted mt-2 text-sm">Completa el formulario para registrar un nuevo usuario</p>
        </div>

        <form method="POST" action="index.php?action=user_store" class="space-y-5">

            <!-- Email y Contraseña -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Email</label>
                    <input type="email" name="email" required
                        class="input-field w-full rounded-lg px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Contraseña</label>
                    <input type="password" name="password" required
                        class="input-field w-full rounded-lg px-4 py-2.5">
                </div>
            </div>

            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Nombre de usuario (Alias)</label>
                <input type="text" name="username" required
                    class="input-field w-full rounded-lg px-4 py-2.5">
            </div>

            <!-- Nombre completo -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Nombre</label>
                    <input type="text" name="nombre" required
                        class="input-field w-full rounded-lg px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Apellido Paterno</label>
                    <input type="text" name="ape_paterno" required
                        class="input-field w-full rounded-lg px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Apellido Materno</label>
                    <input type="text" name="ape_materno" required
                        class="input-field w-full rounded-lg px-4 py-2.5">
                </div>
            </div>

            <!-- RUN y Teléfono -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">RUN</label>
                    <input type="text" name="run" required
                        class="input-field w-full rounded-lg px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Teléfono</label>
                    <input type="text" name="telefono" required
                        class="input-field w-full rounded-lg px-4 py-2.5">
                </div>
            </div>

            <!-- Rol -->
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Rol</label>
                <select name="rol_id" required
                    class="input-field w-full rounded-lg px-4 py-2.5 cursor-pointer">
                    <option value="">— Selecciona un rol —</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-between gap-4 pt-4">
                <button type="submit"
                    class="btn-soft-success px-6 py-2.5 rounded-lg font-semibold shadow transition">
                    Guardar
                </button>
                <a href="index.php?action=users"
                    class="btn-secondary px-6 py-2.5 rounded-lg font-medium transition">
                    Regresar
                </a>
            </div>

        </form>
    </div>

</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>