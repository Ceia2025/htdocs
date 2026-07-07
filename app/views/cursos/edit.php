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

<header class="page-header">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Editar Curso</h1>
    </div>
</header>

<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <div class="flex items-center justify-center">
            <div class="panel p-6 rounded-2xl shadow-lg w-full max-w-md">
                <h2 class="text-2xl font-bold text-strong font-display mb-6 text-center">Actualizar Curso</h2>

                <form method="POST" action="index.php?action=curso_update&id=<?= $curso['id'] ?>" class="space-y-4">

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-soft mb-2">Nombre del Curso</label>
                        <input type="text" name="nombre" id="nombre"
                            value="<?= htmlspecialchars($curso['nombre']) ?>" required
                            class="input-field w-full rounded-lg px-4 py-2">
                    </div>

                    <button type="submit" class="btn-primary w-full font-semibold py-2 rounded-lg transition">
                        Actualizar
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=cursos" class="btn-secondary inline-block rounded-md px-4 py-2 text-sm font-semibold shadow">
                ⬅️ Volver
            </a>
        </div>
    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>