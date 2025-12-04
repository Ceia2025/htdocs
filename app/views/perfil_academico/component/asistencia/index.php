<?php
/**
 * Vista Index/General para Asistencias.
 * Muestra las asistencias agrupadas por Curso.
 * Se asume que la variable $asistenciasAgrupadas es proporcionada por PerfilAcademicoAsistenciaController->index().
 * $asistenciasAgrupadas es un array donde la clave es el nombre del curso.
 */

require_once __DIR__ . "/../../../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

// --- Funciones de Ayuda (movidas de la vista anterior) ---

// Función de ayuda para formatear la fecha a un formato más legible
function formatFecha($fecha)
{
    if (!$fecha)
        return '';
    // Formato Latam: DD/MM/AAAA
    return date('d/m/Y', strtotime($fecha));
}

// Función de ayuda para determinar el estado y colores
function getEstadoAsistencia($presente)
{
    if ($presente == 1) {
        return ['text' => 'Presente', 'color' => 'green', 'bg_class' => 'bg-green-600/40 text-green-200'];
    }
    return ['text' => 'Ausente', 'color' => 'red', 'bg_class' => 'bg-red-600/40 text-red-200'];
}

// La variable esperada ahora es $asistenciasAgrupadas, NO $asistencias
$asistenciasAgrupadas = $asistenciasAgrupadas ?? [];

include __DIR__ . "/../../../layout/header.php";
include __DIR__ . "/../../../layout/navbar.php";
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="relative bg-gray-800 after:absolute after:inset-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6">
                <h1 class="text-3xl font-bold tracking-tight text-white">Asistencias por Curso</h1>
                <p class="text-gray-400 mt-1">Registro histórico de asistencia organizado por materia o curso.</p>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-8">

                <!-- BOTÓN TOMAR ASISTENCIA (Crear) -->
                <div class="mb-6 flex justify-end">
                    <a href="index.php?action=asistencia_create_form" class="w-full sm:w-auto group inline-flex items-center justify-center gap-2 px-5 py-2.5 
                bg-gradient-to-r from-indigo-500 to-purple-600 
                hover:from-indigo-600 hover:to-purple-700 
                text-white font-semibold rounded-xl shadow-md
                hover:shadow-lg transition-all duration-300">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Tomar Asistencia</span>
                    </a>
                </div>

                <!-- CONTENEDOR DE GRUPOS POR CURSO -->
                <?php if (!empty($asistenciasAgrupadas)): ?>

                    <?php foreach ($asistenciasAgrupadas as $cursoNombre => $asistenciasCurso): ?>

                        <!-- Tarjeta de Curso -->
                        <div class="mb-10 bg-gray-800/50 rounded-2xl shadow-xl overflow-hidden border border-gray-700/50">

                            <!-- TÍTULO DEL CURSO (Encabezado del Grupo) -->
                            <h2 class="px-6 py-4 text-xl font-bold bg-gray-900/70 text-indigo-400 border-b border-gray-700">
                                Curso: <?= htmlspecialchars($cursoNombre) ?>
                                <span class="text-sm font-normal text-gray-400 ml-2">(<?= count($asistenciasCurso) ?>
                                    registros)</span>
                            </h2>

                            <!-- TABLA DENTRO DEL CURSO -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-gray-950/50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">
                                                Fecha
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">
                                                Alumno
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">
                                                Año
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">
                                                Estado
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">
                                                Observaciones</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-700">
                                        <?php foreach ($asistenciasCurso as $a): ?>

                                            <?php
                                            $estado = getEstadoAsistencia($a['presente']);
                                            $rowClass = $estado['color'] === 'green'
                                                ? 'bg-gray-700/20 hover:bg-gray-700/40'
                                                : 'bg-red-700/10 hover:bg-red-700/30';
                                            ?>

                                            <tr class="<?= $rowClass ?> transition-all duration-300">

                                                <td class="px-6 py-4 text-sm">
                                                    <span class="px-3 py-1 rounded-full border border-indigo-400 text-indigo-300
                                                    bg-gray-800 shadow-inner text-sm font-mono whitespace-nowrap">
                                                        <?= htmlspecialchars(formatFecha($a['fecha'])) ?>
                                                    </span>
                                                </td>

                                                <td class="px-6 py-4 text-sm text-gray-100 whitespace-nowrap">
                                                    <?= htmlspecialchars($a['alumno_apepat'] . " " . $a['alumno_apemat'] . ", " . $a['alumno_nombre']) ?>
                                                </td>

                                                <td class="px-6 py-4 text-sm text-gray-300 whitespace-nowrap">
                                                    <?= htmlspecialchars($a['anio_escolar'] ?? 'N/A') ?>
                                                </td>

                                                <td class="px-6 py-4 text-sm">
                                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold whitespace-nowrap
                                                        <?= $estado['bg_class'] ?>">
                                                        <?= $estado['text'] ?>
                                                    </span>
                                                </td>

                                                <td class="px-6 py-4 text-sm text-gray-300 italic max-w-xs truncate">
                                                    <?= htmlspecialchars($a['observaciones'] ?? 'N/A') ?>
                                                </td>

                                                <td class="px-6 py-4 text-sm flex gap-3 whitespace-nowrap">

                                                    <!-- Editar -->
                                                    <a href="index.php?action=asistencia_edit&id=<?= $a['id'] ?>"
                                                        class="px-3 py-1.5 rounded-lg bg-blue-600/20 text-blue-300 
                                                        hover:bg-blue-600/40 hover:text-blue-100 
                                                        transition-all duration-300 text-xs font-semibold flex items-center gap-1">
                                                        ✏️ Editar
                                                    </a>

                                                    <!-- Eliminar -->
                                                    <a href="index.php?action=asistencia_delete&id=<?= $a['id'] ?>"
                                                        onclick="return confirm('¿Está seguro de eliminar este registro de asistencia?');"
                                                        class="px-3 py-1.5 rounded-lg bg-red-600/20 text-red-300 
                                                        hover:bg-red-600/40 hover:text-red-100 
                                                        transition-all duration-300 text-xs font-semibold flex items-center gap-1">
                                                        🗑 Eliminar
                                                    </a>

                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div class="px-6 py-10 text-center bg-gray-800 rounded-2xl shadow-lg">
                        <p class="text-xl text-gray-400">
                            No hay registros de asistencias disponibles.
                        </p>
                        <div class="mt-3 text-md text-gray-500">
                            Puedes empezar a registrar haciendo clic en "Tomar Asistencia".
                        </div>
                    </div>
                <?php endif; ?>

                <!-- VOLVER -->
                <div class="mt-8 flex justify-center">
                    <a href="index.php?action=dashboard"
                        class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600">
                        ⬅ Dashboard
                    </a>
                </div>

            </div>
        </main>

    </div>
</body>

<?php include __DIR__ . "/../../../layout/footer.php"; ?>