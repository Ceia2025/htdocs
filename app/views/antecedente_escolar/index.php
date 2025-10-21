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
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Antecedentes Escolares</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- BOTÓN CREAR -->
                <div class="mb-6 flex justify-end">
                    <a href="index.php?action=antecedente_escolar_create"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition duration-200">
                        ➕ Nuevo Antecedente
                    </a>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-950/50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Último Curso
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Año
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Repetidos
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    20%
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Prob. Apren.
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    PIE
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Chile Solidario
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-gray-500/30 divide-y divide-gray-600">
                            <?php if (!empty($antecedentes)): ?>
                                <?php foreach ($antecedentes as $a): ?>
                                    <tr class="hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-100">
                                            <?= htmlspecialchars($a['ultimo_curso'] ?? '-') ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-100">
                                            <?= htmlspecialchars($a['ultimo_anio_cursado'] ?? '-') ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-100 text-center">
                                            <?= htmlspecialchars($a['cursos_repetidos'] ?? 0) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-100 text-center">
                                            <?= $a['pertenece_20'] ? '✅' : '❌' ?>
                                        </td>
                                       
                                        <td class="px-6 py-4 text-sm text-gray-100">
                                            <?= htmlspecialchars($a['prob_apren'] ?? '-') ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-100"><?= htmlspecialchars($a['pie'] ?? '-') ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-100 text-center">
                                            <?= $a['chile_solidario'] ? htmlspecialchars($a['chile_solidario_cual'] ?? 'Sí') : 'No' ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-100 space-x-3">
                                            <a href="index.php?action=antecedente_escolar_edit&id=<?= $a['id'] ?>"
                                                class="text-indigo-400 hover:text-indigo-300 font-medium">✏️ Editar</a>
                                            <a href="index.php?action=antecedente_escolar_delete&id=<?= $a['id'] ?>"
                                                onclick="return confirm('¿Seguro que deseas eliminar este antecedente?');"
                                                class="text-red-400 hover:text-red-300 font-medium">🗑 Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="14" class="px-6 py-4 text-center text-sm text-gray-300">No hay antecedentes
                                        registrados.</td>
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