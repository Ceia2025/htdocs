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

<body class="h-full">
    <div class="min-h-full">

        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Nueva Matrícula</h1>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex justify-center">

                <div class="bg-gray-700 p-8 rounded-2xl shadow-lg w-full max-w-lg">
                    <h2 class="text-2xl font-bold text-white mb-6 text-center">Registrar Matrícula</h2>

                    <form method="post" action="index.php?action=matricula_store" class="space-y-6">

                        <!-- Buscador de Alumno -->
                        <div>
                            <label for="alumno_search" class="block text-sm font-medium text-gray-200">Alumno</label>
                            <input type="text" id="alumno_search" autocomplete="off"
                                placeholder="Buscar por nombre o RUT..."
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <input type="hidden" name="alumno_id" id="alumno_id">
                            <ul id="alumno_list"
                                class="bg-gray-700 mt-1 rounded-lg max-h-48 overflow-y-auto hidden text-white border border-gray-600">
                            </ul>
                        </div>

                        <!-- Curso -->
                        <div>
                            <label for="curso_id" class="block text-sm font-medium text-gray-200">Curso</label>
                            <select name="curso_id" id="curso_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">Seleccione curso</option>
                                <?php foreach ($cursos as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Año -->
                        <div>
                            <label for="anio_id" class="block text-sm font-medium text-gray-200">Año Académico</label>
                            <select name="anio_id" id="anio_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">Seleccione año</option>
                                <?php foreach ($anios as $an): ?>
                                    <option value="<?= $an['id'] ?>"><?= htmlspecialchars($an['anio']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                            Guardar
                        </button>
                    </form>
                </div>
            </div>

            <!-- VOLVER -->
            <div class="mt-8 flex items-center justify-center">
                <a href="index.php?action=matriculas"
                    class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                    ⬅ Volver
                </a>
            </div>
        </main>

    </div>
</body>
<script>
    const searchInput = document.getElementById('alumno_search');
    const hiddenInput = document.getElementById('alumno_id');
    const list = document.getElementById('alumno_list');

    searchInput.addEventListener('input', function () {
        const term = this.value.trim();
        if (term.length < 2) {
            list.classList.add('hidden');
            return;
        }

        fetch(`index.php?action=alumno_search_ajax_matricula&term=${encodeURIComponent(term)}`)
            .then(res => res.json())
            .then(data => {
                list.innerHTML = '';
                if (data.length === 0) {
                    list.classList.add('hidden');
                    return;
                }

                data.forEach(item => {
                    const li = document.createElement('li');
                    li.textContent = `${item.nombre} ${item.apepat} ${item.apemat} (${item.run}-${item.codver})`;
                    li.className = "px-3 py-2 hover:bg-gray-600 cursor-pointer";
                    li.addEventListener('click', () => {
                        searchInput.value = li.textContent;
                        hiddenInput.value = item.id;
                        list.classList.add('hidden');
                    });
                    list.appendChild(li);
                });
                list.classList.remove('hidden');
            })
            .catch(err => console.error("Error buscando alumnos:", err));
    });

    // Ocultar lista si se hace clic fuera
    document.addEventListener('click', (e) => {
        if (!list.contains(e.target) && e.target !== searchInput) {
            list.classList.add('hidden');
        }
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>