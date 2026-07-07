<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$justVal  = $_POST['justificado']  ?? $retiro['justificado'];
$extrVal  = isset($_POST['extraordinario']) ? true : ($retiro['extraordinario'] === 'Si');
$quienVal = $_POST['quien_retira'] ?? $retiro['quien_retira'] ?? '';
?>

<header class="page-header">
    <div class="mx-auto max-w-3xl px-4 py-5 sm:px-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Convivencia Escolar</p>
        <h1 class="text-2xl font-bold text-strong font-display">Editar retiro</h1>
    </div>
</header>

<main class="mx-auto max-w-3xl px-4 py-6 sm:px-6">

    <?php if (!empty($error)): ?>
        <div class="banner-danger flex items-center gap-2 mb-5 px-4 py-3 rounded-xl text-sm font-medium">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Info alumno (solo lectura) -->
    <div class="panel rounded-xl px-5 py-4 mb-5 flex items-center gap-4">
        <div class="avatar-badge w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">
            <?= strtoupper(substr($retiro['nombre'], 0, 1) . substr($retiro['apepat'], 0, 1)) ?>
        </div>
        <div>
            <p class="text-strong font-semibold text-sm">
                <?= htmlspecialchars($retiro['apepat'] . ' ' . $retiro['apemat'] . ', ' . $retiro['nombre']) ?>
            </p>
            <p class="text-xs text-muted">
                <?= htmlspecialchars($retiro['run']) ?> ·
                <?= htmlspecialchars($retiro['curso']) ?> ·
                <?= $retiro['anio'] ?>
            </p>
        </div>
    </div>

    <div class="panel rounded-xl overflow-hidden">
        <form method="POST" action="index.php?action=retiros_edit&id=<?= $retiro['id'] ?>"
              class="px-5 py-5 space-y-5">

            <!-- Fecha y hora -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Fecha *</label>
                    <input type="date" name="fecha_retiro" required
                        value="<?= htmlspecialchars($_POST['fecha_retiro'] ?? $retiro['fecha_retiro']) ?>"
                        class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Hora de retiro *</label>
                    <input type="time" name="hora_retiro" required
                        value="<?= htmlspecialchars($_POST['hora_retiro'] ?? substr($retiro['hora_retiro'], 0, 5)) ?>"
                        class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                </div>
            </div>

            <!-- Motivo -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Motivo *</label>
                <input type="text" name="motivo" required maxlength="240"
                    placeholder="Describe el motivo del retiro…"
                    value="<?= htmlspecialchars($_POST['motivo'] ?? $retiro['motivo'] ?? '') ?>"
                    class="input-field w-full text-sm rounded-lg px-3 py-2">
            </div>

            <!-- Observación -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">
                    Observación <span class="normal-case font-normal text-faint">(opcional)</span>
                </label>
                <textarea name="observacion" rows="2"
                    class="input-field w-full text-sm rounded-lg px-3 py-2 resize-none"><?= htmlspecialchars($_POST['observacion'] ?? $retiro['observacion'] ?? '') ?></textarea>
            </div>

            <!-- Clasificación -->
            <div class="panel rounded-xl p-4 space-y-4" style="background: var(--surface-1);">
                <p class="text-xs font-semibold uppercase tracking-wider text-muted">Clasificación del retiro</p>

                <div class="grid grid-cols-2 gap-4">

                    <!-- Justificado -->
                    <div>
                        <label class="block text-xs font-semibold text-muted mb-2">¿Justificado?</label>
                        <div class="flex gap-2">
                            <label class="flex-1 flex items-center gap-2 input-field rounded-lg px-3 py-2 cursor-pointer transition
                                         has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/20">
                                <input type="radio" name="justificado" value="Si"
                                    <?= $justVal === 'Si' ? 'checked' : '' ?> class="accent-green-500">
                                <span class="text-sm text-soft">Sí</span>
                            </label>
                            <label class="flex-1 flex items-center gap-2 input-field rounded-lg px-3 py-2 cursor-pointer transition
                                         has-[:checked]:border-red-500 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/20">
                                <input type="radio" name="justificado" value="No"
                                    <?= $justVal === 'No' ? 'checked' : '' ?> class="accent-red-500">
                                <span class="text-sm text-soft">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Extraordinario -->
                    <div>
                        <label class="block text-xs font-semibold text-muted mb-2">¿Motivo extraordinario?</label>
                        <label class="flex items-center gap-3 input-field rounded-lg px-3 py-2.5 cursor-pointer transition
                                     has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 dark:has-[:checked]:bg-amber-900/20">
                            <input type="checkbox" name="extraordinario" value="Si"
                                <?= $extrVal ? 'checked' : '' ?> class="accent-amber-500 w-4 h-4">
                            <div>
                                <span class="text-sm text-soft font-medium">Causa del colegio</span>
                                <p class="text-xs text-faint leading-tight">Ej: falta de profesor</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Quien retira (texto libre en el edit, sin AJAX) -->
                <div>
                    <label class="block text-xs font-semibold text-muted mb-1.5">
                        Quien retira <span class="normal-case font-normal text-faint">(opcional)</span>
                    </label>
                    <input type="text" name="quien_retira" maxlength="150"
                        placeholder="Nombre del apoderado, familiar u otro responsable…"
                        value="<?= htmlspecialchars($quienVal) ?>"
                        class="input-field w-full text-sm rounded-lg px-3 py-2">
                    <p class="mt-1 text-xs text-faint">El semestre se recalculará si cambias la fecha.</p>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end gap-3 pt-1 border-t divider-soft">
                <a href="index.php?action=retiros"
                    class="btn-secondary rounded-lg px-4 py-2 text-sm transition">
                    Cancelar
                </a>
                <button type="submit"
                    class="btn-primary rounded-lg px-5 py-2 text-sm font-semibold transition">
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>

</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>