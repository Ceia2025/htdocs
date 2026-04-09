<?php
// Variables disponibles: $anotaciones, $resumenAnotaciones, $matricula_id, $anio
$coloresTipo = [
    'Positiva'  => ['bg' => 'bg-green-900/40',  'border' => 'border-green-500',  'text' => 'text-green-300',  'emoji' => '✅'],
    'Leve'      => ['bg' => 'bg-yellow-900/40', 'border' => 'border-yellow-500', 'text' => 'text-yellow-300', 'emoji' => '⚠️'],
    'Grave'     => ['bg' => 'bg-orange-900/40', 'border' => 'border-orange-500', 'text' => 'text-orange-300', 'emoji' => '🔶'],
    'Gravísima' => ['bg' => 'bg-red-900/40',    'border' => 'border-red-500',    'text' => 'text-red-300',    'emoji' => '🚨'],
    'Registro'  => ['bg' => 'bg-blue-900/40',   'border' => 'border-blue-500',   'text' => 'text-blue-300',   'emoji' => '📋'],
];
?>


<section>
    <h2 class="text-xl font-semibold text-indigo-400 mb-4">📝 Anotaciones</h2>

    <?php if (empty($anotaciones)): ?>
        <p class="text-gray-500 italic text-sm">Sin anotaciones registradas.</p>

    <?php else: ?>

        <!-- Resumen -->
        <div class="grid grid-cols-3 gap-2 mb-5">
            <div class="bg-gray-900 rounded-lg p-3 text-center border border-gray-700">
                <p class="text-2xl font-bold text-white"><?= $resumenAnotaciones['total'] ?></p>
                <p class="text-xs text-gray-400 mt-0.5">Total</p>
            </div>
            <div class="bg-green-900/30 rounded-lg p-3 text-center border border-green-800/50">
                <p class="text-2xl font-bold text-green-400"><?= $resumenAnotaciones['positivas'] ?></p>
                <p class="text-xs text-gray-400 mt-0.5">Positivas</p>
            </div>
            <div class="bg-red-900/30 rounded-lg p-3 text-center border border-red-800/50">
                <p class="text-2xl font-bold text-red-400">
                    <?= $resumenAnotaciones['leves'] + $resumenAnotaciones['graves'] + $resumenAnotaciones['gravisimas'] ?>
                </p>
                <p class="text-xs text-gray-400 mt-0.5">Negativas</p>
            </div>
            <div class="bg-yellow-900/20 rounded-lg p-3 text-center border border-yellow-800/40">
                <p class="text-xl font-bold text-yellow-400"><?= $resumenAnotaciones['leves'] ?></p>
                <p class="text-xs text-gray-400 mt-0.5">Leves</p>
            </div>
            <div class="bg-orange-900/20 rounded-lg p-3 text-center border border-orange-800/40">
                <p class="text-xl font-bold text-orange-400"><?= $resumenAnotaciones['graves'] ?></p>
                <p class="text-xs text-gray-400 mt-0.5">Graves</p>
            </div>
            <div class="bg-red-900/20 rounded-lg p-3 text-center border border-red-800/40">
                <p class="text-xl font-bold text-red-400"><?= $resumenAnotaciones['gravisimas'] ?></p>
                <p class="text-xs text-gray-400 mt-0.5">Gravísimas</p>
            </div>
        </div>

        <!-- Listado -->
        <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
            <?php foreach ($anotaciones as $an):
                $c = $coloresTipo[$an['tipo']] ?? [
                    'bg' => 'bg-gray-800', 'border' => 'border-gray-600',
                    'text' => 'text-gray-300', 'emoji' => '📌'
                ];
            ?>
            <div class="<?= $c['bg'] ?> border <?= $c['border'] ?> rounded-xl p-3">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <div class="flex items-center gap-2">
                        <span class="text-base"><?= $c['emoji'] ?></span>
                        <span class="text-xs font-bold <?= $c['text'] ?> uppercase tracking-wide">
                            <?= htmlspecialchars($an['tipo']) ?>
                        </span>
                        <span class="text-xs text-gray-500">·</span>
                        <span class="text-xs text-gray-400">
                            <?= htmlspecialchars($an['asignatura_nombre'] ?? $an['abreviatura'] ?? '—') ?>
                        </span>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-xs text-gray-500">
                            <?= date('d/m/Y', strtotime($an['fecha_anotacion'])) ?>
                        </span>
                        <span class="text-xs text-gray-600 ml-2"><?= $an['semestre'] ?>° Sem.</span>
                    </div>
                </div>
                <p class="text-sm text-gray-300 leading-snug">
                    <?= htmlspecialchars($an['contenido']) ?>
                </p>
                <?php if (!empty($an['accion_realizada'])): ?>
                    <p class="text-xs text-gray-500 mt-1">
                        Acción: <?= htmlspecialchars($an['accion_realizada']) ?>
                    </p>
                <?php endif; ?>
                <p class="text-xs text-gray-600 mt-1">
                    Apoderado notificado: <?= htmlspecialchars($an['notificado_apoderado'] ?? '—') ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</section>