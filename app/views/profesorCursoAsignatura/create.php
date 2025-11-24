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

<body class="h-full bg-gray-900 text-white">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="relative bg-gray-800 after:absolute after:inset-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6">
                <h1 class="text-3xl font-bold text-white">Nueva Asignación de Profesor</h1>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-3xl px-4 py-6">

                <div class="bg-gray-800 p-8 rounded-2xl shadow-lg">

                    <form action="index.php?action=pca_store" method="POST" class="space-y-6">

                        <!-- PROFESOR -->
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Profesor</label>
                            <select name="profesor_id" required
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2">
                                <option value="">Seleccione…</option>
                                <?php foreach ($profesores as $p): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['nombre_completo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- CURSO -->
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Curso</label>
                            <select name="curso_id" id="curso_id" required
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2">
                                <option value="">Seleccione…</option>
                                <?php foreach ($cursos as $c): ?>
                                    <option value="<?= $c['id'] ?>">
                                        <?= htmlspecialchars($c['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- AÑO -->
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Año Escolar</label>
                            <select name="anio_id" required
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2">
                                <option value="">Seleccione…</option>
                                <?php foreach ($anios as $a): ?>
                                    <option value="<?= $a['id'] ?>"><?= $a['anio'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- ASIGNATURAS DINÁMICAS -->
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Asignaturas del Curso</label>

                            <div id="asignaturas_container"
                                class="bg-gray-900 border border-gray-700 rounded-lg p-4 mt-2 max-h-64 overflow-y-auto text-gray-300">
                                <p class="text-gray-400">Seleccione un curso primero.</p>
                            </div>
                        </div>

                        <!-- GUARDAR -->
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg font-semibold shadow">
                            Guardar Asignación
                        </button>

                    </form>

                </div>

                <div class="mt-6 text-center">
                    <a href="index.php?action=profesor_curso_asignatura"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white">
                        ⬅ Volver
                    </a>
                </div>

            </div>
        </main>

    </div>
</body>

<script>
    document.getElementById('curso_id').addEventListener('change', function () {

        const cursoId = this.value;
        const contenedor = document.getElementById('asignaturas_container');

        contenedor.innerHTML = "<p class='text-gray-400'>Cargando...</p>";

        fetch(`index.php?action=pca_asignaturas_por_curso&curso_id=${cursoId}`)
            .then(res => res.json())
            .then(data => {

                contenedor.innerHTML = '';

                if (data.length === 0) {
                    contenedor.innerHTML = "<p class='text-gray-400'>Este curso no tiene asignaturas asignadas.</p>";
                    return;
                }

                data.forEach(asig => {
                    const item = document.createElement('label');
                    item.classList = "flex items-center gap-2 mb-2";

                    item.innerHTML = `
                    <input type="checkbox" name="asignaturas[]" value="${asig.id}"
                           class="h-4 w-4 text-indigo-500">
                    <span>${asig.nombre}</span>
                `;

                    contenedor.appendChild(item);
                });

            })
            .catch(err => {
                contenedor.innerHTML = "<p class='text-red-400'>Error al cargar asignaturas.</p>";
                console.error(err);
            });
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>