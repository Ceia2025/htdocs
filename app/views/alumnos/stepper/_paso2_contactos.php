<!-- ============================
     PASO 2: CONTACTOS
============================= -->
<div class="step transition-all duration-300 ease-out transform hidden" data-step="2">
    <h2 class="text-3xl font-extrabold mb-2 pb-3 bg-gradient-to-r from-red-400 to-orange-400 text-transparent bg-clip-text drop-shadow">
        Contactos y Antecedentes Familiares
    </h2>
    <p class="text-gray-400 text-sm mb-6">
        Agrega los contactos del alumno. Puedes agregar padre/madre, apoderado y contacto de emergencia por separado.
    </p>

    <!-- Contenedor dinámico -->
    <div id="contactos-container" class="space-y-6"></div>

    <!-- Botón agregar -->
    <button type="button" id="btn-agregar-contacto"
        class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-700 hover:bg-indigo-600
               text-white text-sm font-semibold rounded-xl shadow transition">
        ➕ Agregar contacto
    </button>

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