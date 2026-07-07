<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$semestreActivo = $_GET['semestre'] ?? '';
$cursoIdActivo  = $_GET['curso_id'] ?? '';
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Convivencia Escolar</p>
            <h1 class="text-2xl font-bold text-strong font-display">Registro de Retiros</h1>
            <p class="text-sm text-muted mt-0.5">Alumnos que se retiran durante la jornada</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="index.php?action=retiros_reportes"
                class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6M4 20h16M4 4h16M9 4v4m6-4v4" />
                </svg>
                Reportes
            </a>
            <a href="index.php?action=retiros_create"
                class="btn-primary flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <circle cx="9" cy="8" r="4"/><path d="M2 20c0-4 4-6 7-6s7 2 7 6"/>
                    <line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/>
                </svg>
                Registrar
            </a>
        </div>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-6 sm:px-6">

    <!-- Alertas flash -->
    <?php if (isset($_GET['creado'])): ?>
        <div class="banner-success flex items-center gap-2 mb-5 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            Retiro registrado correctamente.
        </div>
    <?php elseif (isset($_GET['editado'])): ?>
        <div class="banner-info flex items-center gap-2 mb-5 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            Retiro actualizado correctamente.
        </div>
    <?php elseif (isset($_GET['eliminado'])): ?>
        <div class="banner-danger flex items-center gap-2 mb-5 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            Retiro eliminado.
        </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <div class="panel rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-strong"><?= $resumen['total'] ?></p>
            <p class="text-xs text-muted mt-0.5">Total retiros</p>
        </div>
        <div class="panel rounded-xl p-4 text-center" style="border-color: var(--danger);">
            <p class="text-2xl font-bold text-danger"><?= $resumen['injustificados'] ?></p>
            <p class="text-xs text-muted mt-0.5">Injustificados</p>
        </div>
        <div class="panel rounded-xl p-4 text-center" style="border-color: var(--success);">
            <p class="text-2xl font-bold text-success"><?= $resumen['justificados'] ?></p>
            <p class="text-xs text-muted mt-0.5">Justificados</p>
        </div>
        <div class="panel rounded-xl p-4 text-center" style="border-color: var(--warn);">
            <p class="text-2xl font-bold text-warn"><?= $resumen['extraordinarios'] ?></p>
            <p class="text-xs text-muted mt-0.5">Extraordinarios</p>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" action="index.php" class="panel rounded-xl p-4 mb-6">
        <input type="hidden" name="action" value="retiros">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-3">

            <select name="anio_id" class="input-field text-sm rounded-lg px-3 py-2 cursor-pointer">
                <option value="">Todos los años</option>
                <?php foreach ($anios as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= ($_GET['anio_id'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                        <?= $a['anio'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="semestre" class="input-field text-sm rounded-lg px-3 py-2 cursor-pointer">
                <option value="">Ambos semestres</option>
                <option value="1" <?= $semestreActivo === '1' ? 'selected' : '' ?>>1° Semestre</option>
                <option value="2" <?= $semestreActivo === '2' ? 'selected' : '' ?>>2° Semestre</option>
            </select>

            <select name="curso_id" class="input-field text-sm rounded-lg px-3 py-2 cursor-pointer">
                <option value="">Todos los cursos</option>
                <?php foreach ($cursos as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $cursoIdActivo == $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="justificado" class="input-field text-sm rounded-lg px-3 py-2 cursor-pointer">
                <option value="">Justificado / No</option>
                <option value="Si" <?= ($_GET['justificado'] ?? '') === 'Si' ? 'selected' : '' ?>>Justificado</option>
                <option value="No" <?= ($_GET['justificado'] ?? '') === 'No' ? 'selected' : '' ?>>No justificado</option>
            </select>

            <select name="extraordinario" class="input-field text-sm rounded-lg px-3 py-2 cursor-pointer">
                <option value="">Ordinario / Extraordinario</option>
                <option value="Si" <?= ($_GET['extraordinario'] ?? '') === 'Si' ? 'selected' : '' ?>>Extraordinario</option>
                <option value="No" <?= ($_GET['extraordinario'] ?? '') === 'No' ? 'selected' : '' ?>>Ordinario</option>
            </select>
        </div>

        <div class="flex gap-2 items-center">
            <button type="submit" class="btn-accent text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Filtrar
            </button>
            <a href="index.php?action=retiros" class="text-xs text-faint hover:text-strong underline transition">Limpiar</a>
        </div>
    </form>

    <!-- Layout: tabla + top alumnos -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Tabla principal -->
        <div class="xl:col-span-2">
            <div class="panel rounded-xl overflow-hidden">
                <div class="px-5 py-3.5 border-b divider-soft flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">
                        Detalle
                        <span class="ml-1 text-xs font-normal text-faint normal-case">(<?= count($retiros) ?> registros)</span>
                    </h2>
                    <input type="text" id="buscar-tabla" placeholder="Filtrar alumno…"
                        oninput="filtrarTabla(this.value)"
                        class="input-field text-xs rounded-lg px-3 py-1.5 w-40">
                </div>

                <?php if (empty($retiros)): ?>
                    <div class="px-6 py-14 text-center">
                        <svg class="w-10 h-10 text-faint mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/>
                        </svg>
                        <p class="text-muted font-medium">Sin retiros con los filtros actuales</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="data-table w-full text-sm" id="tabla-retiros">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2.5 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Alumno</th>
                                    <th class="px-3 py-2.5 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Curso</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">Fecha / Hora</th>
                                    <th class="px-3 py-2.5 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Motivo</th>
                                    <th class="px-3 py-2.5 text-left   text-xs font-semibold uppercase tracking-wider text-muted">Quien retira</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-muted">Estado</th>
                                    <?php if ($puedeEditar): ?>
                                        <th class="px-3 py-2.5"></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody id="tbody-retiros">
                                <?php foreach ($retiros as $r): ?>
                                    <tr class="fila-retiro transition"
                                        data-nombre="<?= strtolower($r['apepat'] . ' ' . $r['apemat']) ?>">

                                        <td class="px-4 py-3">
                                            <p class="text-strong font-semibold leading-tight">
                                                <?= htmlspecialchars($r['apepat'] . ' ' . $r['apemat']) ?>
                                            </p>
                                            <p class="text-soft text-xs"><?= htmlspecialchars($r['nombre']) ?></p>
                                            <p class="text-faint text-xs font-mono"><?= htmlspecialchars($r['run']) ?></p>
                                        </td>

                                        <td class="px-3 py-3 text-xs text-muted">
                                            <?= htmlspecialchars($r['curso']) ?>
                                        </td>

                                        <td class="px-3 py-3 text-center">
                                            <span class="text-soft text-xs block font-mono">
                                                <?= date('d/m/Y', strtotime($r['fecha_retiro'])) ?>
                                            </span>
                                            <span class="font-bold font-mono text-sm" style="color: var(--azul-vivo);">
                                                <?= substr($r['hora_retiro'], 0, 5) ?>
                                            </span>
                                            <span class="text-faint text-xs block"><?= $r['semestre'] ?>° sem.</span>
                                        </td>

                                        <td class="px-3 py-3 text-xs text-soft max-w-[140px]">
                                            <?= htmlspecialchars($r['motivo']) ?>
                                            <?php if ($r['observacion']): ?>
                                                <span class="block text-faint italic truncate">
                                                    <?= htmlspecialchars($r['observacion']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-3 py-3 text-xs text-muted">
                                            <?= $r['quien_retira'] ? htmlspecialchars($r['quien_retira']) : '—' ?>
                                        </td>

                                        <td class="px-3 py-3 text-center">
                                            <div class="flex flex-col items-center gap-1">
                                                <?php if ($r['justificado'] === 'Si'): ?>
                                                    <span class="banner-success text-xs font-semibold px-2 py-0.5 rounded-full">Justificado</span>
                                                <?php else: ?>
                                                    <span class="chip-danger text-xs font-semibold px-2 py-0.5 rounded-full">No justif.</span>
                                                <?php endif; ?>
                                                <?php if ($r['extraordinario'] === 'Si'): ?>
                                                    <span class="chip-warn text-xs font-semibold px-2 py-0.5 rounded-full">Extraord.</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <?php if ($puedeEditar): ?>
                                            <td class="px-3 py-3 text-right">
                                                <div class="flex items-center justify-end gap-1">
                                                    <a href="index.php?action=retiros_edit&id=<?= $r['id'] ?>"
                                                        class="icon-btn transition p-1 rounded" title="Editar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-1.414.828l-3 1 1-3a4 4 0 01.828-1.414z"/>
                                                        </svg>
                                                    </a>
                                                    <button type="button" onclick="confirmarEliminar(<?= $r['id'] ?>)"
                                                        class="icon-btn transition p-1 rounded text-danger" title="Eliminar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Panel lateral: top alumnos -->
        <div class="xl:col-span-1">
            <div class="panel rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b divider-soft">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-muted">
                        Top alumnos con más retiros
                    </h3>
                </div>
                <div class="divide-soft divide-y">
                    <?php foreach (array_slice($topAlumnos, 0, 5) as $i => $al):
                        $posColor = match($i) { 0 => 'text-warn', 1 => 'text-soft', 2 => 'text-faint', default => 'text-faint' };
                        ?>
                        <div class="flex items-center gap-3 px-4 py-2.5">
                            <span class="text-sm font-bold <?= $posColor ?> w-4 flex-shrink-0 text-center"><?= $i + 1 ?></span>
                            <div class="flex-1 min-w-0">
                                <p class="text-strong text-xs font-semibold truncate">
                                    <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                                </p>
                                <p class="text-xs text-muted"><?= htmlspecialchars($al['curso']) ?></p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-base font-bold <?= $al['total'] >= 5 ? 'text-danger' : ($al['total'] >= 3 ? 'text-warn' : 'text-soft') ?>">
                                    <?= $al['total'] ?>
                                </span>
                                <?php if ($al['injustificados'] > 0): ?>
                                    <p class="text-xs text-danger"><?= $al['injustificados'] ?> IJ</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($topAlumnos) > 5): ?>
                    <div class="px-4 py-3 border-t divider-soft">
                        <button onclick="document.getElementById('modalTopRetiros').classList.remove('hidden')"
                            class="w-full text-xs link-action font-medium transition flex items-center justify-center gap-1">
                            Ver todos (<?= count($topAlumnos) ?>)
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>

<!-- Modal top alumnos -->
<div id="modalTopRetiros"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="modal-panel rounded-xl w-full max-w-md max-h-[80vh] flex flex-col shadow-xl">
        <div class="flex items-center justify-between px-5 py-4 border-b divider-soft flex-shrink-0">
            <h2 class="text-sm font-semibold text-strong font-display">Ranking de retiros</h2>
            <button onclick="document.getElementById('modalTopRetiros').classList.add('hidden')"
                class="icon-btn transition p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="overflow-y-auto divide-soft divide-y">
            <?php foreach ($topAlumnos as $i => $al):
                $posColor = match(true) { $i===0 => 'text-warn', $i===1 => 'text-soft', $i===2 => 'text-faint', default => 'text-faint' };
                ?>
                <div class="flex items-center gap-3 px-5 py-3">
                    <span class="text-sm font-bold <?= $posColor ?> w-5 flex-shrink-0 text-center"><?= $i + 1 ?></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-strong text-xs font-semibold truncate">
                            <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                        </p>
                        <p class="text-xs text-muted"><?= htmlspecialchars($al['curso']) ?></p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-base font-bold <?= $al['total'] >= 5 ? 'text-danger' : ($al['total'] >= 3 ? 'text-warn' : 'text-soft') ?>">
                            <?= $al['total'] ?>
                        </span>
                        <?php if ($al['injustificados'] > 0): ?>
                            <p class="text-xs text-danger"><?= $al['injustificados'] ?> IJ</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="px-5 py-3 border-t divider-soft flex-shrink-0">
            <p class="text-xs text-muted text-center"><?= count($topAlumnos) ?> alumnos en total</p>
        </div>
    </div>
</div>

<!-- Modal confirmar eliminación -->
<div id="modalEliminar"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="modal-danger rounded-xl w-full max-w-sm shadow-xl p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="modal-icon-danger w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-strong font-bold text-base">¿Eliminar retiro?</h2>
                <p class="text-xs text-muted mt-0.5">Esta acción no se puede deshacer</p>
            </div>
        </div>
        <form id="formEliminar" method="POST" action="">
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="cerrarModalEliminar()"
                    class="btn-secondary rounded-lg px-4 py-2 text-sm transition">Cancelar</button>
                <button type="submit"
                    class="btn-danger rounded-lg px-4 py-2 text-sm font-semibold transition">Eliminar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function filtrarTabla(texto) {
        const q = texto.toLowerCase();
        document.querySelectorAll('#tbody-retiros .fila-retiro').forEach(fila => {
            fila.style.display = (fila.dataset.nombre || '').includes(q) ? '' : 'none';
        });
    }
    function confirmarEliminar(id) {
        document.getElementById('formEliminar').action = 'index.php?action=retiros_delete&id=' + id;
        document.getElementById('modalEliminar').classList.remove('hidden');
    }
    function cerrarModalEliminar() {
        document.getElementById('modalEliminar').classList.add('hidden');
    }
    document.getElementById('modalEliminar').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalEliminar();
    });
    document.getElementById('modalTopRetiros').addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>