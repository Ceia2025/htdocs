<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="bg-gray-900 text-white">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">➕ Ingreso de Notas</h1>

        <form method="POST"
            action="index.php?action=notas_storeGroup&curso_id=<?= $_GET['curso_id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
            class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1 text-gray-300">Asignatura</label>
                    <select name="asignatura_id" required
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2">
                        <option value="">Seleccionar...</option>
                        <?php foreach ($asignaturas as $asig): ?>
                            <option value="<?= $asig['id'] ?>"><?= htmlspecialchars($asig['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-gray-300">Fecha</label>
                    <input type="date" name="fecha" required
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2">
                </div>
            </div>

            <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-lg mt-6">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr class="bg-gray-700">
                            <th class="px-4 py-2 text-left">Alumno</th>
                            <th class="px-4 py-2 text-left">Estado</th>
                            <th class="px-4 py-2 text-left">Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos as $a): ?>
                            <tr class="hover:bg-gray-700">
                                <td class="px-4 py-2">
                                    <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat'] . ' ' . $a['nombre']) ?></td>
                                <td class="px-4 py-2">
                                    <?= $a['deleted_at'] ? '<span class="text-red-400">Retirado</span>' : '<span class="text-green-400">Activo</span>' ?>
                                </td>
                                <td class="px-4 py-2">
                                    <?php if ($a['deleted_at']): ?>
                                        <input type="number" step="0.1" value="0" readonly
                                            class="w-24 bg-gray-600 border border-gray-500 rounded-lg text-center">
                                    <?php else: ?>
                                        <!-- usar alumno_id como clave del array -->
                                        <input type="number" name="notas[<?= $a['alumno_id'] ?>][nota]" step="0.1" min="1"
                                            max="7" placeholder="Ej: 6.5"
                                            class="w-24 bg-gray-800 border border-gray-700 rounded-lg text-center">
                                    <?php endif; ?>

                                    <input type="hidden" name="notas[<?= $a['alumno_id'] ?>][matricula_id]"
                                        value="<?= $a['matricula_id'] ?>">
                                    <input type="hidden" name="notas[<?= $a['alumno_id'] ?>][deleted_at]"
                                        value="<?= $a['deleted_at'] ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg font-semibold">Guardar Notas</button>
            </div>
        </form>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>