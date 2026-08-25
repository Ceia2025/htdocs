<?php
date_default_timezone_set('America/Santiago');

require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<header class="page-header">
    <div class="mx-auto max-w-3xl px-4 py-5 sm:px-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Convivencia Escolar</p>
        <h1 class="text-2xl font-bold text-strong font-display">Nuevo retiro</h1>
    </div>
</header>

<main class="mx-auto max-w-3xl px-4 py-6 sm:px-6">

    <?php if (!empty($error)): ?>
        <div class="banner-danger flex items-center gap-2 mb-5 px-4 py-3 rounded-xl text-sm font-medium">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="panel rounded-xl overflow-hidden">

        <!-- Búsqueda alumno -->
        <div class="px-5 py-4 border-b divider-soft">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-2 h-2 rounded-full" style="background: var(--azul-vivo);"></div>
                <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Buscar alumno</h2>
            </div>
            <div class="relative">
                <input type="text" id="buscar-alumno" placeholder="RUN o nombre del alumno…" autocomplete="off"
                    class="input-field w-full text-sm rounded-lg px-3 py-2">
                <div id="sugerencias"
                    class="dropdown-panel hidden absolute z-50 w-full mt-1 rounded-lg shadow-xl overflow-y-auto max-h-64">
                </div>
            </div>

            <!-- Info alumno seleccionado -->
            <div id="info-alumno" class="hidden mt-3 rounded-lg px-4 py-3"
                 style="background: var(--accent-title-soft); border: 1px solid var(--azul-vivo);">
                <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-1">Alumno seleccionado</p>
                <p id="alumno-nombre" class="text-strong font-bold text-sm"></p>
                <p id="alumno-detalle" class="text-muted text-xs mt-0.5"></p>
            </div>
        </div>

        <!-- Formulario -->
        <form method="POST" action="index.php?action=retiros_create" id="form-retiro">
            <input type="hidden" name="alumno_id" id="alumno_id" value="">
            <input type="hidden" name="anio_id"   id="anio_id"   value="">

            <div class="px-5 py-5 space-y-5">

                <!-- Fecha y hora -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Fecha *</label>
                        <input type="date" name="fecha_retiro" required
                            value="<?= htmlspecialchars($_POST['fecha_retiro'] ?? date('Y-m-d')) ?>"
                            max="<?= date('Y-m-d') ?>"
                            class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Hora de retiro *</label>
                        <input type="time" name="hora_retiro" id="campo-hora" required
                            value="<?= htmlspecialchars($_POST['hora_retiro'] ?? date('H:i')) ?>"
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
                    <textarea name="observacion" rows="2" placeholder="Detalles adicionales del retiro…"
                        class="input-field w-full text-sm rounded-lg px-3 py-2 resize-none"><?= htmlspecialchars($_POST['observacion'] ?? '') ?></textarea>
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
                                        <?= ($_POST['justificado'] ?? '') === 'Si' ? 'checked' : '' ?>
                                        class="accent-green-500">
                                    <span class="text-sm text-soft">Sí</span>
                                </label>
                                <label class="flex-1 flex items-center gap-2 input-field rounded-lg px-3 py-2 cursor-pointer transition
                                             has-[:checked]:border-red-500 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/20">
                                    <input type="radio" name="justificado" value="No"
                                        <?= ($_POST['justificado'] ?? 'No') === 'No' ? 'checked' : '' ?>
                                        class="accent-red-500">
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
                                    <?= isset($_POST['extraordinario']) ? 'checked' : '' ?>
                                    class="accent-amber-500 w-4 h-4">
                                <div>
                                    <span class="text-sm text-soft font-medium">Causa del colegio</span>
                                    <p class="text-xs text-faint leading-tight">Ej: falta de profesor, actividad institucional</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Quien retira -->
                    <div>
                        <label class="block text-xs font-semibold text-muted mb-1.5">Quien retira</label>

                        <select id="select-quien" onchange="manejarQuienRetira(this.value)"
                            class="input-field w-full text-sm rounded-lg px-3 py-2 mb-2">
                            <option value="">— Se retira solo / sin especificar —</option>
                            <option value="__otro__">✏️ Escribir nombre manualmente</option>
                            <optgroup id="grupo-contactos" label="Contactos de emergencia">
                                <option disabled class="text-muted" id="opt-cargando">
                                    Selecciona primero un alumno
                                </option>
                            </optgroup>
                        </select>

                        <div id="bloque-otro" class="hidden">
                            <input type="text" id="input-otro" maxlength="150"
                                placeholder="Nombre de quien retira…"
                                oninput="document.getElementById('quien_retira').value = this.value"
                                class="input-field w-full text-sm rounded-lg px-3 py-2"
                                style="border-color: var(--warn);">
                        </div>

                        <input type="hidden" name="quien_retira" id="quien_retira"
                            value="<?= htmlspecialchars($_POST['quien_retira'] ?? '') ?>">

                        <p class="mt-1 text-xs text-faint">
                            Los contactos se cargan automáticamente al seleccionar un alumno.
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end gap-3 pt-1 border-t divider-soft">
                    <a href="index.php?action=retiros"
                        class="btn-secondary rounded-lg px-4 py-2 text-sm transition">
                        Cancelar
                    </a>
                    <button type="submit" id="btn-guardar" disabled
                        class="btn-primary flex items-center gap-2 rounded-lg px-5 py-2 text-sm font-semibold
                               disabled:opacity-40 disabled:cursor-not-allowed transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Registrar retiro
                    </button>
                </div>

            </div>
        </form>
    </div>

