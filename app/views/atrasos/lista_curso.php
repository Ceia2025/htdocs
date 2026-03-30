<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$semestreActivo = $_GET['semestre'] ?? '';
$cursoIdActivo = $_GET['curso_id'] ?? '';
$anioIdActivo = $anioId ?? '';

// Agrupar stats para el ranking lateral
$stats = [];
foreach ($atrasos as $a) {
    $mid = $a['matricula_id'];
    $sem = $a['semestre'];
    if (!isset($stats[$mid])) {
        $stats[$mid] = [
            'nombre' => $a['apepat'] . ' ' . $a['apemat'] . ', ' . $a['nombre'],
            'run' => $a['run'],
            1 => ['total' => 0, 'justificados' => 0],
            2 => ['total' => 0, 'justificados' => 0],
        ];
    }
    $stats[$mid][$sem]['total']++;
    if ($a['justificado'])
        $stats[$mid][$sem]['justificados']++;
}

$rankingAlumnos = [];
foreach ($stats as $mid => $s) {
    $rankingAlumnos[$mid] = $semestreActivo
        ? ($s[$semestreActivo]['total'] ?? 0)
        : $s[1]['total'] + $s[2]['total'];
}
arsort($rankingAlumnos);

// Datos del resumen
$t = $resumen['totales'];
$hasDatos = ($t['total'] ?? 0) > 0;
$maxCurso = !empty($resumen['porCurso']) ? $resumen['porCurso'][0]['total'] : 1;
$maxSemana = !empty($resumen['porSemana']) ? max(array_column($resumen['porSemana'], 'total')) : 1;
$sem1 = count(array_filter($atrasos, fn($a) => $a['semestre'] == 1));
$sem2 = count(array_filter($atrasos, fn($a) => $a['semestre'] == 2));
$maxS = max($sem1, $sem2, 1);
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="bg-gray-800 border-b border-white/10">
            <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-400 mb-0.5">Convivencia Escolar
                    </p>
                    <h1 class="text-2xl font-bold text-white">Atrasos por Curso</h1>
                    <?php if ($hasDatos && $t['primera_fecha']): ?>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Datos desde <?= date('d/m/Y', strtotime($t['primera_fecha'])) ?>
                            hasta <?= date('d/m/Y', strtotime($t['ultima_fecha'])) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <a href="index.php?action=atrasos_registro"
                    class="flex items-center gap-2 text-sm font-semibold bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Registrar atraso
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6">

            <!-- FILTROS -->
            <form method="GET" action="index.php" class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-6">
                <input type="hidden" name="action" value="atrasos_lista_curso">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">

                    <!-- Año -->
                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Año</label>
                        <select name="anio_id" onchange="this.form.submit()"
                            class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer">
                            <?php foreach ($anios as $a): ?>
                                <option value="<?= $a['id'] ?>" <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                                    <?= $a['anio'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Curso -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                            Curso <span class="normal-case font-normal text-gray-600">(opcional)</span>
                        </label>
                        <select name="curso_id"
                            class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer">
                            <option value="">— Todos los cursos —</option>
                            <?php foreach ($cursos as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $c['id'] == $cursoIdActivo ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fecha desde -->
                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Desde</label>
                        <input type="date" name="fecha_desde"
                            value="<?= htmlspecialchars($_GET['fecha_desde'] ?? '') ?>"
                            class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer">
                    </div>

                    <!-- Fecha hasta -->
                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Hasta</label>
                        <input type="date" name="fecha_hasta"
                            value="<?= htmlspecialchars($_GET['fecha_hasta'] ?? '') ?>"
                            class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer">
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">

                    <!-- Semestre -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Semestre:</span>
                        <?php foreach (['' => 'Todos', '1' => '1° Sem', '2' => '2° Sem'] as $val => $label):
                            $activo = (string) ($semestre ?? '') === (string) $val; ?>
                            <button type="submit" name="semestre" value="<?= $val ?>"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition
                                <?= $activo ? 'bg-amber-600 border-amber-500 text-white' : 'bg-gray-900 border-gray-600 text-gray-400 hover:border-gray-400' ?>">
                                <?= $label ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- Semana -->
                    <div class="flex items-center gap-2 ml-auto">
                        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Semana:</span>
                        <input type="week" name="semana" value="<?= htmlspecialchars($_GET['semana'] ?? '') ?>"
                            class="bg-gray-900 text-white text-xs border border-gray-600 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer">
                    </div>

                    <button type="submit"
                        class="bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Filtrar
                    </button>

                    <?php if (!empty(array_filter([$cursoIdActivo, $semestreActivo, $_GET['fecha_desde'] ?? null, $_GET['fecha_hasta'] ?? null, $_GET['semana'] ?? null]))): ?>
                        <a href="index.php?action=atrasos_lista_curso&anio_id=<?= $anioId ?>"
                            class="text-xs text-gray-500 hover:text-gray-300 underline transition">
                            Limpiar filtros
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Badge filtro activo -->
                <?php if ($_GET['semana'] ?? null): ?>
                    <div
                        class="mt-3 text-xs text-amber-400 bg-amber-900/20 border border-amber-800/40 rounded-lg px-3 py-2">
                        📅 Semana <?= htmlspecialchars($_GET['semana']) ?>
                        (<?= date('d/m', strtotime($fechaDesde ?? 'now')) ?> –
                        <?= date('d/m/Y', strtotime($fechaHasta ?? 'now')) ?>)
                    </div>
                <?php elseif ($semestreActivo): ?>
                    <div
                        class="mt-3 text-xs text-amber-400 bg-amber-900/20 border border-amber-800/40 rounded-lg px-3 py-2">
                        📅 <?= $semestreActivo == 1 ? '1° Semestre: 04/03 – 18/06' : '2° Semestre: 06/07 – 24/11' ?>
                    </div>
                <?php endif; ?>
            </form>

            <?php if ($hasDatos): ?>

                <!-- ================================
             MÉTRICAS GENERALES
        ================================= -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
                    <?php
                    $metricas = [
                        ['valor' => $t['total'], 'label' => 'Total atrasos', 'color' => 'text-white', 'border' => 'border-gray-700'],
                        ['valor' => $t['injustificados'], 'label' => 'Injustificados', 'color' => 'text-red-400', 'border' => 'border-red-800/40'],
                        ['valor' => $t['justificados'], 'label' => 'Justificados', 'color' => 'text-green-400', 'border' => 'border-green-800/40'],
                        ['valor' => $t['alumnos_afectados'], 'label' => 'Alumnos afectados', 'color' => 'text-amber-400', 'border' => 'border-amber-800/40'],
                        ['valor' => $t['cursos_afectados'], 'label' => 'Cursos afectados', 'color' => 'text-blue-400', 'border' => 'border-blue-800/40'],
                        ['valor' => $t['dias_con_atrasos'], 'label' => 'Días con atrasos', 'color' => 'text-purple-400', 'border' => 'border-purple-800/40'],
                    ];
                    foreach ($metricas as $m): ?>
                        <div class="bg-gray-800 border <?= $m['border'] ?> rounded-xl p-3 text-center">
                            <p class="text-2xl font-bold <?= $m['color'] ?>"><?= $m['valor'] ?? 0 ?></p>
                            <p class="text-xs text-gray-500 mt-0.5 leading-tight"><?= $m['label'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- ================================
             GRÁFICOS: cursos + alumnos + semanas
        ================================= -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

                    <!-- Ranking por curso -->
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-700">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Atrasos por curso</h3>
                        </div>
                        <div class="px-4 py-3 space-y-3">
                            <?php foreach ($resumen['porCurso'] as $pc): ?>
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span
                                            class="text-gray-300 font-semibold truncate max-w-[140px]"><?= htmlspecialchars($pc['curso']) ?></span>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <span class="text-red-400"><?= $pc['injustificados'] ?> IJ</span>
                                            <span class="text-amber-400 font-bold"><?= $pc['total'] ?></span>
                                        </div>
                                    </div>
                                    <div class="h-1.5 bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-amber-500 rounded-full"
                                            style="width: <?= round($pc['total'] / $maxCurso * 100) ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Top 5 alumnos -->
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-700">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Top alumnos con más
                                atrasos</h3>
                        </div>
                        <div class="divide-y divide-gray-700/50">
                            <?php foreach ($resumen['topAlumnos'] as $i => $al):
                                $colores = ['text-amber-400', 'text-gray-300', 'text-amber-700', 'text-gray-500', 'text-gray-500'];
                                ?>
                                <div class="flex items-center gap-3 px-4 py-2.5">
                                    <span class="text-sm font-bold <?= $colores[$i] ?> w-4 flex-shrink-0"><?= $i + 1 ?></span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-xs font-semibold truncate">
                                            <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', '. $al['nombre']) ?>
                                        </p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($al['curso']) ?></p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span
                                            class="text-base font-bold <?= $al['total'] >= 5 ? 'text-red-400' : ($al['total'] >= 3 ? 'text-amber-400' : 'text-gray-300') ?>">
                                            <?= $al['total'] ?>
                                        </span>
                                        <?php if ($al['injustificados'] > 0): ?>
                                            <p class="text-xs text-red-500"><?= $al['injustificados'] ?> IJ</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Tendencia semanal -->
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-700">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tendencia semanal</h3>
                        </div>
                        <div class="px-4 py-3 space-y-2 max-h-52 overflow-y-auto">
                            <?php foreach ($resumen['porSemana'] as $ps):
                                $pct = round($ps['total'] / $maxSemana * 100);
                                $semNum = date('W', strtotime($ps['semana_inicio']));
                                ?>
                                <div>
                                    <div class="flex justify-between text-xs mb-0.5">
                                        <span class="text-gray-500">Sem <?= $semNum ?> ·
                                            <?= date('d/m', strtotime($ps['semana_inicio'])) ?></span>
                                        <span class="text-amber-400 font-bold"><?= $ps['total'] ?></span>
                                    </div>
                                    <div class="h-1.5 bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full <?= $pct >= 75 ? 'bg-red-500' : ($pct >= 40 ? 'bg-amber-500' : 'bg-green-500') ?>"
                                            style="width: <?= $pct ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Distribución por semestre (barra simple) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <?php foreach ([1 => $sem1, 2 => $sem2] as $num => $total): ?>
                        <div class="bg-gray-800 border border-gray-700 rounded-xl px-5 py-4">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-300 font-semibold"><?= $num ?>° Semestre</span>
                                <span class="text-amber-400 font-bold"><?= $total ?> atrasos</span>
                            </div>
                            <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-500 rounded-full"
                                    style="width: <?= round($total / $maxS * 100) ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>

                <!-- Sin datos aún -->
                <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-12 text-center mb-6">
                    <svg class="w-10 h-10 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0" />
                    </svg>
                    <p class="text-gray-400 font-medium">Sin atrasos registrados en este período</p>
                    <p class="text-gray-600 text-sm mt-1">Prueba cambiando los filtros o registra el primer atraso</p>
                </div>

            <?php endif; ?>

            <!-- ================================
             TABLA DETALLE + RANKING
        ================================= -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                <!-- Tabla principal -->
                <div class="xl:col-span-2">
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-700 flex items-center justify-between">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400">
                                Detalle de atrasos
                                <span class="ml-2 text-xs font-normal text-gray-600 normal-case">
                                    (<?= count($atrasos) ?> registros)
                                </span>
                            </h2>
                            <input type="text" id="buscar-tabla" placeholder="Filtrar alumno…"
                                oninput="filtrarTabla(this.value)"
                                class="bg-gray-900 text-white text-xs border border-gray-600 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-amber-500 w-40 placeholder-gray-600">
                        </div>

                        <?php if (empty($atrasos)): ?>
                            <div class="px-5 py-12 text-center">
                                <p class="text-gray-400 font-medium">Sin registros para mostrar</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm" id="tabla-atrasos">
                                    <thead>
                                        <tr class="bg-gray-900/50">
                                            <th
                                                class="px-4 py-2.5 text-left   text-xs font-semibold uppercase tracking-wider text-gray-500">
                                                Alumno</th>
                                            <th
                                                class="px-3 py-2.5 text-left   text-xs font-semibold uppercase tracking-wider text-gray-500">
                                                Curso</th>
                                            <th
                                                class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                                Fecha</th>
                                            <th
                                                class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                                Hora</th>
                                            <th
                                                class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                                Sem.</th>
                                            <th
                                                class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                                Estado</th>
                                            <th
                                                class="px-3 py-2.5 text-left   text-xs font-semibold uppercase tracking-wider text-gray-500">
                                                Obs.</th>
                                            <th class="px-3 py-2.5"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-atrasos">
                                        <?php foreach ($atrasos as $a): ?>
                                            <tr class="fila-atraso border-t border-gray-700/60 hover:bg-gray-700/20 transition"
                                                data-nombre="<?= strtolower($a['apepat'] . ' ' . $a['apemat']) ?>">
                                                <td class="px-4 py-3">
                                                    <p class="text-white font-semibold leading-tight">
                                                        <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat']) ?>
                                                    </p>
                                                    <p class=" font-semibold text-gray-500 font-mono">
                                                        <?= htmlspecialchars($a['nombre']) ?>
                                                    </p>
                                                    <p class="text-xs text-gray-500 font-mono">
                                                        <?= htmlspecialchars($a['run']) ?>
                                                    </p>
                                                </td>
                                                <td class="px-3 py-3 text-xs text-gray-400">
                                                    <?= htmlspecialchars($a['curso'] ?? '—') ?>
                                                </td>
                                                <td
                                                    class="px-3 py-3 text-center text-gray-300 font-mono text-xs whitespace-nowrap">
                                                    <?= date('d/m/Y', strtotime($a['fecha'])) ?>
                                                </td>
                                                <td class="px-3 py-3 text-center">
                                                    <span class="text-amber-400 font-bold font-mono">
                                                        <?= substr($a['hora_llegada'], 0, 5) ?>
                                                    </span>
                                                </td>
                                                <td class="px-3 py-3 text-center">
                                                    <span
                                                        class="text-xs font-semibold text-gray-400"><?= $a['semestre'] ?>°</span>
                                                </td>
                                                <td class="px-3 py-3 text-center">
                                                    <?php if ($a['justificado']): ?>
                                                        <span
                                                            class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-900/50 text-green-400 border border-green-800/50">J</span>
                                                    <?php else: ?>
                                                        <span
                                                            class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-900/40 text-red-400 border border-red-800/40">IJ</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-3 py-3 text-xs text-gray-500 max-w-[140px] truncate">
                                                    <?= $a['observacion'] ? htmlspecialchars($a['observacion']) : '—' ?>
                                                </td>
                                                <!--
                                                    <td class="px-3 py-3 text-right">
                                                        <a href="index.php?action=atrasos_eliminar&id=<?= $a['id'] ?>&redirect=atrasos_lista_curso&curso_id=<?= $cursoIdActivo ?>&anio_id=<?= $anioIdActivo ?>&semestre=<?= $semestreActivo ?>"
                                                        onclick="return confirm('¿Eliminar este atraso?')"
                                                        class="text-gray-600 hover:text-red-400 transition p-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </a>
                                            </td>
                                            -->
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="xl:col-span-1 space-y-4">

                    <!-- Ranking -->
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-700">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Ranking de atrasos
                            </h2>
                            <p class="text-xs text-gray-600 mt-0.5">Top 10 alumnos</p>
                        </div>
                        <div class="divide-y divide-gray-700/50">
                            <?php
                            $pos = 1;
                            foreach ($rankingAlumnos as $mid => $total):
                                if ($pos > 10)
                                    break;
                                $s = $stats[$mid];
                                $color = match ($pos) { 1 => 'text-amber-400', 2 => 'text-gray-300', 3 => 'text-amber-700', default => 'text-gray-500'};
                                ?>
                                <div class="flex items-center gap-3 px-5 py-3">
                                    <span
                                        class="text-sm font-bold <?= $color ?> w-5 text-center flex-shrink-0"><?= $pos ?></span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-xs font-semibold truncate">
                                            <?= htmlspecialchars($s['nombre']) ?>
                                        </p>
                                        <p class="text-xs text-gray-500 font-mono"><?= htmlspecialchars($s['run']) ?></p>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <span
                                            class="text-lg font-bold <?= $total >= 5 ? 'text-red-400' : ($total >= 3 ? 'text-amber-400' : 'text-gray-300') ?>">
                                            <?= $total ?>
                                        </span>
                                        <p class="text-xs text-gray-600">atrasos</p>
                                    </div>
                                </div>
                                <?php $pos++; endforeach; ?>
                        </div>
                    </div>

                    <!-- Próximamente -->
                    <div class="bg-gray-800/50 border border-dashed border-gray-700 rounded-xl px-5 py-4">
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-2">Próximamente</p>
                        <ul class="space-y-1.5">
                            <?php foreach (['Reporte PDF por alumno', 'Comparativa entre cursos', 'Exportar a Excel'] as $item): ?>
                                <li class="flex items-center gap-2 text-xs text-gray-600">
                                    <svg class="w-3.5 h-3.5 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <?= $item ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

        </main>
    </div>
</body>

<script>
    function filtrarTabla(texto) {
        const q = texto.toLowerCase();
        document.querySelectorAll('#tbody-atrasos .fila-atraso').forEach(fila => {
            fila.style.display = (fila.dataset.nombre || '').includes(q) ? '' : 'none';
        });
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>