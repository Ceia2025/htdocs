<?php
/**
 * Modal de Anamnesis (partial) — se incluye desde views/alumnos/profile.php
 *
 * Variables esperadas:
 *   $alumno           array        alumno (alumnos2)
 *   $anamnesisActual  array|false  resultado de ResumenAnamnesis::getCompletaByAlumnoYAnio()
 *   $anioActualId     int          año que se edita
 *   $anioActualLabel  string       texto del año (ej. "2026")
 *   $matriculaActiva  array|null   para mostrar el curso
 *   $contactosGrupos  array        contactos agrupados (usa 'apoderado' o 'padre_madre_tutor')
 *   $flashAnamnesis   array|null   (opcional) resultado de AnamnesisResumenController::consumirFlash()
 *
 * Botón que abre el modal: cualquier elemento con el atributo data-abrir-anamnesis
 */
require_once __DIR__ . '/../../controllers/AnamnesisResumenController.php';

$e = fn($v) => htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');

$an  = $anamnesisActual ?: [];
$old = AnamnesisResumenController::consumirOld();
if ($old !== null && (int) ($old['alumno_id'] ?? 0) !== (int) $alumno['id']) {
    $old = null;
}
$reabrir = $old !== null;

// Contacto registrado para precargar / botón "Usar datos del apoderado"
$contactoApo = $contactosGrupos['apoderado'][0] ?? ($contactosGrupos['padre_madre_tutor'][0] ?? null);
$datosContacto = [];
if ($contactoApo) {
    $datosContacto = array_filter([
        'realizado_por'       => $contactoApo['nombre_contacto'] ?? '',
        'relacion'            => $contactoApo['relacion'] ?? '',
        'apoderado_run'       => $contactoApo['run_contacto'] ?? '',
        'apoderado_domicilio' => $contactoApo['direccion'] ?? '',
        'apoderado_telefono'  => !empty($contactoApo['celular'])
            ? '+569 ' . $contactoApo['celular']
            : ($contactoApo['telefono'] ?? ''),
        'apoderado_email'     => $contactoApo['email'] ?? '',
    ], fn($v) => $v !== '' && $v !== null);
}
// Si la anamnesis es nueva, se precarga con el contacto
$prefill = !$an ? $datosContacto : [];

// Prioridad: lo enviado que falló > lo guardado > precarga desde contacto
$val = function (string $campo) use ($old, $an, $prefill) {
    if ($old !== null) {
        return $old[$campo] ?? '';
    }
    return $an[$campo] ?? ($prefill[$campo] ?? '');
};
$sel = fn(string $campo, $valor) => (string) $val($campo) === (string) $valor ? 'selected' : '';
$chk = fn(string $campo, $valor) => (string) $val($campo) === (string) $valor ? 'checked' : '';

// <option> de una lista, agregando el valor guardado si no está en ella
$opciones = function (string $campo, array $lista) use ($val, $e) {
    $actual = (string) $val($campo);
    $html = '<option value="">— Seleccionar —</option>';
    if ($actual !== '' && !in_array($actual, $lista, true)) {
        $lista[] = $actual;
    }
    foreach ($lista as $op) {
        $s = $actual === $op ? ' selected' : '';
        $html .= '<option value="' . $e($op) . '"' . $s . '>' . $e($op) . '</option>';
    }
    return $html;
};

// Integrantes
$integrantes = $old !== null ? ($old['integrantes'] ?? []) : ($an['integrantes'] ?? []);
$integrantes = array_values(array_filter(is_array($integrantes) ? $integrantes : [], 'is_array'));
if (!$integrantes) {
    $integrantes = [[]];
}

