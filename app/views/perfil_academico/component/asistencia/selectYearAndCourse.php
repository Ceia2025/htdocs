<?php
/**
 * Vista de formulario para seleccionar el contexto (Curso, Año, Fecha)
 * antes de tomar la asistencia.
 * Se asume que las variables $cursos, $anios y $fechaSeleccionada son proporcionadas por el controlador.
 */

require_once __DIR__ . "/../../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

// Las variables $cursos, $anios, $fechaSeleccionada deben ser provistas por el controlador.
// Si no están, se asumen valores predeterminados (simulados) para evitar errores.
$cursos = $cursos ?? [
    ['id' => 1, 'nombre' => 'Matemáticas Avanzadas', 'anio_default' => 2024],
    ['id' => 2, 'nombre' => 'Historia Universal', 'anio_default' => 2024],
    ['id' => 3, 'nombre' => 'Programación Web', 'anio_default' => 2023],
];
$anios = $anios ?? [2024, 2023, 2022];
$fechaSeleccionada = $fechaSeleccionada ?? date('Y-m-d');

// Nota: Esta vista no necesita $cursoSeleccionadoId ni $anioSeleccionado.

include __DIR__ . "/../../../layout/header.php";
include __DIR__ . "/../../../layout/navbar.php";
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="relative bg-gray-800 after:absolute after:inset-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6">
                <h1 class="text-3xl font-bold tracking-tight text-white">Seleccionar Contexto de Asistencia</h1>
                <p class="text-gray-400 mt-1">Elige el curso, el año y la fecha para cargar la lista de alumnos.</p>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-4xl px-4 py-8">

                <!-- FORMULARIO DE SELECCIÓN -->
                <!-- El action apunta a 'asistencia_create_form', que ahora se encargará de cargar los alumnos y la vista de registro. -->
                <form action="index.php" method="GET" class="bg-gray-800 rounded-xl p-8 shadow-2xl">
                    <input type="hidden" name="action" value="asistencia_create_form">

                    <div class="space-y-6">
                        
                        <!-- Selector de Curso -->
                        <div>
                            <label for="curso_id" class="block text-base font-medium text-gray-300 mb-2">Curso / Materia</label>
                            <select id="curso_id" name="curso_id" required
                                class="w-full rounded-lg bg-gray-700 border border-gray-600 text-white p-3 text-base focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">--- Seleccione Curso ---</option>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= $curso['id'] ?>">
                                        <?= htmlspecialchars($curso['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Selector de Año -->
                        <div>
                            <label for="anio" class="block text-base font-medium text-gray-300 mb-2">Año Escolar</label>
                            <select id="anio" name="anio" required
                                class="w-full rounded-lg bg-gray-700 border border-gray-600 text-white p-3 text-base focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">--- Seleccione Año ---</option>
                                <?php foreach ($anios as $anio): ?>
                                    <option value="<?= $anio ?>">
                                        <?= htmlspecialchars($anio) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Selector de Fecha -->
                        <div>
                            <label for="fecha" class="block text-base font-medium text-gray-300 mb-2">Fecha de Asistencia</label>
                            <input type="date" id="fecha" name="fecha" required
                                value="<?= htmlspecialchars($fechaSeleccionada) ?>"
                                class="w-full rounded-lg bg-gray-700 border border-gray-600 text-white p-3 text-base focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Botón Cargar Alumnos -->
                    <div class="mt-8 flex justify-center">
                         <button type="submit" 
                                class="w-full group inline-flex items-center justify-center gap-2 px-6 py-3 
                                bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-lg rounded-xl shadow-lg
                                transition-all duration-300">
                            Cargar Lista de Asistencia
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>

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

<?php include __DIR__ . "/../../layout/footer.php"; ?>