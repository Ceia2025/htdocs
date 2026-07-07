<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$notaValor = floatval($nota['nota']);
$aprobado = $notaValor >= 4.0;
$colorNota = $aprobado ? 'text-success' : 'text-danger';
?>

<header class="page-header">
    <div class="mx-auto max-w-lg px-4 py-6 sm:px-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Notas</p>
        <h1 class="text-2xl font-bold text-strong font-display">Editar Nota</h1>
        <p class="text-xs text-muted mt-0.5">Modifica los datos de la evaluación</p>
    </div>
</header>

<main>
    <div class="max-w-lg mx-auto px-4 py-8">

        <!-- Card info alumno -->
        <div class="panel rounded-xl px-5 py-4 mb-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-muted mb-1">Alumno</p>
                <p class="text-strong font-semibold">
                    <?= htmlspecialchars($nota['apepat'] . ' ' . $nota['apemat'] . ', ' . $nota['alumno_nombre']) ?>
                </p>
                <p class="text-xs text-accent mt-0.5">
                    <?= htmlspecialchars($nota['asignatura_nombre']) ?> · <?= $nota['semestre'] ?>° Semestre
                </p>
            </div>

            <div class="flex flex-col items-center panel rounded-xl px-5 py-3">
                <span class="text-xs text-muted mb-1">Nota actual</span>
                <span class="text-3xl font-bold <?= $colorNota ?>">
                    <?= number_format($notaValor, 1) ?>
                </span>
                <span class="text-xs mt-1 <?= $aprobado ? 'text-success' : 'text-danger' ?>">
                    <?= $aprobado ? 'Aprobado' : 'Reprobado' ?>
                </span>
            </div>
        </div>

        <!-- Formulario -->
        <form method="POST" action="index.php?action=notas_update&id=<?= $nota['id'] ?>"
            class="panel rounded-xl p-6 space-y-5">

            <input type="hidden" name="matricula_id" value="<?= htmlspecialchars($nota['matricula_id'] ?? '') ?>">
            <input type="hidden" name="semestre" value="<?= htmlspecialchars($nota['semestre'] ?? 1) ?>">

            <!-- Nota -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-2">
                    Nueva nota
                </label>
                <input type="number" name="nota" value="<?= htmlspecialchars($nota['nota']) ?>"
                    step="0.1" min="1" max="7" required
                    class="input-field w-full rounded-lg px-4 py-2.5 text-lg font-semibold text-center">
                <p class="text-xs text-faint mt-1 text-center">Rango válido: 1.0 – 7.0</p>
            </div>

            <!-- Fecha -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-2">
                    Fecha de evaluación
                </label>
                <input type="date" name="fecha" value="<?= htmlspecialchars(substr($nota['fecha'], 0, 10)) ?>"
                    max="<?= date('Y-m-d') ?>"
                    class="input-field w-full rounded-lg px-4 py-2.5">
            </div>

            <!-- Semestre (visual) -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-2">Semestre</label>
                <div class="flex gap-3">
                    <?php foreach ([1 => '1° Semestre', 2 => '2° Semestre'] as $num => $label):
                        $activo = $nota['semestre'] == $num; ?>
                        <div class="flex-1 text-center px-4 py-2.5 rounded-lg border text-sm font-semibold
                                <?= $activo
                                    ? 'border-azul-vivo text-accent'
                                    : 'btn-secondary' ?>"
                             <?php if ($activo): ?> style="background: var(--accent-title-soft); border-color: var(--azul-vivo);" <?php endif; ?>>
                            <?= $label ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 pt-2">
                <a href="javascript:history.back()"
                    class="btn-secondary flex-1 text-center px-4 py-2.5 font-semibold rounded-xl transition text-sm">
                    ← Volver
                </a>
                <input type="hidden" name="curso_id" value="<?= htmlspecialchars($nota['curso_id'] ?? '') ?>">
                <input type="hidden" name="anio_id" value="<?= htmlspecialchars($nota['anio_id'] ?? '') ?>">
                <input type="hidden" name="asignatura_id" value="<?= htmlspecialchars($nota['asignatura_id'] ?? '') ?>">
                <button type="submit"
                    class="btn-accent flex-1 flex items-center justify-center gap-2 px-4 py-2.5 font-bold rounded-xl transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar cambios
                </button>
            </div>
        </form>

    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>