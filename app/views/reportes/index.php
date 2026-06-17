<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Definir los módulos de reporte disponibles
$modulos = [
    [
        'titulo' => 'Reporte de Asistencia',
        'descripcion' => 'Exporta asistencia mensual y acumulada por curso o general. Disponible en PDF y CSV.',
        'icono' => '📋',
        'color' => 'emerald',
        'url' => 'index.php?action=reportes_asistencia',
        'disponible' => true,
        'tags' => ['PDF', 'CSV', 'Por curso', 'General'],
    ],
    [
        'titulo' => 'Reporte de Atrasos',
        'descripcion' => 'Exporta el historial de atrasos por curso, alumno y semestre.',
        'icono' => '⏰',
        'color' => 'amber',
        'url' => '#',
        'disponible' => false,
        'tags' => ['Próximamente'],
    ],
    [
        'titulo' => 'Reporte de Notas',
        'descripcion' => 'Exporta informes de notas por alumno o por asignatura en PDF.',
        'icono' => '📝',
        'color' => 'blue',
        'url' => 'index.php?action=reportes_notas',  // ← URL correcta
        'disponible' => true,                                // ← activado
        'tags' => ['PDF', 'Por alumno', 'Por asignatura'],
    ],
    [
        'titulo' => 'Reporte de Matrícula',
        'descripcion' => 'Exporta nómina de alumnos con matrícula activa por curso y año.',
        'icono' => '🎓',
        'color' => 'purple',
        'url' => 'index.php?action=reportes_matricula',
        'disponible' => true,
        'tags' => ['PDF', 'Por curso', 'General'],
    ],
    [
        'titulo' => 'Reporte Alumnos Etnia',
        'descripcion' => 'Exporta filtro de Alumnos con distintas Etnias, Separadas por curso y año.',
        'icono' => '🧑🏽',
        'color' => 'yellow',
        'url' => 'index.php?action=reportes_etnia',
        'disponible' => true,          // ← activar
        'tags' => ['PDF', 'CSV', 'Por curso', 'Por etnia'],
    ],
    [
        'titulo' => 'Reporte Alumnos Pie',
        'descripcion' => 'Exporta el listado de alumnos PIE por curso y categoría, con porcentajes.',
        'icono' => '👦',
        'color' => 'cyan',
        'url' => 'index.php?action=reportes_pie',
        'disponible' => true,
        'tags' => ['PDF', 'Por curso', 'Por categoría'],
    ],
    [
        'titulo' => 'Alumno Regular',
        'descripcion' => 'Descarga en formato PDF el Certificado de Alumno Regular, con o sin detalle de asistencia',
        'icono' => '👼',
        'color' => 'indigo',
        'url' => 'index.php?action=reportes_cert_alumno_regular',
        'disponible' => true,          // ← activar
        'tags' => ['PDF', 'Normal', 'Con Asistencia',],
    ],
    [
        'titulo' => 'Alumnos Retirados',
        'descripcion' => 'Exporta el listado de alumnos retirados por curso, con fecha de retiro y porcentaje.',
        'icono' => '🚪',
        'color' => 'rose',
        'url' => 'index.php?action=reportes_retirados',
        'disponible' => true,
        'tags' => ['Por curso', 'General', '%'],
    ],
];
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Sistema SAAT</p>
            <h1 class="text-2xl font-bold text-strong font-display">Centro de Reportes</h1>
            <p class="text-sm text-muted mt-0.5">
                Selecciona el tipo de reporte que deseas generar o exportar
            </p>
        </div>
        <a href="index.php?action=dashboard" class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            Regresar a Inicio
        </a>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">

    <!-- CONTADOR -->
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-muted">
            <span class="text-strong font-semibold">
                <?= count(array_filter($modulos, fn($m) => $m['disponible'])) ?>
            </span>
            módulo<?= count(array_filter($modulos, fn($m) => $m['disponible'])) !== 1 ? 's' : '' ?>
            disponible<?= count(array_filter($modulos, fn($m) => $m['disponible'])) !== 1 ? 's' : '' ?>
            de <?= count($modulos) ?> en total
        </p>
    </div>

    <!-- GRID DE CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-5">
        <?php foreach ($modulos as $modulo): ?>
            <div class="report-card report-<?= $modulo['color'] ?> group rounded-2xl overflow-hidden
                <?= !$modulo['disponible'] ? 'opacity-60' : '' ?>">

                <!-- Cabecera -->
                <div class="px-6 py-5 border-b divider-soft flex items-start gap-4">
                    <div class="report-icon-bg flex-shrink-0 w-12 h-12 rounded-xl
                        flex items-center justify-center text-2xl">
                        <?= $modulo['icono'] ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-strong font-bold text-base font-display">
                                <?= htmlspecialchars($modulo['titulo']) ?>
                            </h3>
                            <?php if (!$modulo['disponible']): ?>
                                <span class="badge-soon text-xs font-semibold px-2 py-0.5 rounded-full">
                                    Próximamente
                                </span>
                            <?php else: ?>
                                <span class="badge-available text-xs font-semibold px-2 py-0.5 rounded-full">
                                    Disponible
                                </span>
                            <?php endif ?>
                        </div>
                        <p class="text-sm text-muted leading-relaxed">
                            <?= htmlspecialchars($modulo['descripcion']) ?>
                        </p>
                    </div>
                </div>

                <!-- Tags + Botón -->
                <div class="px-6 py-4 flex items-center justify-between gap-3">
                    <!-- Tags -->
                    <div class="flex flex-wrap gap-1.5">
                        <?php foreach ($modulo['tags'] as $tag): ?>
                            <span class="report-badge text-xs font-medium px-2 py-0.5 rounded-full">
                                <?= htmlspecialchars($tag) ?>
                            </span>
                        <?php endforeach ?>
                    </div>

                    <!-- Botón -->
                    <?php if ($modulo['disponible']): ?>
                        <a href="<?= $modulo['url'] ?>" class="report-btn flex-shrink-0 inline-flex items-center gap-2 px-4 py-2
                           text-sm font-semibold rounded-xl shadow transition">
                            Ver reporte
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <button disabled class="btn-disabled flex-shrink-0 inline-flex items-center gap-2 px-4 py-2
                           text-sm font-semibold rounded-xl">
                            Próximamente
                        </button>
                    <?php endif ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>

</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>