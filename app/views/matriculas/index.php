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

                <!-- CONTADORES -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 mb-2">
                    <!-- Total -->
                    <div
                        class="bg-gray-800/60 border border-gray-700 rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                        <span class="text-4xl font-bold text-indigo-400"><?= count($matriculas) ?></span>
                        <div>
                            <p class="text-white font-semibold">Total matrículas</p>
                            <p class="text-xs text-gray-400">
                                <?php if (!empty($_GET['nombre']) || !empty($_GET['rut']) || !empty($_GET['anio']) || !empty($_GET['curso'])): ?>
                                    Resultado del filtro aplicado
                                <?php else: ?>
                                    Sin filtros aplicados
                                <?php endif ?>
                            </p>
                        </div>
                    </div>
                    <!-- Por curso (solo si hay filtro de curso) -->
                    <?php if (!empty($_GET['curso'])): ?>
                        <div
                            class="bg-gray-800/60 border border-indigo-800 rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                            <span class="text-4xl font-bold text-blue-400"><?= count($matriculas) ?></span>
                            <div>
                                <p class="text-white font-semibold">Curso filtrado</p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($_GET['curso']) ?></p>
                            </div>
                        </div>
                    <?php endif ?>
                    <!-- Por año (solo si hay filtro de año) -->
                    <?php if (!empty($_GET['anio'])): ?>
                        <div
                            class="bg-gray-800/60 border border-purple-800 rounded-2xl px-6 py-4 flex items-center gap-4 shadow">
                            <span class="text-4xl font-bold text-purple-400"><?= htmlspecialchars($_GET['anio']) ?></span>
                            <div>
                                <p class="text-white font-semibold">Año escolar</p>
                                <p class="text-xs text-gray-400"><?= count($matriculas) ?> alumnos en este año</p>
                            </div>
                        </div>
                    <?php endif ?>
                </div>

                <!-- BOTÓN CREAR -->
                <div class="mb-6 mt-8 flex justify-end">
                    <a href="index.php?action=matricula_create"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition duration-200">
                        ➕ Nueva Matrícula
                    </a>
                </div>

                <!-- 
                <a href="index.php?action=matricula_numero_lista&curso_id=...&anio_id=..."
    class="inline-flex items-center px-4 py-2 bg-indigo-700 hover:bg-indigo-600 
           text-white font-semibold rounded-lg shadow transition">
    📋 Números de Lista
</a>
--->


                <!-- TABLA  2-->
                <div class="mt-6 bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg">

                    <table class="min-w-full text-sm">

                        <thead class="bg-slate-950 text-slate-400 uppercase text-xs">
                            <tr>
                                <th class="px-5 py-3 text-left">Alumno</th>
                                <th class="px-5 py-3 text-left">Curso</th>
                                <th class="px-5 py-3 text-left">Año</th>
                                <th class="px-5 py-3 text-left">Fecha</th>
                                <th class="px-5 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            <?php foreach ($matriculas as $m):
                                $nombre = $m['alumno_nombre'] ?? $m['nombre_completo'] ?? '';
                                $iniciales = strtoupper(substr($nombre, 0, 2));
                                ?>

                                <tr class="hover:bg-slate-800/50 transition">

                                    <!-- Alumno -->
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">

                                            <div class="w-9 h-9 rounded-xl bg-indigo-500/20 border border-indigo-500/30
                                            flex items-center justify-center text-xs font-bold text-indigo-400">
                                                <?= $iniciales ?>
                                            </div>

                                            <span class="font-semibold text-white">
                                                <?= htmlspecialchars($nombre) ?>
                                            </span>
                                        </div>
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


                                    <!-- ACCIONES -->
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex justify-center gap-2">

                                            <a href="index.php?action=perfil_academico&id=<?= $m['id'] ?>" class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                           bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                           hover:bg-emerald-500/20">
                                                Perfil Académico
                                            </a>

                                            <a href="index.php?action=matricula_edit&id=<?= $m['id'] ?>" class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                           bg-indigo-500/10 text-indigo-400 border border-indigo-500/20
                                           hover:bg-indigo-500/20">
                                                Editar
                                            </a>

                                            <a href="index.php?action=matricula_delete&id=<?= $m['id'] ?>"
                                                onclick="return confirm('¿Eliminar matrícula?')" class="px-3 py-1.5 rounded-lg text-xs font-semibold
                                           bg-rose-500/10 text-rose-400 border border-rose-500/20
                                           hover:bg-rose-500/20">
                                                Eliminar
                                            </a>

                                        </div>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

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