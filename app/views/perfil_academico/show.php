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

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="bg-gray-800 border-b border-gray-700 shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white">
                    Perfil Académico:
                    <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                </h1>
                <p class="text-gray-400 mt-2">
                    Curso: <strong><?= htmlspecialchars($curso['nombre']) ?></strong> |
                    Año: <strong><?= htmlspecialchars($anio['anio']) ?></strong> |
                    Fecha Matrícula: <?= htmlspecialchars($matricula['fecha_matricula']) ?>
                </p>
            </div>
        </header>

        <!-- MAIN -->
        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- Navegación -->
            <div class="flex space-x-4 mb-6">
                <a href="index.php?action=alumno_profile&id=<?= $alumno['id'] ?>"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">Perfil Alumno</a>
                <a href="index.php?action=matriculas"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">Volver</a>
            </div>

            <!-- Secciones -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Notas -->
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow">
                    <?php
                    // Usamos include para acceder a las variables de show.php
                    include __DIR__ . "/component/notas.php";
                    ?>
                </div>

                <!-- Asistencia -->
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow">
                    <?php
                    include __DIR__ . "/component/asistencia.php";
                    ?>
                </div>

                <!-- Anotaciones -->
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow">
                    <?php
                    include __DIR__ . "/component/anotaciones.php";
                    ?>
                </div>

                <!-- Atrasos -->
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow">
                    <?php
                    include __DIR__ . "/component/atrasos.php";
                    ?>
                </div>
            </div>

        </main>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>