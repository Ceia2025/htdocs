<!-- SECCIÓN DE NOTAS CON SEMESTRES -->
<section class="">
    <div class="flex items-center gap-3 mb-6">
        <div class="card-icon-badge w-12 h-12 rounded-xl flex items-center justify-center">
            <?= saat_icon('clock', 'w-6 h-6') ?>
        </div>
        <h2 class="text-2xl font-bold text-strong tracking-tight">Notas del Alumno</h2>
    </div>

    <?php
    require_once __DIR__ . "/../../../models/Nota.php";
    $notaModel = new Nota();

    // Excluye "ramos" que en realidad son registros administrativos,
    // no asignaturas académicas — no deben aparecer en la tabla ni
    // contar en los promedios.
    $asignaturasExcluidas = ['Inspectoría', 'Convivencia Escolar'];
    $asignaturas = array_values(array_filter(
        $asignaturas,
        fn($a) => !in_array($a['nombre'], $asignaturasExcluidas)
    ));

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
        class="panel flex flex-col md:flex-row md:items-start md:justify-between rounded-xl p-4 mb-6 shadow gap-4">

        <div class="flex items-center gap-3 flex-wrap">
            <input type="hidden" name="action" value="perfil_academico">
            <input type="hidden" name="id" value="<?= $matricula['id'] ?>">

            <label class="text-soft font-semibold">Seleccionar semestre:</label>
            <select name="semestre" class="input-field rounded-lg px-3 py-2">
                <option value="1" <?= $semestreSeleccionado == 1 ? 'selected' : '' ?>>1° Semestre
                    (Marzo-Julio)</option>
                <option value="2" <?= $semestreSeleccionado == 2 ? 'selected' : '' ?>>2° Semestre
                    (Agosto-Diciembre)</option>
            </select>
        </div>

        <div class="flex flex-col gap-2 w-full md:w-auto">
            <button type="submit"
                class="btn-primary w-full justify-center whitespace-nowrap px-5 py-2 rounded-lg font-semibold shadow transition inline-flex items-center gap-2">
                <?= saat_icon('search', 'w-4 h-4') ?>
                Ver notas
            </button>

            <a href="index.php?action=notas_index&matricula_id=<?= $matricula['id'] ?>"
                class="btn-soft-info w-full justify-center whitespace-nowrap px-5 py-2 rounded-lg font-semibold shadow transition inline-flex items-center gap-2">
                <?= saat_icon('book', 'w-4 h-4') ?>
                Ver todas las notas
            </a>
        </div>
    </form>


    <p class="text-sm italic text-accent mb-4"><?= htmlspecialchars($estadoSemestre) ?></p>

    <?php if (!empty($asignaturas)): ?>
        <div class="panel overflow-hidden rounded-2xl shadow-2xl backdrop-blur-sm">
            <table class="data-table w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                            Asignatura</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">Notas
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">
                            Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $promsAsig = [];

                    foreach ($asignaturas as $asig):
                        $notasAsig = array_filter($notas, fn($n) => $n['asignatura_nombre'] === $asig['nombre']);
                        $promedio = Nota::promedio($notasAsig);
                        if ($promedio !== null)
                            $promsAsig[] = $promedio;
                        ?>
                        <tr>
                            <td class="px-6 py-3 font-medium text-strong">
                                <?= htmlspecialchars($asig['nombre']) ?>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <?php if (!empty($notasAsig)): ?>
                                    <?php foreach ($notasAsig as $n): ?>
                                        <span
                                            class="inline-flex items-center bg-[var(--surface-1)] border border-[var(--border-soft)] text-accent px-2 py-1 rounded-lg mx-1 mb-1">
                                            <?= Nota::fmt((float) $n['nota']) ?>
                                            <!-- Botón editar -->
                                            <a href="index.php?action=notas_edit&id=<?= $n['id'] ?>"
                                                class="ml-2 text-warn hover:opacity-75 inline-flex items-center" title="Editar nota">
                                                <?= saat_icon('edit', 'w-3.5 h-3.5') ?>
                                            </a>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-faint italic">Sin notas</span>
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

        <!-- Promedio semestral -->
        <div class="mt-6 text-center text-soft">
            <p class="text-lg font-semibold">
                Promedio <?= $semestreSeleccionado ?>° Semestre:
                <span class="text-accent">
                    <?= Nota::fmt(Nota::promedio($promsAsig), '-') ?>
                </span>
            </p>
        </div>

        <!-- Promedio anual -->
        <?php
        $generalSemestre = function ($sem) use ($notaModel, $matricula, $asignaturas) {
            $notasSem = $notaModel->getByMatriculaAndSemestre($matricula['id'], $sem);
            $proms = [];
            foreach ($asignaturas as $asig) {
                $na = array_filter($notasSem, fn($n) => $n['asignatura_nombre'] === $asig['nombre']);
                $p = Nota::promedio($na);
                if ($p !== null)
                    $proms[] = $p;
            }
            return Nota::promedio($proms);
        };

        $promSem1 = $generalSemestre(1);
        $promSem2 = $generalSemestre(2);

        if ($promSem1 !== null && $promSem2 !== null):
            $promFinal = Nota::promedio([$promSem1, $promSem2]);

            ?>
            <div class="panel mt-10 rounded-2xl p-6 text-center shadow-lg">
                <h3 class="text-xl font-semibold text-strong mb-2 flex items-center justify-center gap-2">
                    <?= saat_icon('chart', 'w-5 h-5') ?>
                    Promedio Final Anual
                </h3>
                <p class="text-soft">
                    1° Sem: <span class="text-accent"><?= Nota::fmt($promSem1, 1) ?></span> |
                    2° Sem: <span class="text-accent"><?= Nota::fmt($promSem2, 1) ?></span>
                </p>
                <p class="mt-2 text-lg font-bold text-accent">
                    Promedio Final: <?= Nota::fmt($promFinal, 1) ?>
                </p>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <p class="text-faint italic mt-4 text-center">No hay asignaturas registradas para este curso.
        </p>
    <?php endif; ?>
</section>