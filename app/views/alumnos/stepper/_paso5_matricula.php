<!-- ============================
     PASO 5: MATRÍCULA
============================= -->
<div class="step transition-all duration-300 ease-out transform hidden" data-step="5">

    <h2 class="text-2xl font-bold text-strong font-display mb-1">Matrícula del Alumno</h2>
    <p class="text-muted text-sm mb-6">
        Elige en qué curso y año quedará matriculado el alumno.<br>
        Al guardar, se registrará el alumno y su matrícula.
    </p>

    <!-- Resumen del alumno (se llena por JS al entrar a este paso) -->
    <div id="resumen-matricula" class="mb-6 rounded-2xl p-5"
        style="background: var(--surface-1); border: 1px solid var(--border-soft);">
        <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-3">Vas a matricular a</p>
        <p id="resumen-nombre" class="text-lg font-bold text-strong capitalize">—</p>
        <p id="resumen-detalle" class="text-sm text-muted mt-0.5">—</p>
        <p id="resumen-pendientes" class="hidden text-sm mt-3 font-medium" style="color: #f59e0b;"></p>
    </div>

    <!-- Sugerencia desde la ficha Excel (solo si se importó) -->
    <div id="curso-sugerido-box" class="hidden mb-6 banner-success text-sm px-4 py-3 rounded-xl">
        Según la ficha (<span id="curso-sugerido-nivel" class="font-semibold"></span>),
        el curso sugerido es <span id="curso-sugerido-nombre" class="font-bold"></span>.
        Puedes cambiarlo abajo si corresponde otro.
    </div>

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
