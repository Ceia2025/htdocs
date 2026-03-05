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

<body class="h-full">
    <div class="min-h-full">
        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Tomar</h1>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">





                <div class="max-w-6xl mx-auto mt-8 bg-gray-800 p-6 rounded-xl shadow-lg">
                    <h2 class="text-2xl font-bold text-white mb-6">
                        📚 Seleccionar Curso
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($cursos as $curso): ?>

                            <div class="bg-gray-700 p-5 rounded-lg shadow hover:bg-gray-600 transition">
                                <h3 class="text-lg font-semibold text-white mb-3">
                                    <?= $curso['nombre'] ?>
                                </h3>

                                <div class="flex justify-between">
                                    <a href="index.php?action=form_asistencia_masiva&curso_id=<?= $curso['id'] ?>&anio_id=1"
                                        class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded text-white text-sm">
                                        Tomar Asistencia
                                    </a>

                                    <a href="index.php?action=resumen_curso&curso_id=<?= $curso['id'] ?>&anio_id=1"
                                        class="bg-green-600 hover:bg-green-700 px-3 py-2 rounded text-white text-sm">
                                        Ver Resumen
                                    </a>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>

                </div>


            </div>
        </main>


</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>