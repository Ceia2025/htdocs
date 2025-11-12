<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="bg-gray-900 text-white min-h-screen">
    <div class="max-w-6xl mx-auto px-6 py-10">
        <!-- Título principal -->
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-indigo-600/20 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6l4 2m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-100 tracking-tight">Ingreso de Notas</h1>
        </div>

        <!-- 📘 Formulario -->
        <form method="POST"
            action="index.php?action=notas_storeGroup&curso_id=<?= $_GET['curso_id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
            class="bg-gray-800/70 backdrop-blur-sm border border-gray-700 rounded-2xl shadow-2xl p-8 space-y-8">

            <input type="hidden" name="semestre" value="<?= $semestreActual ?>">

            <!-- Campos principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Asignatura -->
                <div class="flex flex-col">
                    <label class="flex items-center gap-2 text-sm text-gray-300 mb-2">
                        📘 <span>Asignatura</span>
                    </label>
                    <select name="asignatura_id" required
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-indigo-500 appearance-none">
                        <option value="">Seleccionar...</option>
                        <?php foreach ($asignaturas as $asig): ?>
                            <option value="<?= $asig['id'] ?>"><?= htmlspecialchars($asig['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha -->
                <div class="flex flex-col">
                    <label class="flex items-center gap-2 text-sm text-gray-300 mb-2">
                        📅 <span>Fecha</span>
                    </label>
                    <div class="relative">
                        <input type="date" name="fecha" required
                            class="custom-date w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-indigo-500 cursor-pointer appearance-none">
                        <!-- Icono extra decorativo para mantener alineación -->
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <style>
                .custom-date::-webkit-calendar-picker-indicator {
                    opacity: 0;
                    /* ocultamos el ícono nativo */
                }
            </style>


            <!-- Tabla de alumnos -->
            <div class="overflow-hidden bg-gray-900/70 border border-gray-700 rounded-xl shadow-lg mt-8">
                <table class="min-w-full text-sm text-gray-300">
                    <thead class="bg-gray-800/90 text-gray-200 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Alumno</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                            <th class="px-6 py-3 text-center">Nota</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php foreach ($alumnos as $a): ?>
                            <tr class="hover:bg-gray-800/50 transition-colors duration-200">
                                <td class="px-6 py-3 font-medium">
                                    <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat'] . ' ' . $a['nombre']) ?>
                                </td>
                                <td class="px-6 py-3">
                                    <?= $a['deleted_at']
                                        ? '<span class="px-2 py-1 bg-red-900/40 text-red-300 rounded-lg text-xs">Retirado</span>'
                                        : '<span class="px-2 py-1 bg-green-900/40 text-green-300 rounded-lg text-xs">Activo</span>' ?>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <?php if ($a['deleted_at']): ?>
                                        <input type="number" step="0.1" value="0" readonly
                                            class="w-20 bg-gray-700 border border-gray-600 rounded-lg text-center text-gray-400">
                                    <?php else: ?>
                                        <input type="number" name="notas[<?= $a['alumno_id'] ?>][nota]" step="0.1" min="1"
                                            max="7" placeholder="Ej: 6.5"
                                            class="w-24 bg-gray-800 border border-gray-700 rounded-lg text-center focus:ring focus:ring-indigo-500">
                                    <?php endif; ?>

                                    <input type="hidden" name="notas[<?= $a['alumno_id'] ?>][matricula_id]"
                                        value="<?= $a['matricula_id'] ?>">
                                    <input type="hidden" name="notas[<?= $a['alumno_id'] ?>][deleted_at]"
                                        value="<?= $a['deleted_at'] ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Botón Guardar -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg transition-transform hover:scale-105">
                    💾 Guardar Notas
                </button>
            </div>
        </form>
    </div>
</body>


<?php include __DIR__ . "/../layout/footer.php"; ?>