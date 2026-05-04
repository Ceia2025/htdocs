<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

$contacto = $contacto ?? [];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="h-full bg-gray-900">
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Nuevo Contacto</h1>
            <p class="text-gray-400 text-sm mt-1">
                Alumno: <span class="text-white font-semibold">
                    <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat'] . ', ' . $alumno['nombre']) ?>
                </span>
            </p>
        </div>
    </header>

    <main>
        <!-- Flash messages -->
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="mx-auto max-w-5xl px-4 pt-4 sm:px-6 lg:px-8">
                <div
                    class="px-4 py-3 bg-green-900/50 border border-green-500 text-green-300 rounded-lg text-sm font-medium">
                    <?= htmlspecialchars($_SESSION['flash_success']) ?>
                </div>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="mx-auto max-w-5xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="px-4 py-3 bg-red-900/50 border border-red-500 text-red-300 rounded-lg text-sm font-medium">
                    <?= htmlspecialchars($_SESSION['flash_error']) ?>
                </div>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif ?>
        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- Flash error -->
            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="mb-4 px-4 py-3 bg-red-900/50 border border-red-500 text-red-300 rounded-lg text-sm">
                    <?= htmlspecialchars($_SESSION['flash_error']) ?>
                    <?php unset($_SESSION['flash_error']); ?>
                </div>
            <?php endif ?>

            <form method="POST" action="index.php?action=alum_emergencia_storeProfile"
                class="bg-gray-800 rounded-2xl p-6 space-y-5 shadow-lg border border-gray-700">

                <!-- alumno_id oculto -->
                <input type="hidden" name="alumno_id" value="<?= htmlspecialchars($alumno['id']) ?>">

                <!-- Tipo -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Tipo de contacto</label>
                    <select name="tipo" id="tipo" required class="mt-2 w-full rounded-lg bg-gray-700 border border-gray-600 text-white px-4 py-2
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none">
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

                <!-- Nombre + RUN en fila -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nombre completo</label>
                        <input type="text" name="nombre_contacto" required
                            value="<?= htmlspecialchars($contacto['nombre_contacto'] ?? '') ?>" class="mt-2 w-full rounded-lg bg-gray-700 border border-gray-600 text-white px-3 py-2
                                   focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">R.U.N. del contacto</label>
                        <div class="mt-2 flex gap-2 items-center">
                            <input type="text" id="run_contacto_num" placeholder="Ej: 12.345.678" maxlength="11" class="w-full rounded-lg bg-gray-700 border border-gray-600 text-white px-3 py-2
                                       focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <span class="text-gray-400 font-bold flex-shrink-0">-</span>
                            <input type="text" id="run_contacto_dv" readonly placeholder="DV" maxlength="1" class="w-16 rounded-lg bg-gray-600 border border-gray-500 text-white px-3 py-2
                                       text-center cursor-not-allowed">
                        </div>
                        <!-- Campo oculto con el RUN completo -->
                        <input type="hidden" name="run_contacto" id="run_contacto_full"
                            value="<?= htmlspecialchars($contacto['run_contacto'] ?? '') ?>">
                        <p id="run_contacto_error" class="text-red-400 text-xs mt-1 hidden">RUN inválido</p>
                    </div>
                </div>

                <!-- Dirección + Comuna -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Dirección</label>
                        <input type="text" name="direccion"
                            value="<?= htmlspecialchars($contacto['direccion'] ?? '') ?>" class="mt-2 w-full rounded-lg bg-gray-700 border border-gray-600 text-white px-3 py-2
                                   focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Comuna o Sector</label>
                        <input type="text" name="comuna" value="<?= htmlspecialchars($contacto['comuna'] ?? '') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-700 border border-gray-600 text-white px-3 py-2
                                   focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Correo electrónico</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($contacto['email'] ?? '') ?>" class="mt-2 w-full rounded-lg bg-gray-700 border border-gray-600 text-white px-3 py-2
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <!-- Teléfono + Celular -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Teléfono</label>
                        <input type="text" name="telefono" value="<?= htmlspecialchars($contacto['telefono'] ?? '') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-700 border border-gray-600 text-white px-3 py-2
                                   focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Celular (sin +569)</label>
                        <div class="mt-2 flex rounded-lg overflow-hidden border border-gray-600">
                            <span class="bg-gray-600 text-gray-300 px-3 py-2 text-sm flex-shrink-0">+569</span>
                            <input type="text" name="celular"
                                value="<?= htmlspecialchars($contacto['celular'] ?? '') ?>" placeholder="12345678"
                                class="flex-1 bg-gray-700 text-white px-3 py-2
                                       focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Vínculo -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Vínculo / Relación</label>
                    <select name="relacion" class="mt-2 w-full rounded-lg bg-gray-700 border border-gray-600 text-white px-4 py-2
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none">
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
                    <label class="block text-sm font-medium text-gray-200">Observación</label>
                    <textarea name="observacion" rows="4" class="mt-2 w-full rounded-lg bg-gray-700 border border-gray-600 text-white px-3 py-2
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        placeholder="Información adicional relevante..."><?= htmlspecialchars($contacto['observacion'] ?? '') ?></textarea>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                    <a href="index.php?action=alumno_profile&id=<?= htmlspecialchars($alumno['id']) ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600
                               text-white text-sm font-semibold rounded-xl transition">
                        ⬅ Volver al perfil
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-700 hover:bg-green-600
                               text-white font-semibold rounded-xl shadow transition">
                        💾 Guardar contacto
                    </button>
                </div>

            </form>
        </div>
    </main>
</body>

<script>
    // ── RUN automático ──────────────────────────────────
    (function () {
        const inputNum = document.getElementById('run_contacto_num');
        const inputDV = document.getElementById('run_contacto_dv');
        const inputFull = document.getElementById('run_contacto_full');
        const errorEl = document.getElementById('run_contacto_error');
        const tipoSel = document.getElementById('tipo');
        const obsDiv = document.getElementById('campo_observacion');

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
            if (inputNum.value && inputDV.value) {
                inputFull.value = inputNum.value + '-' + inputDV.value;
            } else {
                inputFull.value = '';
            }
        }

        // Solo dígitos
        inputNum.addEventListener('keypress', e => {
            if (!/[0-9]/.test(e.key)) e.preventDefault();
        });

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

        // ── Mostrar/ocultar observación ─────────────────
        tipoSel.addEventListener('change', function () {
            obsDiv.classList.toggle('hidden', this.value !== 'emergencia');
        });
    })();
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>