<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

//-----------------------------------------
// Solo para los datos de antecedentes familiares
// Conexión a BD para cargar alumnos
$db = new Connection();
$conn = $db->open();
$alumnos = $conn->query("SELECT id, nombre FROM alumnos2 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

// Opciones ENUM
$niveles = [
    'Basica Incompleta',
    'Basica Completa',
    'Media Incompleta',
    'Media Completa',
    'Técnico Incompleta',
    'Técnico Completa',
    'Superior Incompleta',
    'Superior Completa',
    'Desconocido'
];

//-----------------------------------------

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<header
    class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white">Registro Completo de Alumno</h1>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 text-white">
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl border border-gray-700">

        <!-- Barra de progreso -->
        <div class="mb-10">
            <div class="flex justify-between text-sm font-medium mb-2">
                <span>Paso 1</span><span>Paso 2</span><span>Paso 3</span><span>Paso 4</span>
            </div>
            <div class="w-full bg-gray-700 h-2 rounded-full overflow-hidden">
                <div id="progress-bar" class="h-2 bg-indigo-600 transition-all duration-500 w-1/4"></div>
            </div>
        </div>

        <form method="POST" action="index.php?action=alumnos_store_stepper" id="stepperForm" class="space-y-10">

            <!-- 🧾 PASO 1: DATOS DEL ALUMNO -->
            <div class="step" data-step="1">
                <h2 class="text-2xl font-bold mb-6 border-b border-gray-600 pb-2">1️⃣ Datos del Alumno</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="run" class="block text-sm font-medium text-gray-200">RUN</label>
                        <input type="text" name="run" id="run" required class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 
               focus:ring-indigo-500 focus:outline-none" placeholder="Ej: 12.345.678">

                        <!-- Error de formato -->
                        <p id="run-error" class="text-red-500 text-sm mt-1 hidden">
                            RUN inválido (debe estar entre 1.000.000 y 100.000.000)
                        </p>

                        <!-- Error de duplicado -->
                        <p id="run-exists-msg" class="text-red-500 text-sm mt-1 hidden">
                            Este RUN ya se encuentra registrado.
                        </p>
                    </div>

                    <!-- CÓDIGO VERIFICADOR -->
                    <div>
                        <label for="codver" class="block text-sm font-medium text-gray-200">Código
                            Verificador</label>
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

                    <!-- Fecha de Nacimiento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Fecha de Nacimiento</label>
                        <input type="date" name="fechanac"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Número de Hijos -->
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

                    <!-- Ciudad/Comuna -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-200">Ciudad / Comuna</label>
                        <select id="ciudad" name="ciudad"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">Seleccione una ciudad</option>
                        </select>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Direccion</label>
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
                                "No Registra"
                            ];
                            foreach ($etnias as $etnia): ?>
                                <option value="<?= $etnia ?>" <?= (!isset($alumno['cod_etnia']) && $etnia === "No pertenece a ningún Pueblo Originario")
                                      || (isset($alumno['cod_etnia']) && $alumno['cod_etnia'] === $etnia)
                                      ? 'selected' : '' ?>>
                                    <?= $etnia ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="button" class="next-step btn-indigo">Siguiente ➡️</button>
                </div>
            </div>

            <!-- 🚑 PASO 2: CONTACTOS DE EMERGENCIA -->
            <div class="step hidden" data-step="2">
                <h2 class="text-2xl font-bold mb-6 border-b border-gray-600 pb-2">2️⃣ Contactos de Emergencia</h2>



                <div id="emergencias-container" class="space-y-6">


                    <!-- Nombre del contacto -->
                    <div>
                        <label for="nombre_contacto" class="block text-sm font-medium text-gray-200">Nombre del
                            contacto</label>
                        <input type="text" name="emergencias[0][nombre_contacto]" id="nombre_contacto" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-200">Teléfono</label>
                        <input type="text" name="emergencias[0][telefono]" id="telefono" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label for="direccion" class="block text-sm font-medium text-gray-200">Dirección</label>
                        <input type="text" name="emergencias[0][direccion]" id="direccion"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Relación -->
                    <div>
                        <label for="relacion" class="block text-sm font-medium text-gray-200">Relación</label>
                        <select name="emergencias[0][relacion]" id="relacion" required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">-- Selecciona --</option>
                            <option value="Madre">Madre</option>
                            <option value="Padre">Padre</option>
                            <option value="Hermana/Hermano">Hermana/Hermano</option>
                            <option value="Tutor Legal">Tutor Legal</option>
                            <option value="Representante">Representante</option>
                            <option value="Apoderado">Apoderado</option>
                        </select>
                    </div>


                </div>

                <button type="button" id="addEmergencia" class="mt-4 btn-blue">Agregar otro contacto</button>

                <div class="flex justify-between pt-8">
                    <button type="button" class="prev-step btn-gray">Anterior</button>
                    <button type="button" class="next-step btn-indigo">Siguiente</button>
                </div>
            </div>

            <!-- 👨‍👩‍👧 PASO 3: ANTECEDENTES FAMILIARES -->
            <div class="step hidden" data-step="3">
                <h2 class="text-2xl font-bold mb-6 border-b border-gray-600 pb-2">3️⃣ Antecedentes Familiares</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Padre -->
                    <div>
                        <label for="padre" class="block text-sm font-medium text-gray-200">Escolaridad Padre</label>
                        <select name="padre" id="padre"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                            <?php foreach ($niveles as $nivel): ?>
                                <option value="<?= $nivel ?>"><?= $nivel ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nivel ciclo padre -->
                    <div>
                        <label for="nivel_ciclo_p" class="block text-sm font-medium text-gray-200">Nivel o Ciclo
                            Padre</label>
                        <input type="text" name="nivel_ciclo_p" id="nivel_ciclo_p"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                    </div>

                    <!-- Madre -->
                    <div>
                        <label for="madre" class="block text-sm font-medium text-gray-200">Escolaridad Madre</label>
                        <select name="madre" id="madre"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                            <?php foreach ($niveles as $nivel): ?>
                                <option value="<?= $nivel ?>"><?= $nivel ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nivel ciclo madre -->
                    <div>
                        <label for="nivel_ciclo_m" class="block text-sm font-medium text-gray-200">Nivel o Ciclo
                            Madre</label>
                        <input type="text" name="nivel_ciclo_m" id="nivel_ciclo_m"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                    </div>
                </div>

                <div class="flex justify-between pt-8">
                    <button type="button" class="prev-step btn-gray">Anterior</button>
                    <button type="button" class="next-step btn-indigo">Siguiente</button>
                </div>

            </div>

            <!-- 🏫 PASO 4: ANTECEDENTE ESCOLAR -->
            <div class="step hidden" data-step="4">
                <h2 class="text-2xl font-bold mb-6 border-b border-gray-600 pb-2">4️⃣ Antecedente Escolar</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Procedencia -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Procedencia del Colegio</label>
                        <input type="text" name="antecedente_escolar[procedencia_colegio]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Comuna -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Comuna</label>
                        <input type="text" name="antecedente_escolar[comuna]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Último Curso -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Último Curso</label>
                        <select name="antecedente_escolar[ultimo_curso]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php
                            $cursos = ['1ro basico', '2do basico', '3ro basico', '4to basico', '5to basico', '6to basico', '7mo basico', '8vo basico', '1ro medio', '2do medio', '3ro Medio', '4to Medio'];
                            foreach ($cursos as $c)
                                echo "<option value='$c'>$c</option>";
                            ?>
                        </select>
                    </div>

                    <!-- Último Año Cursado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Último Año Cursado</label>
                        <input type="text" name="antecedente_escolar[ultimo_anio_cursado]" maxlength="4"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Cursos Repetidos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Cursos Repetidos</label>
                        <input type="number" name="antecedente_escolar[cursos_repetidos]" min="0"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Pertenece al 20% -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Pertenece al 20%</label>
                        <select name="antecedente_escolar[pertenece_20]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- Informe 20% -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Tiene Informe 20%</label>
                        <select name="antecedente_escolar[informe_20]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- Problemas de Aprendizaje -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Problemas de Aprendizaje</label>
                        <select name="antecedente_escolar[prob_apren]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <?php
                            $pa = ['Sin', 'Con', 'Desconocido'];
                            foreach ($pa as $p)
                                echo "<option value='$p'>$p</option>";
                            ?>
                        </select>
                    </div>

                    <!-- PIE -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">PIE</label>
                        <select name="antecedente_escolar[pie]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <?php
                            $pie = ['Si', 'No', 'No se sabe'];
                            foreach ($pie as $p)
                                echo "<option value='$p'>$p</option>";
                            ?>
                        </select>
                    </div>

                    <!-- Chile Solidario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Chile Solidario</label>
                        <select name="antecedente_escolar[chile_solidario]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- Tipo Chile Solidario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Tipo Chile Solidario</label>
                        <select name="antecedente_escolar[chile_solidario_cual]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php
                            $cs = ['Prioritario', 'Preferente', 'Incremento', 'Pro-Retención'];
                            foreach ($cs as $v)
                                echo "<option value='$v'>$v</option>";
                            ?>
                        </select>
                    </div>

                    <!-- Grupo Fonasa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Grupo Fonasa</label>
                        <select name="antecedente_escolar[grupo_fonasa]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <?php
                            $gf = ['Ninguno', 'A', 'B', 'C', 'D'];
                            foreach ($gf as $g)
                                echo "<option value='$g'>$g</option>";
                            ?>
                        </select>
                    </div>

                    <!-- Isapre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Isapre</label>
                        <select name="antecedente_escolar[isapre]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <?php
                            $isapres = ['Ninguno', 'BANCA MEDICA', 'CRUZ BLANCA', 'COLMENA', 'MAS VIDA', 'CON SALUD', 'VIDA TRES', 'DIPRECA'];
                            foreach ($isapres as $i)
                                echo "<option value='$i'>$i</option>";
                            ?>
                        </select>
                    </div>

                    <!-- Seguro Salud -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Seguro de Salud</label>
                        <input type="text" name="antecedente_escolar[seguro_salud]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Información de Salud -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-200">Información de Salud</label>
                        <textarea name="antecedente_escolar[info_salud]" rows="3"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                    </div>
                </div>

                <div class="flex justify-between pt-8">
                    <button type="button" class="prev-step btn-gray">⬅️ Anterior</button>
                    <button type="submit" id="btnGuardar" class="btn-green">Guardar ✅</button>
                </div>

            </div>

        </form>

        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=dashboard"
                class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                Volver
            </a>
        </div>
    </div>
