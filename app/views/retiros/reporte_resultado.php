<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$tipo    = $_GET['tipo'] ?? 'general';
$titulos = [
    'general' => 'Reporte general de retiros',
    'curso'   => 'Reporte de retiros por curso',
    'alumno'  => 'Reporte individual de retiros',
];
$titulo = $titulos[$tipo] ?? 'Reporte de retiros';

$totalRetiros  = count($retiros);
$totalJust     = count(array_filter($retiros, fn($r) => $r['justificado']   === 'Si'));
$totalInjust   = count(array_filter($retiros, fn($r) => $r['justificado']   === 'No'));
$totalExtraord = count(array_filter($retiros, fn($r) => $r['extraordinario']=== 'Si'));
$maxMotivo     = !empty($porMotivo) ? $porMotivo[0]['total'] : 1;
?>

<header class="page-header">
    <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <a href="index.php?action=retiros_reportes" class="icon-btn p-1 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Convivencia Escolar</p>
                <h1 class="text-2xl font-bold text-strong font-display"><?= $titulo ?></h1>
            </div>
        </div>
        <a href="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>&formato=pdf"
            class="btn-danger flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Exportar PDF
        </a>
    </div>
</header>

<main class="mx-auto max-w-6xl px-4 py-6 sm:px-6 space-y-6">

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="panel rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-strong"><?= $totalRetiros ?></p>
            <p class="text-xs text-muted mt-0.5">Total retiros</p>
        </div>
        <div class="panel rounded-xl p-4 text-center" style="border-color: var(--danger);">
            <p class="text-2xl font-bold text-danger"><?= $totalInjust ?></p>
            <p class="text-xs text-muted mt-0.5">No justificados</p>
        </div>
        <div class="panel rounded-xl p-4 text-center" style="border-color: var(--success);">
            <p class="text-2xl font-bold text-success"><?= $totalJust ?></p>
            <p class="text-xs text-muted mt-0.5">Justificados</p>
        </div>
        <div class="panel rounded-xl p-4 text-center" style="border-color: var(--warn);">
            <p class="text-2xl font-bold text-warn"><?= $totalExtraord ?></p>
            <p class="text-xs text-muted mt-0.5">Extraordinarios</p>
        </div>
    </div>

    <!-- Semestres + motivos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <?php if (!empty($porSemestre) && $tipo !== 'alumno'): ?>
            <div class="panel rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b divider-soft">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-muted">Comparativa por semestre</h2>
                </div>
                <div class="grid grid-cols-<?= count($porSemestre) ?> divide-x" style="border-color: var(--border-soft);">
                    <?php foreach ($porSemestre as $sem): ?>
                        <div class="px-5 py-5">
                            <p class="text-xs text-muted mb-2"><?= $sem['semestre'] ?>° Semestre</p>
                            <p class="text-3xl font-bold text-strong mb-1"><?= $sem['total'] ?></p>
                            <p class="text-xs text-faint mb-3"><?= $sem['porcentaje'] ?>% del total</p>
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-success"><?= $sem['justificados'] ?> just.</span>
                                    <span class="text-faint">·</span>
                                    <span class="text-xs text-danger"><?= $sem['injustificados'] ?> no just.</span>
                                </div>
                                <?php if ($sem['extraordinarios'] > 0): ?>
                                    <div>
                                        <span class="text-xs text-warn"><?= $sem['extraordinarios'] ?> extraordinarios</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($porMotivo)): ?>
            <div class="panel rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b divider-soft">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-muted">Retiros por motivo</h2>
                </div>
                <div class="px-5 py-3 space-y-3">
                    <?php foreach ($porMotivo as $m):
                        $pct = round($m['total'] / $maxMotivo * 100);
                        $barColor = $pct >= 75 ? 'var(--danger)' : ($pct >= 40 ? 'var(--warn)' : 'var(--azul-vivo)');
                        ?>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-soft truncate max-w-[180px]"><?= htmlspecialchars($m['motivo']) ?></span>
                                <span class="text-strong font-bold ml-2 flex-shrink-0"><?= $m['total'] ?></span>
                            </div>
                            <div class="h-1.5 rounded-full overflow-hidden" style="background: var(--surface-1);">
                                <div class="h-full rounded-full" style="width:<?= $pct ?>%; background: <?= $barColor ?>;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- Por curso -->
    <?php if ($tipo !== 'alumno' && !empty($porCurso)): ?>
        <div class="panel rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b divider-soft">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-muted">Retiros por curso</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Curso</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">Total</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">Justificados</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">No justif.</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">Extraordinarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($porCurso as $c): ?>
                            <tr>
                                <td class="px-5 py-3 text-strong font-semibold"><?= htmlspecialchars($c['curso']) ?></td>
                                <td class="px-5 py-3 text-center font-bold text-soft"><?= $c['total'] ?></td>
                                <td class="px-5 py-3 text-center text-success"><?= $c['justificados'] ?></td>
                                <td class="px-5 py-3 text-center text-danger"><?= $c['injustificados'] ?></td>
                                <td class="px-5 py-3 text-center text-warn"><?= $c['extraordinarios'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Media -->
    <?php if ($media !== null): ?>
        <div class="panel rounded-xl px-5 py-4 flex items-center gap-5">
            <p class="text-xs text-muted uppercase tracking-wider">Media de retiros por alumno (año)</p>
            <p class="text-2xl font-bold text-accent"><?= number_format($media, 2) ?></p>
        </div>
    <?php endif; ?>

    <!-- Detalle -->
    <div class="panel rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b divider-soft">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-muted">
                Detalle de retiros
                <span class="normal-case font-normal text-faint ml-1">(<?= count($retiros) ?> registros)</span>
            </h2>
        </div>
        <?php if (empty($retiros)): ?>
            <div class="py-12 text-center text-muted text-sm">Sin retiros para los filtros seleccionados.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="data-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Alumno</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Curso</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">Fecha / Hora</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Motivo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Quien retira</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-muted">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($retiros as $r): ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="text-strong font-semibold">
                                        <?= htmlspecialchars($r['apepat'] . ' ' . $r['apemat'] . ', ' . $r['nombre']) ?>
                                    </p>
                                    <p class="text-xs text-faint font-mono"><?= htmlspecialchars($r['run']) ?></p>
                                </td>
                                <td class="px-4 py-3 text-muted text-xs"><?= htmlspecialchars($r['curso']) ?></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-soft text-xs font-mono block">
                                        <?= date('d/m/Y', strtotime($r['fecha_retiro'])) ?>
                                    </span>
                                    <span class="font-bold font-mono" style="color: var(--azul-vivo);">
                                        <?= substr($r['hora_retiro'], 0, 5) ?>
                                    </span>
                                    <span class="text-faint text-xs block"><?= $r['semestre'] ?>° sem.</span>
                                </td>
                                <td class="px-4 py-3 text-soft text-xs"><?= htmlspecialchars($r['motivo']) ?></td>
                                <td class="px-4 py-3 text-muted text-xs">
                                    <?= $r['quien_retira'] ? htmlspecialchars($r['quien_retira']) : '—' ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <?php if ($r['justificado'] === 'Si'): ?>
                                            <span class="banner-success text-xs font-semibold px-2 py-0.5 rounded-full">Justif.</span>
                                        <?php else: ?>
                                            <span class="chip-danger text-xs font-semibold px-2 py-0.5 rounded-full">No just.</span>
                                        <?php endif; ?>
                                        <?php if ($r['extraordinario'] === 'Si'): ?>
                                            <span class="chip-warn text-xs font-semibold px-2 py-0.5 rounded-full">Extraord.</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>