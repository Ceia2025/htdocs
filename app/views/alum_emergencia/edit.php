<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user    = $_SESSION['user'];
$nombre  = $user['nombre'];
$rol     = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$cancelHref = "index.php?action=alum_emergencia";
if (!empty($back) && $back === 'alumno_profile' && !empty($alumno_id)) {
    $cancelHref = "index.php?action=alumno_profile&id=" . urlencode($alumno_id);
}

$alumnoSeleccionado = null;
foreach ($alumnos as $a) {
    if ($a['id'] == $emergencia['alumno_id']) {
        $alumnoSeleccionado = $a;
        break;
    }
}

$tipoLabels = [
    'padre_madre_tutor' => ['label' => 'Padre / Madre / Tutor', 'icon' => '👨‍👩‍👧', 'color' => 'indigo'],
    'apoderado'         => ['label' => 'Apoderado',              'icon' => '📋',       'color' => 'amber'],
    'emergencia'        => ['label' => 'Emergencia',             'icon' => '🚨',       'color' => 'red'],
];
?>

<body class="h-full bg-gray-900">
<div class="min-h-full">

    <!-- HEADER -->
    <header class="bg-gray-800 border-b border-white/10">
        <div class="mx-auto max-w-4xl px-4 py-5 sm:px-6 lg:px-8 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">Contactos de Emergencia</p>
                <h1 class="text-2xl font-bold text-white">Editar Contacto</h1>
                <?php if ($alumnoSeleccionado): ?>
                    <p class="text-sm text-gray-400 mt-0.5">
                        Alumno:
                        <span class="text-white font-medium">
                            <?= htmlspecialchars($alumnoSeleccionado['nombre'] . ' ' . $alumnoSeleccionado['apepat'] . ' ' . $alumnoSeleccionado['apemat']) ?>
                        </span>
                    </p>
                <?php endif; ?>
            </div>
            <a href="<?= $cancelHref ?>"
               class="flex items-center gap-2 text-sm text-gray-400 hover:text-white border border-gray-600 hover:border-gray-400 px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
        </div>
    </header>

    <main>
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <form method="POST"
                  action="index.php?action=alum_emergencia_update&id=<?= $emergencia['id'] ?>"
                  class="space-y-6">

                <input type="hidden" name="back"      value="<?= htmlspecialchars($back ?? 'alum_emergencia') ?>">
                <input type="hidden" name="alumno_id" value="<?= $emergencia['alumno_id'] ?>">

                <!-- ── SECCIÓN 1: TIPO DE CONTACTO ── -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 flex items-center gap-2">
                        <span class="text-base">🏷️</span>
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-300">Tipo de Contacto</h2>
                    </div>
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <?php foreach ($tipoLabels as $value => $meta): ?>
                                <?php
                                $sel    = $emergencia['tipo'] === $value;
                                $colors = [
                                    'indigo' => $sel ? 'bg-indigo-600/20 border-indigo-500 text-indigo-300'  : 'bg-gray-900/50 border-gray-600 text-gray-400 hover:border-indigo-500/60',
                                    'amber'  => $sel ? 'bg-amber-600/20  border-amber-500  text-amber-300'   : 'bg-gray-900/50 border-gray-600 text-gray-400 hover:border-amber-500/60',
                                    'red'    => $sel ? 'bg-red-600/20    border-red-500    text-red-300'     : 'bg-gray-900/50 border-gray-600 text-gray-400 hover:border-red-500/60',
                                ];
                                ?>
                                <label class="relative flex items-center gap-3 px-4 py-3 rounded-xl border cursor-pointer transition <?= $colors[$meta['color']] ?>">
                                    <input type="radio" name="tipo" value="<?= $value ?>"
                                           class="sr-only" <?= $sel ? 'checked' : '' ?>>
                                    <span class="text-xl"><?= $meta['icon'] ?></span>
                                    <span class="text-sm font-semibold"><?= $meta['label'] ?></span>
                                    <?php if ($sel): ?>
                                        <svg class="w-4 h-4 ml-auto flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    <?php endif; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- ── SECCIÓN 2: DATOS PERSONALES ── -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 flex items-center gap-2">
                        <span class="text-base">👤</span>
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-300">Datos Personales</h2>
                    </div>
                    <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <!-- Nombre -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">
                                Nombre completo <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="nombre_contacto"
                                   value="<?= htmlspecialchars($emergencia['nombre_contacto'] ?? '') ?>"
                                   placeholder="Ej: María González Rojas"
                                   class="w-full rounded-xl bg-gray-900 border border-gray-600 text-white placeholder-gray-600
                                          px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                        <!-- RUN -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">
                                RUN
                            </label>
                            <input type="text" name="run_contacto"
                                   value="<?= htmlspecialchars($emergencia['run_contacto'] ?? '') ?>"
                                   placeholder="12.345.678-9"
                                   class="w-full rounded-xl bg-gray-900 border border-gray-600 text-white placeholder-gray-600
                                          px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                        <!-- Relación -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">
                                Relación con el alumno
                            </label>
                            <select name="relacion"
                                    class="w-full rounded-xl bg-gray-900 border border-gray-600 text-white
                                           px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                <option value="">— Seleccionar —</option>
                                <?php foreach (AlumEmergencia::relacionesDisponibles() as $rel): ?>
                                    <option value="<?= $rel ?>" <?= ($rel == $emergencia['relacion']) ? 'selected' : '' ?>>
                                        <?= $rel ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- ── SECCIÓN 3: CONTACTO ── -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 flex items-center gap-2">
                        <span class="text-base">📞</span>
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-300">Información de Contacto</h2>
                    </div>
                    <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <!-- Teléfono -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">
                                Teléfono
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </span>
                                <input type="text" name="telefono"
                                       value="<?= htmlspecialchars($emergencia['telefono'] ?? '') ?>"
                                       placeholder="+56 2 2345 6789"
                                       class="w-full rounded-xl bg-gray-900 border border-gray-600 text-white placeholder-gray-600
                                              pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <!-- Celular -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">
                                Celular
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input type="text" name="celular"
                                       value="<?= htmlspecialchars($emergencia['celular'] ?? '') ?>"
                                       placeholder="+56 9 1234 5678"
                                       class="w-full rounded-xl bg-gray-900 border border-gray-600 text-white placeholder-gray-600
                                              pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">
                                Email
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input type="email" name="email"
                                       value="<?= htmlspecialchars($emergencia['email'] ?? '') ?>"
                                       placeholder="correo@ejemplo.com"
                                       class="w-full rounded-xl bg-gray-900 border border-gray-600 text-white placeholder-gray-600
                                              pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ── SECCIÓN 4: DIRECCIÓN ── -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 flex items-center gap-2">
                        <span class="text-base">📍</span>
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-300">Dirección</h2>
                    </div>
                    <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-3 gap-5">

                        <!-- Dirección -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">
                                Calle y número
                            </label>
                            <input type="text" name="direccion"
                                   value="<?= htmlspecialchars($emergencia['direccion'] ?? '') ?>"
                                   placeholder="Av. Ejemplo 123, Depto 4"
                                   class="w-full rounded-xl bg-gray-900 border border-gray-600 text-white placeholder-gray-600
                                          px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                        <!-- Comuna -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">
                                Comuna
                            </label>
                            <input type="text" name="comuna"
                                   value="<?= htmlspecialchars($emergencia['comuna'] ?? '') ?>"
                                   placeholder="Parral"
                                   class="w-full rounded-xl bg-gray-900 border border-gray-600 text-white placeholder-gray-600
                                          px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                    </div>
                </div>

                <!-- ── SECCIÓN 5: OBSERVACIÓN ── -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 flex items-center gap-2">
                        <span class="text-base">📝</span>
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-300">Observación</h2>
                    </div>
                    <div class="px-6 py-5">
                        <textarea name="observacion" rows="3"
                                  placeholder="Información adicional relevante sobre este contacto..."
                                  class="w-full rounded-xl bg-gray-900 border border-gray-600 text-white placeholder-gray-600
                                         px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"><?= htmlspecialchars($emergencia['observacion'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- ── BOTONES ── -->
                <div class="flex items-center justify-between gap-4 pt-2">
                    <a href="<?= $cancelHref ?>"
                       class="flex items-center gap-2 bg-gray-700 hover:bg-gray-600 text-white
                              font-semibold px-6 py-3 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" id="btn-guardar"
                            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700
                                   text-white font-bold px-8 py-3 rounded-xl transition shadow-lg shadow-indigo-900/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </main>
