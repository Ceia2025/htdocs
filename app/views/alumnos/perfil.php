<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

// Calcular edad
/*
$edad = null;
if (!empty($alumno['fechanac'])) {
    $fechaNac = new DateTime($alumno['fechanac']);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNac)->y;
}*/
$edad = $this->calcularEdadAl30Junio($alumno['fechanac']);

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<header class="relative bg-gray-800 border-b border-white/10">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white">Perfil Completo del Alumno</h1>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 text-white">
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl border border-gray-700 space-y-10">

        <!-- DATOS DEL ALUMNO -->
        <section>
            <h2 class="text-2xl font-bold border-b border-gray-600 pb-2 mb-6">Datos del Alumno</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-700 p-6 rounded-xl space-y-2">
                    <p><strong>RUN:</strong> <?= htmlspecialchars($alumno['run'] . '-' . $alumno['codver']) ?></p>
                    <p><strong>Nombre:</strong>
                        <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                    </p>
                    <p><strong>Fecha de Nacimiento:</strong>
                        <?= $alumno['fechanac'] ? (new DateTime($alumno['fechanac']))->format('d/m/Y') : 'No registrada' ?>
                    </p>
                    <p><strong>Edad:</strong> <?= $edad !== null ? "$edad años (calculada al 30 de junio)" : "No registrada" ?></p>
                    <p><strong>Sexo:</strong> <?= htmlspecialchars($alumno['sexo']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($alumno['email']) ?></p>
                    <p><strong>Teléfono:</strong> <?= htmlspecialchars($alumno['telefono']) ?></p>
                    <p><strong>celular:</strong> <?= htmlspecialchars($alumno['celular']) ?></p>
                </div>

                <div class="bg-gray-700 p-6 rounded-xl space-y-2">
                    <p><strong>Fecha Creacion:</strong> <?= htmlspecialchars($alumno['created_at']) ?></p>
                    <p><strong>Fecha Retiro:</strong> <?= htmlspecialchars($alumno['deleted_at']) ?></p>
                    <p><strong>Nacionalidad:</strong> <?= htmlspecialchars($alumno['nacionalidades']) ?></p>
                    <p><strong>Región:</strong> <?= htmlspecialchars($alumno['region']) ?></p>
                    <p><strong>Ciudad:</strong> <?= htmlspecialchars($alumno['ciudad']) ?></p>
                    <p><strong>Dirección:</strong> <?= htmlspecialchars($alumno['direccion']) ?></p>
                    <p><strong>Etnia:</strong> <?= htmlspecialchars($alumno['cod_etnia']) ?></p>
                </div>
            </div>

            <div class="flex justify-center mt-8 gap-4">

                <a href="index.php?action=alumno_edit&id=<?= $alumno['id'] ?>"
                    class="btn bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold transition">
                    Editar</a>
            </div>
        </section>

        <!-- CONTACTOS DE EMERGENCIA -->
        <section>
            <h2 class="text-2xl font-bold border-b border-gray-600 pb-2 mb-6">Contactos de Emergencia</h2>

            <?php if (!empty($contactos)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-700 rounded-xl overflow-hidden">
                        <thead class="bg-gray-700 text-gray-300">
                            <tr>
                                <th class="px-4 py-2 text-left">Nombre</th>
                                <th class="px-4 py-2 text-left">Teléfono</th>
                                <th class="px-4 py-2 text-left">Dirección</th>
                                <th class="px-4 py-2 text-left">Relación</th>
                                <th class="px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <?php foreach ($contactos as $c): ?>
                                <tr class="hover:bg-gray-600">
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['nombre_contacto']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['telefono']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['direccion']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['relacion']) ?></td>
                                    <td class="px-4 py-2">
                                        <a href="index.php?action=alum_emergencia_edit&id=<?= $c['id'] ?>&back=alumno_profile&alumno_id=<?= $alumno['id'] ?>"
                                            class="inline-block bg-blue-600 hover:bg-blue-700 px-4 py-1 rounded-lg font-semibold transition">
                                            Editar
                                        </a>
                                        <a href="index.php?action=alum_emergencia_deleteProfile&id=<?= $c['id'] ?>&alumno_id=<?= $alumno['id'] ?>"
                                            onclick="return confirm('¿Estás seguro de eliminar este contacto?');"
                                            class="inline-block bg-red-600 hover:bg-red-700 px-4 py-1 rounded-lg font-semibold text-white transition ml-2">
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-400 italic">No hay contactos de emergencia registrados.</p>
            <?php endif; ?>

            <div class="flex justify-center mt-8 gap-4">
                <!-- (Opcional) Crear nuevo y volver al perfil -->
                <a href="index.php?action=alum_emergencia_createProfile&alumno_id=<?= $alumno['id'] ?>"
                    class="btn bg-green-600 hover:bg-green-700 px-6 py-2 rounded-lg font-semibold transition">
                    Agregar contacto
                </a>
            </div>

        </section>

        <!-- ANTECEDENTES FAMILIARES -->
        <section>
            <h2 class="text-2xl font-bold border-b border-gray-600 pb-2 mb-6">Antecedentes Familiares</h2>

            <?php if (!empty($antecedentes)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-700 p-6 rounded-xl">
                    <div>
                        <p><strong>Escolaridad Padre:</strong> <?= htmlspecialchars($antecedentes['padre']) ?></p>
                        <p><strong>Nivel o Ciclo Padre:</strong> <?= htmlspecialchars($antecedentes['nivel_ciclo_p']) ?></p>
                    </div>
                    <div>
                        <p><strong>Escolaridad Madre:</strong> <?= htmlspecialchars($antecedentes['madre']) ?></p>
                        <p><strong>Nivel o Ciclo Madre:</strong> <?= htmlspecialchars($antecedentes['nivel_ciclo_m']) ?></p>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-gray-400 italic">No hay antecedentes familiares registrados.</p>
            <?php endif; ?>

            <div class="flex justify-center mt-8 gap-4">

                <a href="index.php?action=antecedentefamiliar_editProfile&alumno_id=<?= $alumno['id'] ?>"
                    class="btn bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold transition">
                    Editar
                </a>
            </div>

        </section>

        <!-- ANTECEDENTE ESCOLAR -->
        <section>
            <h2 class="text-2xl font-bold border-b border-gray-600 pb-2 mb-6">Antecedente Escolar</h2>

            <?php if (!empty($alumno['antecedente_id'])): ?>
                <div class="bg-gray-700 p-8 rounded-xl shadow-xl border border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                        <!-- Columna izquierda -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-indigo-400 mb-2">Datos Generales</h3>
                            <p><span class="font-semibold text-gray-200">Procedencia del Colegio:</span>
                                <?= htmlspecialchars($alumno['procedencia_colegio']) ?></p>
                            <p><span class="font-semibold text-gray-200">Comuna:</span>
                                <?= htmlspecialchars($alumno['comuna']) ?></p>
                            <p><span class="font-semibold text-gray-200">Último Curso:</span>
                                <?= htmlspecialchars($alumno['ultimo_curso']) ?></p>
                            <p><span class="font-semibold text-gray-200">Último Año Cursado:</span>
                                <?= htmlspecialchars($alumno['ultimo_anio_cursado']) ?></p>
                            <p><span class="font-semibold text-gray-200">Cursos Repetidos:</span>
                                <?= htmlspecialchars($alumno['cursos_repetidos']) ?></p>
                            <p><span class="font-semibold text-gray-200">Evaluación Psicológica:</span>
                                <?= htmlspecialchars($alumno['eva_psico'] ?: 'No registrada') ?></p>
                            <p><span class="font-semibold text-gray-200">Información de Salud:</span>
                                <?= htmlspecialchars($alumno['info_salud'] ?: 'No registrada') ?></p>
                        </div>

                        <!-- 🧠 Columna derecha -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-indigo-400 mb-2">Condiciones y Programas</h3>
                            <p><span class="font-semibold text-gray-200">Pertenece al 20%:</span>
                                <?= $alumno['pertenece_20'] ? 'Sí' : 'No' ?></p>
                            <p><span class="font-semibold text-gray-200">Informe 20%:</span>
                                <?= $alumno['informe_20'] ? 'Sí' : 'No' ?></p>
                            <p><span class="font-semibold text-gray-200">Embarazo:</span>
                                <?= $alumno['embarazo'] ? 'Sí (' . htmlspecialchars($alumno['semanas'] ?? '-') . ' semanas)' : 'No' ?>
                            </p>
                            <p><span class="font-semibold text-gray-200">Problemas de Aprendizaje:</span>
                                <?= htmlspecialchars($alumno['prob_apren']) ?></p>
                            <p><span class="font-semibold text-gray-200">PIE:</span> <?= htmlspecialchars($alumno['pie']) ?>
                            </p>
                            <p><span class="font-semibold text-gray-200">Chile Solidario:</span>
                                <?= $alumno['chile_solidario']
                                    ? htmlspecialchars($alumno['chile_solidario_cual'] ?? 'Sí')
                                    : 'No' ?>
                            </p>
                            <p><span class="font-semibold text-gray-200">Grupo Fonasa:</span>
                                <?= htmlspecialchars($alumno['grupo_fonasa'] ?: 'No registra') ?></p>
                            <p><span class="font-semibold text-gray-200">Isapre:</span>
                                <?= htmlspecialchars($alumno['isapre'] ?: 'No registra') ?></p>
                            <p><span class="font-semibold text-gray-200">Seguro de Salud:</span>
                                <?= htmlspecialchars($alumno['seguro_salud'] ?: 'No registra') ?></p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-gray-400 italic">No hay antecedente escolar registrado para este alumno.</p>
            <?php endif; ?>

            <div class="flex justify-center mt-8 gap-4">
                <a href="index.php?action=antecedente_escolar_editProfile&alumno_id=<?= $alumno['id'] ?>"
                    class="btn bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold transition">
                    Editar</a>
            </div>


        </section>


        <!-- BOTONES -->
        <div class="flex justify-center mt-8 gap-4 w-full">
            <a href="index.php?action=alumnos"
                class=" flex justify-center btn bg-indigo-600 hover:bg-indigo-700 px-6 py-2 rounded-lg font-semibold transition w-full">
                Volver
            </a>
        </div>



    </div>

    <!-- BOTÓN FLOTANTE DESCARGAR PDF -->
    <a href="index.php?action=alumno_pdf&id=<?= $alumno['id'] ?>" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3 
          bg-green-600 hover:bg-green-700 text-white font-bold rounded-full 
          shadow-2xl shadow-green-500/40 transition-all duration-300
          hover:scale-105">

        <!-- Ícono de colegio -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
            class="w-7 h-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8.5 4.5v9L12 21l-8.5-4.5v-9L12 3z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12l8.5-4.5M12 12L3.5 7.5M12 12v9" />
        </svg>

        Descargar PDF
    </a>

</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>