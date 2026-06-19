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

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Sistema SAAT</p>
            <h1 class="text-2xl font-bold text-strong font-display">Centro de Reportes</h1>
            <p class="text-xs text-muted mt-0.5">Exporta datos de asistencia en PDF o CSV(Excel)</p>
        </div>
        <a href="index.php?action=reportes"
            class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver a reportes
        </a>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-6 sm:px-6">

    <!-- FILTROS -->
    <form method="GET" action="index.php" class="panel rounded-xl p-4 mb-6">
        <input type="hidden" name="action" value="reportes_asistencia">

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">

            <!-- Año -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Año
                    académico</label>
                <select name="anio_id" onchange="this.form.submit()"
                    class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Curso -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">
                    Curso <span class="normal-case font-normal text-faint">(opcional — vacío = todos)</span>
                </label>
                <select name="curso_id" onchange="this.form.submit()"
                    class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
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
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">
                    Mes <span class="normal-case font-normal text-faint">(opcional — vacío =
                        acumulado)</span>
                </label>
                <select name="mes" class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
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
            <button type="submit" class="btn-primary text-sm font-semibold px-4 py-2 rounded-lg transition">
                🔍 Generar reporte
            </button>
            <a href="index.php?action=reportes"
                class="text-xs text-faint hover:text-strong underline transition">Limpiar</a>
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
                <a href="index.php?action=reporte_csv_curso&<?= $queryBase ?>"
                    class="btn-success inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Descargar CSV (este curso)
                </a>

                <!-- Exportar PDF por curso -->
                <a href="index.php?action=reporte_pdf_curso&<?= $queryBase ?>"
                    class="btn-danger inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Descargar PDF (este curso)
                </a>
            <?php endif ?>

            <!-- Exportar CSV general -->
            <a href="index.php?action=reporte_csv_general&<?= http_build_query(array_filter(['anio_id' => $anioId, 'mes' => $mesKey ?: null])) ?>"
                class="btn-success inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Descargar CSV (todos los cursos)
            </a>

            <!-- Exportar PDF general -->
            <a href="index.php?action=reporte_pdf_general&<?= http_build_query(array_filter(['anio_id' => $anioId, 'mes' => $mesKey ?: null])) ?>"
                class="btn-danger inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition">
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
        <div class="panel rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3.5 border-b divider-soft">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">
                    Resumen por curso
                    <?= $mesKey ? '— ' . ($meses[array_search($mesKey, array_column($meses, 'mes_key'))]['mes_nombre'] ?? $mesKey) : '— Acumulado anual' ?>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                                Curso</th>
                            <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                Alumnos</th>
                            <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                Clases</th>
                            <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                Presentes</th>
                            <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                % Asistencia</th>
                            <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                Ver detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resumenCursos as $rc):
                            $pct = $rc['pct'];
                            $color = $pct === null ? 'text-faint'
                                : ($pct >= 85 ? 'text-success' : ($pct >= 75 ? 'text-warn' : 'text-danger'));
                            ?>
                            <tr>
                                <td class="px-4 py-3 text-strong font-semibold"><?= htmlspecialchars($rc['curso']) ?>
                                </td>
                                <td class="px-3 py-3 text-center text-soft"><?= $rc['total_alumnos'] ?></td>
                                <td class="px-3 py-3 text-center text-soft"><?= $rc['total_clases'] ?></td>
                                <td class="px-3 py-3 text-center text-success font-semibold">
                                    <?= $rc['total_presentes'] ?>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="font-bold text-base <?= $color ?>">
                                        <?= $pct !== null ? $pct . '%' : '—' ?>
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <a href="index.php?action=reportes&anio_id=<?= $anioId ?>&curso_id=<?= $rc['id'] ?>&mes=<?= urlencode($mesKey) ?>"
                                        class="link-action text-xs underline transition">
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
        <div class="panel rounded-xl overflow-hidden">
            <div class="px-5 py-3.5 border-b divider-soft flex items-center justify-between">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">
                    Detalle —
                    <?= htmlspecialchars($cursos[array_search($cursoId, array_column($cursos, 'id'))]['nombre'] ?? '') ?>
                    &nbsp;·&nbsp;
                    <?= $mesKey ? ($meses[array_search($mesKey, array_column($meses, 'mes_key'))]['mes_nombre'] ?? $mesKey) : 'Acumulado anual' ?>
                    <span class="ml-2 text-xs font-normal text-faint">(<?= count($datosReporte) ?>
                        alumnos)</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                                Alumno</th>
                            <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                                RUN</th>
                            <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                Total clases</th>
                            <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                Presentes</th>
                            <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                % Acumulado</th>
                            <?php if ($mesKey): ?>
                                <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                    Días mes</th>
                                <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                    % Mes</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datosReporte as $al):
                            $colorAcum = $al['pct_acumulado'] === null ? 'text-faint'
                                : ($al['pct_acumulado'] >= 85 ? 'text-success'
                                    : ($al['pct_acumulado'] >= 75 ? 'text-warn' : 'text-danger'));
                            $colorMes = $al['pct_mes'] === null ? 'text-faint'
                                : ($al['pct_mes'] >= 85 ? 'text-success'
                                    : ($al['pct_mes'] >= 75 ? 'text-warn' : 'text-danger'));
                            ?>
                            <tr>
                                <td class="px-4 py-2.5 text-strong font-semibold">
                                    <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                                </td>
                                <td class="px-3 py-2.5 text-muted font-mono text-xs">
                                    <?= htmlspecialchars($al['run']) ?>
                                </td>
                                <td class="px-3 py-2.5 text-center text-soft"><?= $al['total_clases'] ?></td>
                                <td class="px-3 py-2.5 text-center text-success"><?= $al['presentes_acum'] ?></td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="font-bold <?= $colorAcum ?>">
                                        <?= $al['pct_acumulado'] !== null ? $al['pct_acumulado'] . '%' : '—' ?>
                                    </span>
                                </td>
                                <?php if ($mesKey): ?>
                                    <td class="px-3 py-2.5 text-center text-soft"><?= $al['total_mes'] ?? '—' ?></td>
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
        <div class="panel rounded-xl px-6 py-16 text-center">
            <span class="text-5xl block mb-4">📊</span>
            <p class="text-muted font-medium">Selecciona un año académico para ver los reportes disponibles</p>
        </div>
    <?php endif ?>

</main>

<?php include __DIR__ . "/../../layout/footer.php"; ?>