<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>
<main>
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Anotaciones</h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- FILTROS -->
        <form method="GET" action="index.php" id="formFiltros"
            class="bg-gray-800/60 border border-gray-700 rounded-2xl p-6 grid grid-cols-1 md:grid-cols-4 gap-4 shadow-xl mb-8">
            <input type="hidden" name="action" value="anotaciones">

            <!-- Año -->
            <div class="flex flex-col gap-1">
                <label class="text-xs text-gray-400 uppercase tracking-wider">Año Escolar</label>
                <select name="anio_id" id="sel_anio" onchange="this.form.submit()"
                    class="rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">Seleccionar año...</option>
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= ($_GET['anio_id'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Curso -->
            <div class="flex flex-col gap-1">
                <label class="text-xs text-gray-400 uppercase tracking-wider">Curso</label>
                <select name="curso_id" id="sel_curso" onchange="this.form.submit()"
                    class="rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition"
                    <?= (empty($cursos) && empty($_GET['anio_id'])) ? 'disabled' : '' ?>>
                    <option value="">Seleccionar curso...</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($_GET['curso_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre']) ?>
                        </option>
                    <?php endforeach ?>
                </select>

            </div>

            <!-- Semestre -->
            <div class="flex flex-col gap-1">
                <label class="text-xs text-gray-400 uppercase tracking-wider">Semestre</label>
                <select name="semestre" onchange="this.form.submit()"
                    class="rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">Todos</option>
                    <option value="1" <?= ($_GET['semestre'] ?? '') == '1' ? 'selected' : '' ?>>1° Semestre</option>
                    <option value="2" <?= ($_GET['semestre'] ?? '') == '2' ? 'selected' : '' ?>>2° Semestre</option>
                </select>
            </div>

            <!-- Botón nueva anotación -->
            <div class="flex flex-col justify-end">
                <?php if (!empty($_GET['anio_id']) && !empty($_GET['curso_id'])): ?>
                    <a href="index.php?action=anotacion_create&anio_id=<?= $_GET['anio_id'] ?>&curso_id=<?= $_GET['curso_id'] ?>"
                        class="text-center bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white font-semibold rounded-xl px-4 py-3 shadow-lg transition">
                        ➕ Nueva Anotación
                    </a>
                <?php else: ?>
                    <button disabled
                        class="text-center bg-gray-700 text-gray-500 font-semibold rounded-xl px-4 py-3 cursor-not-allowed">
                        ➕ Nueva Anotación
                    </button>
                <?php endif ?>
            </div>
        </form>

        <!-- LISTADO DE ALUMNOS -->
        <?php if (!empty($alumnos)): ?>

            <!-- Encabezado del curso seleccionado -->
            <?php if (!empty($cursoSeleccionado)): ?>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-white">
                        Curso: <span class="text-indigo-400"><?= htmlspecialchars($cursoSeleccionado['nombre']) ?></span>
                        <?php if (!empty($_GET['semestre'])): ?>
                            — <span class="text-gray-300 text-base font-normal"><?= $_GET['semestre'] ?>° Semestre</span>
                        <?php endif ?>
                    </h2>
                    <span class="text-sm text-gray-400"><?= count($alumnos) ?> alumnos</span>
                </div>
            <?php endif ?>

            <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-950/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                Alumno</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">
                                Total</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-blue-400 uppercase tracking-wider">
                                Registros</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-green-400 uppercase tracking-wider">
                                Positivas</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-yellow-400 uppercase tracking-wider">
                                Leves</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-orange-400 uppercase tracking-wider">
                                Graves</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-red-400 uppercase tracking-wider">
                                Gravísimas</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php foreach ($alumnos as $al): ?>
                            <?php
                            $urlVer = "index.php?action=anotacion_ver&matricula_id={$al['matricula_id']}&anio_id={$_GET['anio_id']}&curso_id={$_GET['curso_id']}";
                            if (!empty($_GET['semestre']))
                                $urlVer .= "&semestre={$_GET['semestre']}";
                            ?>
                            <tr class="bg-gray-900/20 hover:bg-gray-700/50 transition cursor-pointer"
                                onclick="window.location='<?= $urlVer ?>'">
                                <td class="px-4 py-3 text-sm text-gray-100 capitalize font-medium">
                                    <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                                    <span class="text-xs text-gray-400 ml-1"><?= htmlspecialchars($al['run']) ?></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-white font-bold"><?= $al['total_anotaciones'] ?: '—' ?></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <?php if (($al['registros'] ?? 0) > 0): ?>
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 bg-blue-900/40 border border-blue-500 text-blue-300 rounded-full text-xs font-bold">
                                            <?= $al['registros'] ?>
                                        </span>
                                    <?php else: ?><span class="text-gray-600">—</span>
                                    <?php endif ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <?php if ($al['positivas'] > 0): ?>
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 bg-green-900/40 border border-green-500 text-green-300 rounded-full text-xs font-bold"><?= $al['positivas'] ?></span>
                                    <?php else: ?><span class="text-gray-600">—</span><?php endif ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <?php if ($al['leves'] > 0): ?>
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 bg-yellow-900/40 border border-yellow-500 text-yellow-300 rounded-full text-xs font-bold"><?= $al['leves'] ?></span>
                                    <?php else: ?><span class="text-gray-600">—</span><?php endif ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <?php if ($al['graves'] > 0): ?>
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 bg-orange-900/40 border border-orange-500 text-orange-300 rounded-full text-xs font-bold"><?= $al['graves'] ?></span>
                                    <?php else: ?><span class="text-gray-600">—</span><?php endif ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <?php if ($al['gravisimas'] > 0): ?>
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 bg-red-900/40 border border-red-500 text-red-300 rounded-full text-xs font-bold"><?= $al['gravisimas'] ?></span>
                                    <?php else: ?><span class="text-gray-600">—</span><?php endif ?>
                                </td>
                                <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                                    <a href="index.php?action=anotacion_create&anio_id=<?= $_GET['anio_id'] ?>&curso_id=<?= $_GET['curso_id'] ?>&alumno_id=<?= $al['id'] ?>&matricula_id=<?= $al['matricula_id'] ?>"
                                        class="text-indigo-400 hover:text-indigo-300 font-medium text-sm">+ Anotar</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        <?php elseif (!empty($_GET['anio_id']) && !empty($_GET['curso_id'])): ?>
            <div class="text-center py-12 text-gray-400">No hay alumnos matriculados en este curso y año.</div>

        <?php else: ?>
            <div class="text-center py-16 text-gray-500">
                <p class="text-5xl mb-4">📝</p>
                <p class="text-lg">Selecciona un año y curso para ver las anotaciones.</p>
            </div>
        <?php endif ?>

        <div class="mt-8 flex justify-center">
            <a href="index.php?action=dashboard"
                class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                ⬅️ Dashboard
            </a>
        </div>

    </div>
</main>
<?php include __DIR__ . "/../layout/footer.php"; ?>