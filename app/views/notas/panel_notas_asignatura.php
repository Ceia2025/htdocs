<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$notasPorMatricula = [];
foreach ($notas as $n) {
    $notasPorMatricula[$n['matricula_id']][] = $n;
}
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <header class="bg-gray-800 border-b border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-1">
                        <?= htmlspecialchars($curso['nombre']) ?> · <?= $semestre ?>° Semestre
                    </p>
                    <h1 class="text-2xl font-bold text-white"><?= htmlspecialchars($asignatura['nombre']) ?></h1>
                    <p class="text-sm text-gray-400 mt-0.5">Año <?= htmlspecialchars($anio['anio']) ?></p>
                </div>
                <div class="flex items-center gap-3">
                    <?php if ($puedeEditar): ?>
                        <a href="index.php?action=notas_createGroup&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white 
                                  text-sm font-semibold px-4 py-2 rounded-lg transition">
                            + Agregar notas
                        </a>
                    <?php endif; ?>
                    <a href="index.php?action=notas_panel_asignaturas&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&semestre=<?= $semestre ?>"
                        class="text-sm text-gray-400 hover:text-white border border-gray-600 hover:border-gray-400 px-4 py-2 rounded-lg transition">
                        ← Volver
                    </a>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6">

            <!-- Selector semestre -->
            <div class="flex items-center gap-3 mb-6">
                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Semestre:</span>
                <?php foreach ([1 => '1° Sem', 2 => '2° Sem'] as $num => $label): ?>
                    <?php $activo = $semestre == $num; ?>
                    <a href="index.php?action=notas_panel_asignatura&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&asignatura_id=<?= $asignaturaId ?>&semestre=<?= $num ?>"
                        class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition
                              <?= $activo ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-gray-900 border-gray-600 text-gray-400 hover:border-gray-400' ?>">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if (empty($alumnos)): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-12 text-center">
                    <p class="text-gray-400">No hay alumnos matriculados en este curso.</p>
                </div>
            <?php else: ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-700 bg-gray-900/50">
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    #</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Alumno</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Notas</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $alumno):
                                $mid = $alumno['matricula_id'];
                                $notasAlumno = $notasPorMatricula[$mid] ?? [];
                                $estaRetirado = !empty($alumno['fecha_retiro']);

                                $suma = 0;
                                $cant = 0;
                                foreach ($notasAlumno as $n) {
                                    if (is_numeric($n['nota'])) {
                                        $suma += $n['nota'];
                                        $cant++;
                                    }
                                }
                                $promedio = $cant > 0 ? round($suma / $cant, 1) : null;
                                $colorFila = $estaRetirado ? 'opacity-60' : 'hover:bg-gray-700/20';
                                $colorNumero = $alumno['numero_lista'] ? 'text-indigo-400' : 'text-gray-600';
                                $colorPromedio = $promedio !== null
                                    ? ($promedio >= 4.0 ? 'text-green-400' : 'text-red-400')
                                    : '';
                                ?>
                                <tr class="border-t border-gray-700 <?= $colorFila ?>">

                                    <!-- # -->
                                    <td class="px-4 py-3 text-xs font-bold <?= $colorNumero ?>">
                                        <?= $alumno['numero_lista'] ?? '—' ?>
                                    </td>

                                    <!-- Nombre -->
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-white">
                                            <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                                        </span>
                                        <span class="text-gray-500 text-xs block">
                                            <?= htmlspecialchars($alumno['nombre']) ?>
                                        </span>
                                        <?php if ($estaRetirado): ?>
                                            <span class="text-xs text-red-400">Retirado</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Notas -->
                                    <td class="px-4 py-3 text-center">
                                        <?php if (empty($notasAlumno)): ?>
                                            <span class="text-gray-600 text-xs italic">Sin notas</span>
                                        <?php else: ?>
                                            <div class="flex flex-wrap justify-center gap-1">
                                                <?php foreach ($notasAlumno as $n): ?>
                                                    <span
                                                        class="inline-flex items-center gap-1 bg-gray-700 text-indigo-300 px-2 py-1 rounded-lg text-xs">
                                                        <?= number_format($n['nota'], 1) ?>
                                                        <?php if ($puedeEditar): ?>
                                                            <a href="index.php?action=notas_edit&id=<?= $n['id'] ?>"
                                                                class="text-yellow-500 hover:text-yellow-400 ml-1" title="Editar">✏️</a>
                                                        <?php endif; ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Promedio -->
                                    <td class="px-4 py-3 text-center">
                                        <?php if ($promedio !== null): ?>
                                            <span class="font-bold text-sm <?= $colorPromedio ?>">
                                                <?= number_format($promedio, 1) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-600">—</span>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </main>
    </div>
</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>