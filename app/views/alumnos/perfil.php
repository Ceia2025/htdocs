<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

// Calcular edad
$edad = null;
if (!empty($alumno['fechanac'])) {
    $fechaNac = new DateTime($alumno['fechanac']);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNac)->y;
}

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
                    <p><strong>Edad:</strong> <?= $edad !== null ? "$edad años" : "No registrada" ?></p>
                    <p><strong>Sexo:</strong> <?= htmlspecialchars($alumno['sexo']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($alumno['email']) ?></p>
                    <p><strong>Teléfono:</strong> <?= htmlspecialchars($alumno['telefono']) ?></p>
                </div>
                <div class="bg-gray-700 p-6 rounded-xl space-y-2">
                    <p><strong>Nacionalidad:</strong> <?= htmlspecialchars($alumno['nacionalidades']) ?></p>
                    <p><strong>Región:</strong> <?= htmlspecialchars($alumno['region']) ?></p>
                    <p><strong>Ciudad:</strong> <?= htmlspecialchars($alumno['ciudad']) ?></p>
                    <p><strong>Dirección:</strong> <?= htmlspecialchars($alumno['direccion']) ?></p>
                    <p><strong>Etnia:</strong> <?= htmlspecialchars($alumno['cod_etnia']) ?></p>
                </div>
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
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <?php foreach ($contactos as $c): ?>
                                <tr class="hover:bg-gray-600">
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['nombre_contacto']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['telefono']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['direccion']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($c['relacion']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-400 italic">No hay contactos de emergencia registrados.</p>
            <?php endif; ?>
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
        </section>

        <!-- 🔙 BOTONES -->
        <div class="flex justify-center mt-8 gap-4">
            <a href="index.php?action=alumnos"
                class="btn bg-indigo-600 hover:bg-indigo-700 px-6 py-2 rounded-lg font-semibold transition">⬅️
                Volver</a>
            <a href="index.php?action=alumno_edit&id=<?= $alumno['id'] ?>"
                class="btn bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold transition">✏️ Editar</a>
        </div>

    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>