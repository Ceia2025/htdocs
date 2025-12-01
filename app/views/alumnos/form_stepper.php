<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user   = $_SESSION['user'];
$nombre = $user['nombre'];
$rol    = $user['rol'];

//-----------------------------------------
// Conexión a BD
$db   = new Connection();
$conn = $db->open();

// Cargar alumnos (por si se usan después)
$alumnos = $conn->query("SELECT id, nombre FROM alumnos2 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

// Cargar cursos y años académicos
$cursos = $conn->query("SELECT id, nombre FROM cursos2 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$anios  = $conn->query("SELECT id, anio FROM anios2 ORDER BY anio DESC")->fetchAll(PDO::FETCH_ASSOC);

// Opciones ENUM escolaridad padres
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

<header class="relative bg-gradient-to-r from-gray-900 to-gray-800 shadow-lg border-b border-gray-700">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white drop-shadow">
            Registro Completo de Alumno
        </h1>
        <p class="text-gray-300 mt-2">
            Ingresa todos los datos requeridos en cada paso.
        </p>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 text-white">
    <div class="bg-gray-900/80 backdrop-blur-xl p-10 rounded-3xl shadow-2xl border border-gray-700/50 ring-1 ring-gray-600/20">

        <!-- PROGRESO -->
        <div class="mb-10">
            <div class="flex justify-between text-sm font-semibold text-gray-300 mb-3">
                <span class="step-label">Paso 1</span>
                <span class="step-label">Paso 2</span>
                <span class="step-label">Paso 3</span>
                <span class="step-label">Paso 4</span>
                <span class="step-label">Paso 5</span>
            </div>

            <div class="relative w-full h-3 bg-gray-700/60 rounded-full overflow-hidden shadow-inner">
                <div id="progress-bar"
                     class="absolute left-0 top-0 h-full bg-gradient-to-r from-indigo-500 to-blue-500 shadow-md transition-all duration-700 ease-out"
                     style="width: 20%;">
                </div>
            </div>
        </div>

        <form method="POST" action="index.php?action=alumnos_store_stepper" id="stepperForm" class="space-y-10">
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
                        <input
                            type="text"
                            name="run"
                            id="run"
                            required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                            placeholder="Ej: 12.345.678"
                        >
                        <p id="run-error" class="text-red-500 text-sm mt-1 hidden">
                            RUN inválido (debe estar entre 1.000.000 y 100.000.000).
                        </p>
                        <p id="run-exists-msg" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>

                    <!-- Código verificador -->
                    <div>
                        <label for="codver" class="block text-sm font-medium text-gray-200">Código Verificador</label>
                        <input
                            type="text"
                            name="codver"
                            id="codver"
                            required
                            readonly
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 text-center cursor-not-allowed"
                        >
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nombre</label>
                        <input
                            type="text"
                            name="nombre"
                            required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Apellido Paterno -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Apellido Paterno</label>
                        <input
                            type="text"
                            name="apepat"
                            required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Apellido Materno -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Apellido Materno</label>
                        <input
                            type="text"
                            name="apemat"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Fecha Nacimiento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Fecha de Nacimiento</label>
                        <input
                            type="date"
                            name="fechanac"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Número de hijos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Número de Hijos</label>
                        <input
                            type="number"
                            name="numerohijos"
                            min="0"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Teléfono</label>
                        <input
                            type="text"
                            name="telefono"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Sexo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Sexo</label>
                        <select
                            name="sexo"
                            required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                            <option value="F">Femenino</option>
                            <option value="M">Masculino</option>
                        </select>
                    </div>

                    <!-- Nacionalidad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nacionalidad</label>
                        <input
                            type="text"
                            name="nacionalidades"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Región -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Región</label>
                        <select
                            id="region"
                            name="region"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                            <option value="">Seleccione una región</option>
                        </select>
                    </div>

                    <!-- Ciudad / Comuna -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Ciudad / Comuna</label>
                        <select
                            id="ciudad"
                            name="ciudad"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                            <option value="">Seleccione una ciudad</option>
                        </select>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Dirección</label>
                        <input
                            type="text"
                            name="direccion"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Etnia -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-200">Etnia</label>
                        <select
                            name="cod_etnia"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
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
                            foreach ($etnias as $index => $etnia): ?>
                                <option value="<?= $etnia ?>" <?= $index === 0 ? 'selected' : '' ?>>
                                    <?= $etnia ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button
                        type="button"
                        class="next-step inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:shadow-indigo-500/40 transition-all duration-200">
                        Siguiente
                    </button>
                </div>
            </div>

            <!-- ============================
                 PASO 2: CONTACTOS EMERGENCIA
            ============================= -->
            <div class="step transition-all duration-300 ease-out transform hidden" data-step="2">
                <h2 class="text-3xl font-extrabold mb-6 pb-3 bg-gradient-to-r from-indigo-400 to-blue-400 text-transparent bg-clip-text drop-shadow">
                    Contactos de Emergencia
                </h2>

                <div id="emergencias-container" class="space-y-6">
                    <div>
                        <label for="nombre_contacto" class="block text-sm font-medium text-gray-200">Nombre del contacto</label>
                        <input
                            type="text"
                            name="emergencias[0][nombre_contacto]"
                            id="nombre_contacto"
                            required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-200">Teléfono</label>
                        <input
                            type="text"
                            name="emergencias[0][telefono]"
                            id="telefono"
                            required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="direccion" class="block text-sm font-medium text-gray-200">Dirección</label>
                        <input
                            type="text"
                            name="emergencias[0][direccion]"
                            id="direccion"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="relacion" class="block text-sm font-medium text-gray-200">Relación</label>
                        <select
                            name="emergencias[0][relacion]"
                            id="relacion"
                            required
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        >
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

                <div class="flex justify-between pt-8">
                    <button
                        type="button"
                        class="prev-step px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 text-white font-semibold rounded-xl shadow-lg transition duration-200">
                        Anterior
                    </button>
                    <button
                        type="button"
                        class="next-step inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:shadow-indigo-500/40 transition-all duration-200">
                        Siguiente
                    </button>
                </div>
            </div>

            <!-- ============================
                 PASO 3: ANTECEDENTES FAMILIARES
            ============================= -->
            <div class="step transition-all duration-300 ease-out transform hidden" data-step="3">
                <h2 class="text-3xl font-extrabold mb-6 pb-3 bg-gradient-to-r from-indigo-400 to-blue-400 text-transparent bg-clip-text drop-shadow">
                    Antecedentes Familiares
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Padre -->
                    <div>
                        <label for="padre" class="block text-sm font-medium text-gray-200">Escolaridad Padre</label>
                        <select
                            name="padre"
                            id="padre"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2"
                        >
                            <?php foreach ($niveles as $nivel): ?>
                                <option value="<?= $nivel ?>"><?= $nivel ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nivel ciclo padre -->
                    <div>
                        <label for="nivel_ciclo_p" class="block text-sm font-medium text-gray-200">Nivel o Ciclo Padre</label>
                        <input
                            type="text"
                            name="nivel_ciclo_p"
                            id="nivel_ciclo_p"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2"
                        >
                    </div>

                    <!-- Madre -->
                    <div>
                        <label for="madre" class="block text-sm font-medium text-gray-200">Escolaridad Madre</label>
                        <select
                            name="madre"
                            id="madre"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2"
                        >
                            <?php foreach ($niveles as $nivel): ?>
                                <option value="<?= $nivel ?>"><?= $nivel ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nivel ciclo madre -->
                    <div>
                        <label for="nivel_ciclo_m" class="block text-sm font-medium text-gray-200">Nivel o Ciclo Madre</label>
                        <input
                            type="text"
                            name="nivel_ciclo_m"
                            id="nivel_ciclo_m"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2"
                        >
                    </div>
                </div>

                <div class="flex justify-between pt-8">
                    <button
                        type="button"
                        class="prev-step px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 text-white font-semibold rounded-xl shadow-lg transition duration-200">
                        Anterior
                    </button>
                    <button
                        type="button"
                        class="next-step inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:shadow-indigo-500/40 transition-all duration-200">
                        Siguiente
                    </button>
                </div>
            </div>

            <!-- ============================
                 PASO 4: ANTECEDENTE ESCOLAR
            ============================= -->
            <div class="step transition-all duration-300 ease-out transform hidden" data-step="4">
                <h2 class="text-3xl font-extrabold mb-6 pb-3 bg-gradient-to-r from-indigo-400 to-blue-400 text-transparent bg-clip-text drop-shadow">
                    Antecedente Escolar
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Procedencia -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Procedencia del Colegio</label>
                        <input
                            type="text"
                            name="antecedente_escolar[procedencia_colegio]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Comuna -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Comuna</label>
                        <input
                            type="text"
                            name="antecedente_escolar[comuna]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Último Curso -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Último Curso</label>
                        <select
                            name="antecedente_escolar[ultimo_curso]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"
                        >
                            <option value="">Seleccionar...</option>
                            <?php
                            $cursosUlt = [
                                '1ro basico', '2do basico', '3ro basico', '4to basico',
                                '5to basico', '6to basico', '7mo basico', '8vo basico',
                                '1ro medio', '2do medio', '3ro Medio', '4to Medio'
                            ];
                            foreach ($cursosUlt as $c):
                                ?>
                                <option value="<?= $c ?>"><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Último Año Cursado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Último Año Cursado</label>
                        <input
                            type="text"
                            name="antecedente_escolar[ultimo_anio_cursado]"
                            maxlength="4"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Cursos Repetidos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Cursos Repetidos</label>
                        <input
                            type="number"
                            name="antecedente_escolar[cursos_repetidos]"
                            min="0"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Pertenece al 20% -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Pertenece al 20%</label>
                        <select
                            name="antecedente_escolar[pertenece_20]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"
                        >
                            <option value="">Seleccionar...</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- Informe 20% -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Tiene Informe 20%</label>
                        <select
                            name="antecedente_escolar[informe_20]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"
                        >
                            <option value="">Seleccionar...</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- Problemas de Aprendizaje -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Problemas de Aprendizaje</label>
                        <select
                            name="antecedente_escolar[prob_apren]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"
                        >
                            <?php
                            $pa = ['Sin', 'Con', 'Desconocido'];
                            foreach ($pa as $p):
                                ?>
                                <option value="<?= $p ?>"><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- PIE -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">PIE</label>
                        <select
                            name="antecedente_escolar[pie]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"
                        >
                            <?php
                            $pie = ['Si', 'No', 'No se sabe'];
                            foreach ($pie as $p):
                                ?>
                                <option value="<?= $p ?>"><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Chile Solidario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Chile Solidario</label>
                        <select
                            name="antecedente_escolar[chile_solidario]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"
                        >
                            <option value="">Seleccionar...</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- Tipo Chile Solidario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Tipo Chile Solidario</label>
                        <select
                            name="antecedente_escolar[chile_solidario_cual]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"
                        >
                            <option value="">Seleccionar...</option>
                            <?php
                            $cs = ['Prioritario', 'Preferente', 'Incremento', 'Pro-Retención'];
                            foreach ($cs as $v):
                                ?>
                                <option value="<?= $v ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Grupo Fonasa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Grupo Fonasa</label>
                        <select
                            name="antecedente_escolar[grupo_fonasa]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"
                        >
                            <?php
                            $gf = ['Ninguno', 'A', 'B', 'C', 'D'];
                            foreach ($gf as $g):
                                ?>
                                <option value="<?= $g ?>"><?= $g ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Isapre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Isapre</label>
                        <select
                            name="antecedente_escolar[isapre]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2"
                        >
                            <?php
                            $isapres = ['Ninguno', 'BANCA MEDICA', 'CRUZ BLANCA', 'COLMENA', 'MAS VIDA', 'CON SALUD', 'VIDA TRES', 'DIPRECA'];
                            foreach ($isapres as $i):
                                ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Seguro Salud -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Seguro de Salud</label>
                        <input
                            type="text"
                            name="antecedente_escolar[seguro_salud]"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>

                    <!-- Información Salud -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-200">Información de Salud</label>
                        <textarea
                            name="antecedente_escolar[info_salud]"
                            rows="3"
                            class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none"
                        ></textarea>
                    </div>
                </div>

                <div class="flex justify-between pt-8">
                    <button
                        type="button"
                        class="prev-step px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 text-white font-semibold rounded-xl shadow-lg transition duration-200">
                        Anterior
                    </button>
                    <button
                        type="button"
                        class="next-step inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:shadow-indigo-500/40 transition-all duration-200">
                        Siguiente
                    </button>
                </div>
            </div>

            <!-- ============================
                 PASO 5: MATRÍCULA
            ============================= -->
            <div class="step transition-all duration-300 ease-out transform hidden" data-step="5">
                <h2 class="text-3xl font-extrabold mb-6 pb-3 bg-gradient-to-r from-purple-400 to-indigo-400 text-transparent bg-clip-text drop-shadow">
                    Matrícula del Alumno
                </h2>

                <p class="text-gray-300 mb-6">
                    Estás matriculando al alumno que estás creando en este formulario. <br>
                    Al guardar, se registrará el alumno y su matrícula (curso y año académico).
                </p>

                <!-- Curso -->
                <div class="mb-5">
                    <label for="curso_id" class="block text-sm font-medium text-gray-200">Curso</label>
                    <select
                        name="curso_id"
                        id="curso_id"
                        required
                        class="mt-2 w-full rounded-xl bg-gray-900/80 border border-gray-700 text-white px-4 py-3 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-200"
                    >
                        <option value="">Seleccione curso</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Año académico -->
                <div class="mb-5">
                    <label for="anio_id" class="block text-sm font-medium text-gray-200">Año Académico</label>
                    <select
                        name="anio_id"
                        id="anio_id"
                        required
                        class="mt-2 w-full rounded-xl bg-gray-900/80 border border-gray-700 text-white px-4 py-3 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition duration-200"
                    >
                        <option value="">Seleccione año</option>
                        <?php foreach ($anios as $an): ?>
                            <option value="<?= $an['id'] ?>"><?= htmlspecialchars($an['anio']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex justify-between pt-8">
                    <button
                        type="button"
                        class="prev-step px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 text-white font-semibold rounded-xl shadow-lg transition duration-200">
                        Anterior
                    </button>

                    <button
                        type="submit"
                        id="btnGuardar"
                        class="btn-save inline-flex items-center gap-2 px-7 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:shadow-green-500/40 transition-all duration-200">
                        Guardar Alumno + Matrícula
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=dashboard" class="inline-block px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg shadow transition">
                Volver
            </a>
        </div>
    </div>
</main>

<style>
    /* Animación simple para cada paso */
    .step:not(.hidden) {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('%c[INIT] Stepper + RUN script cargado', 'color:#4ade80;font-weight:bold;');

    let currentStep = 1;
    const steps       = document.querySelectorAll('.step');
    const progressBar = document.getElementById('progress-bar');

    const runInput      = document.getElementById('run');
    const codverInput   = document.getElementById('codver');
    const runError      = document.getElementById('run-error');
    const runExistsMsg  = document.getElementById('run-exists-msg');
    const stepperForm   = document.getElementById('stepperForm');
    let   runDuplicado  = false;

    // ============================
    //   FUNCIONES STEPPER
    // ============================
    function showStep(step) {
        steps.forEach((s, index) => {
            const isActive = (index + 1) === step;
            s.classList.toggle('hidden', !isActive);

            // Manejo de "required" solo en el paso activo
            s.querySelectorAll('[required]').forEach(input => {
                if (!isActive) {
                    input.dataset.wasRequired = 'true';
                    input.removeAttribute('required');
                } else {
                    if (input.dataset.wasRequired === 'true' || !('wasRequired' in input.dataset)) {
                        input.setAttribute('required', 'required');
                    }
                }
            });
        });

        if (progressBar) {
            const porcentaje = (step / steps.length) * 100;
            progressBar.style.width = porcentaje + '%';
        }
    }

    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Si el RUN está duplicado, no avanzar
            if (runDuplicado) {
                alert('⚠️ No puedes continuar. El RUN ya está registrado en el sistema.');
                e.preventDefault();
                return;
            }
            if (currentStep < steps.length) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    // ============================
    //   REGIONES / CIUDADES
    // ============================
    fetch('../utils/comunas-regiones.json')
        .then(res => {
            if (!res.ok) {
                console.error('Error cargando comunas-regiones.json:', res.status, res.statusText);
                throw new Error('No se pudo cargar comunas-regiones.json');
            }
            return res.json();
        })
        .then(data => {
            const regionSelect = document.getElementById('region');
            const ciudadSelect = document.getElementById('ciudad');
            if (!regionSelect || !ciudadSelect || !data?.regiones) return;

            data.regiones.forEach(r => {
                const option = document.createElement('option');
                option.value = r.region;
                option.textContent = r.region;
                regionSelect.appendChild(option);
            });

            regionSelect.addEventListener('change', () => {
                ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>';
                const region = data.regiones.find(r => r.region === regionSelect.value);
                if (region && Array.isArray(region.comunas)) {
                    region.comunas.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c;
                        opt.textContent = c;
                        ciudadSelect.appendChild(opt);
                    });
                }
            });
        })
        .catch(err => console.error('Error en carga de regiones/comunas:', err));

    // ============================
    //   ENVÍO DE FORMULARIO
    // ============================
    if (stepperForm) {
        stepperForm.addEventListener('submit', (e) => {
            console.log('[FORM] Enviando formulario...');
            if (runDuplicado) {
                e.preventDefault();
                alert('⚠️ No se puede enviar el formulario. El RUN ya existe.');
                return;
            }

            // Asegurar que no queden disabled
            stepperForm
                .querySelectorAll('input[disabled], select[disabled], textarea[disabled]')
                .forEach(el => el.disabled = false);
        });
    }

    // ============================
    //   LÓGICA RUN + DV
    // ============================
    if (runInput && codverInput && runError && runExistsMsg) {
        console.log('%c[RUN] Lógica de RUN inicializada', 'color:#38bdf8;');

        function formatRun(value) {
            value = value.replace(/\D/g, '');
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function validateRun(value) {
            const numericValue = parseInt(value.replace(/\./g, ''), 10);
            if (isNaN(numericValue)) return false;
            return numericValue >= 1000000 && numericValue <= 100000000;
        }

        function calcularDV(rut) {
            let suma = 0;
            let multiplicador = 2;
            for (let i = rut.length - 1; i >= 0; i--) {
                suma += parseInt(rut.charAt(i), 10) * multiplicador;
                multiplicador = (multiplicador < 7) ? multiplicador + 1 : 2;
            }
            const resto = 11 - (suma % 11);
            if (resto === 11) return '0';
            if (resto === 10) return 'K';
            return String(resto);
        }

        // Solo números en RUN
        runInput.addEventListener('keypress', (e) => {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Formatear + calcular DV
        runInput.addEventListener('input', (e) => {
            let formatted = formatRun(e.target.value);
            e.target.value = formatted;
            const numericValue = formatted.replace(/\./g, '');

            console.log('[RUN] Input:', formatted);

            if (formatted && !validateRun(formatted)) {
                runError.classList.remove('hidden');
                codverInput.value = '';
            } else {
                runError.classList.add('hidden');
                if (numericValue.length > 0 && validateRun(formatted)) {
                    const dv = calcularDV(numericValue);
                    codverInput.value = dv;
                    console.log('[RUN] DV calculado:', dv);
                } else {
                    codverInput.value = '';
                }
            }
        });

        // Verificación AJAX del RUN existente
        let checkRunTimeout;

        function checkRun() {
            const runValue = runInput.value.trim();
            if (!runValue || !validateRun(runValue)) {
                runExistsMsg.textContent = '';
                runExistsMsg.classList.add('hidden');
                runInput.classList.remove('border-red-500');
                runDuplicado = false;
                return;
            }

            console.log('[RUN] Verificando si existe:', runValue);

            fetch(`index.php?action=check_run_exists&run=${encodeURIComponent(runValue)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        runExistsMsg.textContent = '⚠️ Este RUN ya está registrado en el sistema.';
                        runExistsMsg.classList.remove('hidden');
                        runInput.classList.add('border-red-500');
                        runDuplicado = true;
                        console.log('[RUN] RUN duplicado');
                    } else {
                        runExistsMsg.textContent = '';
                        runExistsMsg.classList.add('hidden');
                        runInput.classList.remove('border-red-500');
                        runDuplicado = false;
                        console.log('[RUN] RUN disponible');
                    }
                })
                .catch(err => console.error('Error verificando RUN:', err));
        }

        runInput.addEventListener('blur', checkRun);
        runInput.addEventListener('input', () => {
            clearTimeout(checkRunTimeout);
            checkRunTimeout = setTimeout(checkRun, 700);
        });
    } else {
        console.warn('[RUN] No se encontraron los elementos necesarios para la lógica del RUN.');
    }

    // Mostrar el primer paso al cargar
    showStep(currentStep);
});
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>
