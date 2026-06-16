<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth(); // obliga a tener sesión iniciada

$user = $_SESSION['user']; // usuario logueado
$nombre = $user['nombre'];
$rol = $user['rol'];

// Incluir layout
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<!-- HEADER -->
<header class="page-header">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-strong text-3xl font-bold tracking-tight font-display">Mantenedor de Años</h1>
    </div>
</header>

<!-- MAIN -->
<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- BOTÓN CREAR -->
        <div class="mb-6 flex justify-end">
            <a href="index.php?action=anio_create"
                class="btn-brand inline-flex items-center px-4 py-2 font-semibold rounded-lg shadow transition duration-200">
                ➕ Crear Año
            </a>
        </div>

        <!-- TABLA -->
        <div class="panel overflow-x-auto rounded-2xl shadow-lg">
            <table class="data-table min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                            Año</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                            Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                            1° Semestre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                            2° Semestre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-soft uppercase tracking-wider">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($anios)): ?>
                        <?php foreach ($anios as $anio): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-strong">
                                    <?= htmlspecialchars($anio['anio']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-soft">
                                    <?= htmlspecialchars($anio['descripcion']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-soft">
                                    <?php if (!empty($anio['sem1_inicio'])): ?>
                                        <?= (new DateTime($anio['sem1_inicio']))->format('d/m/Y') ?> -
                                        <?= (new DateTime($anio['sem1_fin']))->format('d/m/Y') ?>
                                    <?php else: ?>
                                        <span class="text-warn text-xs">⚠️ Sin fechas</span>
                                    <?php endif ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-soft">
                                    <?php if (!empty($anio['sem2_inicio'])): ?>
                                        <?= (new DateTime($anio['sem2_inicio']))->format('d/m/Y') ?> -
                                        <?= (new DateTime($anio['sem2_fin']))->format('d/m/Y') ?>
                                    <?php else: ?>
                                        <span class="text-warn text-xs">⚠️ Sin fechas</span>
                                    <?php endif ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                                    <a href="index.php?action=anio_edit&id=<?= $anio['id'] ?>"
                                        class="link-action">✏️ Editar</a>
                                    <a href="index.php?action=anio_delete&id=<?= $anio['id'] ?>"
                                        onclick="return confirm('¿Seguro que quieres eliminar este año?');"
                                        class="link-action-danger">🗑 Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-soft">No hay años
                                registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- VOLVER -->
        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=dashboard"
                class="btn-secondary inline-block rounded-md px-4 py-2 text-sm font-semibold shadow">
                ⬅ Dashboard
            </a>
        </div>
    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>