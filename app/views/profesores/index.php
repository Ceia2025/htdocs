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

<body class="h-full bg-gray-900">
<div class="min-h-full">

    <!-- HEADER -->
    <header class="relative bg-gray-800 after:absolute after:inset-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-6">
            <h1 class="text-3xl font-bold tracking-tight text-white">Profesores</h1>
        </div>
    </header>

    <!-- MAIN -->
    <main>
        <div class="mx-auto max-w-7xl px-4 py-8">

            <!-- BOTÓN CREAR -->
            <div class="mb-6 flex justify-end">
                <a href="index.php?action=profesor_create"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition">
                    ➕ Nuevo Profesor
                </a>
            </div>

            <!-- TABLA -->
            <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-950/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">RUN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="bg-gray-500/30 divide-y divide-gray-600">
                    <?php if (!empty($profesores)): ?>
                        <?php foreach ($profesores as $p): ?>
                            <tr class="hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-100">
                                    <?= htmlspecialchars($p['run']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-100">
                                    <?= htmlspecialchars($p['nombres'] . " " . $p['apepat'] . " " . $p['apemat']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-100 capitalize">
                                    <?= htmlspecialchars($p['tipo']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-100">
                                    <?= $p['estado'] === 'activo' ? '🟢 Activo' : '🔴 Inactivo' ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-100 space-x-3">
                                    <a href="index.php?action=profesor_edit&id=<?= $p['id'] ?>"
                                       class="text-indigo-400 hover:text-indigo-300 font-medium">✏️ Editar</a>

                                    <a href="index.php?action=profesor_delete&id=<?= $p['id'] ?>"
                                       onclick="return confirm('¿Eliminar profesor?');"
                                       class="text-red-400 hover:text-red-300 font-medium">🗑 Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-300">
                                No hay profesores registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- VOLVER -->
            <div class="mt-8 flex justify-center">
                <a href="index.php?action=dashboard"
                   class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600">
                    ⬅ Dashboard
                </a>
            </div>

        </div>
    </main>

</div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>
