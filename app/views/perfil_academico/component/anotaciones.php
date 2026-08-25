<?php
// Variables disponibles: $anotaciones, $resumenAnotaciones, $matricula_id, $anio
$coloresTipo = [
    'Positiva'  => [
        'bg' => 'bg-[var(--success-soft)]', 'border' => 'border-[var(--success)]',
        'text' => 'text-success', 'icono' => 'check'
    ],
    'Leve'      => [
        'bg' => 'bg-[var(--warn-soft)]', 'border' => 'border-[var(--warn)]',
        'text' => 'text-warn', 'icono' => 'warning'
    ],
    'Grave'     => [
        'bg' => 'bg-orange-100 dark:bg-orange-900/40', 'border' => 'border-orange-400 dark:border-orange-500',
        'text' => 'text-orange-700 dark:text-orange-300', 'icono' => 'warning'
    ],
    'Gravísima' => [
        'bg' => 'bg-[var(--danger-soft)]', 'border' => 'border-[var(--danger)]',
        'text' => 'text-danger', 'icono' => 'alert'
    ],
    'Registro'  => [
        'bg' => 'bg-[var(--accent-title-soft)]', 'border' => 'border-[var(--azul-vivo)]',
        'text' => 'text-[var(--link-action)]', 'icono' => 'doc'
    ],
];
?>


<section>
    <h2 class="text-xl font-semibold text-accent mb-4 flex items-center gap-2">
        <?= saat_icon('edit', 'w-5 h-5') ?>
        Anotaciones
    </h2>

    <?php if (empty($anotaciones)): ?>
        <p class="text-faint italic text-sm">Sin anotaciones registradas.</p>

    <?php else: ?>

        <!-- Resumen -->
        <div class="grid grid-cols-3 gap-2 mb-5">
            <div class="bg-[var(--surface-1)] rounded-lg p-3 text-center border divider-soft">
                <p class="text-2xl font-bold text-strong"><?= $resumenAnotaciones['total'] ?></p>
                <p class="text-xs text-muted mt-0.5">Total</p>
            </div>
            <div class="bg-[var(--success-soft)] rounded-lg p-3 text-center border border-[var(--success)]">
                <p class="text-2xl font-bold text-success"><?= $resumenAnotaciones['positivas'] ?></p>
                <p class="text-xs text-muted mt-0.5">Positivas</p>
            </div>
            <div class="bg-[var(--danger-soft)] rounded-lg p-3 text-center border border-[var(--danger)]">
                <p class="text-2xl font-bold text-danger">
                    <?= $resumenAnotaciones['leves'] + $resumenAnotaciones['graves'] + $resumenAnotaciones['gravisimas'] ?>
                </p>
                <p class="text-xs text-muted mt-0.5">Negativas</p>
            </div>
            <div class="bg-[var(--warn-soft)] rounded-lg p-3 text-center border border-[var(--warn)]">
                <p class="text-xl font-bold text-warn"><?= $resumenAnotaciones['leves'] ?></p>
                <p class="text-xs text-muted mt-0.5">Leves</p>
            </div>
            <div class="bg-orange-100 dark:bg-orange-900/20 rounded-lg p-3 text-center border border-orange-400/60 dark:border-orange-800/40">
                <p class="text-xl font-bold text-orange-700 dark:text-orange-400"><?= $resumenAnotaciones['graves'] ?></p>
                <p class="text-xs text-muted mt-0.5">Graves</p>
            </div>
            <div class="bg-[var(--danger-soft)] rounded-lg p-3 text-center border border-[var(--danger)]">
                <p class="text-xl font-bold text-danger"><?= $resumenAnotaciones['gravisimas'] ?></p>
                <p class="text-xs text-muted mt-0.5">Gravísimas</p>
            </div>
        </div>

        <!-- Listado -->
        <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
            <?php foreach ($anotaciones as $an):
                $c = $coloresTipo[$an['tipo']] ?? [
                    'bg' => 'bg-[var(--surface-1)]', 'border' => 'border-[var(--border-soft)]',
                    'text' => 'text-soft', 'icono' => 'doc'
                ];
            ?>
            <div class="<?= $c['bg'] ?> border <?= $c['border'] ?> rounded-xl p-3">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <div class="flex items-center gap-2">
                        <span class="<?= $c['text'] ?>"><?= saat_icon($c['icono'], 'w-4 h-4') ?></span>
                        <span class="text-xs font-bold <?= $c['text'] ?> uppercase tracking-wide">
                            <?= htmlspecialchars($an['tipo']) ?>
                        </span>
                        <span class="text-xs text-faint">·</span>
                        <span class="text-xs text-muted">
                            <?= htmlspecialchars($an['asignatura_nombre'] ?? $an['abreviatura'] ?? '—') ?>
                        </span>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-xs text-faint">
                            <?= date('d/m/Y', strtotime($an['fecha_anotacion'])) ?>
                        </span>
                        <span class="text-xs text-faint ml-2"><?= $an['semestre'] ?>° Sem.</span>
                    </div>
                </div>
                <p class="text-sm text-soft leading-snug">
                    <?= htmlspecialchars($an['contenido']) ?>
                </p>
                <?php if (!empty($an['accion_realizada'])): ?>
                    <p class="text-xs text-faint mt-1">
                        Acción: <?= htmlspecialchars($an['accion_realizada']) ?>
                    </p>
                <?php endif; ?>
                <p class="text-xs text-faint mt-1">
                    Apoderado notificado: <?= htmlspecialchars($an['notificado_apoderado'] ?? '—') ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</section>