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

<header class="page-header">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Libro de clases</h1>
    </div>
</header>

<main>
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- Info del curso -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-strong font-display">
                Curso: <?= htmlspecialchars($curso['nombre']) ?>
            </h2>
            <a href="index.php?action=form_asistencia_masiva&curso_id=<?= $_GET['curso_id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
                class="btn-primary text-sm font-medium px-4 py-2 rounded-lg transition">
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
                <div class="accordion-item">

                    <button
                        class="accordion-trigger acordeon-trigger w-full flex items-center justify-between px-5 py-4 font-bold text-lg transition"
                        onclick="toggleAcordeon(this)" data-abierto="<?= $semTieneHoy ? 'true' : 'false' ?>">
                        <span><?= $semestre['nombre'] ?></span>
                        <svg class="w-5 h-5 chevron transition-transform <?= $semTieneHoy ? 'rotate-180' : '' ?>"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        class="accordion-content acordeon-content px-4 py-4 space-y-3 <?= $semTieneHoy ? '' : 'hidden' ?>">

                        <?php
                        // Precalcular acumulado anual por alumno para todo el semestre
                        $acumuladoSemestre = [];
                        foreach ($alumnos as $alumno) {
                            $presTotal = 0;
                            $ausTotal = 0;
                            $diasTotal = 0;
                            $fechaRetiro = !empty($alumno['fecha_retiro'])
                                ? new DateTime($alumno['fecha_retiro'])
                                : null;

                            $estaRetirado = $fechaRetiro !== null;
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
                            <div class="accordion-item">

                                <button
                                    class="accordion-trigger acordeon-trigger w-full flex items-center justify-between px-4 py-3 font-semibold transition"
                                    onclick="toggleAcordeon(this)" data-mes="<?= $mesKey ?>">
                                    <div class="flex items-center gap-3">
                                        <span><?= $nombreMes ?></span>
                                        <?php if ($esMesActual): ?>
                                            <span class="text-xs bg-azul-vivo text-white px-2 py-0.5 rounded-full">Mes
                                                actual</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm font-normal text-muted">
                                            <?= count($fechas) ?> días hábiles
                                        </span>
                                        <?php if ($totalPresentes > 0): ?>
                                            <span class="text-sm font-normal text-success">
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
                                    <table class="attendance-table w-full text-xs">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="sticky-col left-0 p-2 text-center min-w-[26px] text-muted text-xs">
                                                    #</th>
                                                <th class="sticky-col left-10 p-2 text-left min-w-[26px] text-strong">
                                                    Alumno</th>
                                                <?php foreach ($fechas as $fecha):
                                                    $esViernes = $fecha->format("N") == 5;
                                                    ?>
                                                    <th
                                                        class="p-1 text-center min-w-[26px] border-b divider-soft <?= $esViernes ? 'border-r-2 divider-week' : '' ?>">
                                                        <span class="block text-xs <?= $esViernes ? 'text-warn' : '' ?>">
                                                            <?= $fecha->format("d") ?>
                                                        </span>
                                                        <span
                                                            class="block text-xs font-normal <?= $esViernes ? 'text-warn' : 'text-muted' ?>">
                                                            <?= $diasCortos[$fecha->format("N") - 1] ?>
                                                        </span>
                                                    </th>
                                                <?php endforeach; ?>
                                                <th class="p-2 text-center min-w-[30px] text-muted text-xs border-b divider-soft">%</th>
                                                <th
                                                    class="p-2 text-center min-w-[30px] text-muted text-xs border-b divider-soft border-l divider-soft">
                                                    P</th>
                                                <th class="p-2 text-center min-w-[30px] text-muted text-xs border-b divider-soft">A</th>
                                                <th class="p-2 text-center min-w-[30px] text-muted text-xs border-b divider-soft">Días</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $presentesPorFecha = [];
                                            foreach ($fechas as $fecha) {
                                                $f = $fecha->format("Y-m-d");
                                                $cuenta = 0;
                                                foreach ($alumnos as $al) {
                                                    $fmAl = !empty($al['fecha_matricula'])
                                                        ? new DateTime($al['fecha_matricula'])
                                                        : null;
                                                    $frAl = !empty($al['fecha_retiro'])
                                                        ? new DateTime($al['fecha_retiro'])
                                                        : null;

                                                    if ($fmAl && $fecha < $fmAl)
                                                        continue;
                                                    if ($frAl && $fecha > $frAl)
                                                        continue;

                                                    $v = $asistencia[$al['matricula_id']][$f] ?? null;
                                                    if ($v == 1)
                                                        $cuenta++;
                                                }
                                                $presentesPorFecha[$f] = $cuenta;
                                            }

                                            $loop = 0;
                                            foreach ($alumnos as $alumno):
                                                $presAlumno = 0;
                                                $totalAlumno = 0;

                                                $fechaMatricula = !empty($alumno['fecha_matricula'])
                                                    ? new DateTime($alumno['fecha_matricula'])
                                                    : null;
                                                $fechaRetiro = !empty($alumno['fecha_retiro'])
                                                    ? new DateTime($alumno['fecha_retiro'])
                                                    : null;
                                                $estaRetirado = $fechaRetiro !== null;

                                                foreach ($fechas as $fecha) {
                                                    if ($fechaMatricula && $fecha < $fechaMatricula)
                                                        continue;
                                                    if ($fechaRetiro && $fecha > $fechaRetiro)
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

                                                $numLista = $alumno['numero_lista'] ?? ($loop + 1);
                                                $esGrupoPar = (int) (($numLista - 1) / 5) % 2 === 0;
                                                $rowStateClass = $estaRetirado ? 'row-retired' : ($esGrupoPar ? '' : 'row-band');
                                                ?>

                                                <tr class="border-t divider-soft <?= $rowStateClass ?>">
                                                    <!-- # -->
                                                    <td
                                                        class="sticky-col left-0 p-2 text-center text-xs font-bold
                                                        <?= $estaRetirado ? 'text-danger' : (($alumno['numero_lista'] ?? null) ? 'text-azul-vivo' : 'text-faint') ?>">
                                                        <?= $alumno['numero_lista'] ?? '—' ?>
                                                    </td>

                                                    <!-- Nombre -->
                                                    <td class="sticky-col left-10 p-2 font-semibold leading-tight text-strong">

                                                        <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                                                        <br>
                                                        <span
                                                            class="font-normal text-xs <?= $estaRetirado ? 'text-danger' : 'text-muted' ?>">
                                                            <?= htmlspecialchars($alumno['nombre']) ?>
                                                            <?php if ($estaRetirado): ?>
                                                                · Ret. <?= $fechaRetiro->format('d/m/Y') ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </td>

                                                    <!-- Días del mes -->
                                                    <?php foreach ($fechas as $fecha): ?>
                                                        <?php
                                                        $f = $fecha->format("Y-m-d");
                                                        $v = $asistencia[$alumno['matricula_id']][$f] ?? null;
                                                        $antesDeMatricula = $fechaMatricula && $fecha < $fechaMatricula;
                                                        $despuesDeRetiro = $fechaRetiro && $fecha > $fechaRetiro;
                                                        $esFuturo = $fecha > new DateTime('today');
                                                        $esViernes = $fecha->format("N") == 5;
                                                        ?>
                                                        <td class="text-center p-1
        <?= ($antesDeMatricula || $despuesDeRetiro) ? 'cell-muted' : '' ?>
        <?= $esViernes ? 'border-r-2 divider-week' : '' ?>">

                                                            <?php if ($antesDeMatricula || $despuesDeRetiro): ?>
                                                                <span class="text-faint"
                                                                    title="<?= $despuesDeRetiro ? 'Alumno retirado' : 'Antes de la matrícula' ?>">·</span>

                                                            <?php elseif ($esFuturo): ?>
                                                                <span class="text-faint text-xs" title="Fecha futura">·</span>

                                                            <?php elseif ($v === 1 || $v === "1"): ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    viewBox="0 0 48 48" stroke-width="6" stroke="currentColor"
                                                                    fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                                    class="text-success mx-auto">
                                                                    <path d="M10 24L20 34L38 14" />
                                                                </svg>

                                                            <?php elseif ($v === 0 || $v === "0"): ?>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    viewBox="0 0 48 48" stroke-width="6" stroke="currentColor"
                                                                    fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                                    class="text-danger mx-auto">
                                                                    <path d="M12 12L36 36M36 12L12 36" />
                                                                </svg>

                                                            <?php else: ?>
                                                                <span class="text-faint">—</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    <?php endforeach; ?>

                                                    <!-- % mes -->
                                                    <td class="text-center p-1 text-xs font-bold
                    <?= $pctAlumno === null ? 'text-faint' :
                        ($pctAlumno >= 85 ? 'text-success' :
                            ($pctAlumno >= 75 ? 'text-warn' : 'text-danger')) ?>">
                                                        <?= $pctAlumno !== null ? $pctAlumno . '%' : '—' ?>
                                                    </td>

                                                    <!-- P / A / Días del mes -->
                                                    <?php $ac = $acumuladoSemestre[$alumno['matricula_id']]; ?>
                                                    <td
                                                        class="text-center p-1 text-xs text-success font-bold border-l divider-soft">
                                                        <?= $presAlumno ?>
                                                    </td>
                                                    <td class="text-center p-1 text-xs text-danger font-bold">
                                                        <?= $totalAlumno - $presAlumno ?>
                                                    </td>
                                                    <td class="text-center p-1 text-xs text-muted">
                                                        <?= $totalAlumno ?>
                                                    </td>

                                                </tr>
                                                <?php
                                                $loop++;
                                            endforeach; ?>

                                            <!-- FILA TOTAL POR DÍA -->
                                            <tr class="row-total">
                                                <td class="sticky-col left-0 p-1"></td>
                                                <td
                                                    class="sticky-col left-10 p-1 text-xs font-bold text-accent">
                                                    Total presentes
                                                </td>

                                                <?php foreach ($fechas as $fecha):
                                                    $f = $fecha->format("Y-m-d");
                                                    $esFuturo = $fecha > new DateTime('today');
                                                    $esViernes = $fecha->format("N") == 5;
                                                    $total = $presentesPorFecha[$f] ?? 0;
                                                    ?>
                                                    <td class="text-center p-1 text-xs font-bold text-accent
        <?= $esViernes ? 'border-r-2 divider-week' : '' ?>">
                                                        <?= $esFuturo ? '·' : $total ?>
                                                    </td>
                                                <?php endforeach; ?>

                                                <!-- Celdas vacías para %, P, A, Días -->
                                                <td colspan="4" class="border-l divider-soft"></td>
                                            </tr>

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
            <a href="index.php?action=asistencia_cursos&anio_id=<?= $_GET['anio_id'] ?>"
               class="btn-secondary flex items-center gap-2 font-semibold px-6 py-3 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a cursos
            </a>

        </div>
    </div>
</main>

<!-- Botón flotante PDF con selector de mes -->
<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2">

    <!-- Selector de mes (aparece al hacer hover/click) -->
    <div id="selector-pdf" class="dropdown-panel hidden rounded-xl p-4 shadow-2xl w-64">
        <p class="text-xs text-muted uppercase tracking-wider mb-3">Seleccionar mes a exportar</p>
        <form method="GET" action="index.php" target="_blank">
            <input type="hidden" name="action" value="libro_clases_pdf">
            <input type="hidden" name="curso_id" value="<?= $_GET['curso_id'] ?>">
            <input type="hidden" name="anio_id" value="<?= $_GET['anio_id'] ?>">

            <select name="mes" required
                class="input-field w-full text-sm rounded-lg px-3 py-2 mb-3">
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
                class="btn-accent w-full flex items-center justify-center gap-2 font-bold px-4 py-2 rounded-lg transition">
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
        class="btn-accent flex items-center gap-2 px-5 py-3 font-bold rounded-full shadow-2xl transition-all duration-300 hover:scale-105">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
            stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        Exportar PDF
    </button>
</div>
<div class="fixed bottom-24 right-6 z-50 flex flex-col items-end gap-2">

    <!-- Panel auditoría -->
    <div id="panel-auditoria"
        class="dropdown-panel hidden rounded-2xl shadow-2xl w-80 overflow-hidden mb-1">

        <div class="px-4 py-3 border-b divider-soft flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-azul-vivo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                         a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-xs font-semibold text-strong">Registro de asistencia</p>
            </div>
            <button onclick="document.getElementById('panel-auditoria').classList.add('hidden')"
                class="text-faint hover:text-strong transition text-sm">✕</button>
        </div>

        <div class="max-h-80 overflow-y-auto">
            <?php if (empty($auditoria)): ?>
                <div class="px-4 py-8 text-center">
                    <p class="text-muted text-xs">Sin registros de auditoría disponibles.</p>
                    <p class="text-faint text-[10px] mt-1">Los registros se guardan a partir de ahora.</p>
                </div>
            <?php else: ?>
                <div class="divide-soft divide-y">
                    <?php foreach ($auditoria as $fecha => $info):
                        $dt = new DateTime($info['updated_at']);
                        [$anioA, $mesA, $diaA] = explode('-', $fecha);
                        $nombresMesesAud = [
                            '01' => 'Ene',
                            '02' => 'Feb',
                            '03' => 'Mar',
                            '04' => 'Abr',
                            '05' => 'May',
                            '06' => 'Jun',
                            '07' => 'Jul',
                            '08' => 'Ago',
                            '09' => 'Sep',
                            '10' => 'Oct',
                            '11' => 'Nov',
                            '12' => 'Dic'
                        ];
                        $fechaLegible = $diaA . ' ' . ($nombresMesesAud[$mesA] ?? $mesA) . ' ' . $anioA;
                        ?>
                        <div class="audit-entry px-4 py-3">
                            <div class="flex items-center justify-between mb-0.5">
                                <span class="text-xs font-bold text-strong">
                                    <?= $fechaLegible ?>
                                </span>
                                <span class="text-[10px] text-faint">
                                    <?= $dt->format('H:i') ?>
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="avatar-badge w-5 h-5 rounded-full
                                        flex items-center justify-center flex-shrink-0">
                                    <span class="text-[9px] font-bold">
                                        <?= mb_strtoupper(mb_substr($info['usuario'], 0, 1)) ?>
                                    </span>
                                </div>
                                <span class="text-xs text-soft truncate">
                                    <?= htmlspecialchars($info['usuario']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="px-4 py-2.5 border-t divider-soft">
            <p class="text-[10px] text-faint text-center">
                Mostrando quién registró la asistencia por última vez en cada día
            </p>
        </div>
    </div>

    <!-- Botón principal auditoría -->
    <button onclick="document.getElementById('panel-auditoria').classList.toggle('hidden')"
        class="btn-soft-info flex items-center gap-2 px-4 py-3 font-semibold rounded-full shadow-xl transition-all duration-300 hover:scale-105">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Quién registró
    </button>
</div>

<script>
    // Cerrar panel auditoría al hacer click fuera
    document.addEventListener('click', function (e) {
        const panel = document.getElementById('panel-auditoria');
        if (panel &&
            !e.target.closest('#panel-auditoria') &&
            !e.target.closest('button[onclick*="panel-auditoria"]')) {
            panel.classList.add('hidden');
        }
    });
</script>

<script>
    // Cerrar selector al hacer click fuera
    document.addEventListener('click', function (e) {
        const selector = document.getElementById('selector-pdf');
        if (!e.target.closest('#selector-pdf') && !e.target.closest('button[onclick]')) {
            selector.classList.add('hidden');
        }
    });
</script>

<script>
    function toggleAcordeon(btn) {
        const content = btn.nextElementSibling;
        const estaOculto = content.classList.contains('hidden');

        content.classList.toggle('hidden', !estaOculto);
        btn.querySelector('.chevron').classList.toggle('rotate-180', estaOculto);
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>