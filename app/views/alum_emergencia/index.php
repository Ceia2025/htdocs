<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contactos de Emergencia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<html class="h-full bg-gray-900">

<body class="h-full">
    <div class="min-h-full">

        <!-- NAVBAR -->
        <nav class="bg-gray-800/50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <img src="../img/logo.jpg" alt="Logo" class="size-12 rounded-full" />
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="index.php?action=dashboard"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Dashboard
                                </a>
                                <a href="index.php?action=alum_emergencia_index"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-white bg-gray-700">
                                    Contactos Emergencia
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">📋 Contactos de Emergencia</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- BOTÓN NUEVO CONTACTO -->
                <div class="mb-4 flex justify-end">
                    <a href="index.php?action=alum_emergencia_create"
                        class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                        ➕ Nuevo contacto
                    </a>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="w-full border-collapse bg-gray-800 text-left text-sm text-gray-300">
                        <thead class="bg-gray-700 text-gray-200">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Alumno</th>
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
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['id']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['alumno_nombre'] . ' ' . $e['ape_paterno'] . ' ' . $e['ape_materno']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['nombre_contacto']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['telefono']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['direccion']) ?></td>
                                        <td class="px-6 py-3"><?= htmlspecialchars($e['relacion']) ?></td>
                                        <td class="px-6 py-3 text-center space-x-2">
                                            <a href="index.php?action=alum_emergencia_edit&id=<?= $e['id'] ?>"
                                                class="text-indigo-400 hover:text-indigo-300 font-medium">
                                                ✏️ Editar
                                            </a>
                                            <a href="index.php?action=alum_emergencia_delete&id=<?= $e['id'] ?>"
                                                onclick="return confirm('¿Seguro que deseas eliminar este contacto?');"
                                                class="text-red-400 hover:text-indigo-300 font-medium">
                                                🗑️ Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                        No hay contactos registrados.
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

</html>
