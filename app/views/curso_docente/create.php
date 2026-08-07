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

<main class="min-h-screen app-shell">

    <header class="page-header">
        <div class="mx-auto max-w-xl px-4 py-6 sm:px-6">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Gestión Académica</p>
            <h1 class="text-2xl font-bold text-strong">Asignar Profesor Jefe</h1>
        </div>
    </header>

    <div class="mx-auto max-w-xl px-4 py-8 sm:px-6">
        <div class="panel rounded-2xl p-6">

            <?php if ($asignacionActual): ?>
                <div class="banner-warning mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-sm">
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
                    <label class="block text-xs font-semibold uppercase tracking-wider text-faint mb-1.5">
                        Año lectivo
                    </label>
                    <select name="anio_id_display" disabled
                        class="input-field w-full text-sm rounded-xl px-3 py-2.5 cursor-not-allowed opacity-70">
                        <?php foreach ($anios as $a): ?>
                            <option <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                                <?= $a['anio'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Curso -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-faint mb-1.5">
                        Curso
                    </label>
                    <select name="curso_id" required
                        class="input-field w-full text-sm rounded-xl px-3 py-2.5">
                        <option value="">Selecciona un curso…</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($cursoId && $cursoId == $c['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nombre']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Profesor Jefe (todos los usuarios, agrupados por rol) -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-faint mb-1.5">
                        Profesor Jefe
                    </label>
                    <?php if (empty($usuarios)): ?>
                        <p class="banner-danger text-sm rounded-xl px-4 py-3">
                            No hay usuarios registrados en el sistema.
                        </p>
                    <?php else: ?>
                        <select name="docente_id" required
                            class="input-field w-full text-sm rounded-xl px-3 py-2.5">
                            <option value="">Selecciona un profesor…</option>
                            <?php $rolActual = null; ?>
                            <?php foreach ($usuarios as $u): ?>
                                <?php if ($u['rol_nombre'] !== $rolActual): ?>
                                    <?php if ($rolActual !== null): ?></optgroup><?php endif ?>
                                    <optgroup label="<?= htmlspecialchars($u['rol_nombre']) ?>">
                                    <?php $rolActual = $u['rol_nombre']; ?>
                                <?php endif ?>
                                <option value="<?= $u['id'] ?>" <?= ($asignacionActual && $asignacionActual['id'] == $u['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(
                                        $u['ape_paterno'] . ' ' .
                                        ($u['ape_materno'] ?? '') . ', ' .
                                        $u['nombre']
                                    ) ?>
                                    <?php if ($u['email']): ?>
                                        — <?= htmlspecialchars($u['email']) ?>
                                    <?php endif ?>
                                </option>
                            <?php endforeach ?>
                            <?php if ($rolActual !== null): ?></optgroup><?php endif ?>
                        </select>
                    <?php endif ?>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" <?= empty($usuarios) ? 'disabled' : '' ?>
                        class="btn-primary flex-1 font-bold py-2.5 rounded-xl transition active:scale-95
                               disabled:opacity-40 disabled:cursor-not-allowed">
                        Guardar asignación
                    </button>
                    <a href="index.php?action=curso_docente&anio_id=<?= $anioId ?>"
                        class="btn-secondary flex-1 text-center font-semibold py-2.5 rounded-xl transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>