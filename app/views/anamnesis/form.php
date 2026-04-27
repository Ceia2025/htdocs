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

    <header class="bg-gray-800 border-b border-white/10">
        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-purple-400 mb-1">Anamnesis</p>
                <h1 class="text-2xl font-bold text-white">Resumen de Anamnesis</h1>
                <p class="text-sm text-gray-400 mt-0.5">
                    <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat'] . ', ' . $alumno['nombre']) ?>
                </p>
            </div>
            <a href="index.php?action=alumno_profile&id=<?= $alumno['id'] ?>"
               class="text-sm text-gray-400 hover:text-white border border-gray-600 hover:border-gray-400 px-4 py-2 rounded-lg transition">
                ← Volver al perfil
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 space-y-6">

        <?php if (!empty($_GET['guardado'])): ?>
            <div class="bg-green-900/40 border border-green-600 text-green-300 text-sm px-4 py-3 rounded-xl">
                ✅ Anamnesis guardada correctamente.
            </div>
        <?php endif; ?>

        <!-- Selector de año -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl px-5 py-4 flex items-center gap-4">
            <label class="text-xs font-semibold uppercase tracking-wider text-gray-400">Año académico</label>
            <form method="GET" action="index.php">
                <input type="hidden" name="action" value="anamnesis_form">
                <input type="hidden" name="alumno_id" value="<?= $alumno['id'] ?>">
                <select name="anio_id" onchange="this.form.submit()"
                    class="bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $a['id'] == ($anamnesis['anio_id'] ?? ($anios[0]['id'] ?? '')) ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Formulario -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-700">
                <h2 class="text-sm font-semibold text-white">
                    <?= $anamnesis ? 'Editar anamnesis' : 'Nueva anamnesis' ?>
                </h2>
            </div>

            <form method="POST" action="index.php?action=anamnesis_guardar" class="px-5 py-5 space-y-5">
                <input type="hidden" name="alumno_id" value="<?= $alumno['id'] ?>">
                <input type="hidden" name="anio_id" value="<?= $_GET['anio_id'] ?? ($anios[0]['id'] ?? '') ?>">

                <!-- Realizado por -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                        Realizado por
                        <span class="normal-case font-normal text-gray-500 ml-1">
                            (<?= $esMayor ? 'El alumno es mayor de edad' : 'El alumno es menor de edad' ?>)
                        </span>
                    </label>
                    <input type="text" name="realizado_por"
                           value="<?= htmlspecialchars($anamnesis['realizado_por'] ?? '') ?>"
                           placeholder="<?= $esMayor ? 'Nombre del alumno' : 'Nombre del padre/madre/tutor' ?>"
                           required
                           class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-4 py-2.5
                                  focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Relación (solo si es menor) -->
                <?php if (!$esMayor): ?>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                        Relación con el alumno
                    </label>
                    <select name="relacion"
                        class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-4 py-2.5
                               focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">— Seleccionar —</option>
                        <?php foreach (['Padre', 'Madre', 'Tutor/a', 'Apoderado/a', 'Abuelo/a', 'Otro'] as $rel): ?>
                            <option value="<?= $rel ?>" 
                                <?= ($anamnesis['relacion'] ?? '') === $rel ? 'selected' : '' ?>>
                                <?= $rel ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php else: ?>
                    <input type="hidden" name="relacion" value="">
                <?php endif; ?>

                <!-- Observaciones -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                        Observaciones
                    </label>
                    <textarea name="observaciones" rows="6"
                              placeholder="Ingrese el resumen de la anamnesis..."
                              class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-4 py-2.5
                                     focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"><?= htmlspecialchars($anamnesis['observaciones'] ?? '') ?></textarea>
                </div>

                <!-- Metadata si ya existe -->
                <?php if ($anamnesis): ?>
                    <p class="text-xs text-gray-600">
                        Último guardado: <?= $anamnesis['updated_at'] 
                            ? (new DateTime($anamnesis['updated_at']))->format('d/m/Y H:i') 
                            : (new DateTime($anamnesis['created_at']))->format('d/m/Y H:i') ?>
                    </p>
                <?php endif; ?>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-purple-600 hover:bg-purple-500 text-white font-bold px-6 py-2.5 rounded-xl transition">
                        Guardar anamnesis
                    </button>
                </div>
            </form>
        </div>

        <!-- Historial de años anteriores -->
        <?php if (count($historial) > 1): ?>
        <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-700">
                <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Historial</h2>
            </div>
            <div class="divide-y divide-gray-700">
                <?php foreach ($historial as $h): ?>
                    <div class="px-5 py-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-white"><?= $h['anio_escolar'] ?></span>
                            <span class="text-xs text-gray-500">
                                <?= $h['realizado_por'] ?>
                                <?= $h['relacion'] ? ' (' . $h['relacion'] . ')' : '' ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            <?= nl2br(htmlspecialchars($h['observaciones'] ?? '—')) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </main>
</div>
</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>