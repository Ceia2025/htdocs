<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<?php if (!empty($_GET['guardado'])): ?>
    <div id="toast-ok" class="fixed top-6 right-6 z-50 flex items-center gap-3 bg-gray-900 border border-green-600 
       text-green-300 rounded-2xl px-5 py-4 shadow-2xl transition-all duration-500 max-w-sm">
        <span class="text-2xl">✅</span>
        <p class="text-sm font-medium">Números de lista guardados correctamente.</p>
        <button onclick="cerrarToast()" class="ml-auto text-gray-500 hover:text-white text-lg">✕</button>
    </div>
    <script>
        setTimeout(() => cerrarToast(), 4000);
        function cerrarToast() {
            const t = document.getElementById('toast-ok');
            if (!t) return;
            t.style.opacity = '0';
            t.style.transform = 'translateY(-10px)';
            setTimeout(() => t.remove(), 400);
        }
    </script>
<?php endif ?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <header class="bg-gray-800 border-b border-white/10">
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">Matrículas</p>
                    <h1 class="text-2xl font-bold text-white">Números de Lista</h1>
                    <p class="text-sm text-gray-400 mt-0.5">
                        <?= htmlspecialchars($curso['nombre'] ?? '') ?> —
                        <?= htmlspecialchars($anio['anio'] ?? '') ?>
                    </p>
                </div>
                <a href="index.php?action=matriculas" class="flex items-center gap-2 text-sm text-gray-400 hover:text-white border border-gray-600 
                       hover:border-gray-400 px-4 py-2 rounded-lg transition">
                    ⬅️ Volver
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">

            <?php if (empty($alumnos)): ?>
                <div class="text-center py-16 text-gray-500">
                    <p class="text-5xl mb-4">📋</p>
                    <p>No hay alumnos matriculados en este curso.</p>
                </div>
            <?php else: ?>

                <form method="POST" action="index.php?action=matricula_guardar_lista">
                    <input type="hidden" name="curso_id" value="<?= htmlspecialchars($_GET['curso_id']) ?>">
                    <input type="hidden" name="anio_id" value="<?= htmlspecialchars($_GET['anio_id']) ?>">

                    <div class="bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden shadow mb-6">

                        <!-- Cabecera -->
                        <div class="grid grid-cols-[60px_1fr_120px] items-center px-5 py-3 
                                bg-gray-900/50 border-b border-gray-700">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">#</span>
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Alumno</span>
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">N°
                                Lista</span>
                        </div>

                        <!-- Filas -->
                        <?php foreach ($alumnos as $i => $alumno): ?>
                            <div class="grid grid-cols-[60px_1fr_120px] items-center px-5 py-3.5 
                                    border-t border-gray-700/60 hover:bg-gray-700/20 transition">

                                <!-- Número actual o posición -->
                                <div class="text-center">
                                    <span class="text-sm font-bold 
                                    <?= $alumno['numero_lista'] ? 'text-indigo-400' : 'text-gray-600' ?>">
                                        <?= $alumno['numero_lista'] ?? '—' ?>
                                    </span>
                                </div>

                                <!-- Nombre -->
                                <div>
                                    <p class="text-sm font-semibold text-white capitalize">
                                        <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500 capitalize">
                                        <?= htmlspecialchars($alumno['nombre']) ?>
                                        <span class="font-mono ml-1">·
                                            <?= htmlspecialchars($alumno['run']) ?>
                                        </span>
                                    </p>
                                </div>

                                <!-- Input número de lista -->
                                <div class="flex justify-center">
                                    <input type="number" name="numero_lista[<?= $alumno['matricula_id'] ?>]"
                                        value="<?= htmlspecialchars($alumno['numero_lista'] ?? '') ?>" min="1" max="99"
                                        placeholder="—" class="w-20 text-center bg-gray-900 border border-gray-600 rounded-lg 
                                           text-white text-sm px-2 py-1.5 focus:outline-none 
                                           focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-between gap-4">
                        <a href="index.php?action=matriculas" class="flex items-center gap-2 bg-gray-700 hover:bg-gray-600 text-white 
                               font-semibold px-6 py-3 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 
                               text-white font-bold px-8 py-3 rounded-xl transition shadow-lg">
                            💾 Guardar Números de Lista
                        </button>
                    </div>
                </form>

            <?php endif ?>
        </main>
    </div>
</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>