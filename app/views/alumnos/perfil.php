<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Peril Alumno</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col items-center p-6">

    <!-- Contenedor principal -->
    <div class="w-full max-w-5xl bg-gray-800 rounded-2xl shadow-lg p-8 mt-6">

        <!-- Header -->
        <h1 class="text-4xl font-bold mb-8 text-center">
            Perfil de <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apepat'] . ' ' . $alumno['apemat']) ?>
        </h1>

        <!-- Información del Alumno -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">

            <!-- Columna izquierda -->
            <div class="space-y-3 bg-gray-700 p-6 rounded-xl shadow-inner">
                <p><span class="font-semibold">RUN:</span>
                    <?= htmlspecialchars($alumno['run'] . '-' . $alumno['codver']) ?></p>
                <p><span class="font-semibold">Mayor Edad:</span> <?= $alumno['mayoredad'] ?></p>
                <p><span class="font-semibold">Fecha de Nacimiento:</span> <?= htmlspecialchars($alumno['fechanac']) ?>
                </p>
                <p><span class="font-semibold">Sexo:</span> <?= htmlspecialchars($alumno['sexo']) ?></p>
                <p><span class="font-semibold">Email:</span> <?= htmlspecialchars($alumno['email']) ?></p>
                <p><span class="font-semibold">Teléfono:</span> <?= htmlspecialchars($alumno['telefono']) ?></p>
            </div>

            <!-- Columna derecha -->
            <div class="space-y-3 bg-gray-700 p-6 rounded-xl shadow-inner">
                <p><span class="font-semibold">Nacionalidad:</span> <?= htmlspecialchars($alumno['nacionalidades']) ?>
                </p>
                <p><span class="font-semibold">Región:</span> <?= htmlspecialchars($alumno['region']) ?></p>
                <p><span class="font-semibold">Ciudad:</span> <?= htmlspecialchars($alumno['ciudad']) ?></p>
                <p><span class="font-semibold">Etnia:</span> <?= htmlspecialchars($alumno['cod_etnia']) ?></p>
                <p><span class="font-semibold">Incorporación:</span> <?= htmlspecialchars($alumno['created_at']) ?></p>
                <p><span class="font-semibold">Fecha Retiro:</span> <?= htmlspecialchars($alumno['deleted_at']) ?></p>
            </div>

        </div>

        <!-- Botón de regreso -->
        <div class=" flex justify-center mt-6 gap-4">

            <div class="text-center">
                <a href="index.php?action=alumnos"
                    class="inline-block mt-4 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-200">
                    Volver a la lista de alumnos
                </a>
            </div>
            <div class="text-center">
                <a href="index.php?action=alumno_edit&id=<?= $alumno['id'] ?>"
                    class="inline-block mt-4 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-200">
                    Editar
                </a>
            </div>
            <div class="text-center">
                <a href="index.php?action=alumnos"
                    class="inline-block mt-4 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-200">
                    Descarga información
                </a>
            </div>
        </div>

    </div>


    <div class="w-full max-w-5xl bg-gray-800 rounded-2xl shadow-lg p-8 mt-6">

        <!-- Header -->
        <div class="px-4 sm:px-0 mb-6">
            <h1 class="text-4xl font-bold text-white mb-2 text-center">
                Perfil de <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apepat'] . ' ' . $alumno['apemat']) ?>
            </h1>
            <p class="text-center text-gray-400">Información detallada del alumno</p>
        </div>

        <!-- Detalles del Alumno -->
        <div class="mt-6 border-t border-white/10">
            <dl class="divide-y divide-white/10">

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">RUN</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['run'] . '-' . $alumno['codver']) ?>
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Mayor Edad</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0"><?= $alumno['mayoredad'] ?></dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Fecha de Nacimiento</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['fechanac']) ?>
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Sexo</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['sexo']) ?>
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Email</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['email']) ?>
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Teléfono</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['telefono']) ?>
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Nacionalidad</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['nacionalidades']) ?>
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Región</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['region']) ?>
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Ciudad</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['ciudad']) ?>
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Etnia</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['cod_etnia']) ?>
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Incorporación</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['created_at']) ?>
                    </dd>
                </div>

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Fecha Retiro</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        <?= htmlspecialchars($alumno['deleted_at']) ?>
                    </dd>
                </div>

            </dl>

        </div>


        <!-- Botón de regreso -->
        <div class="flex justify-center mt-6 gap-4">
            <a href="index.php?action=alumnos"
                class="inline-block px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-200">
                Volver a la lista de alumnos
            </a>

            <a href="index.php?action=alumnos"
                class="inline-block px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-200">
                Descargar información
            </a>
        </div>


    </div>


</body>

</html>