<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>
<main class="min-h-screen app-shell">
    <header class="page-header">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-strong">Anotaciones del Alumno</h1>
        </div>
    </header>

    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- CONTADORES -->
        <?php
        $totales = ['Registro' => 0, 'Positiva' => 0, 'Leve' => 0, 'Grave' => 0, 'Gravísima' => 0];
        foreach ($anotaciones as $an)
            $totales[$an['tipo']]++;
        $total = count($anotaciones);

        $contadores = [
            'Registro'  => ['palette' => 'report-blue',    'label' => 'Registros'],
            'Positiva'  => ['palette' => 'report-emerald', 'label' => 'Positivas'],
            'Leve'      => ['palette' => 'report-yellow',  'label' => 'Leves'],
            'Grave'     => ['palette' => 'report-amber',   'label' => 'Graves'],
            'Gravísima' => ['palette' => 'report-red',     'label' => 'Gravísimas'],
        ];
        ?>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <?php foreach ($contadores as $tipo => $cfg): ?>
                <div class="panel <?= $cfg['palette'] ?> rounded-2xl px-5 py-4 flex items-center gap-3 shadow"
                    style="border-color: var(--r-border)">
                    <span class="text-3xl font-bold text-r-accent">
                        <?= $totales[$tipo] ?>
                    </span>
                    <div>
                        <p class="text-strong font-semibold text-sm"><?= $cfg['label'] ?></p>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <!-- BOTÓN AGREGAR -->
        <div class="flex justify-between items-center mb-4">
            <p class="text-faint text-sm">
                <?= $total ?> anotación
                <?= $total !== 1 ? 'es' : '' ?> registrada
                <?= $total !== 1 ? 's' : '' ?>
            </p>
            <a href="index.php?action=anotacion_create&anio_id=<?= $anio_id ?>&curso_id=<?= $curso_id ?>&matricula_id=<?= $matricula_id ?>"
                class="btn-primary inline-flex items-center gap-2 font-semibold rounded-xl px-4 py-2 shadow transition text-sm">
                ➕ Nueva Anotación
            </a>
        </div>

        <!-- LISTA DE ANOTACIONES -->
        <?php if (!empty($anotaciones)): ?>
            <div class="space-y-4">
                <?php
                $colores = [
                    'Registro'  => ['palette' => 'report-blue',    'icon' => '📋'],
                    'Positiva'  => ['palette' => 'report-emerald', 'icon' => '✅'],
                    'Leve'      => ['palette' => 'report-yellow',  'icon' => '⚠️'],
                    'Grave'     => ['palette' => 'report-amber',   'icon' => '🔶'],
                    'Gravísima' => ['palette' => 'report-red',     'icon' => '🚨'],
                ];
                foreach ($anotaciones as $an):
                    $cfg = $colores[$an['tipo']] ?? $colores['Leve'];
                    ?>
                    <div class="panel <?= $cfg['palette'] ?> rounded-2xl p-5 shadow"
                        style="border-color: var(--r-border)">
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                            <!-- Badge tipo + asignatura -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="report-badge inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-bold">
                                    <?= $cfg['icon'] ?>
                                    <?= $an['tipo'] ?>
                                </span>
                                <span class="chip-counter inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium">
                                    📚
                                    <?= htmlspecialchars($an['asignatura_nombre']) ?>
                                    <?php if ($an['abreviatura']): ?>
                                        <span class="ml-1 text-faint">(
                                            <?= htmlspecialchars($an['abreviatura']) ?>)
                                        </span>
                                    <?php endif ?>
                                </span>
                                <span class="chip-counter inline-flex items-center px-3 py-1 rounded-lg text-xs">
                                    <?= $an['semestre'] ?>° Semestre
                                </span>
                                <?php if ($an['notificado_apoderado'] === 'Si'): ?>
                                    <span class="report-blue report-badge inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium">
                                        ✉️ Apoderado notificado
                                    </span>
                                <?php endif ?>
                            </div>

                            <!-- Fecha + acciones -->
                            <div class="flex items-center gap-3 text-xs text-faint">
                                <span>📅
                                    <?= (new DateTime($an['fecha_anotacion']))->format('d/m/Y') ?>
                                </span>
                                <?php if (AuthController::puede('anotacion_delete')): ?>
                                    <a href="index.php?action=anotacion_delete&id=<?= $an['id'] ?>&anio_id=<?= $anio_id ?>&curso_id=<?= $curso_id ?>"
                                        onclick="return confirm('¿Eliminar esta anotación?')"
                                        class="link-action-danger font-medium">🗑️ Eliminar</a>
                                <?php endif ?>

                            </div>
                        </div>

                        <!-- Contenido -->
                        <p class="text-soft text-sm leading-relaxed">
                            <?= nl2br(htmlspecialchars($an['contenido'])) ?>
                        </p>

                        <!-- Acción realizada -->
                        <?php if (!empty($an['accion_realizada'])): ?>
                            <div class="mt-3 pt-3 border-t divider-soft flex items-start gap-2">
                                <span class="text-xs text-faint font-medium min-w-fit">⚡ Acción:</span>
                                <span class="text-xs text-soft">
                                    <?= htmlspecialchars($an['accion_realizada']) ?>
                                </span>
                            </div>
                        <?php endif ?>
                    </div>
                <?php endforeach ?>
            </div>

        <?php else: ?>
            <div class="text-center py-16 text-faint">
                <p class="text-5xl mb-4">📋</p>
                <p class="text-lg">Este alumno no tiene anotaciones
                    <?= $semestre ? 'en el ' . $semestre . '° semestre' : 'registradas' ?>.
                </p>
            </div>
        <?php endif ?>

        <!-- VOLVER -->
        <div class="mt-8 flex justify-center gap-4">
            <a href="index.php?action=anotaciones&anio_id=<?= $anio_id ?>&curso_id=<?= $curso_id ?><?= $semestre ? '&semestre=' . $semestre : '' ?>"
                class="btn-secondary inline-block rounded-md px-4 py-2 text-sm font-semibold shadow">
                ⬅️ Volver al listado
            </a>
        </div>

    </div>
</main>


<?php if (!empty($_GET['anio_id']) && !empty($_GET['curso_id']) && !empty($_GET['matricula_id'])): ?>
    <a href="index.php?action=anotaciones_individual_pdf&anio_id=<?= $_GET['anio_id'] ?>&curso_id=<?= $_GET['curso_id'] ?>&matricula_id=<?= $_GET['matricula_id'] ?>&semestre=<?= htmlspecialchars($_GET['semestre'] ?? '') ?>"
        target="_blank"
        class="btn-danger fixed bottom-6 right-6 z-50 flex items-center gap-2 px-5 py-3 font-bold rounded-full shadow-2xl transition-all hover:scale-105">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        <span>Exportar Anotaciones</span>
    </a>
<?php endif; ?>

<?php include __DIR__ . "/../layout/footer.php"; ?>