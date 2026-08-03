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

<header class="page-header">
    <div class="flex items-center justify-between mb-0 flex-wrap gap-4 max-w-screen-2xl mx-auto px-4 py-5 sm:px-6">
        <div class="flex items-center gap-3">
            <div class="card-icon-badge w-10 h-10 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Ingreso de Notas</p>
                <h1 class="text-xl font-bold text-strong font-display">Libro de Notas</h1>
                <p class="text-xs text-muted mt-0.5"><?= $semestreSeleccionado ?>° Semestre</p>
            </div>
        </div>
        <a href="index.php?action=notas_panel_asignaturas&curso_id=<?= $_GET['curso_id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
            class="btn-secondary text-sm px-4 py-2 rounded-lg transition">
            ← Volver
        </a>
    </div>
</header>

<main>
    <div class="max-w-screen-2xl mx-auto px-4 py-6">

        <!-- Barra de controles -->
        <div class="mb-5">
            <div class="panel rounded-xl p-4 flex flex-wrap items-end gap-4">

                <div class="flex-1 min-w-[220px]">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-2">
                        Asignatura <span class="text-danger">*</span>
                    </label>
                    <select id="sel-asignatura" class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                        <option value="">— Seleccionar asignatura —</option>
                        <?php foreach ($asignaturas as $asig): ?>
                            <option value="<?= $asig['id'] ?>" data-nombre="<?= htmlspecialchars($asig['nombre']) ?>"
                                <?= $asignaturaSeleccionada == $asig['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($asig['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="min-w-[160px]">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-2">
                        Semestre
                    </label>
                    <select id="sel-semestre" class="input-field w-full text-sm rounded-lg px-3 py-2.5">
                        <option value="1" <?= $semestreSeleccionado === 1 ? 'selected' : '' ?>>1° Semestre</option>
                        <option value="2" <?= $semestreSeleccionado === 2 ? 'selected' : '' ?>>2° Semestre</option>
                    </select>
                </div>

                <div id="toast-estado"
                    class="hidden items-center gap-2 text-xs font-semibold px-3 py-2 rounded-full border"></div>
            </div>
        </div>

        <!-- Aviso sin asignatura -->
        <div id="aviso-inicio"
            class="panel rounded-xl px-6 py-16 text-center <?= $asignaturaSeleccionada ? 'hidden' : '' ?>">
            <div class="card-icon-badge w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <p class="text-muted text-sm font-medium">Selecciona una asignatura para ver el libro de notas</p>
            <p class="text-faint text-xs mt-1">Las notas se guardan automáticamente al editar cada celda</p>
        </div>

        <!-- GRILLA PRINCIPAL -->
        <div id="contenedor-grilla" class="<?= $asignaturaSeleccionada ? '' : 'hidden' ?>">

            <div class="overflow-x-auto rounded-xl shadow-xl" style="border: 1px solid var(--border-soft);">
                <table class="text-sm border-collapse" style="width:100%;" id="tabla-grilla">
                    <thead id="thead-grilla"></thead>
                    <tbody id="tbody-grilla"></tbody>
                </table>
            </div>

            <?php if ($puedeEditar): ?>
                <div class="mt-4 flex items-center gap-3">
                    <button onclick="agregarEvaluacion()"
                        class="btn-soft-info flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva evaluación
                    </button>
                    <p class="text-xs text-faint">
                        Edita cualquier celda — se guarda automáticamente al salir · Enter para avanzar
                    </p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<script>
    // ── DATOS PHP → JS ───────────────────────────────────────────────────────────
    const ALUMNOS = <?= json_encode(array_values($alumnos)) ?>;
    const NOTAS_EXIST = <?= json_encode($notasExistentes) ?>;
    const SEMESTRE = <?= $semestreSeleccionado ?>;
    const CURSO_ID = <?= (int) ($_GET['curso_id'] ?? 0) ?>;
    const ANIO_ID = <?= (int) ($_GET['anio_id'] ?? 0) ?>;
    const PUEDE_EDITAR = <?= $puedeEditar ? 'true' : 'false' ?>;
    const HOY = '<?= date('Y-m-d') ?>';

    const evaluaciones = {};
    let asignaturaId = <?= $asignaturaSeleccionada ?: 0 ?>;

    // ═══════════════ HELPERS DE CÁLCULO — espejo de Nota.php ═══════════════

    /**
     * Redondeo canónico — equivalente a Nota::redondear() en PHP.
     * El +1e-9 compensa el ruido de precisión binaria: sin él, 5.05 se
     * almacena como 5.04999999... y cae erróneamente a 5.0.
     */
    function redondearNota(valor, decimales = 1) {
        if (valor === null || valor === undefined || isNaN(valor)) return null;
        const factor = Math.pow(10, decimales);
        return Math.round(valor * factor + 1e-9) / factor;
    }

    /**
     * Promedio REDONDEADO — equivalente a Nota::promedio().
     * Devuelve null si no hay valores numéricos.
     */
    function promedioNotas(valores, decimales = 1) {
        const vals = valores
            .map(v => parseFloat(v))
            .filter(v => !isNaN(v));
        if (vals.length === 0) return null;
        const suma = vals.reduce((a, b) => a + b, 0);
        return redondearNota(suma / vals.length, decimales);
    }

    /** Texto para mostrar — equivalente a Nota::fmt(). */
    function fmtNota(valor, placeholder = '—') {
        return valor !== null && valor !== undefined
            ? valor.toFixed(1)
            : placeholder;
    }

    /** ¿Aprobado? — equivalente a Nota::aprobada(). */
    function aprobada(valor) {
        return valor !== null && valor !== undefined && valor >= 4.0;
    }

    /** Clase CSS — equivalente a Nota::clase(). */
    function claseNota(valor) {
        if (valor === null || valor === undefined) return 'text-faint';
        return valor >= 4.0 ? 'text-success' : 'text-danger';
    }

    /** Recolecta las notas de un alumno a lo largo de todas las evaluaciones. */
    function notasDeAlumno(asigId, matId) {
        const evals = evaluaciones[asigId] ?? [];
        const out = [];
        evals.forEach(ev => {
            const n = ev.notas[matId];
            if (n) out.push(n.valor);
        });
        return out;
    }

    // ═══════════════════════════════════════════════════════════════════════

    document.addEventListener('DOMContentLoaded', () => {
        const sel = document.getElementById('sel-asignatura');

        // Cambio de semestre → recarga conservando acción, curso, año y asignatura
        document.getElementById('sel-semestre').addEventListener('change', (e) => {
            const url = new URL(window.location.href);
            url.searchParams.set('semestre', e.target.value);
            const asig = document.getElementById('sel-asignatura').value;
            if (asig) url.searchParams.set('asignatura_id', asig);
            else url.searchParams.delete('asignatura_id');
            window.location.href = url.toString();
        });

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

    function cargarGrilla(asigId) {
        const notasAsig = NOTAS_EXIST[asigId] ?? {};
        const fechasSet = new Set();
        Object.values(notasAsig).forEach(arr => arr.forEach(n => fechasSet.add(n.fecha)));
        const fechasOrdenadas = [...fechasSet].sort();

        evaluaciones[asigId] = fechasOrdenadas.map(fecha => {
            const notas = {};
            Object.entries(notasAsig).forEach(([matId, arr]) => {
                const nota = arr.find(n => n.fecha === fecha);
                if (nota) notas[matId] = { id: parseInt(nota.id), valor: parseFloat(nota.nota) };
            });
            return { fecha, notas };
        });

        if (evaluaciones[asigId].length === 0) {
            evaluaciones[asigId] = [{ fecha: HOY, notas: {} }];
        }

        renderGrilla(asigId);
    }

    function renderGrilla(asigId) {
        const evals = evaluaciones[asigId] ?? [];
        const thead = document.getElementById('thead-grilla');
        const tbody = document.getElementById('tbody-grilla');

        // Colores de CSS vars aplicados vía style="" para que funcionen en modo claro y oscuro
        const bgHead = 'background: var(--table-head-bg);';
        const borderB = 'border-bottom: 1px solid var(--border-soft);';
        const borderL = 'border-left: 1px solid var(--border-soft);';

        const colFecha = evals.map((ev, idx) => {
            const [, m, d] = ev.fecha.split('-');
            return `
        <th style="${bgHead} ${borderB} ${borderL}" class="px-2 py-2 text-center min-w-[100px]">
            <div class="flex flex-col items-center gap-1">
                <span class="text-[10px] font-bold text-accent uppercase tracking-wider">Nota ${idx + 1}</span>
                ${PUEDE_EDITAR
                    ? `<input type="date" value="${ev.fecha}" max="${HOY}"
                              onchange="cambiarFechaEval(${asigId}, ${idx}, this.value)"
                              style="background: var(--surface-1); border: 1px solid var(--border-soft); color: var(--text-primary);"
                              class="w-[90px] text-[10px] rounded px-1 py-0.5 focus:outline-none">
                       <button type="button" title="Eliminar evaluación"
                               onclick="eliminarEvaluacion(${asigId}, ${idx})"
                               class="mt-0.5 inline-flex items-center justify-center w-5 h-5 rounded transition"
                               style="color: var(--danger);"
                               onmouseover="this.style.background='var(--danger-soft)'"
                               onmouseout="this.style.background='transparent'">
                           <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                     d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                           </svg>
                       </button>`
                    : `<span class="text-[10px] text-muted">${d}/${m}</span>`
                }
            </div>
        </th>`;
        }).join('');

        thead.innerHTML = `
    <tr>
        <th style="${bgHead} ${borderB}" class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-wider text-muted w-10">#</th>
        <th style="${bgHead} ${borderB}" class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-muted min-w-[180px]">Alumno</th>
        ${colFecha}
        ${PUEDE_EDITAR ? `<th style="${bgHead} ${borderB} ${borderL}" class="px-2 py-2 w-8"></th>` : ''}
        <th style="${bgHead} ${borderB} border-left: 2px solid var(--border-soft);" class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-wider text-muted w-20">Prom.</th>
    </tr>`;

        tbody.innerHTML = ALUMNOS.map(al => {
            const retirado = !!al.fecha_retiro;
            const matId = String(al.matricula_id);
            const bgFila = retirado ? 'background: var(--table-danger);' : '';

            const prom = promedioNotas(notasDeAlumno(asigId, matId));
            const clsProm = claseNota(prom);

            const celdas = evals.map((ev, idx) => {
                if (retirado) {
                    return `<td style="${borderL} border-top: 1px solid var(--border-soft);" class="px-2 py-2 text-center">
                            <span class="text-faint text-xs">—</span>
                        </td>`;
                }

                const n = ev.notas[matId];
                const val = n ? fmtNota(n.valor, '') : '';
                const nid = n ? n.id : '';

                if (!PUEDE_EDITAR) {
                    const cls = n ? claseNota(n.valor) : 'text-faint';
                    return `<td style="${borderL} border-top: 1px solid var(--border-soft);" class="px-2 py-2 text-center">
                            <span class="font-bold text-sm ${cls}">${n ? fmtNota(n.valor) : '—'}</span>
                        </td>`;
                }

                const inputStyle = n
                    ? (aprobada(n.valor)
                        ? 'border-color: var(--success); background: var(--success-soft); color: var(--success);'
                        : 'border-color: var(--danger); background: var(--danger-soft); color: var(--danger);')
                    : 'border-color: var(--border-soft); background: var(--surface-1); color: var(--text-primary);';

                return `<td style="${borderL} border-top: 1px solid var(--border-soft);" class="px-2 py-1.5 text-center">
                        <input type="number" step="0.1" min="1.0" max="7.0"
                               value="${val}" placeholder="·"
                               data-mat="${matId}" data-eval="${idx}"
                               data-asig="${asigId}" data-nid="${nid}"
                               style="${inputStyle} border-width: 1px; border-style: solid;"
                               class="celda-nota w-16 text-center text-sm py-1 px-1 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-azul-vivo
                                      placeholder-gray-700 transition font-bold"
                               onblur="guardarCelda(this)"
                               onkeydown="navCelda(event,this)">
                    </td>`;
            }).join('');

            const celdaNueva = `<td style="${borderL} border-top: 1px solid var(--border-soft);" class="w-8"></td>`;

            return `
        <tr style="${bgFila}" data-mat="${matId}">
            <td style="border-top: 1px solid var(--border-soft);" class="px-3 py-2.5 text-center text-xs font-bold ${al.numero_lista ? 'text-accent' : 'text-faint'}">
                ${al.numero_lista ?? '—'}
            </td>
            <td style="border-top: 1px solid var(--border-soft);" class="px-4 py-2.5">
                <p class="font-semibold text-strong text-xs leading-tight">${escHtml(al.apepat + ' ' + al.apemat)}</p>
                <p class="text-muted text-[10px]">${escHtml(al.nombre)}</p>
                ${retirado ? `<span class="text-danger text-[10px]">Retirado</span>` : ''}
            </td>
            ${celdas}
            ${celdaNueva}
            <td style="border-top: 1px solid var(--border-soft); border-left: 2px solid var(--border-soft);"
                class="px-3 py-2.5 text-center font-bold text-sm ${clsProm}"
                id="prom-${matId}">
                ${fmtNota(prom)}
            </td>
        </tr>`;
        }).join('');
    }

    async function guardarCelda(input) {
        const val = input.value.trim();
        const matId = input.dataset.mat;
        const evalIdx = parseInt(input.dataset.eval);
        const asigId = parseInt(input.dataset.asig);
        const nid = input.dataset.nid;
        const ev = (evaluaciones[asigId] ?? [])[evalIdx];
        if (!ev) return;

        if (val === '' && !nid) return;

        if (val === '') {
            const prev = ev.notas[matId];
            input.value = prev ? fmtNota(prev.valor, '') : '';
            return;
        }

        const nota = parseFloat(val);
        if (isNaN(nota) || nota < 1.0 || nota > 7.0) {
            toast('⚠️ Rango inválido (1.0 – 7.0)', 'error');
            setTimeout(() => {
                const prev = ev.notas[matId];
                input.value = prev ? fmtNota(prev.valor, '') : '';
            }, 1500);
            return;
        }

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

            ev.notas[matId] = { id: json.id ?? parseInt(nid), valor: nota };
            input.dataset.nid = ev.notas[matId].id;

            recolorInput(input, nota);
            actualizarProm(asigId, matId);
            toast('✓ Guardado', 'ok');

        } catch (err) {
            toast('✗ Error al guardar', 'error');
            console.error(err);
        }
    }

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

    function agregarEvaluacion() {
        if (!asignaturaId) { alert('Selecciona una asignatura primero.'); return; }
        evaluaciones[asignaturaId] = evaluaciones[asignaturaId] ?? [];
        evaluaciones[asignaturaId].push({ fecha: HOY, notas: {} });
        renderGrilla(asignaturaId);
        const inputs = document.querySelectorAll('#thead-grilla input[type="date"]');
        if (inputs.length) inputs[inputs.length - 1].focus();
    }

    async function eliminarEvaluacion(asigId, evalIdx) {
        const evals = evaluaciones[asigId] ?? [];
        const ev = evals[evalIdx];
        if (!ev) return;

        // IDs guardados en esta columna
        const ids = Object.values(ev.notas).map(n => n.id).filter(Boolean);
        const [y, m, d] = ev.fecha.split('-');
        const fechaLbl = `${d}/${m}/${y}`;

        // Columna solo local (sin notas guardadas) → quitar sin tocar el servidor.
        // Esto evita borrar por fecha una columna hermana que comparta el mismo día.
        if (ids.length === 0) {
            evals.splice(evalIdx, 1);
            if (evals.length === 0) evals.push({ fecha: HOY, notas: {} });
            renderGrilla(asigId);
            toast('✓ Evaluación eliminada', 'ok');
            return;
        }

        if (!confirm(`¿Eliminar la evaluación del ${fechaLbl} y sus ${ids.length} nota(s)? Esta acción no se puede deshacer.`)) return;

        try {
            const body = new URLSearchParams({
                curso_id: CURSO_ID, anio_id: ANIO_ID,
                asignatura_id: asigId, semestre: SEMESTRE, fecha: ev.fecha,
            });
            const res = await fetch('index.php?action=notas_ajax_eliminar_columna', { method: 'POST', body });
            const json = await res.json();
            if (!json.ok) throw new Error(json.msg ?? 'Error');

            evals.splice(evalIdx, 1);
            if (evals.length === 0) evals.push({ fecha: HOY, notas: {} });
            renderGrilla(asigId);

            const extra = (json.eliminadas ?? ids.length) - ids.length;
            toast(extra > 0
                ? `✓ Eliminada (${json.eliminadas} notas, incluidos ${extra} duplicados)`
                : '✓ Evaluación eliminada', 'ok');
        } catch (err) {
            toast('✗ Error al eliminar', 'error');
            console.error(err);
        }
    }

    function actualizarProm(asigId, matId) {
        const prom = promedioNotas(notasDeAlumno(asigId, matId));
        const el = document.getElementById('prom-' + matId);
        if (!el) return;
        el.textContent = fmtNota(prom);
        el.className = el.className.replace(/text-(success|danger|faint)\S*/g, '').trim()
            + ' ' + claseNota(prom);
    }

    function recolorInput(input, nota) {
        const s = aprobada(nota)
            ? 'border-color: var(--success); background: var(--success-soft); color: var(--success);'
            : 'border-color: var(--danger); background: var(--danger-soft); color: var(--danger);';
        input.style.cssText = s + 'border-width: 1px; border-style: solid;';
        input.classList.add('font-bold');
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
            ok: 'banner-success',
            error: 'banner-danger',
            info: 'banner-info',
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