<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// =============================================
// CONFIGURACIÓN DE SEMESTRES Y MESES
// =============================================
$semestres = [
    [
        'nombre' => '1° Semestre',
        'inicio' => $fechasAnio['sem1_inicio'],
        'fin' => $fechasAnio['sem1_fin'],
    ],
    [
        'nombre' => '2° Semestre',
        'inicio' => $fechasAnio['sem2_inicio'],
        'fin' => $fechasAnio['sem2_fin'],
    ],
];

$nombresMeses = [
    '01' => 'Enero',
    '02' => 'Febrero',
    '03' => 'Marzo',
    '04' => 'Abril',
    '05' => 'Mayo',
    '06' => 'Junio',
    '07' => 'Julio',
    '08' => 'Agosto',
    '09' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre'
];

$diasCortos = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi'];

// Agrupar fechas hábiles por semestre → mes
$datosSemestres = [];
foreach ($semestres as $sem) {
    $periodo = new DatePeriod(
        new DateTime($sem['inicio']),
        new DateInterval('P1D'),
        (new DateTime($sem['fin']))->modify('+1 day')
    );

    $fechasPorMes = [];
    foreach ($periodo as $fecha) {
        if ($fecha->format("N") <= 5) {
            $mesKey = $fecha->format("Y-m");
            $fechasPorMes[$mesKey][] = clone $fecha;
        }
    }

    $datosSemestres[] = [
        'nombre' => $sem['nombre'],
        'fechasPorMes' => $fechasPorMes,
    ];
}

// Mes actual para auto-abrir
$mesActual = date("Y-m");
?>

