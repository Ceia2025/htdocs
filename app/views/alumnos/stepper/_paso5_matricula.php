<!-- ============================
     PASO 5: MATRÍCULA
============================= -->
<div class="step transition-all duration-300 ease-out transform hidden" data-step="5">
    <h2 class="text-3xl font-extrabold mb-6 pb-3 bg-gradient-to-r from-purple-400 to-indigo-400 text-transparent bg-clip-text drop-shadow">
        Matrícula del Alumno
    </h2>
    <p class="text-gray-300 mb-6">
        Estás matriculando al alumno que estás creando en este formulario.<br>
        Al guardar, se registrará el alumno y su matrícula (curso y año académico).
    </p>

    <!-- Curso -->
    <div class="mb-5">
        <label for="curso_id" class="block text-sm font-medium text-gray-200">Curso</label>
        <select name="curso_id" id="curso_id" required
            class="mt-2 w-full rounded-xl bg-gray-900/80 border border-gray-700 text-white px-4 py-3 shadow-inner
                   focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-200">
            <option value="">Seleccione curso</option>
            <?php foreach ($cursos as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Año académico -->
    <div class="mb-5">
        <label for="anio_id" class="block text-sm font-medium text-gray-200">Año Académico</label>
        <select name="anio_id" id="anio_id" required
            class="mt-2 w-full rounded-xl bg-gray-900/80 border border-gray-700 text-white px-4 py-3 shadow-inner
                   focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-200">
            <option value="">Seleccione año</option>
            <?php foreach ($anios as $an): ?>
                <option value="<?= $an['id'] ?>"><?= htmlspecialchars($an['anio']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="flex justify-between pt-8">
        <button type="button"
            class="prev-step px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500
                   hover:to-gray-600 text-white font-semibold rounded-xl shadow-lg transition duration-200">
            ← Anterior
        </button>
        <button type="submit" id="btnGuardar"
            class="btn-save inline-flex items-center gap-2 px-7 py-3 bg-gradient-to-r from-green-600
                   to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold
                   rounded-xl shadow-md transition-all duration-200">
            💾 Guardar Alumno + Matrícula
        </button>
    </div>
</div>