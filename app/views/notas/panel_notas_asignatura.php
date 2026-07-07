<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$notasPorMatricula = [];
foreach ($notas as $n) {
    $notasPorMatricula[$n['matricula_id']][] = $n;
}

$fechasUnicas = [];
foreach ($notasPorMatricula as $ns) {
    foreach ($ns as $n) {
        $fechasUnicas[$n['fecha']] = true;
    }
}
ksort($fechasUnicas);
$fechasUnicas = array_keys($fechasUnicas);

$totalAlumnos = 0;
$totalAprobados = 0;
$totalReprobados = 0;
$totalPendientes = 0;
$sumaProms = 0;
$cntProms = 0;
$notasPorColumna = [];
$totalNotasDelCurso = count($fechasUnicas);

foreach ($alumnos as $alumno) {
    if (!empty($alumno['fecha_retiro'])) continue;
    $totalAlumnos++;
    $mid = $alumno['matricula_id'];
    $ns = $notasPorMatricula[$mid] ?? [];
    $cantNotasAlumno = count($ns);

    foreach ($ns as $n) {
        if (isset($n['fecha']) && is_numeric($n['nota'])) {
            $notasPorColumna[$n['fecha']][] = floatval($n['nota']);
        }
    }

    if ($cantNotasAlumno < $totalNotasDelCurso || $totalNotasDelCurso === 0) {
        $totalPendientes++;
    } else {
        $suma = array_sum(array_column($ns, 'nota'));
        $prom = $suma / $cantNotasAlumno;
        $sumaProms += $prom;
        $cntProms++;
        if ($prom >= 4.0) $totalAprobados++;
        else $totalReprobados++;
    }

    $promediosColumnas = [];
    foreach ($fechasUnicas as $fecha) {
        $notasCol = $notasPorColumna[$fecha] ?? [];
        $promediosColumnas[$fecha] = count($notasCol) > 0
            ? Nota::redondearNota(array_sum($notasCol) / count($notasCol), 1)
            : null;
    }
}

$promGeneral = $cntProms > 0 ? Nota::redondearNota($sumaProms / $cntProms, 1) : null;
$pctAprobados = $totalAlumnos > 0 ? round($totalAprobados / $totalAlumnos * 100) : 0;
$colorProm = $promGeneral === null ? 'text-faint'
    : ($promGeneral >= 4.0 ? 'text-success' : 'text-danger');
?>

