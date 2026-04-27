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
        <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-1">Notas</p>
                <h1 class="text-2xl font-bold text-white"><?= htmlspecialchars($curso['nombre']) ?></h1>
                <p class="text-sm text-gray-400 mt-0.5">Año <?= htmlspecialchars($anio['anio']) ?></p>
            </div>
            <a href="index.php?action=notas_panel&anio_id=<?= $anioId ?>"
               class="text-sm text-gray-400 hover:text-white border border-gray-600 hover:border-gray-400 px-4 py-2 rounded-lg transition">
                ← Volver
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6">

        <!-- Selector de semestre -->
        <div class="flex items-center gap-3 mb-6">
            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Semestre:</span>
            <?php foreach ([1 => '1° Semestre', 2 => '2° Semestre'] as $num => $label): ?>
                <a href="index.php?action=notas_panel_asignaturas&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&semestre=<?= $num ?>"
                   class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition
                          <?= $semestre == $num 
                              ? 'bg-indigo-600 border-indigo-500 text-white' 
                              : 'bg-gray-900 border-gray-600 text-gray-400 hover:border-gray-400' ?>">
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($asignaturas)): ?>
            <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-12 text-center">
                <p class="text-gray-400">No hay asignaturas registradas para este curso.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($asignaturas as $asig): ?>
                    <a href="index.php?action=notas_panel_asignatura&curso_id=<?= $cursoId ?>&anio_id=<?= $anioId ?>&asignatura_id=<?= $asig['id'] ?>&semestre=<?= $semestre ?>"
                       class="bg-gray-800 border border-gray-700 hover:border-indigo-500 rounded-xl px-5 py-4 
                              transition group flex items-center justify-between">
                        <span class="text-sm font-semibold text-white group-hover:text-indigo-400 transition">
                            <?= htmlspecialchars($asig['nombre']) ?>
                        </span>
                        <svg class="w-4 h-4 text-gray-600 group-hover:text-indigo-400 transition" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>
</div>
</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>