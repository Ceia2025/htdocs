<!-- ============================
     PASO 4: ANTECEDENTE ESCOLAR
============================= -->
<div class="step transition-all duration-300 ease-out transform hidden" data-step="4">
    <h2 class="text-3xl font-extrabold mb-6 pb-3 bg-gradient-to-r from-indigo-400 to-blue-400 text-transparent bg-clip-text drop-shadow">
        Antecedente Escolar
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="block text-sm font-medium text-gray-200">Procedencia del Colegio</label>
            <input type="text" name="antecedente_escolar[procedencia_colegio]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Comuna</label>
            <input type="text" name="antecedente_escolar[comuna]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Último Curso</label>
            <select name="antecedente_escolar[ultimo_curso]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
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
            <label class="block text-sm font-medium text-gray-200">Último Año Cursado</label>
            <input type="text" name="antecedente_escolar[ultimo_anio_cursado]" maxlength="4"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Cursos Repetidos</label>
            <input type="number" name="antecedente_escolar[cursos_repetidos]" min="0"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Pertenece al 20%</label>
            <select name="antecedente_escolar[pertenece_20]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                <option value="">Seleccionar...</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Tiene Informe 20%</label>
            <select name="antecedente_escolar[informe_20]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                <option value="">Seleccionar...</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Problemas de Aprendizaje</label>
            <select name="antecedente_escolar[prob_apren]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                <?php foreach (['Sin','Con','Desconocido'] as $p): ?>
                    <option value="<?= $p ?>"><?= $p ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">PIE</label>
            <select name="antecedente_escolar[pie]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                <?php foreach (['Si','No','No se sabe'] as $p): ?>
                    <option value="<?= $p ?>"><?= $p ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Chile Solidario</label>
            <select name="antecedente_escolar[chile_solidario]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                <option value="">Seleccionar...</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Tipo Chile Solidario</label>
            <select name="antecedente_escolar[chile_solidario_cual]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                <option value="">Seleccionar...</option>
                <?php foreach (['Ninguno','Prioritario','Preferente','Incremento','Pro-Retención'] as $v): ?>
                    <option value="<?= $v ?>"><?= $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Grupo Fonasa</label>
            <select name="antecedente_escolar[grupo_fonasa]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                <?php foreach (['Ninguno','A','B','C','D','No Sabe'] as $g): ?>
                    <option value="<?= $g ?>"><?= $g ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Isapre</label>
            <select name="antecedente_escolar[isapre]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                <?php foreach (['Ninguno','BANCA MEDICA','CRUZ BLANCA','COLMENA','MAS VIDA','CON SALUD','VIDA TRES','DIPRECA'] as $i): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200">Seguro de Salud</label>
            <input type="text" name="antecedente_escolar[seguro_salud]"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-200">Información de Salud</label>
            <textarea name="antecedente_escolar[info_salud]" rows="3"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"></textarea>
        </div>

    </div>

    <div class="flex justify-between pt-8">
        <button type="button"
            class="prev-step px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500
                   hover:to-gray-600 text-white font-semibold rounded-xl shadow-lg transition duration-200">
            ← Anterior
        </button>
        <button type="button"
            class="next-step inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600
                   to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-xl
                   shadow-md transition-all duration-200">
            Siguiente →
        </button>
    </div>
</div>