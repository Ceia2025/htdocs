<?php
$edad = null;
if (!empty($alumno['fechanac'])) {
    $fechaNac = new DateTime($alumno['fechanac']);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNac)->y;
}
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


<header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Alumnos</h1>
            </div>
        </header>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col items-center p-6">

    <!-- Contenedor principal -->
    <div class="max-w-5xl w-full md:w-4/5 lg:w-3/4 bg-gray-800 rounded-2xl shadow-lg p-8 mt-6 mx-auto text-white">

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
                <p><span class="font-semibold">Fecha de Nacimiento:</span>
                    <?php if (!empty($alumno['fechanac'])): ?>
                        <?= (new DateTime($alumno['fechanac']))->format('d/m/Y') ?>
                    <?php else: ?>
                        No registrada
                    <?php endif; ?>
                </p>
                <p><span class="font-semibold">Edad:</span> <?= $edad !== null ? $edad . " años" : "No registrada" ?>
                </p>
                <p><span class="font-semibold">Mayor Edad:</span> <?= $alumno['mayoredad'] ?></p>
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
                <p><span class="font-semibold">Incorporación:</span>
                    <?php if (!empty($alumno['created_at'])): ?>
                        <?= (new DateTime($alumno['created_at']))->format('d/m/Y') ?>
                    <?php else: ?>
                        No registrada
                    <?php endif; ?>
                </p>
                <p><span class="font-semibold">Fecha Retiro:</span>
                    <?php if (!empty($alumno['deleted_at'])): ?>
                        <?= (new DateTime($alumno['deleted_at']))->format('d/m/Y') ?>
                    <?php else: ?>
                        No registrada
                    <?php endif; ?>
                </p>
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

</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>