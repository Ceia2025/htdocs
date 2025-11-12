<?php
require_once __DIR__ . "/../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$rol = $user['rol'];
$nombre = $user['nombre'];

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/navbar.php";
?>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 1.5s ease-out forwards;
        opacity: 0;
        /* inicia invisible */
    }
</style>



<header class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 shadow-md">
    <div class="mx-auto max-w-7xl px-6 py-8 text-white">
        <h1 class="text-3xl font-bold tracking-tight">
            👋 Bienvenido <?= htmlspecialchars($nombre) ?>
        </h1>
        <h3 class="text-gray-200 font-medium mt-1">
            Rol: <span class="font-semibold"><?= htmlspecialchars($rol) ?></span>
        </h3>
    </div>
</header>

<main class="bg-gray-900 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php
            $menu = json_decode(file_get_contents("../utils/menu.json"), true);
            $delay = 0;
            if (isset($menu[$rol])) {
                foreach ($menu[$rol] as $item): ?>
                    <div
                        class="animate-fadeIn group relative flex flex-col justify-between h-full p-8 rounded-3xl bg-gradient-to-br from-gray-800 to-gray-850 
            border border-gray-700 hover:border-indigo-500 shadow-lg transition-all duration-300 hover:shadow-indigo-500/20">

                        <div>
                            <h3 class="text-xl font-semibold text-indigo-400 mb-2">
                                <?= htmlspecialchars($item['title']) ?>
                            </h3>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                <?= htmlspecialchars($item['desc']) ?>
                            </p>
                        </div>

                        <div class="mt-6">
                            <a href="<?= htmlspecialchars($item['url']) ?>"
                                class="block text-center w-full py-2.5 rounded-xl font-semibold text-white bg-indigo-600 hover:bg-indigo-500 
                                       focus:outline-none focus:ring-4 focus:ring-indigo-400 transition-all duration-300">
                                Ir
                            </a>
                        </div>
                    </div>
                <?php endforeach;
                $delay += 0.15;
            } else {
                echo "<p class='text-red-500'>No hay menús disponibles para este rol.</p>";
            }
            ?>

        </div>
    </div>
</main>

<?php include __DIR__ . "/layout/footer.php"; ?>