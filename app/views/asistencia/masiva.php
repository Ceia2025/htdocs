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

        <header class="bg-gray-800 border-b border-white/10">
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-400 mb-1">Asistencia</p>
                    <h1 class="text-2xl font-bold text-white">Tomar Asistencia</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Curso:
                        <?= htmlspecialchars($curso['nombre'] ?? '') ?>
                    </p>
                </div>
                <a href="index.php?action=libro_clases&curso_id=<?= $curso['id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
                    class="flex items-center gap-2 text-sm text-gray-400 hover:text-white border border-gray-600 hover:border-gray-400 px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Ver libro de clases
                </a>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

                <form method="POST" action="index.php?action=guardar_asistencia_masiva">
                    <input type="hidden" name="curso_id" value="<?= $_GET['curso_id'] ?>">
                    <input type="hidden" name="anio_id" value="<?= $_GET['anio_id'] ?>">

                    <!-- Card fecha + acciones -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden mb-5">

                        <!-- Fila: fecha y botones de selección -->
                        <div class="flex flex-wrap items-center gap-3 px-5 py-4 border-b border-gray-700">

                            <!-- Fecha -->
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <label class="text-sm font-medium text-gray-300">Fecha</label>
                                <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>"
                                    class="bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer">
                            </div>

                            <!-- Divisor -->
                            <div class="hidden sm:block w-px h-5 bg-gray-600"></div>

                            <!-- Botones selección masiva -->
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 font-medium">Selección:</span>
                                <button type="button" onclick="marcarTodos(true)"
                                    class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-green-900/40 text-green-400 border border-green-700/50 hover:bg-green-900/70 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Todos presentes
                                </button>
                                <button type="button" onclick="marcarTodos(false)"
                                    class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-red-900/40 text-red-400 border border-red-700/50 hover:bg-red-900/70 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Todos ausentes
                                </button>
                            </div>

                            <!-- Contador -->
                            <div class="ml-auto">
                                <span id="contador"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-900 border border-gray-600 text-gray-400">
                                    0 / 0 presentes
                                </span>
                            </div>
                        </div>

                        <!-- Lista de alumnos -->
                        <?php if (empty($alumnos)): ?>
                            <div class="px-5 py-8 text-center">
                                <svg class="w-10 h-10 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                </svg>
                                <p class="text-gray-400 font-medium">No hay alumnos matriculados en este curso.</p>
                            </div>
                        <?php else: ?>
                            <!-- Cabecera tabla -->
                            <div class="grid grid-cols-[1fr_auto] items-center px-5 py-2.5 bg-gray-900/50">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Alumno</span>
                                <span
                                    class="text-xs font-semibold uppercase tracking-wider text-gray-500 w-20 text-center">Presente</span>
                            </div>

                            <!-- Filas -->
                            <?php foreach ($alumnos as $i => $alumno): ?>
                                <label
                                    class="grid grid-cols-[1fr_auto] items-center px-5 py-3.5 border-t border-gray-700/60 hover:bg-gray-700/30 cursor-pointer transition group">
                                    <div>
                                        <span class="text-sm font-semibold text-white">
                                            <?= htmlspecialchars($alumno['apepat'] . " " . $alumno['apemat']) ?>
                                        </span>
                                        <span class="text-sm text-gray-400">,
                                            <?= htmlspecialchars($alumno['nombre']) ?>
                                        </span>
                                    </div>
                                    <div class="w-20 flex justify-center">
                                        <input type="checkbox" class="presente w-5 h-5 rounded accent-green-500 cursor-pointer"
                                            name="presentes[]" value="<?= $alumno['matricula_id'] ?>" checked
                                            onchange="actualizarContador()">
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Botón guardar -->
                    <?php if (!empty($alumnos)): ?>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="flex items-center gap-2 bg-green-600 hover:bg-green-500 active:bg-green-700 text-white font-bold px-8 py-3 rounded-xl transition shadow-lg shadow-green-900/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Guardar Asistencia
                            </button>
                        </div>
                    <?php endif; ?>

                </form>
            </div>
        </main>
    </div>
</body>

<script>
    const totalAlumnos = <?= count($alumnos ?? []) ?>;

    function actualizarContador() {
        const presentes = document.querySelectorAll('.presente:checked').length;
        const el = document.getElementById('contador');
        el.textContent = `${presentes} / ${totalAlumnos} presentes`;

        if (presentes === totalAlumnos) {
            el.className = 'text-xs font-semibold px-3 py-1.5 rounded-full bg-green-900/40 border border-green-700/50 text-green-400';
        } else if (presentes === 0) {
            el.className = 'text-xs font-semibold px-3 py-1.5 rounded-full bg-red-900/40 border border-red-700/50 text-red-400';
        } else {
            el.className = 'text-xs font-semibold px-3 py-1.5 rounded-full bg-yellow-900/40 border border-yellow-700/50 text-yellow-400';
        }
    }

    function marcarTodos(estado) {
        document.querySelectorAll('.presente').forEach(el => el.checked = estado);
        actualizarContador();
    }

    // Inicializar contador
    document.addEventListener('DOMContentLoaded', actualizarContador);
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>