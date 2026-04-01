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

<main class="min-h-screen bg-gray-900">
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Malla Curricular</h1>
            <p class="mt-1 text-sm text-indigo-400 font-medium">Relación entre Niveles y Asignaturas</p>
        </div>
    </header>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

        <div
            class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6 bg-gray-800/50 p-6 rounded-2xl border border-white/5 shadow-xl">
            <form method="get" action="index.php" class="flex flex-col gap-2 w-full md:w-auto">
                <input type="hidden" name="action" value="curso_asignaturas">
                <label for="curso_id" class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Filtrar por
                    Nivel</label>
                <div class="relative">
                    <select name="curso_id" id="curso_id"
                        class="appearance-none w-full md:w-72 rounded-xl bg-gray-900 text-gray-200 border border-gray-700 px-4 py-2.5 focus:ring-2 focus:ring-indigo-600 outline-none transition"
                        onchange="this.form.submit()">
                        <option value="">Todos los cursos</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id'] ?>" <?= (isset($_GET['curso_id']) && $_GET['curso_id'] == $curso['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($curso['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </form>

            <a href="index.php?action=curso_asignaturas_create"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-900/20 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Vincular Materia
            </a>
        </div>

        <?php if (!empty($agrupado)): ?>
            <div class="space-y-4">
                <?php foreach ($agrupado as $cursoNombre => $asignaturas):
                    $idUnico = md5($cursoNombre);
                    ?>
                    <div
                        class="overflow-hidden bg-gray-800/40 rounded-2xl border border-gray-800 shadow-sm transition-all hover:border-gray-700">

                        <button class="w-full px-6 py-5 flex justify-between items-center text-left transition-colors group"
                            onclick="toggleCollapse('c_<?= $idUnico ?>')">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-lg bg-gray-900 flex items-center justify-center border border-white/5 text-indigo-400 group-hover:scale-110 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-white group-hover:text-indigo-300 transition">
                                        <?= htmlspecialchars($cursoNombre) ?></h3>
                                    <p class="text-xs text-gray-500 font-medium"><?= count($asignaturas) ?> materias asignadas
                                    </p>
                                </div>
                            </div>

                            <svg id="ic_<?= $idUnico ?>" class="w-6 h-6 text-gray-600 transition-transform duration-300"
                                fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div id="c_<?= $idUnico ?>" class="hidden px-6 pb-6 animate-fadeIn">
                            <div class="flex flex-col gap-3 mt-2">
                                <?php foreach ($asignaturas as $ca): ?>
                                    <div
                                        class="flex items-center justify-between bg-gray-900/60 backdrop-blur-sm px-4 py-4 rounded-xl border border-white/5 hover:border-indigo-500/30 transition group/item">
                                        <div class="flex items-center gap-4">
                                            <div class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.6)]">
                                            </div>
                                            <span class="text-base font-medium text-gray-200">
                                                <?= htmlspecialchars($ca['asignatura']) ?>
                                            </span>
                                        </div>

                                        <div
                                            class="flex items-center gap-2 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                            <a href="index.php?action=curso_asignaturas_edit&id=<?= $ca['id'] ?>"
                                                class="p-2 text-gray-400 hover:text-indigo-400 bg-gray-800 rounded-lg transition"
                                                title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                        stroke-width="2" />
                                                </svg>
                                            </a>
                                            <a href="index.php?action=curso_asignaturas_delete&id=<?= $ca['id'] ?>"
                                                onclick="return confirm('¿Eliminar esta asignatura del curso?')"
                                                class="p-2 text-gray-400 hover:text-red-500 bg-gray-800 rounded-lg transition"
                                                title="Desvincular">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        stroke-width="2" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="text-center py-20 bg-gray-800/20 rounded-3xl border border-dashed border-gray-700">
                <p class="text-gray-500 text-lg">No se encontraron relaciones para los criterios seleccionados.</p>
            </div>
        <?php endif; ?>

        <div class="mt-12 flex justify-center">
            <a href="index.php?action=dashboard"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-800 text-gray-400 font-semibold rounded-xl hover:bg-gray-700 hover:text-white transition-all border border-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Volver al Panel Principal
            </a>
        </div>
    </div>
</main>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>

<script>
    function toggleCollapse(id) {
        const div = document.getElementById(id);
        const idHash = id.split('_')[1];
        const icon = document.getElementById('ic_' + idHash);

        if (div.classList.contains('hidden')) {
            div.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
            icon.classList.add('text-indigo-400');
        } else {
            div.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
            icon.classList.remove('text-indigo-400');
        }
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>