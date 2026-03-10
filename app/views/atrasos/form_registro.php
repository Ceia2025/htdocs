<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$fechaHoy = $_GET['fecha'] ?? date('Y-m-d');
$guardado = $_GET['guardado'] ?? 0;
?>

<body class="h-full bg-gray-900">
<div class="min-h-full">

    <!-- HEADER -->
    <header class="bg-gray-800 border-b border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-5 sm:px-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-amber-400 mb-0.5">Convivencia Escolar</p>
                <h1 class="text-2xl font-bold text-white">Registro de Atrasos</h1>
                <p class="text-sm text-gray-400 mt-0.5">Portería — <?= date('d/m/Y', strtotime($fechaHoy)) ?></p>
            </div>
            <a href="index.php?action=atrasos_lista_curso"
               class="flex items-center gap-2 text-sm text-gray-400 hover:text-white border border-gray-600 hover:border-gray-400 px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Ver por curso
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-6 sm:px-6">

        <!-- ALERTA GUARDADO -->
        <?php if ($guardado): ?>
        <div id="alerta-ok" class="flex items-center gap-3 mb-5 bg-green-900/40 border border-green-700/50 text-green-300 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            Atraso registrado correctamente.
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            <!-- =====================
                 PANEL IZQUIERDO: FORM
            ===================== -->
            <div class="lg:col-span-2">
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden sticky top-6">

                    <div class="px-5 py-4 border-b border-gray-700 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-300">Nuevo atraso</h2>
                    </div>

                    <!-- BÚSQUEDA POR RUN -->
                    <div class="px-5 pt-4 pb-3">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                            Buscar alumno
                        </label>
                            <div class="relative">
                                <input type="text" id="buscar-run" placeholder="RUN o nombre del alumno…" autocomplete="off"
                                    class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent placeholder-gray-600">
                               <!-- Lista de sugerencias -->
                                    <div id="sugerencias"
                                         class="hidden absolute z-50 w-full mt-1 bg-gray-800 border border-gray-600 rounded-lg overflow-hidden shadow-xl">
                                    </div>
                            </div>
                            <div id="error-run" class="hidden mt-2 text-xs text-red-400 font-medium"></div>
                    </div>

                    <!-- INFO ALUMNO (aparece tras búsqueda) -->
                    <div id="info-alumno" class="hidden mx-5 mb-3 bg-gray-900/60 border border-amber-700/40 rounded-lg px-4 py-3">
                        <p class="text-xs text-amber-400 font-semibold uppercase tracking-wider mb-1">Alumno encontrado</p>
                        <p id="alumno-nombre" class="text-white font-bold text-sm"></p>
                        <p id="alumno-curso"  class="text-gray-400 text-xs mt-0.5"></p>
                        <p id="alumno-run"    class="text-gray-500 text-xs font-mono"></p>
                    </div>

                    <!-- FORMULARIO -->
                    <form method="POST" action="index.php?action=atrasos_guardar" id="form-atraso">
                        <input type="hidden" name="matricula_id" id="matricula_id" value="">

                        <div class="px-5 space-y-4 pb-5">

                            <!-- Fecha -->
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                                    Fecha
                                </label>
                                <input type="date" name="fecha" id="campo-fecha"
                                       value="<?= $fechaHoy ?>" max="<?= date('Y-m-d') ?>"
                                       class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent cursor-pointer">
                            </div>

                            <!-- Hora llegada -->
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                                    Hora de llegada
                                </label>
                                <input type="time" name="hora_llegada" id="campo-hora"
                                    value="<?= date('H:i') ?>"
                                    max="<?= date('H:i') ?>"
                                    class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent cursor-pointer">
                            </div>

                            <!-- Justificado -->
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">
                                    ¿Justificado?
                                </label>
                                <div class="flex gap-3">
                                    <label class="flex-1 flex items-center gap-2 bg-gray-900 border border-gray-600 hover:border-green-600 rounded-lg px-3 py-2 cursor-pointer transition has-[:checked]:border-green-500 has-[:checked]:bg-green-900/20">
                                        <input type="radio" name="justificado" value="1" class="accent-green-500">
                                        <span class="text-sm text-gray-300">Sí</span>
                                    </label>
                                    <label class="flex-1 flex items-center gap-2 bg-gray-900 border border-gray-600 hover:border-red-600 rounded-lg px-3 py-2 cursor-pointer transition has-[:checked]:border-red-500 has-[:checked]:bg-red-900/20">
                                        <input type="radio" name="justificado" value="0" checked class="accent-red-500">
                                        <span class="text-sm text-gray-300">No</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Observación -->
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                                    Observación <span class="normal-case font-normal text-gray-600">(opcional)</span>
                                </label>
                                <textarea name="observacion" rows="2"
                                          placeholder="Motivo del atraso, indicaciones, etc."
                                          class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent placeholder-gray-600 resize-none"></textarea>
                            </div>

                            <!-- Submit -->
                            <button type="submit" id="btn-guardar" disabled
                                    class="w-full flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-500 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold px-4 py-3 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Registrar Atraso
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- ==============================
                 PANEL DERECHO: LISTA DEL DÍA
            ============================== -->
            <div class="lg:col-span-3">

                <!-- Filtro de fecha -->
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0"/>
                        </svg>
                        Atrasos del día
                        <span class="text-xs font-normal bg-gray-700 border border-gray-600 text-gray-400 px-2.5 py-0.5 rounded-full">
                            <?= count($atrasos) ?>
                        </span>
                    </h2>
                    <form method="GET" action="index.php" class="flex items-center gap-2">
                        <input type="hidden" name="action" value="atrasos_registro">
                        <input type="date" name="fecha" value="<?= $fechaHoy ?>" max="<?= date('Y-m-d') ?>"
                               onchange="this.form.submit()"
                               class="bg-gray-800 text-white text-sm border border-gray-600 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer">
                    </form>
                </div>

                <!-- Lista -->
                <?php if (empty($atrasos)): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-12 text-center">
                    <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/>
                    </svg>
                    <p class="text-gray-400 font-medium">Sin atrasos registrados este día</p>
                    <p class="text-gray-600 text-sm mt-1">Todos llegaron a tiempo 👍</p>
                </div>

                <?php else: ?>
                <div class="space-y-2">
                    <?php foreach ($atrasos as $a): ?>
                    <div class="bg-gray-800 border <?= $a['justificado'] ? 'border-green-800/50' : 'border-gray-700' ?> rounded-xl px-4 py-3 flex items-center gap-4">

                        <!-- Hora -->
                        <div class="flex-shrink-0 text-center bg-gray-900 rounded-lg px-3 py-2 min-w-[60px]">
                            <span class="block text-lg font-bold font-mono text-amber-400 leading-none">
                                <?= substr($a['hora_llegada'], 0, 5) ?>
                            </span>
                            <span class="text-xs text-gray-600 mt-0.5 block">hrs</span>
                        </div>

                        <!-- Info alumno -->
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-semibold text-sm truncate">
                                <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat'] . ', ' . $a['nombre']) ?>
                            </p>
                            <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                <span class="text-xs text-gray-500 font-mono"><?= htmlspecialchars($a['run']) ?></span>
                                <span class="text-gray-700">·</span>
                                <span class="text-xs text-gray-400"><?= htmlspecialchars($a['curso']) ?></span>
                                <?php if ($a['observacion']): ?>
                                    <span class="text-gray-700">·</span>
                                    <span class="text-xs text-gray-500 italic truncate max-w-[180px]">
                                        "<?= htmlspecialchars($a['observacion']) ?>"
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Badge justificado -->
                        <div class="flex-shrink-0 flex items-center gap-2">
                            <?php if ($a['justificado']): ?>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-900/50 text-green-400 border border-green-700/50">
                                    Justificado
                                </span>
                            <?php else: ?>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-red-900/40 text-red-400 border border-red-800/40">
                                    Injustificado
                                </span>
                            <?php endif; ?>

                            <!-- Eliminar -->
                            <a href="index.php?action=atrasos_eliminar&id=<?= $a['id'] ?>&redirect=atrasos_registro&fecha=<?= $fechaHoy ?>"
                               onclick="return confirm('¿Eliminar este registro?')"
                               class="text-gray-600 hover:text-red-400 transition p-1 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Resumen del día -->
                <?php if (!empty($atrasos)):
                    $totalJ  = count(array_filter($atrasos, fn($a) => $a['justificado']));
                    $totalIJ = count($atrasos) - $totalJ;
                ?>
                <div class="mt-4 grid grid-cols-3 gap-3">
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-white"><?= count($atrasos) ?></p>
                        <p class="text-xs text-gray-500 mt-0.5">Total</p>
                    </div>
                    <div class="bg-gray-800 border border-green-800/40 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-green-400"><?= $totalJ ?></p>
                        <p class="text-xs text-gray-500 mt-0.5">Justificados</p>
                    </div>
                    <div class="bg-gray-800 border border-red-800/40 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-red-400"><?= $totalIJ ?></p>
                        <p class="text-xs text-gray-500 mt-0.5">Injustificados</p>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </main>
