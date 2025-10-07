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
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Editar Inventario</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FORMULARIO -->
                <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
                    <form method="POST" action="index.php?action=inventario_update&id=<?= $item['id'] ?>"
                        class="space-y-6">

                        <!-- Nivel Educativo -->
                        <div>
                            <label for="nivel_id" class="block text-sm font-medium text-gray-200">Nivel
                                Educativo</label>
                            <select name="nivel_id" id="nivel_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Seleccione --</option>
                                <?php foreach ($niveles as $n): ?>
                                    <option value="<?= $n['id'] ?>" <?= ($n['id'] == $item['nivel_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($n['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Individualización -->
                        <div>
                            <label for="individualizacion_id"
                                class="block text-sm font-medium text-gray-200">Individualización</label>
                            <select name="individualizacion_id" id="individualizacion_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Seleccione --</option>
                                <?php foreach ($individualizaciones as $ind): ?>
                                    <option value="<?= $ind['id'] ?>" <?= ($ind['id'] == $item['individualizacion_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($ind['nombre'] . ' (' . $ind['codigo_general'] . '-' . $ind['codigo_especifico'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Categorización -->
                        <div>
                            <label for="categorizacion_id"
                                class="block text-sm font-medium text-gray-200">Categorización</label>
                            <select name="categorizacion_id" id="categorizacion_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Seleccione --</option>
                                <?php foreach ($categorizaciones as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= ($c['id'] == $item['categorizacion_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Cantidad -->
                        <div>
                            <label for="cantidad" class="block text-sm font-medium text-gray-200">Cantidad</label>
                            <input type="number" name="cantidad" id="cantidad" min="1"
                                value="<?= htmlspecialchars($item['cantidad']) ?>" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="estado_id" class="block text-sm font-medium text-gray-200">Estado de
                                Conservación</label>
                            <select name="estado_id" id="estado_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <?php foreach ($estados as $e): ?>
                                    <option value="<?= $e['id'] ?>" <?= ($e['id'] == $item['estado_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($e['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Lugar -->
                        <div>
                            <label for="lugar_id" class="block text-sm font-medium text-gray-200">Lugar Físico</label>
                            <select name="lugar_id" id="lugar_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <?php foreach ($lugares as $l): ?>
                                    <option value="<?= $l['id'] ?>" <?= ($l['id'] == $item['lugar_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($l['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Procedencia -->
                        <div>
                            <label for="procedencia_id"
                                class="block text-sm font-medium text-gray-200">Procedencia</label>
                            <select name="procedencia_id" id="procedencia_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <?php foreach ($procedencias as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= ($p['id'] == $item['procedencia_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['tipo'] . " - " . $p['donador_fondo'] . " - " . $p['fecha_adquisicion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>



                        <!-- Botones -->
                        <div class="flex space-x-4">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                                Actualizar
                            </button>
                            <a href="index.php?action=inventario_index"
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

<?php include __DIR__ . "/../layout/footer.php"; ?>