</main>

<script>
    let debounceTimer = null;

    function calcularEdad(fechanac) {
        if (!fechanac) return { edad: null, esMayor: null };
        const hoy = new Date();
        const nac = new Date(fechanac);
        let edad = hoy.getFullYear() - nac.getFullYear();
        const m = hoy.getMonth() - nac.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;
        return { edad, esMayor: edad >= 18 };
    }

    // Mapea mayor/menor al sistema de tokens CSS
    function colorEdad(esMayor) {
        if (esMayor === null) return { borde: 'var(--border-soft)', badgeClass: '', texto: '' };
        if (esMayor) return {
            borde: 'var(--azul-claro)',
            badgeClass: 'chip-counter',
            texto: 'Mayor de edad'
        };
        return {
            borde: 'var(--warn)',
            badgeClass: 'chip-warn',
            texto: 'Menor de edad'
        };
    }

    document.getElementById('buscar-alumno').addEventListener('input', function () {
        const q = this.value.trim();
        clearTimeout(debounceTimer);
        document.getElementById('sugerencias').classList.add('hidden');
        if (q.length < 2) return;
        debounceTimer = setTimeout(() => buscarSugerencias(q), 300);
    });

    async function buscarSugerencias(q) {
        try {
            const res = await fetch(`index.php?action=retiros_buscar_alumnos&q=${encodeURIComponent(q)}`);
            const data = await res.json();
            const box = document.getElementById('sugerencias');

            if (!data.length) {
                box.innerHTML = `<div class="px-4 py-3 text-sm text-muted">Sin resultados para "${q}"</div>`;
                box.classList.remove('hidden');
                return;
            }

            box.innerHTML = data.map(a => {
                const { edad, esMayor } = calcularEdad(a.fechanac);
                const color = colorEdad(esMayor);
                const edadTexto = edad !== null ? `${edad} años` : '';
                const badge = color.badgeClass
                    ? `<span class="inline-flex items-center ${color.badgeClass} text-xs px-2 py-0.5 rounded-full ml-1">
                           ${color.texto} · ${edadTexto}
                       </span>`
                    : '';

                return `
                <div class="dropdown-link flex items-center justify-between px-4 py-3 cursor-pointer transition
                            border-b divider-soft last:border-0"
                     style="border-left: 3px solid ${color.borde};"
                     onclick="seleccionarAlumno(${JSON.stringify(a).replace(/"/g, '&quot;')})">
                    <div>
                        <div class="flex items-center flex-wrap gap-1">
                            <p class="text-strong text-sm font-semibold">${a.apepat} ${a.apemat}, ${a.nombre}</p>
                            ${badge}
                        </div>
                        <p class="text-xs text-muted mt-0.5">RUN: ${a.run} · ${a.curso} · ${a.anio}</p>
                    </div>
                    <svg class="w-4 h-4 text-faint flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>`;
            }).join('');

            box.classList.remove('hidden');
        } catch (e) { console.error(e); }
    }

    function seleccionarAlumno(a) {
        const { edad, esMayor } = calcularEdad(a.fechanac);
        const color = colorEdad(esMayor);

        // Resetear quien retira antes de cargar nuevos contactos
        const sel = document.getElementById('select-quien');
        sel.value = '';
        sel.disabled = true;
        document.getElementById('quien_retira').value = '';
        document.getElementById('input-otro').value = '';
        document.getElementById('bloque-otro').classList.add('hidden');

        cargarContactos(a.alumno_id, sel);

        document.getElementById('alumno_id').value = a.alumno_id;
        document.getElementById('anio_id').value   = a.anio_id;
        document.getElementById('buscar-alumno').value = `${a.apepat} ${a.apemat}, ${a.nombre}`;
        document.getElementById('alumno-nombre').textContent = `${a.apepat} ${a.apemat}, ${a.nombre}`;

        const badge = color.badgeClass && edad !== null
            ? `<span class="inline-flex items-center ${color.badgeClass} text-xs px-2 py-0.5 rounded-full ml-1">
                   ${color.texto} · ${edad} años
               </span>`
            : '';

        document.getElementById('alumno-detalle').innerHTML =
            `RUN: ${a.run} · Curso: ${a.curso} (${a.anio}) ${badge}`;

        const infoBox = document.getElementById('info-alumno');
        infoBox.style.borderColor = color.borde;
        infoBox.classList.remove('hidden');

        document.getElementById('sugerencias').classList.add('hidden');
        document.getElementById('btn-guardar').disabled = false;
    }

    async function cargarContactos(alumno_id, sel) {
        const grupo = document.getElementById('grupo-contactos');
        const optLoad = document.getElementById('opt-cargando');

        Array.from(grupo.querySelectorAll('option:not(#opt-cargando)')).forEach(o => o.remove());
        optLoad.style.display = '';
        optLoad.disabled = true;
        optLoad.textContent = 'Cargando contactos…';

        try {
            const res = await fetch(`index.php?action=retiros_buscar_contactos&alumno_id=${alumno_id}`);
            const data = await res.json();

            if (!data.length) {
                optLoad.textContent = 'Sin contactos registrados';
            } else {
                optLoad.style.display = 'none';
                data.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.nombre_contacto;
                    opt.textContent = `${c.nombre_contacto} (${c.relacion})${c.telefono ? ' · ' + c.telefono : ''}`;
                    grupo.appendChild(opt);
                });
            }
        } catch (e) {
            optLoad.style.display = '';
            optLoad.textContent = 'Error al cargar contactos';
            console.error(e);
        } finally {
            if (sel) sel.disabled = false;
        }
    }

    function manejarQuienRetira(valor) {
        const bloqueOtro  = document.getElementById('bloque-otro');
        const hiddenQuien = document.getElementById('quien_retira');
        const inputOtro   = document.getElementById('input-otro');

        if (valor === '__otro__') {
            bloqueOtro.classList.remove('hidden');
            inputOtro.focus();
            hiddenQuien.value = inputOtro.value;
        } else {
            bloqueOtro.classList.add('hidden');
            hiddenQuien.value = valor;
        }
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#buscar-alumno') && !e.target.closest('#sugerencias'))
            document.getElementById('sugerencias').classList.add('hidden');
    });

    (() => {
        const campoHora = document.getElementById('campo-hora');
        const huboPost = <?= !empty($_POST) ? 'true' : 'false' ?>;

        if (campoHora && !huboPost) {
            const now = new Date();
            const hh = String(now.getHours()).padStart(2, '0');
            const mm = String(now.getMinutes()).padStart(2, '0');
            campoHora.value = hh + ':' + mm;
        }
    })();
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>