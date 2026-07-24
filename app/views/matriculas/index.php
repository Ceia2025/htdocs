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

<header class="page-header">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Mantenedor de Matrículas</h1>
    </div>
</header>

<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- BÚSQUEDA -->
        <form method="GET" action="index.php"
              class="panel rounded-2xl p-5 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-7 gap-3 mb-6 shadow-lg">
            <input type="hidden" name="action" value="matriculas">

            <input type="text" name="nombre" placeholder="Nombre alumno"
                value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>"
                class="input-field rounded-xl px-4 py-2.5 text-sm lg:col-span-2">

            <input type="text" name="rut" placeholder="RUN"
                value="<?= htmlspecialchars($_GET['rut'] ?? '') ?>"
                class="input-field rounded-xl px-4 py-2.5 text-sm">

            <input type="text" name="numero_matricula" placeholder="N° Matrícula"
                value="<?= htmlspecialchars($_GET['numero_matricula'] ?? '') ?>"
                class="input-field rounded-xl px-4 py-2.5 text-sm">

            <select name="anio" class="input-field rounded-xl px-4 py-2.5 text-sm cursor-pointer">
                <option value="">Año</option>
                <?php foreach ($anios as $a): ?>
                    <option value="<?= $a['anio'] ?>" <?= ($_GET['anio'] ?? '') == $a['anio'] ? 'selected' : '' ?>>
                        <?= $a['anio'] ?>
                    </option>
                <?php endforeach ?>
            </select>

            <select name="curso" class="input-field rounded-xl px-4 py-2.5 text-sm cursor-pointer">
                <option value="">Curso</option>
                <?php foreach ($cursos as $c): ?>
                    <option value="<?= $c['nombre'] ?>" <?= ($_GET['curso'] ?? '') == $c['nombre'] ? 'selected' : '' ?>>
                        <?= $c['nombre'] ?>
                    </option>
                <?php endforeach ?>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1 font-semibold rounded-xl px-4 py-2.5 text-sm transition">
                    Buscar
                </button>
                <a href="index.php?action=matriculas"
                   class="btn-secondary flex-1 text-center font-semibold rounded-xl px-4 py-2.5 text-sm transition">
                    Limpiar
                </a>
            </div>
        </form>

        <!-- CONTADORES -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="panel rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                <span class="text-4xl font-bold text-accent"><?= count($matriculas) ?></span>
                <div>
                    <p class="text-strong font-semibold">Total matrículas</p>
                    <p class="text-xs text-muted">
                        <?php if (!empty($_GET['nombre']) || !empty($_GET['rut']) || !empty($_GET['anio']) || !empty($_GET['curso']) || !empty($_GET['numero_matricula'])): ?>
                            Resultado del filtro aplicado
                        <?php else: ?>
                            Sin filtros aplicados
                        <?php endif ?>
                    </p>
                </div>
            </div>

            <?php if (!empty($_GET['curso'])): ?>
                <div class="panel rounded-2xl px-6 py-4 flex items-center gap-4 shadow" style="border-color: var(--azul-claro);">
                    <span class="text-4xl font-bold" style="color: var(--azul-claro);"><?= count($matriculas) ?></span>
                    <div>
                        <p class="text-strong font-semibold">Curso filtrado</p>
                        <p class="text-xs text-muted"><?= htmlspecialchars($_GET['curso']) ?></p>
                    </div>
                </div>
            <?php endif ?>

            <?php if (!empty($_GET['anio'])): ?>
                <div class="panel rounded-2xl px-6 py-4 flex items-center gap-4 shadow" style="border-color: var(--warn);">
                    <span class="text-4xl font-bold text-warn"><?= htmlspecialchars($_GET['anio']) ?></span>
                    <div>
                        <p class="text-strong font-semibold">Año escolar</p>
                        <p class="text-xs text-muted"><?= count($matriculas) ?> alumnos en este año</p>
                    </div>
                </div>
            <?php endif ?>
        </div>

        <!-- BOTÓN CREAR -->
        <div class="mb-4 flex justify-end">
            <a href="index.php?action=matricula_create"
               class="btn-primary inline-flex items-center gap-2 px-4 py-2 font-semibold rounded-lg shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Matrícula
            </a>
        </div>

        <!-- TABLA -->
        <div class="panel rounded-2xl overflow-hidden shadow-lg overflow-x-auto">
            <table class="data-table min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">N° Lista</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">N° Matrícula</th>
                        <th class="px-5 py-3 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Alumno</th>
                        <th class="px-5 py-3 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Curso</th>
                        <th class="px-5 py-3 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Año</th>
                        <th class="px-5 py-3 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Fecha ingreso</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matriculas as $m):
                        $nombreAl  = $m['alumno_nombre'] ?? $m['nombre_completo'] ?? '';
                        $iniciales = strtoupper(substr($nombreAl, 0, 2));
                        $retirado  = !empty($m['fecha_retiro']);
                    ?>
                        <tr class="<?= $retirado ? 'row-retired' : '' ?>">

                            <!-- N° lista -->
                            <td class="px-5 py-4 text-center">
                                <?php if ($m['numero_lista']): ?>
                                    <span class="avatar-badge inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold">
                                        <?= $m['numero_lista'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-faint text-xs">—</span>
                                <?php endif; ?>
                            </td>

                            <!-- N° matrícula -->
                            <td class="px-5 py-4 text-center">
                                <?php if (!empty($m['numero_matricula'])): ?>
                                    <span class="report-badge text-xs font-bold px-2.5 py-1 rounded-lg" style="--r-accent:var(--azul-claro);--r-soft:rgba(59,130,246,0.1);--r-border:rgba(59,130,246,0.25);">
                                        <?= htmlspecialchars($m['numero_matricula']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-faint text-xs">—</span>
                                <?php endif; ?>
                            </td>

                            <!-- Alumno -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="avatar-badge w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        <?= $iniciales ?>
                                    </div>
                                    <span class="text-strong font-semibold"><?= htmlspecialchars($nombreAl) ?></span>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-soft text-xs"><?= htmlspecialchars($m['curso_nombre'] ?? $m['curso'] ?? '') ?></td>
                            <td class="px-5 py-4 text-soft text-xs"><?= htmlspecialchars($m['anio_escolar'] ?? $m['anio'] ?? '') ?></td>
                            <td class="px-5 py-4 text-soft text-xs">
                                <?= $m['fecha_matricula'] ? date('d/m/Y', strtotime($m['fecha_matricula'])) : '—' ?>
                            </td>

                            <!-- Acciones -->
                            <td class="px-5 py-4 text-center">
                                <div class="flex justify-center gap-2 flex-wrap">

                                    <a href="index.php?action=perfil_academico&id=<?= $m['id'] ?>"
                                       class="btn-soft-success px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        Perfil Académico
                                    </a>

                                    <button type="button"
                                        onclick="abrirModalEditar(this)"
                                        data-id="<?= $m['id'] ?>"
                                        data-alumno-id="<?= $m['alumno_id'] ?>"
                                        data-curso-id="<?= $m['curso_id'] ?>"
                                        data-anio-id="<?= $m['anio_id'] ?>"
                                        data-fecha="<?= htmlspecialchars($m['fecha_matricula'] ?? '', ENT_QUOTES) ?>"
                                        data-lista="<?= htmlspecialchars($m['numero_lista'] ?? '', ENT_QUOTES) ?>"
                                        data-matricula-num="<?= htmlspecialchars($m['numero_matricula'] ?? '', ENT_QUOTES) ?>"
                                        class="btn-soft-info px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        Editar
                                    </button>

                                    <?php if ($retirado): ?>
                                        <span class="chip-danger px-3 py-1.5 rounded-lg text-xs font-semibold italic">
                                            Retirado <?= date('d/m/Y', strtotime($m['fecha_retiro'])) ?>
                                        </span>
                                        <form method="POST" action="index.php?action=matricula_reintegrar"
                                            onsubmit="return confirm('¿Reintegrar a este alumno? Se restaurará su matrícula activa.')">
                                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                            <button type="submit"
                                                class="btn-soft-success px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                                Reintegrar
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button type="button"
                                            onclick="abrirModalRetiro(<?= $m['id'] ?>, '<?= htmlspecialchars($nombreAl) ?>')"
                                            class="btn-soft-warn px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            Retirar
                                        </button>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- VOLVER -->
        <div class="mt-8 flex justify-center">
            <a href="index.php?action=dashboard"
               class="btn-secondary inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold shadow transition">
                ⬅ Dashboard
            </a>
        </div>
    </div>
</main>

<!-- MODAL RETIRO -->
<div id="modal-retiro" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="cerrarModalRetiro()"></div>
    <div class="modal-panel relative rounded-2xl shadow-2xl p-6 w-80 z-10">

        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-warn mb-0.5">Matrícula</p>
                <h3 class="text-lg font-bold text-strong font-display">Registrar retiro</h3>
            </div>
            <button onclick="cerrarModalRetiro()" class="icon-btn p-1 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="index.php?action=matricula_retirar" id="form-retiro"
              onsubmit="return confirm('¿Confirmas el retiro de este alumno?')">
            <input type="hidden" name="id" id="modal-retiro-id">

            <div class="mb-5">
                <label class="block text-xs font-semibold uppercase tracking-wider text-muted mb-2">
                    Fecha de retiro
                </label>
                <input type="date" name="fecha_retiro" id="modal-retiro-fecha"
                    max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>"
                    class="input-field w-full text-lg font-mono rounded-xl px-4 py-3"
                    style="border-color: var(--warn);">
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="cerrarModalRetiro()"
                    class="btn-secondary flex-1 font-semibold py-2.5 rounded-xl transition text-sm">
                    Cancelar
                </button>
                <button type="submit"
                    class="btn-soft-warn flex-1 font-bold py-2.5 rounded-xl transition text-sm">
                    Confirmar retiro
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR MATRÍCULA -->
<div id="modal-editar" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="cerrarModalEditar()"></div>
    <div class="modal-panel relative rounded-2xl shadow-2xl p-6 w-full max-w-lg z-10 max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Matrícula</p>
                <h3 class="text-lg font-bold text-strong font-display">Editar matrícula</h3>
            </div>
            <button onclick="cerrarModalEditar()" class="icon-btn p-1 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" id="form-editar" class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-soft mb-2">Alumno</label>
                <select name="alumno_id" id="edit-alumno" required
                    class="input-field w-full rounded-lg px-4 py-2">
                    <?php foreach ($alumnos as $a): ?>
                        <option value="<?= $a['id'] ?>">
                            <?= htmlspecialchars($a['nombre'] . ' ' . $a['apepat'] . ' ' . $a['apemat']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-soft mb-2">Fecha de matrícula</label>
                <input type="date" name="fecha_matricula" id="edit-fecha"
                    max="<?= date('Y-m-d') ?>"
                    class="input-field w-full rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-soft mb-2">Curso</label>
                <select name="curso_id" id="edit-curso" required
                    class="input-field w-full rounded-lg px-4 py-2">
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-soft mb-2">Año académico</label>
                <select name="anio_id" id="edit-anio" required
                    class="input-field w-full rounded-lg px-4 py-2">
                    <?php foreach ($anios as $an): ?>
                        <option value="<?= $an['id'] ?>"><?= htmlspecialchars($an['anio']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">N° de lista</label>
                    <input type="number" name="numero_lista" id="edit-lista" min="1"
                        class="input-field w-full rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">N° de matrícula</label>
                    <input type="text" name="numero_matricula" id="edit-matricula"
                        class="input-field w-full rounded-lg px-4 py-2">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="cerrarModalEditar()"
                    class="btn-secondary flex-1 font-semibold py-2.5 rounded-xl transition text-sm">
                    Cancelar
                </button>
                <button type="submit"
                    class="btn-primary flex-1 font-bold py-2.5 rounded-xl transition text-sm">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalRetiro(matriculaId) {
        document.getElementById('modal-retiro-id').value = matriculaId;
        document.getElementById('modal-retiro-fecha').value = '<?= date('Y-m-d') ?>';
        document.getElementById('modal-retiro').classList.remove('hidden');
    }
    function cerrarModalRetiro() {
        document.getElementById('modal-retiro').classList.add('hidden');
    }

    function abrirModalEditar(btn) {
        const d = btn.dataset;
        const form = document.getElementById('form-editar');
        form.action = 'index.php?action=matricula_update&id=' + d.id;
        document.getElementById('edit-alumno').value  = d.alumnoId;
        document.getElementById('edit-curso').value   = d.cursoId;
        document.getElementById('edit-anio').value    = d.anioId;
        document.getElementById('edit-fecha').value   = d.fecha || '';
        document.getElementById('edit-lista').value   = d.lista || '';
        document.getElementById('edit-matricula').value = d.matriculaNum || '';
        document.getElementById('modal-editar').classList.remove('hidden');
    }
    function cerrarModalEditar() {
        document.getElementById('modal-editar').classList.add('hidden');
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { cerrarModalRetiro(); cerrarModalEditar(); }
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>