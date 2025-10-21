<?php
require_once __DIR__ . "/../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user']; // usuario de sesión
$rol = $user['rol'];
$nombre = $user['nombre'];


include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/navbar.php";

?>


<header
    class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white capitalize">
            Bienvenido <?= htmlspecialchars($nombre) ?>
        </h1>
        <h3 class="font-bold tracking-tight text-gray-300 capitalize">
            Rol: <?= htmlspecialchars($rol) ?>
        </h3>
    </div>
</header>

<!-- MAIN -->
<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <!-- Grid responsive: 1 col móvil, 2 col tablet, 3 col escritorio -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php
            $menu = json_decode(file_get_contents("../utils/menu.json"), true);

            if (isset($menu[$rol])) {
                foreach ($menu[$rol] as $item): ?>
                    <!-- Tarjeta flex vertical para empujar el botón al fondo -->
                    <div
                        class="flex flex-col justify-between h-full min-h-[280px] rounded-3xl bg-indigo-100 p-8 shadow-2xl ring-1 ring-gray-900/10 transition-transform hover:scale-105 hover:shadow-3xl">

                        <div>
                            <h3 class="text-lg font-semibold text-indigo-800">
                                <?= htmlspecialchars($item['title']) ?>
                            </h3>
                            <p class="mt-4 text-base text-cyan-950 leading-relaxed">
                                <?= htmlspecialchars($item['desc']) ?>
                            </p>
                        </div>

                        <!-- Botón al fondo -->
                        <div class="mt-8">
                            <a href="<?= htmlspecialchars($item['url']) ?>"
                                class="block w-full rounded-md bg-indigo-500 px-4 py-2.5 text-center text-sm font-semibold text-white shadow hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Ir
                            </a>
                        </div>

                    </div>
                <?php endforeach;
            } else {
                echo "<p class='text-red-500'>No hay menús disponibles para este rol.</p>";
            }
            ?>

        </div>
    </div>
</main>


<?php include __DIR__ . "/layout/footer.php"; ?>