<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// 📘 Parámetro: ID de matrícula (obligatorio)
$matricula_id = $_GET['matricula_id'] ?? null;
if (!$matricula_id) {
    echo "<p class='text-center text-red-400 mt-10'>No se recibió el ID de matrícula.</p>";
    exit;
}

require_once __DIR__ . "/../../models/Nota.php";
require_once __DIR__ . "/../../models/CursoAsignatura.php";

$notaModel = new Nota();
$cursoAsignaturaModel = new CursoAsignatura();

// 🔹 Obtener asignaturas del curso del alumno
$matriculaModel = new Matricula();
$matricula = $matriculaModel->getById($matricula_id);
$asignaturas = $cursoAsignaturaModel->getAsignaturasPorCurso($matricula['curso_id']);

// 🔹 Detectar semestre actual
$mesActual = intval(date('n'));
$semestreActual = ($mesActual >= 3 && $mesActual <= 7) ? 1 : 2;

// 🔹 Semestre seleccionado (GET o por defecto)
$semestreSeleccionado = intval($_GET['semestre'] ?? $semestreActual);

// 🔹 Obtener notas de ese semestre
$notas = $notaModel->getByMatriculaAndSemestre($matricula_id, $semestreSeleccionado);
?>

<body class="bg-gray-900 text-white">
    <div class="max-w-6xl mx-auto px-4 py-8">

        <h1 class="text-3xl font-bold mb-6 text-indigo-400">📘 Notas del Alumno</h1>

        <!-- 🔽 Selector de semestre -->
        <form method="get" action="index.php" class="mb-6 flex flex-wrap items-center gap-3">
            <input type="hidden" name="action" value="notas_index">
            <input type="hidden" name="matricula_id" value="<?= $matricula['id'] ?>">
            <label class="text-gray-300 font-semibold">Semestre:</label>
            <select name="semestre" class="bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                <option value="1" <?= $semestreSeleccionado == 1 ? 'selected' : '' ?>>1° Semestre</option>
                <option value="2" <?= $semestreSeleccionado == 2 ? 'selected' : '' ?>>2° Semestre</option>
            </select>
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg font-semibold transition">
                Ver
            </button>
        </form>

        <div class="overflow-x-auto bg-gray-800 rounded-xl shadow-lg border border-gray-700">
            <table class="min-w-full text-sm text-gray-300">
                <thead class="bg-gray-700 text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Asignatura</th>
                        <th class="px-6 py-3 text-center font-semibold">Notas</th>
                        <th class="px-6 py-3 text-center font-semibold">Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sumaGeneral = 0;
                    $countGeneral = 0;

                    foreach ($asignaturas as $asig):
                        $notasAsig = array_filter($notas, fn($n) => $n['asignatura_nombre'] === $asig['nombre']);
                        $promedio = null;
                        if (count($notasAsig) > 0) {
                            $total = array_sum(array_column($notasAsig, 'nota'));
                            $promedio = $total / count($notasAsig);
                            $sumaGeneral += $promedio;
                            $countGeneral++;
                        }
                        ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-700/40 transition">
                            <td class="px-6 py-3 font-medium text-gray-100"><?= htmlspecialchars($asig['nombre']) ?></td>
                            <td class="px-6 py-3 text-center">
                                <?php if ($notasAsig): ?>
                                    <?php foreach ($notasAsig as $n): ?>
                                        <span class="inline-block bg-gray-700 text-indigo-300 px-2 py-1 rounded-lg mx-1 mb-1">
                                            <?= number_format($n['nota'], 1) ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-gray-500 italic">Sin notas</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3 text-center text-indigo-400 font-semibold">
                                <?= $promedio ? number_format($promedio, 1) : '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Promedio del semestre -->
        <div class="mt-6 text-center">
            <p class="text-lg text-gray-300 font-semibold">
                Promedio <?= $semestreSeleccionado ?>° Semestre:
                <span class="text-indigo-400">
                    <?= $countGeneral > 0 ? number_format($sumaGeneral / $countGeneral, 1) : '-' ?>
                </span>
            </p>
        </div>

        <!-- 🔹 BOTÓN AGREGAR NOTAS (solo si el semestre está abierto) -->
        <?php
        $semestreAbierto = ($semestreSeleccionado == 1 && $mesActual >= 3 && $mesActual <= 7)
            || ($semestreSeleccionado == 2 && $mesActual >= 8 && $mesActual <= 12);
        ?>

        <div class="mt-8 flex justify-center">
            <?php if ($semestreAbierto): ?>
                <a href="index.php?action=notas_createGroup&curso_id=<?= $matricula['curso_id'] ?>&anio_id=<?= $matricula['anio_id'] ?>"
                    class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 px-6 py-2 rounded-xl font-semibold text-white shadow-lg hover:shadow-indigo-500/30 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar Notas
                </a>
            <?php else: ?>
                <p class="text-gray-400 italic text-center">📅 Este semestre está cerrado. Solo se pueden visualizar notas.
                </p>
            <?php endif; ?>
        </div>

        <!-- VOLVER -->
        <div class="mt-8 text-center">
            <a href="index.php?action=matriculas"
                class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg shadow text-white font-semibold">⬅ Volver</a>
        </div>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>