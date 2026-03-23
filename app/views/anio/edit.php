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


<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Editar Año</h1>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
                <form method="post" action="index.php?action=anio_update&id=<?= $anio['id'] ?>" class="space-y-6">

                    <!-- Datos generales -->
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 space-y-4">
                        <h2 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">Datos Generales</h2>

                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-1">Año</label>
                            <input type="number" name="anio" required min="2020" max="2099"
                                value="<?= htmlspecialchars($anio['anio']) ?>" class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 
                                   px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-1">
                                Descripción
                                <span class="text-gray-500 font-normal text-xs ml-1">(opcional)</span>
                            </label>
                            <textarea name="descripcion" rows="2"
                                class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 
                                   px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition resize-none"><?= htmlspecialchars($anio['descripcion'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Fechas de semestres -->
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 space-y-4">
                        <h2 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">
                            📅 Fechas del Año Escolar
                        </h2>

                        <?php if (empty($anio['sem1_inicio'])): ?>
                            <div
                                class="flex items-center gap-2 bg-yellow-900/30 border border-yellow-700 rounded-xl px-4 py-3">
                                <span class="text-yellow-400">⚠️</span>
                                <p class="text-yellow-300 text-sm">Este año no tiene fechas configuradas. El libro de clases
                                    no funcionará hasta que las agregues.</p>
                            </div>
                        <?php endif ?>

                        <!-- 1° Semestre -->
                        <div>
                            <p class="text-xs text-indigo-400 uppercase tracking-wider font-semibold mb-2">1° Semestre
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1">Fecha de Inicio</label>
                                    <input type="date" name="sem1_inicio"
                                        value="<?= htmlspecialchars($anio['sem1_inicio'] ?? '') ?>" class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 
                                           px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1">Fecha de Término</label>
                                    <input type="date" name="sem1_fin"
                                        value="<?= htmlspecialchars($anio['sem1_fin'] ?? '') ?>" class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 
                                           px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                                </div>
                            </div>
                        </div>

                        <!-- 2° Semestre -->
                        <div>
                            <p class="text-xs text-indigo-400 uppercase tracking-wider font-semibold mb-2">2° Semestre
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1">Fecha de Inicio</label>
                                    <input type="date" name="sem2_inicio"
                                        value="<?= htmlspecialchars($anio['sem2_inicio'] ?? '') ?>" class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 
                                           px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1">Fecha de Término</label>
                                    <input type="date" name="sem2_fin"
                                        value="<?= htmlspecialchars($anio['sem2_fin'] ?? '') ?>" class="w-full rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 
                                           px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4">
                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 
                               text-white font-semibold rounded-xl py-3 shadow-lg transition">
                            Guardar Cambios
                        </button>
                        <a href="index.php?action=anios"
                            class="w-full text-center bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl py-3 transition">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>