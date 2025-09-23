<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Relaciones Curso - Asignatura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<html class="h-full bg-gray-900">

<body class="h-full">
    <div class="min-h-full">

        <!-- NAVBAR -->
        <nav class="bg-gray-800/50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <img src="../img/logo.jpg" alt="Logo" class="size-12 rounded-full" />
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="index.php?action=dashboard"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Dashboard
                                </a>
                                <a href="index.php?action=curso_asignaturas_index"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-white bg-gray-700">
                                    Relaciones Curso - Asignatura
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

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

</html>