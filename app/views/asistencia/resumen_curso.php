<div class="max-w-7xl mx-auto mt-8 bg-gray-800 p-6 rounded-xl shadow-lg">

    <h2 class="text-2xl font-bold text-white mb-6">
        📊 Resumen de Asistencia del Curso
    </h2>

    <!-- Indicador General -->
    <div class="mb-6 p-4 rounded-lg 
        <?= $porcentajeGeneral >= 85 ? 'bg-green-700' :
            ($porcentajeGeneral >= 70 ? 'bg-yellow-600' : 'bg-red-700') ?>">

        <h3 class="text-xl font-semibold text-white">
            Asistencia General: <?= $porcentajeGeneral ?>%
        </h3>
    </div>

    <!-- Tabla Detallada -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-300">
            <thead class="bg-gray-700 text-gray-200 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Alumno</th>
                    <th class="px-4 py-3 text-center">Clases</th>
                    <th class="px-4 py-3 text-center">Presentes</th>
                    <th class="px-4 py-3 text-center">%</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php foreach ($detalle as $row): ?>

                    <?php
                    $color = $row['porcentaje'] >= 85 ? 'text-green-400' :
                        ($row['porcentaje'] >= 70 ? 'text-yellow-400' : 'text-red-400');
                    ?>

                    <tr class="hover:bg-gray-700">
                        <td class="px-4 py-2">
                            <?= $row['apepat'] . ' ' . $row['apemat'] . ', ' . $row['nombre'] ?>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <?= $row['total_clases'] ?>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <?= $row['total_presentes'] ?? 0 ?>
                        </td>
                        <td class="px-4 py-2 text-center font-bold <?= $color ?>">
                            <?= $row['porcentaje'] ?>%
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>