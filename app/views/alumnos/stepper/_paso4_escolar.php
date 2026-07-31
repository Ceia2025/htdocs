<!-- ============================
     PASO 4: ANTECEDENTE ESCOLAR
============================= -->
<div class="step transition-all duration-300 ease-out transform hidden" data-step="4">

    <h2 class="text-2xl font-bold text-strong font-display mb-6">Antecedente Escolar</h2>

    <!-- SECCIÓN 1: PROCEDENCIA ESCOLAR -->
    <div class="mb-5 rounded-2xl p-5" style="background: var(--surface-1); border: 1px solid var(--border-soft);">
        <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-4 flex items-center gap-2">
            <span class="avatar-badge w-5 h-5 rounded-full text-[10px] flex items-center justify-center">1</span>
            Procedencia escolar
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-soft mb-2">
                    Procedencia del Colegio <span class="text-danger">*</span>
                </label>
                <input type="text" name="antecedente_escolar[procedencia_colegio]" required
                    class="input-field w-full rounded-lg px-3 py-2.5">
            </div>
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Comuna</label>
                <input type="text" name="antecedente_escolar[comuna]"
                    class="input-field w-full rounded-lg px-3 py-2.5">
            </div>
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Último Curso</label>
                <select name="antecedente_escolar[ultimo_curso]"
                    class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                    <option value="">Seleccionar...</option>
                    <?php foreach ([
                        '1ro basico','2do basico','3ro basico','4to basico',
                        '5to basico','6to basico','7mo basico','8vo basico',
                        '1ro medio','2do medio','3ro Medio','4to Medio',
                    ] as $c): ?>
                        <option value="<?= $c ?>"><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Último Año de Estudios</label>
                <input type="text" name="antecedente_escolar[ultimo_anio_cursado]"
                    maxlength="4" inputmode="numeric" placeholder="Ej: 2019"
                    class="input-field w-full rounded-lg px-3 py-2.5">
            </div>
        </div>
    </div>

    <!-- SECCIÓN 2: SITUACIÓN ACADÉMICA -->
    <div class="mb-5 rounded-2xl p-5" style="background: var(--surface-1); border: 1px solid var(--border-soft);">
        <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-4 flex items-center gap-2">
            <span class="avatar-badge w-5 h-5 rounded-full text-[10px] flex items-center justify-center">2</span>
            Situación académica
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Cursos Repetidos</label>
                <input type="number" name="antecedente_escolar[cursos_repetidos]" min="0" value="0"
                    class="input-field w-full rounded-lg px-3 py-2.5">
            </div>
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Problemas de Aprendizaje</label>
                <select name="antecedente_escolar[prob_apren]"
                    class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                    <?php foreach (['Sin','Con','Desconocido'] as $p): ?>
                        <option value="<?= $p ?>"><?= $p ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-soft mb-2">PIE</label>
                <select name="antecedente_escolar[pie]"
                    class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                    <?php foreach (['Si','No','No se sabe'] as $p): ?>
                        <option value="<?= $p ?>"><?= $p ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 3: BENEFICIOS Y PROGRAMAS -->
    <div class="mb-5 rounded-2xl p-5" style="background: var(--surface-1); border: 1px solid var(--border-soft);">
        <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-4 flex items-center gap-2">
            <span class="avatar-badge w-5 h-5 rounded-full text-[10px] flex items-center justify-center">3</span>
            Beneficios y programas
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-soft mb-2">
                    Pertenece al 20% <span class="text-danger">*</span>
                </label>
                <select name="antecedente_escolar[pertenece_20]" required
                    class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                    <option value="">Seleccionar...</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-soft mb-2">
                    Tiene Informe 20% <span class="text-danger">*</span>
                </label>
                <select name="antecedente_escolar[informe_20]" required
                    class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                    <option value="">Seleccionar...</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-soft mb-2">
                    Chile Solidario <span class="text-danger">*</span>
                </label>
                <select name="antecedente_escolar[chile_solidario]" id="chile_solidario_select" required
                    class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                    <option value="">Seleccionar...</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div id="chile_solidario_cual_wrap" class="hidden">
                <label class="block text-sm font-medium text-soft mb-2">Tipo Chile Solidario</label>
                <select name="antecedente_escolar[chile_solidario_cual]"
                    class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                    <?php foreach (['Ninguno','Prioritario','Preferente','Incremento','Pro-Retención'] as $v): ?>
                        <option value="<?= $v ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 4: PREVISIÓN Y SALUD -->
    <div class="mb-2 rounded-2xl p-5" style="background: var(--accent-title-soft); border: 1px solid var(--azul-vivo);">
        <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-4 flex items-center gap-2">
            <span class="avatar-badge w-5 h-5 rounded-full text-[10px] flex items-center justify-center">4</span>
            Previsión y salud
        </p>

        <div class="mb-5">
            <label class="block text-sm font-medium text-soft mb-2">Sistema de Previsión</label>
            <select name="antecedente_escolar[fonasa]" id="prevision_select"
                class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                <option value="Ninguno">Ninguno / No sabe</option>
                <option value="Fonasa">Fonasa</option>
                <option value="Isapre">Isapre</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div id="grupo_fonasa_wrap" class="hidden">
                <label class="block text-sm font-medium text-soft mb-2">
                    Grupo Fonasa <span class="text-danger">*</span>
                </label>
                <select name="antecedente_escolar[grupo_fonasa]" id="grupo_fonasa_select"
                    class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                    <?php foreach (['Ninguno','A','B','C','D','No Sabe'] as $g): ?>
                        <option value="<?= $g ?>"><?= $g ?></option>
                    <?php endforeach; ?>
                </select>
                <p id="grupo_fonasa_error" class="text-danger text-sm mt-1 hidden">
                    Selecciona el grupo Fonasa (A, B, C o D).
                </p>
            </div>

            <div id="isapre_wrap" class="hidden">
                <label class="block text-sm font-medium text-soft mb-2">Isapre</label>
                <select name="antecedente_escolar[isapre]"
                    class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                    <?php foreach (['Ninguno','BANCA MEDICA','CRUZ BLANCA','COLMENA','MAS VIDA','CON SALUD','VIDA TRES','DIPRECA'] as $i): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-soft mb-2">Seguro de Salud</label>
                <input type="text" name="antecedente_escolar[seguro_salud]" maxlength="30"
                    class="input-field w-full rounded-lg px-3 py-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-soft mb-2">Información de Salud</label>
                <textarea name="antecedente_escolar[info_salud]" rows="3" maxlength="200"
                    class="input-field w-full rounded-lg px-3 py-2.5 resize-none"></textarea>
            </div>
        </div>
    </div>

    <div class="flex justify-between pt-8">
        <button type="button"
            class="prev-step btn-secondary px-6 py-2.5 font-semibold rounded-xl shadow transition">
            ← Anterior
        </button>
        <button type="button"
            class="next-step btn-primary inline-flex items-center gap-2 px-6 py-3 font-semibold rounded-xl shadow transition">
            Siguiente →
        </button>
    </div>
