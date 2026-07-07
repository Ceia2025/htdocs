<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<header class="page-header">
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-1">Notas</p>
            <h1 class="text-2xl font-bold text-strong font-display"><?= htmlspecialchars($curso['nombre']) ?></h1>
            <p class="text-sm text-muted mt-0.5">Año <?= htmlspecialchars($anio['anio']) ?></p>
        </div>
        <a href="index.php?action=notas_panel&anio_id=<?= $anioId ?>"
           class="btn-secondary text-sm px-4 py-2 rounded-lg transition">
            ← Volver
        </a>
    </div>
</header>

<main class="mx-auto max-w-4xl px-4 py-8 sm:px-6">

    <!-- Selector de semestre -->
    <div class="flex items-center gap-3 mb-6">
        <span class="text-xs text-muted font-semibold uppercase tracking-wider">Semestre:</span>
        <?php foreach ([1 => '1° Semestre', 2 => '2° Semestre'] as $num => $label): ?>
            <a href="index.php?action=notas_panel_asignaturas&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&semestre=<?= $num ?>"
               class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition
                      <?= $semestre == $num
                          ? 'text-azul-oscuro font-bold'
                          : 'btn-secondary' ?>"
               <?php if ($semestre == $num): ?> style="background: var(--azul-vivo); border-color: var(--azul-vivo); color: #fff;" <?php endif; ?>>
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($asignaturas)): ?>
        <div class="panel rounded-xl px-6 py-12 text-center">
            <p class="text-muted">No hay asignaturas registradas para este curso.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach ($asignaturas as $asig): ?>
                <a href="index.php?action=notas_panel_asignatura&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&asignatura_id=<?= $asig['id'] ?>&semestre=<?= $semestre ?>"
                   class="dashboard-card group flex items-center justify-between rounded-xl px-5 py-4 transition">
                    <span class="text-sm font-semibold text-strong group-hover:text-accent transition">
                        <?= htmlspecialchars($asig['nombre']) ?>
                    </span>
                    <svg class="w-4 h-4 text-faint group-hover:text-accent transition"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>