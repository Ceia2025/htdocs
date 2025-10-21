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
<main>
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- FORM -->
        <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
            <form action="index.php?action=alumnos_store" method="POST" class="space-y-6">

                <!-- Grid de 2 columnas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- RUN -->


                    <div>
                        <label for="run" class="block text-sm font-medium text-gray-200">RUN</label>
                        <input type="text" name="run" id="run" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                        <p id="run-error" class="text-red-500 text-sm mt-1 hidden">RUN inválido (debe estar
                            entre 1.000.000 y 100.000.000)</p>
                    </div>

                    <!-- CÓDIGO VERIFICADOR -->
                    <div>
                        <label for="codver" class="block text-sm font-medium text-gray-200">Código
                            Verificador</label>
                        <input type="text" name="codver" id="codver" required readonly
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 text-center cursor-not-allowed">
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nombre</label>
                        <input type="text" name="nombre" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Apellido Paterno -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Apellido Paterno</label>
                        <input type="text" name="apepat" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Apellido Materno -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Apellido Materno</label>
                        <input type="text" name="apemat"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Fecha de Nacimiento</label>
                        <input type="date" name="fechanac"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Número de Hijos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Número de Hijos</label>
                        <input type="number" name="numerohijos" min="0"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Teléfono</label>
                        <input type="text" name="telefono"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Email</label>
                        <input type="email" name="email"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Sexo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Sexo</label>
                        <select name="sexo" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="F">Femenino</option>
                            <option value="M">Masculino</option>
                        </select>
                    </div>

                    <!-- Nacionalidad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nacionalidad</label>
                        <input type="text" name="nacionalidades"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Región -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Región</label>
                        <select id="region" name="region"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">Seleccione una región</option>
                        </select>
                    </div>

                    <!-- Ciudad/Comuna -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-200">Ciudad / Comuna</label>
                        <select id="ciudad" name="ciudad"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">Seleccione una ciudad</option>
                        </select>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Direccion</label>
                        <input type="text" name="direccion"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Etnia -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-200">Etnia</label>
                        <select name="cod_etnia"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            <?php
                            $etnias = [
                                "No pertenece a ningún Pueblo Originario",
                                "Aymara",
                                "Likanantai( Atacameño )",
                                "Colla",
                                "Diaguita",
                                "Quechua",
                                "Rapa Nui",
                                "Mapuche",
                                "Kawésqar",
                                "Yagán",
                                "Otro",
                                "No Registra"
                            ];
                            foreach ($etnias as $etnia): ?>
                                <option value="<?= $etnia ?>" <?= (!isset($alumno['cod_etnia']) && $etnia === "No pertenece a ningún Pueblo Originario")
                                      || (isset($alumno['cod_etnia']) && $alumno['cod_etnia'] === $etnia)
                                      ? 'selected' : '' ?>>
                                    <?= $etnia ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <!-- Botones -->
                <div class="flex space-x-4 pt-4">
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                        Guardar
                    </button>
                    <a href="index.php?action=alumnos"
                        class="w-full text-center inline-block rounded-lg bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 transition duration-200 ease-in-out">
                        Cancelar
                    </a>
                </div>

            </form>
        </div>

    </div>
</main>

<script>
    //Cargar region, dependiendo de las ciudades 
    document.addEventListener("DOMContentLoaded", () => {
        const regionSelect = document.getElementById("region");
        const ciudadSelect = document.getElementById("ciudad");

        // Cargar JSON desde carpeta utils
        fetch("../utils/comunas-regiones.json")
            .then(response => response.json())
            .then(data => {
                // Llenar el select de regiones
                data.regiones.forEach(r => {
                    const option = document.createElement("option");
                    option.value = r.region;
                    option.textContent = r.region;
                    regionSelect.appendChild(option);
                });

                // Cuando cambie la región, cargar comunas
                regionSelect.addEventListener("change", () => {
                    ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>'; // limpiar
                    const selectedRegion = regionSelect.value;
                    const region = data.regiones.find(r => r.region === selectedRegion);
                    if (region) {
                        region.comunas.forEach(c => {
                            const option = document.createElement("option");
                            option.value = c;
                            option.textContent = c;
                            ciudadSelect.appendChild(option);
                        });
                    }
                });
            })
            .catch(err => console.error("Error cargando comunas-regiones.json:", err));
    });

    // ------------------------------------------------------------------------------------------------------------>

    // Formateo de rut al momento de guardar
    function formatRun(value) {
        value = value.replace(/\D/g, ""); // solo números
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // agrega puntos
    }

    function validateRun(value) {
        const numericValue = parseInt(value.replace(/\./g, ""), 10);
        if (isNaN(numericValue)) return false;
        return numericValue >= 1000000 && numericValue <= 100000000;
    }

    function calcularDV(rut) {
        let suma = 0;
        let multiplicador = 2;
        for (let i = rut.length - 1; i >= 0; i--) {
            suma += parseInt(rut.charAt(i), 10) * multiplicador;
            multiplicador = multiplicador < 7 ? multiplicador + 1 : 2;
        }
        const resto = 11 - (suma % 11);
        if (resto === 11) return "0";
        if (resto === 10) return "K";
        return String(resto);
    }

    const runInput = document.getElementById('run');
    const codverInput = document.getElementById('codver');
    const runError = document.getElementById('run-error');

    // Bloquear cualquier letra o símbolo en el RUN
    runInput.addEventListener('keypress', function (e) {
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });

    runInput.addEventListener('input', function (e) {
        let formatted = formatRun(e.target.value);
        e.target.value = formatted;

        if (formatted && !validateRun(formatted)) {
            runError.classList.remove('hidden');
            codverInput.value = "";
        } else {
            runError.classList.add('hidden');
            const numericValue = formatted.replace(/\./g, "");
            if (numericValue.length > 0 && validateRun(formatted)) {
                codverInput.value = calcularDV(numericValue);
            } else {
                codverInput.value = "";
            }
        }
    });


</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>