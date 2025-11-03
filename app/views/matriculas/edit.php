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

    <!-- HEADER -->
    <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Editar Matrícula</h1>
        </div>
    </header>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex justify-center">

            <div class="bg-gray-700 p-8 rounded-2xl shadow-lg w-full max-w-lg">
                <h2 class="text-2xl font-bold text-white mb-6 text-center">Actualizar Matrícula</h2>

                <form method="post" action="index.php?action=matricula_update&id=<?= $matricula['id'] ?>" class="space-y-6">

                    <!-- Alumno -->
                    <div>
                        <label for="alumno_id" class="block text-sm font-medium text-gray-200">Alumno</label>
                        <select name="alumno_id" id="alumno_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <?php foreach ($alumnos as $a): ?>
                                <option value="<?= $a['id'] ?>" <?= ($a['id'] == $matricula['alumno_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($a['nombre'] . " " . $a['apepat'] . " " . $a['apemat']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Curso -->
                    <div>
                        <label for="curso_id" class="block text-sm font-medium text-gray-200">Curso</label>
                        <select name="curso_id" id="curso_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <?php foreach ($cursos as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($c['id'] == $matricula['curso_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Año -->
                    <div>
                        <label for="anio_id" class="block text-sm font-medium text-gray-200">Año Académico</label>
                        <select name="anio_id" id="anio_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <?php foreach ($anios as $an): ?>
                                <option value="<?= $an['id'] ?>" <?= ($an['id'] == $matricula['anio_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($an['anio']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                        Actualizar
                    </button>
                </form>
            </div>
        </div>

        <!-- VOLVER -->
        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=matriculas"
               class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                ⬅ Volver
            </a>
        </div>
    </main>
</div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>
