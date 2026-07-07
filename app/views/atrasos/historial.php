<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$anioActivo     = $_GET['anio']     ?? ($aniosDisponibles[0]['anio'] ?? date('Y'));
$cursoActivo    = $_GET['curso']    ?? '';
$alumnoActivo   = $_GET['alumno']   ?? '';
$semestreActivo = $_GET['semestre'] ?? '';

$totalHistorial = count($historial);
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Convivencia Escolar</p>
            <h1 class="text-2xl font-bold text-strong font-display">Historial de Atrasos con Acción Tomada</h1>
            <p class="text-xs text-muted mt-0.5">
                Registros movidos al historial al momento de aplicar una medida disciplinaria
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="index.php?action=atrasos_lista_curso"
               class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
                ← Volver a atrasos
            </a>
            <a href="index.php?action=atrasos_historial_pdf&anio=<?= $anioActivo ?>&curso=<?= urlencode($cursoActivo) ?>&alumno=<?= urlencode($alumnoActivo) ?>&semestre=<?= $semestreActivo ?>"
                class="btn-danger flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Exportar PDF
            </a>
        </div>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-6 sm:px-6">

    <!-- FILTROS -->
    <form method="GET" action="index.php" class="panel rounded-xl p-4 mb-6">
        <input type="hidden" name="action" value="atrasos_historial">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">

            <!-- Año -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Año</label>
                <select name="anio" onchange="this.form.submit()"
                    class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                    <option value="">— Todos —</option>
                    <?php foreach ($aniosDisponibles as $a): ?>
                        <option value="<?= $a['anio'] ?>" <?= $a['anio'] == $anioActivo ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Curso -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Curso</label>
                <select name="curso" class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                    <option value="">— Todos los cursos —</option>
                    <?php foreach ($cursosDisponibles as $c): ?>
                        <option value="<?= htmlspecialchars($c['curso_nombre']) ?>"
                            <?= $cursoActivo === $c['curso_nombre'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['curso_nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Alumno -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Alumno</label>
                <input type="text" name="alumno" value="<?= htmlspecialchars($alumnoActivo) ?>"
                    placeholder="Filtrar por nombre..."
                    class="input-field w-full text-sm rounded-lg px-3 py-2">
            </div>

            <!-- Semestre -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Semestre</label>
                <select name="semestre" class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                    <option value="">— Todos —</option>
                    <option value="1" <?= $semestreActivo == '1' ? 'selected' : '' ?>>1° Semestre</option>
                    <option value="2" <?= $semestreActivo == '2' ? 'selected' : '' ?>>2° Semestre</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                class="btn-accent text-sm font-semibold px-4 py-2 rounded-lg transition">
                🔍 Filtrar
            </button>
            <a href="index.php?action=atrasos_historial"
                class="text-xs text-faint hover:text-strong underline transition">
                Limpiar filtros
            </a>
            <span class="ml-auto text-xs text-muted">
                <?= $totalHistorial ?> registro<?= $totalHistorial !== 1 ? 's' : '' ?>
                encontrado<?= $totalHistorial !== 1 ? 's' : '' ?>
            </span>
        </div>
    </form>

    <?php if (empty($agrupado)): ?>
        <div class="panel rounded-xl px-6 py-16 text-center">
            <span class="text-5xl block mb-4">📭</span>
            <p class="text-muted font-medium">No hay registros en el historial para los filtros seleccionados</p>
            <p class="text-faint text-sm mt-1">Los atrasos aparecerán aquí cuando se eliminen aplicando una medida disciplinaria</p>
        </div>

    <?php else: ?>

        <!-- AGRUPADO POR CURSO → ALUMNO -->
        <?php foreach ($agrupado as $cursoNombre => $alumnos): ?>
            <div class="mb-8">

                <!-- Cabecera del curso -->
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-px flex-1" style="background: var(--border-soft);"></div>
                    <div class="flex items-center gap-2 px-4 py-1.5 rounded-full"
                         style="background: var(--warn-soft); border: 1px solid var(--warn);">
                        <span class="text-sm">🏫</span>
                        <span class="font-bold text-sm uppercase tracking-wider" style="color: var(--warn);">
                            <?= htmlspecialchars($cursoNombre) ?>
                        </span>
                        <span class="text-xs text-faint">
                            (<?= array_sum(array_map('count', $alumnos)) ?> atrasos)
                        </span>
                    </div>
                    <div class="h-px flex-1" style="background: var(--border-soft);"></div>
                </div>

                <!-- Alumnos del curso -->
                <div class="space-y-4">
                    <?php foreach ($alumnos as $alumnoNombre => $atrasos): ?>
                        <div class="panel rounded-xl overflow-hidden">

                            <!-- Cabecera del alumno -->
                            <div class="px-5 py-3 border-b divider-soft flex items-center justify-between"
                                 style="background: var(--surface-1);">
                                <div>
                                    <p class="text-strong font-semibold text-sm">
                                        <?= htmlspecialchars($alumnoNombre) ?>
                                    </p>
                                    <p class="text-xs text-faint font-mono mt-0.5">
                                        RUN: <?= htmlspecialchars($atrasos[0]['alumno_run']) ?>
                                    </p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <?php
                                    $totalAlumno = count($atrasos);
                                    $justAlumno  = count(array_filter($atrasos, fn($a) => $a['justificado']));
                                    $injAlumno   = $totalAlumno - $justAlumno;
                                    ?>
                                    <div class="text-center">
                                        <p class="text-lg font-bold <?= $totalAlumno >= 5 ? 'text-danger' : 'text-warn' ?>">
                                            <?= $totalAlumno ?>
                                        </p>
                                        <p class="text-xs text-faint">atrasos</p>
                                    </div>
                                    <?php if ($injAlumno > 0): ?>
                                        <div class="text-center">
                                            <p class="text-lg font-bold text-danger">
                                                <?= $injAlumno ?>
                                            </p>
                                            <p class="text-xs text-faint">injust.</p>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>

                            <!-- Tabla de atrasos del alumno -->
                            <div class="overflow-x-auto">
                                <table class="data-table w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-muted">Fecha atraso</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold uppercase tracking-wider text-muted">Hora</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold uppercase tracking-wider text-muted">Sem.</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold uppercase tracking-wider text-muted">Estado</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider text-muted">Observación</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider text-muted">Acción tomada</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider text-muted">Eliminado por</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($atrasos as $h): ?>
                                            <tr>
                                                <td class="px-4 py-2.5 text-soft font-mono text-xs">
                                                    <?= date('d/m/Y', strtotime($h['fecha_atraso'])) ?>
                                                </td>
                                                <td class="px-3 py-2.5 text-center">
                                                    <span class="font-bold font-mono text-xs" style="color: var(--warn);">
                                                        <?= substr($h['hora_llegada'], 0, 5) ?>
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2.5 text-center text-xs text-muted">
                                                    <?= $h['semestre'] ?>°
                                                </td>
                                                <td class="px-3 py-2.5 text-center">
                                                    <?php if ($h['justificado']): ?>
                                                        <span class="banner-success text-xs font-bold px-2 py-0.5 rounded-full">J</span>
                                                    <?php else: ?>
                                                        <span class="chip-danger text-xs font-bold px-2 py-0.5 rounded-full">IJ</span>
                                                    <?php endif ?>
                                                </td>
                                                <td class="px-3 py-2.5 text-xs text-muted max-w-[160px] truncate">
                                                    <?= $h['observacion'] ? htmlspecialchars($h['observacion']) : '—' ?>
                                                </td>
                                                <td class="px-3 py-2.5">
                                                    <span class="chip-warn text-xs font-semibold px-2 py-0.5 rounded-full">
                                                        Medida disciplinaria
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2.5 text-xs text-muted">
                                                    <p><?= htmlspecialchars($h['eliminado_por_nombre'] ?? 'Sistema') ?></p>
                                                    <p class="text-faint font-mono">
                                                        <?= (new DateTime($h['eliminado_at']))->format('d/m/Y H:i') ?>
                                                    </p>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endforeach ?>

    <?php endif ?>

</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>