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
                    <h1 class="text-2xl font-bold text-white">Nuevo retiro</h1>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-4 py-6 sm:px-6">

            <?php if (!empty($error)): ?>
                <div
                    class="mb-5 flex items-center gap-2 bg-red-900/40 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm font-medium">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">

                <!-- Búsqueda alumno -->
                <div class="px-5 py-4 border-b border-gray-700">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-300">Buscar alumno</h2>
                    </div>
                    <div class="relative">
                        <input type="text" id="buscar-alumno" placeholder="RUN o nombre del alumno…" autocomplete="off"
                            class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-600">
                        <div id="sugerencias"
                            class="hidden absolute z-50 w-full mt-1 bg-gray-800 border border-gray-600 rounded-lg shadow-xl overflow-y-auto max-h-64">
                        </div>
                    </div>

                    <!-- Info alumno seleccionado -->
                    <div id="info-alumno"
                        class="hidden mt-3 bg-gray-900/60 border border-indigo-700/40 rounded-lg px-4 py-3">
                        <p class="text-xs text-indigo-400 font-semibold uppercase tracking-wider mb-1">Alumno
                            seleccionado</p>
                        <p id="alumno-nombre" class="text-white font-bold text-sm"></p>
                        <p id="alumno-detalle" class="text-gray-400 text-xs mt-0.5"></p>
                    </div>
                </div>

                <!-- Formulario -->
                <form method="POST" action="index.php?action=retiros_create" id="form-retiro">
                    <input type="hidden" name="alumno_id" id="alumno_id" value="">
                    <input type="hidden" name="anio_id" id="anio_id" value="">

                    <div class="px-5 py-5 space-y-5">

                        <!-- Fecha y hora -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Fecha
                                    *</label>
                                <input type="date" name="fecha_retiro" required
                                    value="<?= htmlspecialchars($_POST['fecha_retiro'] ?? date('Y-m-d')) ?>"
                                    max="<?= date('Y-m-d') ?>"
                                    class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Hora
                                    de retiro *</label>
                                <input type="time" name="hora_retiro" required
                                    value="<?= htmlspecialchars($_POST['hora_retiro'] ?? date('H:i')) ?>"
                                    class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            </div>
                        </div>

                        <!-- Motivo -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Motivo
                                *</label>
                            <input type="text" name="motivo" required maxlength="240"
                                placeholder="Describe el motivo del retiro…"
                                value="<?= htmlspecialchars($_POST['motivo'] ?? $retiro['motivo'] ?? '') ?>"
                                class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-600">
                        </div>

                        <!-- Observación -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                                Observación
                            </label>
                            <textarea name="observacion" rows="2" placeholder="Detalles adicionales del retiro…"
                                class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none placeholder-gray-600"><?= htmlspecialchars($_POST['observacion'] ?? '') ?></textarea>
                        </div>

                        <!-- Justificado + Extraordinario + Quien retira (bloque unificado) -->
                        <div class="bg-gray-900/50 border border-gray-700 rounded-xl p-4 space-y-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Clasificación del
                                retiro</p>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Justificado -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">¿Justificado?</label>
                                    <div class="flex gap-2">
                                        <label
                                            class="flex-1 flex items-center gap-2 bg-gray-800 border border-gray-600 hover:border-green-600 rounded-lg px-3 py-2 cursor-pointer transition has-[:checked]:border-green-500 has-[:checked]:bg-green-900/20">
                                            <input type="radio" name="justificado" value="Si" <?= ($_POST['justificado'] ?? '') === 'Si' ? 'checked' : '' ?> class="accent-green-500">
                                            <span class="text-sm text-gray-300">Sí</span>
                                        </label>
                                        <label
                                            class="flex-1 flex items-center gap-2 bg-gray-800 border border-gray-600 hover:border-red-600 rounded-lg px-3 py-2 cursor-pointer transition has-[:checked]:border-red-500 has-[:checked]:bg-red-900/20">
                                            <input type="radio" name="justificado" value="No" <?= ($_POST['justificado'] ?? 'No') === 'No' ? 'checked' : '' ?> class="accent-red-500">
                                            <span class="text-sm text-gray-300">No</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Extraordinario -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">¿Motivo
                                        extraordinario?</label>
                                    <label
                                        class="flex items-center gap-3 bg-gray-800 border border-gray-600 hover:border-amber-600 rounded-lg px-3 py-2.5 cursor-pointer transition has-[:checked]:border-amber-500 has-[:checked]:bg-amber-900/20">
                                        <input type="checkbox" name="extraordinario" value="Si"
                                            <?= isset($_POST['extraordinario']) ? 'checked' : '' ?>
                                            class="accent-amber-500 w-4 h-4">
                                        <div>
                                            <span class="text-sm text-gray-300 font-medium">Causa del colegio</span>
                                            <p class="text-xs text-gray-600 leading-tight">Ej: falta de profesor,
                                                actividad institucional</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Quien retira -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Quien retira</label>

                                <select id="select-quien" onchange="manejarQuienRetira(this.value)"
                                    class="w-full bg-gray-800 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-2">
                                    <option value="">— Se retira solo / sin especificar —</option>
                                    <option value="__otro__">✏️ Escribir nombre manualmente</option>
                                    <optgroup id="grupo-contactos" label="Contactos de emergencia">
                                        <option disabled class="text-gray-500" id="opt-cargando">Selecciona primero un
                                            alumno</option>
                                    </optgroup>
                                </select>

                                <!-- Input libre (aparece solo si elige "otro") -->
                                <div id="bloque-otro" class="hidden">
                                    <input type="text" id="input-otro" maxlength="150"
                                        placeholder="Nombre de quien retira…"
                                        oninput="document.getElementById('quien_retira').value = this.value"
                                        class="w-full bg-gray-800 text-white text-sm border border-amber-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 placeholder-gray-600">
                                </div>

                                <!-- Campo oculto que se envía al servidor -->
                                <input type="hidden" name="quien_retira" id="quien_retira"
                                    value="<?= htmlspecialchars($_POST['quien_retira'] ?? '') ?>">

                                <p class="mt-1 text-xs text-gray-600">
                                    Los contactos se cargan automáticamente al seleccionar un alumno.
                                </p>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex items-center justify-end gap-3 pt-1 border-t border-gray-700">
                            <a href="index.php?action=retiros"
                                class="rounded-lg bg-gray-700 px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 transition">Cancelar</a>
                            <button type="submit" id="btn-guardar" disabled
                                class="flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-40 disabled:cursor-not-allowed transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Registrar retiro
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

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

        function colorEdad(esMayor) {
            if (esMayor === null) return { borde: 'border-gray-600', badge: '', texto: '' };
            if (esMayor) return {
                borde: 'border-blue-600',
                badge: 'bg-blue-900/40 text-blue-300 border border-blue-700',
                texto: 'Mayor de edad'
            };
            return {
                borde: 'border-amber-500',
                badge: 'bg-amber-900/40 text-amber-300 border border-amber-700',
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
                    box.innerHTML = `<div class="px-4 py-3 text-sm text-gray-500">Sin resultados para "${q}"</div>`;
                    box.classList.remove('hidden');
                    return;
                }

                box.innerHTML = data.map(a => {
                    const { edad, esMayor } = calcularEdad(a.fechanac);
                    const color = colorEdad(esMayor);
                    const edadTexto = edad !== null ? `${edad} años` : '';

                    return `
                <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-700 cursor-pointer
                            border-b border-gray-700/50 last:border-0 transition border-l-2 ${color.borde}"
                     onclick="seleccionarAlumno(${JSON.stringify(a).replace(/"/g, '&quot;')})">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-white text-sm font-semibold">${a.apepat} ${a.apemat}, ${a.nombre}</p>
                            ${color.badge ? `<span class="text-xs px-2 py-0.5 rounded-full ${color.badge}">${color.texto} · ${edadTexto}</span>` : ''}
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">RUN: ${a.run} · ${a.curso} · ${a.anio}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            sel.disabled = true; // deshabilitar mientras cargan contactos
            document.getElementById('quien_retira').value = '';
            document.getElementById('input-otro').value = '';   // Bug 2 fix
            document.getElementById('bloque-otro').classList.add('hidden');

            // Cargar contactos del nuevo alumno
            cargarContactos(a.alumno_id, sel);

            // Datos del alumno
            document.getElementById('alumno_id').value = a.alumno_id;
            document.getElementById('anio_id').value = a.anio_id;
            document.getElementById('buscar-alumno').value = `${a.apepat} ${a.apemat}, ${a.nombre}`;
            document.getElementById('alumno-nombre').textContent = `${a.apepat} ${a.apemat}, ${a.nombre}`;

            const badgeHtml = color.badge && edad !== null
                ? `<span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full ${color.badge}">${color.texto} · ${edad} años</span>`
                : '';
            document.getElementById('alumno-detalle').innerHTML =
                `RUN: ${a.run} · Curso: ${a.curso} (${a.anio}) ${badgeHtml}`;

            const infoBox = document.getElementById('info-alumno');
            infoBox.className = `mt-3 bg-gray-900/60 rounded-lg px-4 py-3 border ${color.borde}`;
            infoBox.classList.remove('hidden');

            document.getElementById('sugerencias').classList.add('hidden');
            document.getElementById('btn-guardar').disabled = false;
        }

        async function cargarContactos(alumno_id, sel) {
            const grupo = document.getElementById('grupo-contactos');
            const optLoad = document.getElementById('opt-cargando');

            // Limpiar contactos anteriores y mostrar estado de carga
            Array.from(grupo.querySelectorAll('option:not(#opt-cargando)')).forEach(o => o.remove());
            optLoad.style.display = '';      // Bug 1 fix: siempre visible al iniciar
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
                // Bug 5 fix: rehabilitar select siempre al terminar, con o sin error
                if (sel) sel.disabled = false;
            }
        }

        function manejarQuienRetira(valor) {
            const bloqueOtro = document.getElementById('bloque-otro');
            const hiddenQuien = document.getElementById('quien_retira');
            const inputOtro = document.getElementById('input-otro');

            if (valor === '__otro__') {
                bloqueOtro.classList.remove('hidden');
                inputOtro.focus();
                hiddenQuien.value = inputOtro.value; // Bug 3 fix: toma lo que ya haya escrito
            } else {
                bloqueOtro.classList.add('hidden');
                hiddenQuien.value = valor;
            }
        }

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#buscar-alumno') && !e.target.closest('#sugerencias'))
                document.getElementById('sugerencias').classList.add('hidden');
        });
    </script>

    <?php include __DIR__ . "/../layout/footer.php"; ?>