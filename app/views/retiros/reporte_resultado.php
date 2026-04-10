<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$tipo = $_GET['tipo'] ?? 'general';
$titulos = [
    'general' => 'Reporte general de retiros',
    'curso' => 'Reporte de retiros por curso',
    'alumno' => 'Reporte individual de retiros',
];
$titulo = $titulos[$tipo] ?? 'Reporte de retiros';

// Totales rápidos
$totalRetiros = count($retiros);
$totalJust = count(array_filter($retiros, fn($r) => $r['justificado'] === 'Si'));
$totalInjust = count(array_filter($retiros, fn($r) => $r['justificado'] === 'No'));
$totalExtraord = count(array_filter($retiros, fn($r) => $r['extraordinario'] === 'Si'));
$maxMotivo = !empty($porMotivo) ? $porMotivo[0]['total'] : 1;
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <header class="bg-gray-800 border-b border-white/10">
            <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <a href="index.php?action=retiros_reportes" class="text-gray-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">Convivencia
                            Escolar</p>
                        <h1 class="text-2xl font-bold text-white"><?= $titulo ?></h1>
                    </div>
                </div>
                <a href="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>&formato=pdf"
                    class="flex items-center gap-2 text-sm font-semibold bg-red-700 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Exportar PDF
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6 space-y-6">

            <!-- Tarjetas -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-white"><?= $totalRetiros ?></p>
                    <p class="text-xs text-gray-500 mt-0.5">Total retiros</p>
                </div>
                <div class="bg-gray-800 border border-red-800/40 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-red-400"><?= $totalInjust ?></p>
                    <p class="text-xs text-gray-500 mt-0.5">No justificados</p>
                </div>
                <div class="bg-gray-800 border border-green-800/40 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-green-400"><?= $totalJust ?></p>
                    <p class="text-xs text-gray-500 mt-0.5">Justificados</p>
                </div>
                <div class="bg-gray-800 border border-amber-800/40 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-amber-400"><?= $totalExtraord ?></p>
                    <p class="text-xs text-gray-500 mt-0.5">Extraordinarios</p>
                </div>
            </div>

            <!-- Comparativa semestres + motivos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                <?php if (!empty($porSemestre) && $tipo !== 'alumno'): ?>
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-700">
                            <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Comparativa por
                                semestre</h2>
                        </div>
                        <div class="grid grid-cols-<?= count($porSemestre) ?> divide-x divide-gray-700">
                            <?php foreach ($porSemestre as $sem): ?>
                                <div class="px-5 py-5">
                                    <p class="text-xs text-gray-500 mb-2"><?= $sem['semestre'] ?>° Semestre</p>
                                    <p class="text-3xl font-bold text-white mb-1"><?= $sem['total'] ?></p>
                                    <p class="text-xs text-gray-500 mb-3"><?= $sem['porcentaje'] ?>% del total</p>
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-green-400"><?= $sem['justificados'] ?> just.</span>
                                            <span class="text-gray-700">·</span>
                                            <span class="text-xs text-red-400"><?= $sem['injustificados'] ?> no just.</span>
                                        </div>
                                        <?php if ($sem['extraordinarios'] > 0): ?>
                                            <div><span class="text-xs text-amber-400"><?= $sem['extraordinarios'] ?>
                                                    extraordinarios</span></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($porMotivo)): ?>
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-700">
                            <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Retiros por motivo</h2>
                        </div>
                        <div class="px-5 py-3 space-y-3">
                            <?php foreach ($porMotivo as $m):
                                $pct = round($m['total'] / $maxMotivo * 100); ?>
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span
                                            class="text-gray-300 truncate max-w-[180px]"><?= htmlspecialchars($m['motivo']) ?></span>
                                        <span class="text-white font-bold ml-2 flex-shrink-0"><?= $m['total'] ?></span>
                                    </div>
                                    <div class="h-1.5 bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500 rounded-full" style="width:<?= $pct ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Por curso (general y curso) -->
            <?php if ($tipo !== 'alumno' && !empty($porCurso)): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-700">
                        <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Retiros por curso</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-900/50 text-xs uppercase tracking-wider text-gray-500">
                                <tr>
                                    <th class="px-5 py-3 text-left">Curso</th>
                                    <th class="px-5 py-3 text-center">Total</th>
                                    <th class="px-5 py-3 text-center">Justificados</th>
                                    <th class="px-5 py-3 text-center">No justif.</th>
                                    <th class="px-5 py-3 text-center">Extraordinarios</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                <?php foreach ($porCurso as $c): ?>
                                    <tr class="hover:bg-gray-700/20">
                                        <td class="px-5 py-3 font-medium text-white"><?= htmlspecialchars($c['curso']) ?></td>
                                        <td class="px-5 py-3 text-center font-bold text-gray-200"><?= $c['total'] ?></td>
                                        <td class="px-5 py-3 text-center text-green-400"><?= $c['justificados'] ?></td>
                                        <td class="px-5 py-3 text-center text-red-400"><?= $c['injustificados'] ?></td>
                                        <td class="px-5 py-3 text-center text-amber-400"><?= $c['extraordinarios'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Media -->
            <?php if ($media !== null): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-xl px-5 py-4 flex items-center gap-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Media de retiros por alumno (año)</p>
                    <p class="text-2xl font-bold text-indigo-400"><?= number_format($media, 2) ?></p>
                </div>
            <?php endif; ?>

            <!-- Detalle -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-700">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Detalle de retiros <span
                            class="normal-case font-normal text-gray-600 ml-1">(<?= count($retiros) ?> registros)</span>
                    </h2>
                </div>
                <?php if (empty($retiros)): ?>
                    <div class="py-12 text-center text-gray-500 text-sm">Sin retiros para los filtros seleccionados.</div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-900/50 text-xs uppercase tracking-wider text-gray-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Alumno</th>
                                    <th class="px-4 py-3 text-left">Curso</th>
                                    <th class="px-4 py-3 text-center">Fecha / Hora</th>
                                    <th class="px-4 py-3 text-left">Motivo</th>
                                    <th class="px-4 py-3 text-left">Quien retira</th>
                                    <th class="px-4 py-3 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                <?php foreach ($retiros as $r): ?>
                                    <tr class="hover:bg-gray-700/20">
                                        <td class="px-4 py-3">
                                            <p class="text-white font-medium">
                                                <?= htmlspecialchars($r['apepat'] . ' ' . $r['apemat'] . ', ' . $r['nombre']) ?>
                                            </p>
                                            <p class="text-xs text-gray-500 font-mono"><?= htmlspecialchars($r['run']) ?></p>
                                        </td>
                                        <td class="px-4 py-3 text-gray-400 text-xs"><?= htmlspecialchars($r['curso']) ?></td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="text-gray-300 text-xs font-mono block"><?= date('d/m/Y', strtotime($r['fecha_retiro'])) ?></span>
                                            <span
                                                class="text-indigo-400 font-bold font-mono"><?= substr($r['hora_retiro'], 0, 5) ?></span>
                                            <span class="text-gray-600 text-xs block"><?= $r['semestre'] ?>° sem.</span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-300 text-xs"><?= htmlspecialchars($r['motivo']) ?></td>
                                        <td class="px-4 py-3 text-gray-400 text-xs">
                                            <?= $r['quien_retira'] ? htmlspecialchars($r['quien_retira']) : '—' ?></td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex flex-col items-center gap-1">
                                                <?php if ($r['justificado'] === 'Si'): ?>
                                                    <span
                                                        class="text-xs px-2 py-0.5 rounded-full bg-green-900/40 text-green-400 border border-green-800">Justif.</span>
                                                <?php else: ?>
                                                    <span
                                                        class="text-xs px-2 py-0.5 rounded-full bg-red-900/40 text-red-400 border border-red-800">No
                                                        just.</span>
                                                <?php endif; ?>
                                                <?php if ($r['extraordinario'] === 'Si'): ?>
                                                    <span
                                                        class="text-xs px-2 py-0.5 rounded-full bg-amber-900/40 text-amber-400 border border-amber-800">Extraord.</span>
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
    </div>

    <?php include __DIR__ . "/../layout/footer.php"; ?>