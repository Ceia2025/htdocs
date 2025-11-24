<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

function sel($a, $b) { return ((string)$a === (string)$b) ? 'selected' : ''; }
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
                <form method="POST" action="index.php?action=profesor_update&id=<?= $profesor['id'] ?>" class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- RUN -->
                    <div>
                        <label class="block text-sm text-gray-200">RUN</label>
                        <input type="text" name="run" value="<?= htmlspecialchars($profesor['run']) ?>"
                               class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                    </div>

                    <!-- DV -->
                    <div>
                        <label class="block text-sm text-gray-200">Dígito Verificador</label>
                        <input type="text" name="codver" maxlength="1"
                               value="<?= htmlspecialchars($profesor['codver']) ?>"
                               class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                    </div>

                    <!-- Nombres -->
                    <div>
                        <label class="block text-sm text-gray-200">Nombres</label>
                        <input type="text" name="nombres"
                               value="<?= htmlspecialchars($profesor['nombres']) ?>"
                               class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-200">Apellido Paterno</label>
                        <input type="text" name="apepat"
                               value="<?= htmlspecialchars($profesor['apepat']) ?>"
                               class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-200">Apellido Materno</label>
                        <input type="text" name="apemat"
                               value="<?= htmlspecialchars($profesor['apemat']) ?>"
                               class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm text-gray-200">Email</label>
                        <input type="email" name="email"
                               value="<?= htmlspecialchars($profesor['email']) ?>"
                               class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                    </div>

                    <!-- Telefono -->
                    <div>
                        <label class="block text-sm text-gray-200">Teléfono</label>
                        <input type="text" name="telefono"
                               value="<?= htmlspecialchars($profesor['telefono']) ?>"
                               class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm text-gray-200">Tipo</label>
                        <select name="tipo"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="titular" <?= sel($profesor['tipo'], 'titular') ?>>Titular</option>
                            <option value="suplente" <?= sel($profesor['tipo'], 'suplente') ?>>Suplente</option>
                        </select>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm text-gray-200">Estado</label>
                        <select name="estado"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="activo" <?= sel($profesor['estado'], 'activo') ?>>Activo</option>
                            <option value="inactivo" <?= sel($profesor['estado'], 'inactivo') ?>>Inactivo</option>
                        </select>
                    </div>

                    <!-- BOTONES -->
                    <div class="sm:col-span-2 flex justify-between pt-4">
                        <a href="index.php?action=profesores"
                           class="px-5 py-2 rounded-lg bg-gray-600 hover:bg-gray-500 text-white">⬅ Volver</a>

                        <button type="submit"
                                class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">
                            Guardar Cambios
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </main>
</div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>
