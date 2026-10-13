<?php
$meses = [
    3 => "Marzo",
    4 => "Abril",
    5 => "Mayo",
    6 => "Junio",
    7 => "Julio",
    8 => "Agosto",
    9 => "Septiembre",
    10 => "Octubre",
    11 => "Noviembre"
];

$semestres = [
    "Primer Semestre" => [3, 4, 5, 6],
    "Segundo Semestre" => [7, 8, 9, 10, 11],
];

function getEstadoColor($estado): string
{
    return match ($estado) {
        1 => "bg-[var(--success-soft)] border border-[var(--success)] text-success",
        0 => "bg-[var(--danger-soft)] border border-[var(--danger)] text-danger",
        default => "bg-[var(--surface-1)] text-faint"
    };
}
?>

<!-- CONTENEDOR PRINCIPAL -->
<div class="mt-8">
    <h3 class="text-xl font-semibold text-strong mb-6 border-b divider-soft pb-2">
        Asistencia por Calendario
    </h3>

    <div class="w-full">
        <?php foreach ($semestres as $nombreSemestre => $mesesSemestre): ?>

            <!-- TÍTULO DEL SEMESTRE -->
            <h4 class="text-lg text-accent font-bold mt-8 mb-4">
                <?= $nombreSemestre ?>
            </h4>

            <div class="space-y-4">

                <?php foreach ($mesesSemestre as $mes):

                    $anio = date("Y");
                    $primerDia = strtotime("$anio-$mes-01");
                    $diasMes = date("t", $primerDia);
                    $nombreMes = $meses[$mes];

                    ?>

                    <!-- ACORDEÓN DEL MES -->
                    <div x-data="{ open: false }" class="accordion-item">

                        <!-- HEADER DEL ACORDEÓN -->
                        <button @click="open = !open"
                            class="accordion-trigger w-full flex justify-between items-center px-4 py-3">

                            <span class="font-semibold text-lg">
                                <?= $nombreMes ?>
                            </span>

                            <svg x-bind:class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- CONTENIDO DEL ACORDEÓN -->
                        <div x-show="open" x-collapse class="accordion-content px-4 py-3">

                            <table class="w-full text-center border-collapse text-xs">
                                <thead class="text-[11px] text-muted">
                                    <tr>
                                        <th>Lun</th>
                                        <th>Mar</th>
                                        <th>Mié</th>
                                        <th>Jue</th>
                                        <th>Vie</th>
                                    </tr>
                                </thead>

                                <tbody class="text-sm">
                                    <?php
                                    // Solo días hábiles (Lun=1 a Vie=5)
                                    $celdas = [];
                                    for ($dia = 1; $dia <= $diasMes; $dia++) {
                                        $fechaActual = "$anio-" . str_pad($mes, 2, "0", STR_PAD_LEFT) . "-" . str_pad($dia, 2, "0", STR_PAD_LEFT);
                                        $diaSemanaNum = (int) date("N", strtotime($fechaActual));

                                        // Saltar fines de semana
                                        if ($diaSemanaNum >= 6)
                                            continue;

                                        $celdas[] = [
                                            'dia' => $dia,
                                            'fecha' => $fechaActual,
                                            'diaSemana' => $diaSemanaNum, // 1=Lun, 5=Vie
                                        ];
                                    }

                                    // Calcular el día de semana del primer día hábil del mes
                                    $primerHabil = $celdas[0]['diaSemana'] ?? 1;

                                    echo "<tr>";

                                    // Celdas vacías antes del primer día hábil
                                    for ($i = 1; $i < $primerHabil; $i++) {
                                        echo "<td></td>";
                                    }

                                    foreach ($celdas as $celda) {
                                        $estado = $asistenciaMap[$celda['fecha']] ?? null;
                                        $claseColor = getEstadoColor($estado);
                                        $tooltip = match ($estado) {
                                            1 => "Presente",
                                            0 => "Ausente",
                                            default => "Sin registro"
                                        };

                                        echo "<td class='p-1'>";
                                        echo "<div class='rounded-md w-7 h-7 flex items-center justify-center mx-auto text-xs {$claseColor} cursor-default'
               title='{$celda['fecha']} — {$tooltip}'>";
                                        echo $celda['dia'];
                                        echo "</div>";
                                        echo "</td>";

                                        // Nueva fila al llegar al viernes (diaSemana == 5)
                                        if ($celda['diaSemana'] == 5) {
                                            echo "</tr><tr>";
                                        }
                                    }

                                    echo "</tr>";
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>