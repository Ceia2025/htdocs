<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth(); // obliga a tener sesión iniciada

$user = $_SESSION['user']; // usuario logueado
$nombre = $user['nombre'];
$rol = $user['rol'];

// true = contar solo activos en los contadores de sexo y edad; false = incluir retirados
$soloActivos = true;

// Edad mínima para considerarse mayor de edad
$edadMayoria = 18;

function detectarJornada(string $cursoNombre): string
{
    $n = mb_strtolower($cursoNombre, 'UTF-8');

    if (str_contains($n, 'eléctrico') || str_contains($n, 'electrico')
        || str_contains($n, 'básico') || str_contains($n, 'basico')) {
        return 'manana';
    }
    if (str_contains($n, 'párvulo') || str_contains($n, 'parvulo')) {
        return 'tarde';
    }
    if (preg_match('/\bhc\b/u', $n)) {
        return 'noche';
    }
    return 'sin_jornada';
}

function detectarModalidad(string $cursoNombre): string
{
    $n = mb_strtolower($cursoNombre, 'UTF-8');

    if (str_contains($n, 'oficio') || str_contains($n, 'básico') || str_contains($n, 'basico')) {
        return 'oficio';
    }
    if (str_contains($n, 'eléctrico') || str_contains($n, 'electrico')
        || str_contains($n, 'párvulo') || str_contains($n, 'parvulo')) {
        return 'tp';
    }
    if (preg_match('/\bhc\b/u', $n)) {
        return 'hc';
    }
    return 'sin_modalidad';
}

function normalizarSexo(?string $sexo): string
{
    $s = mb_strtolower(trim((string) $sexo), 'UTF-8');

    if (in_array($s, ['m', 'masculino', 'hombre', 'h'], true)) {
        return 'M';
    }
    if (in_array($s, ['f', 'femenino', 'mujer'], true)) {
        return 'F';
    }
    return 'otro';
}

// Devuelve 'menor', 'mayor' o 'sin_fecha' según la fecha de nacimiento
function clasificarEdad(?string $fechanac, int $edadMayoria): string
{
    $f = trim((string) $fechanac);
    if ($f === '' || str_starts_with($f, '0000')) {
        return 'sin_fecha';
    }

    try {
        $nac = new DateTime($f);
        $hoy = new DateTime('today');
        if ($nac > $hoy) {
            return 'sin_fecha';
        }
        return $nac->diff($hoy)->y < $edadMayoria ? 'menor' : 'mayor';
    } catch (Exception $e) {
        return 'sin_fecha';
    }
}

function nuevoGrupo(): array
{
    return [
        'cursos'    => [],
        'total'     => 0,
        'activos'   => 0,
        'retirados' => 0,
        'sexo'      => ['M' => 0, 'F' => 0, 'otro' => 0],
        'edad'      => ['menor' => 0, 'mayor' => 0, 'sin_fecha' => 0],
    ];
}

$jornadasConfig = [
    'manana'      => ['label' => 'Jornada Mañana', 'icon' => '☀️', 'desc' => '7° y 8°, Eléctrico'],
    'tarde'       => ['label' => 'Jornada Tarde',  'icon' => '🌤️', 'desc' => 'Párvulos'],
    'noche'       => ['label' => 'Jornada Noche',  'icon' => '🌙', 'desc' => 'HC'],
    'sin_jornada' => ['label' => 'Sin jornada',    'icon' => '❓', 'desc' => 'Cursos no clasificados'],
];

$modalidadesConfig = [
    'oficio'        => ['label' => 'Oficio',               'corto' => 'Oficio', 'icon' => '🛠️', 'desc' => '7° y 8° Básico'],
    'tp'            => ['label' => 'Técnico Profesional',  'corto' => 'TP',     'icon' => '🎓', 'desc' => 'Eléctrico y Párvulos'],
    'hc'            => ['label' => 'Humanista Científico', 'corto' => 'HC',     'icon' => '📚', 'desc' => '1° a 4° HC'],
    'sin_modalidad' => ['label' => 'Sin modalidad',        'corto' => '—',      'icon' => '❓', 'desc' => 'Cursos no clasificados'],
];

$jornadas = [];
foreach ($jornadasConfig as $key => $cfg) {
    $jornadas[$key] = nuevoGrupo();
}

