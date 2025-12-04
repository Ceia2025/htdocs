<!-- SECCIÓN DE NOTAS CON SEMESTRES -->
<section class="">
    <div class="flex items-center gap-3 mb-6">
        <div class="bg-indigo-600/20 p-3 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6v6l4 2m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-100 tracking-tight">Notas del Alumno</h2>
    </div>

    <?php
    require_once __DIR__ . "/../../../models/Nota.php";
    $notaModel = new Nota();

    // Detectar semestre actual y estado
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

    // Semestre seleccionado manualmente (si viene por GET)
    $semestreSeleccionado = intval($_GET['semestre'] ?? $semestreActual);

    // Obtener notas filtradas por semestre
    $notas = $notaModel->getByMatriculaAndSemestre($matricula['id'], $semestreSeleccionado);
    ?>

    <!-- Selector de semestre -->
    <form method="get" action="index.php"
        class="flex flex-col md:flex-row md:items-center md:justify-between bg-gray-800 border border-gray-700 rounded-xl p-4 mb-6 shadow">

        <div class="flex items-center gap-3 flex-wrap">
            <input type="hidden" name="action" value="perfil_academico">
            <input type="hidden" name="id" value="<?= $matricula['id'] ?>">

            <label class="text-gray-300 font-semibold">Seleccionar semestre:</label>
            <select name="semestre"
                class="bg-gray-900 text-white border border-gray-700 rounded-lg px-3 py-2 focus:ring focus:ring-indigo-500">
                <option value="1" <?= $semestreSeleccionado == 1 ? 'selected' : '' ?>>1° Semestre
                    (Marzo-Julio)</option>
                <option value="2" <?= $semestreSeleccionado == 2 ? 'selected' : '' ?>>2° Semestre
                    (Agosto-Diciembre)</option>
            </select>
        </div>

        <div class="flex gap-3 mt-4 md:mt-0">
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-semibold shadow transition">
                🔍 Ver notas
            </button>

            <a href="index.php?action=notas_index&matricula_id=<?= $matricula['id'] ?>"
                class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg font-semibold shadow transition">
                📘 Ver todas las notas
            </a>
        </div>
    </form>


    <p class="text-sm italic text-indigo-400 mb-4"><?= htmlspecialchars($estadoSemestre) ?></p>

    <?php if (!empty($asignaturas)): ?>
        <div class="overflow-hidden bg-gray-800/80 backdrop-blur-sm border border-gray-700 rounded-2xl shadow-2xl">
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
                        <tr class="border-b border-gray-700 hover:bg-gray-700/40 transition-colors duration-200">
                            <td class="px-6 py-3 font-medium text-gray-100">
                                <?= htmlspecialchars($asig['nombre']) ?>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <?php if (!empty($notasAsig)): ?>
                                    <?php foreach ($notasAsig as $n): ?>
                                        <span class="inline-block bg-gray-700 text-indigo-300 px-2 py-1 rounded-lg mx-1 mb-1">
                                            <?= htmlspecialchars(number_format($n['nota'], 1)) ?>
                                            <!-- Botón editar -->
                                            <a href="index.php?action=notas_edit&id=<?= $n['id'] ?>"
                                                class="ml-2 text-yellow-400 hover:text-yellow-300" title="Editar nota">
                                                ✏️
                                            </a>
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

        <!-- Promedio semestral -->
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