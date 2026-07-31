<!-- ============================
     PASO 2: CONTACTOS
============================= -->
<div class="step transition-all duration-300 ease-out transform hidden" data-step="2">

    <h2 class="text-2xl font-bold text-strong font-display mb-1">Contactos y Antecedentes Familiares</h2>
    <p class="text-muted text-sm mb-6">
        Agrega los contactos del alumno. Puedes agregar padre/madre, apoderado y contacto de emergencia por separado.
    </p>

    <!-- Contenedor dinámico -->
    <div id="contactos-container" class="space-y-5"></div>

    <!-- Botón agregar -->
    <button type="button" id="btn-agregar-contacto"
        class="btn-soft-info mt-5 inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl shadow transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Agregar contacto
    </button>

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