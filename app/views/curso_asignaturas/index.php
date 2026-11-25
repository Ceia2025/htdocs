<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Agrupar asignaturas por curso para el acordeón
$agrupado = [];
foreach ($cursoAsignaturas as $ca) {
    $agrupado[$ca['curso']][] = $ca;
}

$anioLabel  = $anioSeleccionado['anio'] ?? '—';
$mallaVacia = $anio_id && empty($totalesPorAnio[(int) $anio_id]);

// Año sugerido como origen para copiar: el más reciente ANTERIOR al seleccionado que tenga malla
// (si no hay anterior, el más reciente de los demás que tenga malla)
$candidatos = array_filter($anios, fn($a) =>
    (int) $a['id'] !== (int) $anio_id && !empty($totalesPorAnio[(int) $a['id']]));
$anteriores = array_filter($candidatos, fn($a) =>
    isset($anioSeleccionado['anio']) && (int) $a['anio'] < (int) $anioSeleccionado['anio']);
$pool = $anteriores ?: $candidatos;
usort($pool, fn($x, $y) => (int) $y['anio'] <=> (int) $x['anio']);
$origenSugerido = $pool[0] ?? null;
?>

<main class="min-h-screen app-shell">
    <header class="page-header">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Malla Curricular</h1>
            <p class="mt-1 text-sm text-accent font-medium">Asignaturas de cada curso, por año académico</p>
        </div>
    </header>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

        <!-- FILTROS Y ACCIONES -->
        <div class="panel mb-6 p-6 rounded-2xl shadow-lg flex flex-col md:flex-row md:items-end justify-between gap-5">
            <form method="get" action="index.php" class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <input type="hidden" name="action" value="curso_asignaturas">

                <div class="flex flex-col gap-2">
                    <label for="anio_id" class="text-xs font-bold text-muted uppercase tracking-widest ml-1">Año académico</label>
                    <select name="anio_id" id="anio_id" onchange="this.form.submit()"
                        class="input-field w-full sm:w-44 rounded-xl px-4 py-2.5 cursor-pointer">
                        <?php foreach ($anios as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= (int) $a['id'] === (int) $anio_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['anio']) ?>
                                <?= empty($totalesPorAnio[(int) $a['id']]) ? '(sin malla)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="curso_id" class="text-xs font-bold text-muted uppercase tracking-widest ml-1">Curso</label>
                    <select name="curso_id" id="curso_id" onchange="this.form.submit()"
                        class="input-field w-full sm:w-72 rounded-xl px-4 py-2.5 cursor-pointer">
                        <option value="">Todos los cursos</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id'] ?>" <?= (int) $curso['id'] === (int) $curso_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($curso['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" onclick="abrirModal('modal-copiar')"
                    class="btn-secondary inline-flex items-center justify-center gap-2 px-5 py-2.5 font-semibold rounded-xl shadow transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Copiar malla
                </button>
                <button type="button" onclick="abrirVincular()"
                    class="btn-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 font-semibold rounded-xl shadow transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Vincular asignaturas
                </button>
            </div>
        </div>

        <!-- MENSAJES -->
        <?php if ($flashOk): ?>
            <div class="banner-success mb-5 text-sm px-4 py-3 rounded-xl"><?= htmlspecialchars($flashOk) ?></div>
        <?php endif; ?>
        <?php if ($flashError): ?>
            <div class="banner-danger mb-5 text-sm px-4 py-3 rounded-xl"><?= htmlspecialchars($flashError) ?></div>
        <?php endif; ?>

        <?php if ($mallaVacia): ?>
            <div class="banner-warning mb-6 text-sm px-5 py-4 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <span>
                    El año <strong><?= htmlspecialchars($anioLabel) ?></strong> todavía no tiene malla.
                    <?php if ($origenSugerido): ?>
                        Puedes copiar la del <?= htmlspecialchars($origenSugerido['anio']) ?> y después ajustar lo que cambia.
                    <?php endif; ?>
                </span>
                <?php if ($origenSugerido): ?>
                    <button type="button" onclick="abrirModal('modal-copiar')"
                        class="btn-primary px-4 py-2 text-sm font-semibold rounded-lg shadow transition whitespace-nowrap">
                        Copiar malla <?= htmlspecialchars($origenSugerido['anio']) ?> → <?= htmlspecialchars($anioLabel) ?>
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- MALLA -->
        <?php if (!empty($agrupado)): ?>
            <div class="space-y-4">
                <?php foreach ($agrupado as $cursoNombre => $asignaturasCurso):
                    $idUnico = md5($cursoNombre);
                    $cursoIdGrupo = (int) $asignaturasCurso[0]['curso_id'];
                    ?>
                    <div class="panel overflow-hidden rounded-2xl shadow-sm">

                        <button type="button" class="w-full px-6 py-5 flex justify-between items-center text-left group"
                            onclick="toggleCollapse('<?= $idUnico ?>')">
                            <div class="flex items-center gap-4">
                                <div class="avatar-badge w-10 h-10 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-strong"><?= htmlspecialchars($cursoNombre) ?></h3>
                                    <p class="text-xs text-muted font-medium">
                                        <?= count($asignaturasCurso) ?> asignatura(s) en <?= htmlspecialchars($anioLabel) ?>
                                    </p>
                                </div>
                            </div>
                            <svg id="ic_<?= $idUnico ?>" class="w-6 h-6 text-muted transition-transform duration-300"
                                fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div id="c_<?= $idUnico ?>" class="<?= $curso_id ? '' : 'hidden' ?> px-6 pb-6">
                            <div class="flex flex-col gap-2 mt-1">
                                <?php foreach ($asignaturasCurso as $ca):
                                    $notas = (int) $ca['total_notas'];
                                    $planes = (int) $ca['total_planificaciones'];
                                    $protegida = $notas > 0 || $planes > 0;
                                    ?>
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-4 py-3 rounded-xl"
                                        style="background: var(--surface-1); border: 1px solid var(--border-soft);">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="text-base font-medium text-strong"><?= htmlspecialchars($ca['asignatura']) ?></span>
                                            <?php if ($notas > 0): ?>
                                                <span class="chip-counter inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"><?= $notas ?> nota(s)</span>
                                            <?php endif; ?>
                                            <?php if ($planes > 0): ?>
                                                <span class="chip-counter inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold">Planificada</span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <?php if ($protegida): ?>
                                                <span class="text-xs text-faint italic" title="Tiene notas o planificaciones en este curso y año">
                                                    🔒 En uso
                                                </span>
                                            <?php else: ?>
                                                <button type="button"
                                                    onclick="abrirEditar(<?= (int) $ca['id'] ?>, <?= (int) $ca['asignatura_id'] ?>, <?= htmlspecialchars(json_encode($cursoNombre), ENT_QUOTES) ?>)"
                                                    class="btn-soft-info px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                                                    Cambiar
                                                </button>
                                                <a href="index.php?action=curso_asignaturas_delete&id=<?= (int) $ca['id'] ?>"
                                                    onclick="return confirm('¿Quitar <?= htmlspecialchars(addslashes($ca['asignatura']), ENT_QUOTES) ?> de la malla <?= htmlspecialchars($anioLabel) ?> de este curso?')"
                                                    class="btn-soft-danger px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                                                    Quitar
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <button type="button" onclick="abrirVincular(<?= $cursoIdGrupo ?>)"
                                    class="mt-2 self-start text-sm font-semibold text-accent hover:underline">
                                    + Agregar asignaturas a este curso
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif (!$mallaVacia): ?>
            <div class="panel text-center py-16 rounded-3xl">
                <p class="text-muted text-lg">Este curso no tiene asignaturas en la malla <?= htmlspecialchars($anioLabel) ?>.</p>
            </div>
        <?php endif; ?>

        <div class="mt-12 flex justify-center">
            <a href="index.php?action=dashboard"
                class="btn-secondary inline-flex items-center gap-2 px-6 py-2.5 font-semibold rounded-xl shadow transition">
                ← Volver al Panel Principal
            </a>
        </div>
    </div>
