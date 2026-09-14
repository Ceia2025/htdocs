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
$cursoIdActivo  = $_GET['curso_id'] ?? '';
$anioIdActivo   = $anioId ?? '';

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
$maxCurso  = !empty($resumen['porCurso'])   ? $resumen['porCurso'][0]['total']                    : 1;
$maxSemana = !empty($resumen['porSemana'])  ? max(array_column($resumen['porSemana'], 'total'))   : 1;
$sem1 = count(array_filter($atrasos, fn($a) => $a['semestre'] == 1));
$sem2 = count(array_filter($atrasos, fn($a) => $a['semestre'] == 2));
$maxS = max($sem1, $sem2, 1);
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Convivencia Escolar</p>
            <h1 class="text-2xl font-bold text-strong font-display">Atrasos por Curso</h1>
            <?php if ($hasDatos && $t['primera_fecha']): ?>
                <p class="text-xs text-muted mt-0.5">
                    Datos desde <?= date('d/m/Y', strtotime($t['primera_fecha'])) ?>
                    hasta <?= date('d/m/Y', strtotime($t['ultima_fecha'])) ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="flex items-center gap-3">
            <a href="index.php?action=atrasos_registro"
                class="btn-accent flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Registrar atraso
            </a>
            <a href="index.php?action=atrasos_historial&anio=<?= $anioId ?>"
               class="btn-soft-warn flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg transition">
                Ver historial disciplinario
            </a>
        </div>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-6 sm:px-6">

    <!-- FILTROS -->
    <form method="GET" action="index.php" class="panel rounded-xl p-4 mb-6">
        <input type="hidden" name="action" value="atrasos_lista_curso">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">

            <!-- Año -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Año</label>
                <select name="anio_id" onchange="this.form.submit()"
                    class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Curso -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">
                    Curso <span class="normal-case font-normal text-faint">(opcional)</span>
                </label>
                <select name="curso_id"
                    class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
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
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Desde</label>
                <input type="date" name="fecha_desde"
                    value="<?= htmlspecialchars($_GET['fecha_desde'] ?? '') ?>"
                    class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
            </div>

            <!-- Fecha hasta -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Hasta</label>
                <input type="date" name="fecha_hasta"
                    value="<?= htmlspecialchars($_GET['fecha_hasta'] ?? '') ?>"
                    class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">

            <!-- Semestre -->
            <div class="flex items-center gap-2">
                <span class="text-xs text-muted font-semibold uppercase tracking-wider">Semestre:</span>
                <?php foreach (['' => 'Todos', '1' => '1° Sem', '2' => '2° Sem'] as $val => $label):
                    $activo = (string)($semestre ?? '') === (string)$val; ?>
                    <button type="submit" name="semestre" value="<?= $val ?>"
                        class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition <?= $activo
                            ? 'text-azul-oscuro font-bold'
                            : 'btn-secondary' ?>"
                        <?php if ($activo): ?> style="background: var(--warn); border-color: var(--warn);" <?php endif; ?>>
                        <?= $label ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Semana -->
            <div class="flex items-center gap-2 ml-auto">
                <span class="text-xs text-muted font-semibold uppercase tracking-wider">Semana:</span>
                <input type="week" name="semana" value="<?= htmlspecialchars($_GET['semana'] ?? '') ?>"
                    class="input-field text-xs rounded-lg px-3 py-1.5 cursor-pointer">
            </div>

            <button type="submit"
                class="btn-accent text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
                Filtrar
            </button>

            <?php if (!empty(array_filter([$cursoIdActivo, $semestreActivo, $_GET['fecha_desde'] ?? null, $_GET['fecha_hasta'] ?? null, $_GET['semana'] ?? null]))): ?>
                <a href="index.php?action=atrasos_lista_curso&anio_id=<?= $anioId ?>"
                    class="text-xs text-faint hover:text-strong underline transition">
                    Limpiar filtros
                </a>
            <?php endif; ?>
        </div>

        <!-- Badge filtro activo -->
        <?php if ($_GET['semana'] ?? null): ?>
            <div class="banner-warning mt-3 text-xs rounded-lg px-3 py-2">
                📅 Semana <?= htmlspecialchars($_GET['semana']) ?>
                (<?= date('d/m', strtotime($fechaDesde ?? 'now')) ?> –
                <?= date('d/m/Y', strtotime($fechaHasta ?? 'now')) ?>)
            </div>
        <?php elseif ($semestreActivo): ?>
            <div class="banner-warning mt-3 text-xs rounded-lg px-3 py-2">
                📅 <?= $semestreActivo == 1 ? '1° Semestre: 04/03 – 18/06' : '2° Semestre: 06/07 – 24/11' ?>
            </div>
        <?php endif; ?>
    </form>

    <?php if ($hasDatos): ?>

        <!-- MÉTRICAS GENERALES -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <?php
            $metricas = [
                ['valor' => $t['total'],           'label' => 'Total atrasos',      'color' => 'text-strong',   'border' => 'var(--border-soft)'],
                ['valor' => $t['injustificados'],   'label' => 'Injustificados',     'color' => 'text-danger',   'border' => 'var(--danger)'],
                ['valor' => $t['justificados'],     'label' => 'Justificados',       'color' => 'text-success',  'border' => 'var(--success)'],
                ['valor' => $t['alumnos_afectados'],'label' => 'Alumnos afectados',  'color' => 'text-warn',     'border' => 'var(--warn)'],
                ['valor' => $t['cursos_afectados'], 'label' => 'Cursos afectados',   'color' => 'text-accent',   'border' => 'var(--link-action)'],
                ['valor' => $t['dias_con_atrasos'], 'label' => 'Días con atrasos',   'color' => 'text-soft',     'border' => 'var(--border-soft)'],
            ];
            foreach ($metricas as $m): ?>
                <div class="panel rounded-xl p-3 text-center" style="border-color: <?= $m['border'] ?>;">
                    <p class="text-2xl font-bold <?= $m['color'] ?>"><?= $m['valor'] ?? 0 ?></p>
                    <p class="text-xs text-muted mt-0.5 leading-tight"><?= $m['label'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- GRÁFICOS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

            <!-- Ranking por curso -->
            <div class="panel rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b divider-soft">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-muted">Atrasos por curso</h3>
                </div>
                <div class="px-4 py-3 space-y-3">
                    <?php foreach ($resumen['porCurso'] as $pc): ?>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-strong font-semibold truncate max-w-[140px]"><?= htmlspecialchars($pc['curso']) ?></span>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-danger"><?= $pc['injustificados'] ?> IJ</span>
                                    <span class="font-bold" style="color: var(--warn);"><?= $pc['total'] ?></span>
                                </div>
                            </div>
                            <div class="h-1.5 rounded-full overflow-hidden" style="background: var(--surface-1);">
                                <div class="h-full rounded-full" style="width: <?= round($pc['total'] / $maxCurso * 100) ?>%; background: var(--warn);"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Top alumnos -->
            <div class="panel rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b divider-soft">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-muted">Top alumnos con más atrasos</h3>
                </div>
                <div class="divide-soft divide-y">
                    <?php foreach (array_slice($resumen['topAlumnos'], 0, 5) as $i => $al):
                        $posColors = ['text-warn', 'text-soft', 'text-faint', 'text-faint', 'text-faint'];
                        ?>
                        <div class="flex items-center gap-3 px-4 py-2.5">
                            <span class="text-sm font-bold <?= $posColors[$i] ?> w-4 flex-shrink-0"><?= $i + 1 ?></span>
                            <div class="flex-1 min-w-0">
                                <p class="text-strong text-xs font-semibold truncate">
                                    <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                                </p>
                                <p class="text-xs text-muted"><?= htmlspecialchars($al['curso']) ?></p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-base font-bold <?= $al['total'] >= 5 ? 'text-danger' : ($al['total'] >= 3 ? 'text-warn' : 'text-soft') ?>">
                                    <?= $al['total'] ?>
                                </span>
                                <?php if ($al['injustificados'] > 0): ?>
                                    <p class="text-xs text-danger"><?= $al['injustificados'] ?> IJ</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($resumen['topAlumnos']) > 5): ?>
                    <div class="px-4 py-3 border-t divider-soft">
                        <button onclick="document.getElementById('modalTopAlumnos').classList.remove('hidden')"
                            class="w-full text-xs link-action font-medium transition flex items-center justify-center gap-1">
                            Ver todos (<?= count($resumen['topAlumnos']) ?>)
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tendencia semanal -->
            <div class="panel rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b divider-soft">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-muted">Tendencia semanal</h3>
                </div>
                <div class="px-4 py-3 space-y-2 max-h-52 overflow-y-auto">
                    <?php foreach ($resumen['porSemana'] as $ps):
                        $pct = round($ps['total'] / $maxSemana * 100);
                        $semNum = date('W', strtotime($ps['semana_inicio']));
                        $barColor = $pct >= 75 ? 'var(--danger)' : ($pct >= 40 ? 'var(--warn)' : 'var(--success)');
                        ?>
                        <div>
                            <div class="flex justify-between text-xs mb-0.5">
                                <span class="text-muted">Sem <?= $semNum ?> · <?= date('d/m', strtotime($ps['semana_inicio'])) ?></span>
                                <span class="font-bold" style="color: var(--warn);"><?= $ps['total'] ?></span>
                            </div>
                            <div class="h-1.5 rounded-full overflow-hidden" style="background: var(--surface-1);">
                                <div class="h-full rounded-full" style="width: <?= $pct ?>%; background: <?= $barColor ?>;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Distribución por semestre -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <?php foreach ([1 => $sem1, 2 => $sem2] as $num => $total): ?>
                <div class="panel rounded-xl px-5 py-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-strong font-semibold"><?= $num ?>° Semestre</span>
                        <span class="font-bold" style="color: var(--warn);"><?= $total ?> atrasos</span>
                    </div>
                    <div class="h-2 rounded-full overflow-hidden" style="background: var(--surface-1);">
                        <div class="h-full rounded-full" style="width: <?= round($total / $maxS * 100) ?>%; background: var(--warn);"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="panel rounded-xl px-6 py-12 text-center mb-6">
            <svg class="w-10 h-10 text-faint mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0" />
            </svg>
            <p class="text-muted font-medium">Sin atrasos registrados en este período</p>
            <p class="text-faint text-sm mt-1">Prueba cambiando los filtros o registra el primer atraso</p>
        </div>
    <?php endif; ?>

    <!-- TABLA DETALLE + RANKING -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Tabla principal -->
        <div class="xl:col-span-2">
            <div class="panel rounded-xl overflow-hidden">
                <div class="px-5 py-3.5 border-b divider-soft flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">
                        Detalle de atrasos
                        <span class="ml-2 text-xs font-normal text-faint normal-case">
                            (<?= count($atrasos) ?> registros)
                        </span>
                    </h2>
                    <input type="text" id="buscar-tabla" placeholder="Filtrar alumno…"
                        oninput="filtrarTabla(this.value)"
                        class="input-field text-xs rounded-lg px-3 py-1.5 w-40">
                </div>

                <?php if (empty($atrasos)): ?>
                    <div class="px-5 py-12 text-center">
                        <p class="text-muted font-medium">Sin registros para mostrar</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="data-table w-full text-sm" id="tabla-atrasos">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2.5 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Alumno</th>
                                    <th class="px-3 py-2.5 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Curso</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">Fecha</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">Hora</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">Sem.</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">Estado</th>
                                    <th class="px-3 py-2.5 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Obs.</th>
                                    <th class="px-3 py-2.5"></th>
                                </tr>
                            </thead>
                            <tbody id="tbody-atrasos">
                                <?php foreach ($atrasos as $a): ?>
                                    <tr class="fila-atraso" data-nombre="<?= strtolower($a['apepat'] . ' ' . $a['apemat']) ?>">
                                        <td class="px-4 py-3">
                                            <p class="text-strong font-semibold leading-tight">
                                                <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat']) ?>
                                            </p>
                                            <p class="text-soft text-xs font-semibold">
                                                <?= htmlspecialchars($a['nombre']) ?>
                                            </p>
                                            <p class="text-muted text-xs font-mono">
                                                <?= htmlspecialchars($a['run']) ?>
                                            </p>
                                            <?php if (!empty($a['registrado_por_nombre'])): ?>
                                                <p class="text-[10px] text-faint mt-1">
                                                    Registrado por:
                                                    <span class="text-muted font-medium">
                                                        <?= htmlspecialchars($a['registrado_por_nombre']) ?>
                                                    </span>
                                                </p>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-3 py-3 text-xs text-muted">
                                            <?= htmlspecialchars($a['curso'] ?? '—') ?>
                                        </td>
                                        <td class="px-3 py-3 text-center text-soft font-mono text-xs whitespace-nowrap">
                                            <?= date('d/m/Y', strtotime($a['fecha'])) ?>
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <span class="font-bold font-mono text-xs" style="color: var(--warn);">
                                                <?= substr($a['hora_llegada'], 0, 5) ?>
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <span class="text-xs font-semibold text-muted"><?= $a['semestre'] ?>°</span>
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <?php if ($a['justificado']): ?>
                                                <span class="banner-success text-xs font-bold px-2 py-0.5 rounded-full">J</span>
                                            <?php else: ?>
                                                <span class="chip-danger text-xs font-bold px-2 py-0.5 rounded-full">IJ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-3 py-3 text-xs text-muted max-w-[140px] ">
                                            <?= $a['observacion'] ? htmlspecialchars($a['observacion']) : '—' ?>
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="index.php?action=atraso_alumno_pdf&matricula_id=<?= $a['matricula_id'] ?>&anio_id=<?= $anioIdActivo ?>"
                                                    target="_blank"
                                                    class="icon-btn p-1 rounded" title="Descargar PDF del alumno">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                </a>

                                                <?php if ($rol === "administrador" || $rol === "Inspector general y Convivencia escolar"): ?>
                                                    <button type="button"
                                                        onclick="abrirModalHora(<?= $a['id'] ?>, '<?= substr($a['hora_llegada'], 0, 5) ?>', '<?= $a['fecha'] ?>')"
                                                        class="icon-btn p-1 rounded" title="Editar hora y fecha">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828
                                                                   a4 4 0 01-1.414.828l-3 1 1-3a4 4 0 01.828-1.414z" />
                                                        </svg>
                                                    </button>

                                                    <a href="index.php?action=atrasos_eliminar&id=<?= $a['id'] ?>&redirect=atrasos_lista_curso&curso_id=<?= $cursoIdActivo ?>&anio_id=<?= $anioIdActivo ?>&semestre=<?= $semestreActivo ?>"
                                                        onclick="return confirm('¿Eliminar este atraso?')"
                                                        class="icon-btn p-1 rounded text-danger">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
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
            <div class="panel rounded-xl overflow-hidden">
                <div class="px-5 py-3.5 border-b divider-soft">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Ranking de atrasos</h2>
                    <p class="text-xs text-faint mt-0.5">Top 10 alumnos</p>
                </div>
                <div class="divide-soft divide-y">
                    <?php
                    $pos = 1;
                    foreach ($rankingAlumnos as $mid => $total):
                        if ($pos > 10) break;
                        $s = $stats[$mid];
                        $posColor = match($pos) { 1 => 'text-warn', 2 => 'text-soft', 3 => 'text-faint', default => 'text-faint' };
                        ?>
                        <div class="flex items-center gap-3 px-5 py-3">
                            <span class="text-sm font-bold <?= $posColor ?> w-5 text-center flex-shrink-0"><?= $pos ?></span>
                            <div class="flex-1 min-w-0">
                                <p class="text-strong text-xs font-semibold truncate"><?= htmlspecialchars($s['nombre']) ?></p>
                                <p class="text-xs text-faint font-mono"><?= htmlspecialchars($s['run']) ?></p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <span class="text-lg font-bold <?= $total >= 5 ? 'text-danger' : ($total >= 3 ? 'text-warn' : 'text-soft') ?>">
                                    <?= $total ?>
                                </span>
                                <p class="text-xs text-faint">atrasos</p>
                            </div>
                        </div>
                        <?php $pos++; endforeach; ?>
                </div>
            </div>

            <!-- Próximamente -->
            <div class="panel rounded-xl px-5 py-4" style="border-style: dashed;">
                <p class="text-xs text-muted font-semibold uppercase tracking-wider mb-2">Próximamente</p>
                <ul class="space-y-1.5">
                    <?php foreach (['Reporte PDF por alumno', 'Comparativa entre cursos', 'Exportar a Excel'] as $item): ?>
                        <li class="flex items-center gap-2 text-xs text-faint">
                            <svg class="w-3.5 h-3.5 text-faint" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

