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

// Helpers de selección segura
function sel($a, $b)
{
    return (isset($a) && (string) $a === (string) $b) ? 'selected' : '';
}

$cursos = ['1ro basico', '2do basico', '3ro basico', '4to basico', '5to basico', '6to basico', '7mo basico', '8vo basico', '1ro medio', '2do medio', '3ro Medio', '4to Medio'];
$pa = ['Sin', 'Con', 'Desconocido'];
$pie = ['Si', 'No', 'No se sabe'];
$cs = ['Prioritario', 'Preferente', 'Incremento', 'Pro-Retención'];
$gf = ['Ninguno', 'A', 'B', 'C', 'D'];
$isapres = ['Ninguno', 'BANCA MEDICA', 'CRUZ BLANCA', 'COLMENA', 'MAS VIDA', 'CON SALUD', 'VIDA TRES', 'DIPRECA'];
?>

<!-- HEADER -->
<header
    class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white">Editar Antecedente Escolar</h1>
    </div>
</header>

<!-- MAIN -->
<main>
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
            <form method="POST" action="index.php?action=antecedente_escolar_updateProfile" class="space-y-6">
                <input type="hidden" name="alumno_id" value="<?= htmlspecialchars($alumno['id']) ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Procedencia -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Procedencia del Colegio</label>
                        <input type="text" name="procedencia_colegio"
                            value="<?= htmlspecialchars($antecedente['procedencia_colegio'] ?? '') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Comuna -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Comuna</label>
                        <input type="text" name="comuna" value="<?= htmlspecialchars($antecedente['comuna'] ?? '') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Último Curso (ENUM) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Último Curso</label>
                        <select name="ultimo_curso"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($cursos as $c): ?>
                                <option value="<?= $c ?>" <?= sel($antecedente['ultimo_curso'] ?? null, $c) ?>><?= $c ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Último Año Cursado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Último Año Cursado</label>
                        <input type="text" name="ultimo_anio_cursado" maxlength="4"
                            value="<?= htmlspecialchars($antecedente['ultimo_anio_cursado'] ?? '') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Cursos Repetidos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Cursos Repetidos</label>
                        <input type="number" min="0" name="cursos_repetidos"
                            value="<?= htmlspecialchars($antecedente['cursos_repetidos'] ?? '0') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Pertenece al 20% -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Pertenece al 20%</label>
                        <select name="pertenece_20"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= sel((string) ($antecedente['pertenece_20'] ?? ''), '1') ?>>Sí</option>
                            <option value="0" <?= sel((string) ($antecedente['pertenece_20'] ?? ''), '0') ?>>No</option>
                        </select>
                    </div>

                    <!-- Informe 20% -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Tiene Informe 20%</label>
                        <select name="informe_20"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= sel((string) ($antecedente['informe_20'] ?? ''), '1') ?>>Sí</option>
                            <option value="0" <?= sel((string) ($antecedente['informe_20'] ?? ''), '0') ?>>No</option>
                        </select>
                    </div>

                    <!-- Embarazo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Embarazo</label>
                        <select name="embarazo"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= sel((string) ($antecedente['embarazo'] ?? ''), '1') ?>>Sí</option>
                            <option value="0" <?= sel((string) ($antecedente['embarazo'] ?? ''), '0') ?>>No</option>
                        </select>
                    </div>

                    <!-- Semanas (si hay embarazo) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Semanas (si aplica)</label>
                        <input type="number" min="0" name="semanas"
                            value="<?= htmlspecialchars($antecedente['semanas'] ?? '') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Problemas de aprendizaje (ENUM) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Problemas de Aprendizaje</label>
                        <select name="prob_apren"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($pa as $p): ?>
                                <option value="<?= $p ?>" <?= sel($antecedente['prob_apren'] ?? null, $p) ?>><?= $p ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- PIE (ENUM) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">PIE</label>
                        <select name="pie"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($pie as $p): ?>
                                <option value="<?= $p ?>" <?= sel($antecedente['pie'] ?? null, $p) ?>><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Chile Solidario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Chile Solidario</label>
                        <select name="chile_solidario"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1" <?= sel((string) ($antecedente['chile_solidario'] ?? ''), '1') ?>>Sí
                            </option>
                            <option value="0" <?= sel((string) ($antecedente['chile_solidario'] ?? ''), '0') ?>>No
                            </option>
                        </select>
                    </div>

                    <!-- Tipo Chile Solidario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Tipo Chile Solidario</label>
                        <select name="chile_solidario_cual"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($cs as $v): ?>
                                <option value="<?= $v ?>" <?= sel($antecedente['chile_solidario_cual'] ?? null, $v) ?>>
                                    <?= $v ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fonasa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Fonasa</label>
                        <input type="text" name="fonasa" value="<?= htmlspecialchars($antecedente['fonasa'] ?? '') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Grupo Fonasa (ENUM) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Grupo Fonasa</label>
                        <select name="grupo_fonasa"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($gf as $g): ?>
                                <option value="<?= $g ?>" <?= sel($antecedente['grupo_fonasa'] ?? null, $g) ?>><?= $g ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Isapre (ENUM) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Isapre</label>
                        <select name="isapre"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($isapres as $i): ?>
                                <option value="<?= $i ?>" <?= sel($antecedente['isapre'] ?? null, $i) ?>><?= $i ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Seguro Salud -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Seguro de Salud</label>
                        <input type="text" name="seguro_salud"
                            value="<?= htmlspecialchars($antecedente['seguro_salud'] ?? '') ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Información de Salud -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-200">Información de Salud</label>
                        <textarea name="info_salud" rows="3"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            <?= htmlspecialchars($antecedente['info_salud'] ?? '') ?>
                        </textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-200">Información de Salud</label>
                        <textarea name="info_salud" rows="3"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            <?= htmlspecialchars($antecedente['info_salud'] ?? '') ?>
                        </textarea>
                    </div>

                    <!-- Evaluación Psicológica -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-200">Evaluación Psicológica</label>
                        <textarea name="eva_psico" rows="3"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"><?= htmlspecialchars($antecedente['eva_psico'] ?? '') ?></textarea>
                    </div>

                </div>

                <!-- Botones -->
                <div class="flex justify-between pt-8">
                    <a href="index.php?action=alumno_profile&id=<?= $alumno['id'] ?>"
                        class="btn-gray inline-flex items-center justify-center px-6 py-2 rounded-lg bg-gray-600 hover:bg-gray-500 text-white font-semibold">
                        ⬅️ Volver
                    </a>
                    <button type="submit" id="btnGuardar"
                        class="btn-green inline-flex items-center justify-center px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">
                        Guardar ✅
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>

<script>
    // Validación: impedir submit si todo está vacío (tu lógica)
    document.querySelector('form').addEventListener('submit', function (e) {
        const form = new FormData(this);
        const keys = [
            'procedencia_colegio', 'comuna', 'ultimo_curso', 'ultimo_anio_cursado',
            'cursos_repetidos', 'pertenece_20', 'informe_20', 'embarazo', 'semanas',
            'info_salud', 'eva_psico', 'prob_apren', 'pie', 'chile_solidario',
            'chile_solidario_cual', 'fonasa', 'grupo_fonasa', 'isapre', 'seguro_salud'
        ];

        let anyValue = false;
        for (const k of keys) {
            const v = form.get(k);
            if (v !== null && v !== '' && v !== '0') { anyValue = true; break; }
        }
        if (!anyValue) {
            e.preventDefault();
            alert('No se realizaron cambios: todos los campos están vacíos.');
        }
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>