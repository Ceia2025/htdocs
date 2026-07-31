<!-- ============================
     PASO 1: DATOS DEL ALUMNO
============================= -->
<div class="step transition-all duration-300 ease-out transform" data-step="1">

    <h2 class="text-2xl font-bold text-strong font-display mb-6">Datos del Alumno</h2>

    <!-- ORIGEN / DOCUMENTO -->
    <div class="mb-6 rounded-2xl p-5" style="background: var(--accent-title-soft); border: 1px solid var(--azul-vivo);">
        <input type="hidden" name="tiene_run" value="0">
        <label for="nacido_chile" class="flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" id="nacido_chile" name="tiene_run" value="1" checked
                class="h-5 w-5 rounded border-gray-600 text-azul-vivo focus:ring-azul-vivo">
            <span class="text-base font-semibold text-strong">
                ¿Nació en Chile? <span class="text-muted font-normal">(tiene RUN chileno)</span>
            </span>
        </label>
        <p class="text-sm text-muted mt-3 leading-relaxed">
            Déjalo marcado para alumnos chilenos: el RUN se valida y se calcula el dígito verificador
            automáticamente. <span class="text-accent">Desmárcalo</span> para estudiantes extranjeros
            sin RUN: podrás escribir su número de documento libremente (pasaporte, DNI, IPE, etc.), la etnia
            quedará como <strong>"Otro"</strong> y la nacionalidad será obligatoria.
        </p>
    </div>

    <!-- ── IDENTIDAD ── -->
    <div class="mb-6">
        <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-3">Identificación del alumno</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Nombre</label>
                <input type="text" name="nombre" required class="input-field w-full rounded-lg px-3 py-2.5">
            </div>
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Apellido Paterno</label>
                <input type="text" name="apepat" required class="input-field w-full rounded-lg px-3 py-2.5">
            </div>
            <div>
                <label class="block text-sm font-medium text-soft mb-2">Apellido Materno</label>
                <input type="text" name="apemat" class="input-field w-full rounded-lg px-3 py-2.5">
            </div>
        </div>
    </div>

    <!-- ── DOCUMENTO ── -->
    <div class="mb-6">
        <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-3">Documento de identidad</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="md:col-span-2">
                <label for="run" class="block text-sm font-medium text-soft mb-2">
                    <span id="run-label-texto">RUN</span>
                </label>
                <input type="text" name="run" id="run" required placeholder="Ej: 12.345.678"
                    class="input-field w-full rounded-lg px-3 py-2.5">
                <p id="run-error" class="text-danger text-sm mt-1 hidden">
                    RUN inválido (debe estar entre 1.000.000 y 100.000.000).
                </p>
                <p id="run-exists-msg" class="text-danger text-sm mt-1 hidden"></p>
                <p id="run-hint-extranjero" class="text-accent text-xs mt-1 hidden">
                    Número de documento libre (sin validación de RUN).
                </p>
            </div>
            <div>
                <label for="codver" class="block text-sm font-medium text-soft mb-2">Código Verificador</label>
                <input type="text" name="codver" id="codver" required readonly
                    class="input-field w-full rounded-lg px-3 py-2.5 text-center cursor-not-allowed opacity-70">
            </div>
        </div>
    </div>

    <!-- ── RESTO DE DATOS ── -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Fecha de Nacimiento</label>
            <input type="date" name="fechanac" required
                class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
        </div>

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Sexo</label>
            <select name="sexo" required class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                <option value="">Seleccione</option>
                <option value="" disabled>──────────</option>
                <option value="F">Mujer</option>
                <option value="M">Hombre</option>
                <option value="X">No especificado</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Número de Hijos</label>
            <input type="number" name="numerohijos" min="0" class="input-field w-full rounded-lg px-3 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Teléfono</label>
            <input type="text" name="telefono" class="input-field w-full rounded-lg px-3 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Email</label>
            <input type="email" name="email" class="input-field w-full rounded-lg px-3 py-2.5">
        </div>

        <div>
            <label for="nacionalidad" class="block text-sm font-medium text-soft mb-2">
                Nacionalidad
                <span id="nacionalidad-req" class="text-danger hidden">*</span>
            </label>
            <input type="text" name="nacionalidades" id="nacionalidad" list="lista-nacionalidades" value="Chilena"
                placeholder="Ej: Chilena, Venezolana, Haitiana..." class="input-field w-full rounded-lg px-3 py-2.5">
            <datalist id="lista-nacionalidades">
                <option value="Chilena"></option>
                <option value="Argentina"></option>
                <option value="Boliviana"></option>
                <option value="Brasileña"></option>
                <option value="Colombiana"></option>
                <option value="Dominicana"></option>
                <option value="Ecuatoriana"></option>
                <option value="Haitiana"></option>
                <option value="Paraguaya"></option>
                <option value="Peruana"></option>
                <option value="Uruguaya"></option>
                <option value="Venezolana"></option>
                <option value="Otra"></option>
            </datalist>
        </div>

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Región</label>
            <select id="region" name="region" class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                <option value="">Seleccione una región</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-soft mb-2">Ciudad / Comuna</label>
            <select id="ciudad" name="ciudad" class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                <option value="">Seleccione una ciudad</option>
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-soft mb-2">Dirección</label>
            <input type="text" name="direccion" class="input-field w-full rounded-lg px-3 py-2.5">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-soft mb-2">Etnia</label>
            <select name="cod_etnia" id="etnia_select" class="input-field w-full rounded-lg px-3 py-2.5 cursor-pointer">
                <?php
                $etnias = [
                    "No pertenece a ningún Pueblo Originario",
                    "Aymara",
                    "Likanantai( Atacameño )",
                    "Colla",
                    "Diaguita",
                    "Quechua",
                    "Rapa Nui",
                    "Mapuche",
                    "Kawésqar",
                    "Yagán",
                    "Otro",
                    "No Registra",
                ];
                foreach ($etnias as $i => $etnia): ?>
                    <option value="<?= $etnia ?>" <?= $i === 0 ? 'selected' : '' ?>><?= $etnia ?></option>
                <?php endforeach; ?>
            </select>
            <p id="etnia-hint-extranjero" class="text-accent text-xs mt-1 hidden">
                Etnia fijada en "Otro" para estudiantes extranjeros.
            </p>
        </div>

    </div>

    <div class="flex justify-end pt-8">
        <button type="button"
            class="next-step btn-primary inline-flex items-center gap-2 px-6 py-3 font-semibold rounded-xl shadow transition">
            Siguiente →
        </button>
    </div>
</div>