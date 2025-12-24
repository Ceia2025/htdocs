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

<main>
    <!-- HEADER -->
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Gestión de Roles</h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">


        <!-- CREAR NUEVO ROL -->
        <div class="mb-8 flex items-center justify-center">
            <div class="bg-gray-700 p-6 rounded-2xl shadow-lg w-full max-w-md">
                <h2 class="text-2xl font-bold text-white mb-6 text-center">Nuevo Rol</h2>

                <form method="post" action="index.php?action=createRole" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nombre del Rol</label>
                        <input type="text" name="nombre" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
                        Guardar
                    </button>
                </form>
            </div>
        </div>

        <!-- TABLA -->
        <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-950/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase">
                            Nombre
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody id="rolesTableBody" class="bg-gray-500/30 divide-y divide-gray-600">
                    <?php foreach ($roles as $r): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-100 capitalize">
                                <?= htmlspecialchars($r['nombre']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-100 space-x-3">
                                <a href="index.php?action=editRole&id=<?= $r['id'] ?>"
                                    class="text-indigo-400 hover:text-indigo-300">Editar</a>
                                <a href="index.php?action=deleteRole&id=<?= $r['id'] ?>"
                                    onclick="return confirm('¿Eliminar este rol?')"
                                    class="text-red-400 hover:text-red-300">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- VOLVER -->
        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=dashboard"
                class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                ⬅️ Volver al Dashboard
            </a>
        </div>
    </div>
</main>


<?php include __DIR__ . "/../layout/footer.php"; ?>