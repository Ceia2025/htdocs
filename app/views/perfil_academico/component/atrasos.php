<?php
// Variables disponibles: $atrasos, $matricula_id, $anio
$atrasos = $atrasos ?? [];

$totalAtrasos = count($atrasos);
$justificados = count(array_filter($atrasos, fn($a) => (int) $a['justificado'] === 1));
$injustificados = $totalAtrasos - $justificados;
$sem1 = count(array_filter($atrasos, fn($a) => (int) $a['semestre'] === 1));
$sem2 = count(array_filter($atrasos, fn($a) => (int) $a['semestre'] === 2));
?>

<section>
    <h2 class="text-xl font-semibold text-accent mb-4 flex items-center gap-2">
        <?= saat_icon('alarm', 'w-5 h-5') ?>
        Atrasos
    </h2>

    <?php if (empty($atrasos)): ?>
        <p class="text-faint italic text-sm">Sin atrasos registrados.</p>

    <?php else: ?>

        <!-- Resumen -->
        <div class="grid grid-cols-3 gap-2 mb-5">
            <div class="bg-[var(--surface-1)] rounded-lg p-3 text-center border divider-soft">
                <p class="text-2xl font-bold text-strong"><?= $totalAtrasos ?></p>
                <p class="text-xs text-muted mt-0.5">Total</p>
            </div>
            <div class="bg-[var(--success-soft)] rounded-lg p-3 text-center border border-[var(--success)]">
                <p class="text-2xl font-bold text-success"><?= $justificados ?></p>
                <p class="text-xs text-muted mt-0.5">Justificados</p>
            </div>
            <div class="bg-[var(--danger-soft)] rounded-lg p-3 text-center border border-[var(--danger)]">
                <p class="text-2xl font-bold text-danger"><?= $injustificados ?></p>
                <p class="text-xs text-muted mt-0.5">Injustificados</p>
            </div>
            <div class="bg-[var(--surface-1)] rounded-lg p-3 text-center border divider-soft">
                <p class="text-xl font-bold text-strong"><?= $sem1 ?></p>
                <p class="text-xs text-muted mt-0.5">1° Semestre</p>
            </div>
            <div class="bg-[var(--surface-1)] rounded-lg p-3 text-center border divider-soft">
                <p class="text-xl font-bold text-strong"><?= $sem2 ?></p>
                <p class="text-xs text-muted mt-0.5">2° Semestre</p>
            </div>
        </div>

        <!-- Listado -->
        <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
            <?php foreach ($atrasos as $at):
                $esJustificado = (int) $at['justificado'] === 1;
                $c = $esJustificado
                    ? ['bg' => 'bg-[var(--success-soft)]', 'border' => 'border-[var(--success)]', 'text' => 'text-success', 'label' => 'Justificado']
                    : ['bg' => 'bg-[var(--danger-soft)]', 'border' => 'border-[var(--danger)]', 'text' => 'text-danger', 'label' => 'Injustificado'];
            ?>
            <div class="<?= $c['bg'] ?> border <?= $c['border'] ?> rounded-xl p-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="<?= $c['text'] ?>"><?= saat_icon('alarm', 'w-4 h-4') ?></span>
                        <span class="text-xs font-bold <?= $c['text'] ?> uppercase tracking-wide">
                            <?= $c['label'] ?>
                        </span>
                        <span class="text-xs text-faint">·</span>
                        <span class="text-xs text-muted">
                            Llegada <?= htmlspecialchars(substr($at['hora_llegada'], 0, 5)) ?>
                        </span>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-xs text-faint">
                            <?= date('d/m/Y', strtotime($at['fecha'])) ?>
                        </span>
                        <span class="text-xs text-faint ml-2"><?= (int) $at['semestre'] ?>° Sem.</span>
                    </div>
                </div>
                <?php if (!empty($at['observacion'])): ?>
                    <p class="text-sm text-soft leading-snug mt-1">
                        <?= htmlspecialchars($at['observacion']) ?>
                    </p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</section>