<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user   = $_SESSION['user'];
$nombre = $user['nombre'];
$rol    = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$notasPorMatricula = [];
foreach ($notas as $n) {
    $notasPorMatricula[$n['matricula_id']][] = $n;
}

// Stats generales
$totalAlumnos  = 0;
$totalAprobados = 0;
$sumaProms     = 0;
$cntProms      = 0;

foreach ($alumnos as $alumno) {
    if (!empty($alumno['fecha_retiro'])) continue;
    $totalAlumnos++;
    $mid  = $alumno['matricula_id'];
    $ns   = $notasPorMatricula[$mid] ?? [];
    if (count($ns) > 0) {
        $suma = array_sum(array_column($ns, 'nota'));
        $prom = $suma / count($ns);
        $sumaProms += $prom;
        $cntProms++;
        if ($prom >= 4.0) $totalAprobados++;
    }
}
$promGeneral  = $cntProms > 0 ? round($sumaProms / $cntProms, 1) : null;
$pctAprobados = $totalAlumnos > 0 ? round($totalAprobados / $totalAlumnos * 100) : 0;
$colorProm    = $promGeneral === null ? 'text-gray-500'
    : ($promGeneral >= 4.0 ? 'text-green-400' : 'text-red-400');
?>

<body class="h-full bg-gray-900">
<div class="min-h-full">

    <!-- HEADER -->
    <header class="bg-gray-800 border-b border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">
                    <?= htmlspecialchars($curso['nombre']) ?> · <?= $semestre ?>° Semestre · <?= htmlspecialchars($anio['anio']) ?>
                </p>
                <h1 class="text-2xl font-bold text-white"><?= htmlspecialchars($asignatura['nombre']) ?></h1>
            </div>
            <div class="flex items-center gap-2">
                <?php if ($puedeEditar): ?>
                    <a href="index.php?action=notas_createGroup&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&asignatura_id=<?= $asignaturaId ?>"
                       class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white
                              text-sm font-semibold px-4 py-2 rounded-xl transition shadow-lg shadow-indigo-900/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                     m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Libro de notas
                    </a>
                <?php endif; ?>
                <a href="index.php?action=notas_panel_asignaturas&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&semestre=<?= $semestre ?>"
                   class="flex items-center gap-2 text-sm text-gray-400 hover:text-white
                          border border-gray-600 hover:border-gray-400 px-4 py-2 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-6 sm:px-6 space-y-5">

        <!-- STATS + SELECTOR SEMESTRE -->
        <div class="flex flex-wrap items-center justify-between gap-4">

            <!-- Selector semestre -->
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Semestre:</span>
                <?php foreach ([1 => '1° Sem', 2 => '2° Sem'] as $num => $label): ?>
                    <?php $activo = $semestre == $num; ?>
                    <a href="index.php?action=notas_panel_asignatura&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&asignatura_id=<?= $asignaturaId ?>&semestre=<?= $num ?>"
                       class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition
                              <?= $activo
                                  ? 'bg-indigo-600 border-indigo-500 text-white shadow-lg shadow-indigo-900/30'
                                  : 'bg-gray-900 border-gray-700 text-gray-400 hover:border-gray-500 hover:text-gray-300' ?>">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Stats en línea -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-gray-500">Promedio:</span>
                    <span class="text-sm font-bold <?= $colorProm ?>">
                        <?= $promGeneral !== null ? $promGeneral : '—' ?>
                    </span>
                </div>
                <div class="w-px h-4 bg-gray-700"></div>
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-gray-500">Aprobados:</span>
                    <span class="text-sm font-bold <?= $pctAprobados >= 70 ? 'text-green-400' : 'text-red-400' ?>">
                        <?= $pctAprobados ?>%
                    </span>
                </div>
                <div class="w-px h-4 bg-gray-700"></div>
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-gray-500">Alumnos:</span>
                    <span class="text-sm font-bold text-white"><?= $totalAlumnos ?></span>
                </div>
            </div>
        </div>

        <!-- TABLA -->
        <?php if (empty($alumnos)): ?>
            <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-12 text-center">
                <p class="text-gray-400">No hay alumnos matriculados en este curso.</p>
            </div>
        <?php else: ?>
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-xl">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-700 bg-gray-900/60">
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-gray-500 w-10">#</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Alumno</th>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-gray-500">Notas</th>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-gray-500 w-24 border-l border-gray-700/60">Promedio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/60">
                        <?php foreach ($alumnos as $alumno):
                            $mid          = $alumno['matricula_id'];
                            $notasAlumno  = $notasPorMatricula[$mid] ?? [];
                            $estaRetirado = !empty($alumno['fecha_retiro']);

                            $suma = 0; $cant = 0;
                            foreach ($notasAlumno as $n) {
                                if (is_numeric($n['nota'])) { $suma += $n['nota']; $cant++; }
                            }
                            $promedio     = $cant > 0 ? round($suma / $cant, 1) : null;
                            $colorNum     = $alumno['numero_lista'] ? 'text-indigo-400' : 'text-gray-600';
                            $colorPomedio = $promedio !== null
                                ? ($promedio >= 4.0 ? 'text-green-400' : 'text-red-400')
                                : 'text-gray-600';
                            $bgFila       = $estaRetirado ? 'bg-red-950/20' : 'hover:bg-gray-700/20 transition-colors';
                        ?>
                            <tr class="<?= $bgFila ?> <?= $estaRetirado ? 'opacity-70' : '' ?>">

                                <!-- # -->
                                <td class="px-4 py-3 text-center text-xs font-bold <?= $colorNum ?>">
                                    <?= $alumno['numero_lista'] ?? '—' ?>
                                </td>

                                <!-- Alumno -->
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-white leading-tight">
                                        <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                                    </p>
                                    <p class="text-gray-500 text-xs mt-0.5">
                                        <?= htmlspecialchars($alumno['nombre']) ?>
                                    </p>
                                    <?php if ($estaRetirado): ?>
                                        <span class="inline-flex items-center gap-1 mt-0.5 text-[10px] font-semibold
                                                     text-red-400 bg-red-900/30 border border-red-800/40
                                                     px-1.5 py-0.5 rounded-full">
                                            Retirado
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Notas -->
                                <td class="px-4 py-3 text-center">
                                    <?php if (empty($notasAlumno)): ?>
                                        <span class="text-gray-600 text-xs italic">Sin notas</span>
                                    <?php else: ?>
                                        <div class="flex flex-wrap justify-center gap-1.5">
                                            <?php foreach ($notasAlumno as $idx => $n):
                                                $v        = floatval($n['nota']);
                                                $aprobada = $v >= 4.0;
                                                $bgNota   = $aprobada
                                                    ? 'bg-green-900/20 border-green-700/40 text-green-300'
                                                    : 'bg-red-900/20 border-red-700/40 text-red-300';
                                            ?>
                                                <div class="inline-flex items-center gap-1 border rounded-lg px-2 py-1 <?= $bgNota ?>">
                                                    <span class="text-[10px] font-medium text-gray-500">N<?= $idx + 1 ?></span>
                                                    <span class="text-xs font-bold"><?= number_format($v, 1) ?></span>
                                                    <?php if ($puedeEditar): ?>
                                                        <a href="index.php?action=notas_edit&id=<?= $n['id'] ?>"
                                                           class="text-yellow-500/70 hover:text-yellow-400 transition ml-0.5"
                                                           title="Editar nota">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828
                                                                         2.828L11.828 15H9v-2.828z"/>
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <!-- Promedio -->
                                <td class="px-4 py-3 text-center border-l border-gray-700/60">
                                    <?php if ($promedio !== null): ?>
                                        <span class="inline-flex items-center justify-center w-14 h-7
                                                     rounded-lg text-sm font-bold <?= $colorPomedio ?>
                                                     <?= $promedio >= 4.0
                                                         ? 'bg-green-900/20 border border-green-700/30'
                                                         : 'bg-red-900/20 border border-red-700/30' ?>">
                                            <?= number_format($promedio, 1) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-600 text-sm">—</span>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                    <!-- FILA RESUMEN -->
                    <tfoot>
                        <tr class="border-t-2 border-gray-600 bg-gray-900/60">
                            <td colspan="2" class="px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                Resumen del curso
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-4 text-xs">
                                    <span class="text-green-400 font-semibold">
                                        ✓ <?= $totalAprobados ?> aprobados
                                    </span>
                                    <span class="text-red-400 font-semibold">
                                        ✗ <?= $totalAlumnos - $totalAprobados ?> reprobados
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center border-l border-gray-700/60">
                                <span class="inline-flex items-center justify-center w-14 h-7
                                             rounded-lg text-sm font-bold <?= $colorProm ?>
                                             <?= $promGeneral !== null && $promGeneral >= 4.0
                                                 ? 'bg-green-900/20 border border-green-700/30'
                                                 : 'bg-red-900/20 border border-red-700/30' ?>">
                                    <?= $promGeneral ?? '—' ?>
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>

    </main>
