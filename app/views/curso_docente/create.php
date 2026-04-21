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

<main class="min-h-screen bg-gray-900">

    <header class="bg-gray-800 border-b border-white/10">
        <div class="mx-auto max-w-xl px-4 py-6 sm:px-6">
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">Gestión Académica</p>
            <h1 class="text-2xl font-bold text-white">Asignar Profesor Jefe</h1>
        </div>
    </header>

    <div class="mx-auto max-w-xl px-4 py-8 sm:px-6">
        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6">

            <?php if ($asignacionActual): ?>
                <div class="mb-5 flex items-center gap-3 bg-amber-900/30 border border-amber-700/50
                        text-amber-300 px-4 py-3 rounded-xl text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Este curso ya tiene docente asignado. Al guardar se reemplazará.
                </div>
            <?php endif ?>

            <form method="POST" action="index.php?action=curso_docente_store" class="space-y-5">

                <input type="hidden" name="anio_id" value="<?= $anioId ?>">

                <!-- Año (informativo) -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                        Año lectivo
                    </label>
                    <select name="anio_id_display" disabled class="w-full bg-gray-900/50 text-gray-400 text-sm border border-gray-700
                                   rounded-xl px-3 py-2.5 cursor-not-allowed">
                        <?php foreach ($anios as $a): ?>
                            <option <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                                <?= $a['anio'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Curso -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                        Curso
                    </label>
                    <select name="curso_id" required class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-xl
                                   px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Selecciona un curso…</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($cursoId && $cursoId == $c['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nombre']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Docente -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                        Profesor Jefe
                    </label>
                    <?php if (empty($docentes)): ?>
                        <p class="text-sm text-red-400 bg-red-900/30 border border-red-700/50
                                  rounded-xl px-4 py-3">
                            No hay usuarios con rol Docente registrados en el sistema.
                        </p>
                    <?php else: ?>
                        <select name="docente_id" required class="w-full bg-gray-900 text-white text-sm border border-gray-600 rounded-xl
                                   px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Selecciona un docente…</option>
                            <?php foreach ($docentes as $d): ?>
                                <option value="<?= $d['id'] ?>" <?= ($asignacionActual && $asignacionActual['id'] == $d['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(
                                        $d['ape_paterno'] . ' ' .
                                        ($d['ape_materno'] ?? '') . ', ' .
                                        $d['nombre']
                                    ) ?>
                                    <?php if ($d['email']): ?>
                                        — <?= htmlspecialchars($d['email']) ?>
                                    <?php endif ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    <?php endif ?>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" <?= empty($docentes) ? 'disabled' : '' ?> class="flex-1 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-40
                                   disabled:cursor-not-allowed text-white font-bold
                                   py-2.5 rounded-xl transition active:scale-95">
                        Guardar asignación
                    </button>
                    <a href="index.php?action=curso_docente&anio_id=<?= $anioId ?>" class="flex-1 text-center bg-gray-700 hover:bg-gray-600 text-gray-300
                              font-semibold py-2.5 rounded-xl transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>