<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Alumnos</title>
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
                        <!-- Logo -->
                        <div class="shrink-0">
                            <img src="../img/logo.jpg" alt="Logo" class="size-12 rounded-full" />
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="index.php?action=dashboard"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Dashboard
                                </a>
                                <a href="index.php?action=alumno_index"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Alumnos
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
                <h1 class="text-3xl font-bold tracking-tight text-white">Listado de Alumnos</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- BOTÓN CREAR -->
                <div class="mb-6 flex justify-end">
                    <a href="index.php?action=alumno_create"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition duration-200">
                        ➕ Nuevo Alumno
                    </a>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-950/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">RUN</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Mayor Edad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Nombre Completo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Fecha Nac.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Sexo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Teléfono</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Nacionalidad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Región</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Ciudad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Etnia</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Incorporación</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Fecha Retiro</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-500/30 divide-y divide-gray-600">
                            <?php if (!empty($alumnos)): ?>
                                <?php foreach ($alumnos as $alumno): ?>
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['run'] . '-' . $alumno['codver']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['mayoredad']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100 capitalize">
                                            <?= htmlspecialchars($alumno['nombre'] . " " . $alumno['apepat'] . " " . $alumno['apemat']) ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['fechanac']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['sexo']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['email']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['telefono']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['nacionalidades']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['region']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['ciudad']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['cod_etnia']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['created_at']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100"><?= htmlspecialchars($alumno['deleted_at']) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-100 space-x-3">
                                            <a href="index.php?action=alumno_edit&id=<?= $alumno['id'] ?>"
                                                class="text-indigo-400 hover:text-indigo-300 font-medium">Editar</a>
                                            <a href="index.php?action=alumno_delete&id=<?= $alumno['id'] ?>"
                                                onclick="return confirm('¿Eliminar este alumno?')"
                                                class="text-red-400 hover:text-red-300 font-medium">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="12" class="px-6 py-4 text-center text-sm text-gray-300">
                                        No hay alumnos registrados
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- VOLVER -->
                <div class="mt-8 flex items-center justify-center">
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
