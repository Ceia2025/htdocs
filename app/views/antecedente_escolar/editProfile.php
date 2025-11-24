<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Helpers
function sel($a, $b) {
    return (isset($a) && (string)$a === (string)$b) ? 'selected' : '';
}

$cursos = ['1ro basico','2do basico','3ro basico','4to basico','5to basico','6to basico','7mo basico','8vo basico','1ro medio','2do medio','3ro Medio','4to Medio'];
$pa = ['Sin','Con','Desconocido'];
$pie = ['Si','No','No se sabe'];
$cs = ['Prioritario','Preferente','Incremento','Pro-Retención'];
$gf = ['Ninguno','A','B','C','D'];
$isapres = ['Ninguno','BANCA MEDICA','CRUZ BLANCA','COLMENA','MAS VIDA','CON SALUD','VIDA TRES','DIPRECA'];

$esMujer = isset($alumno['sexo']) && strtoupper($alumno['sexo']) === 'F';
?>

<header class="bg-gray-800 border-b border-gray-700">
    <div class="mx-auto max-w-5xl px-4 py-6">
        <h1 class="text-3xl font-bold text-white">Editar Antecedente Escolar</h1>
    </div>
</header>

<main>
    <div class="mx-auto max-w-5xl px-4 py-6">

        <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
            <form method="POST" action="index.php?action=antecedente_escolar_updateProfile" class="space-y-6">

                <input type="hidden" name="alumno_id" value="<?= htmlspecialchars($alumno['id']) ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Procedencia -->
                    <div>
                        <label class="text-gray-200 text-sm">Procedencia del Colegio</label>
                        <input type="text" name="procedencia_colegio"
                               value="<?= htmlspecialchars($antecedente['procedencia_colegio'] ?? '') ?>"
                               class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                    </div>

                    <!-- Comuna -->
                    <div>
                        <label class="text-gray-200 text-sm">Comuna</label>
                        <input type="text" name="comuna"
                               value="<?= htmlspecialchars($antecedente['comuna'] ?? '') ?>"
                               class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                    </div>

                    <!-- Último curso -->
                    <div>
                        <label class="text-gray-200 text-sm">Último Curso</label>
                        <select name="ultimo_curso"
                                class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($cursos as $c): ?>
                                <option value="<?= $c ?>" <?= sel($antecedente['ultimo_curso'], $c) ?>><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Último año cursado -->
                    <div>
                        <label class="text-gray-200 text-sm">Último Año Cursado</label>
                        <input type="text" maxlength="4" name="ultimo_anio_cursado"
                               value="<?= htmlspecialchars($antecedente['ultimo_anio_cursado'] ?? '') ?>"
                               class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                    </div>

                    <!-- Cursos repetidos -->
                    <div>
                        <label class="text-gray-200 text-sm">Cursos Repetidos</label>
                        <input type="number" min="0" name="cursos_repetidos"
                               value="<?= htmlspecialchars($antecedente['cursos_repetidos'] ?? '0') ?>"
                               class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                    </div>

                    <!-- Pertenece 20% -->
                    <div>
                        <label class="text-gray-200 text-sm">Pertenece al 20%</label>
                        <select name="pertenece_20"
                                class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= sel($antecedente['pertenece_20'], '1') ?>>Sí</option>
                            <option value="0" <?= sel($antecedente['pertenece_20'], '0') ?>>No</option>
                        </select>
                    </div>

                    <!-- Informe 20% -->
                    <div>
                        <label class="text-gray-200 text-sm">Tiene Informe 20%</label>
                        <select name="informe_20"
                                class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= sel($antecedente['informe_20'], '1') ?>>Sí</option>
                            <option value="0" <?= sel($antecedente['informe_20'], '0') ?>>No</option>
                        </select>
                    </div>

                    <!-- ==== EMBARAZO (solo si es mujer) ==== -->
                    <?php if ($esMujer): ?>
                    <div>
                        <label class="text-gray-200 text-sm">¿Embarazo?</label>
                        <select name="embarazo" id="embarazo"
                                class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= sel($antecedente['embarazo'], '1') ?>>Sí</option>
                            <option value="0" <?= sel($antecedente['embarazo'], '0') ?>>No</option>
                        </select>
                    </div>

                    <div id="semanas_container">
                        <label class="text-gray-200 text-sm">Semanas de gestación</label>
                        <input type="number" name="semanas" id="semanas"
                               value="<?= htmlspecialchars($antecedente['semanas'] ?? '') ?>"
                               class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                    </div>
                    <?php endif; ?>

                    <!-- Problemas de aprendizaje -->
                    <div>
                        <label class="text-gray-200 text-sm">Problemas de Aprendizaje</label>
                        <select name="prob_apren"
                                class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($pa as $p): ?>
                                <option value="<?= $p ?>" <?= sel($antecedente['prob_apren'], $p) ?>><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- PIE -->
                    <div>
                        <label class="text-gray-200 text-sm">PIE</label>
                        <select name="pie"
                                class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($pie as $p): ?>
                                <option value="<?= $p ?>" <?= sel($antecedente['pie'], $p) ?>><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Chile solidario -->
                    <div>
                        <label class="text-gray-200 text-sm">Chile Solidario</label>
                        <select name="chile_solidario"
                                class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= sel($antecedente['chile_solidario'], '1') ?>>Sí</option>
                            <option value="0" <?= sel($antecedente['chile_solidario'], '0') ?>>No</option>
                        </select>
                    </div>

                    <!-- Tipo Chile solidario -->
                    <div>
                        <label class="text-gray-200 text-sm">Tipo Chile Solidario</label>
                        <select name="chile_solidario_cual"
                                class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($cs as $v): ?>
                                <option value="<?= $v ?>" <?= sel($antecedente['chile_solidario_cual'], $v) ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fonasa -->
                    <div>
                        <label class="text-gray-200 text-sm">Fonasa</label>
                        <input type="text" name="fonasa"
                               value="<?= htmlspecialchars($antecedente['fonasa'] ?? '') ?>"
                               class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                    </div>

                    <!-- Grupo Fonasa -->
                    <div>
                        <label class="text-gray-200 text-sm">Grupo Fonasa</label>
                        <select name="grupo_fonasa"
                                class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($gf as $g): ?>
                                <option value="<?= $g ?>" <?= sel($antecedente['grupo_fonasa'], $g) ?>><?= $g ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Isapre -->
                    <div>
                        <label class="text-gray-200 text-sm">Isapre</label>
                        <select name="isapre"
                                class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($isapres as $i): ?>
                                <option value="<?= $i ?>" <?= sel($antecedente['isapre'], $i) ?>><?= $i ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Seguro -->
                    <div>
                        <label class="text-gray-200 text-sm">Seguro Salud</label>
                        <input type="text" name="seguro_salud"
                               value="<?= htmlspecialchars($antecedente['seguro_salud'] ?? '') ?>"
                               class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2">
                    </div>

                    <!-- Salud -->
                    <div class="md:col-span-2">
                        <label class="text-gray-200 text-sm">Información de Salud</label>
                        <textarea name="info_salud" rows="3"
                                  class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2"><?= htmlspecialchars($antecedente['info_salud'] ?? '') ?></textarea>
                    </div>

                    <!-- Psicológico -->
                    <div class="md:col-span-2">
                        <label class="text-gray-200 text-sm">Evaluación Psicológica</label>
                        <textarea name="eva_psico" rows="3"
                                  class="mt-2 w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2"><?= htmlspecialchars($antecedente['eva_psico'] ?? '') ?></textarea>
                    </div>

                </div>

                <!-- Botones -->
                <div class="flex justify-between pt-8">
                    <a href="index.php?action=alumno_profile&id=<?= $alumno['id'] ?>"
                       class="px-6 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg font-semibold">
                        ⬅️ Volver
                    </a>

                    <button type="submit"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold">
                        Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>


<script>
// Lógica visual de embarazo y semanas
document.addEventListener("DOMContentLoaded", function () {

    const embarazo = document.getElementById("embarazo");
    const semanasContainer = document.getElementById("semanas_container");
    const semanasInput = document.getElementById("semanas");

    if (embarazo && semanasContainer) {

        function actualizar() {
            if (embarazo.value === "1") {
                semanasContainer.style.display = "block";
            } else {
                semanasContainer.style.display = "none";
                semanasInput.value = "";
            }
        }

        actualizar();
        embarazo.addEventListener("change", actualizar);
    }

});
</script>
