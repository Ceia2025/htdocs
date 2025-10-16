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
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Contactos de Emergencia</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FORMULARIO DE BÚSQUEDA -->
                <form method="get" action="index.php" class="mb-6 flex items-center justify-between">
                    <input type="hidden" name="action" value="alum_emergencia">

                    <!-- Campo de búsqueda -->
                    <input type="text" name="q" placeholder="Buscar alumno por nombre o RUN..."
                        value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="w-full max-w-lg rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 
               focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                    <!-- Botones de acción -->
                    <div class="flex items-center space-x-3 ml-3">
                        <!-- Buscar -->
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition">
                            🔍 Buscar
                        </button>

                        <!-- Limpiar -->
                        <a href="index.php?action=alum_emergencia"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow transition">
                            ✖ Limpiar
                        </a>
                    </div>
                </form>


                <!-- BOTÓN NUEVO CONTACTO -->
                <div class="mb-4 flex justify-end">
                    <a href="index.php?action=alum_emergencia_create"
                        class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                        Nuevo contacto
                    </a>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="w-full border-collapse bg-gray-800 text-left text-sm text-gray-300">
                        <thead class="bg-gray-700 text-gray-200">
                            <tr>
                                <th class="px-6 py-3">Alumno</th>
                                <th class="px-6 py-3">RUT</th>
                                <th class="px-6 py-3">Nombre Contacto</th>
                                <th class="px-6 py-3">Teléfono</th>
                                <th class="px-6 py-3">Dirección</th>
                                <th class="px-6 py-3">Relación</th>
                                <th class="px-6 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($emergencias)): ?>
                                <?php foreach ($emergencias as $e): ?>
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                                        <td class="px-6 py-3">
                                            <?= htmlspecialchars($e['alumno_nombre'] . ' ' . $e['ape_paterno'] . ' ' . $e['ape_materno']) ?>
                                        </td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['rut'] ?? '-') ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['nombre_contacto']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['telefono']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['direccion']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['relacion']) ?></td>
                                        <td class="px-6 py-3 text-center space-x-2">
                                            <a href="index.php?action=alum_emergencia_edit&id=<?= $e['id'] ?>"
                                                class="text-indigo-400 hover:text-indigo-300 font-medium">✏️ Editar</a>
                                            <a href="index.php?action=alum_emergencia_delete&id=<?= $e['id'] ?>"
                                                onclick="return confirm('¿Seguro que deseas eliminar este contacto?');"
                                                class="text-red-400 hover:text-red-300 font-medium">🗑️ Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-400">
                                        No hay contactos registrados
                                        <?= !empty($_GET['q']) ? 'para la búsqueda "' . htmlspecialchars($_GET['q']) . '"' : '' ?>.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- VOLVER -->
                <div class="mt-6 flex justify-center">
                    <a href="index.php?action=dashboard"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        ⬅️ Volver al Dashboard
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>