</main>

<!-- ═══════════ MODAL: VINCULAR ASIGNATURAS ═══════════ -->
<div id="modal-vincular" class="modal-saat hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,.55);">
    <div class="panel w-full max-w-2xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">
        <form method="post" action="index.php?action=curso_asignaturas_store" class="flex flex-col min-h-0">
            <input type="hidden" name="anio_id" value="<?= (int) $anio_id ?>">

            <div class="px-6 py-4 border-b divider-soft">
                <h2 class="text-lg font-bold text-strong">Vincular asignaturas · Malla <?= htmlspecialchars($anioLabel) ?></h2>
                <p class="text-xs text-muted mt-0.5">Marca todas las asignaturas que tendrá el curso este año.</p>
            </div>

            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div>
                    <label for="vincular-curso" class="block text-sm font-medium text-soft mb-2">Curso</label>
                    <select name="curso_id" id="vincular-curso" required onchange="marcarAsignadas()"
                        class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                        <option value="">-- Selecciona un curso --</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id'] ?>"><?= htmlspecialchars($curso['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-soft">Asignaturas</label>
                        <input type="text" id="vincular-buscar" placeholder="Buscar…" oninput="filtrarAsignaturas()"
                            class="input-field rounded-lg px-3 py-1.5 text-sm w-48">
                    </div>
                    <div id="vincular-lista" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php foreach ($asignaturas as $asig): ?>
                            <label class="vincular-item flex items-center gap-3 px-3 py-2 rounded-lg cursor-pointer"
                                style="background: var(--surface-1); border: 1px solid var(--border-soft);"
                                data-nombre="<?= htmlspecialchars(mb_strtolower($asig['nombre'], 'UTF-8')) ?>">
                                <input type="checkbox" name="asignatura_id[]" value="<?= (int) $asig['id'] ?>" class="h-4 w-4">
                                <span class="text-sm text-strong"><?= htmlspecialchars($asig['nombre']) ?></span>
                                <span class="vincular-ya ml-auto text-xs text-faint hidden">ya está</span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t divider-soft flex justify-end gap-3">
                <button type="button" onclick="cerrarModales()" class="btn-secondary px-5 py-2 font-semibold rounded-lg">Cancelar</button>
                <button type="submit" class="btn-primary px-5 py-2 font-semibold rounded-lg">Agregar a la malla</button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════════ MODAL: CAMBIAR ASIGNATURA ═══════════ -->
