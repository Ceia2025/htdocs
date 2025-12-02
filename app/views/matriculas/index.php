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

<html class="h-full bg-gray-900">

<body class="h-full">
    <div class="min-h-full">

        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Mantenedor de Matrículas</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- 🔍 FORMULARIO DE BÚSQUEDA -->
                <form method="GET" action="index.php" class="bg-gray-800/60 backdrop-blur-md border border-gray-700 rounded-2xl p-6 
             grid grid-cols-1 md:grid-cols-6 gap-4 shadow-xl">

                    <input type="hidden" name="action" value="matriculas">

                    <!-- Nombre -->
                    <input type="text" name="nombre" placeholder="Nombre Alumno"
                        value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>" class="rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3
               focus:ring-2 focus:ring-indigo-500 transition">

                    <!-- RUT -->
                    <input type="text" name="rut" placeholder="RUT" value="<?= htmlspecialchars($_GET['rut'] ?? '') ?>"
                        class="rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3
               focus:ring-2 focus:ring-indigo-500 transition">

                    <!-- Año -->
                    <select name="anio" class="rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3
               focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">Año</option>
                        <?php foreach ($anios as $a): ?>
                            <option value="<?= $a['anio'] ?>" <?= ($_GET['anio'] ?? '') == $a['anio'] ? 'selected' : '' ?>>
                                <?= $a['anio'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>

                    <!-- Curso -->
                    <select name="curso" class="rounded-xl bg-gray-900/60 border border-gray-700 text-gray-100 px-4 py-3
               focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">Curso</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['nombre'] ?>" <?= ($_GET['curso'] ?? '') == $c['nombre'] ? 'selected' : '' ?>>
                                <?= $c['nombre'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>

                    <!-- Botón Buscar -->
                    <button type="submit" class="bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700
               text-white font-semibold rounded-xl shadow-lg px-4 py-3 transition">
                        Buscar
                    </button>

                    <!-- Botón Limpiar -->
                    <a href="index.php?action=matriculas" class="text-center bg-gray-700 hover:bg-gray-600 text-white font-semibold 
               rounded-xl shadow px-4 py-3 transition">
                        Limpiar
                    </a>
                </form>



                <!-- BOTÓN CREAR -->
                <div class="mb-6 mt-8 flex justify-end">
                    <a href="index.php?action=matricula_create"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition duration-200">
                        ➕ Nueva Matrícula
                    </a>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-950/50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Alumno</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Curso</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Año</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Fecha Matrícula</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-500/30 divide-y divide-gray-600">
                            <?php if (!empty($matriculas)): ?>
                                <?php foreach ($matriculas as $m): ?>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-100">
                                            <?= htmlspecialchars($m['alumno_nombre'] ?? $m['nombre_completo'] ?? '') ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-100">
                                            <?= htmlspecialchars($m['curso_nombre'] ?? $m['curso'] ?? '') ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-100">
                                            <?= htmlspecialchars($m['anio_escolar'] ?? $m['anio'] ?? '') ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-100">
                                            <?= htmlspecialchars($m['fecha_matricula']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-100 space-x-3">
                                            <a href="index.php?action=perfil_academico&id=<?= $m['id'] ?>"
                                                class="text-green-400 hover:text-green-300 font-medium">Ver Perfil</a>
                                            <a href="index.php?action=matricula_edit&id=<?= $m['id'] ?>"
                                                class="text-indigo-400 hover:text-indigo-300 font-medium">Editar</a>
                                            <a href="index.php?action=matricula_delete&id=<?= $m['id'] ?>"
                                                onclick="return confirm('¿Seguro que deseas eliminar esta matrícula?');"
                                                class="text-red-400 hover:text-red-300 font-medium">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-300">No hay matrículas
                                        registradas.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- VOLVER -->
                <div class="mt-8 flex items-center justify-center">
                    <a href="index.php?action=dashboard"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        ⬅ Dashboard
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>