</div>

<script>
    (function () {
        function initEscolarPaso4() {
            const prevision    = document.getElementById('prevision_select');
            const grupoWrap    = document.getElementById('grupo_fonasa_wrap');
            const grupoSelect  = document.getElementById('grupo_fonasa_select');
            const isapreWrap   = document.getElementById('isapre_wrap');
            const grupoError   = document.getElementById('grupo_fonasa_error');
            const chileSol     = document.getElementById('chile_solidario_select');
            const chileCualWrap= document.getElementById('chile_solidario_cual_wrap');

            if (!prevision) return;

            function aplicarPrevision() {
                const val = prevision.value;
                grupoWrap.classList.add('hidden');
                isapreWrap.classList.add('hidden');
                if (grupoError) grupoError.classList.add('hidden');

                if (val === 'Fonasa') {
                    grupoWrap.classList.remove('hidden');
                    if (grupoSelect) grupoSelect.setAttribute('required', 'required');
                } else if (val === 'Isapre') {
                    isapreWrap.classList.remove('hidden');
                    if (grupoSelect) { grupoSelect.removeAttribute('required'); grupoSelect.value = 'Ninguno'; }
                } else {
                    if (grupoSelect) { grupoSelect.removeAttribute('required'); grupoSelect.value = 'Ninguno'; }
                }
            }

            function aplicarChileSolidario() {
                if (!chileSol || !chileCualWrap) return;
                chileCualWrap.classList.toggle('hidden', chileSol.value !== '1');
            }

            prevision.addEventListener('change', aplicarPrevision);
            if (chileSol) chileSol.addEventListener('change', aplicarChileSolidario);

            aplicarPrevision();
            aplicarChileSolidario();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initEscolarPaso4);
        } else {
            initEscolarPaso4();
        }
    })();
</script>