</div>
</body>

<script>let debounceTimer = null;

document.getElementById('buscar-run').addEventListener('input', function() {
    const q = this.value.trim();
    clearTimeout(debounceTimer);

    document.getElementById('sugerencias').classList.add('hidden');
    document.getElementById('error-run').classList.add('hidden');

    if (q.length < 2) return;

    debounceTimer = setTimeout(() => buscarSugerencias(q), 300);
});

async function buscarSugerencias(q) {
    try {
        const res  = await fetch(`index.php?action=atrasos_buscar_alumnos&q=${encodeURIComponent(q)}`);
        const data = await res.json();

        const box = document.getElementById('sugerencias');

        if (!data.length) {
            box.innerHTML = `<div class="px-4 py-3 text-sm text-gray-500">Sin resultados para "${q}"</div>`;
            box.classList.remove('hidden');
            return;
        }

        box.innerHTML = data.map(a => `
            <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-700 cursor-pointer border-b border-gray-700/50 last:border-0 transition"
                 onclick="seleccionarAlumno(${JSON.stringify(a).replace(/"/g, '&quot;')})">
                <div>
                    <p class="text-white text-sm font-semibold">
                        ${a.apepat} ${a.apemat}, ${a.nombre}
                    </p>
                    <p class="text-xs text-gray-500">
                        RUN: ${a.run}-${a.codver ?? ''} · ${a.curso} · ${a.anio}
                    </p>
                </div>
                <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        `).join('');

        box.classList.remove('hidden');

    } catch(e) {
        console.error('Error buscando alumnos:', e);
    }
}

