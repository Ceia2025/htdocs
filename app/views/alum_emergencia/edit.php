<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth(); // obliga a tener sesión iniciada

$user = $_SESSION['user']; // usuario logueado
$nombre = $user['nombre'];
$rol = $user['rol'];

// Incluir layout
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<body class="h-full bg-gray-900">

    <div class="min-h-full">



        <!-- HEADER -->
        <header
            class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Editar Contacto de Emergencia</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

                <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
                    <form method="POST" action="index.php?action=alum_emergencia_update&id=<?= $emergencia['id'] ?>"
                        class="space-y-6">
                        <!-- Mantener retorno -->
                        <input type="hidden" name="back" value="<?= htmlspecialchars($back ?? 'alum_emergencia') ?>">
                        <input type="hidden" name="alumno_id"
                            value="<?= htmlspecialchars($alumno_id ?? $emergencia['alumno_id']) ?>">

                        <!-- Alumno -->
                        <div>
                            <label for="alumno_id" class="block text-sm font-medium text-gray-200">Alumno</label>
                            <select name="alumno_id" id="alumno_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <?php foreach ($alumnos as $a): ?>
                                    <option value="<?= $a['id'] ?>" <?= ($a['id'] == $emergencia['alumno_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($a['nombre'] . " " . $a['apepat'] . " " . $a['apemat']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Nombre del contacto -->
                        <div>
                            <label for="nombre_contacto" class="block text-sm font-medium text-gray-200">Nombre del
                                contacto</label>
                            <input type="text" name="nombre_contacto" id="nombre_contacto" required
                                value="<?= htmlspecialchars($emergencia['nombre_contacto']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-200">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" required
                                value="<?= htmlspecialchars($emergencia['telefono']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-200">Dirección</label>
                            <input type="text" name="direccion" id="direccion"
                                value="<?= htmlspecialchars($emergencia['direccion']) ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Relación -->
                        <div>
                            <label for="relacion" class="block text-sm font-medium text-gray-200">Relación</label>
                            <select name="relacion" id="relacion" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <?php
                                $opciones = ['Madre', 'Padre', 'Relación directa', 'Tutor Legal', 'Representante', 'Apoderado'];
                                foreach ($opciones as $op): ?>
                                    <option value="<?= $op ?>" <?= ($op == $emergencia['relacion']) ? 'selected' : '' ?>>
                                        <?= $op ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
                                Actualizar
                            </button>

                            <?php
                            // construir href de cancelación respetando back
                            $cancelHref = "index.php?action=alum_emergencia";
                            if (!empty($back) && $back === 'alumno_profile' && !empty($alumno_id)) {
                                $cancelHref = "index.php?action=alumno_profile&id=" . urlencode($alumno_id);
                            }
                            ?>
                            <a href="<?= $cancelHref ?>"
                                class="flex-1 text-center inline-block rounded-lg bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 transition">
                                Cancelar
                            </a>
                        </div>

                    </form>
                </div>

            </div>
        </main>

    </div>

</body>
<?php include __DIR__ . "/../layout/footer.php"; ?>