<?php
require_once __DIR__ . "/../../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../../layout/header.php";
include __DIR__ . "/../../layout/navbar.php";

// Lee un ícono de app/public/svg/{key}.svg y lo devuelve listo para inyectar inline,
// con la clase de tamaño Tailwind ya insertada en el <svg>.
function saat_icon(string $key, string $classes = 'w-4 h-4'): string
{
    $path = __DIR__ . "/../../../public/svg/{$key}.svg";
    if (!file_exists($path)) {
        return '';
    }
    $svg = file_get_contents($path);
    return preg_replace('/<svg /', '<svg class="' . htmlspecialchars($classes) . '" ', $svg, 1);
}

$anioId = $_GET['anio_id'] ?? ($anios[0]['id'] ?? '');
$cursoId = $_GET['curso_id'] ?? '';
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Sistema SAAT</p>
            <h1 class="text-2xl font-bold text-strong font-display">Reporte de Alumnos PIE</h1>
            <p class="text-xs text-muted mt-0.5">Programa de Integración Escolar, por curso</p>
        </div>
        <a href="index.php?action=reportes"
            class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            <?= saat_icon('arrow-left', 'w-4 h-4') ?>
            Volver a reportes
        </a>
    </div>
</header>

<main class="report-cyan mx-auto max-w-6xl px-4 py-6 sm:px-6">

    <form method="GET" action="index.php" class="panel rounded-xl p-4 mb-6">
        <input type="hidden" name="action" value="reportes_pie">

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

        <a href="index.php?action=reportes_pie"
            class="text-xs text-faint hover:text-strong underline transition">Limpiar</a>
    </form>

    <?php if ($anioId): ?>
        <?php
        $queryBase = http_build_query(array_filter([
            'anio_id' => $anioId,
            'curso_id' => $cursoId ?: null,
        ]));
        ?>
        <div class="flex flex-wrap gap-3 mb-6">
            <a href="index.php?action=reportes_pie_pdf&<?= $queryBase ?>&preview=1" target="_blank"
                class="btn-secondary inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition">
                <?= saat_icon('eye', 'w-4 h-4') ?>
                Ver PDF
            </a>
            <a href="index.php?action=reportes_pie_pdf&<?= $queryBase ?>"
                class="report-btn inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition">
                <?= saat_icon('download', 'w-4 h-4') ?>
                Descargar PDF
            </a>

            <a href="index.php?action=reportes_pie_pdf_notas&<?= $queryBase ?>&preview=1" target="_blank"
                class="btn-secondary inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition">
                <?= saat_icon('eye', 'w-4 h-4') ?>
                Ver PDF con notas
            </a>
            <a href="index.php?action=reportes_pie_pdf_notas&<?= $queryBase ?>"
                class="report-btn inline-flex items-center gap-2 px-5 py-2.5 font-semibold text-sm rounded-xl shadow transition"
                style="background: #155e75;">
                <?= saat_icon('doc', 'w-4 h-4') ?>
                PDF con notas
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
                <p class="text-xs text-muted uppercase tracking-wider mb-1">Alumnos PIE</p>
                <p class="text-2xl font-bold text-r-accent"><?= $resumenGlobal['total_pie'] ?></p>
            </div>
            <div class="w-px h-10" style="background: var(--border-soft);"></div>
            <div>
                <p class="text-xs text-muted uppercase tracking-wider mb-1">% PIE</p>
                <p class="text-2xl font-bold text-r-accent"><?= $resumenGlobal['porcentaje'] ?>%</p>
            </div>
        </div>
    <?php endif ?>

    <?php if (!empty($resumenCategorias)): ?>
        <div class="panel rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3.5 border-b divider-soft">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">
                    Resumen por categoría PIE <span class="text-faint font-normal">(informativo, todos los estados)</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                                Categoría</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                Total alumnos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resumenCategorias as $rc): ?>
                            <tr>
                                <td class="px-4 py-2.5 text-strong font-semibold"><?= htmlspecialchars($rc['categoria']) ?></td>
                                <td class="px-4 py-2.5 text-center text-r-accent font-bold"><?= $rc['total'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
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
                            <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                                Curso</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                Matriculados</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                PIE</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">%
                                PIE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resumenCursos as $rc): ?>
                            <tr>
                                <td class="px-4 py-2.5 text-strong font-semibold"><?= htmlspecialchars($rc['curso']) ?></td>
                                <td class="px-4 py-2.5 text-center text-soft"><?= $rc['total_matriculados'] ?></td>
                                <td class="px-4 py-2.5 text-center text-r-accent font-semibold"><?= $rc['total_pie'] ?></td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="font-bold text-r-accent"><?= $rc['porcentaje'] ?>%</span>
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
                <div class="px-5 py-3.5 border-b divider-soft flex items-center gap-2">
                    <?= saat_icon('cap', 'w-4 h-4 text-muted') ?>
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">
                        <?= htmlspecialchars($cursoNombreLoop) ?>
                        <span class="ml-2 text-xs font-normal text-faint">
                            (<?= count($alumnos) ?> alumno<?= count($alumnos) !== 1 ? 's' : '' ?> PIE)
                        </span>
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table w-full text-sm">
                        <thead>
                            <tr>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                                    Alumno</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">RUN
                                </th>
                                <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                                    Sexo</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                                    Problema de aprendizaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $al): ?>
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
                                    <td class="px-3 py-2.5 text-soft text-xs">
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
        <div class="panel rounded-xl px-6 py-16 text-center">
            <span class="text-muted block mb-4"><?= saat_icon('search', 'w-12 h-12 mx-auto') ?></span>
            <p class="text-muted font-medium">No se encontraron alumnos PIE con los filtros seleccionados</p>
        </div>
    <?php else: ?>
        <div class="panel rounded-xl px-6 py-16 text-center">
            <span class="text-muted block mb-4"><?= saat_icon('chart', 'w-12 h-12 mx-auto') ?></span>
            <p class="text-muted font-medium">Selecciona un año académico para ver el reporte</p>
        </div>
    <?php endif ?>

</main>

<?php include __DIR__ . "/../../layout/footer.php"; ?>