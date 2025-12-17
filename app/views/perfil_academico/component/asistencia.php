<?php
/**
 * Calendario de Asistencia (Marzo–Diciembre)
 * Se requiere que $asistenciasAlumno venga desde el controlador.
 *
 * Estructura esperada:
 * $asistenciasAlumno = [
 *   ['fecha' => '2024-03-05', 'presente' => 1, 'observaciones' => '' ],
 *   ...
 * ];
 */

// Indexar asistencia por fecha para rápido acceso
$asistenciaMap = [];
if (isset($asistenciasAlumno)) {
    foreach ($asistenciasAlumno as $a) {
        $asistenciaMap[$a['fecha']] = $a;
    }
}

// Meses académicos Chile
$meses = [
    3 => "Marzo",
    4 => "Abril",
    5 => "Mayo",
    6 => "Junio",
    7 => "Julio",
    8 => "Agosto",
    9 => "Septiembre",
    10 => "Octubre",
    11 => "Noviembre",
    12 => "Diciembre"
];

$semestres = [
    "Primer Semestre" => [3, 4, 5, 6],
    "Segundo Semestre" => [7, 8, 9, 10, 11, 12],
];

function getEstadoColor($presente)
{
    return match ($presente) {
        1 => "bg-green-600/40 text-green-200",
        0 => "bg-red-600/40 text-red-200",
        default => "bg-gray-700 text-gray-300"
    };
}

?>

<!-- CONTENEDOR PRINCIPAL -->
<div class="mt-8">
    <h3 class="text-xl font-semibold text-white mb-6 border-b border-indigo-500 pb-2">
        Asistencia por Calendario
    </h3>

    <div class="w-full">
        <?php foreach ($semestres as $nombreSemestre => $mesesSemestre): ?>

            <!-- TÍTULO DEL SEMESTRE -->
            <h4 class="text-lg text-indigo-300 font-bold mt-8 mb-4">
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
                <div x-data="{ open: false }" class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">

                    <!-- HEADER DEL ACORDEÓN -->
                    <button
                        @click="open = !open"
                        class="w-full flex justify-between items-center px-4 py-3 bg-gray-700/40 hover:bg-gray-700/60 transition">
                        
                        <span class="text-indigo-300 font-semibold text-lg">
                            <?= $nombreMes ?>
                        </span>

                        <svg x-bind:class="{'rotate-180': open}"
                            class="w-5 h-5 text-indigo-300 transform transition-transform"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- CONTENIDO DEL ACORDEÓN -->
                    <div x-show="open" x-collapse class="px-4 py-3 bg-gray-800">

                        <table class="w-full text-center border-collapse text-xs">
                            <thead class="text-[11px] text-gray-400">
                                <tr>
                                    <th>Lun</th>
                                    <th>Mar</th>
                                    <th>Mié</th>
                                    <th>Jue</th>
                                    <th>Vie</th>
                                    <th>Sáb</th>
                                    <th>Dom</th>
                                </tr>
                            </thead>

                            <tbody class="text-sm">

                                <?php
                                $diaSemana = date("N", $primerDia); // 1 (Lun) - 7 (Dom)
                                echo "<tr>";

                                // Espacios antes del día 1
                                for ($i = 1; $i < $diaSemana; $i++) {
                                    echo "<td></td>";
                                }

                                // Días del mes
                                for ($dia = 1; $dia <= $diasMes; $dia++) {

                                    $fechaActual = "$anio-$mes-" . str_pad($dia, 2, "0", STR_PAD_LEFT);
                                    $registro = $asistenciaMap[$fechaActual] ?? null;
                                    $estado = $registro["presente"] ?? null;
                                    $claseColor = getEstadoColor($estado);

                                    echo "<td class='p-1'>";
                                    echo "<div class='rounded-md w-7 h-7 flex items-center justify-center mx-auto {$claseColor}'>";
                                    echo $dia;
                                    echo "</div>";
                                    echo "</td>";

                                    // Cambio de fila los domingos
                                    if (date("N", strtotime($fechaActual)) == 7) {
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
