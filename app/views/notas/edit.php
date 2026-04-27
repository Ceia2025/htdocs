<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$notaValor = floatval($nota['nota']);
$aprobado = $notaValor >= 4.0;
$colorNota = $aprobado ? 'text-green-400' : 'text-red-400';
?>

<body class="bg-gray-900 text-white min-h-screen">
    <div class="max-w-lg mx-auto px-4 py-10">

        <!-- Header -->
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-yellow-600/20 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828
                    a4 4 0 01-1.414.828l-3 1 1-3a4 4 0 01.828-1.414z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">Editar Nota</h1>
                <p class="text-xs text-gray-500 mt-0.5">Modifica los datos de la evaluación</p>
            </div>
        </div>

        <!-- Card info alumno -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl px-5 py-4 mb-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Alumno</p>
                <p class="text-white font-semibold">
                    <?= htmlspecialchars($nota['apepat'] . ' ' . $nota['apemat'] . ', ' . $nota['alumno_nombre']) ?>
                </p>
                <p class="text-xs text-indigo-400 mt-0.5">
                    <?= htmlspecialchars($nota['asignatura_nombre']) ?> · <?= $nota['semestre'] ?>° Semestre
                </p>
            </div>

            <!-- Nota actual grande -->
            <div class="flex flex-col items-center bg-gray-900 rounded-xl px-5 py-3 border border-gray-700">
                <span class="text-xs text-gray-500 mb-1">Nota actual</span>
                <span class="text-3xl font-bold <?= $colorNota ?>">
                    <?= number_format($notaValor, 1) ?>
                </span>
                <span class="text-xs mt-1 <?= $aprobado ? 'text-green-500' : 'text-red-500' ?>">
                    <?= $aprobado ? 'Aprobado' : 'Reprobado' ?>
                </span>
            </div>
        </div>

        <!-- Formulario -->
        <form method="POST" action="index.php?action=notas_update&id=<?= $nota['id'] ?>"
            class="bg-gray-800 border border-gray-700 rounded-xl p-6 space-y-5">

            <input type="hidden" name="matricula_id" value="<?= htmlspecialchars($nota['matricula_id'] ?? '') ?>">
            <input type="hidden" name="semestre" value="<?= htmlspecialchars($nota['semestre'] ?? 1) ?>">

            <!-- Nota -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                    Nueva nota
                </label>
                <input type="number" name="nota" value="<?= htmlspecialchars($nota['nota']) ?>" step="0.1" min="1"
                    max="7" required class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-2.5 
                          text-white text-lg font-semibold text-center
                          focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <p class="text-xs text-gray-600 mt-1 text-center">Rango válido: 1.0 – 7.0</p>
            </div>

            <!-- Fecha -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                    Fecha de evaluación
                </label>
                <input type="date" name="fecha" value="<?= htmlspecialchars(substr($nota['fecha'], 0, 10)) ?>"
                    max="<?= date('Y-m-d') ?>" class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-2.5 
                          text-white focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Semestre (solo visual, el hidden lo manda) -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                    Semestre
                </label>
                <div class="flex gap-3">
                    <?php foreach ([1 => '1° Semestre', 2 => '2° Semestre'] as $num => $label): ?>
                        <?php $activo = $nota['semestre'] == $num; ?>
                        <div class="flex-1 text-center px-4 py-2.5 rounded-lg border text-sm font-semibold
                                <?= $activo
                                    ? 'bg-indigo-600/20 border-indigo-500 text-indigo-300'
                                    : 'bg-gray-900 border-gray-700 text-gray-600' ?>">
                            <?= $label ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 pt-2">
                <a href="javascript:history.back()" class="flex-1 text-center px-4 py-2.5 bg-gray-700 hover:bg-gray-600 
                      text-white font-semibold rounded-xl transition text-sm">
                    ← Volver
                </a>
                <input type="hidden" name="curso_id" value="<?= htmlspecialchars($nota['curso_id'] ?? '') ?>">
                <input type="hidden" name="anio_id" value="<?= htmlspecialchars($nota['anio_id'] ?? '') ?>">
                <input type="hidden" name="asignatura_id"
                    value="<?= htmlspecialchars($nota['asignatura_id'] ?? '') ?>">
                <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 
                           bg-yellow-600 hover:bg-yellow-500 text-white font-bold 
                           rounded-xl transition shadow-lg shadow-yellow-900/30 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar cambios
                </button>
            </div>
        </form>

    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>