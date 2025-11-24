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
?>

<body class="h-full">
<div class="min-h-full">

    <!-- HEADER -->
    <header class="relative bg-gray-800 border-b border-gray-700">
        <div class="mx-auto max-w-7xl px-4 py-6">
            <h1 class="text-3xl font-bold text-white">Relaciones Curso - Asignatura</h1>
        </div>
    </header>

    <!-- MAIN -->
    <main>
        <div class="mx-auto max-w-6xl px-4 py-6">

            <div class="flex items-center justify-between mb-6">
                <form method="get" action="index.php" class="flex items-center gap-2">
                    <input type="hidden" name="action" value="curso_asignaturas">
                    <label for="curso_id" class="text-gray-300 font-semibold">Filtrar por curso:</label>
                    <select name="curso_id" id="curso_id"
                            class="rounded-md bg-gray-800 text-gray-200 border border-gray-600 px-3 py-2"
                            onchange="this.form.submit()">
                        <option value="">-- Todos --</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id'] ?>"
                                <?= (isset($_GET['curso_id']) && $_GET['curso_id'] == $curso['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($curso['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>

                <a href="index.php?action=curso_asignaturas_create"
                   class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700">
                    Nueva relación
                </a>
            </div>

            <!-- ACORDEÓN -->
            <?php if (!empty($agrupado)): ?>
                <div class="space-y-4">

                    <?php foreach ($agrupado as $cursoNombre => $asignaturas): ?>
                        <div class="bg-gray-800 rounded-lg shadow border border-gray-700">

                            <!-- ENCABEZADO DEL ACORDEÓN -->
                            <button class="w-full px-6 py-4 flex justify-between items-center text-left text-gray-200 hover:bg-gray-700 transition group"
                                    onclick="toggleCollapse('c_<?= md5($cursoNombre) ?>')">

                                <span class="font-semibold text-lg"><?= htmlspecialchars($cursoNombre) ?></span>

                                <svg id="ic_<?= md5($cursoNombre) ?>" class="w-5 h-5 transition-transform"
                                     fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>

                            </button>

                            <!-- CONTENIDO OCULTO -->
                            <div id="c_<?= md5($cursoNombre) ?>" class="hidden px-6 pb-4">

                                <ul class="space-y-2 mt-2">

                                    <?php foreach ($asignaturas as $ca): ?>
                                        <li class="flex justify-between bg-gray-900 px-4 py-3 rounded-lg border border-gray-700 hover:bg-gray-700/50 transition">

                                            <span class="text-gray-300">
                                                <?= htmlspecialchars($ca['asignatura']) ?>
                                            </span>

                                            <span class="space-x-3">
                                                <a href="index.php?action=curso_asignaturas_edit&id=<?= $ca['id'] ?>"
                                                   class="text-indigo-400 hover:text-indigo-300">✏️ Editar</a>

                                                <a href="index.php?action=curso_asignaturas_delete&id=<?= $ca['id'] ?>"
                                                   onclick="return confirm('¿Eliminar esta asignatura del curso?')"
                                                   class="text-red-400 hover:text-red-300">🗑️ Eliminar</a>
                                            </span>

                                        </li>
                                    <?php endforeach; ?>

                                </ul>
                            </div>

                        </div>
                    <?php endforeach; ?>

                </div>

            <?php else: ?>
                <p class="text-gray-400 text-center mt-6">No hay relaciones registradas.</p>
            <?php endif; ?>

            <!-- VOLVER -->
            <div class="mt-10 flex justify-center">
                <a href="index.php?action=dashboard"
                   class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                    ⬅️ Volver al Dashboard
                </a>
            </div>

        </div>
    </main>
</div>

<script>
function toggleCollapse(id) {
    const div = document.getElementById(id);
    const icon = document.getElementById('ic_' + id.split('_')[1]);

    if (div.classList.contains('hidden')) {
        div.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        div.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>
