<?php
/**
 * Componente de Asistencia para incrustar en el Perfil del Alumno.
 * Ruta: /views/perfil_academico/component/asistencia.php
 * Se asume que $asistenciasAlumno es proporcionada por el controlador.
 */

// Funciones de ayuda (se deben mover a un archivo de utilidades si se repiten)
function formatFecha($fecha) {
    if (!$fecha) return '';
    return date('d/m/Y', strtotime($fecha));
}

function getEstadoAsistencia($presente) {
    if ($presente == 1) {
        return ['text' => 'Presente', 'color' => 'green', 'bg_class' => 'bg-green-600/40 text-green-200'];
    }
    return ['text' => 'Ausente', 'color' => 'red', 'bg_class' => 'bg-red-600/40 text-red-200'];
}

// SIMULACIÓN DE DATOS si la variable $asistenciasAlumno no está definida
if (!isset($asistenciasAlumno)) {
    // Simular que solo se obtienen los datos de un alumno
    $asistenciasAlumno = [
        ['id' => 10, 'fecha' => '2024-10-01', 'curso_nombre' => '3° Básico A', 'anio_escolar' => 2024, 'presente' => 1, 'observaciones' => ''],
        ['id' => 11, 'fecha' => '2024-10-02', 'curso_nombre' => '3° Básico A', 'anio_escolar' => 2024, 'presente' => 0, 'observaciones' => 'Cita médica'],
        ['id' => 12, 'fecha' => '2024-10-03', 'curso_nombre' => '3° Básico A', 'anio_escolar' => 2024, 'presente' => 1, 'observaciones' => ''],
    ];
}
?>

<div class="mt-8">
    <h3 class="text-xl font-semibold text-white mb-4 border-b border-indigo-500 pb-2">Historial de Asistencia</h3>

    <div class="overflow-x-auto bg-gray-800 rounded-xl shadow-lg border border-gray-700">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-700/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Curso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Observaciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-700">
                <?php if (!empty($asistenciasAlumno)): ?>
                    <?php foreach ($asistenciasAlumno as $a): ?>

                        <?php 
                        $estado = getEstadoAsistencia($a['presente']); 
                        $rowClass = $estado['color'] === 'green' 
                            ? 'bg-gray-900/10 hover:bg-gray-900/30' 
                            : 'bg-red-900/10 hover:bg-red-900/30';
                        ?>

                        <tr class="<?= $rowClass ?> transition-all duration-300">
                            
                            <td class="px-6 py-4 text-sm text-gray-200 whitespace-nowrap">
                                <?= htmlspecialchars(formatFecha($a['fecha'])) ?>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-300 whitespace-nowrap">
                                <?= htmlspecialchars($a['curso_nombre'] ?? 'N/A') ?>
                            </td>
                            
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold whitespace-nowrap
                                    <?= $estado['bg_class'] ?>">
                                    <?= $estado['text'] ?>
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 text-sm text-gray-400 italic">
                                <?= htmlspecialchars($a['observaciones'] ?? 'Sin observaciones') ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            No hay registros de asistencia para este alumno.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>