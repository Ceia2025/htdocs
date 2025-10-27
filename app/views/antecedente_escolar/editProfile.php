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

<!-- MAIN -->
<header
    class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white">Alumnos</h1>
    </div>
</header>
<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();
?>

<main>
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
            <h1 class="text-2xl font-bold text-white mb-6">Editar Antecedente Escolar</h1>

            <form method="POST" action="index.php?action=antecedente_escolar_updateProfile" class="space-y-6">
                <input type="hidden" name="alumno_id" value="<?= htmlspecialchars($alumno['id']) ?>">

                <!-- Procedencia Colegio -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Procedencia del Colegio</label>
                    <input type="text" name="procedencia_colegio"
                        value="<?= htmlspecialchars($antecedente['procedencia_colegio'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <!-- Comuna -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Comuna</label>
                    <input type="text" name="comuna" value="<?= htmlspecialchars($antecedente['comuna'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <!-- Último curso -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Último curso</label>
                    <input type="text" name="ultimo_curso"
                        value="<?= htmlspecialchars($antecedente['ultimo_curso'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <!-- Último año cursado -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Último año cursado</label>
                    <input type="text" name="ultimo_anio_cursado"
                        value="<?= htmlspecialchars($antecedente['ultimo_anio_cursado'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <!-- Cursos repetidos -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Cursos Repetidos</label>
                    <input type="text" name="cursos_repetidos"
                        value="<?= htmlspecialchars($antecedente['cursos_repetidos'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <!-- Información de Salud -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Información de Salud</label>
                    <textarea name="info_salud"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"><?= htmlspecialchars($antecedente['info_salud'] ?? '') ?></textarea>
                </div>

                <!-- Evaluación Psicológica -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Evaluación Psicológica</label>
                    <textarea name="eva_psico"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"><?= htmlspecialchars($antecedente['eva_psico'] ?? '') ?></textarea>
                </div>

                <!-- PIE -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">PIE</label>
                    <input type="text" name="pie" value="<?= htmlspecialchars($antecedente['pie'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <!-- Fonasa -->
                <div>
                    <label class="block text-sm font-medium text-gray-200">Grupo Fonasa</label>
                    <input type="text" name="grupo_fonasa"
                        value="<?= htmlspecialchars($antecedente['grupo_fonasa'] ?? '') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                </div>

                <div class="flex gap-4 mt-6">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
                        Guardar cambios
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