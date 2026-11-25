<?php
require_once __DIR__ . "/../../../controllers/AuthController.php";
require_once __DIR__ . "/../../../models/AlumEmergencia.php";
$auth = new AuthController();
$auth->checkAuth();

$user   = $_SESSION['user'];
$nombre = $user['nombre'];
$rol    = $user['rol'];

$db   = new Connection();
$conn = $db->open();

$cursos = $conn->query("SELECT id, nombre FROM cursos2 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$anios  = $conn->query("SELECT id, anio FROM anios2 ORDER BY anio DESC")->fetchAll(PDO::FETCH_ASSOC);

$niveles = [
    'Basica Incompleta', 'Basica Completa',
    'Media Incompleta',  'Media Completa',
    'Técnico Incompleta','Técnico Completa',
    'Superior Incompleta','Superior Completa',
    'Desconocido',
];

// Una sola fuente para los vínculos (la usa el JS de las tarjetas de contacto)
$relaciones = AlumEmergencia::relacionesDisponibles();

$etiquetasPasos = ['Importar ficha', 'Datos personales', 'Contactos', 'Familia', 'Escolar', 'Matrícula'];
$totalPasos     = count($etiquetasPasos);

include __DIR__ . "/../../layout/header.php";
include __DIR__ . "/../../layout/navbar.php";
?>

<header class="page-header">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-1">Alumnos</p>
        <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Registro Completo de Alumno</h1>
        <p class="text-muted mt-1 text-sm">Importa la ficha Excel o ingresa los datos paso a paso.</p>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="panel p-8 sm:p-10 rounded-3xl shadow-2xl">

        <!-- BARRA DE PROGRESO -->
        <div class="mb-10">
            <div class="flex justify-between text-xs font-semibold text-muted mb-2">
                <?php foreach ($etiquetasPasos as $i => $label): ?>
                    <span id="step-label-<?= $i + 1 ?>" class="step-label transition-colors duration-300 <?= $i === 0 ? 'text-accent' : '' ?>">
                        <?= $label ?>
                    </span>
                <?php endforeach ?>
            </div>
            <div class="relative w-full h-2.5 rounded-full overflow-hidden" style="background: var(--surface-1);">
                <div id="progress-bar"
                    class="absolute left-0 top-0 h-full rounded-full transition-all duration-700 ease-out"
                    style="width: <?= round(100 / $totalPasos, 2) ?>%; background: linear-gradient(90deg, var(--azul-vivo), var(--azul-claro));">
                </div>
            </div>
            <div class="flex justify-between mt-2">
                <?php for ($i = 1; $i <= $totalPasos; $i++): ?>
                    <div id="step-dot-<?= $i ?>" class="step-dot w-2 h-2 rounded-full transition-all duration-300"
                         style="background: <?= $i === 1 ? 'var(--azul-vivo)' : 'var(--border-soft)' ?>;">
                    </div>
                <?php endfor ?>
            </div>
        </div>

        <form method="POST" action="index.php?action=alumnos_store_stepper" id="stepperForm" novalidate>

            <?php include __DIR__ . '/_paso0_importar.php'; ?>
            <?php include __DIR__ . '/_paso1_datos.php'; ?>
            <?php include __DIR__ . '/_paso2_contactos.php'; ?>
            <?php include __DIR__ . '/_paso3_familiar.php'; ?>
            <?php include __DIR__ . '/_paso4_escolar.php'; ?>
            <?php include __DIR__ . '/_paso5_matricula.php'; ?>

        </form>

        <div class="mt-8 flex justify-center">
            <a href="index.php?action=dashboard" class="btn-secondary inline-block px-5 py-2.5 rounded-lg font-semibold shadow transition">
                ← Volver
            </a>
        </div>
    </div>
</main>

<style>
    .step:not(.hidden) { animation: stepFadeIn 0.3s ease-out; }
    @keyframes stepFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    #import-dropzone.drag-over { border-color: var(--azul-vivo) !important; background: var(--accent-title-soft) !important; }
    .campo-aviso { box-shadow: 0 0 0 2px #f59e0b !important; }
    .campo-aviso-msg { color: #f59e0b; font-size: .75rem; margin-top: .25rem; }
</style>

<script>
    window.SAAT_RELACIONES = <?= json_encode($relaciones, JSON_UNESCAPED_UNICODE) ?>;
</script>
<?php include __DIR__ . '/_scripts.php'; ?>
<?php include __DIR__ . "/../../layout/footer.php"; ?>
