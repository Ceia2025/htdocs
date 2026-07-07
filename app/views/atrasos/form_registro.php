<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$fechaHoy = $_GET['fecha'] ?? date('Y-m-d');
$guardado = $_GET['guardado'] ?? 0;

function calcularEdad(?string $fechanac): ?array {
    if (!$fechanac) return null;
    $hoy = new DateTime();
    $nac = new DateTime($fechanac);
    $diff = $hoy->diff($nac);
    $esMayor = $diff->y >= 18;
    $data = ['edad' => $diff->y, 'esMayor' => $esMayor];
    if (!$esMayor) {
        $cumple18 = clone $nac;
        $cumple18->modify('+18 years');
        $falta = $hoy->diff($cumple18);
        $data['faltaY'] = $falta->y;
        $data['faltaM'] = $falta->m;
        $data['faltaD'] = $falta->d;
    }
    return $data;
}
?>

<header class="page-header">
    <div class="mx-auto max-w-5xl px-4 py-5 sm:px-6 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Convivencia Escolar</p>
            <h1 class="text-2xl font-bold text-strong font-display">Registro de Atrasos</h1>
            <p class="text-sm text-muted mt-0.5">Portería — <?= date('d/m/Y', strtotime($fechaHoy)) ?></p>
        </div>
        <a href="index.php?action=atrasos_lista_curso"
           class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Ver por curso
        </a>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-6 sm:px-6">

    <?php if ($guardado): ?>
    <div class="banner-success flex items-center gap-3 mb-5 px-4 py-3 rounded-xl text-sm font-medium">
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
            <div class="panel rounded-xl overflow-hidden sticky top-6">

                <div class="px-5 py-4 border-b divider-soft flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full" style="background: var(--warn);"></div>
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Nuevo atraso</h2>
                </div>

                <!-- BÚSQUEDA -->
                <div class="px-5 pt-4 pb-3">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-2">
                        Buscar alumno
                    </label>
                    <div class="relative">
                        <input type="text" id="buscar-run" placeholder="RUN o nombre del alumno…" autocomplete="off"
                            class="input-field w-full text-sm rounded-lg px-3 py-2">
                        <div id="sugerencias"
                             class="dropdown-panel divide-soft hidden absolute z-50 w-full mt-1 rounded-lg shadow-xl overflow-y-auto max-h-72 divide-y">
                        </div>
                    </div>
                    <div id="error-run" class="hidden mt-2 text-xs text-danger font-medium"></div>
                </div>

                <!-- INFO ALUMNO -->
                <div id="info-alumno" class="hidden mx-5 mb-3 rounded-lg px-4 py-3"
                     style="background: var(--warn-soft); border: 1px solid var(--warn);">
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: var(--warn);">Alumno encontrado</p>
                    <p id="alumno-nombre" class="text-strong font-bold text-sm"></p>
                    <p id="alumno-curso"  class="text-muted text-xs mt-0.5"></p>
                    <p id="alumno-run"    class="text-faint text-xs font-mono"></p>
                    <div id="alumno-edad" class="mt-1.5"></div>
                </div>

                <!-- FORMULARIO -->
                <form method="POST" action="index.php?action=atrasos_guardar" id="form-atraso">
                    <input type="hidden" name="matricula_id" id="matricula_id" value="">

                    <div class="px-5 space-y-4 pb-5">

                        <!-- Fecha -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">
                                Fecha
                            </label>
                            <input type="date" name="fecha" id="campo-fecha"
                                   value="<?= $fechaHoy ?>" max="<?= date('Y-m-d') ?>"
                                   class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                        </div>

                        <!-- Hora llegada -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">
                                Hora de llegada
                            </label>
                            <input type="time" name="hora_llegada" id="campo-hora"
                                value=""
                                class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                        </div>

                        <!-- Justificado -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-2">
                                ¿Justificado?
                            </label>
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-2 input-field rounded-lg px-3 py-2 cursor-pointer transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/20">
                                    <input type="radio" name="justificado" value="1" class="accent-green-500">
                                    <span class="text-sm text-soft">Sí</span>
                                </label>
                                <label class="flex-1 flex items-center gap-2 input-field rounded-lg px-3 py-2 cursor-pointer transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/20">
                                    <input type="radio" name="justificado" value="0" checked class="accent-red-500">
                                    <span class="text-sm text-soft">No</span>
                                </label>
                            </div>
                        </div>

                        <!-- Observación -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">
                                Observación <span class="normal-case font-normal text-faint">(opcional)</span>
                            </label>
                            <textarea name="observacion" rows="2"
                                      placeholder="Motivo del atraso, indicaciones, etc."
                                      class="input-field w-full text-sm rounded-lg px-3 py-2 resize-none"></textarea>
                        </div>

                        <!-- Submit -->
                        <button type="submit" id="btn-guardar" disabled
                                class="btn-accent w-full flex items-center justify-center gap-2 font-bold px-4 py-3 rounded-xl transition disabled:opacity-40 disabled:cursor-not-allowed">
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
                <h2 class="text-base font-semibold text-strong flex items-center gap-2">
                    <svg class="w-5 h-5 text-warn" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0"/>
                    </svg>
                    Atrasos del día
                    <span class="chip-counter text-xs font-semibold px-2.5 py-0.5 rounded-full">
                        <?= count($atrasos) ?>
                    </span>
                </h2>
                <form method="GET" action="index.php" class="flex items-center gap-2">
                    <input type="hidden" name="action" value="atrasos_registro">
                    <input type="date" name="fecha" value="<?= $fechaHoy ?>" max="<?= date('Y-m-d') ?>"
                           onchange="this.form.submit()"
                           class="input-field text-sm rounded-lg px-3 py-1.5 cursor-pointer">
                </form>
            </div>

            <!-- Lista -->
            <?php if (empty($atrasos)): ?>
            <div class="panel rounded-xl px-6 py-12 text-center">
                <svg class="w-12 h-12 text-faint mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/>
                </svg>
                <p class="text-muted font-medium">Sin atrasos registrados este día</p>
                <p class="text-faint text-sm mt-1">Todos llegaron a tiempo 👍</p>
            </div>

            <?php else: ?>
            <div class="space-y-2">
                <?php foreach ($atrasos as $a): ?>
                <div class="panel rounded-xl px-4 py-3 flex items-center gap-4"
                     style="<?= $a['justificado'] ? 'border-color: var(--success);' : '' ?>">

                    <!-- Hora -->
                    <div class="flex-shrink-0 text-center rounded-lg px-3 py-2 min-w-[60px]"
                         style="background: var(--surface-1);">
                        <span class="block text-lg font-bold font-mono leading-none" style="color: var(--warn);">
                            <?= substr($a['hora_llegada'], 0, 5) ?>
                        </span>
                        <span class="text-xs text-faint mt-0.5 block">hrs</span>
                    </div>

                    <!-- Info alumno -->
                    <div class="flex-1 min-w-0">
                        <p class="text-strong font-semibold text-sm truncate">
                            <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat'] . ', ' . $a['nombre']) ?>
                        </p>
                        <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                            <span class="text-xs text-faint font-mono"><?= htmlspecialchars($a['run']) ?></span>
                            <span class="text-faint">·</span>
                            <span class="text-xs text-muted"><?= htmlspecialchars($a['curso']) ?></span>
                            <?php $edad = calcularEdad($a['fechanac'] ?? null); if ($edad): ?>
                            <span class="text-faint">·</span>
                            <span class="text-xs text-muted"><?= $edad['edad'] ?> años</span>
                            <?php if ($edad['esMayor']): ?>
                                <span class="chip-counter text-xs font-semibold px-2 py-0.5 rounded-full">
                                    Mayor
                                </span>
                            <?php else: ?>
                                <span class="chip-warn text-xs font-semibold px-2 py-0.5 rounded-full">
                                    Menor
                                </span>
                            <?php endif ?>
                            <?php endif ?>
                            <?php if ($a['observacion']): ?>
                                <span class="text-faint">·</span>
                                <span class="text-xs text-muted italic truncate max-w-[180px]">
                                    "<?= htmlspecialchars($a['observacion']) ?>"
                                </span>
                            <?php endif; ?>
                            <?php if (!empty($a['registrado_por_nombre'])): ?>
                                <span class="text-faint">·</span>
                                <span class="text-xs text-faint">
                                    Por: <span class="text-muted"><?= htmlspecialchars($a['registrado_por_nombre']) ?></span>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Badge justificado -->
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <?php if ($a['justificado']): ?>
                            <span class="banner-success text-xs font-semibold px-2.5 py-1 rounded-full">
                                Justificado
                            </span>
                        <?php else: ?>
                            <span class="chip-danger text-xs font-semibold px-2.5 py-1 rounded-full">
                                Injustificado
                            </span>
                        <?php endif; ?>
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
                <div class="panel rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-strong"><?= count($atrasos) ?></p>
                    <p class="text-xs text-muted mt-0.5">Total</p>
                </div>
                <div class="panel rounded-xl p-3 text-center" style="border-color: var(--success);">
                    <p class="text-2xl font-bold text-success"><?= $totalJ ?></p>
                    <p class="text-xs text-muted mt-0.5">Justificados</p>
                </div>
                <div class="panel rounded-xl p-3 text-center" style="border-color: var(--danger);">
                    <p class="text-2xl font-bold text-danger"><?= $totalIJ ?></p>
                    <p class="text-xs text-muted mt-0.5">Injustificados</p>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<script>
