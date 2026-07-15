<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$matricula_id = $_GET['matricula_id'] ?? null;
if (!$matricula_id) {
    echo "<p class='text-center text-danger mt-10'>No se recibió el ID de matrícula.</p>";
    exit;
}

require_once __DIR__ . "/../../models/Nota.php";
require_once __DIR__ . "/../../models/CursoAsignatura.php";

$notaModel = new Nota();
$cursoAsignaturaModel = new CursoAsignatura();

$matriculaModel = new Matricula();
$matricula = $matriculaModel->getById($matricula_id);
$asignaturas = $cursoAsignaturaModel->getAsignaturasPorCurso($matricula['curso_id']);

$mesActual = intval(date('n'));
$semestreActual = ($mesActual >= 3 && $mesActual <= 7) ? 1 : 2;
$semestreSeleccionado = intval($_GET['semestre'] ?? $semestreActual);
$notas = $notaModel->getByMatriculaAndSemestre($matricula_id, $semestreSeleccionado);
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-1">Notas</p>
        <h1 class="text-2xl font-bold text-strong font-display">Notas del Alumno</h1>
    </div>
</header>

<main>
    <div class="max-w-6xl mx-auto px-4 py-8">

        <!-- Selector de semestre -->
        <form method="get" action="index.php" class="mb-6 flex flex-wrap items-center gap-3">
            <input type="hidden" name="action" value="notas_index">
            <input type="hidden" name="matricula_id" value="<?= $matricula['id'] ?>">
            <label class="text-soft font-semibold text-sm">Semestre:</label>
            <select name="semestre" class="input-field rounded-lg px-3 py-2 text-sm">
                <option value="1" <?= $semestreSeleccionado == 1 ? 'selected' : '' ?>>1° Semestre</option>
                <option value="2" <?= $semestreSeleccionado == 2 ? 'selected' : '' ?>>2° Semestre</option>
            </select>
            <button type="submit" class="btn-primary px-4 py-2 rounded-lg font-semibold transition text-sm">
                Ver
            </button>
        </form>

        <div class="panel overflow-x-auto rounded-xl shadow-lg">
            <table class="data-table min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-muted">Asignatura</th>
                        <th class="px-6 py-3 text-center font-semibold text-muted">Notas</th>
                        <th class="px-6 py-3 text-center font-semibold text-muted">Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $promsAsig = [];

                    foreach ($asignaturas as $asig):
                        $notasAsig = array_filter($notas, fn($n) => $n['asignatura_nombre'] === $asig['nombre']);
                        $promedio  = Nota::promedio($notasAsig);
                        if ($promedio !== null) $promsAsig[] = $promedio;
                        ?>
                        <tr>
                            <td class="px-6 py-3 font-medium text-strong"><?= htmlspecialchars($asig['nombre']) ?></td>
                            <td class="px-6 py-3 text-center">
                                <?php if ($notasAsig): ?>
                                    <?php foreach ($notasAsig as $n): ?>
                                        <span class="inline-block report-badge text-xs px-2 py-1 rounded-lg mx-1 mb-1">
                                            <?= Nota::fmt((float)$n['nota']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-faint italic text-xs">Sin notas</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3 text-center text-accent font-semibold">
                                <?= Nota::fmt($promedio, '-') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-center">
            <p class="text-soft font-semibold">
                Promedio <?= $semestreSeleccionado ?>° Semestre:
                <span class="text-accent font-bold">
                    <?= Nota::fmt(Nota::promedio($promsAsig), '-') ?>
                </span>
            </p>
        </div>

        <?php
        $semestreAbierto = ($semestreSeleccionado == 1 && $mesActual >= 3 && $mesActual <= 7)
            || ($semestreSeleccionado == 2 && $mesActual >= 8 && $mesActual <= 12);
        ?>

        <div class="mt-8 flex justify-center">
            <?php if ($semestreAbierto): ?>
                <a href="index.php?action=notas_createGroup&curso_id=<?= $matricula['curso_id'] ?>&anio_id=<?= $matricula['anio_id'] ?>"
                    class="btn-primary flex items-center gap-2 px-6 py-2 rounded-xl font-semibold shadow-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar Notas
                </a>
            <?php else: ?>
                <p class="text-muted italic text-center text-sm">📅 Este semestre está cerrado. Solo se pueden visualizar notas.</p>
            <?php endif; ?>
        </div>

        <div class="mt-8 text-center">
            <a href="index.php?action=matriculas"
                class="btn-secondary px-4 py-2 rounded-lg shadow font-semibold text-sm">⬅ Volver</a>
        </div>
    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>