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

<body class="h-full bg-gray-900 text-white">
    <div class="min-h-full">

        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Asignación de Profesoresss</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6">

                <!-- BOTÓN CREAR -->
                <div class="mb-6 flex justify-end">
                    <a href="index.php?action=pca_create"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow">
                        ➕ Nueva Asignación
                    </a>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto bg-gray-800 rounded-2xl shadow-lg">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-900/60">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Profesor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Curso</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Asignatura
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Año</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-700 bg-gray-800/40">

                            <?php if (!empty($asignaciones)): ?>
                                <?php foreach ($asignaciones as $a): ?>
                                    <tr class="hover:bg-gray-700/40 transition cursor-pointer">

                                        <td class="px-6 py-4"><?= htmlspecialchars($a['profesor_nombre']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($a['curso_nombre']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($a['asignatura_nombre']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($a['anio']) ?></td>

                                        <td class="px-6 py-4 space-x-4">
                                            <a href="index.php?action=pca_edit&id=<?= $a['id'] ?>"
                                                class="text-indigo-400 hover:text-indigo-300">✏️ Editar</a>

                                            <a href="index.php?action=pca_delete&id=<?= $a['id'] ?>"
                                                onclick="return confirm('¿Eliminar esta asignación?')"
                                                class="text-red-400 hover:text-red-300">🗑 Eliminar</a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                        No hay asignaciones registradas.
                                    </td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <!-- VOLVER -->
                <div class="mt-8 flex justify-center">
                    <a href="index.php?action=dashboard"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg shadow">
                        ⬅ Dashboard
                    </a>
                </div>

            </div>
        </main>

    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>