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

<body class="h-full">
    <div class="min-h-full">



        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Relaciones Curso - Asignatura</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">

                <div class="flex items-center justify-between mb-4">
                    <!-- FILTRO -->
                    <form method="get" action="index.php" class="flex items-center gap-2">
                        <input type="hidden" name="action" value="curso_asignaturas">

                        <label for="curso_id" class="text-gray-300 font-semibold">Filtrar por curso:</label>
                        <select name="curso_id" id="curso_id"
                            class="rounded-md bg-gray-800 text-gray-200 border border-gray-600 px-3 py-2"
                            onchange="this.form.submit()">
                            <option value="">-- Todos --</option>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?= $curso['id'] ?>" <?= (isset($_GET['curso_id']) && $_GET['curso_id'] == $curso['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($curso['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>

                    <!-- BOTÓN CREAR -->
                    <div>
                        <a href="index.php?action=curso_asignaturas_create"
                            class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                            Nueva relación
                        </a>
                    </div>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="w-full border-collapse bg-gray-800 text-left text-sm text-gray-300">
                        <thead class="bg-gray-700 text-gray-200">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Curso</th>
                                <th class="px-6 py-3">Asignatura</th>
                                <th class="px-6 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($cursoAsignaturas)): ?>
                                <?php foreach ($cursoAsignaturas as $ca): ?>
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                                        <td class="px-6 py-3"><?= htmlspecialchars($ca['id']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($ca['curso']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($ca['asignatura']) ?></td>
                                        <td class="px-6 py-3 text-center space-x-2">
                                            <a href="index.php?action=curso_asignaturas_edit&id=<?= $ca['id'] ?>"
                                                class="text-indigo-400 hover:text-indigo-300 font-medium">
                                                ✏️ Editar
                                            </a>
                                            <a href="index.php?action=curso_asignaturas_delete&id=<?= $ca['id'] ?>"
                                                onclick="return confirm('¿Estás seguro de eliminar esta relación?');"
                                                class="text-red-400 hover:text-indigo-300 font-medium">
                                                🗑️ Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-400">
                                        No hay relaciones registradas.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- VOLVER -->
                <div class="mt-6 flex justify-center">
                    <a href="index.php?action=dashboard"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        ⬅️ Volver al Dashboard
                    </a>
                </div>

            </div>
        </main>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>