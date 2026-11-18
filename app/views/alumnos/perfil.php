<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
require_once __DIR__ . "/../../controllers/AnamnesisResumenController.php";
require_once __DIR__ . "/../../models/ResumenAnamnesis.php";
require_once __DIR__ . "/../../models/Anio.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

$edad = $this->calcularEdadAl30Junio($alumno['fechanac']);

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Lee un ícono de app/public/svg/{key}.svg y lo devuelve listo para inyectar inline.
// $classes se agrega al <svg> (por eso reemplazamos el atributo width/height del archivo).
function saat_icon(string $key, string $classes = 'w-5 h-5'): string
{
    $path = __DIR__ . "/../../public/svg/{$key}.svg";
    if (!file_exists($path)) {
        return '';
    }
    $svg = file_get_contents($path);
    // Inserta la clase justo después de <svg para poder controlar el tamaño por Tailwind
    return preg_replace('/<svg /', '<svg class="' . htmlspecialchars($classes) . '" ', $svg, 1);
}

$iniciales = strtoupper(
    substr($alumno['nombre'] ?? '', 0, 1) .
    substr($alumno['apepat'] ?? '', 0, 1)
);
$esRetirado = !empty($alumno['deleted_at']);

/* ================= ANAMNESIS: datos ================= */
$puedeVerAnamnesis    = AuthController::puede('anamnesis_ver') || AuthController::puede('anamnesis_form');
$puedeEditarAnamnesis = AuthController::puede('anamnesis_guardar');

$anamnesisActual    = false;
$historialAnamnesis = [];
$anioActualId       = null;
$anioActualLabel    = '';
$flashAnamnesis     = null;

if ($puedeVerAnamnesis) {
    $aniosAnamnesis  = (new Anio())->getAll();
    $anioActualId    = $aniosAnamnesis[0]['id'] ?? null;
    $anioActualLabel = (string) ($aniosAnamnesis[0]['anio'] ?? '');

    $anamnesisModel = new ResumenAnamnesis();
    if ($anioActualId) {
        $anamnesisActual = $anamnesisModel->getCompletaByAlumnoYAnio($alumno['id'], $anioActualId);
    }
    $historialAnamnesis = array_values(array_filter(
        $anamnesisModel->getAllByAlumno($alumno['id']),
        fn($h) => (int) $h['anio_id'] !== (int) $anioActualId
    ));
    $flashAnamnesis = AnamnesisResumenController::consumirFlash();
}

$mapJornada  = ['diurna' => 'Diurna', 'vespertina' => 'Vespertina'];
$mapVivienda = ['propia' => 'Propia', 'arrendada' => 'Arrendada', 'cedida_allegado' => 'Cedida / allegado'];
$mapConsumo  = ['si' => 'Sí', 'no' => 'No', 'no_sabe' => 'No sabe / No responde'];

$siNo = fn($v) => $v === null || $v === '' ? null : ((int) $v === 1 ? 'Sí' : 'No');

$filaAn = fn(string $label, $valor) =>
    '<div class="flex justify-between items-start gap-4">
        <span class="text-xs text-muted uppercase tracking-wider min-w-[130px]">' . $label . '</span>
        <span class="text-sm text-soft text-right">' . htmlspecialchars(($valor === null || $valor === '') ? '—' : (string) $valor) . '</span>
    </div>';

$textoAn = fn(string $titulo, $texto) =>
    '<div>
        <p class="text-xs text-muted uppercase tracking-wider mb-1">' . $titulo . '</p>
        <p class="text-sm text-soft leading-relaxed">' . nl2br(htmlspecialchars(($texto === null || $texto === '') ? '—' : (string) $texto)) . '</p>
    </div>';

$tituloAn = fn(string $t) => '<h3 class="text-xs font-bold uppercase tracking-wider text-accent mb-3">' . $t . '</h3>';

$chipAn = function (string $label, ?string $texto, string $paleta): string {
    $clase = str_starts_with($paleta, 'report-') ? 'report-badge ' . $paleta : $paleta;
    return '<span class="' . $clase . ' inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold">'
        . htmlspecialchars($label . ': ' . ($texto ?? 'Sin registrar')) . '</span>';
};
$paletaSiNo = fn($v, string $siPaleta = 'report-amber') =>
    $v === null || $v === '' ? 'chip-counter' : ((int) $v === 1 ? $siPaleta : 'report-emerald');
?>

