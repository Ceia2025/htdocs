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

<body class="h-full">
    <div class="min-h-full">

        <header class="relative bg-gray-800">
            <div class="mx-auto max-w-5xl px-4 py-6">
                <h1 class="text-3xl font-bold text-white">
                    Tomar Asistencia
                </h1>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-3xl px-4 py-6">

                <h2 class="text-2xl font-bold mb-6">
                    Tomar Asistencia
                </h2>

                <h3 class="text-lg text-gray-300 mb-4">
                    Curso: <?= $curso['nombre'] ?? '' ?>
                </h3>

                <form method="POST" action="index.php?action=guardar_asistencia_masiva">

                    <input type="hidden" name="curso_id" value="<?= $_GET['curso_id'] ?>">
                    <input type="hidden" name="anio_id" value="<?= $_GET['anio_id'] ?>">

                    <div class="mb-6">
                        <label class="block mb-2 font-semibold">
                            Fecha
                        </label>

                        <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>"
                            class="bg-gray-700 text-white px-4 py-2 rounded">
                    </div>

                    <div class="mb-4">
                        <button type="button" onclick="marcarTodos()" class="bg-blue-600 px-4 py-2 rounded text-white">
                            Marcar todos presentes
                        </button>
                    </div>

                    <?php if (empty($alumnos)): ?>

                        <div class="bg-yellow-500 text-black p-4 rounded">
                            No hay alumnos matriculados en este curso.
                        </div>

                    <?php else: ?>

                        <table class="w-full bg-gray-800 text-white rounded overflow-hidden">

                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="p-3 text-left">Alumno</th>
                                    <th class="p-3 text-center">Presente</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($alumnos as $alumno): ?>

                                    <tr class="border-t border-gray-700">

                                        <td class="p-3">
                                            <?= $alumno['apepat'] ?>         <?= $alumno['apemat'] ?>, <?= $alumno['nombre'] ?>
                                        </td>

                                        <td class="p-3 text-center">

                                            <input type="checkbox" class="presente" name="presentes[]"
                                                value="<?= $alumno['matricula_id'] ?>" checked>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>

                    <?php endif; ?>

                    <div class="mt-6">
                        <button type="submit" class="bg-green-600 px-6 py-3 rounded text-white font-bold">
                            Guardar Asistencia
                        </button>
                    </div>

                </form>

                <script>
                    function marcarTodos() {

                        document.querySelectorAll('.presente').forEach(el => {
                            el.checked = true
                        })

                    }
                </script>

            </div>
        </main>

    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>