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
        <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Listado de Asignaturas</h1>
        <p class="mt-1 text-sm text-muted">Administración del currículo académico</p>
    </div>
</header>

<main class="min-h-screen">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">

        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <input id="searchAsignatura" type="text" placeholder="Buscar asignatura..."
                class="input-field block w-full sm:w-1/2 px-4 py-2.5 rounded-xl">

            <a href="index.php?action=asignatura_create"
                class="btn-primary w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 font-bold rounded-xl shadow-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Asignatura
            </a>
        </div>

        <div class="panel overflow-hidden rounded-2xl shadow-2xl">
            <table class="data-table min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-muted uppercase tracking-widest">Abrev.</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-muted uppercase tracking-widest">Nombre</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-muted uppercase tracking-widest">Detalles</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-muted uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody id="asignaturaTableBody">
                    <?php if (!empty($asignaturas)): ?>
                        <?php foreach ($asignaturas as $asignatura): ?>
                            <tr class="group transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-accent">
                                    [<?= htmlspecialchars($asignatura['abreviatura']) ?>]
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-strong capitalize">
                                    <?= htmlspecialchars($asignatura['nombre']) ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button
                                        onclick="openDescriptionModal('<?= htmlspecialchars($asignatura['nombre']) ?>', '<?= htmlspecialchars($asignatura['descp']) ?>')"
                                        class="icon-btn p-2 rounded-full transition" title="Ver descripción">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="index.php?action=asignatura_edit&id=<?= $asignatura['id'] ?>"
                                        class="icon-btn inline-flex p-2 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <a href="index.php?action=asignatura_delete&id=<?= $asignatura['id'] ?>"
                                        onclick="return confirm('¿Seguro que deseas eliminar esta asignatura?')"
                                        class="inline-flex p-2 rounded-lg transition text-danger hover:bg-red-600 hover:text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-muted italic">No hay asignaturas registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-center">
            <a href="index.php?action=dashboard"
                class="btn-secondary inline-flex items-center gap-2 px-6 py-2 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Panel
            </a>
        </div>
    </div>
</main>

<!-- Modal descripción -->
<div id="descModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDescriptionModal()"></div>
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="modal-panel relative overflow-hidden rounded-2xl text-left shadow-2xl w-full max-w-lg">
                <div class="px-6 py-5">
                    <div class="flex items-center justify-between border-b divider-soft pb-3">
                        <h3 class="text-xl font-bold text-strong font-display" id="modalTitle">Nombre Asignatura</h3>
                        <button onclick="closeDescriptionModal()" class="icon-btn transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4">
                        <p class="text-muted text-xs font-bold uppercase tracking-widest mb-2">Descripción Detallada</p>
                        <div id="modalDesc"
                            class="panel text-soft leading-relaxed text-base italic p-4 rounded-xl">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t divider-soft text-right">
                    <button onclick="closeDescriptionModal()"
                        class="btn-primary w-full sm:w-auto px-5 py-2 text-sm font-bold rounded-xl transition">
                        Cerrar ventana
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchAsignatura').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('#asignaturaTableBody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
        });
    });

    function openDescriptionModal(nombre, descripcion) {
        document.getElementById('modalTitle').innerText = nombre;
        document.getElementById('modalDesc').innerText = descripcion || "Sin descripción disponible.";
        document.getElementById('descModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDescriptionModal() {
        document.getElementById('descModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDescriptionModal(); });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>