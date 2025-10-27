<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

// Calcular edad
$edad = null;
if (!empty($alumno['fechanac'])) {
    $fechaNac = new DateTime($alumno['fechanac']);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNac)->y;
}

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<header class="relative bg-gray-800 border-b border-white/10">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white">Perfil Completo del Alumno</h1>
    </div>
</header>

<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();
?>

<main>
    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
            <h1 class="text-2xl font-bold text-white mb-6">Actualizar Registro</h1>

            <form method="POST" action="index.php?action=antecedentefamiliar_updateProfile" class="space-y-6">
                <input type="hidden" name="alumno_id" value="<?= htmlspecialchars($alumno['id']) ?>">

                <!-- Escolaridad Padre -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Escolaridad Padre</label>
                    <input type="text" name="padre" value="<?= htmlspecialchars($antecedente['padre'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <!-- Nivel o Ciclo Padre -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Nivel o Ciclo Padre</label>
                    <input type="text" name="nivel_ciclo_p"
                        value="<?= htmlspecialchars($antecedente['nivel_ciclo_p'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <!-- Escolaridad Madre -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Escolaridad Madre</label>
                    <input type="text" name="madre" value="<?= htmlspecialchars($antecedente['madre'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <!-- Nivel o Ciclo Madre -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Nivel o Ciclo Madre</label>
                    <input type="text" name="nivel_ciclo_m"
                        value="<?= htmlspecialchars($antecedente['nivel_ciclo_m'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
                        Actualizar
                    </button>
                    <a href="index.php?action=alumno_profile&id=<?= $alumno['id'] ?>"
                        class="flex-1 text-center bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 rounded-lg transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>


<?php include __DIR__ . "/../layout/footer.php"; ?>