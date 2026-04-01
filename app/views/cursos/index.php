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

<main>
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Gestión de Cursos</h1>
        </div>
    </header>

    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

        <div class="mb-6 flex flex-col sm:flex-row justify-between gap-4">
            <input id="searchCurso" type="text" placeholder="Filtrar cursos..."
                class="px-4 py-2 rounded-lg border border-gray-600 bg-gray-800 text-gray-200 focus:ring focus:ring-indigo-500 w-full sm:w-1/2" />

            <a href="index.php?action=curso_create"
                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition duration-200 gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Nuevo Curso
            </a>
        </div>

        <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg border border-gray-800">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-950/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Detalles del Curso</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider text-center">
                            Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody id="cursoTableBody" class="divide-y divide-gray-800">
                    <?php if (!empty($cursos)): ?>
                        <?php foreach ($cursos as $curso):
                            // Generamos iniciales para el icono visual
                            $inicial = strtoupper(substr($curso['nombre'], 0, 2));
                            ?>
                            <tr class="hover:bg-gray-800/40 transition group cursor-pointer"
                                onclick="window.location='index.php?action=curso_edit&id=<?= $curso['id'] ?>';">

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">

                                        <div>
                                            <p
                                                class="text-sm font-semibold text-white capitalize group-hover:text-indigo-300 transition">
                                                <?= htmlspecialchars($curso['nombre']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/30 text-green-400 border border-green-800">
                                        Activo
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3" onclick="event.stopPropagation();">
                                        <a href="index.php?action=curso_edit&id=<?= $curso['id'] ?>"
                                            class="p-2 rounded-lg bg-gray-800 text-gray-400 hover:text-white hover:bg-gray-700 transition"
                                            title="Editar">
                                            ✏️
                                        </a>
                                        <a href="index.php?action=curso_delete&id=<?= $curso['id'] ?>"
                                            onclick="return confirm('¿Seguro que deseas eliminar este curso?')"
                                            class="p-2 rounded-lg bg-red-900/20 text-red-400 hover:text-white hover:bg-red-600 transition"
                                            title="Eliminar">
                                            🗑️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">
                                No hay cursos registrados actualmente.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-10 flex justify-center">
            <a href="index.php?action=dashboard"
                class="inline-flex items-center gap-2 rounded-xl bg-gray-800 px-6 py-2.5 text-sm font-semibold text-gray-300 shadow-sm hover:bg-gray-700 border border-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Panel Principal
            </a>
        </div>
    </div>
</main>

<script>
    // Buscador en tiempo real (Local)
    document.getElementById('searchCurso').addEventListener('input', function (e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#cursoTableBody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>