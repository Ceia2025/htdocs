<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>
<main>
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Anotaciones del Alumno</h1>
        </div>
    </header>

    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- CONTADORES -->
        <?php
        $totales = ['Registro' => 0, 'Positiva' => 0, 'Leve' => 0, 'Grave' => 0, 'Gravísima' => 0];
        foreach ($anotaciones as $an)
            $totales[$an['tipo']]++;
        $total = count($anotaciones);
        ?>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-gray-800/60 border border-blue-800 rounded-2xl px-5 py-4 flex items-center gap-3 shadow">
                <span class="text-3xl font-bold text-blue-400">
                    <?= $totales['Registro'] ?>
                </span>
                <div>
                    <p class="text-white font-semibold text-sm">Registros</p>
                </div>
            </div>
            <div class="bg-gray-800/60 border border-green-800 rounded-2xl px-5 py-4 flex items-center gap-3 shadow">
                <span class="text-3xl font-bold text-green-400">
                    <?= $totales['Positiva'] ?>
                </span>
                <div>
                    <p class="text-white font-semibold text-sm">Positivas</p>
                </div>
            </div>
            <div class="bg-gray-800/60 border border-yellow-800 rounded-2xl px-5 py-4 flex items-center gap-3 shadow">
                <span class="text-3xl font-bold text-yellow-400">
                    <?= $totales['Leve'] ?>
                </span>
                <div>
                    <p class="text-white font-semibold text-sm">Leves</p>
                </div>
            </div>
            <div class="bg-gray-800/60 border border-orange-800 rounded-2xl px-5 py-4 flex items-center gap-3 shadow">
                <span class="text-3xl font-bold text-orange-400">
                    <?= $totales['Grave'] ?>
                </span>
                <div>
                    <p class="text-white font-semibold text-sm">Graves</p>
                </div>
            </div>
            <div class="bg-gray-800/60 border border-red-800 rounded-2xl px-5 py-4 flex items-center gap-3 shadow">
                <span class="text-3xl font-bold text-red-400">
                    <?= $totales['Gravísima'] ?>
                </span>
                <div>
                    <p class="text-white font-semibold text-sm">Gravísimas</p>
                </div>
            </div>
        </div>

        <!-- BOTÓN AGREGAR -->
        <div class="flex justify-between items-center mb-4">
            <p class="text-gray-400 text-sm">
                <?= $total ?> anotación
                <?= $total !== 1 ? 'es' : '' ?> registrada
                <?= $total !== 1 ? 's' : '' ?>
            </p>
            <a href="index.php?action=anotacion_create&anio_id=<?= $anio_id ?>&curso_id=<?= $curso_id ?>&matricula_id=<?= $matricula_id ?>"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white font-semibold rounded-xl px-4 py-2 shadow transition text-sm">
                ➕ Nueva Anotación
            </a>
        </div>

        <!-- LISTA DE ANOTACIONES -->
        <?php if (!empty($anotaciones)): ?>
            <div class="space-y-4">
                <?php
                $colores = [
                    'Registro' => ['border' => 'border-blue-700', 'badge' => 'bg-blue-900/40 border-blue-500 text-blue-300', 'icon' => '📋'],
                    'Positiva' => ['border' => 'border-green-700', 'badge' => 'bg-green-900/40 border-green-500 text-green-300', 'icon' => '✅'],
                    'Leve' => ['border' => 'border-yellow-700', 'badge' => 'bg-yellow-900/40 border-yellow-500 text-yellow-300', 'icon' => '⚠️'],
                    'Grave' => ['border' => 'border-orange-700', 'badge' => 'bg-orange-900/40 border-orange-500 text-orange-300', 'icon' => '🔶'],
                    'Gravísima' => ['border' => 'border-red-700', 'badge' => 'bg-red-900/40 border-red-500 text-red-300', 'icon' => '🚨'],
                ];
                foreach ($anotaciones as $an):
                    $cfg = $colores[$an['tipo']] ?? $colores['Leve'];
                    ?>
                    <div class="bg-gray-800/60 border <?= $cfg['border'] ?> rounded-2xl p-5 shadow">
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                            <!-- Badge tipo + asignatura -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <span
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-lg border text-xs font-bold <?= $cfg['badge'] ?>">
                                    <?= $cfg['icon'] ?>
                                    <?= $an['tipo'] ?>
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-gray-700 border border-gray-600 text-gray-200 rounded-lg text-xs font-medium">
                                    📚
                                    <?= htmlspecialchars($an['asignatura_nombre']) ?>
                                    <?php if ($an['abreviatura']): ?>
                                        <span class="ml-1 text-gray-400">(
                                            <?= htmlspecialchars($an['abreviatura']) ?>)
                                        </span>
                                    <?php endif ?>
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-gray-700 border border-gray-600 text-gray-300 rounded-lg text-xs">
                                    <?= $an['semestre'] ?>° Semestre
                                </span>
                                <?php if ($an['notificado_apoderado'] === 'Si'): ?>
                                    <span
                                        class="inline-flex items-center px-3 py-1 bg-blue-900/40 border border-blue-600 text-blue-300 rounded-lg text-xs font-medium">
                                        ✉️ Apoderado notificado
                                    </span>
                                <?php endif ?>
                            </div>

                            <!-- Fecha + acciones -->
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <span>📅
                                    <?= (new DateTime($an['fecha_anotacion']))->format('d/m/Y') ?>
                                </span>
                                <?php if (AuthController::puede('anotacion_delete')): ?>
                                    <a href="index.php?action=anotacion_delete&id=<?= $an['id'] ?>&anio_id=<?= $anio_id ?>&curso_id=<?= $curso_id ?>"
                                        onclick="return confirm('¿Eliminar esta anotación?')"
                                        class="text-red-400 hover:text-red-300 font-medium">🗑️ Eliminar</a>
                                <?php endif ?>

                            </div>
                        </div>

                        <!-- Contenido -->
                        <p class="text-gray-200 text-sm leading-relaxed">
                            <?= nl2br(htmlspecialchars($an['contenido'])) ?>
                        </p>

                        <!-- Acción realizada -->
                        <?php if (!empty($an['accion_realizada'])): ?>
                            <div class="mt-3 pt-3 border-t border-gray-700/50 flex items-start gap-2">
                                <span class="text-xs text-gray-400 font-medium min-w-fit">⚡ Acción:</span>
                                <span class="text-xs text-gray-300">
                                    <?= htmlspecialchars($an['accion_realizada']) ?>
                                </span>
                            </div>
                        <?php endif ?>
                    </div>
                <?php endforeach ?>
            </div>

        <?php else: ?>
            <div class="text-center py-16 text-gray-500">
                <p class="text-5xl mb-4">📋</p>
                <p class="text-lg">Este alumno no tiene anotaciones
                    <?= $semestre ? 'en el ' . $semestre . '° semestre' : 'registradas' ?>.
                </p>
            </div>
        <?php endif ?>

        <!-- VOLVER -->
        <div class="mt-8 flex justify-center gap-4">
            <a href="index.php?action=anotaciones&anio_id=<?= $anio_id ?>&curso_id=<?= $curso_id ?><?= $semestre ? '&semestre=' . $semestre : '' ?>"
                class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                ⬅️ Volver al listado
            </a>
        </div>

    </div>
</main>


<?php if (!empty($_GET['anio_id']) && !empty($_GET['curso_id']) && !empty($_GET['matricula_id'])): ?>
    <a href="index.php?action=anotaciones_individual_pdf&anio_id=<?= $_GET['anio_id'] ?>&curso_id=<?= $_GET['curso_id'] ?>&matricula_id=<?= $_GET['matricula_id'] ?>&semestre=<?= htmlspecialchars($_GET['semestre'] ?? '') ?>"
        target="_blank"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-500 text-white font-bold rounded-full shadow-2xl transition-all hover:scale-105">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        <span>Exportar Anotaciones</span>
    </a>
<?php endif; ?>

<?php include __DIR__ . "/../layout/footer.php"; ?>