<div id="modal-editar" class="modal-saat hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,.55);">
    <div class="panel w-full max-w-md rounded-2xl shadow-2xl">
        <form method="post" id="form-editar" action="">
            <div class="px-6 py-4 border-b divider-soft">
                <h2 class="text-lg font-bold text-strong">Cambiar asignatura</h2>
                <p id="editar-curso" class="text-xs text-muted mt-0.5"></p>
            </div>
            <div class="px-6 py-5">
                <label for="editar-asignatura" class="block text-sm font-medium text-soft mb-2">Asignatura</label>
                <select name="asignatura_id" id="editar-asignatura" required class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                    <?php foreach ($asignaturas as $asig): ?>
                        <option value="<?= (int) $asig['id'] ?>"><?= htmlspecialchars($asig['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-faint mt-3">Solo se puede cambiar mientras no tenga notas ni planificaciones.</p>
            </div>
            <div class="px-6 py-4 border-t divider-soft flex justify-end gap-3">
                <button type="button" onclick="cerrarModales()" class="btn-secondary px-5 py-2 font-semibold rounded-lg">Cancelar</button>
                <button type="submit" class="btn-primary px-5 py-2 font-semibold rounded-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════════ MODAL: COPIAR MALLA ═══════════ -->
<div id="modal-copiar" class="modal-saat hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,.55);">
    <div class="panel w-full max-w-md rounded-2xl shadow-2xl">
        <form method="post" action="index.php?action=curso_asignaturas_copiar">
            <div class="px-6 py-4 border-b divider-soft">
                <h2 class="text-lg font-bold text-strong">Copiar malla de un año a otro</h2>
                <p class="text-xs text-muted mt-0.5">Solo agrega lo que falta en el año de destino: no borra ni duplica nada.</p>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label for="copiar-origen" class="block text-sm font-medium text-soft mb-2">Copiar desde</label>
                    <select name="anio_origen" id="copiar-origen" required class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                        <?php foreach ($anios as $a): if (empty($totalesPorAnio[(int) $a['id']])) continue; ?>
                            <option value="<?= $a['id'] ?>" <?= $origenSugerido && (int) $origenSugerido['id'] === (int) $a['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['anio']) ?> (<?= (int) $totalesPorAnio[(int) $a['id']] ?> asignaturas)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="copiar-destino" class="block text-sm font-medium text-soft mb-2">Hacia</label>
                    <select name="anio_destino" id="copiar-destino" required class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                        <?php foreach ($anios as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= (int) $a['id'] === (int) $anio_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['anio']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="copiar-curso" class="block text-sm font-medium text-soft mb-2">Cursos</label>
                    <select name="curso_id" id="copiar-curso" class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                        <option value="">Todos los cursos</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id'] ?>" <?= (int) $curso['id'] === (int) $curso_id ? 'selected' : '' ?>>
                                Solo <?= htmlspecialchars($curso['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="px-6 py-4 border-t divider-soft flex justify-end gap-3">
                <button type="button" onclick="cerrarModales()" class="btn-secondary px-5 py-2 font-semibold rounded-lg">Cancelar</button>
                <button type="submit" class="btn-primary px-5 py-2 font-semibold rounded-lg"
                    onclick="return validarCopia()">Copiar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const ASIGNADAS_POR_CURSO = <?= json_encode($asignadasPorCurso ?: new stdClass()) ?>;

    function toggleCollapse(id) {
        const div = document.getElementById('c_' + id);
        const icon = document.getElementById('ic_' + id);
        const abrir = div.classList.contains('hidden');
        div.classList.toggle('hidden', !abrir);
        icon.style.transform = abrir ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    function abrirModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function cerrarModales() {
        document.querySelectorAll('.modal-saat').forEach(m => m.classList.add('hidden'));
        document.body.style.overflow = '';
    }

    // Cerrar con Esc o clic en el fondo oscuro
    document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModales(); });
    document.querySelectorAll('.modal-saat').forEach(m =>
        m.addEventListener('click', e => { if (e.target === m) cerrarModales(); }));

    /* ── Vincular ── */
    function abrirVincular(cursoId) {
        const sel = document.getElementById('vincular-curso');
        sel.value = cursoId || sel.value || '<?= (int) $curso_id ?: '' ?>';
        document.getElementById('vincular-buscar').value = '';
        filtrarAsignaturas();
        marcarAsignadas();
        abrirModal('modal-vincular');
    }

    // Las que el curso ya tiene en este año quedan marcadas y deshabilitadas
    function marcarAsignadas() {
        const cursoId = document.getElementById('vincular-curso').value;
        const ya = (ASIGNADAS_POR_CURSO[cursoId] || []).map(Number);
        document.querySelectorAll('.vincular-item').forEach(item => {
            const chk = item.querySelector('input');
            const esta = ya.includes(Number(chk.value));
            chk.checked = esta;
            chk.disabled = esta;
            item.querySelector('.vincular-ya').classList.toggle('hidden', !esta);
            item.style.opacity = esta ? '.55' : '1';
        });
    }

    function filtrarAsignaturas() {
        const q = document.getElementById('vincular-buscar').value.trim().toLowerCase();
        document.querySelectorAll('.vincular-item').forEach(item => {
            item.classList.toggle('hidden', q !== '' && !item.dataset.nombre.includes(q));
        });
    }

    /* ── Editar ── */
    function abrirEditar(id, asignaturaId, cursoNombre) {
        document.getElementById('form-editar').action = 'index.php?action=curso_asignaturas_update&id=' + id;
        document.getElementById('editar-asignatura').value = asignaturaId;
        document.getElementById('editar-curso').textContent = cursoNombre + ' · Malla <?= htmlspecialchars($anioLabel, ENT_QUOTES) ?>';
        abrirModal('modal-editar');
    }

    /* ── Copiar ── */
    function validarCopia() {
        const o = document.getElementById('copiar-origen');
        const d = document.getElementById('copiar-destino');
        if (!o.value) { alert('No hay ningún año con malla para copiar.'); return false; }
        if (o.value === d.value) { alert('El año de origen y el de destino deben ser distintos.'); return false; }
        return confirm(`¿Copiar la malla ${o.options[o.selectedIndex].text.split(' ')[0]} al año ${d.options[d.selectedIndex].text.trim()}?`);
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>
