<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
require_once __DIR__ . "/../../config/Connection.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

// Conexión a BD para cargar alumnos
$db = new Connection();
$conn = $db->open();
$alumnos = $conn->query("SELECT id, nombre FROM alumnos2 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

// Opciones ENUM
$niveles = [
    'Basica Incompleta','Basica Completa','Media Incompleta','Media Completa',
    'Técnico Incompleta','Técnico Completa','Superior Incompleta','Superior Completa','Desconocido'
];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="h-full">
<div class="min-h-full">
    <!-- HEADER -->
    <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Crear Antecedente Familiar</h1>
        </div>
    </header>

    <!-- MAIN -->
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center">
                <div class="bg-gray-700 p-6 rounded-2xl shadow-lg w-full max-w-lg">
                    <h2 class="text-2xl font-bold text-white mb-6 text-center">Nuevo Registro</h2>

                    <form method="post" action="index.php?action=antecedentefamiliar_store" class="space-y-4">
                        <!-- Alumno -->
                        <div>
                            <label for="alumno_id" class="block text-sm font-medium text-gray-200">Alumno</label>
                            <select name="alumno_id" id="alumno_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                                <option value="">Seleccione un alumno...</option>
                                <?php foreach ($alumnos as $a): ?>
                                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Padre -->
                        <div>
                            <label for="padre" class="block text-sm font-medium text-gray-200">Escolaridad Padre</label>
                            <select name="padre" id="padre" class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                                <?php foreach ($niveles as $nivel): ?>
                                    <option value="<?= $nivel ?>"><?= $nivel ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Nivel ciclo padre -->
                        <div>
                            <label for="nivel_ciclo_p" class="block text-sm font-medium text-gray-200">Nivel o Ciclo Padre</label>
                            <input type="text" name="nivel_ciclo_p" id="nivel_ciclo_p"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                        </div>

                        <!-- Madre -->
                        <div>
                            <label for="madre" class="block text-sm font-medium text-gray-200">Escolaridad Madre</label>
                            <select name="madre" id="madre" class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                                <?php foreach ($niveles as $nivel): ?>
                                    <option value="<?= $nivel ?>"><?= $nivel ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Nivel ciclo madre -->
                        <div>
                            <label for="nivel_ciclo_m" class="block text-sm font-medium text-gray-200">Nivel o Ciclo Madre</label>
                            <input type="text" name="nivel_ciclo_m" id="nivel_ciclo_m"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2">
                        </div>

                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200">
                            Guardar
                        </button>
                    </form>
                </div>
            </div>

            <!-- VOLVER -->
            <div class="mt-8 flex items-center justify-center">
                <a href="index.php?action=antecedentefamiliar"
                    class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                    ⬅ Volver
                </a>
            </div>
        </div>
    </main>
</div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>
