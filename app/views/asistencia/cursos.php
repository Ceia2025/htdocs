<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Lee un ícono de app/public/svg/{key}.svg y lo devuelve listo para inyectar inline,
// con la clase de tamaño Tailwind ya insertada en el <svg>.
function saat_icon(string $key, string $classes = 'w-4 h-4'): string
{
    $path = __DIR__ . "/../../public/svg/{$key}.svg";
    if (!file_exists($path)) {
        return '';
    }
    $svg = file_get_contents($path);
    return preg_replace('/<svg /', '<svg class="' . htmlspecialchars($classes) . '" ', $svg, 1);
}
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Asistencia</p>
            <h1 class="text-2xl font-bold text-strong font-display">Seleccionar Curso</h1>
            <p class="text-sm text-muted mt-0.5">Elige un curso para tomar asistencia, ver el resumen o el
                libro de clases</p>
        </div>
        <a href="index.php?action=dashboard"
            class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            <?= saat_icon('arrow-left', 'w-4 h-4') ?>
            Inicio
        </a>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

    <?php if (empty($cursos)): ?>
        <div class="text-center py-20 text-muted">
            <p class="mb-4"><?= saat_icon('inbox', 'w-12 h-12 mx-auto') ?></p>
            <p class="text-lg font-medium">No hay cursos disponibles para este año.</p>
        </div>
    <?php else: ?>

        <!-- Contador -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-muted">
                <span class="text-strong font-semibold"><?= count($cursos) ?></span> cursos disponibles
            </p>
        </div>

        <!-- Grid de cursos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($cursos as $curso): ?>
                <div class="dashboard-card animate-fadeIn group rounded-2xl overflow-hidden transition-all duration-200">

                    <!-- Cabecera de la tarjeta -->
                    <div class="px-5 py-4 border-b divider-soft flex items-center gap-3">
                        <div class="card-icon-badge w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center">
                            <?= saat_icon('cap', 'w-5 h-5') ?>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-strong font-bold text-base truncate font-display">
                                <?= htmlspecialchars($curso['nombre']) ?>
                            </h3>
                            <?php if (isset($ultimasFechas[$curso['id']])): ?>
                                <p class="text-xs text-muted mt-0.5 flex items-center gap-1">
                                    <?= saat_icon('calendar', 'w-3 h-3 text-faint flex-shrink-0') ?>
                                    Última lista:
                                    <span class="text-strong font-medium">
                                        <?= date('d/m/Y', strtotime($ultimasFechas[$curso['id']])) ?>
                                    </span>
                                </p>
                            <?php else: ?>
                                <p class="text-xs text-faint mt-0.5">Sin asistencia registrada</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Acciones: 3 botones -->
                    <div class="px-5 py-4 grid grid-cols-3 gap-2">

                        <!-- Tomar asistencia -->
                        <a href="index.php?action=form_asistencia_masiva&curso_id=<?= $curso['id'] ?>&anio_id=<?= $anioId ?>"
                            class="btn-soft-info flex flex-col items-center gap-1.5 px-2 py-3 rounded-xl transition text-center">
                            <?= saat_icon('edit', 'w-5 h-5') ?>
                            <span class="text-xs font-semibold leading-tight">Tomar Asistencia</span>
                        </a>

                        <!-- Ver resumen -->
                        <a href="index.php?action=resumen_curso&curso_id=<?= $curso['id'] ?>&anio_id=<?= $anioId ?>"
                            class="btn-soft-success flex flex-col items-center gap-1.5 px-2 py-3 rounded-xl transition text-center">
                            <?= saat_icon('chart', 'w-5 h-5') ?>
                            <span class="text-xs font-semibold leading-tight">Ver Resumen</span>
                        </a>

                        <!-- Libro de clases -->
                        <a href="index.php?action=libro_clases&curso_id=<?= $curso['id'] ?>&anio_id=<?= $anioId ?>"
                            class="btn-soft-warn flex flex-col items-center gap-1.5 px-2 py-3 rounded-xl transition text-center">
                            <?= saat_icon('book', 'w-5 h-5') ?>
                            <span class="text-xs font-semibold leading-tight">Libro de Clases</span>
                        </a>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>