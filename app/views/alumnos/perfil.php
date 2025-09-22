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
                <p><span class="font-semibold">RUN:</span> <?= htmlspecialchars($alumno['run'] . '-' . $alumno['codver']) ?></p>
                <p><span class="font-semibold">Mayor Edad:</span> <?= $alumno['mayoredad'] ?></p>
                <p><span class="font-semibold">Fecha de Nacimiento:</span> <?= htmlspecialchars($alumno['fechanac']) ?></p>
                <p><span class="font-semibold">Sexo:</span> <?= htmlspecialchars($alumno['sexo']) ?></p>
                <p><span class="font-semibold">Email:</span> <?= htmlspecialchars($alumno['email']) ?></p>
                <p><span class="font-semibold">Teléfono:</span> <?= htmlspecialchars($alumno['telefono']) ?></p>
            </div>

            <!-- Columna derecha -->
            <div class="space-y-3 bg-gray-700 p-6 rounded-xl shadow-inner">
                <p><span class="font-semibold">Nacionalidad:</span> <?= htmlspecialchars($alumno['nacionalidades']) ?></p>
                <p><span class="font-semibold">Región:</span> <?= htmlspecialchars($alumno['region']) ?></p>
                <p><span class="font-semibold">Ciudad:</span> <?= htmlspecialchars($alumno['ciudad']) ?></p>
                <p><span class="font-semibold">Etnia:</span> <?= htmlspecialchars($alumno['cod_etnia']) ?></p>
                <p><span class="font-semibold">Incorporación:</span> <?= htmlspecialchars($alumno['created_at']) ?></p>
                <p><span class="font-semibold">Fecha Retiro:</span> <?= htmlspecialchars($alumno['deleted_at']) ?></p>
            </div>

        </div>

        <!-- Botón de regreso -->
        <div class="text-center">
            <a href="index.php?action=alumnos"
                class="inline-block mt-4 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-200">
                ← Volver a la lista de alumnos
            </a>
        </div>

    </div>

</body>
</html>
