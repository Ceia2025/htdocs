<!-- ============================
     PASO 0: IMPORTAR FICHA EXCEL
============================= -->
<div class="step transition-all duration-300 ease-out transform" data-step="0">

    <h2 class="text-2xl font-bold text-strong font-display mb-1">Importar Ficha de Matrícula</h2>
    <p class="text-muted text-sm mb-6">
        Sube la ficha Excel del alumno (.xls o .xlsx) y el formulario se llenará automáticamente.
        Después revisas cada paso y eliges el curso antes de guardar.
        Si no tienes la ficha, puedes llenarlo a mano.
    </p>

    <!-- ZONA DE CARGA -->
    <label id="import-dropzone" for="import-file"
        class="flex flex-col items-center justify-center gap-3 rounded-2xl px-6 py-10 text-center cursor-pointer transition"
        style="background: var(--surface-1); border: 2px dashed var(--border-soft);">
        <svg class="w-10 h-10 text-muted" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
        </svg>
        <span class="text-base font-semibold text-strong">Arrastra la ficha aquí o haz clic para elegirla</span>
        <span id="import-file-name" class="text-sm text-muted">Formatos aceptados: .xls, .xlsx (máx. 5 MB)</span>
        <!-- Sin name: no viaja en el POST del formulario -->
        <input type="file" id="import-file" accept=".xls,.xlsx" class="hidden">
    </label>

    <!-- ESTADO -->
    <div id="import-loading" class="hidden mt-5 flex items-center gap-3 text-sm text-muted">
        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"></circle>
            <path fill="currentColor" class="opacity-75" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"></path>
        </svg>
        Leyendo la ficha…
    </div>

    <div id="import-error" class="hidden mt-5 banner-danger text-sm px-4 py-3 rounded-xl"></div>

    <!-- RESULTADO -->
    <div id="import-resultado" class="hidden mt-6 space-y-4">

        <div id="import-duplicado" class="hidden banner-danger text-sm px-4 py-3 rounded-xl font-semibold"></div>

        <div class="rounded-2xl p-5" style="background: var(--accent-title-soft); border: 1px solid var(--azul-vivo);">
            <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-3">Datos leídos de la ficha</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm">
                <div class="flex justify-between gap-4">
                    <span class="text-muted">Alumno</span>
                    <span id="res-alumno" class="text-strong font-semibold text-right"></span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-muted">RUN / Documento</span>
                    <span id="res-run" class="text-soft font-mono text-right"></span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-muted">Nivel en la ficha</span>
                    <span id="res-nivel" class="text-soft text-right"></span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-muted">Año escolar</span>
                    <span id="res-anio" class="text-soft text-right"></span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-muted">Curso sugerido</span>
                    <span id="res-curso" class="text-strong font-semibold text-right"></span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-muted">Contactos</span>
                    <span id="res-contactos" class="text-soft text-right"></span>
                </div>
            </div>
        </div>

        <!-- AVISOS -->
        <div id="import-avisos-wrap" class="hidden rounded-2xl p-5"
            style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.45);">
            <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: #f59e0b;">
                Revisa antes de guardar
            </p>
            <ul id="import-avisos" class="space-y-1.5 text-sm text-soft list-disc pl-5"></ul>
            <p class="text-xs text-muted mt-3">Los campos con problemas quedan marcados en amarillo en cada paso.</p>
        </div>

        <button type="button" id="import-otra"
            class="btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl shadow transition">
            Cargar otra ficha
        </button>
    </div>

    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-8">
        <button type="button" id="import-next"
            class="next-step btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 font-semibold rounded-xl shadow transition">
            Llenar a mano →
        </button>
        <button type="button" id="import-saltar"
            class="hidden btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 font-semibold rounded-xl shadow transition">
            Ir directo a Matrícula ⏭
        </button>
    </div>
</div>
