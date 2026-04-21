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
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6">
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">Gestión Académica</p>
            <h1 class="text-2xl font-bold text-white">Profesor Jefe por Curso</h1>
            <p class="text-sm text-gray-400 mt-0.5">Asignación docente para el año lectivo</p>
        </div>
    </header>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">

        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">

            <!-- Filtro año -->
            <form method="GET" action="index.php" class="flex items-center gap-2">
                <input type="hidden" name="action" value="curso_docente">
                <select name="anio_id" onchange="this.form.submit()" class="bg-gray-800 text-white text-sm border border-gray-600 rounded-lg
                               px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </form>

            <a href="index.php?action=curso_docente_create&anio_id=<?= $anioId ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500
                      text-white font-bold rounded-xl transition active:scale-95 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Asignar Docente
            </a>
        </div>

        <!-- Tabla -->
        <div class="bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700 text-left">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Curso</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Profesor Jefe
                        </th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    <?php foreach ($relaciones as $r): ?>
                        <tr class="hover:bg-gray-700/30 transition">

                            <td class="px-5 py-3.5 font-semibold text-white">
                                <?= htmlspecialchars($r['curso']) ?>
                            </td>

                            <td class="px-5 py-3.5">
                                <?php if ($r['docente_id']): ?>
                                    <span class="text-gray-200">
                                        <?= htmlspecialchars(
                                            $r['ape_paterno'] . ' ' .
                                            ($r['ape_materno'] ?? '') . ', ' .
                                            $r['docente_nombre']
                                        ) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1
                                             rounded-full bg-amber-900/40 text-amber-400 border border-amber-700/50">
                                        Sin asignar
                                    </span>
                                <?php endif ?>
                            </td>

                            <td class="px-5 py-3.5 text-gray-400 text-xs font-mono">
                                <?= htmlspecialchars($r['email'] ?? '—') ?>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="index.php?action=curso_docente_create&curso_id=<?= $r['curso_id'] ?>&anio_id=<?= $anioId ?>"
                                        class="p-1.5 text-gray-400 hover:text-indigo-400 bg-gray-700 hover:bg-gray-600
                                          rounded-lg transition"
                                        title="<?= $r['docente_id'] ? 'Cambiar docente' : 'Asignar docente' ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <?php if ($r['asignacion_id']): ?>
                                        <a href="index.php?action=curso_docente_delete&id=<?= $r['asignacion_id'] ?>&anio_id=<?= $anioId ?>"
                                            onclick="return confirm('¿Quitar este docente del curso?')" class="p-1.5 text-gray-400 hover:text-red-400 bg-gray-700 hover:bg-gray-600
                                          rounded-lg transition" title="Desasignar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </a>
                                    <?php endif ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <!-- Resumen rápido -->
        <?php
        $conDocente = count(array_filter($relaciones, fn($r) => $r['docente_id']));
        $sinDocente = count($relaciones) - $conDocente;
        ?>
        <div class="mt-4 grid grid-cols-3 gap-3">
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-white"><?= count($relaciones) ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Total cursos</p>
            </div>
            <div class="bg-gray-800 border border-green-800/40 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-green-400"><?= $conDocente ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Con docente</p>
            </div>
            <div class="bg-gray-800 border border-amber-800/40 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-amber-400"><?= $sinDocente ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Sin asignar</p>
            </div>
        </div>

    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>