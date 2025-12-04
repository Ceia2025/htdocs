<?php
/**
 * Vista de formulario para tomar Asistencia (Creación).
 * Muestra el selector de Curso/Fecha y luego la tabla de alumnos para registrar el estado.
 * Se asume que las variables $cursos, $anios y $alumnos son proporcionadas por el controlador.
 */

require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

// --- VARIABLES SIMULADAS (REEMPLAZAR CON DATOS REALES DEL CONTROLADOR) ---
// Simula la lista de cursos disponibles
$cursos = $cursos ?? [
    ['id' => 1, 'nombre' => 'Matemáticas Avanzadas', 'anio_default' => 2024],
    ['id' => 2, 'nombre' => 'Historia Universal', 'anio_default' => 2024],
    ['id' => 3, 'nombre' => 'Programación Web', 'anio_default' => 2023],
];
// Simula años escolares disponibles (si aplica)
$anios = $anios ?? [2024, 2023, 2022];

// Valores seleccionados de la URL/GET para la recarga del formulario
$cursoSeleccionadoId = $_GET['curso_id'] ?? null;
$anioSeleccionado = $_GET['anio'] ?? null;
$fechaSeleccionada = $_GET['fecha'] ?? date('Y-m-d');

// Simula la carga de alumnos si ya se ha seleccionado un curso/año
$alumnos = $alumnos ?? [];
if ($cursoSeleccionadoId && $anioSeleccionado && empty($alumnos)) {
    // *** ESTA LÓGICA DEBE SER MANEJADA POR EL CONTROLADOR ***
    // Si el controlador pasó un curso/año seleccionado pero no alumnos,
    // simulamos la carga de alumnos para la demostración de la vista.
    // En la aplicación real, el controlador llamaría a $model->getAlumnosByCursoYAnio($id, $anio)
    $alumnos = [
        ['matricula_id' => 101, 'nombre' => 'Juan', 'apepat' => 'Pérez', 'apemat' => 'Gómez'],
        ['matricula_id' => 102, 'nombre' => 'María', 'apepat' => 'López', 'apemat' => 'Díaz'],
        ['matricula_id' => 103, 'nombre' => 'Carlos', 'apepat' => 'Sánchez', 'apemat' => 'Ruiz'],
        ['matricula_id' => 104, 'nombre' => 'Laura', 'apepat' => 'Martínez', 'apemat' => 'Flores'],
    ];
}
// -------------------------------------------------------------------------

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="relative bg-gray-800 after:absolute after:inset-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6">
                <h1 class="text-3xl font-bold tracking-tight text-white">Tomar Nueva Asistencia</h1>
                <p class="text-gray-400 mt-1">Selecciona el curso, el año y la fecha para registrar la asistencia.</p>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-8">

                <!-- PASO 1: SELECCIÓN DE CURSO Y FECHA (Formulario de Filtrado) -->
                <form action="index.php" method="GET" class="bg-gray-800 rounded-xl p-6 shadow-xl mb-8">
                    <input type="hidden" name="action" value="asistencia_create_form">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        
                        <!-- Selector de Curso -->
                        <div>
                            <label for="curso_id" class="block text-sm font-medium text-gray-300 mb-1">Curso / Materia</label>
                            <select id="curso_id" name="curso_id" required
                                class="w-full rounded-lg bg-gray-700 border border-gray-600 text-white p-2.5 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">--- Seleccione Curso ---</option>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= $curso['id'] ?>" 
                                        <?= ($curso['id'] == $cursoSeleccionadoId) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($curso['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Selector de Año -->
                        <div>
                            <label for="anio" class="block text-sm font-medium text-gray-300 mb-1">Año Escolar</label>
                            <select id="anio" name="anio" required
                                class="w-full rounded-lg bg-gray-700 border border-gray-600 text-white p-2.5 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">--- Seleccione Año ---</option>
                                <?php foreach ($anios as $anio): ?>
                                    <option value="<?= $anio ?>" 
                                        <?= ($anio == $anioSeleccionado) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($anio) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Selector de Fecha -->
                        <div>
                            <label for="fecha" class="block text-sm font-medium text-gray-300 mb-1">Fecha de Asistencia</label>
                            <input type="date" id="fecha" name="fecha" required
                                value="<?= htmlspecialchars($fechaSeleccionada) ?>"
                                class="w-full rounded-lg bg-gray-700 border border-gray-600 text-white p-2.5 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Botón Cargar Alumnos -->
                    <div class="mt-6 flex justify-end">
                         <button type="submit" 
                                class="group inline-flex items-center justify-center gap-2 px-5 py-2.5 
                                bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md
                                transition-all duration-300">
                            Cargar Alumnos
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- PASO 2: REGISTRO DE ASISTENCIA (Formulario de Inserción) -->
                <?php if (!empty($alumnos)): ?>
                    <h2 class="text-2xl font-bold text-white mb-4">Registro para <?= htmlspecialchars($fechaSeleccionada) ?></h2>
                    
                    <!-- El formulario final POSTea al método create del controlador -->
                    <form action="index.php?action=asistencia_create" method="POST" class="bg-gray-800 rounded-xl p-6 shadow-xl">

                        <!-- Datos ocultos para el controlador -->
                        <input type="hidden" name="fecha" value="<?= htmlspecialchars($fechaSeleccionada) ?>">
                        <input type="hidden" name="curso_id" value="<?= htmlspecialchars($cursoSeleccionadoId) ?>">
                        <input type="hidden" name="anio" value="<?= htmlspecialchars($anioSeleccionado) ?>">

                        <!-- Tabla de Asistencia -->
                        <div class="overflow-x-auto rounded-xl">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-950/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase w-1/4">
                                            Alumno (Matrícula)
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase w-1/6">
                                            Presente
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase w-1/6">
                                            Ausente
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase w-1/2">
                                            Observaciones
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-700">
                                    <?php foreach ($alumnos as $i => $alumno): ?>
                                        <tr class="bg-gray-700/20 hover:bg-gray-700/40 transition-colors duration-200">
                                            
                                            <!-- Nombre del Alumno -->
                                            <td class="px-6 py-4 text-sm font-medium text-gray-100 whitespace-nowrap">
                                                <?= htmlspecialchars($alumno['apepat'] . " " . $alumno['apemat'] . ", " . $alumno['nombre']) ?>
                                                <span class="text-xs text-gray-400 block mt-0.5">ID: <?= htmlspecialchars($alumno['matricula_id']) ?></span>
                                                <!-- Campo oculto para enviar el ID del alumno al controlador -->
                                                <input type="hidden" 
                                                    name="asistencias[<?= $i ?>][matricula_id]" 
                                                    value="<?= htmlspecialchars($alumno['matricula_id']) ?>">
                                            </td>

                                            <!-- Radio Presente -->
                                            <td class="px-6 py-4 text-sm text-center">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" required
                                                        class="form-radio size-5 text-green-500 bg-gray-700 border-gray-600 focus:ring-green-500"
                                                        name="asistencias[<?= $i ?>][presente]" value="1">
                                                    <span class="ml-2 text-green-300 font-semibold hidden sm:inline">Sí</span>
                                                </label>
                                            </td>

                                            <!-- Radio Ausente -->
                                            <td class="px-6 py-4 text-sm text-center">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" required
                                                        class="form-radio size-5 text-red-500 bg-gray-700 border-gray-600 focus:ring-red-500"
                                                        name="asistencias[<?= $i ?>][presente]" value="0" checked>
                                                    <span class="ml-2 text-red-300 font-semibold hidden sm:inline">No</span>
                                                </label>
                                            </td>

                                            <!-- Observaciones -->
                                            <td class="px-6 py-4 text-sm">
                                                <input type="text"
                                                    name="asistencias[<?= $i ?>][observaciones]" 
                                                    placeholder="Ej: Atraso, Justificado..."
                                                    class="w-full rounded-lg bg-gray-700 border border-gray-600 text-white p-2 text-sm placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Botón Final de Guardar -->
                        <div class="mt-8 flex justify-center">
                            <button type="submit" 
                                class="w-full sm:w-auto group inline-flex items-center justify-center gap-3 px-8 py-3 
                                bg-gradient-to-r from-green-500 to-teal-600 
                                hover:from-green-600 hover:to-teal-700 
                                text-white text-lg font-bold rounded-xl shadow-lg
                                hover:shadow-xl transition-all duration-300">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Guardar Asistencias
                            </button>
                        </div>
                    </form>

                <?php elseif ($cursoSeleccionadoId && $anioSeleccionado): ?>
                    <!-- Mensaje si se seleccionó curso/año pero no hay alumnos -->
                    <div class="px-6 py-10 text-center bg-gray-800 rounded-2xl shadow-lg">
                        <p class="text-xl text-gray-400">
                            No se encontraron alumnos para el Curso seleccionado y el Año <?= htmlspecialchars($anioSeleccionado) ?>.
                        </p>
                        <div class="mt-3 text-md text-gray-500">
                            Verifique que la matrícula esté cargada correctamente para este curso.
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Mensaje inicial antes de seleccionar algo -->
                    <div class="px-6 py-10 text-center bg-gray-800 rounded-2xl shadow-lg">
                        <p class="text-xl font-semibold text-indigo-400">
                            Comience seleccionando el contexto
                        </p>
                        <p class="text-gray-400 mt-2">
                            Seleccione el curso, el año escolar y la fecha para cargar la lista de alumnos.
                        </p>
                    </div>
                <?php endif; ?>

                <!-- VOLVER -->
                <div class="mt-8 flex justify-start">
                    <a href="index.php?action=asistencia_index"
                        class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600">
                        ⬅ Volver a Asistencias
                    </a>
                </div>

            </div>
        </main>

    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>