<?php
require_once __DIR__ . '/../../../controllers/reportes/etnia/ReporteEtniaController.php';
$auth = new AuthController();
$auth->checkAuth();
$user    = $_SESSION['user'];
$nombre  = $user['nombre'];
$rol     = $user['rol'];
include __DIR__ . "/../../layout/header.php";
include __DIR__ . "/../../layout/navbar.php";

$anioId      = $_GET['anio_id']  ?? ($anios[0]['id'] ?? '');
$cursoId     = $_GET['curso_id'] ?? '';
$etniaFiltro = $_GET['etnia']    ?? '';
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Sistema SAAT</p>
            <h1 class="text-2xl font-bold text-strong font-display">Reporte de Pueblos Originarios</h1>
            <p class="text-xs text-muted mt-0.5">Visualiza y exporta la distribución étnica por curso</p>
        </div>
        <a href="index.php?action=dashboard"
            class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            ⬅ Dashboard
        </a>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-6 sm:px-6">

    <!-- FILTROS -->
    <form method="GET" action="index.php" class="panel rounded-xl p-4 mb-6">
        <input type="hidden" name="action" value="reportes_etnia">

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">

            <!-- Año -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">
                    Año académico
                </label>
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
                    Curso <span class="normal-case font-normal text-faint">(opcional)</span>
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

            <!-- Etnia -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">
                    Pueblo Originario <span class="normal-case font-normal text-faint">(opcional)</span>
                </label>
                <select name="etnia"
                    class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                    <option value="">— Todos —</option>
                    <?php foreach ($etnias as $e): ?>
                        <option value="<?= htmlspecialchars($e) ?>"
                            <?= $e === $etniaFiltro ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                class="btn-primary text-sm font-semibold px-4 py-2 rounded-lg transition">
                🔍 Generar reporte
            </button>
            <a href="index.php?action=reporte_etnia"
                class="text-xs text-faint hover:text-strong underline transition">Limpiar</a>
        </div>
    </form>

    <!-- BOTONES DE EXPORTACIÓN -->
    <?php if ($anioId): ?>
        <?php
        $queryBase = http_build_query(array_filter([
            'anio_id'  => $anioId,
            'curso_id' => $cursoId  ?: null,
            'etnia'    => $etniaFiltro ?: null,
        ]));
        ?>
        <div class="flex flex-wrap gap-3 mb-6">
            <a href="index.php?action=reporte_etnia_pdf&<?= $queryBase ?>"
                class="btn-danger inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Descargar PDF
            </a>
            <a href="index.php?action=reporte_etnia_csv&<?= $queryBase ?>"
                class="btn-success inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Descargar CSV (Excel)
            </a>
        </div>
    <?php endif ?>

    <!-- RESUMEN GLOBAL POR ETNIA -->
    <?php if (!empty($resumenGlobal)): ?>
        <div class="panel rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3.5 border-b divider-soft">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">
                    Resumen global — distribución por pueblo originario
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                                Pueblo Originario / Etnia</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                Total alumnos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalGeneral = array_sum(array_column($resumenGlobal, 'total'));
                        foreach ($resumenGlobal as $rg):
                            $pct = $totalGeneral > 0 ? round($rg['total'] / $totalGeneral * 100, 1) : 0;
                            $esPueblo = $rg['cod_etnia'] !== 'No pertenece a ningún Pueblo Originario'
                                        && $rg['cod_etnia'] !== 'No Registra';
                        ?>
                            <tr>
                                <td class="px-4 py-2.5">
                                    <?php if ($esPueblo): ?>
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full inline-block" style="background: var(--accent-title);"></span>
                                            <span class="text-strong font-semibold"><?= htmlspecialchars($rg['cod_etnia']) ?></span>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted"><?= htmlspecialchars($rg['cod_etnia']) ?></span>
                                    <?php endif ?>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="font-bold <?= $esPueblo ? 'text-accent' : 'text-muted' ?>">
                                        <?= $rg['total'] ?>
                                    </span>
                                    <span class="text-xs text-faint ml-1">(<?= $pct ?>%)</span>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <tr class="border-t-2" style="border-color: var(--border-soft);">
                            <td class="px-4 py-2.5 text-strong font-bold text-xs uppercase tracking-wider">Total</td>
                            <td class="px-4 py-2.5 text-center text-strong font-bold"><?= $totalGeneral ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif ?>

    <!-- DETALLE POR CURSO -->
    <?php if (!empty($reporte)): ?>
        <?php foreach ($reporte as $cursoNombreLoop => $alumnos): ?>
            <div class="panel rounded-xl overflow-hidden mb-5">
                <div class="px-5 py-3.5 border-b divider-soft flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">
                        🎓 <?= htmlspecialchars($cursoNombreLoop) ?>
                        <span class="ml-2 text-xs font-normal text-faint">
                            (<?= count($alumnos) ?> alumno<?= count($alumnos) !== 1 ? 's' : '' ?>)
                        </span>
                    </h2>
                    <?php
                    // Contar cuántos son de pueblo originario en este curso
                    $pueblos = array_filter($alumnos, fn($a) =>
                        $a['cod_etnia'] !== 'No pertenece a ningún Pueblo Originario'
                        && $a['cod_etnia'] !== 'No Registra'
                    );
                    ?>
                    <?php if (count($pueblos) > 0): ?>
                        <span class="text-xs text-accent font-semibold">
                            ● <?= count($pueblos) ?> de pueblo originario
                        </span>
                    <?php endif ?>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table w-full text-sm">
                        <thead>
                            <tr>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">Alumno</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">RUN</th>
                                <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">Sexo</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">Pueblo Originario / Etnia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $al):
                                $esPueblo = $al['cod_etnia'] !== 'No pertenece a ningún Pueblo Originario'
                                            && $al['cod_etnia'] !== 'No Registra';
                            ?>
                                <tr>
                                    <td class="px-4 py-2.5 text-strong font-semibold">
                                        <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                                    </td>
                                    <td class="px-3 py-2.5 text-muted font-mono text-xs">
                                        <?= htmlspecialchars($al['run']) ?>
                                    </td>
                                    <td class="px-3 py-2.5 text-center text-soft text-xs">
                                        <?= $al['sexo'] === 'F' ? '♀ F' : ($al['sexo'] === 'M' ? '♂ M' : '—') ?>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <?php if ($esPueblo): ?>
                                            <span class="inline-flex items-center gap-1.5 text-accent font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background: var(--accent-title);"></span>
                                                <?= htmlspecialchars($al['cod_etnia']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-faint text-xs"><?= htmlspecialchars($al['cod_etnia']) ?></span>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach ?>

    <?php elseif ($anioId): ?>
        <div class="panel rounded-xl px-6 py-16 text-center">
            <span class="text-5xl block mb-4">🔎</span>
            <p class="text-muted font-medium">No se encontraron alumnos con los filtros seleccionados</p>
        </div>
    <?php else: ?>
        <div class="panel rounded-xl px-6 py-16 text-center">
            <span class="text-5xl block mb-4">📊</span>
            <p class="text-muted font-medium">Selecciona un año académico para ver el reporte</p>
        </div>
    <?php endif ?>

</main>

<?php include __DIR__ . "/../../layout/footer.php"; ?>