$filaIntegrante = function ($idx, array $i) use ($e) {
    $in = 'input-field w-full rounded-lg px-2.5 py-1.5 text-sm';
    return '<tr>
        <td class="py-1.5 pr-2"><input type="text" name="integrantes[' . $idx . '][nombre]" value="' . $e($i['nombre'] ?? '') . '" maxlength="150" class="' . $in . '" placeholder="Nombre"></td>
        <td class="py-1.5 pr-2 w-20"><input type="number" name="integrantes[' . $idx . '][edad]" value="' . $e($i['edad'] ?? '') . '" min="0" max="120" class="' . $in . '"></td>
        <td class="py-1.5 pr-2"><input type="text" name="integrantes[' . $idx . '][parentesco]" value="' . $e($i['parentesco'] ?? '') . '" maxlength="80" list="listaParentescos" class="' . $in . '"></td>
        <td class="py-1.5 pr-2"><input type="text" name="integrantes[' . $idx . '][ocupacion]" value="' . $e($i['ocupacion'] ?? '') . '" maxlength="150" class="' . $in . '"></td>
        <td class="py-1.5 pr-2"><input type="text" name="integrantes[' . $idx . '][centro_salud]" value="' . $e($i['centro_salud'] ?? '') . '" maxlength="150" class="' . $in . '" placeholder="CESFAM / Posta"></td>
        <td class="py-1.5 w-10 text-right">
            <button type="button" data-quitar-integrante title="Quitar fila"
                class="btn-soft-danger px-2 py-1.5 text-xs font-bold rounded-lg transition">✕</button>
        </td>
    </tr>';
};

// Datos del alumno (Sección I, solo lectura)
$edadAlumno = null;
$esMayorModal = false;
if (!empty($alumno['fechanac'])) {
    $edadAlumno = (new DateTime())->diff(new DateTime($alumno['fechanac']))->y;
    $esMayorModal = $edadAlumno >= 18;
}
$datosAlumno = [
    'Nombre completo'  => trim($alumno['nombre'] . ' ' . $alumno['apepat'] . ' ' . $alumno['apemat']),
    'RUN'              => $alumno['run'] . (!empty($alumno['codver']) ? '-' . $alumno['codver'] : ''),
    'Edad'             => $edadAlumno !== null ? $edadAlumno . ' años' : '—',
    'Fecha de nac.'    => !empty($alumno['fechanac']) ? (new DateTime($alumno['fechanac']))->format('d/m/Y') : '—',
    'Curso'            => $matriculaActiva['curso_nombre'] ?? 'Sin matrícula activa',
    'Domicilio'        => $alumno['direccion'] ?: '—',
    'Teléfono'         => $alumno['celular'] ?: ($alumno['telefono'] ?: '—'),
    'Correo'           => $alumno['email'] ?: '—',
];

// Entrevistador
$usuarioSesion = trim(($_SESSION['user']['nombre'] ?? '') . ' ' . ($_SESSION['user']['ape_paterno'] ?? ''));
$rolSesion     = $_SESSION['user']['rol'] ?? '';

$lbl = 'block text-xs font-semibold uppercase tracking-wider text-muted mb-1.5';
$inp = 'input-field w-full rounded-lg px-3 py-2 text-sm';
$h3  = 'text-sm font-bold uppercase tracking-wider text-accent mb-4';
?>

