<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Datos de encabezado (del primer item o del querystring)
$g_nombre = $items[0]['nombre'] ?? ($_GET['nombre'] ?? '');
$g_tipo   = $items[0]['tipo']   ?? ($_GET['tipo'] ?? '');
$g_lugar  = $items[0]['lugar']  ?? '';
?>

<html class="h-full bg-gray-900">

<body class="h-full">
    <div class="min-h-full">

        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">
                    <?= htmlspecialchars($g_nombre) ?>
                    <?= !empty($g_tipo) ? '<span class="text-gray-400 font-normal">/ ' . htmlspecialchars($g_tipo) . '</span>' : '' ?>
                </h1>
                <p class="mt-1 text-sm text-gray-400">
                    &#128205; <?= htmlspecialchars($g_lugar) ?>
                    &middot; <span class="text-indigo-300 font-semibold"><?= count($items) ?></span> unidad(es)
                </p>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <?php if (isset($_GET['mov'])): ?>
                    <div class="mb-4 rounded-lg border border-emerald-500/30 bg-emerald-500/10 text-emerald-300 px-4 py-3 text-sm">
                        &#10004; Movimiento registrado.
                    </div>
                <?php endif; ?>

                <div class="mb-4 flex justify-between items-center">
                    <a href="index.php?action=inventario_index"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        &#11013;&#65039; Volver al resumen
                    </a>
                    <a href="index.php?action=inventario_create"
                        class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                        &#10133; Agregar mas
                    </a>
                </div>

                <!-- TABLA INDIVIDUAL -->
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="w-full border-collapse bg-gray-800 text-left text-sm text-gray-300">
                        <thead class="bg-gray-700 text-gray-200">
                            <tr>
                                <th class="px-5 py-3">Codigo</th>
                                <th class="px-5 py-3 text-center">N&deg;</th>
                                <th class="px-5 py-3 text-center">Cant.</th>
                                <th class="px-5 py-3">Estado</th>
                                <th class="px-5 py-3">Nivel</th>
                                <th class="px-5 py-3">Observaciones</th>
                                <th class="px-5 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold
                                         bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 font-mono">
                                            <?= htmlspecialchars($item['codigo_unico']) ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center"><?= htmlspecialchars($item['numero'] ?? '&mdash;') ?></td>
                                    <td class="px-5 py-3 text-center"><?= (int) $item['cantidad'] ?></td>
                                    <td class="px-5 py-3"><?= htmlspecialchars($item['estado']) ?></td>
                                    <td class="px-5 py-3"><?= htmlspecialchars($item['nivel_educativo']) ?></td>
                                    <td class="px-5 py-3 text-gray-400"><?= htmlspecialchars($item['observaciones'] ?? '') ?: '&mdash;' ?></td>
                                    <td class="px-5 py-3">
                                        <div class="flex justify-center gap-2 flex-wrap">
                                            <button type="button"
                                                onclick="abrirModalEditarInv(this)"
                                                data-id="<?= $item['id'] ?>"
                                                data-nombre="<?= htmlspecialchars($item['nombre'], ENT_QUOTES) ?>"
                                                data-tipo="<?= htmlspecialchars($item['tipo'] ?? '', ENT_QUOTES) ?>"
                                                data-numero="<?= htmlspecialchars($item['numero'] ?? '', ENT_QUOTES) ?>"
                                                data-codigo="<?= htmlspecialchars($item['codigo_unico'], ENT_QUOTES) ?>"
                                                data-naturaleza="<?= htmlspecialchars($item['naturaleza'], ENT_QUOTES) ?>"
                                                data-cantidad="<?= (int) $item['cantidad'] ?>"
                                                data-nivel="<?= $item['nivel_id'] ?>"
                                                data-estado="<?= $item['estado_id'] ?>"
                                                data-lugar="<?= $item['lugar_id'] ?>"
                                                data-fecha="<?= htmlspecialchars($item['fecha_registro'] ?? '', ENT_QUOTES) ?>"
                                                data-obs="<?= htmlspecialchars($item['observaciones'] ?? '', ENT_QUOTES) ?>"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                                bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-500/20 transition">
                                                Editar
                                            </button>
                                            <a href="index.php?action=inventario_movimientos&id=<?= $item['id'] ?>"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                                bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20">
                                                Mover
                                            </a>
                                            <a href="index.php?action=inventario_delete&id=<?= $item['id'] ?>"
                                                onclick="return confirm('Dar de baja este bien? Podras restaurarlo luego.');"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                                bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500/20">
                                                Baja
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>


    <!-- ===================== MODAL EDITAR BIEN ===================== -->
    <div id="modal-editar-inv" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="cerrarModalEditarInv()"></div>
        <div class="relative bg-gray-800 border border-gray-700 rounded-2xl shadow-2xl p-6 w-full max-w-2xl z-10 max-h-[90vh] overflow-y-auto">

            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">Inventario</p>
                    <h3 class="text-lg font-bold text-white">Editar bien</h3>
                </div>
                <button onclick="cerrarModalEditarInv()"
                    class="text-gray-500 hover:text-white transition p-1 rounded-lg hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="post" id="form-editar-inv" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-200">Nombre</label>
                    <input type="text" name="nombre" id="edit-nombre" required
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Tipo</label>
                    <input type="text" name="tipo" id="edit-tipo"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">N&deg;</label>
                    <input type="number" name="numero" id="edit-numero" min="1"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Codigo unico</label>
                    <input type="text" name="codigo_unico" id="edit-codigo" required
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Naturaleza</label>
                    <select name="naturaleza" id="edit-naturaleza"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="inventariable">Inventariable</option>
                        <option value="consumible">Consumible</option>
                    </select>
                </div>
                <div id="edit-wrap-cantidad" class="hidden">
                    <label class="block text-sm font-medium text-gray-200">Cantidad</label>
                    <input type="number" name="cantidad" id="edit-cantidad" min="1" value="1"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Nivel Educativo</label>
                    <select name="nivel_id" id="edit-nivel" required
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <?php foreach ($niveles as $n): ?>
                            <option value="<?= $n['id'] ?>"><?= htmlspecialchars($n['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Estado</label>
                    <select name="estado_id" id="edit-estado" required
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <?php foreach ($estados as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Lugar Fisico</label>
                    <select name="lugar_id" id="edit-lugar" required
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <?php foreach ($lugares as $l): ?>
                            <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Fecha de registro</label>
                    <input type="date" name="fecha_registro" id="edit-fecha" max="<?= date('Y-m-d') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-200">Observaciones</label>
                    <textarea name="observaciones" id="edit-obs" rows="2"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>
                <div class="md:col-span-2 flex gap-3 pt-2">
                    <button type="button" onclick="cerrarModalEditarInv()"
                        class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition text-sm">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modalEdit = document.getElementById('modal-editar-inv');
        const formEdit  = document.getElementById('form-editar-inv');
        const natEdit   = document.getElementById('edit-naturaleza');
        const wrapCantEdit = document.getElementById('edit-wrap-cantidad');

        function toggleCantidadEdit() {
            if (natEdit.value === 'consumible') wrapCantEdit.classList.remove('hidden');
            else wrapCantEdit.classList.add('hidden');
        }
        natEdit.addEventListener('change', toggleCantidadEdit);

        function abrirModalEditarInv(btn) {
            const d = btn.dataset;
            formEdit.action = 'index.php?action=inventario_update&id=' + d.id;
            document.getElementById('edit-nombre').value     = d.nombre || '';
            document.getElementById('edit-tipo').value       = d.tipo || '';
            document.getElementById('edit-numero').value     = d.numero || '';
            document.getElementById('edit-codigo').value     = d.codigo || '';
            document.getElementById('edit-naturaleza').value = d.naturaleza || 'inventariable';
            document.getElementById('edit-cantidad').value   = d.cantidad || 1;
            document.getElementById('edit-nivel').value      = d.nivel;
            document.getElementById('edit-estado').value     = d.estado;
            document.getElementById('edit-lugar').value      = d.lugar;
            document.getElementById('edit-fecha').value      = d.fecha || '';
            document.getElementById('edit-obs').value        = d.obs || '';
            toggleCantidadEdit();
            modalEdit.classList.remove('hidden');
        }

        function cerrarModalEditarInv() {
            modalEdit.classList.add('hidden');
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') cerrarModalEditarInv();
        });
    </script>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>