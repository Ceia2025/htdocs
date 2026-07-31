<!-- ============================
     PASO 3: ANTECEDENTES FAMILIARES
============================= -->
<div class="step transition-all duration-300 ease-out transform hidden" data-step="3">

    <h2 class="text-2xl font-bold text-strong font-display mb-6">Antecedentes Familiares</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Escolaridad Padre</label>
            <select name="padre" id="padre" class="input-field w-full rounded-lg px-4 py-2.5 cursor-pointer">
                <?php foreach ($niveles as $nivel): ?>
                    <option value="<?= $nivel ?>"><?= $nivel ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Nivel o Ciclo Padre</label>
            <input type="text" name="nivel_ciclo_p" id="nivel_ciclo_p"
                class="input-field w-full rounded-lg px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Escolaridad Madre</label>
            <select name="madre" id="madre" class="input-field w-full rounded-lg px-4 py-2.5 cursor-pointer">
                <?php foreach ($niveles as $nivel): ?>
                    <option value="<?= $nivel ?>"><?= $nivel ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Nivel o Ciclo Madre</label>
            <input type="text" name="nivel_ciclo_m" id="nivel_ciclo_m"
                class="input-field w-full rounded-lg px-4 py-2.5">
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