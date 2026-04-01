<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

$edad = $this->calcularEdadAl30Junio($alumno['fechanac']);

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Avatar con iniciales
$iniciales = strtoupper(
    substr($alumno['nombre'] ?? '', 0, 1) .
    substr($alumno['apepat'] ?? '', 0, 1)
);

$esRetirado = !empty($alumno['deleted_at']);
?>

<main class="min-h-screen bg-gray-900 pb-20">

    <!-- HERO DEL PERFIL -->
    <div class="bg-gradient-to-r from-gray-800 via-gray-800 to-gray-900 border-b border-gray-700 shadow-xl">
        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                <!-- Avatar -->
                <div
                    class="flex-shrink-0 w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 
                            flex items-center justify-center shadow-lg shadow-indigo-900/50 text-white text-3xl font-bold tracking-wide">
                    <?= $iniciales ?>
                </div>

                <!-- Info principal -->
                <div class="flex-1 text-center sm:text-left">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-1">
                        <h1 class="text-2xl font-bold text-white capitalize">
                            <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat'] . ', ' . $alumno['nombre']) ?>
                        </h1>
                        <!-- Badge estado -->
                        <?php if ($esRetirado): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                         bg-red-900/50 border border-red-500 text-red-300">
                                🚫 Retirado el <?= (new DateTime($alumno['deleted_at']))->format('d/m/Y') ?>
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                         bg-green-900/50 border border-green-500 text-green-300">
                                ✅ Activo
                            </span>
                        <?php endif ?>
                    </div>
                    <p class="text-gray-400 text-sm">
                        RUN: <span
                            class="text-gray-200 font-medium"><?= htmlspecialchars($alumno['run'] . '-' . $alumno['codver']) ?></span>
                        <span class="mx-2 text-gray-600">·</span>
                        <?= htmlspecialchars($alumno['sexo'] === 'M' ? 'Masculino' : 'Femenino') ?>
                        <?php if ($edad !== null): ?>
                            <span class="mx-2 text-gray-600">·</span>
                            <?= $edad ?> años
                        <?php endif ?>
                    </p>
                    <p class="text-gray-500 text-xs mt-1">
                        Incorporado el
                        <?= !empty($alumno['created_at']) ? (new DateTime($alumno['created_at']))->format('d/m/Y') : 'N/A' ?>
                    </p>
                </div>

                <!-- Acciones rápidas -->
                <div class="flex gap-2 flex-shrink-0">
                    <?php if (AuthController::puede('alumno_edit')): ?>
                        <a href="index.php?action=alumno_edit&id=<?= $alumno['id'] ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 
                                   text-white text-sm font-semibold rounded-xl transition shadow">
                            ✏️ Editar
                        </a>
                    <?php endif ?>
                </div>

            </div>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">

        <!-- DATOS PERSONALES -->
        <div class="bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden shadow">
            <div class="px-6 py-4 border-b border-gray-700 flex items-center gap-2">
                <span class="text-indigo-400 text-lg">👤</span>
                <h2 class="text-base font-semibold text-white">Datos Personales</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-700">

                <!-- Columna izquierda -->
                <div class="px-6 py-5 space-y-3">
                    <?php
                    $campos = [
                        'Fecha de Nacimiento' => $alumno['fechanac'] ? (new DateTime($alumno['fechanac']))->format('d/m/Y') : 'No registrada',
                        'Edad (al 30 de junio)' => $edad !== null ? "$edad años" : 'No registrada',
                        'Email' => $alumno['email'] ?: 'No registrado',
                        'Teléfono' => $alumno['telefono'] ?: 'No registrado',
                        'Celular' => $alumno['celular'] ?: 'No registrado',
                    ];
                    foreach ($campos as $label => $valor): ?>
                        <div class="flex justify-between items-start gap-4">
                            <span class="text-xs text-gray-400 uppercase tracking-wider min-w-[130px]"><?= $label ?></span>
                            <span class="text-sm text-gray-100 text-right"><?= htmlspecialchars($valor) ?></span>
                        </div>
                    <?php endforeach ?>
                </div>

                <!-- Columna derecha -->
                <div class="px-6 py-5 space-y-3">
                    <?php
                    $campos2 = [
                        'Nacionalidad' => $alumno['nacionalidades'] ?: 'No registrada',
                        'Región' => $alumno['region'] ?: 'No registrada',
                        'Ciudad' => $alumno['ciudad'] ?: 'No registrada',
                        'Dirección' => $alumno['direccion'] ?: 'No registrada',
                        'Etnia' => $alumno['cod_etnia'] ?: 'No registrada',
                        'Mayor de edad' => $alumno['mayoredad'] ?: 'No',
                    ];
                    foreach ($campos2 as $label => $valor): ?>
                        <div class="flex justify-between items-start gap-4">
                            <span class="text-xs text-gray-400 uppercase tracking-wider min-w-[130px]"><?= $label ?></span>
                            <span class="text-sm text-gray-100 text-right"><?= htmlspecialchars($valor) ?></span>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <!-- CONTACTOS DE EMERGENCIA -->
        <div class="bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden shadow">
            <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-red-400 text-lg">🚨</span>
                    <h2 class="text-base font-semibold text-white">Contactos de Emergencia</h2>
                </div>
                <?php if (AuthController::puede('alum_emergencia_createProfile')): ?>
                    <a href="index.php?action=alum_emergencia_createProfile&alumno_id=<?= $alumno['id'] ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-700 hover:bg-green-600 
                               text-white text-xs font-semibold rounded-lg transition">
                        ➕ Agregar
                    </a>
                <?php endif ?>
            </div>

            <?php if (!empty($contactos)): ?>
                <div class="divide-y divide-gray-700">
                    <?php foreach ($contactos as $c): ?>
                        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-1 flex-1">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wider">Nombre</p>
                                    <p class="text-sm text-gray-100"><?= htmlspecialchars($c['nombre_contacto']) ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wider">Teléfono</p>
                                    <p class="text-sm text-gray-100"><?= htmlspecialchars($c['telefono']) ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wider">Relación</p>
                                    <p class="text-sm text-gray-100"><?= htmlspecialchars($c['relacion']) ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wider">Dirección</p>
                                    <p class="text-sm text-gray-100"><?= htmlspecialchars($c['direccion']) ?></p>
                                </div>
                            </div>
                            <div class="flex gap-2 flex-shrink-0">


                                <?php if ($rol === "administrador" || $rol === "administrativo" || $rol === "Inspector general y Convivencia escolar"): ?>

                                    <a href="index.php?action=alum_emergencia_edit&id=<?= $c['id'] ?>&back=alumno_profile&alumno_id=<?= $alumno['id'] ?>"
                                        class="px-3 py-1.5 bg-indigo-700 hover:bg-indigo-600 text-white text-xs font-semibold rounded-lg transition">
                                        Editar
                                    </a>
                                   <a href="index.php?action=alum_emergencia_deleteProfile&id=<?= $c['id'] ?>&alumno_id=<?= $alumno['id'] ?>"
                                        onclick="return confirm('¿Estás seguro de eliminar este contacto?');"
                                        class="px-3 py-1.5 bg-red-800 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition">
                                        Eliminar
                                    </a>

                                <?php endif; ?>
                                    
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <div class="px-6 py-8 text-center text-gray-500 text-sm italic">
                    No hay contactos de emergencia registrados.
                </div>
            <?php endif ?>
        </div>

        <!-- ANTECEDENTES FAMILIARES -->
        <div class="bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden shadow">
            <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-yellow-400 text-lg">👨‍👩‍👦</span>
                    <h2 class="text-base font-semibold text-white">Antecedentes Familiares</h2>
                </div>
                <?php if (AuthController::puede('antecedentefamiliar_editProfile')): ?>
                    <a href="index.php?action=antecedentefamiliar_editProfile&alumno_id=<?= $alumno['id'] ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-700 hover:bg-indigo-600 
                               text-white text-xs font-semibold rounded-lg transition">
                        ✏️ Editar
                    </a>
                <?php endif ?>
            </div>

            <?php if (!empty($antecedentes)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-700">
                    <div class="px-6 py-5 space-y-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-2">Padre</p>
                        <div class="flex justify-between"><span class="text-xs text-gray-400">Escolaridad</span><span
                                class="text-sm text-gray-100"><?= htmlspecialchars($antecedentes['padre'] ?: '—') ?></span>
                        </div>
                        <div class="flex justify-between"><span class="text-xs text-gray-400">Nivel / Ciclo</span><span
                                class="text-sm text-gray-100"><?= htmlspecialchars($antecedentes['nivel_ciclo_p'] ?: '—') ?></span>
                        </div>
                    </div>
                    <div class="px-6 py-5 space-y-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-2">Madre</p>
                        <div class="flex justify-between"><span class="text-xs text-gray-400">Escolaridad</span><span
                                class="text-sm text-gray-100"><?= htmlspecialchars($antecedentes['madre'] ?: '—') ?></span>
                        </div>
                        <div class="flex justify-between"><span class="text-xs text-gray-400">Nivel / Ciclo</span><span
                                class="text-sm text-gray-100"><?= htmlspecialchars($antecedentes['nivel_ciclo_m'] ?: '—') ?></span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="px-6 py-8 text-center text-gray-500 text-sm italic">
                    No hay antecedentes familiares registrados.
                </div>
            <?php endif ?>
        </div>

        <!-- ANTECEDENTE ESCOLAR -->
        <div class="bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden shadow">
            <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-blue-400 text-lg">🎓</span>
                    <h2 class="text-base font-semibold text-white">Antecedente Escolar</h2>
                </div>
                <?php if (AuthController::puede('antecedente_escolar_editProfile')): ?>
                    <a href="index.php?action=antecedente_escolar_editProfile&alumno_id=<?= $alumno['id'] ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-700 hover:bg-indigo-600 
                               text-white text-xs font-semibold rounded-lg transition">
                        ✏️ Editar
                    </a>
                <?php endif ?>
            </div>

            <?php if (!empty($alumno['antecedente_id'])): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-700">

                    <div class="px-6 py-5 space-y-3">
                        <p class="text-xs text-indigo-400 uppercase tracking-wider font-semibold mb-2">Datos Generales</p>
                        <?php
                        $escolar1 = [
                            'Procedencia' => $alumno['procedencia_colegio'] ?: '—',
                            'Comuna' => $alumno['comuna'] ?: '—',
                            'Último Curso' => $alumno['ultimo_curso'] ?: '—',
                            'Último Año' => $alumno['ultimo_anio_cursado'] ?: '—',
                            'Cursos Repetidos' => $alumno['cursos_repetidos'] ?: '—',
                            'Eval. Psicológica' => $alumno['eva_psico'] ?: 'No registrada',
                            'Info. Salud' => $alumno['info_salud'] ?: 'No registrada',
                        ];
                        foreach ($escolar1 as $l => $v): ?>
                            <div class="flex justify-between gap-4">
                                <span class="text-xs text-gray-400 min-w-[130px]"><?= $l ?></span>
                                <span class="text-sm text-gray-100 text-right"><?= htmlspecialchars($v) ?></span>
                            </div>
                        <?php endforeach ?>
                    </div>

                    <div class="px-6 py-5 space-y-3">
                        <p class="text-xs text-indigo-400 uppercase tracking-wider font-semibold mb-2">Condiciones y
                            Programas</p>
                        <?php
                        $escolar2 = [
                            'Pertenece al 20%' => $alumno['pertenece_20'] ? 'Sí' : 'No',
                            'Informe 20%' => $alumno['informe_20'] ? 'Sí' : 'No',
                            'Embarazo' => $alumno['embarazo'] ? 'Sí (' . ($alumno['semanas'] ?? '-') . ' semanas)' : 'No',
                            'Prob. Aprendizaje' => $alumno['prob_apren'] ?: '—',
                            'PIE' => $alumno['pie'] ?: '—',
                            'Chile Solidario' => $alumno['chile_solidario'] ? ($alumno['chile_solidario_cual'] ?? 'Sí') : 'No',
                            'Grupo Fonasa' => $alumno['grupo_fonasa'] ?: 'No registra',
                            'Isapre' => $alumno['isapre'] ?: 'No registra',
                            'Seguro Salud' => $alumno['seguro_salud'] ?: 'No registra',
                        ];
                        foreach ($escolar2 as $l => $v): ?>
                            <div class="flex justify-between gap-4">
                                <span class="text-xs text-gray-400 min-w-[130px]"><?= $l ?></span>
                                <span class="text-sm text-gray-100 text-right"><?= htmlspecialchars($v) ?></span>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="px-6 py-8 text-center text-gray-500 text-sm italic">
                    No hay antecedente escolar registrado para este alumno.
                </div>
            <?php endif ?>
        </div>

        <!-- VOLVER -->
        <div class="flex justify-center pt-2">
            <a href="index.php?action=alumnos" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-700 hover:bg-gray-600 
                       text-white text-sm font-semibold rounded-xl transition shadow">
                ⬅️ Volver al listado
            </a>
        </div>

    </div>
</main>

<!-- BOTÓN FLOTANTE PDF -->
<?php if (AuthController::puede('alumno_pdf')): ?>
    <a href="index.php?action=alumno_pdf&id=<?= $alumno['id'] ?>" class="fixed bottom-6 right-6 z-50 flex items-center gap-2 px-5 py-3 
               bg-green-600 hover:bg-green-500 text-white font-bold rounded-full 
               shadow-2xl shadow-green-900/50 transition-all duration-300 hover:scale-105">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
            class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        Descargar PDF
    </a>
<?php endif ?>

<?php include __DIR__ . "/../layout/footer.php"; ?>