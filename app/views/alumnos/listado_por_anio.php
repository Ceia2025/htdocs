<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth(); // obliga a tener sesión iniciada

$user = $_SESSION['user']; // usuario logueado
$nombre = $user['nombre'];
$rol = $user['rol'];

// Incluir layout
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<main class="min-h-screen app-shell">
    <header class="page-header">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-strong">Listado por Año Escolar</h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- SELECTOR DE AÑO -->
        <form method="GET" action="index.php"
            class="panel rounded-2xl p-6 flex flex-wrap gap-4 items-end shadow-xl mb-8">
            <input type="hidden" name="action" value="listado_por_anio">
            <div class="flex flex-col gap-1">
                <label class="text-xs text-faint uppercase tracking-wider">Año Escolar</label>
                <select name="anio_id"
                    class="input-field rounded-xl px-4 py-3 transition min-w-[160px]">
                    <option value="">Seleccionar año...</option>
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= ($_GET['anio_id'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>     <?= $a['descripcion'] ? ' — ' . htmlspecialchars($a['descripcion']) : '' ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <button type="submit"
                class="btn-primary font-semibold rounded-xl px-6 py-3 shadow-lg transition">
                Ver Listado
            </button>
        </form>

        <?php if ($anioSeleccionado): ?>

            <!-- CONTADORES GLOBALES -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="chip-counter rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold text-accent"><?= $totalActivos + $totalRetirados ?></span>
                    <div>
                        <p class="text-strong font-semibold">Total Alumnos</p>
                        <p class="text-xs text-faint">Año <?= $anioSeleccionado['anio'] ?></p>
                    </div>
                </div>
                <div class="chip-counter chip-counter--all rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold"><?= $totalActivos ?></span>
                    <div>
                        <p class="text-strong font-semibold">Activos</p>
                        <p class="text-xs text-faint">Sin fecha de retiro</p>
                    </div>
                </div>
                <div class="chip-counter chip-counter--none rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold"><?= $totalRetirados ?></span>
                    <div>
                        <p class="text-strong font-semibold">Retirados</p>
                        <p class="text-xs text-faint">Con fecha de retiro</p>
                    </div>
                </div>
            </div>

            <!-- LISTADO POR CURSO - ACCORDION -->
            <?php if (!empty($alumnosPorCurso)): ?>
                <div class="space-y-3">
                    <?php foreach ($alumnosPorCurso as $cursoNombre => $alumnos): ?>
                        <?php
                        $activos = count(array_filter($alumnos, fn($a) => empty($a['deleted_at'])));
                        $retirados = count(array_filter($alumnos, fn($a) => !empty($a['deleted_at'])));
                        $cursoId = 'curso-' . preg_replace('/[^a-z0-9]/i', '-', $cursoNombre);
                        ?>

                        <div class="accordion-item shadow-lg">

                            <!-- CABECERA CLICKEABLE -->
                            <button onclick="toggleCurso('<?= $cursoId ?>')"
                                class="accordion-trigger w-full flex items-center justify-between px-6 py-4 transition group">

                                <div class="flex items-center gap-4">
                                    <!-- Ícono chevron -->
                                    <svg id="icon-<?= $cursoId ?>" class="w-5 h-5 text-accent transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <span class="text-lg font-bold text-strong"><?= htmlspecialchars($cursoNombre) ?></span>
                                </div>

                                <!-- Badges contadores -->
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="report-blue report-badge rounded-lg px-3 py-1 font-semibold">
                                        <?= count($alumnos) ?> alumnos
                                    </span>
                                    <span class="report-emerald report-badge rounded-lg px-3 py-1 font-semibold">
                                        ✅ <?= $activos ?>
                                    </span>
                                    <?php if ($retirados > 0): ?>
                                        <span class="report-red report-badge rounded-lg px-3 py-1 font-semibold">
                                            🚪 <?= $retirados ?>
                                        </span>
                                    <?php endif ?>
                                </div>
                            </button>

                            <!-- CONTENIDO DESPLEGABLE -->
                            <div id="<?= $cursoId ?>" class="hidden">
                                <div class="accordion-content overflow-x-auto">
                                    <table class="data-table min-w-full">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                    #</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                    RUN</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                    Nombre Completo</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                    Sexo</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                    Estado</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                                    Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($alumnos as $i => $a): ?>
                                                <tr class="cursor-pointer <?= !empty($a['deleted_at']) ? 'row-inactive' : '' ?>"
                                                    onclick="window.location='index.php?action=alumno_profile&id=<?= $a['id'] ?>'">
                                                    <td class="px-4 py-3 text-sm text-faint"><?= $i + 1 ?></td>
                                                    <td class="px-4 py-3 text-sm text-soft">
                                                        <?= htmlspecialchars($a['run'] . '-' . $a['codver']) ?>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-strong capitalize font-medium">
                                                        <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat'] . ', ' . $a['nombre']) ?>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-soft"><?= htmlspecialchars($a['sexo']) ?></td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <?php if (!empty($a['deleted_at'])): ?>
                                                            <span class="report-red report-badge inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold">
                                                                Retirado <?= (new DateTime($a['deleted_at']))->format('d/m/Y') ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="report-emerald report-badge inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold">
                                                                ✅ Activo
                                                            </span>
                                                        <?php endif ?>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm space-x-3"
                                                        onclick="event.stopPropagation()">
                                                        <a href="index.php?action=alumno_edit&id=<?= $a['id'] ?>"
                                                            class="link-action font-medium">Editar</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <div class="text-center text-faint py-12">No hay alumnos matriculados en este año.</div>
            <?php endif ?>

        <?php else: ?>
            <!-- Estado inicial sin año seleccionado -->
            <div class="text-center py-16 text-faint">
                <p class="text-5xl mb-4">📋</p>
                <p class="text-lg">Selecciona un año escolar para ver el listado de alumnos.</p>
            </div>
        <?php endif ?>

        <!-- VOLVER -->
        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=dashboard"
                class="btn-secondary inline-block rounded-md px-4 py-2 text-sm font-semibold shadow">
                ⬅️ Volver al Dashboard
            </a>
        </div>

    </div>
</main>

<script>
    function toggleCurso(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        const isOpen = !content.classList.contains('hidden');

        if (isOpen) {
            // Cerrar con animación
            content.style.maxHeight = content.scrollHeight + 'px';
            requestAnimationFrame(() => {
                content.style.transition = 'max-height 0.35s ease, opacity 0.3s ease';
                content.style.maxHeight = '0';
                content.style.opacity = '0';
                content.style.overflow = 'hidden';
            });
            setTimeout(() => {
                content.classList.add('hidden');
                content.style.maxHeight = '';
                content.style.opacity = '';
            }, 350);
            icon.style.transform = 'rotate(0deg)';
        } else {
            // Abrir con animación
            content.classList.remove('hidden');
            content.style.maxHeight = '0';
            content.style.opacity = '0';
            content.style.overflow = 'hidden';
            content.style.transition = 'max-height 0.35s ease, opacity 0.3s ease';
            requestAnimationFrame(() => {
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
            });
            setTimeout(() => {
                content.style.maxHeight = 'none';
                content.style.overflow = '';
            }, 360);
            icon.style.transform = 'rotate(180deg)';
        }
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>