let debounceTimer = null;

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
            box.innerHTML = `<div class="px-4 py-3 text-sm text-muted">Sin resultados para "${q}"</div>`;
            box.classList.remove('hidden');
            return;
        }

        box.innerHTML = data.map(a => `
            <div class="dropdown-link flex items-center justify-between px-4 py-3 cursor-pointer transition"
                 onclick="seleccionarAlumno(${JSON.stringify(a).replace(/"/g, '&quot;')})">
                <div>
                    <p class="text-strong text-sm font-semibold">
                        ${a.apepat} ${a.apemat}, ${a.nombre}
                    </p>
                    <p class="text-xs text-muted">
                        RUN: ${a.run}-${a.codver ?? ''} · ${a.curso} · ${a.anio}
                    </p>
                </div>
                <svg class="w-4 h-4 text-faint flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        `).join('');

        box.classList.remove('hidden');

    } catch(e) {
        console.error('Error buscando alumnos:', e);
    }
}

function calcularEdadJS(fechanac) {
    if (!fechanac) return null;
    const hoy = new Date();
    const nac = new Date(fechanac);
    let edad = hoy.getFullYear() - nac.getFullYear();
    const m = hoy.getMonth() - nac.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;
    return { edad, esMayor: edad >= 18 };
}

function seleccionarAlumno(alumno) {
    document.getElementById('matricula_id').value = alumno.matricula_id;
    document.getElementById('buscar-run').value   = `${alumno.apepat} ${alumno.apemat}, ${alumno.nombre}`;

    document.getElementById('alumno-nombre').textContent = `${alumno.apepat} ${alumno.apemat}, ${alumno.nombre}`;
    document.getElementById('alumno-curso').textContent  = `Curso: ${alumno.curso} (${alumno.anio})`;
    document.getElementById('alumno-run').textContent    = `RUN: ${alumno.run}${alumno.codver ? '-' + alumno.codver : ''}`;

    const edadEl = document.getElementById('alumno-edad');
    const e = calcularEdadJS(alumno.fechanac);
    if (e) {
        edadEl.innerHTML = e.esMayor
            ? `<span class="text-xs text-muted">${e.edad} años</span>
               <span class="chip-counter ml-1.5 inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full">Mayor de edad</span>`
            : `<span class="text-xs text-muted">${e.edad} años</span>
               <span class="chip-warn ml-1.5 inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full">Menor de edad</span>`;
    } else {
        edadEl.innerHTML = '<span class="text-xs text-faint italic">Sin fecha de nacimiento</span>';
    }

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

(() => {
    const now = new Date();
    const hh = String(now.getHours()).padStart(2, '0');
    const mm = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('campo-hora').value = hh + ':' + mm;
})();
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>