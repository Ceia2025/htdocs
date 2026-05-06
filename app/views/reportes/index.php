<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user   = $_SESSION['user'];
$nombre = $user['nombre'];
$rol    = $user['rol'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Definir los módulos de reporte disponibles
$modulos = [
    [
        'titulo'      => 'Reporte de Asistencia',
        'descripcion' => 'Exporta asistencia mensual y acumulada por curso o general. Disponible en PDF y CSV.',
        'icono'       => '📋',
        'color'       => 'emerald',
        'url'         => 'index.php?action=reportes_asistencia',
        'disponible'  => true,
        'tags'        => ['PDF', 'CSV', 'Por curso', 'General'],
    ],
    [
        'titulo'      => 'Reporte de Atrasos',
        'descripcion' => 'Exporta el historial de atrasos por curso, alumno y semestre.',
        'icono'       => '⏰',
        'color'       => 'amber',
        'url'         => '#',
        'disponible'  => false,
        'tags'        => ['Próximamente'],
    ],
    [
        'titulo'      => 'Reporte de Notas',
        'descripcion' => 'Exporta el rendimiento académico por curso y asignatura.',
        'icono'       => '📝',
        'color'       => 'blue',
        'url'         => '#',
        'disponible'  => false,
        'tags'        => ['Próximamente'],
    ],
    [
        'titulo'      => 'Reporte de Matrícula',
        'descripcion' => 'Exporta nómina de alumnos matriculados por curso y año.',
        'icono'       => '🎓',
        'color'       => 'purple',
        'url'         => '#',
        'disponible'  => false,
        'tags'        => ['Próximamente'],
    ],
];

// Colores por tipo
$colores = [
    'emerald' => [
        'border'  => 'border-emerald-700/50',
        'icon_bg' => 'bg-emerald-900/40',
        'icon'    => 'text-emerald-400',
        'badge'   => 'bg-emerald-900/30 text-emerald-400 border-emerald-700/40',
        'btn'     => 'bg-emerald-700 hover:bg-emerald-600',
        'glow'    => 'hover:shadow-emerald-900/30',
    ],
    'amber'  => [
        'border'  => 'border-amber-700/40',
        'icon_bg' => 'bg-amber-900/30',
        'icon'    => 'text-amber-400',
        'badge'   => 'bg-amber-900/30 text-amber-400 border-amber-700/40',
        'btn'     => 'bg-amber-700 hover:bg-amber-600',
        'glow'    => 'hover:shadow-amber-900/30',
    ],
    'blue'   => [
        'border'  => 'border-blue-700/40',
        'icon_bg' => 'bg-blue-900/30',
        'icon'    => 'text-blue-400',
        'badge'   => 'bg-blue-900/30 text-blue-400 border-blue-700/40',
        'btn'     => 'bg-blue-700 hover:bg-blue-600',
        'glow'    => 'hover:shadow-blue-900/30',
    ],
    'purple' => [
        'border'  => 'border-purple-700/40',
        'icon_bg' => 'bg-purple-900/30',
        'icon'    => 'text-purple-400',
        'badge'   => 'bg-purple-900/30 text-purple-400 border-purple-700/40',
        'btn'     => 'bg-purple-700 hover:bg-purple-600',
        'glow'    => 'hover:shadow-purple-900/30',
    ],
];
?>

<body class="h-full bg-gray-900">
<div class="min-h-full">

    <!-- HEADER -->
    <header class="bg-gray-800 border-b border-white/10">
        <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-emerald-400 mb-0.5">Sistema SAAT</p>
                <h1 class="text-2xl font-bold text-white">Centro de Reportes</h1>
                <p class="text-sm text-gray-400 mt-0.5">
                    Selecciona el tipo de reporte que deseas generar o exportar
                </p>
            </div>
            <a href="index.php?action=dashboard"
                class="flex items-center gap-2 text-sm text-gray-400 hover:text-white border border-gray-600
                       hover:border-gray-400 px-4 py-2 rounded-lg transition">
                ⬅ Dashboard
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">

        <!-- CONTADOR -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-400">
                <span class="text-white font-semibold">
                    <?= count(array_filter($modulos, fn($m) => $m['disponible'])) ?>
                </span>
                módulo<?= count(array_filter($modulos, fn($m) => $m['disponible'])) !== 1 ? 's' : '' ?>
                disponible<?= count(array_filter($modulos, fn($m) => $m['disponible'])) !== 1 ? 's' : '' ?>
                de <?= count($modulos) ?> en total
            </p>
        </div>

        <!-- GRID DE CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-5">
            <?php foreach ($modulos as $modulo):
                $c = $colores[$modulo['color']];
            ?>
            <div class="group bg-gray-800 border <?= $c['border'] ?> rounded-2xl overflow-hidden shadow
                        transition-all duration-200 hover:shadow-lg <?= $c['glow'] ?>
                        <?= !$modulo['disponible'] ? 'opacity-60' : '' ?>">

                <!-- Cabecera -->
                <div class="px-6 py-5 border-b border-gray-700/60 flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl <?= $c['icon_bg'] ?>
                                flex items-center justify-center text-2xl">
                        <?= $modulo['icono'] ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-white font-bold text-base">
                                <?= htmlspecialchars($modulo['titulo']) ?>
                            </h3>
                            <?php if (!$modulo['disponible']): ?>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                             bg-gray-700 text-gray-400 border border-gray-600">
                                    Próximamente
                                </span>
                            <?php else: ?>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                             bg-emerald-900/40 text-emerald-400 border border-emerald-700/40">
                                    Disponible
                                </span>
                            <?php endif ?>
                        </div>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            <?= htmlspecialchars($modulo['descripcion']) ?>
                        </p>
                    </div>
                </div>

                <!-- Tags + Botón -->
                <div class="px-6 py-4 flex items-center justify-between gap-3">
                    <!-- Tags -->
                    <div class="flex flex-wrap gap-1.5">
                        <?php foreach ($modulo['tags'] as $tag): ?>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full border <?= $c['badge'] ?>">
                                <?= htmlspecialchars($tag) ?>
                            </span>
                        <?php endforeach ?>
                    </div>

                    <!-- Botón -->
                    <?php if ($modulo['disponible']): ?>
                        <a href="<?= $modulo['url'] ?>"
                            class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2
                                   <?= $c['btn'] ?> text-white text-sm font-semibold
                                   rounded-xl shadow transition">
                            Ver reporte
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <button disabled
                            class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2
                                   bg-gray-700 text-gray-500 text-sm font-semibold
                                   rounded-xl cursor-not-allowed">
                            Próximamente
                        </button>
                    <?php endif ?>
                </div>
            </div>
            <?php endforeach ?>
        </div>

    </main>
</div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>