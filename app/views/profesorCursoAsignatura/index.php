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

<body class="h-full bg-gradient-to-br from-gray-900 via-gray-900 to-gray-800 text-gray-100">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="relative bg-gray-900/70 backdrop-blur border-b border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">
                    Asignación de Profesores
                </h1>
                <p class="mt-1 text-sm text-gray-400">
                    Gestión de profesores, cursos y asignaturas
                </p>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6">

                <!-- BOTÓN CREAR -->
                <div class="mb-6 flex justify-end">
                    <a href="index.php?action=pca_create"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow transition">
                        ➕ Nueva Asignación
                    </a>
                </div>

                <!-- TABLA -->
                <div class="overflow-hidden rounded-3xl bg-gray-900/70 backdrop-blur shadow-xl border border-white/10">
                    <table class="min-w-full divide-y divide-gray-700">

                        <thead class="bg-gray-700/80">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Profesor
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Curso
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Asignatura
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Año
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-800">

                            <?php if (!empty($asignaciones)): ?>
                                <?php foreach ($asignaciones as $a): ?>
                                    <tr class="group transition-all duration-200 hover:bg-indigo-600/10">

                                        <td class="px-6 py-4 font-medium text-gray-100">
                                            <?= htmlspecialchars($a['profesor_nombre']) ?>
                                        </td>

                                        <td class="px-6 py-4 text-gray-200">
                                            <?= htmlspecialchars($a['curso_nombre']) ?>
                                        </td>

                                        <td class="px-6 py-4 text-gray-300">
                                            <?= htmlspecialchars($a['asignatura_nombre']) ?>
                                        </td>

                                        <td class="px-6 py-4 text-gray-300">
                                            <?= htmlspecialchars($a['anio']) ?>
                                        </td>

                                        <td class="px-6 py-4 space-x-4">
                                            <a href="index.php?action=pca_edit&id=<?= $a['id'] ?>"
                                                class="inline-flex items-center gap-1 text-indigo-400 hover:text-indigo-300 transition">
                                                ✏️ Editar
                                            </a>

                                            <a href="index.php?action=pca_delete&id=<?= $a['id'] ?>"
                                                onclick="return confirm('¿Eliminar esta asignación?')"
                                                class="inline-flex items-center gap-1 text-red-400 hover:text-red-300 transition">
                                                🗑 Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                        No hay asignaciones registradas
                                    </td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <!-- VOLVER -->
                <div class="mt-10 flex justify-center">
                    <a href="index.php?action=dashboard"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-xl shadow transition">
                        ⬅ Volver al Dashboard
                    </a>
                </div>

            </div>
        </main>

    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>