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

<html class="h-full bg-gray-900">
<body class="h-full">
<div class="min-h-full">

    <!-- HEADER -->
    <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Mantenedor de Individualización</h1>
        </div>
    </header>

    <!-- MAIN -->
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- BOTÓN CREAR -->
            <div class="mb-6 flex justify-end">
                <a href="index.php?action=categorizacion_create"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition duration-200">
                    ➕ Crear Individualización
                </a>
            </div>

            <!-- TABLA -->
            <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-950/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Código General</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Código Específico</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="bg-gray-500/30 divide-y divide-gray-600">
                    <?php if (!empty($categorizaciones)): ?>
                        <?php foreach ($categorizaciones as $c): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100"><?= htmlspecialchars($c['id']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100"><?= htmlspecialchars($c['nombre']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100"><?= htmlspecialchars($c['codigo_general']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100"><?= htmlspecialchars($c['codigo_especifico']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100 space-x-3">
                                    <a href="index.php?action=categorizacion_edit&id=<?= $c['id'] ?>"
                                       class="text-indigo-400 hover:text-indigo-300 font-medium">✏️ Editar</a>
                                    <a href="index.php?action=categorizacion_delete&id=<?= $c['id'] ?>"
                                       onclick="return confirm('¿Seguro que quieres eliminar esta categorización?');"
                                       class="text-red-400 hover:text-red-300 font-medium">🗑 Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-300">No hay categorizaciones registradas.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- VOLVER -->
            <div class="mt-8 flex items-center justify-center">
                <a href="index.php?action=dashboard"
                   class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                    ⬅ Dashboard
                </a>
            </div>
        </div>
    </main>
</div>
</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>
