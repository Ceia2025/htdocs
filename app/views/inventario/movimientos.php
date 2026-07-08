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

<html class="h-full bg-gray-900">

<body class="h-full">
    <div class="min-h-full">

        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Movimientos del bien</h1>
                <p class="mt-1 text-sm text-gray-400">
                    <span class="font-mono text-indigo-300"><?= htmlspecialchars($item['codigo_unico']) ?></span>
                    &middot; <?= htmlspecialchars($item['nombre']) ?>
                    <?= !empty($item['tipo']) ? '(' . htmlspecialchars($item['tipo']) . ')' : '' ?>
                </p>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FLASH -->
                <?php if (isset($_GET['mov'])): ?>
                    <div class="mb-4 rounded-lg border border-emerald-500/30 bg-emerald-500/10 text-emerald-300 px-4 py-3 text-sm">
                        &#10004; Movimiento registrado correctamente.
                    </div>
                <?php endif; ?>

                <!-- UBICACION ACTUAL -->
                <div class="mb-6 bg-gray-800 border border-gray-700 rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                    <span class="text-xs uppercase tracking-widest text-gray-400">Ubicacion actual</span>
                    <span class="text-lg font-bold text-white">&#128205; <?= htmlspecialchars($item['lugar']) ?></span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- FORMULARIO REGISTRAR MOVIMIENTO -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-700 p-6 rounded-2xl shadow-lg">
                            <h2 class="text-lg font-bold text-white mb-4">Registrar movimiento</h2>

                            <form method="POST" action="index.php?action=inventario_movimiento_store" class="space-y-4">
                                <input type="hidden" name="inventario_id" value="<?= $item['id'] ?>">

                                <!-- Lugar destino -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-200">Nuevo lugar</label>
                                    <select name="lugar_destino_id" required
                                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                        <?php foreach ($lugares as $l): ?>
                                            <option value="<?= $l['id'] ?>" <?= ($l['id'] == $item['lugar_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($l['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="text-xs text-gray-400 mt-1">Si es el mismo lugar, queda como nota/observacion.</p>
                                </div>

                                <!-- Fecha -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-200">Fecha</label>
                                    <input type="date" name="fecha" max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>"
                                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>

                                <!-- Observacion -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-200">Observacion</label>
                                    <textarea name="observacion" rows="3" placeholder="Ej: Se traslado por reparacion..."
                                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition">
                                    Guardar movimiento
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- HISTORIAL -->
                    <div class="lg:col-span-2">
                        <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-700">
                                <h2 class="text-lg font-bold text-white">Historial</h2>
                            </div>

                            <?php if (!empty($movimientos)): ?>
                                <ul class="divide-y divide-gray-700">
                                    <?php foreach ($movimientos as $mov): ?>
                                        <li class="px-6 py-4">
                                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                                <div class="flex items-center gap-2 text-sm">
                                                    <span class="text-gray-300"><?= htmlspecialchars($mov['lugar_origen'] ?? '&mdash;') ?></span>
                                                    <span class="text-indigo-400">&rarr;</span>
                                                    <span class="font-semibold text-white"><?= htmlspecialchars($mov['lugar_destino'] ?? '&mdash;') ?></span>
                                                </div>
                                                <span class="text-xs text-gray-400 font-mono">
                                                    <?= $mov['fecha'] ? date('d/m/Y', strtotime($mov['fecha'])) : '' ?>
                                                </span>
                                            </div>
                                            <?php if (!empty($mov['observacion'])): ?>
                                                <p class="mt-1 text-sm text-gray-400"><?= htmlspecialchars($mov['observacion']) ?></p>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="px-6 py-8 text-center text-gray-400 text-sm">
                                    Este bien aun no tiene movimientos registrados.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <!-- VOLVER -->
                <div class="mt-8 flex justify-center">
                    <a href="index.php?action=inventario_index"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        &#11013;&#65039; Volver al Inventario
                    </a>
                </div>

            </div>
        </main>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>