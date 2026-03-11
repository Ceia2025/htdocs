<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

// Opciones ENUM escolaridad — deben coincidir con el stepper
$niveles = [
    'Basica Incompleta',
    'Basica Completa',
    'Media Incompleta',
    'Media Completa',
    'Técnico Incompleta',
    'Técnico Completa',
    'Superior Incompleta',
    'Superior Completa',
    'Desconocido'
];

// Helper: marca selected si coincide
function selFam($a, $b)
{
    return (isset($a) && (string) $a === (string) $b) ? 'selected' : '';
}

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<header class="relative bg-gray-800 border-b border-white/10">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white">Antecedentes Familiares</h1>
    </div>
</header>

<main>
    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-6">Actualizar Registro</h2>

            <form method="POST" action="index.php?action=antecedentefamiliar_updateProfile" class="space-y-6">
                <input type="hidden" name="alumno_id" value="<?= htmlspecialchars($alumno['id']) ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Escolaridad Padre — SELECT -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Escolaridad Padre</label>
                        <select name="padre"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($niveles as $nivel): ?>
                                <option value="<?= $nivel ?>" <?= selFam($antecedente['padre'] ?? '', $nivel) ?>>
                                    <?= $nivel ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nivel o Ciclo Padre — INPUT -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nivel o Ciclo Padre</label>
                        <input type="text" name="nivel_ciclo_p"
                            value="<?= htmlspecialchars($antecedente['nivel_ciclo_p'] ?? '') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                    </div>

                    <!-- Escolaridad Madre — SELECT -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Escolaridad Madre</label>
                        <select name="madre"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($niveles as $nivel): ?>
                                <option value="<?= $nivel ?>" <?= selFam($antecedente['madre'] ?? '', $nivel) ?>>
                                    <?= $nivel ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nivel o Ciclo Madre — INPUT -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nivel o Ciclo Madre</label>
                        <input type="text" name="nivel_ciclo_m"
                            value="<?= htmlspecialchars($antecedente['nivel_ciclo_m'] ?? '') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                    </div>

                </div>

                <!-- Botones -->
                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
                        Actualizar
                    </button>
                    <a href="index.php?action=alumno_profile&id=<?= $alumno['id'] ?>"
                        class="flex-1 text-center bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 rounded-lg transition">
                        ⬅️ Cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>