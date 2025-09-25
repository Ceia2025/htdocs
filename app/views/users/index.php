<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth(); // obliga a tener sesión iniciada

$user = $_SESSION['user']; // usuario logueado
$nombre = $user['nombre'];
$rol = $user['rol'];

// Incluir layout
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<html class="h-full bg-gray-900">

<body class="h-full">
    <div class="min-h-full">

        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Gestión de Usuarios</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- Botón Crear -->
                <div class="mb-4">
                    <a href="index.php?action=user_create"
                        class="inline-block rounded-md bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-400">
                        Crear nuevo
                    </a>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-950/50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Rut</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Username</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Teléfono</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Rol</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-500/30 divide-y divide-gray-600">
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                        <?= htmlspecialchars($user['id']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                        <?= htmlspecialchars($user['run']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                        <?= htmlspecialchars($user['username']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                        <?= htmlspecialchars($user['nombre']) ?>
                                        <?= htmlspecialchars($user['ape_paterno']) ?>
                                        <?= htmlspecialchars($user['ape_materno']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                        <?= htmlspecialchars($user['email']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                        <?= htmlspecialchars($user['numero_telefonico']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                        <?= htmlspecialchars($user['rol']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="index.php?action=user_edit&id=<?= $user['id'] ?>"
                                            class="text-indigo-400 hover:text-indigo-200 font-semibold">
                                            ✏ Editar
                                        </a>
                                        <a href="index.php?action=user_delete&id=<?= $user['id'] ?>"
                                            onclick="return confirm('¿Seguro que deseas eliminar este usuario?')"
                                            class="ml-4 text-red-400 hover:text-red-200 font-semibold">
                                            🗑 Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Regresar -->
                <div class="mt-6">
                    <a href="index.php?action=dashboard"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        Regresar
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>