<body class="h-full">
    <div class="min-h-full">

        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Libro de clases</h1>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- Info del curso -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">
                        Curso: <?= htmlspecialchars($curso['nombre']) ?>
                    </h2>
                    <a href="index.php?action=form_asistencia_masiva&curso_id=<?= $_GET['curso_id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded transition">
                        + Tomar asistencia
                    </a>
                </div>

                <!-- ================================
                 ACORDEONES DE SEMESTRES
            ================================= -->
                <div class="space-y-4">

                    <?php foreach ($datosSemestres as $semIdx => $semestre):

                        // Determinar si el semestre contiene el mes actual
                        $semTieneHoy = array_key_exists($mesActual, $semestre['fechasPorMes']);
                        ?>

                        <!-- SEMESTRE -->
                        <div class="rounded-lg border border-gray-600 overflow-hidden">

                            <button
                                class="acordeon-trigger w-full flex items-center justify-between px-5 py-4 bg-gray-700 text-white font-bold text-lg hover:bg-gray-600 transition"
                                onclick="toggleAcordeon(this)" data-abierto="<?= $semTieneHoy ? 'true' : 'false' ?>">
                                <span><?= $semestre['nombre'] ?></span>
                                <svg class="w-5 h-5 chevron transition-transform <?= $semTieneHoy ? 'rotate-180' : '' ?>"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                class="acordeon-content bg-gray-900 px-4 py-4 space-y-3 <?= $semTieneHoy ? '' : 'hidden' ?>">

                                <?php
                                // Precalcular acumulado anual por alumno para todo el semestre
                                $acumuladoSemestre = [];
                                foreach ($alumnos as $alumno) {
                                    $presTotal = 0;
                                    $ausTotal = 0;
                                    $diasTotal = 0;
                                    $fechaMatricula = !empty($alumno['fecha_matricula'])
                                        ? new DateTime($alumno['fecha_matricula'])
                                        : null;

                                    foreach ($semestre['fechasPorMes'] as $todasFechas) {
                                        foreach ($todasFechas as $fecha) {
                                            if ($fechaMatricula && $fecha < $fechaMatricula)
                                                continue;
                                            $f = $fecha->format("Y-m-d");
                                            $v = $asistencia[$alumno['matricula_id']][$f] ?? null;
                                            if ($v !== null) {
                                                $diasTotal++;
                                                if ($v == 1)
                                                    $presTotal++;
                                                else
                                                    $ausTotal++;
                                            }
                                        }
                                    }
                                    $acumuladoSemestre[$alumno['matricula_id']] = [
                                        'presentes' => $presTotal,
                                        'ausentes' => $ausTotal,
                                        'total' => $diasTotal,
                                        'pct' => $diasTotal > 0 ? round($presTotal / $diasTotal * 100) : null,
                                    ];
                                }
                                ?>

                                <?php foreach ($semestre['fechasPorMes'] as $mesKey => $fechas):
                                    [$anio, $mes] = explode('-', $mesKey);
                                    $nombreMes = $nombresMeses[$mes] . " " . $anio;
                                    $esMesActual = ($mesKey === $mesActual);

                                    // Calcular presentes/ausentes del mes para el resumen
                                    $totalCeldas = 0;
                                    $totalPresentes = 0;
                                    foreach ($alumnos as $alumno) {
                                        $fechaMatricula = !empty($alumno['fecha_matricula'])
                                            ? new DateTime($alumno['fecha_matricula'])
                                            : null;

                                        foreach ($fechas as $fecha) {
                                            if ($fechaMatricula && $fecha < $fechaMatricula)
                                                continue;

                                            $f = $fecha->format("Y-m-d");
                                            $v = $asistencia[$alumno['matricula_id']][$f] ?? null;
                                            if ($v !== null) {
                                                $totalCeldas++;
                                                if ($v == 1)
                                                    $totalPresentes++;
                                            }
                                        }
                                    }
                                    $pct = $totalCeldas > 0 ? round(($totalPresentes / $totalCeldas) * 100) : 0;
                                    ?>

                                    <!-- MES -->
                                    <div class="rounded-md border border-gray-600 overflow-hidden">

                                        <button
                                            class="acordeon-trigger w-full flex items-center justify-between px-4 py-3 bg-gray-800 text-white font-semibold hover:bg-gray-700 transition"
                                            onclick="toggleAcordeon(this)" data-mes="<?= $mesKey ?>">
                                            <div class="flex items-center gap-3">
                                                <span><?= $nombreMes ?></span>
                                                <?php if ($esMesActual): ?>
                                                    <span class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">Mes
                                                        actual</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span class="text-sm font-normal text-gray-400">
                                                    <?= count($fechas) ?> días hábiles
                                                </span>
                                                <?php if ($totalPresentes > 0): ?>
                                                    <span class="text-sm font-normal text-green-400">
                                                        <?= $pct ?>% asistencia
                                                    </span>
                                                <?php endif; ?>
                                                <svg class="w-4 h-4 chevron transition-transform <?= $esMesActual ? 'rotate-180' : '' ?>"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </button>

                                        <div class="acordeon-content overflow-x-auto <?= $esMesActual ? '' : 'hidden' ?>">
                                            <table class="w-full text-white bg-gray-800 text-xs">
                                                <thead>
                                                    <tr class="border-b border-gray-600">
                                                        <th
                                                            class="p-2 text-center sticky left-0 bg-gray-800 min-w-[26px] z-10 text-gray-500 text-xs">
                                                            #</th>
                                                        <th class="p-2 text-left sticky left-0 bg-gray-800 min-w-[26px] z-10">
                                                            Alumno</th>
                                                        <?php foreach ($fechas as $fecha): ?>
                                                            <th class="p-1 text-center min-w-[26px]">
                                                                <span class="block text-xs">
                                                                    <?= $fecha->format("d") ?>
                                                                </span>
                                                                <span class="block text-xs font-normal text-gray-400">
                                                                    <?= $diasCortos[$fecha->format("N") - 1] ?>
                                                                </span>
                                                            </th>
                                                        <?php endforeach; ?>
                                                        <th class="p-2 text-center min-w-[30px] text-gray-400 text-xs">%</th>
                                                        <th
                                                            class="p-2 text-center min-w-[30px] text-gray-400 text-xs border-l border-gray-600">
                                                            P</th>
                                                        <th class="p-2 text-center min-w-[30px] text-gray-400 text-xs">A</th>
                                                        <th class="p-2 text-center min-w-[30px] text-gray-400 text-xs">Días</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $loop = 0;
                                                    foreach ($alumnos as $alumno):
                                                        $presAlumno = 0;
                                                        $totalAlumno = 0;
                                                        $fechaMatricula = !empty($alumno['fecha_matricula'])
                                                            ? new DateTime($alumno['fecha_matricula'])
                                                            : null;

                                                        foreach ($fechas as $fecha) {
                                                            if ($fechaMatricula && $fecha < $fechaMatricula)
                                                                continue;
                                                            $f = $fecha->format("Y-m-d");
                                                            $v = $asistencia[$alumno['matricula_id']][$f] ?? null;
                                                            if ($v !== null) {
                                                                $totalAlumno++;
                                                                if ($v == 1)
                                                                    $presAlumno++;
                                                            }
                                                        }

                                                        $pctAlumno = $totalAlumno > 0
                                                            ? round(($presAlumno / $totalAlumno) * 100)
                                                            : null;

                                                        // Color basado en número de lista (1-5 par, 6-10 impar, etc.)
                                                        // Si no tiene número de lista usa la posición del loop como fallback
                                                        $numLista = $alumno['numero_lista'] ?? ($loop + 1);
                                                        $esGrupoPar = (int) (($numLista - 1) / 5) % 2 === 0;
                                                        $bgFila = $esGrupoPar ? '' : 'bg-slate-700/30';
                                                        $bgSticky = $esGrupoPar ? 'bg-gray-800' : 'bg-slate-700/60';
                                                        ?>
                                                        <tr class="border-t border-gray-700 <?= $bgFila ?>">

                                                            <!-- # -->
                                                            <td class="p-2 text-center sticky left-0 <?= $bgSticky ?> z-10 text-xs font-bold
                    <?= $alumno['numero_lista'] ? 'text-indigo-400' : 'text-gray-600' ?>">
                                                                <?= $alumno['numero_lista'] ?? '—' ?>
                                                            </td>

                                                            <!-- Nombre -->
                                                            <td
                                                                class="p-2 font-semibold sticky left-10 <?= $bgSticky ?> z-10 leading-tight">
                                                                <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?><br>
                                                                <span style="color:#969292; font-weight:normal;">
                                                                    <?= htmlspecialchars($alumno['nombre']) ?>
                                                                </span>
                                                            </td>

                                                            <!-- Días del mes -->
                                                            <?php foreach ($fechas as $fecha): ?>
                                                                <?php
                                                                $f = $fecha->format("Y-m-d");
                                                                $v = $asistencia[$alumno['matricula_id']][$f] ?? null;
                                                                $antesDeMatricula = $fechaMatricula && $fecha < $fechaMatricula;
                                                                ?>
                                                                <td
                                                                    class="text-center p-1 <?= $antesDeMatricula ? 'bg-gray-900/40' : '' ?>">
                                                                    <?php
                                                                    $esFuturo = $fecha > new DateTime('today');
                                                                    ?>
                                                                    <?php if ($antesDeMatricula): ?>
                                                                        <span class="text-gray-700" title="Antes de la matrícula">·</span>
                                                                    <?php elseif ($esFuturo): ?>
                                                                        <span class="text-gray-600 text-xs" title="Fecha futura">·</span>
                                                                    <?php elseif ($v === 1 || $v === "1"): ?>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                            viewBox="0 0 48 48" stroke-width="6" stroke="currentColor"
                                                                            fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                                            class="text-green-500 mx-auto">
                                                                            <path d="M10 24L20 34L38 14" />
                                                                        </svg>
                                                                    <?php elseif ($v === 0 || $v === "0"): ?>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                            viewBox="0 0 48 48" stroke-width="6" stroke="currentColor"
                                                                            fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                                            class="text-red-400 mx-auto">
                                                                            <path d="M12 12L36 36M36 12L12 36" />
                                                                        </svg>
                                                                    <?php else: ?>
                                                                        <span class="text-gray-600">—</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            <?php endforeach; ?>

                                                            <!-- % mes -->
                                                            <td class="text-center p-1 text-xs font-bold
                    <?= $pctAlumno === null ? 'text-gray-500' :
                        ($pctAlumno >= 85 ? 'text-green-400' :
                            ($pctAlumno >= 75 ? 'text-yellow-400' : 'text-red-400')) ?>">
                                                                <?= $pctAlumno !== null ? $pctAlumno . '%' : '—' ?>
                                                            </td>

                                                            <!-- P / A / Días del mes -->
                                                            <?php $ac = $acumuladoSemestre[$alumno['matricula_id']]; ?>
                                                            <td
                                                                class="text-center p-1 text-xs text-green-400 font-bold border-l border-gray-600">
                                                                <?= $presAlumno ?>
                                                            </td>
                                                            <td class="text-center p-1 text-xs text-red-400 font-bold">
                                                                <?= $totalAlumno - $presAlumno ?>
                                                            </td>
                                                            <td class="text-center p-1 text-xs text-gray-400">
                                                                <?= $totalAlumno ?>
                                                            </td>

                                                        </tr>
                                                        <?php
                                                        $loop++;
                                                    endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>



                                    </div><!-- fin mes -->

                                <?php endforeach; ?>

                            </div><!-- fin contenido semestre -->

                        </div><!-- fin semestre -->

                    <?php endforeach; ?>

                </div><!-- fin acordeones -->


                <div class="flex items-center justify-end gap-4" style="margin-top: 8px">

                    <!-- Botón volver -->
                    <a href="index.php?action=asistencia_cursos&anio_id=<?= $_GET['anio_id'] ?>" class="flex items-center gap-2 bg-gray-700 hover:bg-gray-600 text-white 
                   font-semibold px-6 py-3 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver a cursos
                    </a>

                </div>
            </div>
        </main>
    </div>

    <!-- Botón flotante PDF con selector de mes -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2">

        <!-- Selector de mes (aparece al hacer hover/click) -->
        <div id="selector-pdf" class="hidden bg-gray-800 border border-gray-600 rounded-xl p-4 shadow-2xl w-64">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Seleccionar mes a exportar</p>
            <form method="GET" action="index.php" target="_blank">
                <input type="hidden" name="action" value="libro_clases_pdf">
                <input type="hidden" name="curso_id" value="<?= $_GET['curso_id'] ?>">
                <input type="hidden" name="anio_id" value="<?= $_GET['anio_id'] ?>">

                <select name="mes" required
                    class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">— Elegir mes —</option>
                    <?php
                    // Recolectar todos los meses disponibles de ambos semestres
                    $mesesDisponibles = [];
                    foreach ($datosSemestres as $sem) {
                        foreach ($sem['fechasPorMes'] as $mesKey => $_) {
                            $mesesDisponibles[$mesKey] = $sem['nombre'];
                        }
                    }
                    $nombresMesesCortos = [
                        '01' => 'Enero',
                        '02' => 'Febrero',
                        '03' => 'Marzo',
                        '04' => 'Abril',
                        '05' => 'Mayo',
                        '06' => 'Junio',
                        '07' => 'Julio',
                        '08' => 'Agosto',
                        '09' => 'Septiembre',
                        '10' => 'Octubre',
                        '11' => 'Noviembre',
                        '12' => 'Diciembre'
                    ];
                    foreach ($mesesDisponibles as $mesKey => $semNombre):
                        [$anioM, $mesM] = explode('-', $mesKey);
                        $esFuturo = $mesKey > date('Y-m');
                        ?>
                        <option value="<?= $mesKey ?>" <?= $esFuturo ? 'disabled' : '' ?>     <?= $mesKey === date('Y-m') ? 'selected' : '' ?>>
                            <?= $nombresMesesCortos[$mesM] . ' ' . $anioM ?>
                            <?= $esFuturo ? '(sin datos)' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-500 text-white font-bold px-4 py-2 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Descargar PDF
                </button>
            </form>
        </div>

        <!-- Botón principal -->
        <button onclick="document.getElementById('selector-pdf').classList.toggle('hidden')"
            class="flex items-center gap-2 px-5 py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-full shadow-2xl shadow-green-900/50 transition-all duration-300 hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Exportar PDF
        </button>
    </div>

    <script>
        // Cerrar selector al hacer click fuera
        document.addEventListener('click', function (e) {
            const selector = document.getElementById('selector-pdf');
            if (!e.target.closest('#selector-pdf') && !e.target.closest('button[onclick]')) {
                selector.classList.add('hidden');
            }
        });
    </script>

</body>

<script>
    function toggleAcordeon(btn) {
        const content = btn.nextElementSibling;
        const estaOculto = content.classList.contains('hidden');

        content.classList.toggle('hidden', !estaOculto);
        btn.querySelector('.chevron').classList.toggle('rotate-180', estaOculto);
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>