<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>
<main class="min-h-screen app-shell">
    <header class="page-header relative">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-strong">Anotaciones</h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- FILTROS -->
        <form method="GET" action="index.php" id="formFiltros"
            class="panel rounded-2xl p-6 grid grid-cols-1 md:grid-cols-4 gap-4 shadow-xl mb-8">
            <input type="hidden" name="action" value="anotaciones">

            <!-- Año -->
            <div class="flex flex-col gap-1">
                <label class="text-xs text-faint uppercase tracking-wider">Año Escolar</label>
                <select name="anio_id" id="sel_anio" onchange="this.form.submit()"
                    class="input-field rounded-xl px-4 py-3 transition">
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
                <label class="text-xs text-faint uppercase tracking-wider">Curso</label>
                <select name="curso_id" id="sel_curso" onchange="this.form.submit()"
                    class="input-field rounded-xl px-4 py-3 transition"
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
                <label class="text-xs text-faint uppercase tracking-wider">Semestre</label>
                <select name="semestre" onchange="this.form.submit()"
                    class="input-field rounded-xl px-4 py-3 transition">
                    <option value="">Todos</option>
                    <option value="1" <?= ($_GET['semestre'] ?? '') == '1' ? 'selected' : '' ?>>1° Semestre</option>
                    <option value="2" <?= ($_GET['semestre'] ?? '') == '2' ? 'selected' : '' ?>>2° Semestre</option>
                </select>
            </div>

            <!-- Botón nueva anotación -->
            <div class="flex flex-col justify-end">
                <?php if (!empty($_GET['anio_id']) && !empty($_GET['curso_id'])): ?>
                    <a href="index.php?action=anotacion_create&anio_id=<?= $_GET['anio_id'] ?>&curso_id=<?= $_GET['curso_id'] ?>"
                        class="btn-primary text-center font-semibold rounded-xl px-4 py-3 shadow-lg transition">
                        ➕ Nueva Anotación
                    </a>
                <?php else: ?>
                    <button disabled
                        class="btn-disabled text-center font-semibold rounded-xl px-4 py-3">
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
                    <h2 class="text-lg font-bold text-strong">
                        Curso: <span class="text-accent"><?= htmlspecialchars($cursoSeleccionado['nombre']) ?></span>
                        <?php if (!empty($_GET['semestre'])): ?>
                            — <span class="text-soft text-base font-normal"><?= $_GET['semestre'] ?>° Semestre</span>
                        <?php endif ?>
                    </h2>
                    <span class="text-sm text-faint"><?= count($alumnos) ?> alumnos</span>
                </div>
            <?php endif ?>

            <div class="panel overflow-x-auto rounded-3xl shadow-lg">
                <table class="data-table min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                                Alumno</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-soft uppercase tracking-wider">
                                Total</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-soft uppercase tracking-wider">
                                Registros</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-soft uppercase tracking-wider">
                                Positivas</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-soft uppercase tracking-wider">
                                Leves</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-soft uppercase tracking-wider">
                                Graves</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-soft uppercase tracking-wider">
                                Gravísimas</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-soft uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos as $al): ?>
                            <?php
                            $urlVer = "index.php?action=anotacion_ver&matricula_id={$al['matricula_id']}&anio_id={$_GET['anio_id']}&curso_id={$_GET['curso_id']}";
                            if (!empty($_GET['semestre']))
                                $urlVer .= "&semestre={$_GET['semestre']}";
                            ?>
                            <tr class="cursor-pointer" onclick="window.location='<?= $urlVer ?>'">
                                <td class="px-4 py-3 text-sm text-strong capitalize font-medium">
                                    <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                                    <span class="text-xs text-faint ml-1"><?= htmlspecialchars($al['run']) ?></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-strong font-bold"><?= $al['total_anotaciones'] ?: '—' ?></span>
                                </td>
                                <td class="px-4 py-3 text-center report-blue">
                                    <?php if (($al['registros'] ?? 0) > 0): ?>
                                        <span
                                            class="report-icon-bg inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold border"
                                            style="border-color: var(--r-border)">
                                            <?= $al['registros'] ?>
                                        </span>
                                    <?php else: ?><span class="text-faint">—</span>
                                    <?php endif ?>
                                </td>
                                <td class="px-4 py-3 text-center report-emerald">
                                    <?php if ($al['positivas'] > 0): ?>
                                        <span
                                            class="report-icon-bg inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold border"
                                            style="border-color: var(--r-border)"><?= $al['positivas'] ?></span>
                                    <?php else: ?><span class="text-faint">—</span><?php endif ?>
                                </td>
                                <td class="px-4 py-3 text-center report-yellow">
                                    <?php if ($al['leves'] > 0): ?>
                                        <span
                                            class="report-icon-bg inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold border"
                                            style="border-color: var(--r-border)"><?= $al['leves'] ?></span>
                                    <?php else: ?><span class="text-faint">—</span><?php endif ?>
                                </td>
                                <td class="px-4 py-3 text-center report-amber">
                                    <?php if ($al['graves'] > 0): ?>
                                        <span
                                            class="report-icon-bg inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold border"
                                            style="border-color: var(--r-border)"><?= $al['graves'] ?></span>
                                    <?php else: ?><span class="text-faint">—</span><?php endif ?>
                                </td>
                                <td class="px-4 py-3 text-center report-red">
                                    <?php if ($al['gravisimas'] > 0): ?>
                                        <span
                                            class="report-icon-bg inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold border"
                                            style="border-color: var(--r-border)"><?= $al['gravisimas'] ?></span>
                                    <?php else: ?><span class="text-faint">—</span><?php endif ?>
                                </td>
                                <td class="px-4 py-3 text-center" onclick="event.stopPropagation()">
                                    <a href="index.php?action=anotacion_create&anio_id=<?= $_GET['anio_id'] ?>&curso_id=<?= $_GET['curso_id'] ?>&alumno_id=<?= $al['id'] ?>&matricula_id=<?= $al['matricula_id'] ?>"
                                        class="link-action font-medium text-sm">+ Anotar</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        <?php elseif (!empty($_GET['anio_id']) && !empty($_GET['curso_id'])): ?>
            <div class="text-center py-12 text-faint">No hay alumnos matriculados en este curso y año.</div>

        <?php else: ?>
            <div class="text-center py-16 text-muted">
                <p class="text-5xl mb-4">📝</p>
                <p class="text-lg text-faint">Selecciona un año y curso para ver las anotaciones.</p>
            </div>
        <?php endif ?>

        <div class="mt-8 flex justify-center">
            <a href="index.php?action=dashboard"
                class="btn-secondary inline-block rounded-md px-4 py-2 text-sm font-semibold shadow">
                ⬅️ Dashboard
            </a>
        </div>

    </div>
</main>

<?php if (!empty($_GET['anio_id']) && !empty($_GET['curso_id'])): ?>
    <a href="index.php?action=anotaciones_pdf&anio_id=<?= $_GET['anio_id'] ?>&curso_id=<?= $_GET['curso_id'] ?>&semestre=<?= htmlspecialchars($_GET['semestre'] ?? '') ?>"
        target="_blank"
        class="btn-danger fixed bottom-6 right-6 z-50 flex items-center gap-2 px-5 py-3 font-bold rounded-full shadow-2xl transition-all hover:scale-105">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        <span>Exportar Reporte</span>
    </a>
<?php endif; ?>

<?php include __DIR__ . "/../layout/footer.php"; ?>