$modalidades = [];
foreach ($modalidadesConfig as $key => $cfg) {
    $modalidades[$key] = nuevoGrupo();
}

$sexoStats = ['M' => 0, 'F' => 0, 'otro' => 0];
$edadStats = ['menor' => 0, 'mayor' => 0, 'sin_fecha' => 0];

if (!empty($alumnosPorCurso)) {
    foreach ($alumnosPorCurso as $cursoNombre => $alumnos) {
        $key = detectarJornada($cursoNombre);
        $mod = detectarModalidad($cursoNombre);
        $act = count(array_filter($alumnos, fn($a) => empty($a['deleted_at'])));
        $ret = count($alumnos) - $act;

        $jornadas[$key]['cursos'][$cursoNombre] = $alumnos;
        $jornadas[$key]['total']     += count($alumnos);
        $jornadas[$key]['activos']   += $act;
        $jornadas[$key]['retirados'] += $ret;

        $modalidades[$mod]['cursos'][$cursoNombre] = $alumnos;
        $modalidades[$mod]['total']     += count($alumnos);
        $modalidades[$mod]['activos']   += $act;
        $modalidades[$mod]['retirados'] += $ret;

        foreach ($alumnos as $al) {
            if ($soloActivos && !empty($al['deleted_at'])) {
                continue;
            }

            $sx = normalizarSexo($al['sexo'] ?? '');
            $sexoStats[$sx]++;
            $jornadas[$key]['sexo'][$sx]++;
            $modalidades[$mod]['sexo'][$sx]++;

            $ed = clasificarEdad($al['fechanac'] ?? null, $edadMayoria);
            $edadStats[$ed]++;
            $jornadas[$key]['edad'][$ed]++;
            $modalidades[$mod]['edad'][$ed]++;
        }
    }
}

$totalSexo = array_sum($sexoStats);
$pctM = $totalSexo > 0 ? round($sexoStats['M'] * 100 / $totalSexo, 1) : 0;
$pctF = $totalSexo > 0 ? round($sexoStats['F'] * 100 / $totalSexo, 1) : 0;
$subtituloSexo = $soloActivos ? 'Solo alumnos activos' : 'Incluye retirados';
$etiquetaSexoJornada = $soloActivos ? 'activos' : 'incl. retirados';

$totalEdad = array_sum($edadStats);
$pctMenores = $totalEdad > 0 ? round($edadStats['menor'] * 100 / $totalEdad, 1) : 0;
$pctMayores = $totalEdad > 0 ? round($edadStats['mayor'] * 100 / $totalEdad, 1) : 0;

