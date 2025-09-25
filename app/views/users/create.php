<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

// Incluir layout
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="h-screen">
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Editar Asignatura</h1>
        </div>
    </header>

    <div class="h-screen isolate bg-gray-900 flex items-center justify-center px-6 py-24 sm:py-32 lg:px-8">


        <!-- HEADER -->

        <div class="w-full max-w-2xl bg-gray-800/60 backdrop-blur-md rounded-2xl shadow-xl p-10 mt-12">
            <!-- Título -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white">Crear Usuario</h1>
                <p class="text-gray-400 mt-2">Completa el formulario para registrar un nuevo usuario</p>
            </div>
            <form method="post" action="index.php?action=user_store" class="space-y-6">

                <!-- Email y Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Email</label>
                        <input type="email" name="email" required
                            class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Contraseña</label>
                        <input type="password" name="password" required
                            class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <!-- Usuario -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Nombre de usuario (Alias)</label>
                    <input type="text" name="username" required
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <!-- Nombre completo -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nombre</label>
                        <input type="text" name="nombre" required
                            class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Apellido Paterno</label>
                        <input type="text" name="ape_paterno" required
                            class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Apellido Materno</label>
                        <input type="text" name="ape_materno" required
                            class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <!-- RUN y Teléfono -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">RUT</label>
                        <input type="text" name="run" required
                            class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Teléfono</label>
                        <input type="text" name="telefono" required
                            class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <!-- Rol -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Rol</label>
                    <select name="rol_id" required
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="">-- Selecciona un rol --</option>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Botones -->
                <div class="flex justify-between items-center pt-6">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-500 transition">
                        Guardar
                    </button>
                    <div class="space-x-4 text-sm">
                        <a href="index.php?action=users"
                            class="ml-4 px-6 py-2.5 rounded-lg bg-gray-700 text-white font-medium hover:bg-gray-600 transition">
                            Regresar
                        </a>


                    </div>

                </div>

            </form>

        </div>

    </div>

</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>