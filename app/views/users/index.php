<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Mapeo de roles a colores del sistema de diseño (clases .report-*)
// Cada rol hereda las variables CSS de su color y las usa en .report-badge
$coloresRol = [
    'administrador'                              => 'report-purple',
    'soporte'                                    => 'report-blue',
    'direccion'                                  => 'report-indigo',
    'administrativo'                             => 'report-cyan',
    'inspector general y convivencia escolar'    => 'report-amber',
    'docente'                                    => 'report-emerald',
    'asistente social'                           => 'report-teal',
    'registro de anotaciones'                    => 'report-yellow',
    'registro asistencias'                       => 'report-teal',
    'registro atrasos y salidas'                 => 'report-rose',
];
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Sistema</p>
            <h1 class="text-2xl font-bold text-strong font-display">Gestión de Usuarios</h1>
            <p class="text-sm text-muted mt-0.5">
                <span class="text-strong font-semibold"><?= count($users) ?></span> usuarios registrados
            </p>
        </div>
        <?php if (AuthController::puede('user_create')): ?>
            <a href="index.php?action=user_create"
               class="btn-primary inline-flex items-center gap-2 text-sm font-semibold px-4 py-2.5 rounded-xl shadow transition">
                Nuevo Usuario
            </a>
        <?php endif ?>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

    <?php if (empty($users)): ?>
        <div class="text-center py-20 text-muted">
            <p class="text-5xl mb-4">👥</p>
            <p class="text-lg font-medium">No hay usuarios registrados.</p>
        </div>
    <?php else: ?>

        <!-- Resumen por rol -->
        <?php
        $conteoRoles = [];
        foreach ($users as $u) {
            $r = strtolower($u['rol'] ?? 'sin rol');
            $conteoRoles[$r] = ($conteoRoles[$r] ?? 0) + 1;
        }
        arsort($conteoRoles);
        ?>
        <div class="flex flex-wrap gap-2 mb-6">
            <?php foreach ($conteoRoles as $r => $cnt):
                $cls = $coloresRol[$r] ?? 'report-slate';
                ?>
                <span class="report-badge <?= $cls ?> inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold">
                    <?= htmlspecialchars(ucfirst($r)) ?>
                    <span class="opacity-60">(<?= $cnt ?>)</span>
                </span>
            <?php endforeach ?>
        </div>

        <!-- TABLA -->
        <div class="panel rounded-2xl overflow-hidden shadow-lg overflow-x-auto">
            <table class="data-table min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Usuario</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Contacto</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">RUN</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Rol</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u):
                        $iniciales = strtoupper(
                            substr($u['nombre'] ?? '', 0, 1) .
                            substr($u['ape_paterno'] ?? '', 0, 1)
                        );
                        $rolLower  = strtolower($u['rol'] ?? '');
                        $badgeCls  = $coloresRol[$rolLower] ?? 'report-slate';
                        ?>
                        <tr class="group transition">

                            <!-- Usuario -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="avatar-badge w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        <?= $iniciales ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-strong capitalize">
                                            <?= htmlspecialchars($u['nombre'] . ' ' . $u['ape_paterno'] . ' ' . $u['ape_materno']) ?>
                                        </p>
                                        <p class="text-xs text-faint font-mono">@<?= htmlspecialchars($u['username']) ?></p>
                                    </div>
                                </div>
                            </td>

                            <!-- Contacto -->
                            <td class="px-5 py-4">
                                <p class="text-sm text-soft"><?= htmlspecialchars($u['email']) ?></p>
                                <p class="text-xs text-muted mt-0.5">
                                    <?= !empty($u['numero_telefonico'])
                                        ? '📞 ' . htmlspecialchars($u['numero_telefonico'])
                                        : '<span class="italic text-faint">Sin teléfono</span>' ?>
                                </p>
                            </td>

                            <!-- RUN -->
                            <td class="px-5 py-4">
                                <span class="text-sm text-soft font-mono">
                                    <?= !empty($u['run'])
                                        ? htmlspecialchars($u['run'])
                                        : '<span class="text-faint italic text-xs">No registrado</span>' ?>
                                </span>
                            </td>

                            <!-- Rol -->
                            <td class="px-5 py-4">
                                <span class="report-badge <?= $badgeCls ?> inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold">
                                    <?= htmlspecialchars(ucfirst($u['rol'] ?? '—')) ?>
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <?php if (AuthController::puede('user_edit')): ?>
                                        <a href="index.php?action=user_edit&id=<?= $u['id'] ?>"
                                           class="link-action inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                                                  text-xs font-semibold transition"
                                           style="background: var(--accent-title-soft); border: 1px solid var(--azul-vivo);">
                                            ✏️ Editar
                                        </a>
                                    <?php endif ?>
                                    <?php if (AuthController::puede('user_delete')): ?>
                                        <a href="index.php?action=user_delete&id=<?= $u['id'] ?>"
                                           onclick="return confirm('¿Seguro que deseas eliminar a <?= htmlspecialchars(addslashes($u['nombre'])) ?>?')"
                                           class="link-action-danger inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                                                  text-xs font-semibold transition"
                                           style="background: var(--danger-soft); border: 1px solid var(--danger);">
                                            🗑️ Eliminar
                                        </a>
                                    <?php endif ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

    <?php endif ?>

    <div class="mt-8 flex justify-center">
        <a href="index.php?action=dashboard"
           class="btn-secondary inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold shadow transition">
            Regresar
        </a>
    </div>

</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>