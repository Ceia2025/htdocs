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

<main class="min-h-screen bg-gray-900">
    <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Asignación Académica</h1>
            <p class="mt-1 text-sm text-indigo-400 font-medium">Vinculación de Docentes, Cursos y Asignaturas</p>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="relative w-full sm:w-1/2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input id="searchAsignacion" type="text" placeholder="Buscar por profesor o asignatura..."
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-700 rounded-xl bg-gray-800 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition">
            </div>

            <a href="index.php?action=pca_create"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-900/20 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Nueva Asignación
            </a>
        </div>

        <div class="overflow-hidden bg-gray-900 rounded-3xl border border-gray-800 shadow-2xl">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-950/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Profesor</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Detalles Académicos</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Año</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody id="asignacionTableBody" class="divide-y divide-gray-800">
                    <?php if (!empty($asignaciones)): ?>
                        <?php foreach ($asignaciones as $a): 
                            $iniciales = strtoupper(substr($a['profesor_nombre'], 0, 1));
                        ?>
                            <tr class="hover:bg-gray-800/40 transition group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold shadow-inner">
                                            <?= $iniciales ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white group-hover:text-indigo-300 transition">
                                                <?= htmlspecialchars($a['profesor_nombre']) ?>
                                            </div>
                                            <div class="text-xs text-gray-500">Docente</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-gray-800 text-indigo-300 border border-indigo-500/30">CURSO</span>
                                            <span class="text-sm text-gray-200"><?= htmlspecialchars($a['curso_nombre']) ?></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-gray-800 text-emerald-300 border border-emerald-500/30">MATERIA</span>
                                            <span class="text-sm text-gray-300 font-medium"><?= htmlspecialchars($a['asignatura_nombre']) ?></span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                        <?= htmlspecialchars($a['anio']) ?>
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="index.php?action=pca_edit&id=<?= $a['id'] ?>" 
                                           class="p-2 rounded-lg bg-gray-800 text-gray-400 hover:text-indigo-400 hover:bg-gray-750 transition-all" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <a href="index.php?action=pca_delete&id=<?= $a['id'] ?>" 
                                           onclick="return confirm('¿Seguro que deseas eliminar esta asignación?')"
                                           class="p-2 rounded-lg bg-red-900/10 text-red-500 hover:bg-red-600 hover:text-white transition-all" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic bg-gray-900/50">
                                <svg class="mx-auto h-12 w-12 text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                No hay asignaciones académicas registradas.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-10 flex justify-center">
            <a href="index.php?action=dashboard"
                class="inline-flex items-center gap-2 rounded-xl bg-gray-800 px-6 py-2.5 text-sm font-semibold text-gray-300 shadow-sm hover:bg-gray-700 border border-gray-700 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver al Panel Principal
            </a>
        </div>
    </div>
</main>

<script>
    // Buscador local en tiempo real
    document.getElementById('searchAsignacion').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#asignacionTableBody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>