<!-- MODAL: Top alumnos completo -->
<div id="modalTopAlumnos"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="modal-panel rounded-xl w-full max-w-md max-h-[80vh] flex flex-col shadow-xl">

        <div class="flex items-center justify-between px-5 py-4 border-b divider-soft flex-shrink-0">
            <h2 class="text-sm font-semibold text-strong font-display">Ranking de atrasos</h2>
            <button onclick="document.getElementById('modalTopAlumnos').classList.add('hidden')"
                class="icon-btn transition p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="overflow-y-auto divide-soft divide-y">
            <?php foreach ($resumen['topAlumnos'] as $i => $al):
                $posColor = match(true) { $i===0 => 'text-warn', $i===1 => 'text-soft', $i===2 => 'text-faint', default => 'text-faint' };
                ?>
                <div class="flex items-center gap-3 px-5 py-3">
                    <span class="text-sm font-bold <?= $posColor ?> w-5 flex-shrink-0 text-center"><?= $i + 1 ?></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-strong text-xs font-semibold truncate">
                            <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                        </p>
                        <p class="text-xs text-muted"><?= htmlspecialchars($al['curso']) ?></p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-base font-bold <?= $al['total'] >= 5 ? 'text-danger' : ($al['total'] >= 3 ? 'text-warn' : 'text-soft') ?>">
                            <?= $al['total'] ?>
                        </span>
                        <?php if ($al['injustificados'] > 0): ?>
                            <p class="text-xs text-danger"><?= $al['injustificados'] ?> IJ</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="px-5 py-3 border-t divider-soft flex-shrink-0">
            <p class="text-xs text-muted text-center"><?= count($resumen['topAlumnos']) ?> alumnos en total</p>
        </div>
    </div>
