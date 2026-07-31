<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user    = $_SESSION['user'];
$nombre  = $user['nombre'];
$rol     = $user['rol'];
$contacto = $contacto ?? [];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<header class="page-header">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold tracking-tight text-strong font-display">Nuevo Contacto</h1>
        <p class="text-muted text-sm mt-1">
            Alumno: <span class="text-strong font-semibold">
                <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat'] . ', ' . $alumno['nombre']) ?>
            </span>
        </p>
    </div>
</header>

<main>

    <!-- Flash messages -->
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="mx-auto max-w-3xl px-4 pt-4 sm:px-6 lg:px-8">
            <div class="banner-success px-4 py-3 rounded-lg text-sm font-medium">
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
            </div>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif ?>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="mx-auto max-w-3xl px-4 pt-4 sm:px-6 lg:px-8">
            <div class="banner-danger px-4 py-3 rounded-lg text-sm font-medium">
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
            </div>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif ?>

    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

        <form method="POST" action="index.php?action=alum_emergencia_storeProfile"
              class="panel rounded-2xl p-6 space-y-5 shadow-lg">

            <input type="hidden" name="alumno_id" value="<?= htmlspecialchars($alumno['id']) ?>">

            <!-- Tipo -->
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Tipo de contacto</label>
                <select name="tipo" id="tipo" required class="input-field w-full rounded-lg px-4 py-2.5 cursor-pointer">
                    <option value="padre_madre_tutor" <?= ($contacto['tipo'] ?? '') === 'padre_madre_tutor' ? 'selected' : '' ?>>
                        👨‍👩‍👦 Padre / Madre / Tutor Legal
                    </option>
                    <option value="apoderado" <?= ($contacto['tipo'] ?? '') === 'apoderado' ? 'selected' : '' ?>>
                        🧑‍💼 Representante / Apoderado
                    </option>
                    <option value="emergencia" <?= ($contacto['tipo'] ?? '') === 'emergencia' ? 'selected' : '' ?>>
                        🆘 Emergencia / Apoderado Suplente
                    </option>
                </select>
            </div>

            <!-- Nombre + RUN -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Nombre completo</label>
                    <input type="text" name="nombre_contacto" required
                        value="<?= htmlspecialchars($contacto['nombre_contacto'] ?? '') ?>"
                        class="input-field w-full rounded-lg px-3 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">R.U.N. del contacto</label>
                    <div class="flex gap-2 items-center">
                        <input type="text" id="run_contacto_num" placeholder="Ej: 12.345.678" maxlength="11"
                            class="input-field flex-1 rounded-lg px-3 py-2.5">
                        <span class="text-muted font-bold flex-shrink-0">-</span>
                        <input type="text" id="run_contacto_dv" readonly placeholder="DV" maxlength="1"
                            class="input-field w-14 rounded-lg px-3 py-2.5 text-center cursor-not-allowed opacity-70">
                    </div>
                    <input type="hidden" name="run_contacto" id="run_contacto_full"
                        value="<?= htmlspecialchars($contacto['run_contacto'] ?? '') ?>">
                    <p id="run_contacto_error" class="text-danger text-xs mt-1 hidden">RUN inválido</p>
                </div>
            </div>

            <!-- Dirección + Comuna -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Dirección</label>
                    <input type="text" name="direccion"
                        value="<?= htmlspecialchars($contacto['direccion'] ?? '') ?>"
                        class="input-field w-full rounded-lg px-3 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Comuna o Sector</label>
                    <input type="text" name="comuna"
                        value="<?= htmlspecialchars($contacto['comuna'] ?? '') ?>"
                        class="input-field w-full rounded-lg px-3 py-2.5">
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Correo electrónico</label>
                <input type="email" name="email"
                    value="<?= htmlspecialchars($contacto['email'] ?? '') ?>"
                    class="input-field w-full rounded-lg px-3 py-2.5">
            </div>

            <!-- Teléfono + Celular -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Teléfono</label>
                    <input type="text" name="telefono"
                        value="<?= htmlspecialchars($contacto['telefono'] ?? '') ?>"
                        class="input-field w-full rounded-lg px-3 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Celular (sin +569)</label>
                    <div class="flex rounded-lg overflow-hidden" style="border: 1px solid var(--border-soft);">
                        <span class="px-3 py-2.5 text-sm text-soft flex-shrink-0 font-mono"
                              style="background: var(--surface-1);">+569</span>
                        <input type="text" name="celular"
                            value="<?= htmlspecialchars($contacto['celular'] ?? '') ?>"
                            placeholder="12345678"
                            class="input-field flex-1 px-3 py-2.5 rounded-none"
                            style="border: none; border-left: 1px solid var(--border-soft);">
                    </div>
                </div>
            </div>

            <!-- Vínculo -->
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Vínculo / Relación</label>
                <select name="relacion" class="input-field w-full rounded-lg px-4 py-2.5 cursor-pointer">
                    <option value="">Seleccione vínculo</option>
                    <?php foreach (AlumEmergencia::relacionesDisponibles() as $rel): ?>
                        <option value="<?= $rel ?>" <?= ($contacto['relacion'] ?? '') === $rel ? 'selected' : '' ?>>
                            <?= $rel ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Observación (solo tipo emergencia) -->
            <div id="campo_observacion"
                class="<?= ($contacto['tipo'] ?? 'emergencia') !== 'emergencia' ? 'hidden' : '' ?>">
                <label class="block text-sm font-medium text-soft mb-2">Observación</label>
                <textarea name="observacion" rows="4"
                    placeholder="Información adicional relevante..."
                    class="input-field w-full rounded-lg px-3 py-2.5 resize-none"><?= htmlspecialchars($contacto['observacion'] ?? '') ?></textarea>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-between pt-4 border-t divider-soft">
                <a href="index.php?action=alumno_profile&id=<?= htmlspecialchars($alumno['id']) ?>"
                   class="btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl transition">
                    ⬅ Volver al perfil
                </a>
                <button type="submit"
                    class="btn-soft-success inline-flex items-center gap-2 px-6 py-2.5 font-semibold rounded-xl shadow transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar contacto
                </button>
            </div>

        </form>
    </div>
