<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user   = $_SESSION['user'];
$nombre = $user['nombre'];
$rol    = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="h-full bg-gray-900">
<div class="min-h-full">

    <!-- HEADER -->
    <header class="bg-gray-800 border-b border-white/10">
        <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">Asistencia</p>
                <h1 class="text-2xl font-bold text-white">Seleccionar Curso</h1>
                <p class="text-sm text-gray-400 mt-0.5">Elige un curso para tomar asistencia, ver el resumen o el libro de clases</p>
            </div>
            <a href="index.php?action=dashboard"
               class="flex items-center gap-2 text-sm text-gray-400 hover:text-white border border-gray-600 hover:border-gray-400 px-4 py-2 rounded-lg transition">
                ⬅️ Dashboard
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

        <?php if (empty($cursos)): ?>
            <div class="text-center py-20 text-gray-500">
                <p class="text-5xl mb-4">📋</p>
                <p class="text-lg font-medium">No hay cursos disponibles para este año.</p>
            </div>
        <?php else: ?>

            <!-- Contador -->
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-400">
                    <span class="text-white font-semibold"><?= count($cursos) ?></span> cursos disponibles
                </p>
            </div>

            <!-- Grid de cursos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($cursos as $curso): ?>
                    <div class="group bg-gray-800 border border-gray-700 hover:border-indigo-500
                                rounded-2xl overflow-hidden shadow transition-all duration-200
                                hover:shadow-indigo-500/10 hover:shadow-lg">

                        <!-- Cabecera de la tarjeta -->
                        <div class="px-5 py-4 border-b border-gray-700 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600/20 border border-indigo-600/30
                                        flex items-center justify-center flex-shrink-0">
                                <span class="text-indigo-400 text-lg">🎓</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-white font-bold text-base truncate">
                                    <?= htmlspecialchars($curso['nombre']) ?>
                                </h3>
                                <?php if (isset($ultimasFechas[$curso['id']])): ?>
                                    <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Última lista:
                                        <span class="text-white font-medium">
                                            <?= date('d/m/Y', strtotime($ultimasFechas[$curso['id']])) ?>
                                        </span>
                                    </p>
                                <?php else: ?>
                                    <p class="text-xs text-gray-600 mt-0.5">Sin asistencia registrada</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Acciones: 3 botones -->
                        <div class="px-5 py-4 grid grid-cols-3 gap-2">

                            <!-- Tomar asistencia -->
                            <a href="index.php?action=form_asistencia_masiva&curso_id=<?= $curso['id'] ?>&anio_id=<?= $anioId ?>"
                               class="flex flex-col items-center gap-1.5
                                      bg-indigo-600/10 hover:bg-indigo-600/20
                                      border border-indigo-600/30 hover:border-indigo-500
                                      text-indigo-300 hover:text-indigo-200
                                      px-2 py-3 rounded-xl transition text-center">
                                <span class="text-xl">✏️</span>
                                <span class="text-xs font-semibold leading-tight">Tomar Asistencia</span>
                            </a>

                            <!-- Ver resumen -->
                            <a href="index.php?action=resumen_curso&curso_id=<?= $curso['id'] ?>&anio_id=<?= $anioId ?>"
                               class="flex flex-col items-center gap-1.5
                                      bg-green-600/10 hover:bg-green-600/20
                                      border border-green-600/30 hover:border-green-500
                                      text-green-300 hover:text-green-200
                                      px-2 py-3 rounded-xl transition text-center">
                                <span class="text-xl">📊</span>
                                <span class="text-xs font-semibold leading-tight">Ver Resumen</span>
                            </a>

                            <!-- Libro de clases -->
                            <a href="index.php?action=libro_clases&curso_id=<?= $curso['id'] ?>&anio_id=<?= $anioId ?>"
                               class="flex flex-col items-center gap-1.5
                                      bg-amber-600/10 hover:bg-amber-600/20
                                      border border-amber-600/30 hover:border-amber-500
                                      text-amber-300 hover:text-amber-200
                                      px-2 py-3 rounded-xl transition text-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="text-xs font-semibold leading-tight">Libro de Clases</span>
                            </a>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </main>
</div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>