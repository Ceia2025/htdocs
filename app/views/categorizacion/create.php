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
                <h1 class="text-3xl font-bold tracking-tight text-white">Crear Individualización</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <div class="flex items-center justify-center">
                    <div class="bg-gray-700 p-6 rounded-2xl shadow-lg w-full max-w-md">
                        <h2 class="text-2xl font-bold text-white mb-6 text-center">Nueva Individualización</h2>

                        <?php if (isset($_GET['error']) && $_GET['error'] === 'duplicado'): ?>
                            <div class="mb-4 p-3 bg-red-600 text-white rounded-lg text-center">
                                ⚠️ Ya existe una Individualización con ese código general y específico.
                            </div>
                        <?php endif; ?>

                        <form method="post" action="index.php?action=categorizacion_store" class="space-y-4">

                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-200">Nombre</label>
                                <input type="text" name="nombre" id="nombre" required autocomplete="off"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    oninput="buscarNombre(this.value)">
                                <div id="sugerencias" class="bg-gray-800 rounded-lg mt-1 shadow-lg hidden"></div>
                            </div>

                            <div>
                                <label for="codigo_general" class="block text-sm font-medium text-gray-200">Código
                                    General</label>
                                <input type="text" name="codigo_general" id="codigo_general" required
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label for="codigo_especifico" class="block text-sm font-medium text-gray-200">Código
                                    Específico</label>
                                <input type="number" name="codigo_especifico" id="codigo_especifico"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                                Guardar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-center">
                    <a href="index.php?action=categorizaciones"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        ⬅ Volver
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        const baseURL = window.location.origin + '/app/ajax/';

        async function buscarNombre(termino) {
            const sugerenciasDiv = document.getElementById('sugerencias');

            if (termino.length < 2) {
                sugerenciasDiv.classList.add('hidden');
                sugerenciasDiv.innerHTML = '';
                return;
            }

            const response = await fetch(`${baseURL}buscar_individualizacion.php?term=${encodeURIComponent(termino)}`);
            const datos = await response.json();

            if (!datos.length) {
                sugerenciasDiv.classList.add('hidden');
                sugerenciasDiv.innerHTML = '';
                return;
            }

            sugerenciasDiv.classList.remove('hidden');
            sugerenciasDiv.innerHTML = datos.map(item => `
        <div class="p-2 hover:bg-indigo-600 hover:text-white cursor-pointer"
             onclick="seleccionarSugerencia('${item.nombre}', '${item.codigo_general}')">
             ${item.nombre} <span class='text-gray-400 text-xs'>(Código: ${item.codigo_general})</span>
        </div>
    `).join('');
        }

        function seleccionarSugerencia(nombre, codigoGeneral) {
            document.getElementById('nombre').value = nombre;
            document.getElementById('codigo_general').value = codigoGeneral;

            // Ocultar sugerencias
            const sugerenciasDiv = document.getElementById('sugerencias');
            sugerenciasDiv.classList.add('hidden');
            sugerenciasDiv.innerHTML = '';

            // Generar automáticamente el código específico
            generarCodigoEspecifico(codigoGeneral);
        }

        async function generarCodigoEspecifico(codigoGeneral) {
            const response = await fetch(`${baseURL}generar_codigo.php?codigo_general=${encodeURIComponent(codigoGeneral)}`);
            const data = await response.json();
            document.getElementById('codigo_especifico').value = data.nuevo_codigo;
        }
    </script>

</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>