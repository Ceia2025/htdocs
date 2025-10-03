<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth(); // obliga a tener sesión iniciada

$user = $_SESSION['user']; // usuario logueado
$nombre = $user['nombre'];
$rol = $user['rol'];

// Incluir layout
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
                <h1 class="text-3xl font-bold tracking-tight text-white">Inventario</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- BOTÓN NUEVO -->
                <div class="mb-4 flex justify-end">
                    <a href="index.php?action=inventario_create"
                        class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                        ➕ Nuevo Registro
                    </a>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="w-full border-collapse bg-gray-800 text-left text-sm text-gray-300">
                        <thead class="bg-gray-700 text-gray-200">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Nivel Educativo</th>
                                <th class="px-6 py-3">Individualización</th>
                                <th class="px-6 py-3">Categorización</th>
                                <th class="px-6 py-3">Cantidad</th>
                                <th class="px-6 py-3">Estado</th>
                                <th class="px-6 py-3">Lugar Físico</th>
                                <th class="px-6 py-3">Procedencia</th>
                                <th class="px-6 py-3">Código General</th>
                                <th class="px-6 py-3">Código Específico</th>
                                <th class="px-6 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                                        <td class="px-6 py-3"><?= $item['id'] ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($item['nivel_educativo']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($item['individualizacion']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($item['categorizacion']) ?></td>
                                        <td class="px-6 py-3"><?= $item['cantidad'] ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($item['estado']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($item['lugar']) ?></td>
                                        <td class="px-6 py-3">
                                            <?= htmlspecialchars($item['procedencia_tipo'] . " - " . $item['donador_fondo'] . " - " . $item['fecha_adquisicion']) ?>
                                        </td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($item['codigo_general']) ?></td>
                                        <td class="px-6 py-3"><?= $item['codigo_especifico'] ?></td>
                                        <td class="px-6 py-3 text-center space-x-2">
                                            <a href="index.php?action=inventario_edit&id=<?= $item['id'] ?>"
                                                class="text-indigo-400 hover:text-indigo-300 font-medium">
                                                ✏️ Editar
                                            </a>
                                            <a href="index.php?action=inventario_delete&id=<?= $item['id'] ?>"
                                                onclick="return confirm('¿Seguro que deseas eliminar este registro?');"
                                                class="text-red-400 hover:text-red-300 font-medium">
                                                🗑️ Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="px-6 py-4 text-center text-gray-400">
                                        No hay registros en el inventario.
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