</div>

<!-- MODAL: Editar hora -->
<div id="modal-hora" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="cerrarModalHora()"></div>
    <div class="modal-panel relative rounded-2xl shadow-2xl p-6 w-80 z-10">

        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Atraso</p>
                <h3 class="text-lg font-bold text-strong font-display">Editar hora</h3>
            </div>
            <button onclick="cerrarModalHora()" class="icon-btn transition p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="index.php?action=atrasos_actualizar_hora">
            <input type="hidden" name="id" id="modal-id">
            <input type="hidden" name="curso_id" value="<?= $cursoIdActivo ?>">
            <input type="hidden" name="anio_id"  value="<?= $anioIdActivo ?>">
            <input type="hidden" name="semestre"  value="<?= $semestreActivo ?>">

            <div class="mb-4">
                <label class="block text-xs font-semibold text-muted uppercase tracking-wider mb-2">Fecha</label>
                <input type="date" name="fecha" id="modal-fecha-input"
                    class="input-field w-full text-lg font-mono rounded-xl px-4 py-3">
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-muted uppercase tracking-wider mb-2">Hora de llegada</label>
                <input type="time" name="hora_llegada" id="modal-hora-input"
                    class="input-field w-full text-lg font-mono rounded-xl px-4 py-3">
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="cerrarModalHora()"
                    class="btn-secondary flex-1 font-semibold py-2.5 rounded-xl transition text-sm">
                    Cancelar
                </button>
                <button type="submit"
                    class="btn-accent flex-1 font-bold py-2.5 rounded-xl transition text-sm">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Botón flotante PDF -->
