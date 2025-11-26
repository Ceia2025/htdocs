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

                        <!-- RUN + DV juntos -->
                        <div class="col-span-2">
                            <label class="block text-sm text-gray-200">RUN</label>

                            <div class="mt-2 flex rounded-lg bg-gray-800 border border-gray-700 overflow-hidden">

                                <!-- RUN (solo números, formateado automáticamente) -->
                                <input type="text" id="run" name="run"
                                    class="w-full bg-gray-800 text-white px-3 py-2 outline-none"
                                    placeholder="18.362.031">

                                <!-- DV (generado automáticamente) -->
                                <input type="text" id="dv" name="codver"
                                    class="w-20 text-center bg-gray-900 text-white border-l border-gray-700 px-3 py-2"
                                    readonly>
                            </div>
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
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg shadow">
                                Guardar Profesor
                            </button>
                        </div>

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
        function formatearRun(value) {
            value = value.replace(/\D/g, "");
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function calcularDV(run) {
            let suma = 0;
            let multiplicador = 2;

            for (let i = run.length - 1; i >= 0; i--) {
                suma += parseInt(run[i]) * multiplicador;
                multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
            }

            const resto = suma % 11;
            const dv = 11 - resto;

            if (dv === 11) return "0";
            if (dv === 10) return "K";
            return dv.toString();
        }

        document.getElementById("run").addEventListener("input", function () {
            let valor = this.value.replace(/\D/g, "");

            // Formato visible
            this.value = formatearRun(valor);

            // Cálculo DV
            document.getElementById("dv").value =
                valor.length > 0 ? calcularDV(valor) : "";
        });
    </script>

</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>