</div>

<!-- BOTÓN FLOTANTE PDF -->
<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2">

    <div id="panel-pdf"
         class="hidden bg-gray-800 border border-gray-600 rounded-2xl p-4 shadow-2xl w-72 mb-1">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Exportar PDF</p>

        <!-- Ramo completo -->
        <a href="index.php?action=reportes_notas_pdf_asignatura&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&asignatura_id=<?= $asignaturaId ?>&semestre=<?= $semestre ?>"
           target="_blank"
           class="flex items-center gap-3 w-full px-4 py-3 rounded-xl mb-3
                  bg-indigo-600/10 hover:bg-indigo-600/20 border border-indigo-600/30
                  text-indigo-300 hover:text-indigo-200 transition">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                         a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <div>
                <p class="text-xs font-semibold">Notas del ramo completo</p>
                <p class="text-[10px] text-gray-500 mt-0.5">Todos los alumnos · <?= $semestre ?>° Sem</p>
            </div>
        </a>

        <!-- Informe individual -->
        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Informe individual</p>
        <select id="sel-alumno-pdf"
                class="w-full bg-gray-900 text-white text-xs border border-gray-600 rounded-xl
                       px-3 py-2 mb-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">— Seleccionar alumno —</option>
            <?php foreach ($alumnos as $a): ?>
                <option value="<?= $a['matricula_id'] ?>">
                    <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat'] . ', ' . $a['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button onclick="descargarPdfAlumno()"
                class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-500
                       text-white font-bold px-4 py-2.5 rounded-xl transition text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5
                         M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
            Descargar informe
        </button>
    </div>

    <!-- Botón principal -->
    <button onclick="togglePdf()"
            class="flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-500 text-white
                   font-bold rounded-full shadow-2xl shadow-red-900/50 transition-all
                   duration-300 hover:scale-105">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414
                     A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        Exportar PDF
    </button>
</div>

<script>
function togglePdf() {
    document.getElementById('panel-pdf').classList.toggle('hidden');
}
document.addEventListener('click', function (e) {
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

</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>