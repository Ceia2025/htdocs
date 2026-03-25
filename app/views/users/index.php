<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Colores por rol
$coloresRol = [
    'administrador' => 'bg-purple-900/40 border-purple-500 text-purple-300',
    'soporte' => 'bg-blue-900/40 border-blue-500 text-blue-300',
    'direccion' => 'bg-indigo-900/40 border-indigo-500 text-indigo-300',
    'administrativo' => 'bg-cyan-900/40 border-cyan-500 text-cyan-300',
    'inspector general' => 'bg-orange-900/40 border-orange-500 text-orange-300',
    'docente' => 'bg-green-900/40 border-green-500 text-green-300',
    'asistente social' => 'bg-teal-900/40 border-teal-500 text-teal-300',
    'registro de anotaciones' => 'bg-yellow-900/40 border-yellow-500 text-yellow-300',
    'registro asistencias' => 'bg-lime-900/40 border-lime-500 text-lime-300',
    'registro atrasos y salidas' => 'bg-rose-900/40 border-rose-500 text-rose-300',
];
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="bg-gray-800 border-b border-white/10">
            <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-purple-400 mb-0.5">Sistema</p>
                    <h1 class="text-2xl font-bold text-white">Gestión de Usuarios</h1>
                    <p class="text-sm text-gray-400 mt-0.5">
                        <span class="text-white font-semibold"><?= count($users) ?></span> usuarios registrados
                    </p>
                </div>
                <?php if (AuthController::puede('user_create')): ?>
                    <a href="index.php?action=user_create" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 
                           text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow transition">
                        ➕ Nuevo Usuario
                    </a>
                <?php endif ?>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

            <?php if (empty($users)): ?>
                <div class="text-center py-20 text-gray-500">
                    <p class="text-5xl mb-4">👥</p>
                    <p class="text-lg font-medium">No hay usuarios registrados.</p>
                </div>
            <?php else: ?>

                <!-- Resumen por rol -->
                <?php
                $conteoRoles = [];
                foreach ($users as $u) {
                    $r = strtolower($u['rol'] ?? 'sin rol');
                    $conteoRoles[$r] = ($conteoRoles[$r] ?? 0) + 1;
                }
                arsort($conteoRoles);
                ?>
                <div class="flex flex-wrap gap-2 mb-6">
                    <?php foreach ($conteoRoles as $r => $cnt):
                        $cls = $coloresRol[$r] ?? 'bg-gray-800 border-gray-600 text-gray-300';
                        ?>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border text-xs font-semibold <?= $cls ?>">
                            <?= htmlspecialchars(ucfirst($r)) ?>
                            <span class="opacity-70">(<?= $cnt ?>)</span>
                        </span>
                    <?php endforeach ?>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-950/50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Usuario</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Contacto</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    RUN</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Rol</th>
                                <th
                                    class="px-5 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            <?php foreach ($users as $u):
                                $iniciales = strtoupper(
                                    substr($u['nombre'] ?? '', 0, 1) .
                                    substr($u['ape_paterno'] ?? '', 0, 1)
                                );
                                $rolLower = strtolower($u['rol'] ?? '');
                                $badgeRol = $coloresRol[$rolLower] ?? 'bg-gray-800 border-gray-600 text-gray-300';
                                ?>
                                <tr class="hover:bg-gray-800/50 transition group">

                                    <!-- Usuario -->
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <!-- Avatar -->
                                            <div class="w-9 h-9 rounded-xl bg-indigo-600/20 border border-indigo-600/30 
                                                    flex items-center justify-center flex-shrink-0 
                                                    text-indigo-400 text-xs font-bold">
                                                <?= $iniciales ?>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-white capitalize">
                                                    <?= htmlspecialchars($u['nombre'] . ' ' . $u['ape_paterno'] . ' ' . $u['ape_materno']) ?>
                                                </p>
                                                <p class="text-xs text-gray-500 font-mono">
                                                    @<?= htmlspecialchars($u['username']) ?></p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Contacto -->
                                    <td class="px-5 py-4">
                                        <p class="text-sm text-gray-300"><?= htmlspecialchars($u['email']) ?></p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            <?= !empty($u['numero_telefonico']) ? '📞 ' . htmlspecialchars($u['numero_telefonico']) : '<span class="italic">Sin teléfono</span>' ?>
                                        </p>
                                    </td>

                                    <!-- RUN -->
                                    <td class="px-5 py-4">
                                        <span class="text-sm text-gray-300 font-mono">
                                            <?= !empty($u['run']) ? htmlspecialchars($u['run']) : '<span class="text-gray-600 italic text-xs">No registrado</span>' ?>
                                        </span>
                                    </td>

                                    <!-- Rol -->
                                    <td class="px-5 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg border text-xs font-semibold <?= $badgeRol ?>">
                                            <?= htmlspecialchars(ucfirst($u['rol'] ?? '—')) ?>
                                        </span>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <?php if (AuthController::puede('user_edit')): ?>
                                                <a href="index.php?action=user_edit&id=<?= $u['id'] ?>"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                                                       bg-indigo-700/30 hover:bg-indigo-700/60 border border-indigo-600/40
                                                       text-indigo-300 hover:text-indigo-200 text-xs font-semibold transition">
                                                    ✏️ Editar
                                                </a>
                                            <?php endif ?>
                                            <?php if (AuthController::puede('user_delete')): ?>
                                                <a href="index.php?action=user_delete&id=<?= $u['id'] ?>"
                                                    onclick="return confirm('¿Seguro que deseas eliminar a <?= htmlspecialchars(addslashes($u['nombre'])) ?>?')"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                                                       bg-red-900/30 hover:bg-red-900/60 border border-red-700/40
                                                       text-red-400 hover:text-red-300 text-xs font-semibold transition">
                                                    🗑️ Eliminar
                                                </a>
                                            <?php endif ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>

            <?php endif ?>

            <!-- VOLVER -->
            <div class="mt-8 flex justify-center">
                <a href="index.php?action=dashboard"
                    class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                    ⬅️ Dashboard
                </a>
            </div>

        </main>
    </div>
</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>