<header class="page-header">
    <div class="mx-auto max-w-5xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">
                <?= htmlspecialchars($curso['nombre']) ?> · <?= $semestre ?>° Semestre ·
                <?= htmlspecialchars($anio['anio']) ?>
            </p>
            <h1 class="text-2xl font-bold text-strong font-display"><?= htmlspecialchars($asignatura['nombre']) ?></h1>
        </div>
        <div class="flex items-center gap-2">
            <?php if ($puedeEditar): ?>
                <a href="index.php?action=notas_createGroup&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&asignatura_id=<?= $asignaturaId ?>"
                    class="btn-primary flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                             m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Libro de notas
                </a>
            <?php endif; ?>
            <a href="index.php?action=notas_panel_asignaturas&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&semestre=<?= $semestre ?>"
                class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver
            </a>
        </div>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-6 sm:px-6 space-y-5">

    <!-- STATS + SELECTOR SEMESTRE -->
    <div class="flex flex-wrap items-center justify-between gap-4">

        <div class="flex items-center gap-2">
            <span class="text-xs text-muted font-semibold uppercase tracking-wider">Semestre:</span>
            <?php foreach ([1 => '1° Sem', 2 => '2° Sem'] as $num => $label):
                $activo = $semestre == $num; ?>
                <a href="index.php?action=notas_panel_asignatura&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&asignatura_id=<?= $asignaturaId ?>&semestre=<?= $num ?>"
                    class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition <?= $activo ? '' : 'btn-secondary' ?>"
                    <?php if ($activo): ?> style="background: var(--azul-vivo); border-color: var(--azul-vivo); color: #fff;" <?php endif; ?>>
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-1.5">
                <span class="text-xs text-muted">Promedio:</span>
                <span class="text-sm font-bold <?= $colorProm ?>">
                    <?= $promGeneral !== null ? $promGeneral : '—' ?>
                </span>
            </div>
            <div class="w-px h-4" style="background: var(--border-soft);"></div>
            <div class="flex items-center gap-1.5">
                <span class="text-xs text-muted">Aprobados:</span>
                <span class="text-sm font-bold <?= $pctAprobados >= 70 ? 'text-success' : 'text-danger' ?>">
                    <?= $pctAprobados ?>%
                </span>
            </div>
            <div class="w-px h-4" style="background: var(--border-soft);"></div>
            <div class="flex items-center gap-1.5">
                <span class="text-xs text-muted">Alumnos:</span>
                <span class="text-sm font-bold text-strong"><?= $totalAlumnos ?></span>
            </div>
        </div>
    </div>

    <!-- TABLA -->
    <?php if (empty($alumnos)): ?>
        <div class="panel rounded-xl px-6 py-12 text-center">
            <p class="text-muted">No hay alumnos matriculados en este curso.</p>
        </div>
    <?php else: ?>
        <div class="panel rounded-xl overflow-hidden shadow-xl overflow-x-auto">
            <table class="attendance-table w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-muted w-10">#</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-muted min-w-[180px]">Alumno</th>

                        <?php foreach ($fechasUnicas as $idx => $fecha): ?>
                            <th class="px-3 py-3 text-center min-w-[90px]">
                                <span class="block text-xs font-bold uppercase tracking-wider text-accent">
                                    Nota <?= $idx + 1 ?>
                                </span>
                                <span class="block text-[10px] font-normal text-muted mt-0.5">
                                    <?= date('d/m/Y', strtotime($fecha)) ?>
                                </span>
                            </th>
                        <?php endforeach ?>

                        <?php if (empty($fechasUnicas)): ?>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-muted">Notas</th>
                        <?php endif ?>

                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-muted w-24">
                            Promedio
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumnos as $alumno):
                        $mid = $alumno['matricula_id'];
                        $notasAlumno = $notasPorMatricula[$mid] ?? [];
                        $estaRetirado = !empty($alumno['fecha_retiro']);

                        $notasPorFecha = [];
                        foreach ($notasAlumno as $n) {
                            $notasPorFecha[$n['fecha']] = $n;
                        }

                        $suma = 0; $cant = 0;
                        foreach ($notasAlumno as $n) {
                            if (is_numeric($n['nota'])) { $suma += $n['nota']; $cant++; }
                        }
                        $promedio = $cant > 0 ? Nota::redondearNota($suma / $cant, 1) : null;
                        $colorNum = $alumno['numero_lista'] ? 'text-accent' : 'text-faint';
                        $colorPromedio = $promedio !== null
                            ? ($promedio >= 4.0 ? 'text-success' : 'text-danger')
                            : 'text-faint';
                        $bgFila = $estaRetirado ? 'row-retired' : '';
                        ?>
                        <tr class="<?= $bgFila ?>">

                            <td class="px-4 py-3 text-center text-xs font-bold <?= $colorNum ?>">
                                <?= $alumno['numero_lista'] ?? '—' ?>
                            </td>

                            <td class="px-4 py-3">
                                <p class="font-semibold text-strong leading-tight">
                                    <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                                </p>
                                <p class="text-muted text-xs mt-0.5">
                                    <?= htmlspecialchars($alumno['nombre']) ?>
                                </p>
                                <?php if ($estaRetirado): ?>
                                    <span class="chip-danger inline-flex items-center gap-1 mt-0.5 text-[10px] font-semibold px-1.5 py-0.5 rounded-full">
                                        Retirado
                                    </span>
                                <?php endif; ?>
                            </td>

                            <?php if (empty($fechasUnicas)): ?>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-faint text-xs italic">Sin notas</span>
                                </td>
                            <?php else: ?>
                                <?php foreach ($fechasUnicas as $fecha):
                                    $n = $notasPorFecha[$fecha] ?? null;
                                    ?>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($estaRetirado): ?>
                                            <span class="text-faint text-xs">—</span>
                                        <?php elseif ($n): ?>
                                            <?php
                                            $v = Nota::redondearNota(floatval($n['nota']), 1);
                                            $bgNota = $v >= 4.0
                                                ? 'banner-success'
                                                : 'banner-danger';
                                            ?>
                                            <div class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 <?= $bgNota ?>">
                                                <span class="text-sm font-bold"><?= number_format($v, 1) ?></span>
                                                <?php if ($puedeEditar): ?>
                                                    <a href="index.php?action=notas_edit&id=<?= $n['id'] ?>"
                                                        class="text-warn hover:opacity-80 transition" title="Editar nota">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15H9v-2.828z" />
                                                        </svg>
                                                    </a>
                                                    <a href="index.php?action=notas_delete&id=<?= $n['id'] ?>&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&asignatura_id=<?= $asignaturaId ?>&semestre=<?= $semestre ?>"
                                                        class="hover:opacity-80 transition" title="Eliminar nota"
                                                        onclick="return confirm('¿Eliminar esta nota (<?= number_format($v, 1) ?>)?')">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </a>
                                                <?php endif ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-faint text-xs">—</span>
                                        <?php endif ?>
                                    </td>
                                <?php endforeach ?>
                            <?php endif ?>

                            <td class="px-4 py-3 text-center">
                                <?php if ($promedio !== null): ?>
                                    <span class="inline-flex items-center justify-center w-14 h-7 rounded-lg text-sm font-bold <?= $colorPromedio ?>
                                             <?= $promedio >= 4.0 ? 'banner-success' : 'banner-danger' ?>">
                                        <?= number_format($promedio, 1) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-faint text-sm">—</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

                <tfoot>
                    <tr class="row-total">
                        <td colspan="2" class="px-4 py-3 text-xs font-bold text-muted uppercase tracking-wider">
                            Resumen del curso
                        </td>
                        <?php foreach ($fechasUnicas as $fecha):
                            $promCol = $promediosColumnas[$fecha] ?? null;
                            $colorCol = $promCol !== null
                                ? ($promCol >= 4.0 ? 'text-success' : 'text-danger')
                                : 'text-faint';
                            ?>
                            <td class="px-3 py-3 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="inline-flex items-center justify-center w-12 h-6 rounded-md text-xs font-bold <?= $colorCol ?>
                                                 <?= $promCol !== null ? ($promCol >= 4.0 ? 'banner-success' : 'banner-danger') : '' ?>">
                                        <?= $promCol !== null ? number_format($promCol, 1) : '—' ?>
                                    </span>
                                    <span class="text-[9px] text-faint mt-0.5 font-medium uppercase tracking-tight">Prom.</span>
                                </div>
                            </td>
                        <?php endforeach ?>

                        <?php if (empty($fechasUnicas)): ?>
                            <td></td>
                        <?php endif ?>

                        <td class="px-4 py-3 text-center">
                            <div class="flex flex-col items-center gap-0.5">
                                <span class="text-success text-xs font-semibold">✓ <?= $totalAprobados ?> Aprobados</span>
                                <span class="text-accent text-xs font-semibold">-- <?= $totalPendientes ?> Pendientes</span>
                                <span class="text-danger text-xs font-semibold">✗ <?= $totalReprobados ?> Reprobados</span>
                                <span class="inline-flex items-center justify-center w-14 h-7 mt-1 rounded-lg text-sm font-bold <?= $colorProm ?>
                                        <?= $promGeneral === null ? '' : ($promGeneral >= 4.0 ? 'banner-success' : 'banner-danger') ?>">
                                    <?= $promGeneral ?? '—' ?>
                                </span>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>

