<?php
require_once __DIR__ . "/../../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user   = $_SESSION['user'];
$nombre = $user['nombre'];
$rol    = $user['rol'];

$db   = new Connection();
$conn = $db->open();

$alumnos = $conn->query("SELECT id, nombre FROM alumnos2 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$cursos  = $conn->query("SELECT id, nombre FROM cursos2 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$anios   = $conn->query("SELECT id, anio FROM anios2 ORDER BY anio DESC")->fetchAll(PDO::FETCH_ASSOC);

$niveles = [
    'Basica Incompleta', 'Basica Completa',
    'Media Incompleta',  'Media Completa',
    'Técnico Incompleta','Técnico Completa',
    'Superior Incompleta','Superior Completa',
    'Desconocido',
];

include __DIR__ . "/../../layout/header.php";
include __DIR__ . "/../../layout/navbar.php";
?>

<header class="page-header">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-1">Alumnos</p>
        <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Registro Completo de Alumno</h1>
        <p class="text-muted mt-1 text-sm">Ingresa todos los datos requeridos en cada paso.</p>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="panel p-8 sm:p-10 rounded-3xl shadow-2xl">

        <!-- BARRA DE PROGRESO -->
        <div class="mb-10">
            <div class="flex justify-between text-xs font-semibold text-muted mb-2">
                <?php foreach (['Datos personales', 'Contactos', 'Familia', 'Escolar', 'Matrícula'] as $i => $label): ?>
                    <span id="step-label-<?= $i + 1 ?>" class="transition-colors duration-300
                        <?= $i === 0 ? 'text-accent' : '' ?>">
                        <?= $label ?>
                    </span>
                <?php endforeach ?>
            </div>
            <div class="relative w-full h-2.5 rounded-full overflow-hidden" style="background: var(--surface-1);">
                <div id="progress-bar"
                    class="absolute left-0 top-0 h-full rounded-full transition-all duration-700 ease-out"
                    style="width: 20%; background: linear-gradient(90deg, var(--azul-vivo), var(--azul-claro));">
                </div>
            </div>
            <!-- Indicadores de paso -->
            <div class="flex justify-between mt-2">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div id="step-dot-<?= $i ?>"
                         class="w-2 h-2 rounded-full transition-all duration-300
                                <?= $i === 1 ? '' : '' ?>"
                         style="background: <?= $i === 1 ? 'var(--azul-vivo)' : 'var(--border-soft)' ?>;">
                    </div>
                <?php endfor ?>
            </div>
        </div>

        <form method="POST" action="index.php?action=alumnos_store_stepper" id="stepperForm">

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
</style>

<?php include __DIR__ . '/_scripts.php'; ?>
<?php include __DIR__ . "/../../layout/footer.php"; ?>