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
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

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
                                            <table class="w-full text-white bg-gray-800 text-sm">
                                                <thead>
                                                    <tr class="border-b border-gray-600">
                                                        <th class="p-2 text-left sticky left-0 bg-gray-800 min-w-[180px] z-10">
                                                            Alumno
                                                        </th>
                                                        <?php foreach ($fechas as $fecha): ?>
                                                            <th class="p-1 text-center min-w-[34px]">
                                                                <span class="block text-xs"><?= $fecha->format("d") ?></span>
                                                                <span class="block text-xs font-normal text-gray-400">
                                                                    <?= $diasCortos[$fecha->format("N") - 1] ?>
                                                                </span>
                                                            </th>
                                                        <?php endforeach; ?>
                                                        <th class="p-2 text-center min-w-[60px] text-gray-400 text-xs">%</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($alumnos as $alumno):
                                                        // Calcular % individual del mes
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
                                                        ?>
                                                        <tr class="border-t border-gray-700 hover:bg-gray-750">
                                                            <td class="p-2 font-semibold sticky left-0 bg-gray-800 z-10">
                                                                <?= htmlspecialchars($alumno['apepat'] . " " . $alumno['apemat']) ?>
                                                            </td>
                                                            <?php foreach ($fechas as $fecha): ?>
                                                                <?php
                                                                $f = $fecha->format("Y-m-d");
                                                                $v = $asistencia[$alumno['matricula_id']][$f] ?? null;
                                                                $antesDeMatricula = $fechaMatricula && $fecha < $fechaMatricula;
                                                                ?>
                                                                <td
                                                                    class="text-center p-1 <?= $antesDeMatricula ? 'bg-gray-900/40' : '' ?>">
                                                                    <?php if ($antesDeMatricula): ?>
                                                                        <span class="text-gray-700 text-xs"
                                                                            title="Antes de la matrícula">·</span>
                                                                    <?php elseif ($v === "1" || $v === 1): ?>
                                                                        <span class="text-green-400 text-base">✔</span>
                                                                    <?php elseif ($v === "0" || $v === 0): ?>
                                                                        <span class="text-red-400 text-base">✖</span>
                                                                    <?php else: ?>
                                                                        <span class="text-gray-600">—</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            <?php endforeach; ?>
                                                            <td class="text-center p-1 text-xs font-bold
                                                <?= $pctAlumno === null ? 'text-gray-500' :
                                                    ($pctAlumno >= 85 ? 'text-green-400' :
                                                        ($pctAlumno >= 75 ? 'text-yellow-400' : 'text-red-400')) ?>">
                                                                <?= $pctAlumno !== null ? $pctAlumno . "%" : "—" ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div><!-- fin mes -->

                                <?php endforeach; ?>

                            </div><!-- fin contenido semestre -->

                        </div><!-- fin semestre -->

                    <?php endforeach; ?>

                </div><!-- fin acordeones -->

            </div>
        </main>
    </div>
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