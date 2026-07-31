<!-- ============================
     PASO 5: MATRÍCULA
============================= -->
<div class="step transition-all duration-300 ease-out transform hidden" data-step="5">

    <h2 class="text-2xl font-bold text-strong font-display mb-1">Matrícula del Alumno</h2>
    <p class="text-muted text-sm mb-6">
        Estás matriculando al alumno que estás creando en este formulario.<br>
        Al guardar, se registrará el alumno y su matrícula (curso y año académico).
    </p>

    <div class="space-y-5">

        <!-- Curso -->
        <div>
            <label class="block text-sm font-medium text-soft mb-2">Curso</label>
            <select name="curso_id" id="curso_id" required
                class="input-field w-full rounded-xl px-4 py-3 cursor-pointer">
                <option value="">Seleccione curso</option>
                <?php foreach ($cursos as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Año académico -->
        <div>
            <label class="block text-sm font-medium text-soft mb-2">Año académico</label>
            <select name="anio_id" id="anio_id" required
                class="input-field w-full rounded-xl px-4 py-3 cursor-pointer">
                <option value="">Seleccione año</option>
                <?php foreach ($anios as $an): ?>
                    <option value="<?= $an['id'] ?>"><?= htmlspecialchars($an['anio']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

    </div>

    <div class="flex justify-between pt-8">
        <button type="button"
            class="prev-step btn-secondary px-6 py-2.5 font-semibold rounded-xl shadow transition">
            ← Anterior
        </button>
        <button type="submit" id="btnGuardar"
            class="btn-soft-success btn-save inline-flex items-center gap-2 px-7 py-3 font-semibold rounded-xl shadow transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            Guardar Alumno + Matrícula
        </button>
    </div>
</div>