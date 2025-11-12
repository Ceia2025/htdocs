<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="bg-gray-900 text-white">
    <div class="max-w-3xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold mb-6 text-indigo-400">✏️ Editar Nota</h1>

        <form method="POST" action="index.php?action=notas_update&id=<?= $nota['id'] ?>"
            class="space-y-6 bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700">

            <div>
                <label class="block text-gray-400 mb-1">Alumno</label>
                <p class="text-lg font-semibold text-gray-100">
                    <?= htmlspecialchars($nota['alumno_nombre'] . ' ' . $nota['apepat'] . ' ' . $nota['apemat']) ?>
                </p>
            </div>

            <div>
                <label class="block text-gray-400 mb-1">Asignatura</label>
                <p class="text-indigo-300 font-semibold"><?= htmlspecialchars($nota['asignatura_nombre']) ?></p>
            </div>

            <div>
                <label class="block text-gray-400 mb-1">Nota</label>
                <input type="number" name="nota" value="<?= htmlspecialchars($nota['nota']) ?>" step="0.1" min="1"
                    max="7" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white">
            </div>

            <div>
                <label class="block text-gray-400 mb-1">Fecha</label>
                <input type="date" name="fecha" value="<?= htmlspecialchars(substr($nota['fecha'], 0, 10)) ?>"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white">
            </div>

            <div>
                <label class="block text-gray-400 mb-1">Semestre</label>
                <select name="semestre" class="bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white">
                    <option value="1" <?= $nota['semestre'] == 1 ? 'selected' : '' ?>>1° Semestre</option>
                    <option value="2" <?= $nota['semestre'] == 2 ? 'selected' : '' ?>>2° Semestre</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <!-- Campos ocultos importantes -->
                <input type="hidden" name="matricula_id" value="<?= htmlspecialchars($nota['matricula_id'] ?? '') ?>">
                <input type="hidden" name="semestre" value="<?= htmlspecialchars($nota['semestre'] ?? 1) ?>">

                <a href="javascript:history.back()" class="px-5 py-2 bg-gray-600 rounded-lg hover:bg-gray-500">
                    ⬅ Volver
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-lg">
                    💾 Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>