<a id="btnExportarPdf"
    href="index.php?action=atrasos_pdf&anio_id=<?= $anioId ?>&curso_id=<?= $cursoIdActivo ?>&semestre=<?= $semestreActivo ?>&fecha_desde=<?= htmlspecialchars($_GET['fecha_desde'] ?? '') ?>&fecha_hasta=<?= htmlspecialchars($_GET['fecha_hasta'] ?? '') ?>&semana=<?= htmlspecialchars($_GET['semana'] ?? '') ?>"
    class="btn-danger fixed bottom-6 right-6 z-50 flex items-center gap-2 px-5 py-3 font-bold rounded-full shadow-2xl transition-all hover:scale-105">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
    </svg>
    <span>Exportar Reporte</span>
</a>

<script>
    function filtrarTabla(texto) {
        const q = texto.toLowerCase();
        document.querySelectorAll('#tbody-atrasos .fila-atraso').forEach(fila => {
            fila.style.display = (fila.dataset.nombre || '').includes(q) ? '' : 'none';
        });
    }

    function abrirModalHora(id, hora, fecha) {
        document.getElementById('modal-id').value = id;
        document.getElementById('modal-hora-input').value = hora;
        document.getElementById('modal-fecha-input').value = fecha;
        document.getElementById('modal-hora').classList.remove('hidden');
    }

    function cerrarModalHora() {
        document.getElementById('modal-hora').classList.add('hidden');
    }

    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') cerrarModalHora(); });

    document.getElementById('modalTopAlumnos').addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>