<!-- ============================
     PASO 1: DATOS DEL ALUMNO
============================= -->
<div class="step transition-all duration-300 ease-out transform" data-step="1">
    <h2 class="text-3xl font-extrabold mb-6 pb-3 bg-gradient-to-r from-indigo-400 to-blue-400 text-transparent bg-clip-text drop-shadow">
        Datos del Alumno
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- RUN -->
        <div>
            <label for="run" class="block text-sm font-medium text-gray-200">RUN</label>
            <input type="text" name="run" id="run" required placeholder="Ej: 12.345.678"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
            <p id="run-error" class="text-red-500 text-sm mt-1 hidden">
                RUN inválido (debe estar entre 1.000.000 y 100.000.000).
            </p>
            <p id="run-exists-msg" class="text-red-500 text-sm mt-1 hidden"></p>
        </div>

        <!-- Código verificador -->
        <div>
            <label for="codver" class="block text-sm font-medium text-gray-200">Código Verificador</label>
            <input type="text" name="codver" id="codver" required readonly
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 text-center cursor-not-allowed">
        </div>

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

        <!-- Fecha Nacimiento -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Fecha de Nacimiento</label>
            <input type="date" name="fechanac"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
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

        <!-- Sexo -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Sexo</label>
            <select name="sexo" required
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                <option value="F">Femenino</option>
                <option value="M">Masculino</option>
            </select>
        </div>

        <!-- Nacionalidad -->
        <div>
            <label class="block text-sm font-medium text-gray-200">Nacionalidad</label>
            <input type="text" name="nacionalidades"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
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
        <div>
            <label class="block text-sm font-medium text-gray-200">Dirección</label>
            <input type="text" name="direccion"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <!-- Etnia -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-200">Etnia</label>
            <select name="cod_etnia"
                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                <?php
                $etnias = [
                    "No pertenece a ningún Pueblo Originario","Aymara",
                    "Likanantai( Atacameño )","Colla","Diaguita","Quechua",
                    "Rapa Nui","Mapuche","Kawésqar","Yagán","Otro","No Registra",
                ];
                foreach ($etnias as $i => $etnia): ?>
                    <option value="<?= $etnia ?>" <?= $i === 0 ? 'selected' : '' ?>>
                        <?= $etnia ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

    </div>

    <div class="flex justify-end pt-6">
        <button type="button"
            class="next-step inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600
                   hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-md transition-all duration-200">
            Siguiente →
        </button>
    </div>
</div>