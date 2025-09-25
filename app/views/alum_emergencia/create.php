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

<body class="h-full bg-gray-900">
        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Nuevo Contacto de Emergencia</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FORMULARIO -->
                <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
                    <form method="POST" action="index.php?action=alum_emergencia_store" class="space-y-6">

                        <!-- Alumno -->
                        <div>
                            <label for="alumno_search" class="block text-sm font-medium text-gray-200">Alumno</label>
                            <input type="text" id="alumno_search" placeholder="Escribe nombre o RUT"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <input type="hidden" name="alumno_id" id="alumno_id">
                            <ul id="alumno_list"
                                class="bg-gray-700 mt-1 rounded-lg max-h-40 overflow-y-auto hidden text-white"></ul>
                        </div>

                        <!-- Nombre del contacto -->
                        <div>
                            <label for="nombre_contacto" class="block text-sm font-medium text-gray-200">Nombre del
                                contacto</label>
                            <input type="text" name="nombre_contacto" id="nombre_contacto" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-200">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-200">Dirección</label>
                            <input type="text" name="direccion" id="direccion"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Relación -->
                        <div>
                            <label for="relacion" class="block text-sm font-medium text-gray-200">Relación</label>
                            <select name="relacion" id="relacion" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Selecciona --</option>
                                <option value="Madre">Madre</option>
                                <option value="Padre">Padre</option>
                                <option value="Hermana/Hermano">Hermana/Hermano</option>
                                <option value="Tutor Legal">Tutor Legal</option>
                                <option value="Representante">Representante</option>
                                <option value="Apoderado">Apoderado</option>
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex space-x-4">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                                Guardar
                            </button>
                            <a href="index.php?action=alum_emergencia"
                                class="w-full text-center inline-block rounded-lg bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 transition duration-200 ease-in-out">
                                Cancelar
                            </a>
                        </div>

                    </form>
                </div>

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

        fetch(`index.php?action=alumno_search_ajax&term=${encodeURIComponent(term)}`)
            .then(res => res.json())
            .then(data => {
                list.innerHTML = '';
                if (data.length === 0) {
                    list.classList.add('hidden');
                    return;
                }
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.textContent = `${item.nombre} ${item.apepat} ${item.apemat} ${item.run}-${item.codver}`;
                    li.className = "px-3 py-2 hover:bg-gray-600 cursor-pointer";
                    li.addEventListener('click', () => {
                        searchInput.value = li.textContent;
                        hiddenInput.value = item.id;
                        list.classList.add('hidden');
                    });
                    list.appendChild(li);
                });
                list.classList.remove('hidden');
            });
    });

    // Cierra la lista si se hace click fuera
    document.addEventListener('click', (e) => {
        if (!list.contains(e.target) && e.target !== searchInput) {
            list.classList.add('hidden');
        }
    });
</script>


<?php include __DIR__ . "/../layout/footer.php"; ?>