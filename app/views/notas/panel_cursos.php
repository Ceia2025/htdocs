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
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-1">Notas</p>
                <h1 class="text-2xl font-bold text-white">Panel de Notas</h1>
            </div>

            <!-- Selector de año -->
            <form method="GET" action="index.php">
                <input type="hidden" name="action" value="notas_panel">
                <select name="anio_id" onchange="this.form.submit()"
                    class="bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6">

        <?php if (empty($cursos)): ?>
            <div class="bg-gray-800 border border-gray-700 rounded-xl px-6 py-12 text-center">
                <p class="text-gray-400 font-medium">
                    <?= $esTodos ? 'No hay cursos registrados para este año.' : 'No tienes cursos asignados para este año.' ?>
                </p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($cursos as $c): ?>
                    <a href="index.php?action=notas_panel_asignaturas&curso_id=<?= $c['curso_id'] ?>&anio_id=<?= $anioId ?>"
                       class="bg-gray-800 border border-gray-700 hover:border-indigo-500 rounded-xl px-5 py-5 
                              transition group flex flex-col gap-3">

                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-white group-hover:text-indigo-400 transition">
                                <?= htmlspecialchars($c['curso'] ?? $c['curso_nombre'] ?? '—') ?>
                            </span>
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-indigo-400 transition" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>

                        <?php if ($esTodos && !empty($c['docente_nombre'])): ?>
                            <p class="text-xs text-gray-500">
                                Profesor jefe: 
                                <span class="text-gray-300">
                                    <?= htmlspecialchars($c['docente_nombre'] . ' ' . $c['ape_paterno']) ?>
                                </span>
                            </p>
                        <?php elseif ($esTodos): ?>
                            <p class="text-xs text-yellow-600 italic">Sin profesor jefe asignado</p>
                        <?php endif; ?>

                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>
</div>
</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>