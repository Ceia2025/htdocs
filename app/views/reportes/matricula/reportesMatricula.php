<?php
require_once __DIR__ . "/../../../controllers/AuthController.php";
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

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Sistema SAAT</p>
            <h1 class="text-2xl font-bold text-strong font-display">Reporte de Matrícula</h1>
            <p class="text-xs text-muted mt-0.5">Alumnos con matrícula activa por curso</p>
        </div>
        <a href="index.php?action=dashboard"
            class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            ⬅ Dashboard
        </a>
    </div>
</header>

<main class="report-purple mx-auto max-w-6xl px-4 py-6 sm:px-6">

    <form method="GET" action="index.php" class="panel rounded-xl p-4 mb-6">
        <input type="hidden" name="action" value="reportes_matricula">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
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
        </div>

        <a href="index.php?action=reportes_matricula"
            class="text-xs text-faint hover:text-strong underline transition">Limpiar</a>
    </form>

    <?php if ($anioId): ?>
        <?php
        $queryBase = http_build_query(array_filter([
            'anio_id'  => $anioId,
            'curso_id' => $cursoId ?: null,
        ]));
        ?>
        <div class="flex flex-wrap gap-3 mb-6">
            <a href="index.php?action=reportes_matricula_pdf&<?= $queryBase ?>"
                class="report-btn inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Descargar PDF
            </a>
        </div>
    <?php endif ?>

    <?php if ($anioId): ?>
        <div class="panel rounded-xl p-5 mb-6 flex flex-wrap items-center gap-6">
            <div>
                <p class="text-xs text-muted uppercase tracking-wider mb-1">Matriculados</p>
                <p class="text-2xl font-bold text-strong"><?= $resumenGlobal['total_matriculados'] ?></p>
            </div>
            <div class="w-px h-10" style="background: var(--border-soft);"></div>
            <div>
                <p class="text-xs text-muted uppercase tracking-wider mb-1">Matrícula activa</p>
                <p class="text-2xl font-bold text-r-accent"><?= $resumenGlobal['total_activos'] ?></p>
            </div>
            <div class="w-px h-10" style="background: var(--border-soft);"></div>
            <div>
                <p class="text-xs text-muted uppercase tracking-wider mb-1">% activos</p>
                <p class="text-2xl font-bold text-r-accent"><?= $resumenGlobal['porcentaje'] ?>%</p>
            </div>
        </div>
    <?php endif ?>

    <?php if (!empty($resumenCursos)): ?>
        <div class="panel rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3.5 border-b divider-soft">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">
                    Resumen por curso
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">Curso</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">Matriculados</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">Activos</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">% Activo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resumenCursos as $rc): ?>
                            <tr>
                                <td class="px-4 py-2.5 text-strong font-semibold"><?= htmlspecialchars($rc['curso']) ?></td>
                                <td class="px-4 py-2.5 text-center text-soft"><?= $rc['total_matriculados'] ?></td>
                                <td class="px-4 py-2.5 text-center text-r-accent font-semibold"><?= $rc['total_activos'] ?></td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="font-bold <?= $rc['porcentaje'] >= 85 ? 'text-success' : 'text-warn' ?>">
                                        <?= $rc['porcentaje'] ?>%
                                    </span>
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
            <div class="panel rounded-xl overflow-hidden mb-5">
                <div class="px-5 py-3.5 border-b divider-soft">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">
                        🎓 <?= htmlspecialchars($cursoNombreLoop) ?>
                        <span class="ml-2 text-xs font-normal text-faint">
                            (<?= count($alumnos) ?> activo<?= count($alumnos) !== 1 ? 's' : '' ?>)
                        </span>
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table w-full text-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted w-10">#</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">Alumno</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">RUN</th>
                                <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">Fecha matrícula</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $al): ?>
                                <tr>
                                    <td class="px-3 py-2.5 text-center text-xs font-bold text-r-accent">
                                        <?= $al['numero_lista'] ?? '—' ?>
                                    </td>
                                    <td class="px-4 py-2.5 text-strong font-semibold">
                                        <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                                    </td>
                                    <td class="px-3 py-2.5 text-muted font-mono text-xs">
                                        <?= htmlspecialchars($al['run']) ?>
                                    </td>
                                    <td class="px-3 py-2.5 text-center text-muted text-xs">
                                        <?= $al['fecha_matricula'] ? date('d/m/Y', strtotime($al['fecha_matricula'])) : '—' ?>
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
            <span class="text-5xl block mb-4">📭</span>
            <p class="text-muted font-medium">No hay alumnos con matrícula activa con los filtros seleccionados</p>
        </div>
    <?php else: ?>
        <div class="panel rounded-xl px-6 py-16 text-center">
            <span class="text-5xl block mb-4">📊</span>
            <p class="text-muted font-medium">Selecciona un año académico para ver el reporte</p>
        </div>
    <?php endif ?>

</main>

<?php include __DIR__ . "/../../layout/footer.php"; ?>