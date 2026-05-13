<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$asignaturaSeleccionada = (int) ($_GET['asignatura_id'] ?? 0);
?>

<body class="bg-gray-900 text-white min-h-screen">
    <div class="max-w-full px-4 py-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6 flex-wrap gap-4 max-w-screen-2xl mx-auto">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-600/20 p-3 rounded-xl border border-indigo-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                             M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">Ingreso de Notas
                    </p>
                    <h1 class="text-xl font-bold text-white">Libro de Notas</h1>
                    <p class="text-xs text-gray-500 mt-0.5"><?= $semestreActual ?>° Semestre</p>
                </div>
            </div>
            <a href="index.php?action=notas_panel_asignaturas&curso_id=<?= $_GET['curso_id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
                class="text-sm text-gray-400 hover:text-white border border-gray-700 hover:border-gray-500 px-4 py-2 rounded-lg transition">
                ← Volver
            </a>
        </div>

        <!-- Barra de controles -->
        <div class="max-w-screen-2xl mx-auto mb-5">
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 flex flex-wrap items-end gap-4">

                <!-- Selector de asignatura -->
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                        Asignatura <span class="text-red-400">*</span>
                    </label>
                    <select id="sel-asignatura" class="w-full bg-gray-900 border border-gray-600 rounded-lg px-3 py-2.5
                               text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="">— Seleccionar asignatura —</option>
                        <?php foreach ($asignaturas as $asig): ?>
                            <option value="<?= $asig['id'] ?>" data-nombre="<?= htmlspecialchars($asig['nombre']) ?>"
                                <?= $asignaturaSeleccionada == $asig['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($asig['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Badge semestre -->
                <span class="inline-flex items-center gap-1.5 text-xs px-3 py-2 rounded-full
                         bg-indigo-900/40 border border-indigo-700/50 text-indigo-300 font-semibold">
                    <?= $semestreActual ?>° Semestre activo
                </span>

                <!-- Toast estado -->
                <div id="toast-estado"
                    class="hidden items-center gap-2 text-xs font-semibold px-3 py-2 rounded-full border">
                </div>

            </div>
        </div>

        <!-- Aviso sin asignatura -->
        <div id="aviso-inicio" class="max-w-screen-2xl mx-auto <?= $asignaturaSeleccionada ? 'hidden' : '' ?>
                bg-gray-800 border border-gray-700 rounded-xl px-6 py-16 text-center">
            <div class="w-14 h-14 bg-gray-700/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <p class="text-gray-500 text-sm font-medium">Selecciona una asignatura para ver el libro de notas</p>
            <p class="text-gray-600 text-xs mt-1">Las notas se guardan automáticamente al editar cada celda</p>
        </div>

        <!-- GRILLA PRINCIPAL -->
        <div id="contenedor-grilla" class="max-w-screen-2xl mx-auto <?= $asignaturaSeleccionada ? '' : 'hidden' ?>">

            <div class="overflow-x-auto rounded-xl border border-gray-700 shadow-xl">
                <table class="text-sm border-collapse" style="width:100%;" id="tabla-grilla">
                    <thead id="thead-grilla"></thead>
                    <tbody id="tbody-grilla"></tbody>
                </table>
            </div>

            <?php if ($puedeEditar): ?>
                <div class="mt-4 flex items-center gap-3">
                    <button onclick="agregarEvaluacion()" class="flex items-center gap-2 px-4 py-2 bg-indigo-600/20 hover:bg-indigo-600/30
                           border border-indigo-600/40 text-indigo-300 hover:text-indigo-200
                           text-sm font-semibold rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva evaluación
                    </button>
                    <p class="text-xs text-gray-600">
                        Edita cualquier celda — se guarda automáticamente al salir · Enter para avanzar
                    </p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</body>

<script>
    // ── DATOS PHP → JS ───────────────────────────────────────────────────────────
    const ALUMNOS = <?= json_encode(array_values($alumnos)) ?>;
    const NOTAS_EXIST = <?= json_encode($notasExistentes) ?>;
    const SEMESTRE = <?= $semestreActual ?>;
    const CURSO_ID = <?= (int) ($_GET['curso_id'] ?? 0) ?>;
    const ANIO_ID = <?= (int) ($_GET['anio_id'] ?? 0) ?>;
    const PUEDE_EDITAR = <?= $puedeEditar ? 'true' : 'false' ?>;
    const HOY = '<?= date('Y-m-d') ?>';

    // Estado local: evaluaciones[asigId] = [{fecha, notas:{matId:{id,valor}}}]
    const evaluaciones = {};
    let asignaturaId = <?= $asignaturaSeleccionada ?: 0 ?>;

    // ── INICIALIZACIÓN ───────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        const sel = document.getElementById('sel-asignatura');

        sel.addEventListener('change', () => {
            asignaturaId = parseInt(sel.value) || 0;
            const grilla = document.getElementById('contenedor-grilla');
            const aviso = document.getElementById('aviso-inicio');
            if (!asignaturaId) {
                grilla.classList.add('hidden');
                aviso.classList.remove('hidden');
                return;
            }
            grilla.classList.remove('hidden');
            aviso.classList.add('hidden');
            cargarGrilla(asignaturaId);
        });

        if (asignaturaId) cargarGrilla(asignaturaId);
    });

    // ── CARGAR Y ESTRUCTURAR DATOS ───────────────────────────────────────────────
    function cargarGrilla(asigId) {
        const notasAsig = NOTAS_EXIST[asigId] ?? {};

        // 1. Recopilar todas las fechas únicas y ordenarlas
        const fechasSet = new Set();
        Object.values(notasAsig).forEach(arr => {
            arr.forEach(n => fechasSet.add(n.fecha));
        });
        const fechasOrdenadas = [...fechasSet].sort(); // orden cronológico

        // 2. Construir evaluaciones por fecha
        evaluaciones[asigId] = fechasOrdenadas.map(fecha => {
            const notas = {};
            Object.entries(notasAsig).forEach(([matId, arr]) => {
                // buscar la nota de este alumno en esta fecha específica
                const nota = arr.find(n => n.fecha === fecha);
                if (nota) {
                    notas[matId] = { id: parseInt(nota.id), valor: parseFloat(nota.nota) };
                }
            });
            return { fecha, notas };
        });

        // Si no hay evaluaciones aún, iniciar con una columna vacía
        if (evaluaciones[asigId].length === 0) {
            evaluaciones[asigId] = [{ fecha: HOY, notas: {} }];
        }

        renderGrilla(asigId);
    }
    
    // ── RENDERIZAR TABLA ─────────────────────────────────────────────────────────
    function renderGrilla(asigId) {
        const evals = evaluaciones[asigId] ?? [];
        const thead = document.getElementById('thead-grilla');
        const tbody = document.getElementById('tbody-grilla');

        // ── ENCABEZADO ──────────────────────────────────────────────────────────
        const colFecha = evals.map((ev, idx) => {
            const [, m, d] = ev.fecha.split('-');
            return `
        <th class="px-2 py-2 text-center bg-gray-900/70 border-b border-gray-700 min-w-[100px]
                   border-l border-l-gray-700/50">
            <div class="flex flex-col items-center gap-1">
                <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">
                    Nota ${idx + 1}
                </span>
                ${PUEDE_EDITAR
                    ? `<input type="date" value="${ev.fecha}" max="${HOY}"
                              onchange="cambiarFechaEval(${asigId}, ${idx}, this.value)"
                              class="w-[90px] bg-gray-800 border border-gray-600 text-gray-300
                                     text-[10px] rounded px-1 py-0.5 focus:outline-none
                                     focus:ring-1 focus:ring-indigo-500">`
                    : `<span class="text-[10px] text-gray-400">${d}/${m}</span>`
                }
            </div>
        </th>`;
        }).join('');

        thead.innerHTML = `
    <tr>
        <th class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-wider
                   text-gray-500 bg-gray-900/70 border-b border-gray-700 w-10">#</th>
        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider
                   text-gray-500 bg-gray-900/70 border-b border-gray-700 min-w-[180px]">
            Alumno
        </th>
        ${colFecha}
        ${PUEDE_EDITAR ? `<th class="px-2 py-2 bg-gray-900/70 border-b border-gray-700 w-8
                                      border-l border-l-gray-700/50"></th>` : ''}
        <th class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-wider
                   text-gray-500 bg-gray-900/70 border-b border-gray-700 w-20
                   border-l border-l-gray-600">
            Prom.
        </th>
    </tr>`;

        // ── FILAS ────────────────────────────────────────────────────────────────
        tbody.innerHTML = ALUMNOS.map(al => {
            const retirado = !!al.fecha_retiro;
            const matId = String(al.matricula_id);
            const bgFila = retirado ? 'bg-red-950/30' : 'hover:bg-gray-800/40';

            // Promedio
            let suma = 0, cnt = 0;
            evals.forEach(ev => {
                const n = ev.notas[matId];
                if (n) { suma += n.valor; cnt++; }
            });
            const prom = cnt > 0 ? (suma / cnt).toFixed(1) : null;
            const clsProm = prom === null ? 'text-gray-600'
                : parseFloat(prom) >= 4.0 ? 'text-green-400' : 'text-red-400';

            // Celdas de notas
            const celdas = evals.map((ev, idx) => {
                if (retirado) {
                    return `<td class="px-2 py-2 text-center border-t border-gray-700/40
                                   border-l border-l-gray-700/40">
                            <span class="text-gray-700 text-xs">—</span>
                        </td>`;
                }

                const n = ev.notas[matId];
                const val = n ? n.valor.toFixed(1) : '';
                const nid = n ? n.id : '';

                if (!PUEDE_EDITAR) {
                    const cls = n
                        ? (n.valor >= 4.0 ? 'text-green-400' : 'text-red-400')
                        : 'text-gray-600';
                    return `<td class="px-2 py-2 text-center border-t border-gray-700/40
                                   border-l border-l-gray-700/40">
                            <span class="font-bold text-sm ${cls}">
                                ${n ? n.valor.toFixed(1) : '—'}
                            </span>
                        </td>`;
                }

                const clsInput = n
                    ? (n.valor >= 4.0
                        ? 'border-green-700/60 bg-green-900/15 text-green-300 font-bold'
                        : 'border-red-700/60 bg-red-900/15 text-red-300 font-bold')
                    : 'border-gray-600 bg-gray-900 text-white';

                return `<td class="px-2 py-1.5 text-center border-t border-gray-700/40
                               border-l border-l-gray-700/40">
                        <input type="number"
                               step="0.1" min="1.0" max="7.0"
                               value="${val}"
                               placeholder="·"
                               data-mat="${matId}"
                               data-eval="${idx}"
                               data-asig="${asigId}"
                               data-nid="${nid}"
                               class="celda-nota w-16 text-center text-sm py-1 px-1 rounded-lg
                                      border focus:outline-none focus:ring-2 focus:ring-indigo-500
                                      placeholder-gray-700 transition ${clsInput}"
                               onblur="guardarCelda(this)"
                               onkeydown="navCelda(event,this)">
                    </td>`;
            }).join('');

            // Celda vacía para nueva col (si hay espacio)
            const celdaNueva = PUEDE_EDITAR && !retirado
                ? `<td class="border-t border-gray-700/40 border-l border-l-gray-700/40 w-8"></td>`
                : `<td class="border-t border-gray-700/40 border-l border-l-gray-700/40 w-8"></td>`;

            return `
        <tr class="transition-colors ${bgFila}" data-mat="${matId}">
            <td class="px-3 py-2.5 text-center text-xs font-bold
                       ${al.numero_lista ? 'text-indigo-400' : 'text-gray-600'}">
                ${al.numero_lista ?? '—'}
            </td>
            <td class="px-4 py-2.5 border-t border-gray-700/40">
                <p class="font-semibold text-white text-xs leading-tight">
                    ${escHtml(al.apepat + ' ' + al.apemat)}
                </p>
                <p class="text-gray-500 text-[10px]">${escHtml(al.nombre)}</p>
                ${retirado ? `<span class="text-red-400 text-[10px]">Retirado</span>` : ''}
            </td>
            ${celdas}
            ${celdaNueva}
            <td class="px-3 py-2.5 text-center border-t border-gray-700/40
                       border-l border-l-gray-600 font-bold text-sm ${clsProm}"
                id="prom-${matId}">
                ${prom ?? '—'}
            </td>
        </tr>`;
        }).join('');
    }

    // ── GUARDAR CELDA ────────────────────────────────────────────────────────────
    async function guardarCelda(input) {
        const val = input.value.trim();
        const matId = input.dataset.mat;
        const evalIdx = parseInt(input.dataset.eval);
        const asigId = parseInt(input.dataset.asig);
        const nid = input.dataset.nid;
        const ev = (evaluaciones[asigId] ?? [])[evalIdx];
        if (!ev) return;

        // Celda vacía sin nota previa → sin acción
        if (val === '' && !nid) return;

        // Celda vaciada con nota previa → restaurar valor anterior
        if (val === '') {
            const prev = ev.notas[matId];
            input.value = prev ? prev.valor.toFixed(1) : '';
            return;
        }

        const nota = parseFloat(val);
        if (isNaN(nota) || nota < 1.0 || nota > 7.0) {
            toast('⚠️ Rango inválido (1.0 – 7.0)', 'error');
            input.classList.add('ring-2', 'ring-red-500', 'border-red-500');
            setTimeout(() => {
                input.classList.remove('ring-2', 'ring-red-500', 'border-red-500');
                const prev = ev.notas[matId];
                input.value = prev ? prev.valor.toFixed(1) : '';
            }, 1500);
            return;
        }

        // Misma nota → sin acción
        if (ev.notas[matId] && ev.notas[matId].valor === nota) return;

        toast('Guardando…', 'info');

        try {
            let url, body;
            if (nid) {
                url = 'index.php?action=notas_ajax_update';
                body = new URLSearchParams({ id: nid, nota, fecha: ev.fecha, semestre: SEMESTRE });
            } else {
                url = 'index.php?action=notas_ajax_store';
                body = new URLSearchParams({
                    matricula_id: matId, asignatura_id: asigId,
                    semestre: SEMESTRE, nota, fecha: ev.fecha,
                    curso_id: CURSO_ID, anio_id: ANIO_ID,
                });
            }

            const res = await fetch(url, { method: 'POST', body });
            const json = await res.json();
            if (!json.ok) throw new Error(json.msg ?? 'Error');

            // ── Actualizar estado en memoria ──
            ev.notas[matId] = {
                id: json.id ?? parseInt(nid),
                valor: nota,
            };
            input.dataset.nid = ev.notas[matId].id;

            recolorInput(input, nota);
            actualizarProm(asigId, matId);
            toast('✓ Guardado', 'ok');

        } catch (err) {
            toast('✗ Error al guardar', 'error');
            console.error(err);
        }
    }

    // ── CAMBIAR FECHA DE COLUMNA ─────────────────────────────────────────────────
    async function cambiarFechaEval(asigId, evalIdx, nuevaFecha) {
        if (!nuevaFecha || nuevaFecha > HOY) return;
        const ev = (evaluaciones[asigId] ?? [])[evalIdx];
        if (!ev) return;

        const ids = Object.values(ev.notas).map(n => n.id).filter(Boolean);
        if (ids.length === 0) { ev.fecha = nuevaFecha; return; }

        try {
            const body = new URLSearchParams({ ids: ids.join(','), fecha: nuevaFecha });
            const res = await fetch('index.php?action=notas_ajax_update_fecha', { method: 'POST', body });
            const json = await res.json();
            if (!json.ok) throw new Error();
            ev.fecha = nuevaFecha;
            toast('✓ Fecha actualizada', 'ok');
        } catch {
            toast('✗ Error al actualizar fecha', 'error');
        }
    }

    // ── NUEVA COLUMNA DE EVALUACIÓN ──────────────────────────────────────────────
    function agregarEvaluacion() {
        if (!asignaturaId) { alert('Selecciona una asignatura primero.'); return; }
        evaluaciones[asignaturaId] = evaluaciones[asignaturaId] ?? [];
        evaluaciones[asignaturaId].push({ fecha: HOY, notas: {} });
        renderGrilla(asignaturaId);
        // Foco en la fecha del nuevo encabezado
        const inputs = document.querySelectorAll('#thead-grilla input[type="date"]');
        if (inputs.length) inputs[inputs.length - 1].focus();
    }

    // ── HELPERS ──────────────────────────────────────────────────────────────────
    function actualizarProm(asigId, matId) {
        const evals = evaluaciones[asigId] ?? [];
        let suma = 0, cnt = 0;
        evals.forEach(ev => {
            const n = ev.notas[matId];
            if (n) { suma += n.valor; cnt++; }
        });
        const prom = cnt > 0 ? (suma / cnt).toFixed(1) : null;
        const el = document.getElementById('prom-' + matId);
        if (!el) return;
        el.textContent = prom ?? '—';
        el.className = el.className
            .replace(/text-(green|red|gray)-\S+/g, '').trim()
            + ' ' + (prom === null ? 'text-gray-600'
                : parseFloat(prom) >= 4.0 ? 'text-green-400' : 'text-red-400');
    }

    function recolorInput(input, nota) {
        const base = 'celda-nota w-16 text-center text-sm py-1 px-1 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-700 transition font-bold';
        const color = nota >= 4.0
            ? 'border-green-700/60 bg-green-900/15 text-green-300'
            : 'border-red-700/60 bg-red-900/15 text-red-300';
        input.className = base + ' ' + color;
    }

    function navCelda(e, input) {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        const all = [...document.querySelectorAll('.celda-nota')];
        const next = all[all.indexOf(input) + 1];
        if (next) next.focus();
    }

    let toastTimer;
    function toast(msg, tipo) {
        const el = document.getElementById('toast-estado');
        const map = {
            ok: 'bg-green-900/40 border-green-700/50 text-green-300',
            error: 'bg-red-900/40 border-red-700/50 text-red-300',
            info: 'bg-gray-800 border-gray-600 text-gray-400',
        };
        clearTimeout(toastTimer);
        el.className = `flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-full border ${map[tipo] ?? map.info}`;
        el.textContent = msg;
        el.classList.remove('hidden');
        if (tipo !== 'info') toastTimer = setTimeout(() => el.classList.add('hidden'), 2000);
    }

    function escHtml(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>