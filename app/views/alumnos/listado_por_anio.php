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

<main>
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Listado por Año Escolar</h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- SELECTOR DE AÑO -->
        <form method="GET" action="index.php"
            class="bg-gray-800/60 border border-gray-700 rounded-2xl p-6 flex flex-wrap gap-4 items-end shadow-xl mb-8">
            <input type="hidden" name="action" value="listado_por_anio">
            <div class="flex flex-col gap-1">
                <label class="text-xs text-gray-400 uppercase tracking-wider">Año Escolar</label>
                <select name="anio_id"
                    class="rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition min-w-[160px]">
                    <option value="">Seleccionar año...</option>
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= ($_GET['anio_id'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>     <?= $a['descripcion'] ? ' — ' . htmlspecialchars($a['descripcion']) : '' ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <button type="submit"
                class="bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white font-semibold rounded-xl px-6 py-3 shadow-lg transition">
                Ver Listado
            </button>
        </form>

        <?php if ($anioSeleccionado): ?>

            <!-- CONTADORES GLOBALES -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-gray-800/60 border border-gray-700 rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold text-indigo-400"><?= $totalActivos + $totalRetirados ?></span>
                    <div>
                        <p class="text-white font-semibold">Total Alumnos</p>
                        <p class="text-xs text-gray-400">Año <?= $anioSeleccionado['anio'] ?></p>
                    </div>
                </div>
                <div class="bg-gray-800/60 border border-green-800 rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold text-green-400"><?= $totalActivos ?></span>
                    <div>
                        <p class="text-white font-semibold">Activos</p>
                        <p class="text-xs text-gray-400">Sin fecha de retiro</p>
                    </div>
                </div>
                <div class="bg-gray-800/60 border border-red-800 rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-4xl font-bold text-red-400"><?= $totalRetirados ?></span>
                    <div>
                        <p class="text-white font-semibold">Retirados</p>
                        <p class="text-xs text-gray-400">Con fecha de retiro</p>
                    </div>
                </div>
            </div>

            <!-- LISTADO POR CURSO -->
            <!-- LISTADO POR CURSO - ACCORDION -->
            <?php if (!empty($alumnosPorCurso)): ?>
                <div class="space-y-3">
                    <?php foreach ($alumnosPorCurso as $cursoNombre => $alumnos): ?>
                        <?php
                        $activos = count(array_filter($alumnos, fn($a) => empty($a['deleted_at'])));
                        $retirados = count(array_filter($alumnos, fn($a) => !empty($a['deleted_at'])));
                        $cursoId = 'curso-' . preg_replace('/[^a-z0-9]/i', '-', $cursoNombre);
                        ?>

                        <div class="rounded-2xl border border-gray-700 overflow-hidden shadow-lg">

                            <!-- CABECERA CLICKEABLE -->
                            <button onclick="toggleCurso('<?= $cursoId ?>')"
                                class="w-full flex items-center justify-between bg-gray-800 hover:bg-gray-750 px-6 py-4 transition group">

                                <div class="flex items-center gap-4">
                                    <!-- Ícono chevron -->
                                    <svg id="icon-<?= $cursoId ?>" class="w-5 h-5 text-indigo-400 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <span class="text-lg font-bold text-white"><?= htmlspecialchars($cursoNombre) ?></span>
                                </div>

                                <!-- Badges contadores -->
                                <div class="flex items-center gap-2 text-sm">
                                    <span
                                        class="bg-indigo-900/50 border border-indigo-600 text-indigo-300 rounded-lg px-3 py-1 font-semibold">
                                        <?= count($alumnos) ?> alumnos
                                    </span>
                                    <span
                                        class="bg-green-900/40 border border-green-600 text-green-300 rounded-lg px-3 py-1 font-semibold">
                                        ✅ <?= $activos ?>
                                    </span>
                                    <?php if ($retirados > 0): ?>
                                        <span
                                            class="bg-red-900/40 border border-red-600 text-red-300 rounded-lg px-3 py-1 font-semibold">
                                            🚪 <?= $retirados ?>
                                        </span>
                                    <?php endif ?>
                                </div>
                            </button>

                            <!-- CONTENIDO DESPLEGABLE -->
                            <div id="<?= $cursoId ?>" class="hidden">
                                <div class="overflow-x-auto bg-gray-900">
                                    <table class="min-w-full divide-y divide-gray-700">
                                        <thead class="bg-gray-950/50">
                                            <tr>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                                    #</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                                    RUN</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                                    Nombre Completo</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                                    Sexo</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                                    Estado</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                                    Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-700">
                                            <?php foreach ($alumnos as $i => $a): ?>
                                                <tr class="transition-all duration-150 cursor-pointer
                                        <?= !empty($a['deleted_at'])
                                            ? 'bg-red-950/30 hover:bg-red-900/40'
                                            : 'bg-gray-900/20 hover:bg-gray-700/50' ?>"
                                                    onclick="window.location='index.php?action=alumno_profile&id=<?= $a['id'] ?>'">
                                                    <td class="px-4 py-3 text-sm text-gray-400"><?= $i + 1 ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-100">
                                                        <?= htmlspecialchars($a['run'] . '-' . $a['codver']) ?>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-100 capitalize font-medium">
                                                        <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat'] . ', ' . $a['nombre']) ?>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($a['sexo']) ?></td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <?php if (!empty($a['deleted_at'])): ?>
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 bg-red-900/40 border border-red-500 text-red-300 rounded-md text-xs font-semibold">
                                                                Retirado <?= (new DateTime($a['deleted_at']))->format('d/m/Y') ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 bg-green-900/40 border border-green-500 text-green-300 rounded-md text-xs font-semibold">
                                                                ✅ Activo
                                                            </span>
                                                        <?php endif ?>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-100 space-x-3"
                                                        onclick="event.stopPropagation()">
                                                        <a href="index.php?action=alumno_edit&id=<?= $a['id'] ?>"
                                                            class="text-indigo-400 hover:text-indigo-300 font-medium">Editar</a>
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
                <div class="text-center text-gray-400 py-12">No hay alumnos matriculados en este año.</div>
            <?php endif ?>

        <?php else: ?>
            <!-- Estado inicial sin año seleccionado -->
            <div class="text-center py-16 text-gray-500">
                <p class="text-5xl mb-4">📋</p>
                <p class="text-lg">Selecciona un año escolar para ver el listado de alumnos.</p>
            </div>
        <?php endif ?>

        <!-- VOLVER -->
        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=dashboard"
                class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
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