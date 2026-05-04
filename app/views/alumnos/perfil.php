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
                        <?php if (!empty($alumno['fechanac'])):
                            $hoy = new DateTime();
                            $nac = new DateTime($alumno['fechanac']);
                            $diff = $hoy->diff($nac);
                            $edadReal = $diff->y;
                            $meses = $diff->m;
                            $dias = $diff->d;
                            $esMayor = $edadReal >= 18;

                            // Cuánto le falta para los 18 si es menor
                            if (!$esMayor) {
                                $cumple18 = clone $nac;
                                $cumple18->modify('+18 years');
                                $falta = $hoy->diff($cumple18);
                            }
                            ?>
                            <span class="mx-2 text-gray-600">·</span>
                            <?php if ($esMayor): ?>
                                <span class="text-gray-200"><?= $edadReal ?> años</span>
                                <span
                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-900/40 border border-blue-700 text-blue-300">
                                    Mayor de edad
                                </span>
                            <?php else: ?>
                                <span class="text-gray-200"><?= $edadReal ?> años, <?= $meses ?> meses y <?= $dias ?>
                                    días</span>
                                <span
                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-900/40 border border-amber-700 text-amber-300">
                                    Menor · faltan <?= $falta->y > 0 ? $falta->y . ' año(s), ' : '' ?><?= $falta->m ?> mes(es) y
                                    <?= $falta->d ?> día(s) para los 18
                                </span>
                            <?php endif ?>
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


    <div class="mx-auto max-w-5xl px-4 pt-8 sm:px-6 lg:px-8">
        <div class="bg-gray-800 border border-lime-800/50 rounded-2xl overflow-hidden shadow-lg shadow-lime-900/20">

            <!-- RESUMEN ANAMNESIS -->
            <?php if (AuthController::puede('anamnesis_form')): ?>
                <div class="bg-gray-800 border border-lime-700/40 rounded-2xl overflow-hidden shadow">
                    <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-lime-400 text-lg">🧠</span>
                            <h2 class="text-base font-semibold text-white">Resumen de Anamnesis</h2>
                        </div>
                        <a href="index.php?action=anamnesis_form&alumno_id=<?= $alumno['id'] ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-700 hover:bg-purple-600 
                        text-white text-xs font-semibold rounded-lg transition">
                            ✏️ Ver / Editar
                        </a>
                    </div>
                    <?php
                    require_once __DIR__ . '/../../models/ResumenAnamnesis.php';
                    require_once __DIR__ . '/../../models/Anio.php';
                    $anamnesisModel = new ResumenAnamnesis();
                    $anioModel = new Anio();
                    $anios = $anioModel->getAll();
                    $anioActualId = !empty($anios) ? $anios[0]['id'] : null;
                    $anamnesisActual = $anioActualId
                        ? $anamnesisModel->getByAlumnoYAnio($alumno['id'], $anioActualId)
                        : null;
                    ?>
                    <?php if ($anamnesisActual): ?>
                        <div class="px-6 py-5 space-y-3">
                            <div class="flex justify-between items-start gap-4">
                                <span class="text-xs text-gray-400 uppercase tracking-wider min-w-[130px]">Realizado por</span>
                                <span class="text-sm text-gray-100 text-right">
                                    <?= htmlspecialchars($anamnesisActual['realizado_por']) ?>
                                    <?= $anamnesisActual['relacion']
                                        ? '<span class="text-gray-500"> (' . htmlspecialchars($anamnesisActual['relacion']) . ')</span>'
                                        : '' ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-start gap-4">
                                <span class="text-xs text-gray-400 uppercase tracking-wider min-w-[130px]">Año</span>
                                <span class="text-sm text-gray-100">
                                    <?= $anamnesisActual['anio_escolar'] ?>
                                </span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Observaciones</p>
                                <p class="text-sm text-gray-300 leading-relaxed">
                                    <?= nl2br(htmlspecialchars($anamnesisActual['observaciones'] ?? '—')) ?>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="px-6 py-8 text-center text-gray-500 text-sm italic">
                            No hay anamnesis registrada para el año actual.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- MATRÍCULA ACTIVA -->
    <div class="mx-auto max-w-5xl px-4 pt-8 sm:px-6 lg:px-8">
        <div class="bg-gray-800 border border-purple-800/50 rounded-2xl overflow-hidden shadow-lg shadow-purple-900/20">

            <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-purple-400 text-lg">📋</span>
                    <h2 class="text-base font-semibold text-white">Matrícula Activa</h2>
                </div>
                <?php if (!empty($matriculaActiva)): ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                             bg-purple-900/50 border border-purple-600 text-purple-300">
                        <?= htmlspecialchars($matriculaActiva['anio_escolar']) ?>
                    </span>
                <?php endif ?>
            </div>

            <?php if (!empty($matriculaActiva)): ?>
                <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-700">

                    <!-- Curso -->
                    <div class="px-6 py-5 flex flex-col items-center text-center gap-1">
                        <span class="text-2xl">🏫</span>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mt-1">Curso</p>
                        <p class="text-lg font-bold text-white">
                            <?= htmlspecialchars($matriculaActiva['curso_nombre']) ?>
                        </p>
                    </div>

                    <!-- Año escolar -->
                    <div class="px-6 py-5 flex flex-col items-center text-center gap-1">
                        <span class="text-2xl">📅</span>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mt-1">Año Escolar</p>
                        <p class="text-lg font-bold text-white">
                            <?= htmlspecialchars($matriculaActiva['anio_escolar']) ?>
                        </p>
                    </div>

                    <!-- N° de lista -->
                    <div class="px-6 py-5 flex flex-col items-center text-center gap-1">
                        <span class="text-2xl">🔢</span>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mt-1">N° Lista</p>
                        <p class="text-lg font-bold text-white">
                            <?= $matriculaActiva['numero_lista'] ?? '—' ?>
                        </p>
                    </div>

                    <!-- Fecha matrícula -->
                    <div class="px-6 py-5 flex flex-col items-center text-center gap-1">
                        <span class="text-2xl">📝</span>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mt-1">Fecha Matrícula</p>
                        <p class="text-lg font-bold text-white">
                            <?= $matriculaActiva['fecha_matricula']
                                ? (new DateTime($matriculaActiva['fecha_matricula']))->format('d/m/Y')
                                : '—' ?>
                        </p>
                    </div>

                </div>
            <?php else: ?>
                <div class="px-6 py-10 flex flex-col items-center gap-2 text-center">
                    <span class="text-4xl">📭</span>
                    <p class="text-gray-400 text-sm font-medium">Sin matrícula activa</p>
                    <p class="text-gray-600 text-xs">Este alumno no está matriculado en ningún curso actualmente.</p>
                </div>
            <?php endif ?>

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
                    <h2 class="text-base font-semibold text-white">Contactos y Antecedentes Familiares</h2>
                </div>
                <?php if (AuthController::puede('alum_emergencia_createProfile')): ?>
                    <a href="index.php?action=alum_emergencia_createProfile&alumno_id=<?= $alumno['id'] ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-700 hover:bg-green-600
                      text-white text-xs font-semibold rounded-lg transition">
                        ➕ Agregar
                    </a>
                <?php endif ?>
            </div>

            <?php
            // Helper para mostrar una fila de dato
            $fila = fn($label, $valor) =>
                '<div class="flex justify-between items-start gap-4">
            <span class="text-xs text-gray-400 uppercase tracking-wider min-w-[140px]">' . $label . '</span>
            <span class="text-sm text-gray-100 text-right">' . htmlspecialchars($valor ?: '—') . '</span>
        </div>';

            // Helper para renderizar una tarjeta de contacto
            $tarjetaContacto = function ($c, $mostrarObservacion = false) use ($fila, $alumno, $rol) {
                echo '<div class="px-6 py-4 flex flex-col gap-3">';
                echo '<div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2">';
                echo $fila('Nombre', $c['nombre_contacto']);
                echo $fila('R.U.N.', $c['run_contacto']);
                echo $fila('Dirección', $c['direccion']);
                echo $fila('Correo electrónico', $c['email']);
                echo $fila('Comuna o Sector', $c['comuna']);
                echo $fila('Vínculo', $c['relacion']);
                echo $fila('Teléfono', $c['telefono']);
                echo $fila('Celular', $c['celular'] ? '+569 ' . $c['celular'] : null);
                echo '</div>';

                if ($mostrarObservacion) {
                    echo '<div class="mt-3 pt-3 border-t border-gray-700">';
                    echo '<p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Observación</p>';
                    echo '<p class="text-sm text-gray-300 leading-relaxed">'
                        . nl2br(htmlspecialchars($c['observacion'] ?? '—')) . '</p>';
                    echo '</div>';
                }

                // Botones editar/eliminar
                if (in_array($rol, ['administrador', 'administrativo', 'Inspector general y Convivencia escolar'])): ?>
                    <div class="flex gap-2 mt-2">
                        <a href="index.php?action=alum_emergencia_edit&id=<?= $c['id'] ?>&back=alumno_profile&alumno_id=<?= $alumno['id'] ?>"
                            class="px-3 py-1.5 bg-indigo-700 hover:bg-indigo-600 text-white text-xs font-semibold rounded-lg transition">
                            Editar
                        </a>
                        <a href="index.php?action=alum_emergencia_deleteProfile&id=<?= $c['id'] ?>&alumno_id=<?= $alumno['id'] ?>"
                            onclick="return confirm('¿Estás seguro de eliminar este contacto?');"
                            class="px-3 py-1.5 bg-red-800 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition">
                            Eliminar
                        </a>
                    </div>
                <?php endif;
                echo '</div>';
            };

            // ── SECCIÓN 1: Padre / Madre / Tutor Legal ──
            ?>
            <div class="border-b border-gray-700">
                <div class="px-6 py-3 bg-gray-750 flex items-center gap-2">
                    <span class="text-yellow-400">👨‍👩‍👦</span>
                    <h3 class="text-sm font-semibold text-yellow-300 uppercase tracking-wider">
                        Antecedentes del Padre, Madre o Tutor Legal
                    </h3>
                </div>
                <?php if (!empty($contactosGrupos['padre_madre_tutor'])): ?>
                    <div class="divide-y divide-gray-700">
                        <?php foreach ($contactosGrupos['padre_madre_tutor'] as $c): ?>
                            <?php $tarjetaContacto($c, false); ?>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <p class="px-6 py-5 text-gray-500 text-sm italic">No hay antecedentes del padre/madre/tutor registrados.
                    </p>
                <?php endif ?>
            </div>

            <!-- ── SECCIÓN 2: Apoderado ── -->
            <div class="border-b border-gray-700">
                <div class="px-6 py-3 bg-gray-750 flex items-center gap-2">
                    <span class="text-blue-400">🧑‍💼</span>
                    <h3 class="text-sm font-semibold text-blue-300 uppercase tracking-wider">
                        Representante o Apoderado del Estudiante
                    </h3>
                </div>
                <?php if (!empty($contactosGrupos['apoderado'])): ?>
                    <div class="divide-y divide-gray-700">
                        <?php foreach ($contactosGrupos['apoderado'] as $c): ?>
                            <?php $tarjetaContacto($c, false); ?>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <p class="px-6 py-5 text-gray-500 text-sm italic">No hay apoderado registrado.</p>
                <?php endif ?>
            </div>

            <!-- ── SECCIÓN 3: Emergencia / Apoderado Suplente ── -->
            <div>
                <div class="px-6 py-3 bg-gray-750 flex items-center gap-2">
                    <span class="text-red-400">🆘</span>
                    <h3 class="text-sm font-semibold text-red-300 uppercase tracking-wider">
                        Avisar en Caso de Emergencia / Apoderado Suplente
                    </h3>
                </div>
                <?php if (!empty($contactosGrupos['emergencia'])): ?>
                    <div class="divide-y divide-gray-700">
                        <?php foreach ($contactosGrupos['emergencia'] as $c): ?>
                            <?php $tarjetaContacto($c, true); // true = mostrar observación ?>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <p class="px-6 py-5 text-gray-500 text-sm italic">No hay contacto de emergencia registrado.</p>
                <?php endif ?>
            </div>
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