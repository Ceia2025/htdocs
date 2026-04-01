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
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Listado de Asignaturas</h1>
            <p class="mt-1 text-sm text-gray-400">Administración del currículo académico</p>
        </div>
    </header>

    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">

        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <input id="searchAsignatura" type="text" placeholder="Buscar asignatura..."
                class="block w-full sm:w-1/2 px-4 py-2.5 border border-gray-700 rounded-xl bg-gray-800 text-gray-200 focus:ring-2 focus:ring-indigo-600 outline-none transition">

            <a href="index.php?action=asignatura_create"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Asignatura
            </a>
        </div>

        <div class="overflow-hidden bg-gray-900 rounded-3xl border border-gray-800 shadow-2xl">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-950/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Abrev.
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Nombre
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                            Detalles</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody id="asignaturaTableBody" class="divide-y divide-gray-800">
                    <?php if (!empty($asignaturas)): ?>
                        <?php foreach ($asignaturas as $asignatura): ?>
                            <tr class="hover:bg-gray-800/40 transition group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-indigo-400">
                                    [<?= htmlspecialchars($asignatura['abreviatura']) ?>]
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-200 capitalize">
                                    <?= htmlspecialchars($asignatura['nombre']) ?>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <button
                                        onclick="openDescriptionModal('<?= htmlspecialchars($asignatura['nombre']) ?>', '<?= htmlspecialchars($asignatura['descp']) ?>')"
                                        class="p-2 rounded-full bg-gray-800 text-gray-400 hover:text-indigo-400 hover:bg-gray-700 transition"
                                        title="Ver descripción">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </td>

                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="index.php?action=asignatura_edit&id=<?= $asignatura['id'] ?>"
                                        class="inline-flex p-2 bg-gray-800 text-gray-400 hover:text-white hover:bg-indigo-600 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <a href="index.php?action=asignatura_delete&id=<?= $asignatura['id'] ?>"
                                        onclick="return confirm('¿Seguro que deseas eliminar esta asignatura?')"
                                        class="inline-flex p-2 bg-red-900/20 text-red-400 hover:bg-red-600 hover:text-white rounded-lg transition">
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
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">No hay asignaturas
                                registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-center">
            <a href="index.php?action=dashboard"
                class="inline-flex items-center gap-2 px-6 py-2 bg-gray-800 text-gray-300 rounded-xl hover:bg-gray-700 transition border border-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Panel
            </a>
        </div>
    </div>
</main>

<div id="descModal" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm transition-opacity" onclick="closeDescriptionModal()">
    </div>

    <div class="fixed inset-0 z-[101] overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

            <div
                class="relative transform overflow-hidden rounded-2xl bg-gray-800 border border-white/10 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="px-6 py-5">
                    <div class="flex items-center justify-between border-b border-gray-700 pb-3">
                        <h3 class="text-xl font-bold text-white" id="modalTitle">Nombre Asignatura</h3>
                        <button onclick="closeDescriptionModal()" class="text-gray-400 hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-2">Descripción Detallada
                        </p>
                        <div id="modalDesc"
                            class="text-gray-200 leading-relaxed text-base italic bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-900/30 text-right">
                    <button onclick="closeDescriptionModal()"
                        class="w-full sm:w-auto px-5 py-2 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-500 transition shadow-lg shadow-indigo-500/20">
                        Cerrar ventana
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Buscador local
    document.getElementById('searchAsignatura').addEventListener('input', function (e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#asignaturaTableBody tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
        });
    });

    // Lógica del Modal
    const modal = document.getElementById('descModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDesc = document.getElementById('modalDesc');

    function openDescriptionModal(nombre, descripcion) {
        const modal = document.getElementById('descModal');
        document.getElementById('modalTitle').innerText = nombre;
        document.getElementById('modalDesc').innerText = descripcion || "Sin descripción disponible.";

        // Cambiamos hidden por flex para centrar
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
    }

    function closeDescriptionModal() {
        const modal = document.getElementById('descModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // Cerrar con la tecla ESC
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDescriptionModal();
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>