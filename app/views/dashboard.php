<?php
require_once __DIR__ . "/../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user']; // usuario de sesión
$rol = $user['rol'];
$nombre = $user['nombre'];


include __DIR__ ."/layout/header.php";
include __DIR__ ."/layout/navbar.php";

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
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-8">
            <?php
            // Cargar el archivo JSON
            $menu = json_decode(file_get_contents("../utils/menu.json"), true);
            
            // Usar el rol del usuario logueado
            if (isset($menu[$rol])) {
                foreach ($menu[$rol] as $item): ?>
                    <div class="relative w-[320px] rounded-3xl bg-indigo-100 p-8 shadow-2xl ring-1 ring-gray-900/10 sm:p-10">
                        <h3 class="text-base/7 font-semibold text-indigo-800">
                            <?= htmlspecialchars($item['title']) ?>
                        </h3>
                        <p class="mt-6 text-base/7 text-cyan-950">
                            <?= htmlspecialchars($item['desc']) ?>
                        </p>
                        <a href="<?= htmlspecialchars($item['url']) ?>"
                        class="mt-8 block rounded-md bg-indigo-500 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs
                        hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 sm:mt-10">
                        Ir
                    </a>
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