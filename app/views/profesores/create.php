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

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="relative bg-gray-800 after:absolute after:inset-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-6xl px-4 py-6">
                <h1 class="text-3xl font-bold tracking-tight text-white">Nuevo Profesor</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-5xl px-4 py-8">

                <div class="bg-gray-700 p-6 rounded-2xl shadow-lg">
                    <h2 class="text-2xl font-bold text-white mb-6 text-center">Registrar Profesor</h2>

                    <form method="POST" action="index.php?action=profesor_store"
                        class="grid grid-cols-1 sm:grid-cols-2 gap-6">


                        <!-- RUN y DV -->
                        <div class="">
                            <label class="block text-sm text-gray-200 mb-1">RUN</label>

                            <div id="run-wrapper"
                                class="flex items-center bg-gray-800 border border-gray-700 rounded-xl px-3 overflow-hidden transition-all duration-300 relative">

                                <input id="run" type="text" class="w-full bg-transparent text-white py-2 outline-none"
                                    placeholder="12.345.678">

                                <div class="px-3 text-gray-400 select-none">—</div>

                                <div class="flex items-center gap-2 relative">
                                    <input id="dv" maxlength="1" readonly
                                        class="w-8 text-center bg-transparent text-white py-2 outline-none select-none">

                                    <div id="dv-tooltip"
                                        class="absolute -top-8 right-0 bg-gray-700 text-white text-xs px-2 py-1 rounded-md opacity-0 transition-all duration-300">
                                        DV calculado
                                    </div>
                                </div>

                                <div id="run-icon" class="ml-3 text-xl opacity-0 transition-all duration-300"></div>
                            </div>

                            <!-- CAMPOS OCULTOS PARA EL MODELO -->
                            <input type="hidden" name="run">
                            <input type="hidden" name="codver">

                            <p id="run-msg" class="text-xs mt-1 h-4 text-gray-400 transition-all duration-300"></p>
                        </div>





                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm text-gray-200">Nombres</label>
                            <input type="text" name="nombres"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-200">Apellido Paterno</label>
                            <input type="text" name="apepat"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-200">Apellido Materno</label>
                            <input type="text" name="apemat"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm text-gray-200">Email</label>
                            <input type="email" name="email"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label class="block text-sm text-gray-200">Teléfono</label>
                            <input type="text" name="telefono"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                        </div>

                        <!-- Tipo -->
                        <div>
                            <label class="block text-sm text-gray-200">Tipo</label>
                            <select name="tipo"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                                <option value="titular">Titular</option>
                                <option value="suplente">Suplente</option>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm text-gray-200">Estado</label>
                            <select name="estado"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>

                        <!-- Botón -->
                        <div class="sm:col-span-2 mt-4">
                            <button type="submit" class="w-full group inline-flex items-center justify-center gap-2 px-5 py-3
        bg-gradient-to-r from-indigo-500 to-purple-600
        hover:from-indigo-600 hover:to-purple-700
        text-white font-semibold rounded-xl shadow-md
        hover:shadow-lg transition-all duration-300">

                                <span>Guardar Profesor</span>


                            </button>
                        </div>





                        <script>
                            document.querySelector("form").addEventListener("submit", (e) => {

                                let runFormatted = runInput.value.replace(/\./g, "");
                                let dvCalculated = dvInput.value;

                                // Validaciones mínimas
                                if (runFormatted.length < 7 || runFormatted.length > 8) {
                                    e.preventDefault();
                                    marcarInvalido("RUN inválido");
                                    return;
                                }

                                // Asignar al formulario (la BD lo necesita)
                                document.querySelector('input[name="run"]').value = runFormatted;
                                document.querySelector('input[name="codver"]').value = dvCalculated;

                            });
                        </script>

                    </form>
                </div>

                <!-- VOLVER -->
                <div class="mt-8 flex justify-center">
                    <a href="index.php?action=profesores"
                        class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600">
                        Volver
                    </a>
                </div>

            </div>
        </main>
    </div>

    <script>

        // ⭐ Formatea el RUN (12.345.678)
        function formatearRun(value) {
            value = value.replace(/\D/g, "");
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // ⭐ Calcula DV
        function calcularDV(run) {
            let suma = 0, mult = 2;
            for (let i = run.length - 1; i >= 0; i--) {
                suma += parseInt(run[i]) * mult;
                mult = mult === 7 ? 2 : mult + 1;
            }
            const dv = 11 - (suma % 11);
            return dv === 11 ? "0" : dv === 10 ? "K" : dv.toString();
        }

        // ELEMENTOS
        const runInput = document.getElementById("run");
        const dvInput = document.getElementById("dv");
        const wrapper = document.getElementById("run-wrapper");
        const icon = document.getElementById("run-icon");
        const msg = document.getElementById("run-msg");
        const runFull = document.getElementById("run_full");

        // ⭐ Estilos dinámicos 100% Tailwind
        function marcarValido() {
            wrapper.classList.remove("border-red-500");
            wrapper.classList.add("border-green-500");

            icon.classList.remove("text-red-500");
            icon.classList.add("text-green-500");
            icon.textContent = "✔";
            icon.style.opacity = "1";

            msg.textContent = "RUN válido";
            msg.classList.remove("text-red-400");
            msg.classList.add("text-green-400");
        }

        function marcarInvalido(texto) {
            wrapper.classList.remove("border-green-500");
            wrapper.classList.add("border-red-500");

            icon.classList.remove("text-green-500");
            icon.classList.add("text-red-500");
            icon.textContent = "✖";
            icon.style.opacity = "1";

            msg.textContent = texto;
            msg.classList.remove("text-green-400");
            msg.classList.add("text-red-400");
        }

        function limpiar() {
            wrapper.classList.remove("border-green-500", "border-red-500");
            wrapper.classList.add("border-gray-700");
            icon.style.opacity = "0";
            msg.textContent = "";
        }

        // ⭐ Evento principal
        runInput.addEventListener("input", () => {
            let numeric = runInput.value.replace(/\D/g, "");

            // ❗ RUN demasiado largo
            if (numeric.length > 8) {
                marcarInvalido("RUN demasiado largo (máx 8 dígitos)");
                dvInput.value = "";
                runFull.value = "";
                return;
            }

            // Formatear visualmente
            runInput.value = formatearRun(numeric);

            if (numeric.length < 7) {
                limpiar();
                dvInput.value = "";
                runFull.value = "";
                return;
            }

            // Calcular DV
            const dv = calcularDV(numeric);
            dvInput.value = dv;

            // Guardar RUN completo
            runFull.value = `${runInput.value}-${dv}`;

            marcarValido();
        });

        // ⭐ Validar al enviar formulario
        document.querySelector("form").addEventListener("submit", (e) => {
            let numeric = runInput.value.replace(/\D/g, "");

            if (numeric.length < 7 || numeric.length > 8) {
                e.preventDefault();
                marcarInvalido("RUN inválido");
                alert("El RUN ingresado no es válido.");
            }
        });
    </script>




</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>