function seleccionarAlumno(alumno) {
    document.getElementById('matricula_id').value = alumno.matricula_id;
    document.getElementById('buscar-run').value   = `${alumno.apepat} ${alumno.apemat}, ${alumno.nombre}`;

    document.getElementById('alumno-nombre').textContent = `${alumno.apepat} ${alumno.apemat}, ${alumno.nombre}`;
    document.getElementById('alumno-curso').textContent  = `Curso: ${alumno.curso} (${alumno.anio})`;
    document.getElementById('alumno-run').textContent    = `RUN: ${alumno.run}${alumno.codver ? '-' + alumno.codver : ''}`;

    document.getElementById('info-alumno').classList.remove('hidden');
    document.getElementById('sugerencias').classList.add('hidden');
    document.getElementById('btn-guardar').disabled = false;
    document.getElementById('error-run').classList.add('hidden');
}

// Cerrar sugerencias al hacer clic fuera
document.addEventListener('click', function(e) {
    if (!e.target.closest('#buscar-run') && !e.target.closest('#sugerencias')) {
        document.getElementById('sugerencias').classList.add('hidden');
    }
});

function actualizarMaxHora() {
    const ahora = new Date();
    const hh = String(ahora.getHours()).padStart(2, '0');
    const mm = String(ahora.getMinutes()).padStart(2, '0');
    document.getElementById('campo-hora').max = `${hh}:${mm}`;
}
setInterval(actualizarMaxHora, 60000);
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>