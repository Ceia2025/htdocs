<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Lee un ícono de app/public/svg/{key}.svg y lo devuelve listo para inyectar inline.
// Definida aquí una sola vez: los componentes incluidos más abajo (notas.php,
// asistencia.php, anotaciones.php, atrasos.php) la heredan por scope de include,
// no hace falta redefinirla en cada uno.
function saat_icon(string $key, string $classes = 'w-4 h-4'): string
{
    $path = __DIR__ . "/../../public/svg/{$key}.svg";
    if (!file_exists($path)) {
        return '';
    }
    $svg = file_get_contents($path);
    return preg_replace('/<svg /', '<svg class="' . htmlspecialchars($classes) . '" ', $svg, 1);
}
?>

<div class="min-h-full">

    <!-- HEADER -->
    <header class="page-header shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-strong">
                Perfil Académico:
                <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apepat'] . ' ' . $alumno['apemat']) ?>
            </h1>
            <p class="text-muted mt-2">
                Curso: <strong class="text-soft"><?= htmlspecialchars($curso['nombre']) ?></strong> |
                Año: <strong class="text-soft"><?= htmlspecialchars($anio['anio']) ?></strong> |
                Fecha Matrícula: <?= htmlspecialchars($matricula['fecha_matricula']) ?>
            </p>
        </div>
    </header>

    <!-- MAIN -->
    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- Navegación -->
        <div class="flex space-x-4 mb-6">
            <a href="index.php?action=alumno_profile&id=<?= $alumno['id'] ?>"
                class="btn-secondary px-4 py-2 rounded-lg shadow inline-flex items-center gap-2 transition">
                <?= saat_icon('user', 'w-4 h-4') ?>
                Ficha del Alumno
            </a>
            <a href=""
                class="btn-secondary px-4 py-2 rounded-lg shadow inline-flex items-center gap-2 transition">
                <?= saat_icon('eye', 'w-4 h-4') ?>
                Ver resumen en PDF
            </a>
            <a href="index.php?action=matriculas"
                class="btn-secondary px-4 py-2 rounded-lg shadow inline-flex items-center gap-2 transition">
                
                Volver
            </a>
        </div>

        <!-- Secciones -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Notas -->
            <div class="panel p-6 rounded-xl shadow">
                <?php
                // Usamos include para acceder a las variables de show.php
                include __DIR__ . "/component/notas.php";
                ?>
            </div>

            <!-- Asistencia -->
            <div class="panel p-6 rounded-xl shadow">
                <?php
                include __DIR__ . "/component/asistencia.php";
                ?>
            </div>

            <!-- Anotaciones -->
            <div class="panel p-6 rounded-xl shadow">
                <?php
                include __DIR__ . "/component/anotaciones.php";
                ?>
            </div>

            <!-- Atrasos -->
            <div class="panel p-6 rounded-xl shadow">
                <?php
                include __DIR__ . "/component/atrasos.php";
                ?>
            </div>
        </div>

    </main>
</div>

<?php include __DIR__ . "/../layout/footer.php"; ?>