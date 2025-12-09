<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth(); // obliga a tener sesión iniciada

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

// Incluir layout
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="h-full bg-gray-900">
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Nuevo Registro de Inventario</h1>
        </div>
    </header>

    <main>
        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
                <form method="POST" action="index.php?action=inventario_store">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Nivel Educativo -->
                            <div>
                                <label for="nivel_id" class="block text-sm font-medium text-gray-200">Nivel Educativo</label>
                                <select name="nivel_id" id="nivel_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($niveles as $nivel): ?>
                                <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Individualización -->
                        <div>
                            <label for="nivel_id" class="block text-sm font-medium text-gray-200">Individualizacion</label>
                            <select name="individualizacion_id" id="individualizacion_id"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required>
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($individualizaciones as $ind): ?>
                                <option value="<?= $ind['id'] ?>">
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
                            <?php foreach ($categorizaciones as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['descripcion']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                    <!-- Cantidad -->
                    <input type="hidden" name="cantidad" value="1">

                    <!-- Estado -->
                    <div>
                        <label for="estado_id" class="block text-sm font-medium text-gray-200">Estado de
                            Conservación</label>
                            <select name="estado_id" id="estado_id" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?= $estado['id'] ?>"><?= htmlspecialchars($estado['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Lugar Físico -->
                        <div>
                            <label for="lugar_id" class="block text-sm font-medium text-gray-200">Lugar Físico</label>
                            <select name="lugar_id" id="lugar_id" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($lugares as $lugar): ?>
                                <option value="<?= $lugar['id'] ?>"><?= htmlspecialchars($lugar['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Procedencia -->
                        <div>
                            <label for="procedencia_id" class="block text-sm font-medium text-gray-200">Procedencia</label>
                            <select name="procedencia_id" id="procedencia_id" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($procedencias as $proc): ?>
                                <option value="<?= $proc['id'] ?>">
                                    <?= htmlspecialchars($proc['tipo'] . " - " . $proc['donador_fondo'] . " - " . $proc['fecha_adquisicion']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        
                    </div>

                    <!-- Botones -->
                    <div class="flex space-x-4 mt-8">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                            Guardar
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
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>