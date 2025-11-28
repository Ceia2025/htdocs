<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

function sel($a, $b)
{
    return ((string) $a === (string) $b) ? 'selected' : '';
}
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="relative bg-gray-800 after:absolute after:inset-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-6xl px-4 py-6">
                <h1 class="text-3xl font-bold tracking-tight text-white">Editar Profesor</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-5xl px-4 py-8">

                <div class="bg-gray-700 p-6 rounded-2xl shadow-lg">



                    <form method="POST" action="index.php?action=profesor_update&id=<?= $profesor['id'] ?>"
                        class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- Campo oculto con RUN final sin puntos -->
                        <input type="hidden" name="run" id="run_final">
                        <input type="hidden" name="codver" id="codver_final">


                        <!-- RUN + DV -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm text-gray-200 mb-1">RUN</label>

                            <div id="rutBox"
                                class="flex items-center bg-gray-800 border border-gray-700 rounded-2xl px-4 py-3 transition-all duration-300 gap-2">

                                <?php
                                // Tomamos RUN limpio desde DB
                                $runDB = preg_replace('/\D/', '', $profesor['run']);

                                // Formateo visible con puntos, SIN DV
                                $runVisible = number_format($runDB, 0, '', '.');
                                ?>

                                <!-- RUN visible -->
                                <input id="run_input" type="text" value="<?= $runVisible ?>"
                                    class="w-full bg-transparent text-white focus:outline-none" maxlength="10">

                                <!-- Separador -->
                                <span class="text-gray-400 select-none">—</span>

                                <!-- DV real desde DB -->
                                <input id="dv_input" type="text" value="<?= strtoupper($profesor['codver']) ?>"
                                    class="w-10 text-center bg-transparent text-white focus:outline-none" maxlength="1">

                                <!-- Iconos -->
                                <span id="rutIcon" class="text-lg ml-2"></span>
                            </div>

                            <p id="rutMessage" class="text-xs mt-1"></p>
                        </div>

                        <!-- NOMBRES -->
                        <div>
                            <label class="block text-sm text-gray-200">Nombres</label>
                            <input type="text" name="nombres" value="<?= htmlspecialchars($profesor['nombres']) ?>"
                                class="mt-2 w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-2">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-200">Apellido Paterno</label>
                            <input type="text" name="apepat" value="<?= htmlspecialchars($profesor['apepat']) ?>"
                                class="mt-2 w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-2">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-200">Apellido Materno</label>
                            <input type="text" name="apemat" value="<?= htmlspecialchars($profesor['apemat']) ?>"
                                class="mt-2 w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-2">
                        </div>

                        <!-- EMAIL -->
                        <div>
                            <label class="block text-sm text-gray-200">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($profesor['email']) ?>"
                                class="mt-2 w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-2">
                        </div>

                        <!-- TELEFONO -->
                        <div>
                            <label class="block text-sm text-gray-200">Teléfono</label>
                            <input type="text" name="telefono" value="<?= htmlspecialchars($profesor['telefono']) ?>"
                                class="mt-2 w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-2">
                        </div>

                        <!-- TIPO -->
                        <div>
                            <label class="block text-sm text-gray-200">Tipo</label>
                            <select name="tipo"
                                class="mt-2 w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-2">
                                <option value="titular" <?= sel($profesor['tipo'], 'titular') ?>>Titular</option>
                                <option value="suplente" <?= sel($profesor['tipo'], 'suplente') ?>>Suplente</option>
                            </select>
                        </div>

                        <!-- ESTADO -->
                        <div>
                            <label class="block text-sm text-gray-200">Estado</label>
                            <select name="estado"
                                class="mt-2 w-full rounded-xl bg-gray-800 border border-gray-700 text-white px-4 py-2">
                                <option value="activo" <?= sel($profesor['estado'], 'activo') ?>>Activo</option>
                                <option value="inactivo" <?= sel($profesor['estado'], 'inactivo') ?>>Inactivo</option>
                            </select>
                        </div>

                        <!-- BOTONES -->
                        <div class="sm:col-span-2 flex justify-between pt-4">
                            <a href="index.php?action=profesores"
                                class="px-5 py-2 rounded-lg bg-gray-600 hover:bg-gray-500 text-white">
                                Volver
                            </a>

                            <button id="submitBtn" type="submit"
                                class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>




                </div>

            </div>
        </main>
    </div>

    <script>
        function calcularDV(rut) {
            let suma = 0, multiplo = 2;
            for (let i = rut.length - 1; i >= 0; i--) {
                suma += multiplo * rut[i];
                multiplo = multiplo === 7 ? 2 : multiplo + 1;
            }
            const dv = 11 - (suma % 11);
            if (dv === 11) return "0";
            if (dv === 10) return "K";
            return dv.toString();
        }

        const runInput = document.getElementById("run_input");
        const dvInput = document.getElementById("dv_input");
        const runFinal = document.getElementById("run_final");
        const rutBox = document.getElementById("rutBox");
        const rutIcon = document.getElementById("rutIcon");
        const rutMessage = document.getElementById("rutMessage");
        const submitBtn = document.getElementById("submitBtn");

        function formatear(num) {
            return num.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function validar() {
            let raw = runInput.value.replace(/\D/g, "");

            if (raw.length < 7 || raw.length > 10) {
                rutBox.classList.remove("border-green-500");
                rutBox.classList.add("border-red-500");
                rutIcon.innerHTML = "❌";
                rutIcon.className = "text-red-500";
                rutMessage.textContent = "RUN inválido";
                rutMessage.className = "text-xs text-red-400";
                submitBtn.disabled = true;
                runFinal.value = "";
                return;
            }

            const dv = calcularDV(raw);
            dvInput.value = dv;

            rutBox.classList.remove("border-red-500");
            rutBox.classList.add("border-green-500");
            rutIcon.innerHTML = "✔";
            rutIcon.className = "text-green-500";

            rutMessage.textContent = "RUN válido";
            rutMessage.className = "text-xs text-green-400";

            runFinal.value = raw;
            document.getElementById("codver_final").value = dv;
            submitBtn.disabled = false;
        }

        runInput.addEventListener("input", () => {
            runInput.value = formatear(runInput.value);
            validar();
        });

        dvInput.addEventListener("input", validar);
        window.addEventListener("load", validar);

    </script>


</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>