<div id="modalAnamnesis" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true"
     aria-labelledby="modalAnamnesisTitulo" data-reabrir="<?= $reabrir ? '1' : '0' ?>">

    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-cerrar-anamnesis></div>

    <div class="relative mx-auto my-6 w-[calc(100%-2rem)] max-w-4xl">
        <form id="formAnamnesis" method="POST" action="index.php?action=anamnesis_guardar"
              class="panel rounded-2xl shadow-2xl flex flex-col max-h-[calc(100vh-3rem)] overflow-hidden">

            <input type="hidden" name="alumno_id" value="<?= (int) $alumno['id'] ?>">
            <input type="hidden" name="anio_id" value="<?= (int) $anioActualId ?>">
            <input type="hidden" name="origen" value="perfil">
            <input type="hidden" name="seccion_integrantes" value="1">
            <input type="hidden" name="csrf" value="<?= $e(AnamnesisResumenController::csrfToken()) ?>">

            <!-- HEADER -->
            <div class="px-6 py-4 border-b divider-soft flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">
                        Anamnesis <?= $e($anioActualLabel) ?>
                    </p>
                    <h2 id="modalAnamnesisTitulo" class="text-lg font-bold text-strong font-display">
                        <?= $an ? 'Editar anamnesis' : 'Nueva anamnesis' ?>
                    </h2>
                    <p class="text-sm text-muted capitalize">
                        <?= $e($alumno['apepat'] . ' ' . $alumno['apemat'] . ', ' . $alumno['nombre']) ?>
                    </p>
                </div>
                <button type="button" data-cerrar-anamnesis aria-label="Cerrar"
                        class="btn-secondary w-9 h-9 flex items-center justify-center rounded-lg text-lg leading-none">
                    ✕
                </button>
            </div>

            <!-- BODY -->
            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-8">

                <?php if ($reabrir && !empty($flashAnamnesis) && $flashAnamnesis['tipo'] === 'error'): ?>
                    <div class="banner-danger text-sm px-4 py-3 rounded-xl">
                        <?= $e($flashAnamnesis['mensaje']) ?>
                    </div>
                <?php endif ?>

                <!-- I. DATOS GENERALES DEL ESTUDIANTE -->
                <section>
                    <h3 class="<?= $h3 ?>">I. Datos generales del estudiante</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 mb-4">
                        <?php foreach ($datosAlumno as $l => $v): ?>
                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-xs text-muted uppercase tracking-wider"><?= $l ?></span>
                                <span class="text-soft text-right"><?= $e($v) ?></span>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <p class="text-xs text-faint mb-3">Estos datos provienen de la ficha del alumno. Para cambiarlos, edita la ficha.</p>

                    <span class="<?= $lbl ?>">Jornada</span>
                    <div class="flex gap-6 text-sm text-soft">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jornada" value="diurna" class="w-4 h-4" <?= $chk('jornada', 'diurna') ?>> Diurna
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jornada" value="vespertina" class="w-4 h-4" <?= $chk('jornada', 'vespertina') ?>> Vespertina
                        </label>
                    </div>
                </section>

                <!-- II. DATOS DEL APODERADO / RESPONSABLE -->
                <section class="pt-6 border-t divider-soft">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <h3 class="<?= $h3 ?> !mb-0">II. Datos del apoderado / responsable</h3>
                        <?php if ($datosContacto): ?>
                            <button type="button" id="btnCargarApoderado"
                                    data-apoderado='<?= $e(json_encode($datosContacto, JSON_UNESCAPED_UNICODE)) ?>'
                                    class="btn-soft-info px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                                Usar datos del apoderado registrado
                            </button>
                        <?php endif ?>
                    </div>
                    <?php if ($esMayorModal): ?>
                        <p class="text-xs text-faint mb-3">El alumno es mayor de edad: si responde él mismo, ingresa su nombre y selecciona "Estudiante".</p>
                    <?php endif ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="<?= $lbl ?>">Nombre completo <span class="text-danger">*</span></label>
                            <input type="text" name="realizado_por" required maxlength="150"
                                   value="<?= $e($val('realizado_por')) ?>" class="<?= $inp ?>">
                        </div>
                        <div>
                            <label class="<?= $lbl ?>">RUT</label>
                            <input type="text" name="apoderado_run" maxlength="20" placeholder="12.345.678-9"
                                   value="<?= $e($val('apoderado_run')) ?>" class="<?= $inp ?>">
                        </div>
                        <div>
                            <label class="<?= $lbl ?>">Parentesco con el estudiante</label>
                            <select name="relacion" class="<?= $inp ?>">
                                <?= $opciones('relacion', ['Padre', 'Madre', 'Tutor/a', 'Apoderado/a', 'Abuelo/a', 'Hermano/a', 'Tío/a', 'Pareja', 'Estudiante', 'Otro']) ?>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="<?= $lbl ?>">Domicilio</label>
                            <input type="text" name="apoderado_domicilio" maxlength="255"
                                   value="<?= $e($val('apoderado_domicilio')) ?>" class="<?= $inp ?>">
                        </div>
                        <div>
                            <label class="<?= $lbl ?>">Edad</label>
                            <input type="number" name="apoderado_edad" min="0" max="120"
                                   value="<?= $e($val('apoderado_edad')) ?>" class="<?= $inp ?>">
                        </div>
                        <div>
                            <label class="<?= $lbl ?>">Estado civil</label>
                            <select name="apoderado_estado_civil" class="<?= $inp ?>">
                                <?= $opciones('apoderado_estado_civil', ['Soltero/a', 'Casado/a', 'Conviviente civil', 'Conviviente', 'Separado/a', 'Divorciado/a', 'Viudo/a']) ?>
                            </select>
                        </div>
                        <div>
                            <label class="<?= $lbl ?>">Nivel educacional</label>
                            <select name="apoderado_nivel_educacional" class="<?= $inp ?>">
                                <?= $opciones('apoderado_nivel_educacional', ['Sin escolaridad', 'Básica incompleta', 'Básica completa', 'Media incompleta', 'Media completa', 'Técnico nivel superior', 'Universitaria incompleta', 'Universitaria completa', 'Postgrado']) ?>
                            </select>
                        </div>
                        <div>
                            <label class="<?= $lbl ?>">Ocupación / Profesión</label>
                            <input type="text" name="apoderado_ocupacion" maxlength="150"
                                   value="<?= $e($val('apoderado_ocupacion')) ?>" class="<?= $inp ?>">
                        </div>
                        <div>
                            <label class="<?= $lbl ?>">Teléfono de contacto</label>
                            <input type="text" name="apoderado_telefono" maxlength="30"
                                   value="<?= $e($val('apoderado_telefono')) ?>" class="<?= $inp ?>">
                        </div>
                        <div>
                            <label class="<?= $lbl ?>">Correo electrónico</label>
                            <input type="email" name="apoderado_email" maxlength="150"
                                   value="<?= $e($val('apoderado_email')) ?>" class="<?= $inp ?>">
                        </div>
                    </div>
                </section>

                <!-- III. ANTECEDENTES FAMILIARES -->
                <section class="pt-6 border-t divider-soft">
                    <h3 class="<?= $h3 ?>">III. Antecedentes familiares y contexto sociofamiliar</h3>

                    <div class="flex items-center gap-3 mb-4">
                        <label class="<?= $lbl ?> !mb-0">Total integrantes</label>
                        <input type="number" name="total_integrantes" min="0" max="50"
                               value="<?= $e($val('total_integrantes')) ?>" class="<?= $inp ?> !w-24">
                        <span class="text-xs text-faint">Se calcula solo si lo dejas vacío.</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[720px] text-left">
                            <thead>
                                <tr class="text-xs text-muted uppercase tracking-wider">
                                    <th class="pb-2 pr-2 font-semibold">Nombre</th>
                                    <th class="pb-2 pr-2 font-semibold">Edad</th>
                                    <th class="pb-2 pr-2 font-semibold">Parentesco</th>
                                    <th class="pb-2 pr-2 font-semibold">Ocupación</th>
                                    <th class="pb-2 pr-2 font-semibold">Centro de salud (APS)</th>
                                    <th class="pb-2"></th>
                                </tr>
                            </thead>
                            <tbody id="anamnesisIntegrantes">
                                <?php foreach ($integrantes as $idx => $i): ?>
                                    <?= $filaIntegrante($idx, $i) ?>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" id="btnAgregarIntegrante"
                            class="btn-soft-success mt-3 px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                        + Agregar integrante
                    </button>

                    <datalist id="listaParentescos">
                        <option value="Padre"><option value="Madre"><option value="Hermano/a">
                        <option value="Hijo/a"><option value="Abuelo/a"><option value="Tío/a">
                        <option value="Pareja"><option value="Primo/a"><option value="Otro">
                    </datalist>

                    <template id="tplIntegrante"><?= $filaIntegrante('__IDX__', []) ?></template>
                </section>

                <!-- IV. SITUACIÓN LABORAL -->
                <section class="pt-6 border-t divider-soft">
                    <h3 class="<?= $h3 ?> !mb-1">IV. Situación laboral</h3>
                    <p class="text-xs text-faint mb-4">Indicar si tiene trabajo formal o informal, lugar de trabajo y jornada. Cesante, dueña de casa o estudiante.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="<?= $lbl ?>">Situación laboral actual del apoderado</label>
                            <input type="text" name="sit_laboral_apoderado" maxlength="255"
                                   value="<?= $e($val('sit_laboral_apoderado')) ?>" class="<?= $inp ?>">
                        </div>
                        <div>
                            <label class="<?= $lbl ?>">Situación laboral del estudiante</label>
                            <input type="text" name="sit_laboral_estudiante" maxlength="255"
                                   value="<?= $e($val('sit_laboral_estudiante')) ?>" class="<?= $inp ?>">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="<?= $lbl ?>">Observaciones</label>
                            <textarea name="observaciones" rows="3" class="<?= $inp ?> resize-y"><?= $e($val('observaciones')) ?></textarea>
                        </div>
                    </div>
                </section>

                <!-- V. ANTECEDENTES ESCOLARES Y MOTIVACIÓN -->
                <section class="pt-6 border-t divider-soft">
                    <h3 class="<?= $h3 ?> !mb-1">V. Antecedentes escolares y motivación</h3>
                    <p class="text-xs text-faint mb-4">Motivo por el cual el estudiante retomó estudios. Considerar experiencias previas en el sistema escolar y la presencia de apoyo familiar.</p>
                    <textarea name="motivo_retoma" rows="4" class="<?= $inp ?> resize-y"><?= $e($val('motivo_retoma')) ?></textarea>
                </section>

                <!-- VI. SALUD Y BIENESTAR -->
                <section class="pt-6 border-t divider-soft">
                    <h3 class="<?= $h3 ?>">VI. Antecedentes de salud y bienestar</h3>

                    <div class="space-y-5">
                        <div>
                            <span class="<?= $lbl ?>">Problemas de salud relevantes</span>
                            <div class="flex gap-6 text-sm text-soft">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="salud_problemas" value="1" class="w-4 h-4" <?= $chk('salud_problemas', '1') ?>> Sí
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="salud_problemas" value="0" class="w-4 h-4" <?= $chk('salud_problemas', '0') ?>> No
                                </label>
                            </div>
                            <div id="detalleSaludProblemas" class="mt-3 hidden">
                                <label class="<?= $lbl ?>">¿Cuáles?</label>
                                <textarea name="salud_problemas_cuales" rows="2" class="<?= $inp ?> resize-y"><?= $e($val('salud_problemas_cuales')) ?></textarea>
                            </div>
                        </div>

                        <div>
                            <span class="<?= $lbl ?>">Tratamientos médicos o psicológicos actuales</span>
                            <div class="flex gap-6 text-sm text-soft">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="salud_tratamientos" value="1" class="w-4 h-4" <?= $chk('salud_tratamientos', '1') ?>> Sí
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="salud_tratamientos" value="0" class="w-4 h-4" <?= $chk('salud_tratamientos', '0') ?>> No
                                </label>
                            </div>
                            <div id="detalleSaludTratamientos" class="mt-3 hidden">
                                <label class="<?= $lbl ?>">Detalles</label>
                                <textarea name="salud_tratamientos_detalle" rows="2" class="<?= $inp ?> resize-y"><?= $e($val('salud_tratamientos_detalle')) ?></textarea>
                            </div>
                        </div>

                        <div>
                            <span class="<?= $lbl ?>">Consumo problemático de sustancias (propio o en el entorno familiar)</span>
                            <div class="flex flex-wrap gap-6 text-sm text-soft">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="consumo_sustancias" value="si" class="w-4 h-4" <?= $chk('consumo_sustancias', 'si') ?>> Sí
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="consumo_sustancias" value="no" class="w-4 h-4" <?= $chk('consumo_sustancias', 'no') ?>> No
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="consumo_sustancias" value="no_sabe" class="w-4 h-4" <?= $chk('consumo_sustancias', 'no_sabe') ?>> No sabe / No responde
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="<?= $lbl ?>">Red de apoyo o instituciones que acompañan al estudiante</label>
                            <textarea name="red_apoyo" rows="2" class="<?= $inp ?> resize-y"><?= $e($val('red_apoyo')) ?></textarea>
                        </div>
                    </div>
                </section>

                <!-- VII. SITUACIÓN DE VIVIENDA -->
                <section class="pt-6 border-t divider-soft">
                    <h3 class="<?= $h3 ?>">VII. Situación de vivienda</h3>
                    <span class="<?= $lbl ?>">Tipo de vivienda</span>
                    <div class="flex flex-wrap gap-6 text-sm text-soft mb-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="vivienda_tipo" value="propia" class="w-4 h-4" <?= $chk('vivienda_tipo', 'propia') ?>> Propia
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="vivienda_tipo" value="arrendada" class="w-4 h-4" <?= $chk('vivienda_tipo', 'arrendada') ?>> Arrendada
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="vivienda_tipo" value="cedida_allegado" class="w-4 h-4" <?= $chk('vivienda_tipo', 'cedida_allegado') ?>> Cedida / allegado
                        </label>
                    </div>
                    <label class="<?= $lbl ?>">Comentarios</label>
                    <textarea name="vivienda_comentarios" rows="2" class="<?= $inp ?> resize-y"><?= $e($val('vivienda_comentarios')) ?></textarea>
                </section>

                <!-- VIII. OBSERVACIONES DEL ENTREVISTADOR -->
                <section class="pt-6 border-t divider-soft">
                    <h3 class="<?= $h3 ?>">VIII. Observaciones generales del entrevistador</h3>
                    <textarea name="obs_entrevistador" rows="4" class="<?= $inp ?> resize-y mb-4"><?= $e($val('obs_entrevistador')) ?></textarea>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                        <div>
                            <span class="<?= $lbl ?>">Profesional que entrevista</span>
                            <p class="text-sm text-soft">
                                <?php if (!empty($an['entrevistador_nombre'])): ?>
                                    <?= $e($an['entrevistador_nombre']) ?>
                                    <?php if (!empty($an['entrevistador_rol'])): ?>
                                        <span class="text-faint">(<?= $e($an['entrevistador_rol']) ?>)</span>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?= $e($usuarioSesion ?: 'Usuario actual') ?>
                                    <?php if ($rolSesion): ?><span class="text-faint">(<?= $e($rolSesion) ?>)</span><?php endif ?>
                                    <span class="block text-xs text-faint">Se registrará con tu usuario al guardar.</span>
                                <?php endif ?>
                            </p>
                        </div>
                        <div>
                            <label class="<?= $lbl ?>">Fecha de la entrevista</label>
                            <input type="date" name="fecha_entrevista" max="<?= date('Y-m-d') ?>"
                                   value="<?= $e($val('fecha_entrevista') ?: date('Y-m-d')) ?>" class="<?= $inp ?>">
                        </div>
                    </div>

                    <?php if (!empty($an['modificado_por_nombre']) && !empty($an['updated_at'])): ?>
                        <p class="text-xs text-faint mt-4">
                            Última modificación: <?= $e($an['modificado_por_nombre']) ?>
                            · <?= (new DateTime($an['updated_at']))->format('d/m/Y H:i') ?>
                        </p>
                    <?php endif ?>
                </section>
            </div>

            <!-- FOOTER -->
            <div class="px-6 py-4 border-t divider-soft flex items-center justify-between gap-3">
                <span class="text-xs text-faint"><span class="text-danger">*</span> Campo obligatorio</span>
                <div class="flex gap-2">
                    <button type="button" data-cerrar-anamnesis
                            class="btn-secondary px-5 py-2.5 text-sm font-semibold rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="btn-primary px-6 py-2.5 text-sm font-bold rounded-xl transition shadow">
                        Guardar anamnesis
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const modal = document.getElementById('modalAnamnesis');
    if (!modal) return;

    const form  = document.getElementById('formAnamnesis');
    const tbody = document.getElementById('anamnesisIntegrantes');
    const tpl   = document.getElementById('tplIntegrante');
    const total = form.querySelector('[name="total_integrantes"]');
    let idx = tbody.querySelectorAll('tr').length;
    let ultimoFoco = null;

    /* ---------- Abrir / cerrar ---------- */
    function abrir() {
        ultimoFoco = document.activeElement;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => form.querySelector('input:not([type=hidden]):not([type=radio]), select, textarea')?.focus(), 50);
    }

    function cerrar() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        ultimoFoco?.focus();
    }

    document.querySelectorAll('[data-abrir-anamnesis]').forEach(b => b.addEventListener('click', abrir));
    modal.querySelectorAll('[data-cerrar-anamnesis]').forEach(b => b.addEventListener('click', cerrar));
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) cerrar();
    });

    /* ---------- Integrantes ---------- */
    const contarIntegrantes = () =>
        [...tbody.querySelectorAll('input[name$="[nombre]"]')].filter(i => i.value.trim() !== '').length;

    let totalManual = total.value !== '' && Number(total.value) !== contarIntegrantes();
    total.addEventListener('input', () => { totalManual = total.value !== ''; });

    function sincronizarTotal() {
        if (!totalManual) total.value = contarIntegrantes() || '';
    }

    document.getElementById('btnAgregarIntegrante').addEventListener('click', () => {
        tbody.insertAdjacentHTML('beforeend', tpl.innerHTML.replaceAll('__IDX__', idx++));
        tbody.lastElementChild.querySelector('input')?.focus();
    });

    tbody.addEventListener('click', e => {
        const btn = e.target.closest('[data-quitar-integrante]');
        if (!btn) return;
        btn.closest('tr').remove();
        sincronizarTotal();
    });

    tbody.addEventListener('input', e => {
        if (e.target.name && e.target.name.endsWith('[nombre]')) sincronizarTotal();
    });

    /* ---------- Detalles de salud (Sí → muestra y exige detalle) ---------- */
    function toggleDetalle(nombreRadio, idDetalle) {
        const detalle = document.getElementById(idDetalle);
        const textarea = detalle.querySelector('textarea');
        const actualizar = () => {
            const si = form.querySelector(`[name="${nombreRadio}"]:checked`)?.value === '1';
            detalle.classList.toggle('hidden', !si);
            textarea.required = si;
        };
        form.querySelectorAll(`[name="${nombreRadio}"]`).forEach(r => r.addEventListener('change', actualizar));
        actualizar();
    }
    toggleDetalle('salud_problemas', 'detalleSaludProblemas');
    toggleDetalle('salud_tratamientos', 'detalleSaludTratamientos');

    /* ---------- Cargar datos del apoderado registrado ---------- */
    const btnApo = document.getElementById('btnCargarApoderado');
    if (btnApo) {
        btnApo.addEventListener('click', () => {
            const datos = JSON.parse(btnApo.dataset.apoderado || '{}');
            Object.entries(datos).forEach(([campo, valor]) => {
                const el = form.querySelector(`[name="${campo}"]`);
                if (!el || !valor) return;
                if (el.tagName === 'SELECT' && ![...el.options].some(o => o.value === valor)) {
                    el.add(new Option(valor, valor));
                }
                el.value = valor;
            });
        });
    }

    /* ---------- Evitar doble envío ---------- */
    form.addEventListener('submit', () => {
        const btn = form.querySelector('[type=submit]');
        btn.disabled = true;
        btn.textContent = 'Guardando…';
    });

    /* ---------- Reabrir si falló la validación ---------- */
    if (modal.dataset.reabrir === '1') abrir();
})();
</script>