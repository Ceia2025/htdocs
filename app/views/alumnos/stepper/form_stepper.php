<?php
require_once __DIR__ . "/../../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user   = $_SESSION['user'];
$nombre = $user['nombre'];
$rol    = $user['rol'];

//-----------------------------------------
// Conexión a BD
$db   = new Connection();
$conn = $db->open();

$alumnos = $conn->query("SELECT id, nombre FROM alumnos2 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$cursos  = $conn->query("SELECT id, nombre FROM cursos2 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$anios   = $conn->query("SELECT id, anio FROM anios2 ORDER BY anio DESC")->fetchAll(PDO::FETCH_ASSOC);

$niveles = [
    'Basica Incompleta', 'Basica Completa',
    'Media Incompleta',  'Media Completa',
    'Técnico Incompleta','Técnico Completa',
    'Superior Incompleta','Superior Completa',
    'Desconocido',
];
//-----------------------------------------

include __DIR__ . "/../../layout/header.php";
include __DIR__ . "/../../layout/navbar.php";
?>

<header class="relative bg-gradient-to-r from-gray-900 to-gray-800 shadow-lg border-b border-gray-700">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white drop-shadow">
            Registro Completo de Alumno
        </h1>
        <p class="text-gray-300 mt-2">Ingresa todos los datos requeridos en cada paso.</p>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 text-white">
    <div class="bg-gray-900/80 backdrop-blur-xl p-10 rounded-3xl shadow-2xl border border-gray-700/50 ring-1 ring-gray-600/20">

        <!-- BARRA DE PROGRESO -->
        <div class="mb-10">
            <div class="flex justify-between text-sm font-semibold text-gray-300 mb-3">
                <span>Paso 1</span>
                <span>Paso 2</span>
                <span>Paso 3</span>
                <span>Paso 4</span>
                <span>Paso 5</span>
            </div>
            <div class="relative w-full h-3 bg-gray-700/60 rounded-full overflow-hidden shadow-inner">
                <div id="progress-bar"
                    class="absolute left-0 top-0 h-full bg-gradient-to-r from-indigo-500 to-blue-500 shadow-md transition-all duration-700 ease-out"
                    style="width: 20%;"></div>
            </div>
        </div>

        <form method="POST" action="index.php?action=alumnos_store_stepper" id="stepperForm" class="space-y-10">

            <?php include __DIR__ . '/_paso1_datos.php';    ?>
            <?php include __DIR__ . '/_paso2_contactos.php'; ?>
            <?php include __DIR__ . '/_paso3_familiar.php'; ?>
            <?php include __DIR__ . '/_paso4_escolar.php';  ?>
            <?php include __DIR__ . '/_paso5_matricula.php'; ?>

        </form>

        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=dashboard"
                class="inline-block px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg shadow transition">
                Volver
            </a>
        </div>
    </div>
</main>

<style>
    .step:not(.hidden) { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<?php include __DIR__ . '/_scripts.php'; ?>
<?php include __DIR__ . "/../../layout/footer.php"; ?>