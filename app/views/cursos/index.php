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
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Gestión de Cursos</h1>
    </div>
</header>

<main>
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

        <div class="mb-6 flex flex-col sm:flex-row justify-between gap-4">
            <input id="searchCurso" type="text" placeholder="Filtrar cursos..."
                class="input-field px-4 py-2 rounded-lg w-full sm:w-1/2" />

            <a href="index.php?action=curso_create"
                class="btn-primary inline-flex items-center justify-center px-4 py-2 font-semibold rounded-lg shadow transition gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Nuevo Curso
            </a>
        </div>

        <div class="panel overflow-x-auto rounded-2xl shadow-lg">
            <table class="data-table min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-muted uppercase tracking-wider">
                            Detalles del Curso</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-muted uppercase tracking-wider">
                            Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-muted uppercase tracking-wider">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody id="cursoTableBody">
                    <?php if (!empty($cursos)): ?>
                        <?php foreach ($cursos as $curso): ?>
                            <tr class="group cursor-pointer"
                                onclick="window.location='index.php?action=curso_edit&id=<?= $curso['id'] ?>';">

                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-strong group-hover:text-accent transition">
                                        <?= htmlspecialchars($curso['nombre']) ?>
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="banner-success inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        Activo
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3" onclick="event.stopPropagation();">
                                        <a href="index.php?action=curso_edit&id=<?= $curso['id'] ?>"
                                            class="icon-btn p-2 rounded-lg transition" title="Editar">
                                            ✏️
                                        </a>
                                        <a href="index.php?action=curso_delete&id=<?= $curso['id'] ?>"
                                            onclick="return confirm('¿Seguro que deseas eliminar este curso?')"
                                            class="p-2 rounded-lg transition text-danger hover:bg-red-600 hover:text-white"
                                            title="Eliminar">
                                            🗑️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-muted italic">
                                No hay cursos registrados actualmente.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-10 flex justify-center">
            <a href="index.php?action=dashboard"
                class="btn-secondary inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold shadow-sm transition">
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
    document.getElementById('searchCurso').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('#cursoTableBody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
        });
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>