</main>

<!-- BOTÓN FLOTANTE PDF -->
<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2">

    <div id="panel-pdf" class="dropdown-panel hidden rounded-2xl p-4 shadow-2xl w-72 mb-1">
        <p class="text-xs font-semibold uppercase tracking-wider text-muted mb-3">Exportar PDF</p>

        <a href="index.php?action=reportes_notas_pdf_asignatura&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&asignatura_id=<?= $asignaturaId ?>&semestre=<?= $semestre ?>"
            target="_blank"
            class="btn-soft-info flex items-center gap-3 w-full px-4 py-3 rounded-xl mb-3 transition">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                     a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <div>
                <p class="text-xs font-semibold">Notas del ramo completo</p>
                <p class="text-[10px] text-faint mt-0.5">Todos los alumnos · <?= $semestre ?>° Sem</p>
            </div>
        </a>

        <p class="text-[10px] text-faint uppercase tracking-wider mb-2">Informe individual</p>
        <select id="sel-alumno-pdf" class="input-field w-full text-xs rounded-xl px-3 py-2 mb-2">
            <option value="">— Seleccionar alumno —</option>
            <?php foreach ($alumnos as $a): ?>
                <option value="<?= $a['matricula_id'] ?>">
                    <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat'] . ', ' . $a['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button onclick="descargarPdfAlumno()"
            class="btn-soft-success w-full flex items-center justify-center gap-2 font-bold px-4 py-2.5 rounded-xl transition text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5
                     M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Descargar informe
        </button>
    </div>

    <button onclick="togglePdf()"
        class="btn-danger flex items-center gap-2 px-5 py-3 font-bold rounded-full shadow-2xl transition-all duration-300 hover:scale-105">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414
                 A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        Exportar PDF
    </button>
</div>

<script>
    function togglePdf() {
        document.getElementById('panel-pdf').classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        const panel = document.getElementById('panel-pdf');
        if (!e.target.closest('#panel-pdf') && !e.target.closest('button[onclick="togglePdf()"]')) {
            panel.classList.add('hidden');
        }
    });
    function descargarPdfAlumno() {
        const sel = document.getElementById('sel-alumno-pdf');
        if (!sel.value) { alert('Selecciona un alumno primero.'); return; }
        window.open('index.php?action=reportes_notas_pdf_alumno&matricula_id=' + sel.value, '_blank');
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>