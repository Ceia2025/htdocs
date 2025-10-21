<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="h-full bg-gray-900">
<div class="min-h-full">
    <!-- HEADER -->
    <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Nuevo Antecedente Escolar</h1>
        </div>
    </header>

    <!-- MAIN -->
    <main>
        <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="bg-gray-700 p-6 rounded-2xl shadow-lg">
                <h2 class="text-2xl font-bold text-white mb-6 text-center">Registrar Antecedente Escolar</h2>

                <form method="post" action="index.php?action=antecedente_escolar_store" class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <!-- Procedencia -->
                    <div>
                        <label class="text-sm text-gray-200">Procedencia del Colegio</label>
                        <input type="text" name="procedencia_colegio"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Comuna -->
                    <div>
                        <label class="text-sm text-gray-200">Comuna</label>
                        <input type="text" name="comuna"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Último curso -->
                    <div>
                        <label class="text-sm text-gray-200">Último Curso</label>
                        <select name="ultimo_curso"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php
                            $cursos = ['1ro basico','2do basico','3ro basico','4to basico','5to basico','6to basico','7mo basico','8vo basico','1ro medio','2do medio','3ro Medio','4to Medio'];
                            foreach ($cursos as $c) echo "<option value='$c'>$c</option>";
                            ?>
                        </select>
                    </div>

                    <!-- Último año cursado -->
                    <div>
                        <label class="text-sm text-gray-200">Último Año Cursado</label>
                        <input type="text" name="ultimo_anio_cursado" maxlength="4"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Cursos repetidos -->
                    <div>
                        <label class="text-sm text-gray-200">Cursos Repetidos</label>
                        <input type="number" name="cursos_repetidos" min="0"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Pertenece 20% -->
                    <div>
                        <label class="text-sm text-gray-200">Pertenece al 20%</label>
                        <select name="pertenece_20"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- Informe 20% -->
                    <div>
                        <label class="text-sm text-gray-200">Tiene Informe 20%</label>
                        <select name="informe_20"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- Embarazo -->
                    <div>
                        <label class="text-sm text-gray-200">Embarazo</label>
                        <select name="embarazo"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- Semanas de embarazo -->
                    <div>
                        <label class="text-sm text-gray-200">Semanas</label>
                        <input type="number" name="semanas" min="0"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Evaluación psicológica -->
                    <div>
                        <label class="text-sm text-gray-200">Evaluación Psicológica</label>
                        <input type="text" name="eva_psico"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Problemas de aprendizaje -->
                    <div>
                        <label class="text-sm text-gray-200">Problemas de Aprendizaje</label>
                        <select name="prob_apren"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <?php
                            $pa = ['Sin', 'Con', 'Desconocido'];
                            foreach ($pa as $p) echo "<option value='$p'>$p</option>";
                            ?>
                        </select>
                    </div>

                    <!-- PIE -->
                    <div>
                        <label class="text-sm text-gray-200">PIE</label>
                        <select name="pie"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <?php
                            $pie = ['Si', 'No', 'No se sabe'];
                            foreach ($pie as $p) echo "<option value='$p'>$p</option>";
                            ?>
                        </select>
                    </div>

                    <!-- Chile Solidario -->
                    <div>
                        <label class="text-sm text-gray-200">Chile Solidario</label>
                        <select name="chile_solidario"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- Chile Solidario Cual -->
                    <div>
                        <label class="text-sm text-gray-200">Tipo Chile Solidario</label>
                        <select name="chile_solidario_cual"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <option value="">Seleccionar...</option>
                            <?php
                            $cs = ['Prioritario', 'Preferente', 'Incremento', 'Pro-Retención'];
                            foreach ($cs as $v) echo "<option value='$v'>$v</option>";
                            ?>
                        </select>
                    </div>

                    <!-- Fonasa -->
                    <div>
                        <label class="text-sm text-gray-200">Fonasa</label>
                        <input type="text" name="fonasa"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Grupo Fonasa -->
                    <div>
                        <label class="text-sm text-gray-200">Grupo Fonasa</label>
                        <select name="grupo_fonasa"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <?php
                            $gf = ['Ninguno', 'A', 'B', 'C', 'D'];
                            foreach ($gf as $g) echo "<option value='$g'>$g</option>";
                            ?>
                        </select>
                    </div>

                    <!-- Isapre -->
                    <div>
                        <label class="text-sm text-gray-200">Isapre</label>
                        <select name="isapre"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2">
                            <?php
                            $isapres = ['Ninguno','BANCA MEDICA','CRUZ BLANCA','COLMENA','MAS VIDA','CON SALUD','VIDA TRES','DIPRECA'];
                            foreach ($isapres as $i) echo "<option value='$i'>$i</option>";
                            ?>
                        </select>
                    </div>

                    <!-- Seguro salud -->
                    <div>
                        <label class="text-sm text-gray-200">Seguro de Salud</label>
                        <input type="text" name="seguro_salud"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Info salud -->
                    <div class="sm:col-span-2">
                        <label class="text-sm text-gray-200">Información de Salud</label>
                        <textarea name="info_salud" rows="3"
                            class="w-full mt-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <!-- BOTÓN -->
                    <div class="sm:col-span-2 mt-4">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
                            Guardar
                        </button>
                    </div>

                </form>
            </div>

            <!-- VOLVER -->
            <div class="mt-8 flex items-center justify-center">
                <a href="index.php?action=antecedente_escolar"
                    class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                    ⬅ Volver
                </a>
            </div>
        </div>
    </main>
</div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>