// Incluir layout
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<main class="min-h-screen app-shell">
    <header class="page-header">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-strong">Listado por Año Escolar</h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- SELECTOR DE AÑO -->
        <form method="GET" action="index.php"
            class="panel rounded-2xl p-6 flex flex-wrap gap-4 items-end shadow-xl mb-8">
            <input type="hidden" name="action" value="listado_por_anio">
            <div class="flex flex-col gap-1">
                <label class="text-xs text-faint uppercase tracking-wider">Año Escolar</label>
                <select name="anio_id"
                    class="input-field rounded-xl px-4 py-3 transition min-w-[160px]">
                    <option value="">Seleccionar año...</option>
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= ($_GET['anio_id'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>     <?= $a['descripcion'] ? ' — ' . htmlspecialchars($a['descripcion']) : '' ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <button type="submit"
                class="btn-primary font-semibold rounded-xl px-6 py-3 shadow-lg transition">
                Ver Listado
            </button>
        </form>

        <?php if ($anioSeleccionado): ?>

            <!-- CONTADORES GLOBALES -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div class="chip-counter rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold text-accent"><?= $totalActivos + $totalRetirados ?></span>
                    <div>
                        <p class="text-strong font-semibold">Total Alumnos</p>
                        <p class="text-xs text-faint">Año <?= $anioSeleccionado['anio'] ?></p>
                    </div>
                </div>
                <div class="chip-counter chip-counter--all rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold"><?= $totalActivos ?></span>
                    <div>
                        <p class="text-strong font-semibold">Activos</p>
                        <p class="text-xs text-faint">Sin fecha de retiro</p>
                    </div>
                </div>
                <div class="chip-counter chip-counter--none rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold"><?= $totalRetirados ?></span>
                    <div>
                        <p class="text-strong font-semibold">Retirados</p>
                        <p class="text-xs text-faint">Con fecha de retiro</p>
                    </div>
                </div>
            </div>

            <!-- CONTADORES POR SEXO -->
            <div class="grid grid-cols-1 sm:grid-cols-<?= $sexoStats['otro'] > 0 ? '3' : '2' ?> gap-4 mb-4">
                <div class="chip-counter rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold text-accent"><?= $sexoStats['M'] ?></span>
                    <div>
                        <p class="text-strong font-semibold">👦 Masculino</p>
                        <p class="text-xs text-faint"><?= $pctM ?>% · <?= $subtituloSexo ?></p>
                    </div>
                </div>
                <div class="chip-counter rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold text-accent"><?= $sexoStats['F'] ?></span>
                    <div>
                        <p class="text-strong font-semibold">👧 Femenino</p>
                        <p class="text-xs text-faint"><?= $pctF ?>% · <?= $subtituloSexo ?></p>
                    </div>
                </div>
                <?php if ($sexoStats['otro'] > 0): ?>
                    <div class="chip-counter chip-counter--none rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                        <span class="text-4xl font-bold"><?= $sexoStats['otro'] ?></span>
                        <div>
                            <p class="text-strong font-semibold">Sin dato</p>
                            <p class="text-xs text-faint">Sexo vacío o no reconocido</p>
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <!-- CONTADORES POR EDAD -->
            <div class="grid grid-cols-1 sm:grid-cols-<?= $edadStats['sin_fecha'] > 0 ? '3' : '2' ?> gap-4 mb-8">
                <div class="chip-counter rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold text-accent"><?= $edadStats['menor'] ?></span>
                    <div>
                        <p class="text-strong font-semibold">🧒 Menores de edad</p>
                        <p class="text-xs text-faint"><?= $pctMenores ?>% · Menos de <?= $edadMayoria ?> años · <?= $subtituloSexo ?></p>
                    </div>
                </div>
                <div class="chip-counter rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold text-accent"><?= $edadStats['mayor'] ?></span>
                    <div>
                        <p class="text-strong font-semibold">🧑 Mayores de edad</p>
                        <p class="text-xs text-faint"><?= $pctMayores ?>% · <?= $edadMayoria ?> años o más · <?= $subtituloSexo ?></p>
                    </div>
                </div>
                <?php if ($edadStats['sin_fecha'] > 0): ?>
                    <div class="chip-counter chip-counter--none rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                        <span class="text-4xl font-bold"><?= $edadStats['sin_fecha'] ?></span>
                        <div>
                            <p class="text-strong font-semibold">Sin fecha</p>
                            <p class="text-xs text-faint">Fecha de nacimiento vacía o inválida</p>
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <?php if (!empty($alumnosPorCurso)): ?>

                <!-- CONTADORES POR JORNADA -->
                <h2 class="text-sm text-faint uppercase tracking-wider mb-3">Alumnos por jornada</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                    <?php foreach (['manana', 'tarde', 'noche'] as $key): ?>
                        <?php $j = $jornadas[$key]; $cfg = $jornadasConfig[$key]; ?>
                        <div class="chip-counter rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                            <span class="text-4xl"><?= $cfg['icon'] ?></span>
                            <div class="flex-1">
                                <p class="text-strong font-semibold"><?= $cfg['label'] ?></p>
                                <p class="text-xs text-faint"><?= $cfg['desc'] ?></p>
                                <p class="text-xs text-faint mt-1">
                                    ✅ <?= $j['activos'] ?> activos · 🚪 <?= $j['retirados'] ?> retirados
                                </p>
                                <p class="text-xs text-faint mt-1">
                                    👦 <?= $j['sexo']['M'] ?> · 👧 <?= $j['sexo']['F'] ?><?php if ($j['sexo']['otro'] > 0): ?> · ❔ <?= $j['sexo']['otro'] ?><?php endif ?>
                                    <span>(<?= $etiquetaSexoJornada ?>)</span>
                                </p>
                                <p class="text-xs text-faint mt-1">
                                    🧒 <?= $j['edad']['menor'] ?> menores · 🧑 <?= $j['edad']['mayor'] ?> mayores<?php if ($j['edad']['sin_fecha'] > 0): ?> · ❔ <?= $j['edad']['sin_fecha'] ?> sin fecha<?php endif ?>
                                </p>
                            </div>
                            <span class="text-4xl font-bold text-accent"><?= $j['total'] ?></span>
                        </div>
                    <?php endforeach ?>
                </div>

                <?php if (!empty($jornadas['sin_jornada']['cursos'])): ?>
                    <?php $js = $jornadas['sin_jornada']; ?>
                    <div class="chip-counter rounded-2xl px-6 py-4 flex items-center gap-4 shadow mb-10">
                        <span class="text-4xl"><?= $jornadasConfig['sin_jornada']['icon'] ?></span>
                        <div class="flex-1">
                            <p class="text-strong font-semibold"><?= $jornadasConfig['sin_jornada']['label'] ?></p>
                            <p class="text-xs text-faint"><?= $jornadasConfig['sin_jornada']['desc'] ?></p>
                            <p class="text-xs text-faint mt-1">
                                👦 <?= $js['sexo']['M'] ?> · 👧 <?= $js['sexo']['F'] ?><?php if ($js['sexo']['otro'] > 0): ?> · ❔ <?= $js['sexo']['otro'] ?><?php endif ?>
                                <span>(<?= $etiquetaSexoJornada ?>)</span>
                            </p>
                            <p class="text-xs text-faint mt-1">
                                🧒 <?= $js['edad']['menor'] ?> menores · 🧑 <?= $js['edad']['mayor'] ?> mayores<?php if ($js['edad']['sin_fecha'] > 0): ?> · ❔ <?= $js['edad']['sin_fecha'] ?> sin fecha<?php endif ?>
                            </p>
                        </div>
                        <span class="text-4xl font-bold text-accent"><?= $js['total'] ?></span>
                    </div>
                <?php endif ?>

                <!-- CONTADORES POR MODALIDAD -->
                <h2 class="text-sm text-faint uppercase tracking-wider mb-3">Alumnos por modalidad</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                    <?php foreach (['oficio', 'tp', 'hc'] as $key): ?>
                        <?php $m = $modalidades[$key]; $cfg = $modalidadesConfig[$key]; ?>
                        <div class="chip-counter rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                            <span class="text-4xl"><?= $cfg['icon'] ?></span>
                            <div class="flex-1">
                                <p class="text-strong font-semibold"><?= $cfg['label'] ?></p>
                                <p class="text-xs text-faint"><?= $cfg['desc'] ?></p>
                                <p class="text-xs text-faint mt-1">
                                    ✅ <?= $m['activos'] ?> activos · 🚪 <?= $m['retirados'] ?> retirados
                                </p>
                                <p class="text-xs text-faint mt-1">
                                    👦 <?= $m['sexo']['M'] ?> · 👧 <?= $m['sexo']['F'] ?><?php if ($m['sexo']['otro'] > 0): ?> · ❔ <?= $m['sexo']['otro'] ?><?php endif ?>
                                    <span>(<?= $etiquetaSexoJornada ?>)</span>
                                </p>
                                <p class="text-xs text-faint mt-1">
                                    🧒 <?= $m['edad']['menor'] ?> menores · 🧑 <?= $m['edad']['mayor'] ?> mayores<?php if ($m['edad']['sin_fecha'] > 0): ?> · ❔ <?= $m['edad']['sin_fecha'] ?> sin fecha<?php endif ?>
                                </p>
                            </div>
                            <span class="text-4xl font-bold text-accent"><?= $m['total'] ?></span>
                        </div>
                    <?php endforeach ?>
                </div>

                <?php if (!empty($modalidades['sin_modalidad']['cursos'])): ?>
                    <?php $ms = $modalidades['sin_modalidad']; ?>
                    <div class="chip-counter rounded-2xl px-6 py-4 flex items-center gap-4 shadow mb-10">
                        <span class="text-4xl"><?= $modalidadesConfig['sin_modalidad']['icon'] ?></span>
                        <div class="flex-1">
                            <p class="text-strong font-semibold"><?= $modalidadesConfig['sin_modalidad']['label'] ?></p>
                            <p class="text-xs text-faint"><?= $modalidadesConfig['sin_modalidad']['desc'] ?></p>
                            <p class="text-xs text-faint mt-1">
                                👦 <?= $ms['sexo']['M'] ?> · 👧 <?= $ms['sexo']['F'] ?><?php if ($ms['sexo']['otro'] > 0): ?> · ❔ <?= $ms['sexo']['otro'] ?><?php endif ?>
                                <span>(<?= $etiquetaSexoJornada ?>)</span>
                            </p>
                            <p class="text-xs text-faint mt-1">
                                🧒 <?= $ms['edad']['menor'] ?> menores · 🧑 <?= $ms['edad']['mayor'] ?> mayores<?php if ($ms['edad']['sin_fecha'] > 0): ?> · ❔ <?= $ms['edad']['sin_fecha'] ?> sin fecha<?php endif ?>
                            </p>
                        </div>
                        <span class="text-4xl font-bold text-accent"><?= $ms['total'] ?></span>
                    </div>
                <?php endif ?>

                <!-- LISTADO POR JORNADA Y CURSO -->
                <?php foreach ($jornadas as $jornadaKey => $jornada): ?>
                    <?php if (empty($jornada['cursos'])) continue; ?>
                    <?php $cfg = $jornadasConfig[$jornadaKey]; ?>

                    <section class="mb-10">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                            <h2 class="text-xl font-bold text-strong">
                                <?= $cfg['icon'] ?> <?= $cfg['label'] ?>
                            </h2>
                            <div class="flex flex-wrap items-center gap-2 text-sm">
                                <span class="report-blue report-badge rounded-lg px-3 py-1 font-semibold">
                                    <?= $jornada['total'] ?> alumnos
                                </span>
                                <span class="report-emerald report-badge rounded-lg px-3 py-1 font-semibold">
                                    ✅ <?= $jornada['activos'] ?>
                                </span>
                                <?php if ($jornada['retirados'] > 0): ?>
                                    <span class="report-red report-badge rounded-lg px-3 py-1 font-semibold">
                                        🚪 <?= $jornada['retirados'] ?>
                                    </span>
                                <?php endif ?>
                                <span class="report-blue report-badge rounded-lg px-3 py-1 font-semibold">
                                    👦 <?= $jornada['sexo']['M'] ?>
                                </span>
                                <span class="report-blue report-badge rounded-lg px-3 py-1 font-semibold">
                                    👧 <?= $jornada['sexo']['F'] ?>
                                </span>
                                <span class="report-blue report-badge rounded-lg px-3 py-1 font-semibold">
                                    🧒 <?= $jornada['edad']['menor'] ?> menores
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <?php foreach ($jornada['cursos'] as $cursoNombre => $alumnos): ?>
                                <?php
                                $activos = count(array_filter($alumnos, fn($a) => empty($a['deleted_at'])));
                                $retirados = count(array_filter($alumnos, fn($a) => !empty($a['deleted_at'])));
                                $cursoId = 'curso-' . preg_replace('/[^a-z0-9]/i', '-', $cursoNombre);
                                $modCurso = $modalidadesConfig[detectarModalidad($cursoNombre)];
                                ?>

                                <div class="accordion-item shadow-lg">

                                    <!-- CABECERA CLICKEABLE -->
                                    <button onclick="toggleCurso('<?= $cursoId ?>')"
                                        class="accordion-trigger w-full flex items-center justify-between px-6 py-4 transition group">

                                        <div class="flex items-center gap-4">
                                            <svg id="icon-<?= $cursoId ?>" class="w-5 h-5 text-accent transition-transform duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-lg font-bold text-strong"><?= htmlspecialchars($cursoNombre) ?></span>
                                            <span class="report-blue report-badge rounded-lg px-2 py-0.5 text-xs font-semibold">
                                                <?= $modCurso['icon'] ?> <?= $modCurso['corto'] ?>
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2 text-sm">
                                            <span class="report-blue report-badge rounded-lg px-3 py-1 font-semibold">
                                                <?= count($alumnos) ?> alumnos
                                            </span>
                                            <span class="report-emerald report-badge rounded-lg px-3 py-1 font-semibold">
                                                ✅ <?= $activos ?>
                                            </span>
                                            <?php if ($retirados > 0): ?>
                                                <span class="report-red report-badge rounded-lg px-3 py-1 font-semibold">
                                                    🚪 <?= $retirados ?>
                                                </span>
                                            <?php endif ?>
                                        </div>
                                    </button>

                                    <!-- CONTENIDO DESPLEGABLE -->
                                    <div id="<?= $cursoId ?>" class="hidden">
                                        <div class="accordion-content overflow-x-auto">
                                            <table class="data-table min-w-full">
                                                <thead>
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                            #</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                            RUN</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                            Nombre Completo</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                            Sexo</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                            Estado</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                            Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($alumnos as $i => $a): ?>
                                                        <tr class="cursor-pointer <?= !empty($a['deleted_at']) ? 'row-inactive' : '' ?>"
                                                            onclick="window.location='index.php?action=alumno_profile&id=<?= $a['id'] ?>'">
                                                            <td class="px-4 py-3 text-sm text-faint"><?= $i + 1 ?></td>
                                                            <td class="px-4 py-3 text-sm text-soft">
                                                                <?= htmlspecialchars($a['run'] . '-' . $a['codver']) ?>
                                                            </td>
                                                            <td class="px-4 py-3 text-sm text-strong capitalize font-medium">
                                                                <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat'] . ', ' . $a['nombre']) ?>
                                                            </td>
                                                            <td class="px-4 py-3 text-sm text-soft"><?= htmlspecialchars($a['sexo']) ?></td>
                                                            <td class="px-4 py-3 text-sm">
                                                                <?php if (!empty($a['deleted_at'])): ?>
                                                                    <span class="report-red report-badge inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold">
                                                                        Retirado <?= (new DateTime($a['deleted_at']))->format('d/m/Y') ?>
                                                                    </span>
                                                                <?php else: ?>
                                                                    <span class="report-emerald report-badge inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold">
                                                                        ✅ Activo
                                                                    </span>
                                                                <?php endif ?>
                                                            </td>
                                                            <td class="px-4 py-3 text-sm space-x-3"
                                                                onclick="event.stopPropagation()">
                                                                <a href="index.php?action=alumno_edit&id=<?= $a['id'] ?>"
                                                                    class="link-action font-medium">Editar</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            <?php endforeach ?>
                        </div>
                    </section>

                <?php endforeach ?>

            <?php else: ?>
                <div class="text-center text-faint py-12">No hay alumnos matriculados en este año.</div>
            <?php endif ?>

        <?php else: ?>
            <!-- Estado inicial sin año seleccionado -->
            <div class="text-center py-16 text-faint">
                <p class="text-5xl mb-4">📋</p>
                <p class="text-lg">Selecciona un año escolar para ver el listado de alumnos.</p>
            </div>
        <?php endif ?>

        <!-- VOLVER -->
        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=dashboard"
                class="btn-secondary inline-block rounded-md px-4 py-2 text-sm font-semibold shadow">
                ⬅️ Volver al Dashboard
            </a>
        </div>

    </div>
</main>

<script>
    function toggleCurso(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        const isOpen = !content.classList.contains('hidden');

        if (isOpen) {
            content.style.maxHeight = content.scrollHeight + 'px';
            requestAnimationFrame(() => {
                content.style.transition = 'max-height 0.35s ease, opacity 0.3s ease';
                content.style.maxHeight = '0';
                content.style.opacity = '0';
                content.style.overflow = 'hidden';
            });
            setTimeout(() => {
                content.classList.add('hidden');
                content.style.maxHeight = '';
                content.style.opacity = '';
            }, 350);
            icon.style.transform = 'rotate(0deg)';
        } else {
            content.classList.remove('hidden');
            content.style.maxHeight = '0';
            content.style.opacity = '0';
            content.style.overflow = 'hidden';
            content.style.transition = 'max-height 0.35s ease, opacity 0.3s ease';
            requestAnimationFrame(() => {
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
            });
            setTimeout(() => {
                content.style.maxHeight = 'none';
                content.style.overflow = '';
            }, 360);
            icon.style.transform = 'rotate(180deg)';
        }
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>