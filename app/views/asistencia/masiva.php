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




                <h2 class="text-2xl font-bold mb-6">Tomar Asistencia</h2>

                <form method="POST" action="index.php?action=guardar_asistencia_masiva">

                    <input type="hidden" name="curso_id" value="<?= $_GET['curso_id'] ?>">
                    <input type="hidden" name="anio_id" value="<?= $_GET['anio_id'] ?>">

                    <div class="mb-6">
                        <label class="block mb-2 font-semibold">
                            Fecha
                        </label>

                        <input type="date" name="fecha" value="<?= date('Y-m-d') ?>"
                            class="bg-gray-700 text-white px-4 py-2 rounded">
                    </div>

                    <div class="mb-4">
                        <button type="button" onclick="marcarTodos()" class="bg-blue-600 px-4 py-2 rounded text-white">
                            Marcar todos presentes
                        </button>
                    </div>

                    <table class="w-full bg-gray-800 text-white rounded overflow-hidden">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="p-3 text-left">Alumno</th>
                                <th class="p-3 text-center">Presente</th>
                                <th class="p-3 text-center">Ausente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $alumno): ?>
                                <tr class="border-b border-gray-600">
                                    <td class="p-3">
                                        <?= $alumno['nombre'] ?>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="asistencia[<?= $alumno['id'] ?>]" value="1"
                                            class="presente" checked>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="asistencia[<?= $alumno['id'] ?>]" value="0">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="mt-6">
                        <button type="submit" class="bg-green-600 px-6 py-3 rounded text-white font-bold">
                            Guardar Asistencia
                        </button>
                    </div>

                </form>

                <script>
                    function marcarTodos() {
                        document.querySelectorAll('.presente').forEach(radio => {
                            radio.checked = true;
                        });
                    }
                </script>

            </div>
        </main>


</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>