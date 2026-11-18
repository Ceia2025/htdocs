<?php
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

if (!function_exists('saat_icon')) {
    function saat_icon(string $key, string $classes = 'w-4 h-4'): string
    {
        $path = __DIR__ . "/../../public/svg/{$key}.svg";
        if (!file_exists($path)) {
            return '';
        }
        $svg = file_get_contents($path);
        return preg_replace('/<svg /', '<svg class="' . htmlspecialchars($classes) . '" ', $svg, 1);
    }
}

$h = fn($valor) => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$disponibles = array_filter($opciones, fn($o) => !$o['planificada']);
$hayDestinos = $esAdmin ? !empty($opciones) : !empty($disponibles);
$urlBase = 'index.php?action=planificaciones';
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Planificación</p>
            <h1 class="text-2xl font-bold text-strong font-display"><?= $esAdmin ? 'Planificaciones' : 'Mis Planificaciones' ?></h1>
            <p class="text-sm text-muted mt-0.5">
                <?= $esAdmin
                    ? 'Vista de administrador: ves las planificaciones de todos los docentes'
                    : 'Planifica cada asignatura por curso según las Bases Curriculares EPJA 2024' ?>
            </p>
        </div>
        <a href="index.php?action=dashboard"
            class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            <?= saat_icon('arrow-left', 'w-4 h-4') ?>
            Inicio
        </a>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

    <?php if ($flash): ?>
        <div class="banner-<?= $h($flash['tipo']) ?> rounded-lg px-4 py-3 text-sm font-medium mb-6" role="status">
            <?= $h($flash['msg']) ?>
        </div>
    <?php endif; ?>

    <?php if (!$anio): ?>
        <div class="text-center py-20 text-muted">
            <p class="mb-4"><?= saat_icon('calendar', 'w-12 h-12 mx-auto') ?></p>
            <p class="text-lg font-medium">Aún no hay años académicos creados.</p>
            <p class="text-sm mt-1">Crea primero el año y sus fechas de semestre para comenzar a planificar.</p>
        </div>
    <?php else: ?>

        <!-- Barra de control: año + semanas + nueva -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex flex-wrap items-center gap-3">
                <label for="selector-anio" class="sr-only">Año académico</label>
                <select id="selector-anio" class="input-field rounded-lg px-3 py-2 text-sm font-semibold"
                    onchange="window.location.href='<?= $h($urlBase) ?>&anio_id=' + this.value">
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= (int) $a['id'] ?>" <?= (int) $a['id'] === $anioId ? 'selected' : '' ?>>
                            Año <?= $h($a['anio']) ?><?= $a['descripcion'] ? ' · ' . $h($a['descripcion']) : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if ($esAdmin): ?>
                    <label for="selector-docente" class="sr-only">Docente</label>
                    <select id="selector-docente" class="input-field rounded-lg px-3 py-2 text-sm"
                        onchange="window.location.href='<?= $h($urlBase) ?>&anio_id=<?= $anioId ?>&docente_id=' + this.value">
                        <option value="0">Todos los docentes</option>
                        <?php foreach ($docentes as $d): ?>
                            <option value="<?= (int) $d['id'] ?>" <?= (int) $d['id'] === $docenteId ? 'selected' : '' ?>>
                                <?= $h($d['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                <?php if ($semanas): ?>
                    <span class="chip-counter <?= $semanas['total'] >= 36 ? 'chip-counter--all' : 'chip-counter--partial' ?> text-xs font-semibold px-3 py-1.5 rounded-full"
                        title="Semanas calendario entre las fechas de inicio y fin de cada semestre. Las Bases EPJA consideran 36 semanas lectivas.">
                        <?= (int) $semanas['total'] ?> semanas · S1 <?= (int) $semanas['s1'] ?> + S2 <?= (int) $semanas['s2'] ?>
                    </span>
                <?php else: ?>
                    <span class="chip-counter chip-counter--none text-xs font-semibold px-3 py-1.5 rounded-full">
                        Fechas de semestre sin definir
                    </span>
                <?php endif; ?>
            </div>

            <button type="button" data-modal-open="modal-nueva"
                class="btn-primary flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg">
                <?= saat_icon('plus', 'w-4 h-4') ?>
                Nueva planificación
            </button>
        </div>

        <?php if (empty($planificaciones)): ?>
            <div class="panel rounded-2xl text-center py-16 px-6">
                <p class="mb-4 text-muted"><?= saat_icon('inbox', 'w-12 h-12 mx-auto') ?></p>
                <p class="text-lg font-semibold text-strong font-display">
                    <?= $esAdmin ? 'No hay planificaciones' : 'Aún no tienes planificaciones' ?> en <?= $h($anio['anio']) ?>
                </p>
                <p class="text-sm text-muted mt-1 mb-6">Elige un curso y una asignatura para comenzar.</p>
                <button type="button" data-modal-open="modal-nueva"
                    class="btn-primary text-sm font-semibold px-5 py-2.5 rounded-lg">
                    Crear mi primera planificación
                </button>
            </div>
        <?php else: ?>

            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-muted">
                    <span class="text-strong font-semibold"><?= count($planificaciones) ?></span>
                    <?= count($planificaciones) === 1 ? 'planificación' : 'planificaciones' ?> en <?= $h($anio['anio']) ?>
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($planificaciones as $p):
                    $avance = max(0, min(100, (int) $p['avance']));
                    $etiqueta = $p['asignatura'] . ' · ' . $p['curso'];
                    ?>
                    <article class="dashboard-card animate-fadeIn rounded-2xl overflow-hidden">

                        <div class="px-5 py-4 border-b divider-soft flex items-start gap-3">
                            <div class="card-icon-badge w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center">
                                <?= saat_icon('book', 'w-5 h-5') ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-strong font-bold text-base truncate font-display">
                                    <?= $h($p['asignatura']) ?>
                                </h3>
                                <p class="text-xs text-muted mt-0.5 truncate">
                                    <?= $h($p['curso']) ?><?= $p['nivel'] ? ' · ' . $h($p['nivel']) : '' ?>
                                </p>
                                <?php if ($esAdmin): ?>
                                    <p class="text-xs text-accent font-semibold mt-0.5 truncate">
                                        <?= $h($p['docente']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <?php if ($avance === 100): ?>
                                <span class="badge-available text-xs font-semibold px-2.5 py-0.5 rounded-full whitespace-nowrap">Completa</span>
                            <?php else: ?>
                                <span class="chip-warn text-xs font-semibold px-2.5 py-0.5 rounded-full whitespace-nowrap">En curso</span>
                            <?php endif; ?>
                        </div>

                        <div class="px-5 pt-4">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="text-muted">Avance</span>
                                <span class="text-strong font-semibold"><?= $avance ?>%</span>
                            </div>
                            <div class="progress-track" role="progressbar" aria-valuenow="<?= $avance ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-fill" style="width: <?= $avance ?>%"></div>
                            </div>

                            <?php if (!$p['catalogo_ok']): ?>
                                <p class="chip-danger text-xs font-medium px-2.5 py-1 rounded-lg mt-3 inline-block">
                                    El plan curricular de esta planificación ya no existe en el catálogo
                                </p>
                            <?php endif; ?>

                            <p class="text-xs text-faint mt-3 flex items-center gap-1">
                                <?= saat_icon('calendar', 'w-3 h-3 flex-shrink-0') ?>
                                Actualizada:
                                <span class="text-strong font-medium"><?= date('d/m/Y H:i', strtotime($p['updated_at'])) ?></span>
                            </p>
                        </div>

                        <div class="px-5 py-4 grid grid-cols-3 gap-2">
                            <a href="index.php?action=editar_planificacion&id=<?= (int) $p['id'] ?>"
                                class="btn-soft-info flex flex-col items-center gap-1.5 px-2 py-3 rounded-xl transition text-center">
                                <?= saat_icon('edit', 'w-5 h-5') ?>
                                <span class="text-xs font-semibold leading-tight">Editar</span>
                            </a>

                            <button type="button" data-modal-open="modal-duplicar"
                                data-id="<?= (int) $p['id'] ?>" data-nombre="<?= $h($etiqueta) ?>"
                                class="btn-soft-warn flex flex-col items-center gap-1.5 px-2 py-3 rounded-xl transition text-center">
                                <?= saat_icon('copy', 'w-5 h-5') ?>
                                <span class="text-xs font-semibold leading-tight">Duplicar</span>
                            </button>

                            <button type="button" data-modal-open="modal-eliminar"
                                data-id="<?= (int) $p['id'] ?>" data-nombre="<?= $h($etiqueta) ?>"
                                class="btn-soft-danger flex flex-col items-center gap-1.5 px-2 py-3 rounded-xl transition text-center">
                                <?= saat_icon('trash', 'w-5 h-5') ?>
                                <span class="text-xs font-semibold leading-tight">Eliminar</span>
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</main>

<?php if ($anio): ?>

    <!-- Modal: nueva planificación -->
    <div id="modal-nueva" class="modal-backdrop" hidden>
        <div class="modal-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="titulo-nueva">
            <h2 id="titulo-nueva" class="text-lg font-bold text-strong font-display">Nueva planificación</h2>
            <p class="text-sm text-muted mt-1 mb-5">
                Se registrará a nombre de <span class="text-strong font-semibold"><?= $h($nombre) ?></span> para el año <?= $h($anio['anio']) ?>.
            </p>

            <?php if (empty($disponibles)): ?>
                <div class="banner-info rounded-lg px-4 py-3 text-sm mb-5">
                    No quedan cursos y asignaturas disponibles para planificar en este año.
                </div>
                <div class="flex justify-end">
                    <button type="button" data-modal-close class="btn-secondary text-sm px-4 py-2 rounded-lg">Cerrar</button>
                </div>
            <?php else: ?>
                <form method="post" action="index.php?action=crear_planificacion" class="space-y-4">
                    <input type="hidden" name="csrf" value="<?= $h($csrf) ?>">
                    <input type="hidden" name="anio_id" value="<?= $anioId ?>">

                    <div>
                        <label for="nueva-ca" class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Curso y asignatura</label>
                        <select id="nueva-ca" name="curso_asignatura_id" required class="input-field w-full rounded-lg px-3 py-2.5 text-sm">
                            <option value="">Selecciona…</option>
                            <?php foreach ($opciones as $o): ?>
                                <option value="<?= (int) $o['id'] ?>" <?= $o['planificada'] ? 'disabled' : '' ?>>
                                    <?= $h($o['curso']) ?> — <?= $h($o['asignatura']) ?><?= $o['planificada'] ? ' (ya planificada)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="nueva-catalogo" class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Plan curricular (Bases EPJA)</label>
                        <select id="nueva-catalogo" name="catalogo_id" required class="input-field w-full rounded-lg px-3 py-2.5 text-sm">
                            <option value="">Selecciona nivel y asignatura…</option>
                            <?php foreach ($catalogo as $nivel => $items): ?>
                                <optgroup label="<?= $h($nivel) ?>">
                                    <?php foreach ($items as $i): ?>
                                        <option value="<?= $h($i['id']) ?>">
                                            <?= $h($i['asignatura']) ?><?= $i['categoria'] !== 'General' ? ' (' . $h($i['categoria']) . ')' : '' ?> · <?= (int) $i['total_oa'] ?> OA
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-xs text-faint mt-1.5">Define los OA, habilidades y conocimientos disponibles al planificar.</p>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" data-modal-close class="btn-secondary text-sm px-4 py-2 rounded-lg">Cancelar</button>
                        <button type="submit" class="btn-primary text-sm font-semibold px-5 py-2 rounded-lg">Comenzar a planificar</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal: duplicar -->
    <div id="modal-duplicar" class="modal-backdrop" hidden>
        <div class="modal-panel w-full max-w-lg rounded-2xl p-6 shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="titulo-duplicar">
            <h2 id="titulo-duplicar" class="text-lg font-bold text-strong font-display">Duplicar planificación</h2>
            <p class="text-sm text-muted mt-1 mb-5">
                Se copiará <span class="text-strong font-semibold" data-fill="nombre"></span>
                completa (módulos y clases) hacia otro curso.
            </p>

            <?php if (!$hayDestinos): ?>
                <div class="banner-info rounded-lg px-4 py-3 text-sm mb-5">
                    No hay otros cursos disponibles para duplicar esta planificación.
                </div>
                <div class="flex justify-end">
                    <button type="button" data-modal-close class="btn-secondary text-sm px-4 py-2 rounded-lg">Cerrar</button>
                </div>
            <?php else: ?>
                <form method="post" action="index.php?action=duplicar_planificacion" class="space-y-4">
                    <input type="hidden" name="csrf" value="<?= $h($csrf) ?>">
                    <input type="hidden" name="id" value="">

                    <div>
                        <label for="duplicar-ca" class="block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5">Curso y asignatura de destino</label>
                        <select id="duplicar-ca" name="curso_asignatura_id" required class="input-field w-full rounded-lg px-3 py-2.5 text-sm">
                            <option value="">Selecciona…</option>
                            <?php foreach ($opciones as $o): ?>
                                <?php $bloqueada = !$esAdmin && $o['planificada']; ?>
                                <option value="<?= (int) $o['id'] ?>" <?= $bloqueada ? 'disabled' : '' ?>>
                                    <?= $h($o['curso']) ?> — <?= $h($o['asignatura']) ?><?= $bloqueada ? ' (ya planificada)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($esAdmin): ?>
                            <p class="text-xs text-faint mt-1.5">La copia queda a nombre del docente dueño de la planificación original.</p>
                        <?php endif; ?>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" data-modal-close class="btn-secondary text-sm px-4 py-2 rounded-lg">Cancelar</button>
                        <button type="submit" class="btn-primary text-sm font-semibold px-5 py-2 rounded-lg">Duplicar</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal: eliminar -->
    <div id="modal-eliminar" class="modal-backdrop" hidden>
        <div class="modal-danger w-full max-w-md rounded-2xl p-6 shadow-2xl" role="alertdialog" aria-modal="true" aria-labelledby="titulo-eliminar">
            <div class="modal-icon-danger w-12 h-12 rounded-full flex items-center justify-center mb-4">
                <?= saat_icon('trash', 'w-6 h-6') ?>
            </div>
            <h2 id="titulo-eliminar" class="text-lg font-bold text-strong font-display">¿Eliminar planificación?</h2>
            <p class="text-sm text-muted mt-1 mb-6">
                Se eliminará <span class="text-strong font-semibold" data-fill="nombre"></span>
                con todos sus módulos y clases. Esta acción no se puede deshacer.
            </p>
            <form method="post" action="index.php?action=eliminar_planificacion" class="flex justify-end gap-2">
                <input type="hidden" name="csrf" value="<?= $h($csrf) ?>">
                <input type="hidden" name="id" value="">
                <button type="button" data-modal-close class="btn-secondary text-sm px-4 py-2 rounded-lg">Cancelar</button>
                <button type="submit" class="btn-danger text-sm font-semibold px-5 py-2 rounded-lg">Sí, eliminar</button>
            </form>
        </div>
    </div>

    <script>
        (function () {
            function abrir(modal, origen) {
                if (origen && origen.dataset.id) {
                    var campoId = modal.querySelector('input[name="id"]');
                    if (campoId) campoId.value = origen.dataset.id;
                    modal.querySelectorAll('[data-fill="nombre"]').forEach(function (el) {
                        el.textContent = origen.dataset.nombre || '';
                    });
                }
                modal.hidden = false;
                document.body.classList.add('overflow-hidden');
                var primero = modal.querySelector('select, button[type="submit"], button');
                if (primero) primero.focus();
            }

            function cerrar(modal) {
                modal.hidden = true;
                if (!document.querySelector('.modal-backdrop:not([hidden])')) {
                    document.body.classList.remove('overflow-hidden');
                }
            }

            document.addEventListener('click', function (e) {
                var disparador = e.target.closest('[data-modal-open]');
                if (disparador) {
                    var modal = document.getElementById(disparador.dataset.modalOpen);
                    if (modal) abrir(modal, disparador);
                    return;
                }
                if (e.target.closest('[data-modal-close]')) {
                    cerrar(e.target.closest('.modal-backdrop'));
                    return;
                }
                if (e.target.classList.contains('modal-backdrop')) {
                    cerrar(e.target);
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal-backdrop:not([hidden])').forEach(cerrar);
                }
            });
        })();
    </script>
<?php endif; ?>

<?php include __DIR__ . "/../layout/footer.php"; ?>