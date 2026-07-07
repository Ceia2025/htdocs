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
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-1">Notas</p>
            <h1 class="text-2xl font-bold text-strong font-display">Panel de Notas</h1>
        </div>

        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="notas_panel">
            <select name="anio_id" onchange="this.form.submit()"
                class="input-field text-sm rounded-lg px-3 py-2">
                <?php foreach ($anios as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                        <?= $a['anio'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-8 sm:px-6">

    <?php if (empty($cursos)): ?>
        <div class="panel rounded-xl px-6 py-12 text-center">
            <p class="text-muted font-medium">
                <?= $esTodos ? 'No hay cursos registrados para este año.' : 'No tienes cursos asignados para este año.' ?>
            </p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($cursos as $c): ?>
                <a href="index.php?action=notas_panel_asignaturas&curso_id=<?= $c['curso_id'] ?>&anio_id=<?= $anioId ?>"
                   class="dashboard-card animate-fadeIn group flex flex-col gap-3 rounded-xl px-5 py-5 transition">

                    <div class="flex items-center justify-between">
                        <span class="text-base font-bold text-strong group-hover:text-accent transition font-display">
                            <?= htmlspecialchars($c['curso'] ?? $c['curso_nombre'] ?? '—') ?>
                        </span>
                        <svg class="w-5 h-5 text-faint group-hover:text-accent transition"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>

                    <?php if ($esTodos && !empty($c['docente_nombre'])): ?>
                        <p class="text-xs text-muted">
                            Profesor jefe:
                            <span class="text-soft">
                                <?= htmlspecialchars($c['docente_nombre'] . ' ' . $c['ape_paterno']) ?>
                            </span>
                        </p>
                    <?php elseif ($esTodos): ?>
                        <p class="text-xs text-warn italic">Sin profesor jefe asignado</p>
                    <?php endif; ?>

                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>