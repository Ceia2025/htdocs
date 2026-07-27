<!-- ============================
     PASO 1: DATOS DEL ALUMNO
============================= -->
<div class="step transition-all duration-300 ease-out transform" data-step="1">
    <h2
        class="text-3xl font-extrabold mb-6 pb-3 bg-gradient-to-r from-indigo-400 to-blue-400 text-transparent bg-clip-text drop-shadow">
        Datos del Alumno
    </h2>

    <!-- ORIGEN / DOCUMENTO -->
    <div class="mb-6 rounded-2xl border border-indigo-700/40 bg-indigo-900/20 p-5">
        <!-- Hidden que asegura el valor 0 cuando el checkbox va desmarcado -->
        <input type="hidden" name="tiene_run" value="0">

        <label for="nacido_chile" class="flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" id="nacido_chile" name="tiene_run" value="1" checked
                class="h-5 w-5 rounded border-gray-600 bg-gray-800 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-gray-900">
            <span class="text-base font-semibold text-white">
                ¿Nació en Chile? <span class="text-gray-400 font-normal">(tiene RUN chileno)</span>
            </span>
        </label>

        <p class="text-sm text-gray-400 mt-3 leading-relaxed">
            Déjalo marcado para alumnos chilenos: el RUN se valida y se calcula el dígito verificador
            automáticamente. <span class="text-indigo-300">Desmárcalo</span> para estudiantes extranjeros
            sin RUN: podrás escribir su número de documento libremente (pasaporte, DNI, IPE, etc.), la etnia
            quedará como <strong>"Otro"</strong> y la nacionalidad será obligatoria.
        </p>
    </div>

    <!-- ── IDENTIDAD ── -->
    <div class="mb-6">
        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-300 mb-3">Identificación del alumno</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium text-gray-200">Nombre</label>
                <input type="text" name="nombre" required
                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <!-- Apellido Paterno -->
            <div>
                <label class="block text-sm font-medium text-gray-200">Apellido Paterno</label>
                <input type="text" name="apepat" required
                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <!-- Apellido Materno -->
            <div>
                <label class="block text-sm font-medium text-gray-200">Apellido Materno</label>
                <input type="text" name="apemat"
                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
            </div>
        </div>
    </div>

    <!-- ── DOCUMENTO ── -->
    <div class="mb-6">
        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-300 mb-3">Documento de identidad</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- RUN / Documento -->
            <div class="md:col-span-2">
                <label for="run" class="block text-sm font-medium text-gray-200">
                    <span id="run-label-texto">RUN</span>
                </label>
                <input type="text" name="run" id="run" required placeholder="Ej: 12.345.678"
                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                <p id="run-error" class="text-red-500 text-sm mt-1 hidden">
                    RUN inválido (debe estar entre 1.000.000 y 100.000.000).
                </p>
                <p id="run-exists-msg" class="text-red-500 text-sm mt-1 hidden"></p>
                <p id="run-hint-extranjero" class="text-indigo-300 text-xs mt-1 hidden">
                    Número de documento libre (sin validación de RUN).
                </p>
            </div>
            <!-- Código verificador -->
            <div>
                <label for="codver" class="block text-sm font-medium text-gray-200">Código Verificador</label>
                <input type="text" name="codver" id="codver" required readonly
                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 text-center cursor-not-allowed">
            </div>
        </div>
    </div>

    <!-- ── RESTO DE DATOS ── -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Fecha Nacimiento -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Fecha de Nacimiento</label>
            <input type="date" name="fechanac"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <!-- Sexo -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Sexo</label>
            <select name="sexo" required
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                <option value="">Seleccione</option>
                <option value="" disabled>──────────</option>
                <option value="F">Mujer</option>
                <option value="M">Hombre</option>
                <option value="X">No especificado</option>
            </select>
        </div>

        <!-- Número de hijos -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Número de Hijos</label>
            <input type="number" name="numerohijos" min="0"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <!-- Teléfono -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Teléfono</label>
            <input type="text" name="telefono"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Email</label>
            <input type="email" name="email"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <!-- Nacionalidad -->
        <div>
            <label for="nacionalidad" class="block text-sm font-medium text-gray-200">
                Nacionalidad <span id="nacionalidad-req" class="text-red-400 hidden">*</span>
            </label>
            <input type="text" name="nacionalidades" id="nacionalidad" list="lista-nacionalidades" value="Chilena"
                placeholder="Ej: Chilena, Venezolana, Haitiana..."
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
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

        <!-- Región -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Región</label>
            <select id="region" name="region"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                <option value="">Seleccione una región</option>
            </select>
        </div>

        <!-- Ciudad / Comuna -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Ciudad / Comuna</label>
            <select id="ciudad" name="ciudad"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                <option value="">Seleccione una ciudad</option>
            </select>
        </div>

        <!-- Dirección -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-200">Dirección</label>
            <input type="text" name="direccion"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <!-- Etnia -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-200">Etnia</label>
            <select name="cod_etnia" id="etnia_select"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
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
                    <option value="<?= $etnia ?>" <?= $i === 0 ? 'selected' : '' ?>>
                        <?= $etnia ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p id="etnia-hint-extranjero" class="text-indigo-300 text-xs mt-1 hidden">
                Etnia fijada en "Otro" para estudiantes extranjeros.
            </p>
        </div>

    </div>

    <div class="flex justify-end pt-6">
        <button type="button"
            class="next-step inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600
                   hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-md transition-all duration-200">
            Siguiente
        </button>
    </div>
</div>