</div>

<script>
    // Radio buttons de tipo con estilo visual
    document.querySelectorAll('input[name="tipo"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('input[name="tipo"]').forEach(r => {
                const label = r.closest('label');
                const colorMap = {
                    padre_madre_tutor: {
                        active:   'bg-indigo-600/20 border-indigo-500 text-indigo-300',
                        inactive: 'bg-gray-900/50 border-gray-600 text-gray-400 hover:border-indigo-500/60'
                    },
                    apoderado: {
                        active:   'bg-amber-600/20 border-amber-500 text-amber-300',
                        inactive: 'bg-gray-900/50 border-gray-600 text-gray-400 hover:border-amber-500/60'
                    },
                    emergencia: {
                        active:   'bg-red-600/20 border-red-500 text-red-300',
                        inactive: 'bg-gray-900/50 border-gray-600 text-gray-400 hover:border-red-500/60'
                    }
                };
                const map   = colorMap[r.value];
                const state = r.checked ? 'active' : 'inactive';
                label.className = label.className
                    .replace(/bg-\S+\/20|border-\S+|text-\S+300|text-gray-400/g, '')
                    .trim();
                map[state].split(' ').forEach(cls => label.classList.add(cls));

                // Mostrar/ocultar check icon
                const icon = label.querySelector('svg');
                if (icon) icon.remove();
                if (r.checked) {
                    label.insertAdjacentHTML('beforeend', `
                        <svg class="w-4 h-4 ml-auto flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586
                                     7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"/>
                        </svg>`);
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {

    document.querySelector('form').addEventListener('submit', function (e) {
        const select = document.querySelector('select[name="relacion"]');

        if (!select.value) {
            e.preventDefault();
            e.stopImmediatePropagation();

            // Resaltar el select en rojo
            select.classList.add('border-red-500', 'ring-2', 'ring-red-500');
            select.classList.remove('border-gray-600');

            // Mostrar mensaje de error debajo del select
            let aviso = document.getElementById('aviso-relacion');
            if (!aviso) {
                aviso = document.createElement('p');
                aviso.id = 'aviso-relacion';
                aviso.className = 'mt-1.5 text-xs text-red-400 flex items-center gap-1';
                aviso.innerHTML = '⚠️ Debes seleccionar una relación antes de guardar.';
                select.parentElement.appendChild(aviso);
            }

            // Scroll suave hacia el campo
            select.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Limpiar error cuando el usuario elige algo
            select.addEventListener('change', function () {
                select.classList.remove('border-red-500', 'ring-2', 'ring-red-500');
                select.classList.add('border-gray-600');
                document.getElementById('aviso-relacion')?.remove();
            }, { once: true });
        }
    });

    });
    
</script>

</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>