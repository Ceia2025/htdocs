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
            <div class="mx-auto max-w-3xl px-4 py-5 sm:px-6 flex items-center gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">Convivencia
                        Escolar</p>
                    <h1 class="text-2xl font-bold text-white">Editar retiro</h1>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-4 py-6 sm:px-6">

            <?php if (!empty($error)): ?>
                <div
                    class="mb-5 flex items-center gap-2 bg-red-900/40 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Info alumno (solo lectura) -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl px-5 py-4 mb-5 flex items-center gap-4">
                <div
                    class="h-10 w-10 rounded-full bg-indigo-900/50 border border-indigo-700 flex items-center justify-center text-indigo-300 font-bold text-sm flex-shrink-0">
                    <?= strtoupper(substr($retiro['nombre'], 0, 1) . substr($retiro['apepat'], 0, 1)) ?>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">
                        <?= htmlspecialchars($retiro['apepat'] . ' ' . $retiro['apemat'] . ', ' . $retiro['nombre']) ?>
                    </p>
                    <p class="text-xs text-gray-500"><?= htmlspecialchars($retiro['run']) ?> ·
                        <?= htmlspecialchars($retiro['curso']) ?> · <?= $retiro['anio'] ?>
                    </p>
                </div>
            </div>

            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <form method="POST" action="index.php?action=retiros_edit&id=<?= $retiro['id'] ?>"
                    class="px-5 py-5 space-y-5">

                    <!-- Fecha y hora -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Fecha
                                *</label>
                            <input type="date" name="fecha_retiro" required
                                value="<?= htmlspecialchars($_POST['fecha_retiro'] ?? $retiro['fecha_retiro']) ?>"
                                class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Hora
                                de retiro *</label>
                            <input type="time" name="hora_retiro" required
                                value="<?= htmlspecialchars($_POST['hora_retiro'] ?? substr($retiro['hora_retiro'], 0, 5)) ?>"
                                class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                        </div>
                    </div>

                    <!-- Motivo -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Motivo
                            *</label>
                        <input type="text" name="motivo" required maxlength="240"
                            placeholder="Describe el motivo del retiro…"
                            value="<?= htmlspecialchars($_POST['motivo'] ?? $retiro['motivo'] ?? '') ?>"
                            class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-600">
                    </div>

                    <!-- Observación -->
                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Observación
                            <span class="normal-case font-normal text-gray-600">(opcional)</span></label>
                        <textarea name="observacion" rows="2"
                            class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"><?= htmlspecialchars($_POST['observacion'] ?? $retiro['observacion'] ?? '') ?></textarea>
                    </div>

                    <!-- Bloque clasificación -->
                    <div class="bg-gray-900/50 border border-gray-700 rounded-xl p-4 space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Clasificación del retiro
                        </p>

                        <?php
                        $justVal = $_POST['justificado'] ?? $retiro['justificado'];
                        $extrVal = isset($_POST['extraordinario'])
                            ? true
                            : ($retiro['extraordinario'] === 'Si');
                        $quienVal = $_POST['quien_retira'] ?? $retiro['quien_retira'] ?? '';
                        ?>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-2">¿Justificado?</label>
                                <div class="flex gap-2">
                                    <label
                                        class="flex-1 flex items-center gap-2 bg-gray-800 border border-gray-600 hover:border-green-600 rounded-lg px-3 py-2 cursor-pointer transition has-[:checked]:border-green-500 has-[:checked]:bg-green-900/20">
                                        <input type="radio" name="justificado" value="Si" <?= $justVal === 'Si' ? 'checked' : '' ?> class="accent-green-500">
                                        <span class="text-sm text-gray-300">Sí</span>
                                    </label>
                                    <label
                                        class="flex-1 flex items-center gap-2 bg-gray-800 border border-gray-600 hover:border-red-600 rounded-lg px-3 py-2 cursor-pointer transition has-[:checked]:border-red-500 has-[:checked]:bg-red-900/20">
                                        <input type="radio" name="justificado" value="No" <?= $justVal === 'No' ? 'checked' : '' ?> class="accent-red-500">
                                        <span class="text-sm text-gray-300">No</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-2">¿Motivo
                                    extraordinario?</label>
                                <label
                                    class="flex items-center gap-3 bg-gray-800 border border-gray-600 hover:border-amber-600 rounded-lg px-3 py-2.5 cursor-pointer transition has-[:checked]:border-amber-500 has-[:checked]:bg-amber-900/20">
                                    <input type="checkbox" name="extraordinario" value="Si" <?= $extrVal ? 'checked' : '' ?> class="accent-amber-500 w-4 h-4">
                                    <div>
                                        <span class="text-sm text-gray-300 font-medium">Causa del colegio</span>
                                        <p class="text-xs text-gray-600 leading-tight">Ej: falta de profesor</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Quien retira <span
                                    class="normal-case font-normal text-gray-600">(opcional)</span></label>
                            <input type="text" name="quien_retira" maxlength="150"
                                placeholder="Nombre del apoderado, familiar u otro responsable…"
                                value="<?= htmlspecialchars($quienVal) ?>"
                                class="w-full bg-gray-800 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-600">
                            <p class="mt-1 text-xs text-gray-600">El semestre se recalculará si cambias la fecha.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-1 border-t border-gray-700">
                        <a href="index.php?action=retiros"
                            class="rounded-lg bg-gray-700 px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 transition">Cancelar</a>
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition">Guardar
                            cambios</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <?php include __DIR__ . "/../layout/footer.php"; ?>