</main>

<style>
    .input-step {
        @apply mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none;
    }

    .btn-indigo {
        @apply bg-indigo-600 hover:bg-indigo-700 px-6 py-2 rounded-lg font-semibold transition;
    }

    .btn-gray {
        @apply bg-gray-600 hover:bg-gray-500 px-6 py-2 rounded-lg font-semibold transition;
    }

    .btn-blue {
        @apply bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg font-semibold transition;
    }

    .btn-green {
        @apply bg-green-600 hover:bg-green-700 px-6 py-2 rounded-lg font-semibold transition;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let currentStep = 1;
        const steps = document.querySelectorAll('.step');
        const progressBar = document.getElementById('progress-bar');
        const runInput = document.getElementById('run');
        const codverInput = document.getElementById('codver');
        const runError = document.getElementById('run-error');
        const runExistsMsg = document.getElementById('run-exists-msg');
        let runDuplicado = false; // 🔹 controla si el RUN ya existe

        // ✅ Mostrar paso actual y deshabilitar validación de los demás
        function showStep(step) {
            steps.forEach((s, i) => {
                const isActive = (i + 1) === step;
                s.classList.toggle('hidden', !isActive);

                s.querySelectorAll('[required]').forEach(input => {
                    if (!isActive) {
                        input.removeAttribute('required');
                    } else {
                        input.setAttribute('required', true);
                    }
                });
            });
            progressBar.style.width = (step / steps.length * 100) + '%';
        }

        // 🔹 Botón "Siguiente"
        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', (e) => {
                // ⛔ Si el RUN ya existe, no permitir avanzar
                if (runDuplicado) {
                    alert("⚠️ No puedes continuar. El RUN ya está registrado en el sistema.");
                    e.preventDefault();
                    return;
                }
                if (currentStep < steps.length) currentStep++;
                showStep(currentStep);
            });
        });

        // 🔹 Botón "Anterior"
        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentStep > 1) currentStep--;
                showStep(currentStep);
            });
        });

        // 🔹 Agregar contacto de emergencia dinámico
        let count = 1;
        document.getElementById('addEmergencia').addEventListener('click', () => {
            const container = document.getElementById('emergencias-container');
            const newDiv = document.createElement('div');
            newDiv.classList.add('emergencia', 'bg-gray-900', 'p-4', 'rounded-lg', 'border', 'border-gray-700', 'mt-4');
            newDiv.innerHTML = `
            <label class="block text-sm text-gray-300">Nombre Contacto</label>
            <input type="text" name="emergencias[${count}][nombre_contacto]" class="input-step mb-2">
            <label class="block text-sm text-gray-300">Teléfono</label>
            <input type="text" name="emergencias[${count}][telefono]" class="input-step mb-2">
            <label class="block text-sm text-gray-300">Relación</label>
            <input type="text" name="emergencias[${count}][relacion]" class="input-step mb-2">
            <label class="block text-sm text-gray-300">Dirección</label>
            <input type="text" name="emergencias[${count}][direccion]" class="input-step">
        `;
            container.appendChild(newDiv);
            count++;
        });

        // 🔹 Región → ciudad dinámica (JSON)
        fetch("../utils/comunas-regiones.json")
            .then(res => res.json())
            .then(data => {
                const regionSelect = document.getElementById("region");
                const ciudadSelect = document.getElementById("ciudad");

                data.regiones.forEach(r => {
                    const option = document.createElement("option");
                    option.value = r.region;
                    option.textContent = r.region;
                    regionSelect.appendChild(option);
                });

                regionSelect.addEventListener("change", () => {
                    ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>';
                    const region = data.regiones.find(r => r.region === regionSelect.value);
                    if (region) {
                        region.comunas.forEach(c => {
                            const opt = document.createElement("option");
                            opt.value = c;
                            opt.textContent = c;
                            ciudadSelect.appendChild(opt);
                        });
                    }
                });
            });

        // ✅ Habilitar todos los campos antes de enviar el formulario
        document.getElementById('stepperForm').addEventListener('submit', function (e) {
            console.log("✅ Enviando formulario...");
            if (runDuplicado) {
                e.preventDefault();
                alert("⚠️ No se puede enviar el formulario. El RUN ya existe.");
                return;
            }
            document.querySelectorAll('#stepperForm input[disabled], #stepperForm select[disabled], #stepperForm textarea[disabled]')
                .forEach(el => el.disabled = false);
        });

        // Mostrar primer paso
        showStep(currentStep);

        /* 🔹 Funciones auxiliares para RUN */
        function formatRun(value) {
            value = value.replace(/\D/g, "");
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function validateRun(value) {
            const numericValue = parseInt(value.replace(/\./g, ""), 10);
            if (isNaN(numericValue)) return false;
            return numericValue >= 1000000 && numericValue <= 100000000;
        }

        function calcularDV(rut) {
            let suma = 0;
            let multiplicador = 2;
            for (let i = rut.length - 1; i >= 0; i--) {
                suma += parseInt(rut.charAt(i), 10) * multiplicador;
                multiplicador = multiplicador < 7 ? multiplicador + 1 : 2;
            }
            const resto = 11 - (suma % 11);
            if (resto === 11) return "0";
            if (resto === 10) return "K";
            return String(resto);
        }

        // 🔹 Validación de RUN y cálculo de dígito verificador
        runInput.addEventListener('keypress', function (e) {
            if (!/[0-9]/.test(e.key)) e.preventDefault();
        });

        runInput.addEventListener('input', function (e) {
            let formatted = formatRun(e.target.value);
            e.target.value = formatted;

            if (formatted && !validateRun(formatted)) {
                runError.classList.remove('hidden');
                codverInput.value = "";
            } else {
                runError.classList.add('hidden');
                const numericValue = formatted.replace(/\./g, "");
                if (numericValue.length > 0 && validateRun(formatted)) {
                    codverInput.value = calcularDV(numericValue);
                } else {
                    codverInput.value = "";
                }
            }
        });

        // 🔹 Verificación automática de RUN en tiempo real
        let checkRunTimeout;
        runInput.addEventListener('blur', checkRun);
        runInput.addEventListener('input', () => {
            clearTimeout(checkRunTimeout);
            checkRunTimeout = setTimeout(checkRun, 700);
        });

        function checkRun() {
            const runValue = runInput.value.trim();
            if (!runValue || !validateRun(runValue)) return;

            fetch(`index.php?action=check_run_exists&run=${encodeURIComponent(runValue)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        runExistsMsg.textContent = "⚠️ Este RUN ya está registrado en el sistema.";
                        runExistsMsg.classList.remove('hidden');
                        runExistsMsg.classList.add('text-red-500');
                        runInput.classList.add('border-red-500');
                        runDuplicado = true;
                    } else {
                        runExistsMsg.textContent = "";
                        runExistsMsg.classList.add('hidden');
                        runInput.classList.remove('border-red-500');
                        runDuplicado = false;
                    }
                })
                .catch(err => console.error('Error verificando RUN:', err));
        }
    });
</script>




<?php include __DIR__ . "/../layout/footer.php"; ?>