</main>

<script>
    (function () {
        const inputNum  = document.getElementById('run_contacto_num');
        const inputDV   = document.getElementById('run_contacto_dv');
        const inputFull = document.getElementById('run_contacto_full');
        const errorEl   = document.getElementById('run_contacto_error');
        const tipoSel   = document.getElementById('tipo');
        const obsDiv    = document.getElementById('campo_observacion');

        function formatRun(v) {
            v = v.replace(/\D/g, '');
            return v.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        function validateRun(v) {
            const n = parseInt(v.replace(/\./g, ''), 10);
            return !isNaN(n) && n >= 1000000 && n <= 109000000;
        }
        function calcularDV(rut) {
            let suma = 0, mult = 2;
            for (let i = rut.length - 1; i >= 0; i--) {
                suma += parseInt(rut.charAt(i), 10) * mult;
                mult = mult < 7 ? mult + 1 : 2;
            }
            const r = 11 - (suma % 11);
            if (r === 11) return '0';
            if (r === 10) return 'K';
            return String(r);
        }
        function actualizarFull() {
            inputFull.value = (inputNum.value && inputDV.value)
                ? inputNum.value + '-' + inputDV.value
                : '';
        }

        inputNum.addEventListener('keypress', e => { if (!/[0-9]/.test(e.key)) e.preventDefault(); });

        inputNum.addEventListener('input', e => {
            const formatted = formatRun(e.target.value);
            e.target.value = formatted;
            const solo = formatted.replace(/\./g, '');

            if (formatted && !validateRun(formatted)) {
                errorEl.classList.remove('hidden');
                inputDV.value = '';
                inputFull.value = '';
            } else {
                errorEl.classList.add('hidden');
                if (solo.length > 0 && validateRun(formatted)) {
                    inputDV.value = calcularDV(solo);
                } else {
                    inputDV.value = '';
                }
                setTimeout(actualizarFull, 10);
            }
        });

        tipoSel.addEventListener('change', function () {
            obsDiv.classList.toggle('hidden', this.value !== 'emergencia');
        });
    })();
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>