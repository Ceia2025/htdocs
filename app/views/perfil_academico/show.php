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

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="bg-gray-800 border-b border-gray-700 shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white">
                    Perfil Académico:
                    <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                </h1>
                <p class="text-gray-400 mt-2">
                    Curso: <strong><?= htmlspecialchars($curso['nombre']) ?></strong> |
                    Año: <strong><?= htmlspecialchars($anio['anio']) ?></strong> |
                    Fecha Matrícula: <?= htmlspecialchars($matricula['fecha_matricula']) ?>
                </p>
            </div>
        </header>

        <!-- MAIN -->
        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- Navegación -->
            <div class="flex space-x-4 mb-6">
                <a href="index.php?action=alumno_profile&id=<?= $alumno['id'] ?>"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">👤 Perfil Alumno</a>
                <a href="index.php?action=matriculas"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">⬅ Volver</a>
            </div>

            <!-- Secciones -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Notas -->
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow">






                    <!-- 📚 SECCIÓN DE NOTAS CON SEMESTRES -->
                    <section class="mt-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-indigo-600/20 p-3 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6l4 2m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-100 tracking-tight">Notas del Alumno</h2>
                        </div>

                        <?php
                        require_once __DIR__ . "/../../models/Nota.php";
                        $notaModel = new Nota();

                        // 🔹 Detectar semestre actual y estado
                        $mesActual = intval(date('n'));
                        $semestreActual = null;
                        $estadoSemestre = '';

                        if ($mesActual >= 3 && $mesActual <= 7) {
                            $semestreActual = 1;
                            $estadoSemestre = 'Primer semestre en curso';
                        } elseif ($mesActual >= 8 && $mesActual <= 12) {
                            $semestreActual = 2;
                            $estadoSemestre = 'Segundo semestre en curso';
                        } else {
                            $semestreActual = 2;
                            $estadoSemestre = 'Fuera de plazo (semestre cerrado)';
                        }

                        // 🔹 Semestre seleccionado manualmente (si viene por GET)
                        $semestreSeleccionado = intval($_GET['semestre'] ?? $semestreActual);

                        // 🔹 Obtener notas filtradas por semestre
                        $notas = $notaModel->getByMatriculaAndSemestre($matricula['id'], $semestreSeleccionado);
                        ?>

                        <!-- 🔽 Selector de semestre -->
                        <form method="get" class="mb-6 flex flex-wrap items-center gap-3">
                            <input type="hidden" name="action" value="perfil_academico">
                            <input type="hidden" name="matricula_id" value="<?= $matricula['id'] ?>">
                            <label class="text-gray-300 font-semibold">Seleccionar semestre:</label>
                            <select name="semestre"
                                class="bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                                <option value="1" <?= $semestreSeleccionado == 1 ? 'selected' : '' ?>>1° Semestre
                                    (Marzo–Julio)</option>
                                <option value="2" <?= $semestreSeleccionado == 2 ? 'selected' : '' ?>>2° Semestre
                                    (Agosto–Diciembre)</option>
                            </select>
                            <button
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                                Ver Notas
                            </button>
                        </form>

                        <p class="text-sm italic text-indigo-400 mb-4"><?= htmlspecialchars($estadoSemestre) ?></p>

                        <?php if (!empty($asignaturas)): ?>
                            <div
                                class="overflow-hidden bg-gray-800/80 backdrop-blur-sm border border-gray-700 rounded-2xl shadow-2xl">
                                <table class="min-w-full text-sm text-gray-300">
                                    <thead class="bg-gray-700/80 text-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 text-left font-semibold uppercase tracking-wider">
                                                Asignatura</th>
                                            <th class="px-6 py-3 text-center font-semibold uppercase tracking-wider">Notas
                                            </th>
                                            <th class="px-6 py-3 text-center font-semibold uppercase tracking-wider">
                                                Promedio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sumaGeneral = 0;
                                        $countGeneral = 0;

                                        foreach ($asignaturas as $asig):
                                            $notasAsig = array_filter($notas, fn($n) => $n['asignatura_nombre'] === $asig['nombre']);
                                            $promedio = 0;
                                            $total = 0;
                                            $cant = 0;

                                            foreach ($notasAsig as $n) {
                                                if (is_numeric($n['nota'])) {
                                                    $total += $n['nota'];
                                                    $cant++;
                                                }
                                            }

                                            $promedio = $cant > 0 ? $total / $cant : null;
                                            if ($promedio !== null) {
                                                $sumaGeneral += $promedio;
                                                $countGeneral++;
                                            }
                                            ?>
                                            <tr
                                                class="border-b border-gray-700 hover:bg-gray-700/40 transition-colors duration-200">
                                                <td class="px-6 py-3 font-medium text-gray-100">
                                                    <?= htmlspecialchars($asig['nombre']) ?></td>
                                                <td class="px-6 py-3 text-center">
                                                    <?php if (!empty($notasAsig)): ?>
                                                        <?php foreach ($notasAsig as $n): ?>
                                                            <span
                                                                class="inline-block bg-gray-700 text-indigo-300 px-2 py-1 rounded-lg mx-1 mb-1">
                                                                <?= htmlspecialchars(number_format($n['nota'], 1)) ?>
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

                            <!-- 📈 Promedio semestral -->
                            <div class="mt-6 text-center text-gray-300">
                                <p class="text-lg font-semibold">
                                    Promedio <?= $semestreSeleccionado ?>° Semestre:
                                    <span class="text-indigo-400">
                                        <?= $countGeneral > 0 ? number_format($sumaGeneral / $countGeneral, 1) : '-' ?>
                                    </span>
                                </p>
                            </div>

                            <!-- 📘 Promedio anual -->
                            <?php
                            $notasSem1 = $notaModel->getByMatriculaAndSemestre($matricula['id'], 1);
                            $notasSem2 = $notaModel->getByMatriculaAndSemestre($matricula['id'], 2);

                            $promSem1 = 0;
                            $promSem2 = 0;
                            $cnt1 = 0;
                            $cnt2 = 0;

                            foreach ([1 => $notasSem1, 2 => $notasSem2] as $sem => $ns) {
                                $total = 0;
                                $c = 0;
                                foreach ($ns as $n) {
                                    if (is_numeric($n['nota'])) {
                                        $total += $n['nota'];
                                        $c++;
                                    }
                                }
                                if ($c > 0) {
                                    if ($sem == 1) {
                                        $promSem1 = $total / $c;
                                        $cnt1 = $c;
                                    }
                                    if ($sem == 2) {
                                        $promSem2 = $total / $c;
                                        $cnt2 = $c;
                                    }
                                }
                            }

                            if ($cnt1 > 0 && $cnt2 > 0):
                                $promFinal = ($promSem1 + $promSem2) / 2;
                                ?>
                                <div class="mt-10 bg-gray-800 border border-gray-700 rounded-2xl p-6 text-center shadow-lg">
                                    <h3 class="text-xl font-semibold text-gray-100 mb-2">📊 Promedio Final Anual</h3>
                                    <p class="text-gray-300">
                                        1° Sem: <span class="text-indigo-400"><?= number_format($promSem1, 1) ?></span> |
                                        2° Sem: <span class="text-indigo-400"><?= number_format($promSem2, 1) ?></span>
                                    </p>
                                    <p class="mt-2 text-lg font-bold text-indigo-400">
                                        Promedio Final: <?= number_format($promFinal, 1) ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                        <?php else: ?>
                            <p class="text-gray-400 italic mt-4 text-center">No hay asignaturas registradas para este curso.
                            </p>
                        <?php endif; ?>
                    </section>









                </div>

                <!-- Asistencia -->
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow">
                    <h2 class="text-xl font-semibold text-indigo-400 mb-3">🕓 Asistencia</h2>
                    <p class="text-gray-400 italic">Próximamente: porcentajes y registros diarios.</p>
                </div>

                <!-- Anotaciones -->
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow">
                    <h2 class="text-xl font-semibold text-indigo-400 mb-3">📝 Anotaciones</h2>
                    <p class="text-gray-400 italic">Próximamente: positivas y negativas.</p>
                </div>

                <!-- Atrasos -->
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow">
                    <h2 class="text-xl font-semibold text-indigo-400 mb-3">⏰ Atrasos</h2>
                    <p class="text-gray-400 italic">Próximamente: registro de retrasos a clases.</p>
                </div>
            </div>

        </main>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>