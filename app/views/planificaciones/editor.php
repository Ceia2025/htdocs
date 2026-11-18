<?php
/** @var string $jsonDatos  JSON ya escapado (JSON_HEX_*) con todos los datos del editor */
/** @var array  $datos */
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$h = fn($valor) => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$rutaJs = __DIR__ . '/planificacion_editor.js';
$version = is_file($rutaJs) ? filemtime($rutaJs) : 1;
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8 flex flex-wrap items-center justify-between gap-3">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">
                Planificación <?= (int) $datos['plan']['anio'] ?>
            </p>
            <h1 class="text-2xl font-bold text-strong font-display">
                <?= $h($datos['plan']['asignatura']) ?>
                <span class="text-muted font-semibold">· <?= $h($datos['plan']['curso']) ?></span>
            </h1>
            <p class="text-sm text-muted mt-0.5">
                <?= $h($datos['plan']['nivel']) ?>
                <?php if ($esAdmin): ?> · Docente: <?= $h($datos['plan']['docente']) ?><?php endif; ?>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span id="pl-sync" class="pl-sync" role="status" aria-live="polite"></span>
            <a href="<?= $h($datos['urls']['volver']) ?>" class="btn-secondary text-sm px-4 py-2 rounded-lg transition">
                ← Volver
            </a>
        </div>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
    <noscript>
        <div class="banner-danger rounded-lg px-4 py-3 text-sm font-medium mb-6">
            El editor de planificaciones necesita JavaScript activado.
        </div>
    </noscript>

    <div id="pl-app" class="pl">
        <p class="text-muted text-sm">Cargando planificación…</p>
    </div>

    <p class="pl-ref">
        Referente curricular: Bases Curriculares para la Educación de Personas Jóvenes y Adultas 2024
        (Decreto Supremo N° 10/2022, Mineduc) · Evaluación: Decreto 67/2018.
    </p>
</main>

<div id="pl-modal"></div>
<div id="pl-toast" class="pl-toast" hidden></div>

<script type="application/json" id="pl-data"><?= $jsonDatos ?></script>
<script src="../views/planificaciones/planificacion_editor.js?v=<?= (int) $version ?>"></script>

<?php include __DIR__ . "/../layout/footer.php"; ?>