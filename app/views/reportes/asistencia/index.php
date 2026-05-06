<?php
require_once __DIR__ . '/../../../controllers/reportes/ReporteController.php';
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../../layout/header.php";
include __DIR__ . "/../../layout/navbar.php";

$anioId = $_GET['anio_id'] ?? ($anios[0]['id'] ?? '');
$cursoId = $_GET['curso_id'] ?? '';
$mesKey = $_GET['mes'] ?? '';

$esGeneral = !$cursoId;
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="bg-gray-800 border-b border-white/10">
            <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-400 mb-0.5">Sistema SAAT</p>
                    <h1 class="text-2xl font-bold text-white">Centro de Reportes</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Exporta datos de asistencia en PDF o CSV(Excel)</p>
                </div>
                <a href="index.php?action=dashboard" class="flex items-center gap-2 text-sm text-gray-400 hover:text-white border border-gray-600
                       hover:border-gray-400 px-4 py-2 rounded-lg transition">
                    ⬅ Dashboard
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6">

            <!-- FILTROS -->
            <form method="GET" action="index.php" class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-6">
                <input type="hidden" name="action" value="reportes_asistencia">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">

                    <!-- Año -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Año
                            académico</label>
                        <select name="anio_id" onchange="this.form.submit()" class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2
                               focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                            <?php foreach ($anios as $a): ?>
                                <option value="<?= $a['id'] ?>" <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                                    <?= $a['anio'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Curso -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                            Curso <span class="normal-case font-normal text-gray-600">(opcional — vacío = todos)</span>
                        </label>
                        <select name="curso_id" onchange="this.form.submit()" class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2
                               focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                            <option value="">— Todos los cursos —</option>
                            <?php foreach ($cursos as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $c['id'] == $cursoId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Mes -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                            Mes <span class="normal-case font-normal text-gray-600">(opcional — vacío =
                                acumulado)</span>
                        </label>
                        <select name="mes" class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2
                               focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                            <option value="">— Acumulado anual —</option>
                            <?php foreach ($meses as $m): ?>
                                <option value="<?= $m['mes_key'] ?>" <?= $m['mes_key'] === $mesKey ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['mes_nombre']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                        🔍 Generar reporte
                    </button>
                    <a href="index.php?action=reportes"
                        class="text-xs text-gray-500 hover:text-gray-300 underline transition">Limpiar</a>
                </div>
            </form>

            <!-- BOTONES DE EXPORTACIÓN -->
            <?php if ($anioId): ?>
                <div class="flex flex-wrap gap-3 mb-6">

                    <?php
                    $queryBase = http_build_query(array_filter([
                        'anio_id' => $anioId,
                        'curso_id' => $cursoId ?: null,
                        'mes' => $mesKey ?: null,
                    ]));
                    ?>

                    <?php if ($cursoId): ?>
                        <!-- Exportar CSV por curso -->
                        <a href="index.php?action=reporte_csv_curso&<?= $queryBase ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700 hover:bg-green-600
                           text-white font-semibold text-sm rounded-xl shadow transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Descargar CSV (este curso)
                        </a>

                        <!-- Exportar PDF por curso -->
                        <a href="index.php?action=reporte_pdf_curso&<?= $queryBase ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-700 hover:bg-red-600
                           text-white font-semibold text-sm rounded-xl shadow transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Descargar PDF (este curso)
                        </a>
                    <?php endif ?>

                    <!-- Exportar CSV general -->
                    <a href="index.php?action=reporte_csv_general&<?= http_build_query(array_filter(['anio_id' => $anioId, 'mes' => $mesKey ?: null])) ?>"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-700 hover:bg-teal-600
                       text-white font-semibold text-sm rounded-xl shadow transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Descargar CSV (todos los cursos)
                    </a>

                    <!-- Exportar PDF general -->
                    <a href="index.php?action=reporte_pdf_general&<?= http_build_query(array_filter(['anio_id' => $anioId, 'mes' => $mesKey ?: null])) ?>"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-700 hover:bg-rose-600
                       text-white font-semibold text-sm rounded-xl shadow transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Descargar PDF (todos los cursos)
                    </a>
                </div>
            <?php endif ?>

            <!-- RESUMEN DE CURSOS -->
            <?php if (!empty($resumenCursos)): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden mb-6">
                    <div class="px-5 py-3.5 border-b border-gray-700">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400">
                            Resumen por curso
                            <?= $mesKey ? '— ' . ($meses[array_search($mesKey, array_column($meses, 'mes_key'))]['mes_nombre'] ?? $mesKey) : '— Acumulado anual' ?>
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-900/50">
                                    <th
                                        class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Curso</th>
                                    <th
                                        class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Alumnos</th>
                                    <th
                                        class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Clases</th>
                                    <th
                                        class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Presentes</th>
                                    <th
                                        class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        % Asistencia</th>
                                    <th
                                        class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Ver detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resumenCursos as $rc):
                                    $pct = $rc['pct'];
                                    $color = $pct === null ? 'text-gray-500'
                                        : ($pct >= 85 ? 'text-green-400' : ($pct >= 75 ? 'text-yellow-400' : 'text-red-400'));
                                    ?>
                                    <tr class="border-t border-gray-700/60 hover:bg-gray-700/20 transition">
                                        <td class="px-4 py-3 text-white font-semibold"><?= htmlspecialchars($rc['curso']) ?>
                                        </td>
                                        <td class="px-3 py-3 text-center text-gray-300"><?= $rc['total_alumnos'] ?></td>
                                        <td class="px-3 py-3 text-center text-gray-300"><?= $rc['total_clases'] ?></td>
                                        <td class="px-3 py-3 text-center text-green-400 font-semibold">
                                            <?= $rc['total_presentes'] ?></td>
                                        <td class="px-3 py-3 text-center">
                                            <span class="font-bold text-base <?= $color ?>">
                                                <?= $pct !== null ? $pct . '%' : '—' ?>
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <a href="index.php?action=reportes&anio_id=<?= $anioId ?>&curso_id=<?= $rc['id'] ?>&mes=<?= urlencode($mesKey) ?>"
                                                class="text-xs text-indigo-400 hover:text-indigo-300 underline transition">
                                                Ver alumnos
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <!-- TABLA DETALLE -->
            <?php if ($cursoId && !empty($datosReporte)): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-gray-700 flex items-center justify-between">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400">
                            Detalle —
                            <?= htmlspecialchars($cursos[array_search($cursoId, array_column($cursos, 'id'))]['nombre'] ?? '') ?>
                            &nbsp;·&nbsp;
                            <?= $mesKey ? ($meses[array_search($mesKey, array_column($meses, 'mes_key'))]['mes_nombre'] ?? $mesKey) : 'Acumulado anual' ?>
                            <span class="ml-2 text-xs font-normal text-gray-600">(<?= count($datosReporte) ?>
                                alumnos)</span>
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-900/50">
                                    <th
                                        class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Alumno</th>
                                    <th
                                        class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        RUN</th>
                                    <th
                                        class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Total clases</th>
                                    <th
                                        class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Presentes</th>
                                    <th
                                        class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        % Acumulado</th>
                                    <?php if ($mesKey): ?>
                                        <th
                                            class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                            Días mes</th>
                                        <th
                                            class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                            % Mes</th>
                                    <?php endif ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($datosReporte as $al):
                                    $colorAcum = $al['pct_acumulado'] === null ? 'text-gray-500'
                                        : ($al['pct_acumulado'] >= 85 ? 'text-green-400'
                                            : ($al['pct_acumulado'] >= 75 ? 'text-yellow-400' : 'text-red-400'));
                                    $colorMes = $al['pct_mes'] === null ? 'text-gray-500'
                                        : ($al['pct_mes'] >= 85 ? 'text-green-400'
                                            : ($al['pct_mes'] >= 75 ? 'text-yellow-400' : 'text-red-400'));
                                    ?>
                                    <tr class="border-t border-gray-700/60 hover:bg-gray-700/20 transition">
                                        <td class="px-4 py-2.5 text-white font-semibold">
                                            <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                                        </td>
                                        <td class="px-3 py-2.5 text-gray-400 font-mono text-xs">
                                            <?= htmlspecialchars($al['run']) ?></td>
                                        <td class="px-3 py-2.5 text-center text-gray-300"><?= $al['total_clases'] ?></td>
                                        <td class="px-3 py-2.5 text-center text-green-400"><?= $al['presentes_acum'] ?></td>
                                        <td class="px-3 py-2.5 text-center">
                                            <span class="font-bold <?= $colorAcum ?>">
                                                <?= $al['pct_acumulado'] !== null ? $al['pct_acumulado'] . '%' : '—' ?>
                                            </span>
                                        </td>
                                        <?php if ($mesKey): ?>
                                            <td class="px-3 py-2.5 text-center text-gray-300"><?= $al['total_mes'] ?? '—' ?></td>
                                            <td class="px-3 py-2.5 text-center">
                                                <span class="font-bold <?= $colorMes ?>">
                                                    <?= $al['pct_mes'] !== null ? $al['pct_mes'] . '%' : '—' ?>
                                                </span>
                                            </td>
                                        <?php endif ?>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php elseif (!$cursoId && empty($resumenCursos)): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-16 text-center">
                    <span class="text-5xl block mb-4">📊</span>
                    <p class="text-gray-400 font-medium">Selecciona un año académico para ver los reportes disponibles</p>
                </div>
            <?php endif ?>

        </main>
    </div>
</body>

<?php include __DIR__ . "/../../layout/footer.php"; ?>