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

<html class="h-full bg-gray-900">

<body class="h-full">
    <div class="min-h-full">
        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Editar Relación Curso - Asignatura</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FORMULARIO -->
                <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
                    <form action="index.php?action=curso_asignaturas_update&id=<?= $cursoAsignatura['id'] ?>" method="post" class="space-y-6">

                        <!-- Selección de Curso -->
                        <div>
                            <label for="curso_id" class="block text-sm font-medium text-gray-200">Curso</label>
                            <select name="curso_id" id="curso_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= $curso['id'] ?>" <?= $curso['id'] == $cursoAsignatura['curso_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($curso['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Selección de Asignatura -->
                        <div>
                            <label for="asignatura_id" class="block text-sm font-medium text-gray-200">Asignatura</label>
                            <select name="asignatura_id" id="asignatura_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <?php foreach ($asignaturas as $asignatura): ?>
                                    <option value="<?= $asignatura['id'] ?>" <?= $asignatura['id'] == $cursoAsignatura['asignatura_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($asignatura['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex space-x-4">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                                Actualizar
                            </button>
                            <a href="index.php?action=curso_asignaturas"
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
<?php include __DIR__ . "/../layout/footer.php"; ?>