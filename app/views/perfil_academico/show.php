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

$seccionesPdf = [
    'notas' => 'Notas',
    'asistencia' => 'Asistencia',
    'anotaciones' => 'Anotaciones',
    'atrasos' => 'Atrasos',
];
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
            <button type="button" id="btnAbrirPdf"
                class="btn-secondary px-4 py-2 rounded-lg shadow inline-flex items-center gap-2 transition">
                <?= saat_icon('eye', 'w-4 h-4') ?>
                Descargar PDF
            </button>
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

<!-- MODAL: descargar perfil en PDF -->
<div id="modalPdf" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
    style="background: rgba(0, 0, 0, 0.6);" aria-hidden="true">
    <div class="panel rounded-xl shadow w-full max-w-md p-6" role="dialog" aria-labelledby="modalPdfTitulo">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 id="modalPdfTitulo" class="text-lg font-semibold text-strong">Descargar perfil en PDF</h2>
                <p class="text-xs text-muted mt-1">Elige qué secciones quieres incluir.</p>
            </div>
            <button type="button" id="btnCerrarPdf" class="text-faint hover:text-strong transition text-xl leading-none"
                aria-label="Cerrar">&times;</button>
        </div>

        <form id="formPdf" method="GET" action="index.php" target="_blank">
            <input type="hidden" name="action" value="perfil_academico_pdf">
            <input type="hidden" name="matricula_id" value="<?= (int) $matricula['id'] ?>">

            <label class="flex items-center gap-3 px-3 py-2 rounded-lg border divider-soft mb-3 cursor-pointer">
                <input type="checkbox" id="pdfTodas" checked class="w-4 h-4">
                <span class="text-sm font-semibold text-strong">Todas las secciones</span>
            </label>

            <div class="space-y-2 mb-5">
                <?php foreach ($seccionesPdf as $clave => $etiqueta): ?>
                    <label class="flex items-center gap-3 px-3 py-2 rounded-lg border divider-soft cursor-pointer">
                        <input type="checkbox" name="secciones[]" value="<?= $clave ?>" checked class="pdf-seccion w-4 h-4">
                        <span class="text-sm text-soft"><?= $etiqueta ?></span>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="btnCancelarPdf" class="btn-secondary text-sm px-4 py-2 rounded-lg transition">
                    Cancelar
                </button>
                <button type="submit" id="btnGenerarPdf" class="btn-primary text-sm px-4 py-2 rounded-lg transition">
                    Generar PDF
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('modalPdf');
        var todas = document.getElementById('pdfTodas');
        var items = Array.prototype.slice.call(document.querySelectorAll('.pdf-seccion'));
        var btnGenerar = document.getElementById('btnGenerarPdf');

        function abrir() {
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
        }

        function cerrar() {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        }

        function sincronizar() {
            var marcadas = items.filter(function (i) { return i.checked; }).length;
            todas.checked = marcadas === items.length;
            btnGenerar.disabled = marcadas === 0;
            btnGenerar.style.opacity = marcadas === 0 ? '0.5' : '';
            btnGenerar.style.cursor = marcadas === 0 ? 'not-allowed' : '';
        }

        document.getElementById('btnAbrirPdf').addEventListener('click', abrir);
        document.getElementById('btnCerrarPdf').addEventListener('click', cerrar);
        document.getElementById('btnCancelarPdf').addEventListener('click', cerrar);

        modal.addEventListener('click', function (e) {
            if (e.target === modal) cerrar();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) cerrar();
        });

        todas.addEventListener('change', function () {
            items.forEach(function (i) { i.checked = todas.checked; });
            sincronizar();
        });

        items.forEach(function (i) { i.addEventListener('change', sincronizar); });

        sincronizar();
    })();
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>