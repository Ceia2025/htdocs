<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
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
        <h1 class="text-3xl font-bold tracking-tight text-white">Editar Alumno</h1>
    </div>
</header>

<main>
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">

            <!-- ⚠️ acción corregida: update con id -->
            <form action="index.php?action=alumno_update&id=<?= htmlspecialchars($alumno['id']) ?>" method="POST"
                class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- RUN -->
                    <div>
                        <label for="run" class="block text-sm font-medium text-gray-200">RUN</label>
                        <input type="text" name="run" id="run" required value="<?= htmlspecialchars($alumno['run']) ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                        <p id="run-error" class="text-red-500 text-sm mt-1 hidden">RUN inválido (debe estar entre
                            1.000.000 y 100.000.000)</p>
                    </div>

                    <!-- Código verificador -->
                    <div>
                        <label for="codver" class="block text-sm font-medium text-gray-200">Código Verificador</label>
                        <input type="text" name="codver" id="codver" required readonly
                            value="<?= htmlspecialchars($alumno['codver']) ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 text-center cursor-not-allowed">
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nombre</label>
                        <input type="text" name="nombre" required value="<?= htmlspecialchars($alumno['nombre']) ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Apellido Paterno -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Apellido Paterno</label>
                        <input type="text" name="apepat" required value="<?= htmlspecialchars($alumno['apepat']) ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Apellido Materno -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Apellido Materno</label>
                        <input type="text" name="apemat" value="<?= htmlspecialchars($alumno['apemat']) ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Fecha de nacimiento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Fecha de Nacimiento</label>
                        <input type="date" name="fechanac" value="<?= htmlspecialchars($alumno['fechanac']) ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Número de hijos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Número de Hijos</label>
                        <input type="number" name="numerohijos" min="0"
                            value="<?= htmlspecialchars($alumno['numerohijos']) ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Teléfono</label>
                        <input type="text" name="telefono" value="<?= htmlspecialchars($alumno['telefono']) ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($alumno['email']) ?>"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Sexo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Sexo</label>
                        <select name="sexo" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="F" <?= ($alumno['sexo'] === 'F' ? 'selected' : '') ?>>Femenino</option>
                            <option value="M" <?= ($alumno['sexo'] === 'M' ? 'selected' : '') ?>>Masculino</option>
                        </select>
                    </div>

                    <!-- Nacionalidad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nacionalidad</label>
                        <input type="text" name="nacionalidades"
                            value="<?= htmlspecialchars($alumno['nacionalidades']) ?>"
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

                    <!-- Ciudad / Comuna -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-200">Ciudad / Comuna</label>
                        <select id="ciudad" name="ciudad"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">Seleccione una ciudad</option>
                        </select>
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-200">Dirección</label>
                        <input type="text" name="direccion" value="<?= htmlspecialchars($alumno['direccion']) ?>"
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
                                <option value="<?= $etnia ?>" <?= ($alumno['cod_etnia'] === $etnia ? 'selected' : '') ?>>
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
                        Actualizar
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
    // ====== Datos actuales (desde PHP) ======
    const PRE_REGION = <?= json_encode($alumno['region'] ?? '') ?>;
    const PRE_CIUDAD = <?= json_encode($alumno['ciudad'] ?? '') ?>;

    // ====== Relleno de regiones y comunas con preselección ======
    document.addEventListener("DOMContentLoaded", () => {
        const regionSelect = document.getElementById("region");
        const ciudadSelect = document.getElementById("ciudad");

        fetch("../utils/comunas-regiones.json")
            .then(r => r.json())
            .then(data => {
                // 1) Llenar regiones
                data.regiones.forEach(r => {
                    const opt = document.createElement("option");
                    opt.value = r.region;
                    opt.textContent = r.region;
                    if (PRE_REGION && r.region === PRE_REGION) opt.selected = true;
                    regionSelect.appendChild(opt);
                });

                // 2) Si hay región previa, llenar comunas de esa región y seleccionar la previa
                function fillComunas(regionName, selectedComuna = "") {
                    ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>';
                    const reg = data.regiones.find(rr => rr.region === regionName);
                    if (!reg) return;
                    reg.comunas.forEach(c => {
                        const opt = document.createElement("option");
                        opt.value = c;
                        opt.textContent = c;
                        if (selectedComuna && c === selectedComuna) opt.selected = true;
                        ciudadSelect.appendChild(opt);
                    });
                }

                if (PRE_REGION) {
                    fillComunas(PRE_REGION, PRE_CIUDAD || "");
                }

                // 3) Al cambiar región, recargar comunas y limpiar selección
                regionSelect.addEventListener("change", () => {
                    fillComunas(regionSelect.value, "");
                });
            })
            .catch(err => console.error("Error cargando comunas-regiones.json:", err));
    });

    // ====== RUN: formateo + dígito verificador ======
    function formatRun(value) {
        value = value.replace(/\D/g, "");
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    function validateRun(value) {
        const numericValue = parseInt(value.replace(/\./g, ""), 10);
        if (isNaN(numericValue)) return false;
        return numericValue >= 1000000 && numericValue <= 100000000;
    }
    function calcularDV(rut) {
        let suma = 0, mul = 2;
        for (let i = rut.length - 1; i >= 0; i--) {
            suma += parseInt(rut.charAt(i), 10) * mul;
            mul = mul < 7 ? mul + 1 : 2;
        }
        const resto = 11 - (suma % 11);
        if (resto === 11) return "0";
        if (resto === 10) return "K";
        return String(resto);
    }

    const runInput = document.getElementById('run');
    const codverInput = document.getElementById('codver');
    const runError = document.getElementById('run-error');

    runInput.addEventListener('keypress', (e) => {
        if (!/[0-9]/.test(e.key)) e.preventDefault();
    });

    runInput.addEventListener('input', (e) => {
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