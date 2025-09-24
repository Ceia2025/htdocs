<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo Contacto de Emergencia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="h-full bg-gray-900">

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
                                <a href="index.php?action=alum_emergencia"
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
        <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">➕ Nuevo Contacto de Emergencia</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FORMULARIO -->
                <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
                    <form method="POST" action="index.php?action=alum_emergencia_store" class="space-y-6">

                        <!-- Alumno -->
                        <div>
                            <label for="alumno_id" class="block text-sm font-medium text-gray-200">Alumno</label>
                            <select name="alumno_id" id="alumno_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Selecciona un alumno --</option>
                                <?php foreach ($alumnos as $a): ?>
                                    <option value="<?= $a['id'] ?>">
                                        <?= htmlspecialchars($a['nombre'] . " " . $a['apepat'] . " " . $a['apemat'] . " " . $a['run'] . "-" . $a['codver']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Nombre del contacto -->
                        <div>
                            <label for="nombre_contacto" class="block text-sm font-medium text-gray-200">Nombre del contacto</label>
                            <input type="text" name="nombre_contacto" id="nombre_contacto" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-200">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-200">Dirección</label>
                            <input type="text" name="direccion" id="direccion"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Relación -->
                        <div>
                            <label for="relacion" class="block text-sm font-medium text-gray-200">Relación</label>
                            <select name="relacion" id="relacion" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Selecciona --</option>
                                <option value="Madre">Madre</option>
                                <option value="Padre">Padre</option>
                                <option value="Hermana/Hermano">Hermana/Hermano</option>
                                <option value="Tutor Legal">Tutor Legal</option>
                                <option value="Representante">Representante</option>
                                <option value="Apoderado">Apoderado</option>
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex space-x-4">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                                Guardar
                            </button>
                            <a href="index.php?action=alum_emergencia"
                                class="w-full text-center inline-block rounded-lg bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 transition duration-200 ease-in-out">
                                Cancelar
                            </a>
                        </div>

                    </form>
                </div>

            </div>
        </main>

    </div>

</body>
</html>
