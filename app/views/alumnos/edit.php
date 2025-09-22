<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Alumno</title>
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
                <h1 class="text-3xl font-bold tracking-tight text-white">Editar Alumno</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FORMULARIO -->
                <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
                    <form action="index.php?action=alumno_update&id=<?= $alumno['id'] ?>" method="POST"
                        class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- RUN -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">RUN</label>
                            <input type="text" name="run" value="<?= htmlspecialchars($alumno['run']) ?>" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 
                                focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Codigo verificador -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Código Verificador</label>
                            <input type="text" name="codver" value="<?= htmlspecialchars($alumno['codver']) ?>" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 
                                focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Nombre</label>
                            <input type="text" name="nombre" value="<?= htmlspecialchars($alumno['nombre']) ?>" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Apellido Paterno -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Apellido Paterno</label>
                            <input type="text" name="apepat" value="<?= htmlspecialchars($alumno['apepat']) ?>" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Apellido Materno -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Apellido Materno</label>
                            <input type="text" name="apemat" value="<?= htmlspecialchars($alumno['apemat']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Fecha de Nacimiento</label>
                            <input type="date" name="fechanac" value="<?= htmlspecialchars($alumno['fechanac']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>


                        <!-- Número de Hijos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Número de Hijos</label>
                            <input type="number" name="numerohijos" min="0"
                                value="<?= htmlspecialchars($alumno['numerohijos']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Teléfono</label>
                            <input type="text" name="telefono" value="<?= htmlspecialchars($alumno['telefono']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($alumno['email']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Sexo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Sexo</label>
                            <select name="sexo" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                                <option value="F" <?= $alumno['sexo'] === 'F' ? 'selected' : '' ?>>Femenino</option>
                                <option value="M" <?= $alumno['sexo'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                            </select>
                        </div>

                        <!-- Nacionalidad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Nacionalidad</label>
                            <input type="text" name="nacionalidades" value="<?= htmlspecialchars($alumno['nacionalidades']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Región -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Región</label>
                            <input type="text" name="region" value="<?= htmlspecialchars($alumno['region']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Ciudad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Ciudad</label>
                            <input type="text" name="ciudad" value="<?= htmlspecialchars($alumno['ciudad']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Fecha Retiro</label>
                            <input type="date" name="deleted_at" value="<?= htmlspecialchars($alumno['deleted_at']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Etnia -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-200">Etnia</label>
                            <select name="cod_etnia"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                                <?php
                                $etnias = [
                                    "Ninguna","Mapuche","Aymara","Rapa Nui","Lickan Antai (Atacameños)",
                                    "Quechua","Colla","Diaguita","Chango","Kawésqar","Yagán","Selk nam"
                                ];
                                foreach ($etnias as $etnia): ?>
                                    <option value="<?= $etnia ?>" <?= $alumno['cod_etnia'] === $etnia ? 'selected' : '' ?>>
                                        <?= $etnia ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- BOTÓN -->
                        <div class="md:col-span-2">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg 
                                transition duration-200 ease-in-out">
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- VOLVER -->
                <div class="mt-8 flex items-center justify-center">
                    <a href="index.php?action=alumno_index"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        ⬅️ Volver
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
