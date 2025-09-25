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
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Editar Curso</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FORMULARIO -->
                <div class="flex items-center justify-center">
                    <div class="bg-gray-700 p-6 rounded-2xl shadow-lg w-full max-w-md">
                        <h2 class="text-2xl font-bold text-white mb-6 text-center">Actualizar Curso</h2>

                        <form method="POST" action="index.php?action=curso_update&id=<?= $curso['id'] ?>" class="space-y-4">

                            <!-- Campo Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-200">Nombre del Curso</label>
                                <input type="text" name="nombre" id="nombre" 
                                    value="<?= htmlspecialchars($curso['nombre']) ?>" required
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 
                                    focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Botón -->
                            <button type="submit" 
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg 
                                transition duration-200 ease-in-out">
                                Actualizar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- VOLVER -->
                <div class="mt-8 flex items-center justify-center">
                    <a href="index.php?action=cursos"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        ⬅️ Volver
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>
