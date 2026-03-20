<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>
<main>
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Nueva Anotación</h1>
        </div>
    </header>

    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-gray-800/60 border border-gray-700 rounded-2xl p-8 shadow-xl">

            <form action="index.php?action=anotacion_store" method="POST" class="space-y-6">
                <input type="hidden" name="anio_id" value="<?= htmlspecialchars($anio_id ?? '') ?>">
                <input type="hidden" name="curso_id" value="<?= htmlspecialchars($curso_id ?? '') ?>">
                <input type="hidden" name="alumno_id" id="hidden_alumno_id"
                    value="<?= htmlspecialchars($alumno_id ?? '') ?>">
                <input type="hidden" name="matricula_id" id="hidden_matricula_id"
                    value="<?= htmlspecialchars($matricula_id ?? '') ?>">

                <!-- ALUMNO -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">Alumno</label>

                    <?php if ($alumnoPreseleccionado): ?>
                        <!-- Alumno preseleccionado desde el listado -->
                        <div class="flex items-center gap-3 bg-indigo-900/30 border border-indigo-600 rounded-xl px-4 py-3">
                            <span class="text-2xl">👤</span>
                            <div>
                                <p class="text-white font-semibold capitalize">
                                    <?= htmlspecialchars($alumnoPreseleccionado['apepat'] . ' ' . $alumnoPreseleccionado['apemat'] . ', ' . $alumnoPreseleccionado['nombre']) ?>
                                </p>
                                <p class="text-xs text-indigo-300">
                                    <?= htmlspecialchars($alumnoPreseleccionado['curso_nombre']) ?> — RUN:
                                    <?= htmlspecialchars($alumnoPreseleccionado['run']) ?>
                                </p>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Buscador libre -->
                        <div class="relative">
                            <input type="text" id="buscar_alumno" placeholder="Buscar por RUN o nombre..."
                                class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition"
                                autocomplete="off">
                            <div id="resultados_alumno"
                                class="hidden absolute z-50 w-full bg-gray-800 border border-gray-700 rounded-xl mt-1 shadow-xl max-h-56 overflow-y-auto">
                            </div>
                        </div>

                        <div id="alumno_seleccionado"
                            class="hidden mt-3 flex items-center gap-3 bg-indigo-900/30 border border-indigo-600 rounded-xl px-4 py-3">
                            <span class="text-2xl">👤</span>
                            <div>
                                <p id="alumno_nombre_display" class="text-white font-semibold capitalize"></p>
                                <p id="alumno_curso_display" class="text-xs text-indigo-300"></p>
                            </div>
                            <button type="button" onclick="limpiarAlumno()"
                                class="ml-auto text-gray-400 hover:text-white text-sm">✕</button>
                        </div>
                    <?php endif ?>
                </div>

                <!-- ASIGNATURA -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">Asignatura</label>
                    <select name="asignatura_id" id="sel_asignatura" required
                        class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">Seleccionar asignatura...</option>

                        <?php
                        $especiales = ['Convivencia Escolar', 'Inspectoría'];
                        $normales = [];
                        $destacadas = [];

                        foreach ($asignaturas as $as) {
                            if (in_array(trim($as['nombre']), $especiales)) {
                                $destacadas[] = $as;
                            } else {
                                $normales[] = $as;
                            }
                        }
                        ?>

                        <!-- Asignaturas normales -->
                        <?php foreach ($normales as $as): ?>
                            <option value="<?= $as['id'] ?>">
                                <?= htmlspecialchars($as['nombre']) ?>
                            </option>
                        <?php endforeach ?>

                        <!-- Separador + especiales al final -->
                        <?php if (!empty($destacadas)): ?>
                            <optgroup label="─── Gestión y Convivencia ───">
                                <?php foreach ($destacadas as $as): ?>
                                    <option value="<?= $as['id'] ?>">
                                        ⭐
                                        <?= htmlspecialchars($as['nombre']) ?>
                                    </option>
                                <?php endforeach ?>
                            </optgroup>
                        <?php endif ?>

                    </select>
                </div>

                <!-- TIPO -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Tipo de Anotación</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <?php
                        $tipos = [
                            'Registro' => ['color' => 'blue', 'icon' => '📋'],
                            'Positiva' => ['color' => 'green', 'icon' => '✅'],
                            'Leve' => ['color' => 'yellow', 'icon' => '⚠️'],
                            'Grave' => ['color' => 'orange', 'icon' => '🔶'],
                            'Gravísima' => ['color' => 'red', 'icon' => '🚨'],
                        ];
                        foreach ($tipos as $tipo => $cfg): ?>
                            <label class="cursor-pointer">
                                <input type="radio" name="tipo" value="<?= $tipo ?>" class="sr-only peer" required>
                                <div
                                    class="text-center border-2 border-gray-600 peer-checked:border-<?= $cfg['color'] ?>-500
                                            peer-checked:bg-<?= $cfg['color'] ?>-900/40 rounded-xl px-3 py-4 transition hover:border-gray-500">
                                    <p class="text-2xl mb-1"><?= $cfg['icon'] ?></p>
                                    <p class="text-sm font-semibold text-gray-200"><?= $tipo ?></p>
                                </div>
                            </label>
                        <?php endforeach ?>
                    </div>
                </div>

                <!-- SEMESTRE -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">Semestre</label>
                    <select name="semestre" required
                        class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">Seleccionar...</option>
                        <option value="1">1° Semestre</option>
                        <option value="2">2° Semestre</option>
                    </select>
                </div>

                <!-- FECHA -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">Fecha de la Anotación</label>
                    <input type="date" name="fecha_anotacion" value="<?= date('Y-m-d') ?>" required
                        class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                </div>

                <!-- CONTENIDO -->
                <!-- CONTENIDO -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">Descripción</label>
                    <textarea name="contenido" id="contenido" rows="4" required placeholder="Describe la situación..."
                        maxlength="800"
                        class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition resize-none"></textarea>

                    <!-- Contador -->
                    <div class="flex justify-end mt-1">
                        <span id="char_count" class="text-xs font-medium text-green-400 transition-colors duration-300">
                            0 / 800
                        </span>
                    </div>
                </div>
                <!-- ACCIÓN REALIZADA -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-1">
                        Acción Realizada
                        <span class="text-gray-500 font-normal text-xs ml-1">(opcional)</span>
                    </label>
                    <input type="text" name="accion_realizada" maxlength="500"
                        placeholder="Ej: Se llamó al apoderado, se envió citación..." class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 
               focus:ring-2 focus:ring-indigo-500 transition">
                </div>

                <!-- NOTIFICADO APODERADO -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="notificado_apoderado" id="notif" value="Si"
                        class="w-5 h-5 rounded bg-gray-700 border-gray-600 text-indigo-500 focus:ring-indigo-500">
                    <label for="notif" class="text-sm text-gray-200">Apoderado notificado</label>
                </div>

                <!-- BOTONES -->
                <div class="flex gap-4 pt-2">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white font-semibold rounded-xl py-3 shadow-lg transition">
                        Guardar Anotación
                    </button>
                    <a href="index.php?action=anotaciones&anio_id=<?= $anio_id ?>&curso_id=<?= $curso_id ?>"
                        class="w-full text-center bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl py-3 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    const ANIO_ID = <?= json_encode($anio_id) ?>;
    const CURSO_ID = <?= json_encode($curso_id) ?>;

    <?php if (!$alumnoPreseleccionado): ?>
        // Solo se inicializa el buscador si NO hay alumno preseleccionado
        const buscarInput = document.getElementById('buscar_alumno');
        const resultadosDiv = document.getElementById('resultados_alumno');
        const alumnoSelDiv = document.getElementById('alumno_seleccionado');

        let debounceTimer;
        buscarInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            const term = buscarInput.value.trim();
            if (term.length < 2) { resultadosDiv.classList.add('hidden'); return; }

            debounceTimer = setTimeout(async () => {
                const res = await fetch(`index.php?action=anotacion_buscar_alumno&term=${encodeURIComponent(term)}&anio_id=${ANIO_ID}`);
                const data = await res.json();

                resultadosDiv.innerHTML = '';
                if (data.length === 0) {
                    resultadosDiv.innerHTML = '<p class="px-4 py-3 text-gray-400 text-sm">Sin resultados</p>';
                } else {
                    data.forEach(a => {
                        const div = document.createElement('div');
                        div.className = "px-4 py-3 hover:bg-gray-700 cursor-pointer text-sm text-gray-100 capitalize border-b border-gray-700/50";
                        div.textContent = `${a.apepat} ${a.apemat}, ${a.nombre} — ${a.run} (${a.curso_nombre})`;
                        div.onclick = () => seleccionarAlumno(a);
                        resultadosDiv.appendChild(div);
                    });
                }
                resultadosDiv.classList.remove('hidden');
            }, 300);
        });

        function seleccionarAlumno(a) {
            document.getElementById('hidden_alumno_id').value = a.id;
            document.getElementById('hidden_matricula_id').value = a.matricula_id;
            document.getElementById('alumno_nombre_display').textContent = `${a.apepat} ${a.apemat}, ${a.nombre}`;
            document.getElementById('alumno_curso_display').textContent = `${a.curso_nombre} — RUN: ${a.run}`;
            alumnoSelDiv.classList.remove('hidden');
            buscarInput.style.display = 'none';
            resultadosDiv.classList.add('hidden');
            cargarAsignaturas(a.curso_id ?? CURSO_ID);
        }

        function limpiarAlumno() {
            document.getElementById('hidden_alumno_id').value = '';
            document.getElementById('hidden_matricula_id').value = '';
            alumnoSelDiv.classList.add('hidden');
            buscarInput.style.display = '';
            buscarInput.value = '';
        }

        document.addEventListener('click', (e) => {
            if (!resultadosDiv.contains(e.target) && e.target !== buscarInput) {
                resultadosDiv.classList.add('hidden');
            }
        });


    <?php endif ?>

    async function cargarAsignaturas(curso_id) {
        const sel = document.getElementById('sel_asignatura');
        const res = await fetch(`index.php?action=anotacion_asignaturas&curso_id=${curso_id}`);
        const data = await res.json();
        sel.innerHTML = '<option value="">Seleccionar asignatura...</option>';
        data.forEach(a => {
            sel.innerHTML += `<option value="${a.id}">${a.nombre}</option>`;
        });
    }
    // Contador de caracteres
    const textarea = document.getElementById('contenido');
    const charCount = document.getElementById('char_count');

    textarea.addEventListener('input', () => {
        const len = textarea.value.length;
        const max = 800;
        const pct = len / max;

        charCount.textContent = `${len} / ${max}`;

        // Cambio de color según porcentaje usado
        charCount.className = 'text-xs font-medium transition-colors duration-300 ' + (
            pct >= 0.9 ? 'text-red-400' :   // 90%+ → rojo
                pct >= 0.65 ? 'text-yellow-400' :   // 65%+ → amarillo
                    'text-green-400'       // menos → verde
        );
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>