<main class="min-h-screen pb-20">

    <!-- HERO -->
    <div class="page-header">
        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                <!-- Avatar -->
                <div class="avatar-badge flex-shrink-0 w-24 h-24 rounded-2xl text-3xl font-bold
                            flex items-center justify-center shadow-lg">
                    <?= $iniciales ?>
                </div>

                <!-- Info principal -->
                <div class="flex-1 text-center sm:text-left">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-1 flex-wrap">
                        <h1 class="text-2xl font-bold text-strong font-display capitalize">
                            <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat'] . ', ' . $alumno['nombre']) ?>
                        </h1>
                        <?php if ($esRetirado): ?>
                            <span
                                class="chip-danger inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold">
                                <?= saat_icon('exit', 'w-3.5 h-3.5') ?>
                                Retirado el <?= (new DateTime($alumno['deleted_at']))->format('d/m/Y') ?>
                            </span>
                        <?php else: ?>
                            <span
                                class="banner-success inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold">
                                <?= saat_icon('check', 'w-3.5 h-3.5') ?>
                                Activo
                            </span>
                        <?php endif ?>
                    </div>

                    <p class="text-muted text-sm flex flex-wrap items-center gap-1">
                        RUN: <span
                            class="text-soft font-medium"><?= htmlspecialchars($alumno['run'] . '-' . $alumno['codver']) ?></span>
                        <span class="text-faint mx-1">·</span>
                        <?= htmlspecialchars($alumno['sexo'] === 'M' ? 'Masculino' : 'Femenino') ?>

                        <?php if (!empty($alumno['fechanac'])):
                            $hoy = new DateTime();
                            $nac = new DateTime($alumno['fechanac']);
                            $diff = $hoy->diff($nac);
                            $edadReal = $diff->y;
                            $meses = $diff->m;
                            $dias = $diff->d;
                            $esMayor = $edadReal >= 18;
                            if (!$esMayor) {
                                $cumple18 = clone $nac;
                                $cumple18->modify('+18 years');
                                $falta = $hoy->diff($cumple18);
                            }
                            ?>
                            <span class="text-faint mx-1">·</span>
                            <?php if ($esMayor): ?>
                                <span class="text-soft"><?= $edadReal ?> años</span>
                                <span
                                    class="chip-counter inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold ml-1">
                                    Mayor de edad
                                </span>
                            <?php else: ?>
                                <span class="text-soft"><?= $edadReal ?> años, <?= $meses ?> meses y <?= $dias ?> días</span>
                                <span
                                    class="chip-warn inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold ml-1">
                                    Menor · faltan <?= $falta->y > 0 ? $falta->y . ' año(s), ' : '' ?><?= $falta->m ?> mes(es) y
                                    <?= $falta->d ?> día(s) para los 18
                                </span>
                            <?php endif ?>
                        <?php endif ?>
                    </p>

                    <p class="text-faint text-xs mt-1">
                        Incorporado el
                        <?= !empty($alumno['created_at']) ? (new DateTime($alumno['created_at']))->format('d/m/Y') : 'N/A' ?>
                    </p>
                </div>

                <!-- Acciones -->
                <div class="flex gap-2 flex-shrink-0">
                    <?php if (AuthController::puede('alumno_edit')): ?>
                        <a href="index.php?action=alumno_edit&id=<?= $alumno['id'] ?>"
                            class="btn-primary inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl transition shadow">
                            <?= saat_icon('edit', 'w-4 h-4') ?>
                            Editar
                        </a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ANAMNESIS -->
    <?php if ($puedeVerAnamnesis): ?>
        <div id="anamnesis" class="mx-auto max-w-5xl px-4 pt-6 sm:px-6 lg:px-8 scroll-mt-6">
            <div class="panel rounded-2xl overflow-hidden shadow-lg" style="border-color: var(--success);">

                <!-- Encabezado -->
                <div class="px-6 py-4 border-b divider-soft flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <?= saat_icon('brain', 'w-5 h-5 text-muted') ?>
                        <h2 class="text-base font-semibold text-strong">Anamnesis</h2>
                        <?php if ($anioActualLabel !== ''): ?>
                            <span class="report-badge report-emerald px-2.5 py-0.5 rounded-full text-xs font-bold">
                                <?= htmlspecialchars($anioActualLabel) ?>
                            </span>
                        <?php endif ?>
                    </div>
                    <?php if ($puedeEditarAnamnesis && $anioActualId): ?>
                        <button type="button" data-abrir-anamnesis
                            class="btn-soft-info inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                            <?= saat_icon($anamnesisActual ? 'edit' : 'plus', 'w-3.5 h-3.5') ?>
                            <?= $anamnesisActual ? 'Editar' : 'Registrar' ?>
                        </button>
                    <?php endif ?>
                </div>

                <!-- Mensaje de resultado -->
                <?php if (!empty($flashAnamnesis)): ?>
                    <div class="px-6 pt-5">
                        <div class="<?= $flashAnamnesis['tipo'] === 'ok' ? 'banner-success' : 'banner-danger' ?> text-sm px-4 py-3 rounded-xl">
                            <?= htmlspecialchars($flashAnamnesis['mensaje']) ?>
                        </div>
                    </div>
                <?php endif ?>

                <?php if ($anamnesisActual):
                    $a = $anamnesisActual;
                    $responsable = $a['realizado_por'] . (!empty($a['relacion']) ? ' (' . $a['relacion'] . ')' : '');
                    $entrevistador = $a['entrevistador_nombre']
                        ? $a['entrevistador_nombre'] . (!empty($a['entrevistador_rol']) ? ' (' . $a['entrevistador_rol'] . ')' : '')
                        : null;
                    ?>
                    <div class="px-6 py-5 space-y-5">

                        <!-- Resumen -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-3">
                            <?= $filaAn('Responsable', $responsable) ?>
                            <?= $filaAn('Jornada', $mapJornada[$a['jornada'] ?? ''] ?? null) ?>
                            <?= $filaAn('Integrantes del hogar', $a['total_integrantes']) ?>
                            <?= $filaAn('Vivienda', $mapVivienda[$a['vivienda_tipo'] ?? ''] ?? null) ?>
                            <?= $filaAn('Entrevistado por', $entrevistador) ?>
                            <?= $filaAn('Fecha entrevista', !empty($a['fecha_entrevista']) ? (new DateTime($a['fecha_entrevista']))->format('d/m/Y') : null) ?>
                        </div>

                        <!-- Indicadores -->
                        <div class="flex flex-wrap gap-2">
                            <?= $chipAn('Problemas de salud', $siNo($a['salud_problemas']), $paletaSiNo($a['salud_problemas'])) ?>
                            <?= $chipAn('Tratamientos', $siNo($a['salud_tratamientos']), $paletaSiNo($a['salud_tratamientos'])) ?>
                            <?= $chipAn(
                                'Consumo de sustancias',
                                $mapConsumo[$a['consumo_sustancias'] ?? ''] ?? null,
                                match ($a['consumo_sustancias'] ?? null) {
                                    'si'      => 'report-red',
                                    'no'      => 'report-emerald',
                                    'no_sabe' => 'report-amber',
                                    default   => 'chip-counter',
                                }
                            ) ?>
                        </div>

                        <!-- Detalle completo (solo lectura) -->
                        <details class="group rounded-xl border divider-soft">
                            <summary class="cursor-pointer select-none px-4 py-3 text-sm font-semibold text-accent flex items-center justify-between list-none">
                                Ver anamnesis completa
                                <span class="transition-transform group-open:rotate-180">▾</span>
                            </summary>

                            <div class="px-4 pb-5 pt-2 space-y-6">

                                <section>
                                    <?= $tituloAn('Apoderado / responsable') ?>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-2">
                                        <?= $filaAn('Nombre', $a['realizado_por']) ?>
                                        <?= $filaAn('Parentesco', $a['relacion']) ?>
                                        <?= $filaAn('RUT', $a['apoderado_run']) ?>
                                        <?= $filaAn('Edad', $a['apoderado_edad'] ? $a['apoderado_edad'] . ' años' : null) ?>
                                        <?= $filaAn('Domicilio', $a['apoderado_domicilio']) ?>
                                        <?= $filaAn('Estado civil', $a['apoderado_estado_civil']) ?>
                                        <?= $filaAn('Nivel educacional', $a['apoderado_nivel_educacional']) ?>
                                        <?= $filaAn('Ocupación', $a['apoderado_ocupacion']) ?>
                                        <?= $filaAn('Teléfono', $a['apoderado_telefono']) ?>
                                        <?= $filaAn('Correo', $a['apoderado_email']) ?>
                                    </div>
                                </section>

                                <section>
                                    <?= $tituloAn('Grupo familiar' . ($a['total_integrantes'] ? ' · ' . (int) $a['total_integrantes'] . ' integrantes' : '')) ?>
                                    <?php if (!empty($a['integrantes'])): ?>
                                        <div class="overflow-x-auto">
                                            <table class="w-full min-w-[600px] text-left text-sm">
                                                <thead>
                                                    <tr class="text-xs text-muted uppercase tracking-wider border-b divider-soft">
                                                        <th class="py-2 pr-3 font-semibold">Nombre</th>
                                                        <th class="py-2 pr-3 font-semibold">Edad</th>
                                                        <th class="py-2 pr-3 font-semibold">Parentesco</th>
                                                        <th class="py-2 pr-3 font-semibold">Ocupación</th>
                                                        <th class="py-2 font-semibold">Centro de salud (APS)</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divider-soft">
                                                    <?php foreach ($a['integrantes'] as $int): ?>
                                                        <tr class="text-soft">
                                                            <td class="py-2 pr-3"><?= htmlspecialchars($int['nombre']) ?></td>
                                                            <td class="py-2 pr-3"><?= htmlspecialchars($int['edad'] ?? '—') ?></td>
                                                            <td class="py-2 pr-3"><?= htmlspecialchars($int['parentesco'] ?? '—') ?></td>
                                                            <td class="py-2 pr-3"><?= htmlspecialchars($int['ocupacion'] ?? '—') ?></td>
                                                            <td class="py-2"><?= htmlspecialchars($int['centro_salud'] ?? '—') ?></td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-sm text-muted italic">Sin integrantes registrados.</p>
                                    <?php endif ?>
                                </section>

                                <section class="space-y-3">
                                    <?= $tituloAn('Situación laboral') ?>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-2">
                                        <?= $filaAn('Apoderado', $a['sit_laboral_apoderado']) ?>
                                        <?= $filaAn('Estudiante', $a['sit_laboral_estudiante']) ?>
                                    </div>
                                    <?= $textoAn('Observaciones', $a['observaciones']) ?>
                                </section>

                                <section>
                                    <?= $tituloAn('Antecedentes escolares y motivación') ?>
                                    <?= $textoAn('Motivo por el que retomó estudios', $a['motivo_retoma']) ?>
                                </section>

                                <section class="space-y-3">
                                    <?= $tituloAn('Salud y bienestar') ?>
                                    <?php if ((string) $a['salud_problemas'] === '1'): ?>
                                        <?= $textoAn('Problemas de salud', $a['salud_problemas_cuales']) ?>
                                    <?php endif ?>
                                    <?php if ((string) $a['salud_tratamientos'] === '1'): ?>
                                        <?= $textoAn('Tratamientos actuales', $a['salud_tratamientos_detalle']) ?>
                                    <?php endif ?>
                                    <?= $textoAn('Red de apoyo', $a['red_apoyo']) ?>
                                </section>

                                <section>
                                    <?= $tituloAn('Vivienda') ?>
                                    <?= $textoAn('Comentarios', $a['vivienda_comentarios']) ?>
                                </section>

                                <section>
                                    <?= $tituloAn('Observaciones del entrevistador') ?>
                                    <?= $textoAn('Observaciones', $a['obs_entrevistador']) ?>
                                </section>

                                <?php if (!empty($a['modificado_por_nombre']) && !empty($a['updated_at'])): ?>
                                    <p class="text-xs text-faint pt-3 border-t divider-soft">
                                        Última modificación: <?= htmlspecialchars($a['modificado_por_nombre']) ?>
                                        · <?= (new DateTime($a['updated_at']))->format('d/m/Y H:i') ?>
                                    </p>
                                <?php endif ?>
                            </div>
                        </details>
                    </div>
                <?php else: ?>
                    <div class="px-6 py-8 flex flex-col items-center gap-3 text-center">
                        <p class="text-muted text-sm italic">
                            No hay anamnesis registrada para <?= $anioActualLabel !== '' ? 'el año ' . htmlspecialchars($anioActualLabel) : 'el año actual' ?>.
                        </p>
                        <?php if ($puedeEditarAnamnesis && $anioActualId): ?>
                            <button type="button" data-abrir-anamnesis
                                class="btn-primary inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl transition shadow">
                                <?= saat_icon('plus', 'w-4 h-4') ?>
                                Registrar anamnesis
                            </button>
                        <?php endif ?>
                    </div>
                <?php endif ?>

                <!-- Historial de años anteriores -->
                <?php if (!empty($historialAnamnesis)): ?>
                    <div class="border-t divider-soft">
                        <div class="px-6 py-3" style="background: var(--surface-1);">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-muted">Años anteriores</h3>
                        </div>
                        <div class="divide-y divider-soft">
                            <?php foreach ($historialAnamnesis as $h): ?>
                                <details class="group">
                                    <summary class="cursor-pointer select-none px-6 py-3 flex flex-wrap items-center justify-between gap-2 list-none">
                                        <span class="flex items-center gap-3">
                                            <span class="text-sm font-semibold text-strong"><?= htmlspecialchars($h['anio_escolar']) ?></span>
                                            <span class="text-xs text-muted">
                                                <?= htmlspecialchars($h['realizado_por']) ?>
                                                <?= !empty($h['relacion']) ? '(' . htmlspecialchars($h['relacion']) . ')' : '' ?>
                                            </span>
                                        </span>
                                        <span class="flex items-center gap-3 text-xs text-faint">
                                            <?php if (!empty($h['entrevistador_nombre'])): ?>
                                                <?= htmlspecialchars($h['entrevistador_nombre']) ?>
                                            <?php endif ?>
                                            <?php if (!empty($h['fecha_entrevista'])): ?>
                                                · <?= (new DateTime($h['fecha_entrevista']))->format('d/m/Y') ?>
                                            <?php endif ?>
                                            <span class="transition-transform group-open:rotate-180">▾</span>
                                        </span>
                                    </summary>
                                    <div class="px-6 pb-5 space-y-3">
                                        <?= $textoAn('Motivo por el que retomó estudios', $h['motivo_retoma']) ?>
                                        <?= $textoAn('Observaciones', $h['observaciones']) ?>
                                        <?= $textoAn('Observaciones del entrevistador', $h['obs_entrevistador']) ?>
                                    </div>
                                </details>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    <?php endif ?>

    <!-- MATRÍCULA ACTIVA -->
    <div class="mx-auto max-w-5xl px-4 pt-6 sm:px-6 lg:px-8">
        <div class="panel rounded-2xl overflow-hidden shadow-lg" style="border-color: var(--link-action);">
            <div class="px-6 py-4 border-b divider-soft flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <?= saat_icon('doc', 'w-5 h-5 text-muted') ?>
                    <h2 class="text-base font-semibold text-strong">Matrícula Activa</h2>
                </div>
                <?php if (!empty($matriculaActiva)): ?>
                    <span class="report-badge report-indigo px-3 py-1 rounded-full text-xs font-bold">
                        <?= htmlspecialchars($matriculaActiva['anio_escolar']) ?>
                    </span>
                <?php endif ?>
            </div>

            <?php if (!empty($matriculaActiva)): ?>
                <div class="grid grid-cols-2 sm:grid-cols-4 divide-x" style="border-color: var(--border-soft);">
                    <?php
                    $celdasMatricula = [
                        ['book', 'Curso', $matriculaActiva['curso_nombre']],
                        ['calendar', 'Año Escolar', $matriculaActiva['anio_escolar']],
                        ['hash', 'N° Lista', $matriculaActiva['numero_lista'] ?? '—'],
                        [
                            'doc',
                            'Fecha Matrícula',
                            $matriculaActiva['fecha_matricula']
                            ? (new DateTime($matriculaActiva['fecha_matricula']))->format('d/m/Y')
                            : '—'
                        ],
                    ];
                    foreach ($celdasMatricula as [$ico, $lbl, $val]): ?>
                        <div class="px-6 py-5 flex flex-col items-center text-center gap-1">
                            <span class="text-muted"><?= saat_icon($ico, 'w-6 h-6') ?></span>
                            <p class="text-xs text-muted uppercase tracking-wider mt-1"><?= $lbl ?></p>
                            <p class="text-lg font-bold text-strong"><?= htmlspecialchars((string) $val) ?></p>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <div class="px-6 py-10 flex flex-col items-center gap-2 text-center">
                    <span class="text-muted"><?= saat_icon('inbox', 'w-10 h-10') ?></span>
                    <p class="text-muted text-sm font-medium">Sin matrícula activa</p>
                    <p class="text-faint text-xs">Este alumno no está matriculado en ningún curso actualmente.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">

        <!-- DATOS PERSONALES -->
        <div class="panel rounded-2xl overflow-hidden shadow">
            <div class="px-6 py-4 border-b divider-soft flex items-center gap-2">
                <?= saat_icon('user', 'w-5 h-5 text-muted') ?>
                <h2 class="text-base font-semibold text-strong">Datos Personales</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divider-soft">
                <div class="px-6 py-5 space-y-3">
                    <?php foreach ([
                        'Fecha de Nacimiento' => $alumno['fechanac'] ? (new DateTime($alumno['fechanac']))->format('d/m/Y') : 'No registrada',
                        'Edad (al 30 de junio)' => $edad !== null ? "$edad años" : 'No registrada',
                        'Email' => $alumno['email'] ?: 'No registrado',
                        'Teléfono' => $alumno['telefono'] ?: 'No registrado',
                        'Celular' => $alumno['celular'] ?: 'No registrado',
                    ] as $label => $valor): ?>
                        <div class="flex justify-between items-start gap-4">
                            <span class="text-xs text-muted uppercase tracking-wider min-w-[130px]"><?= $label ?></span>
                            <span class="text-sm text-soft text-right"><?= htmlspecialchars($valor) ?></span>
                        </div>
                    <?php endforeach ?>
                </div>
                <div class="px-6 py-5 space-y-3">
                    <?php foreach ([
                        'Nacionalidad' => $alumno['nacionalidades'] ?: 'No registrada',
                        'Región' => $alumno['region'] ?: 'No registrada',
                        'Ciudad' => $alumno['ciudad'] ?: 'No registrada',
                        'Dirección' => $alumno['direccion'] ?: 'No registrada',
                        'Etnia' => $alumno['cod_etnia'] ?: 'No registrada',
                        'Mayor de edad' => $alumno['mayoredad'] ?: 'No',
                    ] as $label => $valor): ?>
                        <div class="flex justify-between items-start gap-4">
                            <span class="text-xs text-muted uppercase tracking-wider min-w-[130px]"><?= $label ?></span>
                            <span class="text-sm text-soft text-right"><?= htmlspecialchars($valor) ?></span>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <!-- CONTACTOS -->
        <div class="panel rounded-2xl overflow-hidden shadow">
            <div class="px-6 py-4 border-b divider-soft flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <?= saat_icon('alert', 'w-5 h-5 text-muted') ?>
                    <h2 class="text-base font-semibold text-strong">Contactos y Antecedentes Familiares</h2>
                </div>
                <?php if (AuthController::puede('alum_emergencia_createProfile')): ?>
                    <a href="index.php?action=alum_emergencia_createProfile&alumno_id=<?= $alumno['id'] ?>"
                        class="btn-soft-success inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                        <?= saat_icon('plus', 'w-3.5 h-3.5') ?>
                        Agregar
                    </a>
                <?php endif ?>
            </div>

            <?php
            $fila = fn($label, $valor) =>
                '<div class="flex justify-between items-start gap-4">
                    <span class="text-xs text-muted uppercase tracking-wider min-w-[140px]">' . $label . '</span>
                    <span class="text-sm text-soft text-right">' . htmlspecialchars($valor ?: '—') . '</span>
                </div>';

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
                    echo '<div class="mt-3 pt-3 border-t divider-soft">';
                    echo '<p class="text-xs text-muted uppercase tracking-wider mb-1">Observación</p>';
                    echo '<p class="text-sm text-soft leading-relaxed">' . nl2br(htmlspecialchars($c['observacion'] ?? '—')) . '</p>';
                    echo '</div>';
                }
                if (in_array($rol, ['administrador', 'administrativo', 'Inspector general y Convivencia escolar'])): ?>
                    <div class="flex gap-2 mt-2">
                        <a href="index.php?action=alum_emergencia_edit&id=<?= $c['id'] ?>&back=alumno_profile&alumno_id=<?= $alumno['id'] ?>"
                            class="btn-soft-info px-3 py-1.5 text-xs font-semibold rounded-lg transition">Editar</a>
                        <a href="index.php?action=alum_emergencia_deleteProfile&id=<?= $c['id'] ?>&alumno_id=<?= $alumno['id'] ?>"
                            onclick="return confirm('¿Estás seguro de eliminar este contacto?');"
                            class="btn-soft-danger px-3 py-1.5 text-xs font-semibold rounded-lg transition">Eliminar</a>
                    </div>
                <?php endif;
                echo '</div>';
            };

            $seccionesContacto = [
                ['grupo' => 'padre_madre_tutor', 'ico' => 'family', 'color' => 'text-warn', 'titulo' => 'Antecedentes del Padre, Madre o Tutor Legal', 'obs' => false],
                ['grupo' => 'apoderado', 'ico' => 'badge', 'color' => 'text-accent', 'titulo' => 'Representante o Apoderado del Estudiante', 'obs' => false],
                ['grupo' => 'emergencia', 'ico' => 'alert', 'color' => 'text-danger', 'titulo' => 'Avisar en Caso de Emergencia / Apoderado Suplente', 'obs' => true],
            ];
            foreach ($seccionesContacto as $i => $s):
                $isLast = $i === count($seccionesContacto) - 1;
                ?>
                <div class="<?= !$isLast ? 'border-b divider-soft' : '' ?>">
                    <div class="px-6 py-3 flex items-center gap-2" style="background: var(--surface-1);">
                        <?= saat_icon($s['ico'], 'w-4 h-4 ' . $s['color']) ?>
                        <h3 class="text-sm font-semibold uppercase tracking-wider <?= $s['color'] ?>">
                            <?= $s['titulo'] ?>
                        </h3>
                    </div>
                    <?php if (!empty($contactosGrupos[$s['grupo']])): ?>
                        <div class="divide-y divider-soft">
                            <?php foreach ($contactosGrupos[$s['grupo']] as $c): ?>
                                <?php $tarjetaContacto($c, $s['obs']); ?>
                            <?php endforeach ?>
                        </div>
                    <?php else: ?>
                        <p class="px-6 py-5 text-muted text-sm italic">No hay registros para esta sección.</p>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>

        <!-- ANTECEDENTES FAMILIARES -->
        <div class="panel rounded-2xl overflow-hidden shadow">
            <div class="px-6 py-4 border-b divider-soft flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <?= saat_icon('family', 'w-5 h-5 text-muted') ?>
                    <h2 class="text-base font-semibold text-strong">Antecedentes Familiares</h2>
                </div>
                <?php if (AuthController::puede('antecedentefamiliar_editProfile')): ?>
                    <a href="index.php?action=antecedentefamiliar_editProfile&alumno_id=<?= $alumno['id'] ?>"
                        class="btn-soft-info inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                        <?= saat_icon('edit', 'w-3.5 h-3.5') ?>
                        Editar
                    </a>
                <?php endif ?>
            </div>
            <?php if (!empty($antecedentes)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divider-soft">
                    <?php foreach (['Padre' => ['padre', 'nivel_ciclo_p'], 'Madre' => ['madre', 'nivel_ciclo_m']] as $label => [$escF, $cicF]): ?>
                        <div class="px-6 py-5 space-y-3">
                            <p class="text-xs text-muted uppercase tracking-wider font-semibold mb-2"><?= $label ?></p>
                            <div class="flex justify-between">
                                <span class="text-xs text-muted">Escolaridad</span>
                                <span class="text-sm text-soft"><?= htmlspecialchars($antecedentes[$escF] ?: '—') ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-muted">Nivel / Ciclo</span>
                                <span class="text-sm text-soft"><?= htmlspecialchars($antecedentes[$cicF] ?: '—') ?></span>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <div class="px-6 py-8 text-center text-muted text-sm italic">
                    No hay antecedentes familiares registrados.
                </div>
            <?php endif ?>
        </div>

        <!-- ANTECEDENTE ESCOLAR -->
        <div class="panel rounded-2xl overflow-hidden shadow">
            <div class="px-6 py-4 border-b divider-soft flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <?= saat_icon('cap', 'w-5 h-5 text-muted') ?>
                    <h2 class="text-base font-semibold text-strong">Antecedente Escolar</h2>
                </div>
                <?php if (AuthController::puede('antecedente_escolar_editProfile')): ?>
                    <a href="index.php?action=antecedente_escolar_editProfile&alumno_id=<?= $alumno['id'] ?>"
                        class="btn-soft-info inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                        <?= saat_icon('edit', 'w-3.5 h-3.5') ?>
                        Editar
                    </a>
                <?php endif ?>
            </div>
            <?php if (!empty($alumno['antecedente_id'])): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divider-soft">
                    <div class="px-6 py-5 space-y-3">
                        <p class="text-xs text-accent uppercase tracking-wider font-semibold mb-2">Datos Generales</p>
                        <?php foreach ([
                            'Procedencia' => $alumno['procedencia_colegio'] ?: '—',
                            'Comuna' => $alumno['comuna'] ?: '—',
                            'Último Curso' => $alumno['ultimo_curso'] ?: '—',
                            'Último Año' => $alumno['ultimo_anio_cursado'] ?: '—',
                            'Cursos Repetidos' => $alumno['cursos_repetidos'] ?: '—',
                            'Eval. Psicológica' => $alumno['eva_psico'] ?: 'No registrada',
                            'Info. Salud' => $alumno['info_salud'] ?: 'No registrada',
                        ] as $l => $v): ?>
                            <div class="flex justify-between gap-4">
                                <span class="text-xs text-muted min-w-[130px]"><?= $l ?></span>
                                <span class="text-sm text-soft text-right"><?= htmlspecialchars($v) ?></span>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <div class="px-6 py-5 space-y-3">
                        <p class="text-xs text-accent uppercase tracking-wider font-semibold mb-2">Condiciones y Programas
                        </p>
                        <?php foreach ([
                            'Pertenece al 20%' => $alumno['pertenece_20'] ? 'Sí' : 'No',
                            'Informe 20%' => $alumno['informe_20'] ? 'Sí' : 'No',
                            'Embarazo' => $alumno['embarazo'] ? 'Sí (' . ($alumno['semanas'] ?? '-') . ' semanas)' : 'No',
                            'Prob. Aprendizaje' => $alumno['prob_apren'] ?: '—',
                            'PIE' => $alumno['pie'] ?: '—',
                            'Chile Solidario' => $alumno['chile_solidario'] ? ($alumno['chile_solidario_cual'] ?? 'Sí') : 'No',
                            'Grupo Fonasa' => $alumno['grupo_fonasa'] ?: 'No registra',
                            'Isapre' => $alumno['isapre'] ?: 'No registra',
                            'Seguro Salud' => $alumno['seguro_salud'] ?: 'No registra',
                        ] as $l => $v): ?>
                            <div class="flex justify-between gap-4">
                                <span class="text-xs text-muted min-w-[130px]"><?= $l ?></span>
                                <span class="text-sm text-soft text-right"><?= htmlspecialchars($v) ?></span>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="px-6 py-8 text-center text-muted text-sm italic">
                    No hay antecedente escolar registrado para este alumno.
                </div>
            <?php endif ?>
        </div>

        <!-- ÚLTIMA MODIFICACIÓN -->
        <?php if (!empty($ultimaModificacion)): ?>
            <div class="panel rounded-2xl overflow-hidden shadow">
                <div class="px-6 py-4 flex items-center gap-3">
                    <span class="text-muted"><?= saat_icon('clock', 'w-5 h-5') ?></span>
                    <div>
                        <p class="text-xs text-muted uppercase tracking-wider">Última modificación</p>
                        <p class="text-sm text-soft mt-0.5 flex flex-wrap items-center gap-1">
                            <span class="font-semibold text-strong">
                                <?= htmlspecialchars($ultimaModificacion['usuario_nombre'] . ' ' . $ultimaModificacion['usuario_apepat']) ?>
                            </span>
                            <span class="text-faint">·</span>
                            <?= htmlspecialchars(ucfirst($ultimaModificacion['accion'])) ?>
                            en <span
                                class="text-soft italic"><?= htmlspecialchars($ultimaModificacion['tabla_afectada']) ?></span>
                            <span class="text-faint">·</span>
                            <?= (new DateTime($ultimaModificacion['created_at']))->format('d/m/Y H:i') ?>
                        </p>
                        <?php if (!empty($ultimaModificacion['detalle'])): ?>
                            <p class="text-xs text-faint mt-0.5"><?= htmlspecialchars($ultimaModificacion['detalle']) ?></p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <!-- VOLVER -->
        <div class="flex justify-center pt-2">
            <a href="index.php?action=alumnos"
                class="btn-secondary inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold rounded-xl transition shadow">
                <?= saat_icon('arrow-left', 'w-4 h-4') ?>
                Volver al listado
            </a>
        </div>

    </div>
</main>

<!-- BOTÓN FLOTANTE PDF -->
<?php if (AuthController::puede('alumno_pdf')): ?>
    <a href="index.php?action=alumno_pdf&id=<?= $alumno['id'] ?>" class="btn-success fixed bottom-6 right-6 z-50 flex items-center gap-2 px-5 py-3
              font-bold rounded-full shadow-2xl transition-all duration-300 hover:scale-105">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        Descargar PDF
    </a>
<?php endif ?>

<!-- MODAL ANAMNESIS -->
<?php if ($puedeVerAnamnesis && $puedeEditarAnamnesis && $anioActualId): ?>
    <?php include __DIR__ . '/../anamnesis/_modal.php'; ?>
<?php endif ?>

<?php include __DIR__ . "/../layout/footer.php"; ?>