<!-- ============================
     PASO 3: ANTECEDENTES FAMILIARES
============================= -->
<div class="step transition-all duration-300 ease-out transform hidden" data-step="3">
    <h2 class="text-3xl font-extrabold mb-6 pb-3 bg-gradient-to-r from-indigo-400 to-blue-400 text-transparent bg-clip-text drop-shadow">
        Antecedentes Familiares
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Escolaridad Padre -->
        <div>
            <label for="padre" class="block text-sm font-medium text-gray-200">Escolaridad Padre</label>
            <select name="padre" id="padre"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                <?php foreach ($niveles as $nivel): ?>
                    <option value="<?= $nivel ?>"><?= $nivel ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Nivel / Ciclo Padre -->
        <div>
            <label for="nivel_ciclo_p" class="block text-sm font-medium text-gray-200">Nivel o Ciclo Padre</label>
            <input type="text" name="nivel_ciclo_p" id="nivel_ciclo_p"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
        </div>

        <!-- Escolaridad Madre -->
        <div>
            <label for="madre" class="block text-sm font-medium text-gray-200">Escolaridad Madre</label>
            <select name="madre" id="madre"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                <?php foreach ($niveles as $nivel): ?>
                    <option value="<?= $nivel ?>"><?= $nivel ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Nivel / Ciclo Madre -->
        <div>
            <label for="nivel_ciclo_m" class="block text-sm font-medium text-gray-200">Nivel o Ciclo Madre</label>
            <input type="text" name="nivel_ciclo_m" id="nivel_ciclo_m"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
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