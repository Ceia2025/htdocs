<?php
$auth = new AuthController();
$auth->checkAuth();
$user   = $_SESSION['user'];
$nombre = $user['nombre'];
$rol    = $user['rol'];
include __DIR__ . "/../../layout/header.php";
include __DIR__ . "/../../layout/navbar.php";

$anioId  = $_GET['anio_id']  ?? ($anios[0]['id'] ?? '');
$cursoId = $_GET['curso_id'] ?? '';
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <header class="bg-gray-800 border-b border-white/10">
            <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-cyan-400 mb-0.5">Sistema SAAT</p>
                    <h1 class="text-2xl font-bold text-white">Reporte de Alumnos PIE</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Programa de Integración Escolar, por curso</p>
                </div>
                <a href="index.php?action=dashboard"
                    class="flex items-center gap-2 text-sm text-gray-400 hover:text-white border border-gray-600
                           hover:border-gray-400 px-4 py-2 rounded-lg transition">
                    ⬅ Dashboard
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6">

            <form method="GET" action="index.php" class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-6">
                <input type="hidden" name="action" value="reportes_pie">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                            Año académico
                        </label>
                        <select name="anio_id" onchange="this.form.submit()"
                            class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2
                                   focus:outline-none focus:ring-2 focus:ring-cyan-500 cursor-pointer">
                            <?php foreach ($anios as $a): ?>
                                <option value="<?= $a['id'] ?>" <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                                    <?= $a['anio'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                            Curso <span class="normal-case font-normal text-gray-600">(opcional)</span>
                        </label>
                        <select name="curso_id" onchange="this.form.submit()"
                            class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2
                                   focus:outline-none focus:ring-2 focus:ring-cyan-500 cursor-pointer">
                            <option value="">— Todos los cursos —</option>
                            <?php foreach ($cursos as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $c['id'] == $cursoId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <a href="index.php?action=reportes_pie"
                    class="text-xs text-gray-500 hover:text-gray-300 underline transition">Limpiar</a>
            </form>

            <?php if ($anioId): ?>
                <?php
                $queryBase = http_build_query(array_filter([
                    'anio_id'  => $anioId,
                    'curso_id' => $cursoId ?: null,
                ]));
                ?>
                <div class="flex flex-wrap gap-3 mb-6">
                    <a href="index.php?action=reportes_pie_pdf&<?= $queryBase ?>"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-cyan-700 hover:bg-cyan-600
                               text-white font-semibold text-sm rounded-xl shadow transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Descargar PDF
                    </a>
                </div>
            <?php endif ?>

            <?php if ($anioId): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 mb-6 flex flex-wrap items-center gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Matriculados</p>
                        <p class="text-2xl font-bold text-white"><?= $resumenGlobal['total_matriculados'] ?></p>
                    </div>
                    <div class="w-px h-10 bg-gray-700"></div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Alumnos PIE</p>
                        <p class="text-2xl font-bold text-cyan-400"><?= $resumenGlobal['total_pie'] ?></p>
                    </div>
                    <div class="w-px h-10 bg-gray-700"></div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">% PIE</p>
                        <p class="text-2xl font-bold text-cyan-400"><?= $resumenGlobal['porcentaje'] ?>%</p>
                    </div>
                </div>
            <?php endif ?>

            <?php if (!empty($resumenCategorias)): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden mb-6">
                    <div class="px-5 py-3.5 border-b border-gray-700">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400">
                            Resumen por categoría PIE <span class="text-gray-600 font-normal">(informativo, todos los estados)</span>
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-900/50">
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Categoría</th>
                                    <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Total alumnos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resumenCategorias as $rc): ?>
                                    <tr class="border-t border-gray-700/60 hover:bg-gray-700/20 transition">
                                        <td class="px-4 py-2.5 text-white font-semibold"><?= htmlspecialchars($rc['categoria']) ?></td>
                                        <td class="px-4 py-2.5 text-center text-cyan-400 font-bold"><?= $rc['total'] ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if (!empty($resumenCursos)): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden mb-6">
                    <div class="px-5 py-3.5 border-b border-gray-700">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400">
                            Resumen por curso
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-900/50">
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Curso</th>
                                    <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Matriculados</th>
                                    <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">PIE</th>
                                    <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">% PIE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resumenCursos as $rc): ?>
                                    <tr class="border-t border-gray-700/60 hover:bg-gray-700/20 transition">
                                        <td class="px-4 py-2.5 text-white font-semibold"><?= htmlspecialchars($rc['curso']) ?></td>
                                        <td class="px-4 py-2.5 text-center text-gray-300"><?= $rc['total_matriculados'] ?></td>
                                        <td class="px-4 py-2.5 text-center text-cyan-400 font-semibold"><?= $rc['total_pie'] ?></td>
                                        <td class="px-4 py-2.5 text-center">
                                            <span class="font-bold text-cyan-400"><?= $rc['porcentaje'] ?>%</span>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if (!empty($reporte)): ?>
                <?php foreach ($reporte as $cursoNombreLoop => $alumnos): ?>
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden mb-5">
                        <div class="px-5 py-3.5 border-b border-gray-700">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400">
                                🎓 <?= htmlspecialchars($cursoNombreLoop) ?>
                                <span class="ml-2 text-xs font-normal text-gray-600">
                                    (<?= count($alumnos) ?> alumno<?= count($alumnos) !== 1 ? 's' : '' ?> PIE)
                                </span>
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-900/50">
                                        <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Alumno</th>
                                        <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">RUN</th>
                                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Sexo</th>
                                        <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Problema de aprendizaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alumnos as $al): ?>
                                        <tr class="border-t border-gray-700/60 hover:bg-gray-700/20 transition">
                                            <td class="px-4 py-2.5 text-white font-semibold">
                                                <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                                            </td>
                                            <td class="px-3 py-2.5 text-gray-400 font-mono text-xs">
                                                <?= htmlspecialchars($al['run']) ?>
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-gray-300 text-xs">
                                                <?= $al['sexo'] === 'F' ? '♀ F' : ($al['sexo'] === 'M' ? '♂ M' : '—') ?>
                                            </td>
                                            <td class="px-3 py-2.5 text-gray-300 text-xs">
                                                <?= !empty($al['prob_apren']) ? htmlspecialchars($al['prob_apren']) : '—' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach ?>

            <?php elseif ($anioId): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-16 text-center">
                    <span class="text-5xl block mb-4">🔎</span>
                    <p class="text-gray-400 font-medium">No se encontraron alumnos PIE con los filtros seleccionados</p>
                </div>
            <?php else: ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-16 text-center">
                    <span class="text-5xl block mb-4">📊</span>
                    <p class="text-gray-400 font-medium">Selecciona un año académico para ver el reporte</p>
                </div>
            <?php endif ?>

        </main>
    </div>
</body>

<?php include __DIR__ . "/../../layout/footer.php"; ?>