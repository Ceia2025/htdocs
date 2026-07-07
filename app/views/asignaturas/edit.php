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
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Editar Asignatura</h1>
    </div>
</header>

<main>
    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

        <div class="panel p-8 rounded-2xl shadow-lg">
            <form method="POST" action="index.php?action=asignatura_update&id=<?= $asignatura['id'] ?>" class="space-y-6">

                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Nombre de la Abreviatura</label>
                    <input type="text" name="abreviatura" value="<?= htmlspecialchars($asignatura['abreviatura']) ?>" required
                        class="input-field w-full rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Nombre de la Asignatura</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($asignatura['nombre']) ?>" required
                        class="input-field w-full rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Descripción</label>
                    <textarea name="descp" rows="4" required
                        class="input-field w-full rounded-lg px-3 py-2 resize-none"><?= htmlspecialchars($asignatura['descp']) ?></textarea>
                </div>

                <button type="submit" class="btn-primary w-full font-semibold py-2 rounded-lg transition">
                    Actualizar
                </button>
            </form>
        </div>

        <div class="mt-6 flex justify-center">
            <a href="index.php?action=asignaturas" class="btn-secondary inline-block rounded-md px-4 py-2 text-sm font-semibold shadow">
                ⬅️ Volver
            </a>
        </div>
    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>