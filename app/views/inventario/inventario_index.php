<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Agrupar los resultados por lugar (solo apareceran lugares con items)
$porLugar = [];
foreach ($items as $g) {
    $porLugar[$g['lugar']][] = $g;
}
?>

<html class="h-full bg-gray-900">

<body class="h-full">
    <div class="min-h-full">

        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Inventario</h1>
                <p class="mt-1 text-sm text-gray-400">Resumen agrupado por lugar fisico.</p>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FLASH MESSAGES -->
                <?php
                $flash = null;
                if (isset($_GET['creado']))       $flash = 'Bien registrado correctamente.';
                if (isset($_GET['creados']))      $flash = ((int) $_GET['creados']) . ' bienes registrados correctamente.';
                if (isset($_GET['actualizado']))  $flash = 'Bien actualizado correctamente.';
                if (isset($_GET['eliminado']))    $flash = 'Bien eliminado (puedes restaurarlo).';
                if (isset($_GET['restaurado']))   $flash = 'Bien restaurado correctamente.';
                ?>
                <?php if ($flash): ?>
                    <div class="mb-4 rounded-lg border border-emerald-500/30 bg-emerald-500/10 text-emerald-300 px-4 py-3 text-sm">
                        &#10004; <?= htmlspecialchars($flash) ?>
                    </div>
                <?php endif; ?>

                <!-- FILTRO DE BUSQUEDA -->
                <form method="GET" action="index.php" class="mb-6 bg-gray-800 p-4 md:p-6 rounded-xl shadow-xl text-white">
                    <input type="hidden" name="action" value="inventario_index">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 items-end">

                        <div>
                            <label class="block text-sm font-semibold mb-1">Nombre, tipo o codigo</label>
                            <input type="text" name="busqueda"
                                value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>"
                                placeholder="Ej: Silla, Plastica o SILLA-001"
                                class="w-full h-[42px] rounded-md bg-gray-700 text-white px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Nivel Educativo</label>
                            <select name="nivel_id"
                                class="w-full h-[42px] rounded-md bg-gray-700 text-white px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Todos --</option>
                                <?php foreach ($niveles as $n): ?>
                                    <option value="<?= $n['id'] ?>" <?= ($_GET['nivel_id'] ?? '') == $n['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($n['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Naturaleza</label>
                            <select name="naturaleza"
                                class="w-full h-[42px] rounded-md bg-gray-700 text-white px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Todas --</option>
                                <option value="inventariable" <?= ($_GET['naturaleza'] ?? '') === 'inventariable' ? 'selected' : '' ?>>Inventariable</option>
                                <option value="consumible" <?= ($_GET['naturaleza'] ?? '') === 'consumible' ? 'selected' : '' ?>>Consumible</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Estado</label>
                            <select name="estado_id"
                                class="w-full h-[42px] rounded-md bg-gray-700 text-white px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Todos --</option>
                                <?php foreach ($estados as $e): ?>
                                    <option value="<?= $e['id'] ?>" <?= ($_GET['estado_id'] ?? '') == $e['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($e['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Lugar Fisico</label>
                            <select name="lugar_id"
                                class="w-full h-[42px] rounded-md bg-gray-700 text-white px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Todos --</option>
                                <?php foreach ($lugares as $l): ?>
                                    <option value="<?= $l['id'] ?>" <?= ($_GET['lugar_id'] ?? '') == $l['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($l['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="flex flex-wrap gap-2 justify-end sm:justify-start xl:justify-end mt-2 xl:mt-0">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg shadow flex items-center gap-2">
                                &#128269; Buscar
                            </button>
                            <a href="index.php?action=inventario_index"
                                class="bg-gray-600 hover:bg-gray-500 text-white font-semibold px-4 py-2 rounded-lg shadow flex items-center gap-2">
                                &#10060; Limpiar
                            </a>
                        </div>
                    </div>
                </form>

                <!-- BARRA SUPERIOR -->
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-gray-400">
                        <span class="text-indigo-400 font-bold"><?= count($porLugar) ?></span> lugar(es)
                        &middot; <span class="text-indigo-400 font-bold"><?= count($items) ?></span> grupo(s)
                    </p>
                    <div class="flex gap-2">
                        <a href="index.php?action=inventario_exportExcel<?= !empty($_SERVER['QUERY_STRING'])
                            ? '&' . preg_replace('/(^|&)action=[^&]*/', '', $_SERVER['QUERY_STRING'])
                            : '' ?>"
                            class="inline-block rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-green-700 transition">
                            Exportar a Excel
                        </a>
                        <a href="index.php?action=inventario_create"
                            class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                            &#10133; Nuevo Registro
                        </a>
                    </div>
                </div>

                <!-- SECCIONES POR LUGAR -->
                <?php if (!empty($porLugar)): ?>
                    <?php foreach ($porLugar as $lugarNombre => $grupos): ?>
                        <?php
                            // Total de unidades en este lugar
                            $totalLugar = 0;
                            foreach ($grupos as $gg) {
                                $totalLugar += ($gg['naturaleza'] === 'consumible')
                                    ? (int) $gg['stock_total']
                                    : (int) $gg['unidades'];
                            }
                        ?>
                        <section class="mb-6 bg-gray-800 border border-gray-700 rounded-xl shadow overflow-hidden">

                            <!-- Encabezado de la sala -->
                            <div class="px-5 py-3 bg-gray-900/40 border-b border-gray-700 flex items-center justify-between gap-3">
                                <h2 class="text-white font-bold flex items-center gap-2">
                                    &#128205; <?= htmlspecialchars($lugarNombre) ?>
                                </h2>
                                <span class="inline-flex items-center gap-1 text-xs text-gray-300">
                                    <span class="px-2 py-0.5 rounded-full bg-indigo-500/15 text-indigo-300 border border-indigo-500/30 font-bold">
                                        <?= $totalLugar ?>
                                    </span>
                                    items &middot; <?= count($grupos) ?> grupo(s)
                                </span>
                            </div>

                            <!-- Tabla de la sala -->
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse text-left text-sm text-gray-300">
                                    <thead class="bg-gray-700/60 text-gray-300 text-xs uppercase">
                                        <tr>
                                            <th class="px-5 py-2">Nombre</th>
                                            <th class="px-5 py-2">Tipo</th>
                                            <th class="px-5 py-2 text-center">Naturaleza</th>
                                            <th class="px-5 py-2 text-center">Cantidad</th>
                                            <th class="px-5 py-2">Nivel</th>
                                            <th class="px-5 py-2">Estado</th>
                                            <th class="px-5 py-2 text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($grupos as $g): ?>
                                            <?php
                                                $unidades = (int) $g['unidades'];
                                                $cantidad = $g['naturaleza'] === 'consumible' ? (int) $g['stock_total'] : $unidades;
                                                $qs = 'nombre=' . urlencode($g['nombre'])
                                                    . '&tipo=' . urlencode($g['tipo'] ?? '')
                                                    . '&lugar_id=' . urlencode($g['lugar_id']);
                                            ?>
                                            <tr class="border-b border-gray-700/70 hover:bg-gray-700/40">
                                                <td class="px-5 py-3 font-semibold text-white"><?= htmlspecialchars($g['nombre']) ?></td>
                                                <td class="px-5 py-3"><?= htmlspecialchars($g['tipo'] ?? '') ?: '&mdash;' ?></td>

                                                <td class="px-5 py-3 text-center">
                                                    <?php if ($g['naturaleza'] === 'consumible'): ?>
                                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold
                                                         bg-amber-500/10 text-amber-300 border border-amber-500/20">Consumible</span>
                                                    <?php else: ?>
                                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold
                                                         bg-sky-500/10 text-sky-300 border border-sky-500/20">Inventariable</span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="px-5 py-3 text-center">
                                                    <span class="inline-flex items-center justify-center min-w-[2.2rem] px-2 py-1 rounded-lg
                                                     text-sm font-bold bg-indigo-500/15 text-indigo-300 border border-indigo-500/30">
                                                        <?= $cantidad ?>
                                                    </span>
                                                    <?php if ($g['naturaleza'] === 'consumible'): ?>
                                                        <span class="block text-[10px] text-gray-500 mt-0.5">stock</span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="px-5 py-3">
                                                    <?= (int) $g['niveles_distintos'] > 1 ? '<span class="text-gray-500 italic">Varios</span>' : htmlspecialchars($g['nivel']) ?>
                                                </td>
                                                <td class="px-5 py-3">
                                                    <?= (int) $g['estados_distintos'] > 1 ? '<span class="text-gray-500 italic">Varios</span>' : htmlspecialchars($g['estado']) ?>
                                                </td>

                                                <td class="px-5 py-3 text-center">
                                                    <a href="index.php?action=inventario_grupo&<?= $qs ?>"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                        bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 hover:bg-indigo-500/20 transition">
                                                        Gestionar
                                                        <?php if ($unidades > 1): ?>
                                                            <span class="ml-1 px-1.5 py-0.5 rounded-full bg-indigo-500/30 text-[10px]"><?= $unidades ?></span>
                                                        <?php endif; ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-10 text-center text-gray-400">
                        No hay registros en el inventario.
                    </div>
                <?php endif; ?>

                <div class="mt-6 flex justify-center">
                    <a href="index.php?action=dashboard"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        &#11013;&#65039; Volver al Dashboard
                    </a>
                </div>

            </div>
        </main>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>