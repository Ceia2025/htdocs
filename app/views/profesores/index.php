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

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="relative bg-gray-800 after:absolute after:inset-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6">
                <h1 class="text-3xl font-bold tracking-tight text-white">Profesores</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-8">

                <!-- BOTÓN CREAR -->
                <div class="mb-6 flex justify-end">
                    <a href="index.php?action=profesor_create" class="group inline-flex items-center gap-2 px-5 py-2.5 
               bg-gradient-to-r from-indigo-500 to-purple-600 
               hover:from-indigo-600 hover:to-purple-700 
               text-white font-semibold rounded-xl shadow-md
               hover:shadow-lg transition-all duration-300">
                        <span class="text-lg">＋</span>
                        <span>Nuevo Profesor</span>
                    </a>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">

                    <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
                        <?php
                        function formatearRUN($run, $dv)
                        {
                            $run = preg_replace('/\D/', '', $run);
                            $run_formateado = number_format($run, 0, '', '.');
                            return $run_formateado . "-" . strtoupper($dv);
                        }
                        ?>

                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-950/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">RUN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Nombre
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Tipo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Acciones
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-700">
                                <?php if (!empty($profesores)): ?>
                                    <?php foreach ($profesores as $p): ?>

                                        <?php
                                        // 🔵 marcar colores según tipo
                                        $rowClass = $p['tipo'] === 'titular'
                                            ? 'bg-gray-700/20 hover:bg-gray-700/40'
                                            : 'bg-purple-700/10 hover:bg-purple-700/30';
                                        ?>

                                        <tr class="<?= $rowClass ?> transition-all duration-300">
                                            <td class="px-6 py-4 text-sm">
                                                <span class="px-3 py-1 rounded-full border border-indigo-400 text-indigo-300
                                       bg-gray-800 shadow-inner text-sm font-mono">
                                                    <?= htmlspecialchars(formatearRUN($p['run'], $p['codver'])) ?>
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-100">
                                                <?= htmlspecialchars($p['nombres'] . " " . $p['apepat'] . " " . $p['apemat']) ?>
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-100 capitalize">
                                                <?php if ($p['tipo'] === 'titular'): ?>
                                                    <span
                                                        class="px-2 py-1 rounded-lg bg-indigo-600/40 text-indigo-200 text-xs font-semibold">
                                                        Titular
                                                    </span>
                                                <?php else: ?>
                                                    <span
                                                        class="px-2 py-1 rounded-lg bg-purple-600/40 text-purple-200 text-xs font-semibold">
                                                        Suplente
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="px-6 py-4 text-sm">
                                                <?php if ($p['estado'] === 'activo'): ?>
                                                    <span class="flex items-center gap-2 text-green-400">
                                                        <span class="size-3 rounded-full bg-green-400"></span> Activo
                                                    </span>
                                                <?php else: ?>
                                                    <span class="flex items-center gap-2 text-red-400">
                                                        <span class="size-3 rounded-full bg-red-400"></span> Inactivo
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="px-6 py-4 text-sm flex gap-3">

                                                <!-- Editar -->
                                                <a href="index.php?action=profesor_edit&id=<?= $p['id'] ?>" class="px-3 py-1.5 rounded-lg bg-blue-600/20 text-blue-300 
                                       hover:bg-blue-600/40 hover:text-blue-100 
                                       transition-all duration-300 text-xs font-semibold flex items-center gap-1">
                                                    ✏️ Editar
                                                </a>

                                                <!-- Eliminar -->
                                                <a href="index.php?action=profesor_delete&id=<?= $p['id'] ?>"
                                                    onclick="return confirm('¿Eliminar profesor?');" class="px-3 py-1.5 rounded-lg bg-red-600/20 text-red-300 
                                       hover:bg-red-600/40 hover:text-red-100 
                                       transition-all duration-300 text-xs font-semibold flex items-center gap-1">
                                                    🗑 Eliminar
                                                </a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-300">
                                            No hay profesores registrados.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- VOLVER -->
                <div class="mt-8 flex justify-center">
                    <a href="index.php?action=dashboard"
                        class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600">
                        ⬅ Dashboard
                    </a>
                </div>

            </div>
        </main>

    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>