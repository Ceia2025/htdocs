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
                    <!-- 📚 SECCIÓN DE NOTAS -->
                    <section class="mt-10">
                        <h2 class="text-2xl font-bold border-b border-gray-600 pb-2 mb-6">Notas del Alumno</h2>

                        <?php
                        require_once __DIR__ . "/../../models/Nota.php";
                        $notaModel = new Nota();
                        $notas = $notaModel->getByMatricula($matricula['id'] ?? $_GET['matricula_id']);
                        ?>

                        <?php if (!empty($notas)): ?>
                            <div class="overflow-x-auto bg-gray-800 rounded-xl shadow-lg border border-gray-700">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-gray-300">Asignatura</th>
                                            <th class="px-4 py-2 text-left text-gray-300">Nota</th>
                                            <th class="px-4 py-2 text-left text-gray-300">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        <?php
                                        $suma = 0;
                                        $count = 0;
                                        foreach ($notas as $n):
                                            $notaColor = $n['nota'] < 4 ? 'text-red-400' : 'text-green-400';
                                            $suma += $n['nota'];
                                            $count++;
                                            ?>
                                            <tr>
                                                <td class="px-4 py-2"><?= htmlspecialchars($n['asignatura_nombre']) ?></td>
                                                <td class="px-4 py-2 font-semibold <?= $notaColor ?>">
                                                    <?= number_format($n['nota'], 1) ?></td>
                                                <td class="px-4 py-2"><?= htmlspecialchars($n['fecha']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Promedio final -->
                            <div class="mt-4 text-center">
                                <span class="text-gray-300 text-lg font-semibold">Promedio General:
                                    <span class="text-indigo-400"><?= number_format($suma / $count, 1) ?></span>
                                </span>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-400 italic">No hay notas registradas para este alumno.</p>
                        <?php endif; ?>

                        <!-- Botón agregar -->
                        <div class="mt-6 flex justify-center">
                            <a href="index.php?action=notas_createGroup&curso_id=<?= $curso['id'] ?>&anio_id=<?= $anio['id'] ?>"
                                class="bg-indigo-600 hover:bg-indigo-700 px-6 py-2 rounded-lg font-semibold">
                                ➕ Agregar Notas
                            </a>
                        </div>
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