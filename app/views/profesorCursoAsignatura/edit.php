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

<body class="min-h-screen bg-gray-900 text-white">

<div class="min-h-full">

    <!-- HEADER -->
    <header class="bg-gray-800 border-b border-gray-700 shadow">
        <div class="mx-auto max-w-6xl px-6 py-6">
            <h1 class="text-3xl font-bold">Editar Asignación</h1>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-4 py-8">

        <div class="bg-gray-800 p-8 rounded-2xl shadow-xl space-y-8">

            <form method="POST" action="index.php?action=pca_update&id=<?= $asignacion['id'] ?>" class="space-y-6">

                <!-- PROFESOR -->
                <div>
                    <label class="text-sm text-gray-300">Profesor</label>
                    <select name="profesor_id"
                            class="w-full bg-gray-900 text-white border border-gray-700 rounded-lg px-3 py-2 mt-2"
                            required>
                        <?php foreach ($profesores as $p): ?>
                            <option value="<?= $p['id'] ?>"
                                <?= $p['id'] == $asignacion['profesor_id'] ? 'selected':'' ?>>
                                <?= htmlspecialchars($p['nombre_completo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- CURSO -->
                <div>
                    <label class="text-sm text-gray-300">Curso</label>
                    <select name="curso_id" id="curso_id"
                            class="w-full bg-gray-900 text-white border border-gray-700 rounded-lg px-3 py-2 mt-2"
                            required>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['id'] ?>"
                                <?= $c['id'] == $asignacion['curso_id'] ? 'selected':'' ?>>
                                <?= htmlspecialchars($c['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- AÑO -->
                <div>
                    <label class="text-sm text-gray-300">Año Escolar</label>
                    <select name="anio_id"
                            class="w-full bg-gray-900 text-white border border-gray-700 rounded-lg px-3 py-2 mt-2"
                            required>
                        <?php foreach ($anios as $a): ?>
                            <option value="<?= $a['id'] ?>"
                                <?= $a['id'] == $asignacion['anio_id'] ? 'selected':'' ?>>
                                <?= $a['anio'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- HORAS SEMANALES -->
                <div>
                    <label class="text-sm text-gray-300">Horas semanales</label>
                    <input type="number" name="horas_semanales"
                           value="<?= $asignacion['horas_semanales'] ?>"
                           class="w-full bg-gray-900 text-white border border-gray-700 rounded-lg px-3 py-2 mt-2">
                </div>

                <!-- FECHAS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-300">Fecha inicio</label>
                        <input type="date" name="fecha_inicio"
                               value="<?= $asignacion['fecha_inicio'] ?>"
                               class="w-full bg-gray-900 text-white border border-gray-700 rounded-lg px-3 py-2 mt-2">
                    </div>

                    <div>
                        <label class="text-sm text-gray-300">Fecha fin</label>
                        <input type="date" name="fecha_fin"
                               value="<?= $asignacion['fecha_fin'] ?>"
                               class="w-full bg-gray-900 text-white border border-gray-700 rounded-lg px-3 py-2 mt-2">
                    </div>
                </div>

                <!-- JEFE DE CURSO -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="es_jefe_curso" id="jefe_chk"
                        <?= $asignacion['es_jefe_curso'] ? 'checked':'' ?>
                        class="h-5 w-5 text-indigo-500">
                    <label for="jefe_chk" class="text-gray-300">Profesor Jefe</label>
                </div>

                <!-- ASIGNATURAS DEL CURSO -->
                <div>
                    <label class="text-sm text-gray-300">Asignaturas del Curso</label>

                    <!-- BOTONES DE SELECCIÓN -->
                    <div class="flex gap-3 mt-3 mb-3">
                        <button type="button" id="checkAll"
                                class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm">
                            Seleccionar todas
                        </button>

                        <button type="button" id="uncheckAll"
                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">
                            Quitar todas
                        </button>
                    </div>

                    <!-- CONTENEDOR ASIGNATURAS -->
                    <div id="asig_container"
                         class="bg-gray-900 border border-gray-700 rounded-lg p-4 max-h-64 overflow-y-auto">

                        <?php foreach ($asignaturas as $asig): ?>
                            <label class="flex items-center gap-2 mb-2">
                                <input type="checkbox"
                                       name="asignaturas[]"
                                       value="<?= $asig['id'] ?>"
                                       <?= in_array($asig['id'], $asignaturasActuales) ? 'checked' : '' ?>
                                       class="h-4 w-4 text-indigo-500">
                                <span><?= htmlspecialchars($asig['nombre']) ?></span>
                            </label>
                        <?php endforeach; ?>

                    </div>
                </div>

                <!-- BOTÓN GUARDAR -->
                <button
                    class="w-full bg-indigo-600 hover:bg-indigo-700 px-4 py-3 rounded-lg font-semibold shadow text-white">
                    Guardar Cambios
                </button>

            </form>

        </div>

        <div class="mt-8 text-center">
            <a href="index.php?action=profesor_curso_asignatura"
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white">
                ⬅ Volver
            </a>
        </div>

    </main>

</div>

<!-- JS -->
<script>
/* ============================================
   SELECCIONAR TODAS / QUITAR TODAS
============================================ */
document.getElementById("checkAll").onclick = () => {
    document.querySelectorAll("input[name='asignaturas[]']").forEach(cb => cb.checked = true);
};

document.getElementById("uncheckAll").onclick = () => {
    document.querySelectorAll("input[name='asignaturas[]']").forEach(cb => cb.checked = false);
};

/* ============================================
   CARGAR ASIGNATURAS DINÁMICAS (AJAX)
============================================ */
document.getElementById('curso_id').addEventListener('change', function () {

    const curso = this.value;

    fetch(`index.php?action=pca_asignaturas_por_curso&curso_id=${curso}`)
        .then(r => r.json())
        .then(list => {

            const cont = document.getElementById('asig_container');
            cont.innerHTML = '';

            if (list.length === 0) {
                cont.innerHTML = '<p class="text-gray-400">Este curso no tiene asignaturas.</p>';
                return;
            }

            list.forEach(a => {
                const lbl = document.createElement('label');
                lbl.className = "flex items-center gap-2 mb-2";

                lbl.innerHTML = `
                    <input type="checkbox" name="asignaturas[]" value="${a.id}"
                        class="h-4 w-4 text-indigo-500">
                    <span>${a.nombre}</span>
                `;

                cont.appendChild(lbl);
            });
        });
});
</script>

</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>
