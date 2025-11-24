<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];

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

                <form method="POST" action="index.php?action=profesor_store" class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- RUN -->
                    <div>
                        <label class="block text-sm text-gray-200">RUN</label>
                        <input type="text" name="run"
                               class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                    </div>

                    <!-- DV -->
                    <div>
                        <label class="block text-sm text-gray-200">Dígito Verificador</label>
                        <input type="text" name="codver"
                               class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2" maxlength="1">
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
                    ⬅ Volver
                </a>
            </div>

        </div>
    </main>
</div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>
