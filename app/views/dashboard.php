<?php
require_once __DIR__ . "/../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user']; // usuario de sesión
$rol = $user['rol'];
$nombre = $user['nombre'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Dashboard</title>
</head>

<html class="h-full bg-gray-900">

<body class="h-full">
    <div class="min-h-full">
        <!-- NAVBAR -->
        <nav class="bg-gray-800/50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="shrink-0">
                            <img src="../img/logo.jpg" alt="Your Company" class="size-12" />
                        </div>
                        <!-- Menú principal -->
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="#"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Dashboard</a>
                                <a href="#"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Equipos</a>
                                <a href="#"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Proyectos</a>
                                <a href="#"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Calendario</a>
                                <a href="#"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Reportes</a>
                            </div>
                        </div>
                    </div>
                    <!-- Usuario / Perfil -->
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <button type="button"
                                class="relative rounded-full p-1 text-gray-400 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
                                <span class="sr-only">Notificaciones</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    class="size-6">
                                    <path
                                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <div class="ml-3">
                                <span class="text-white font-semibold"><?= htmlspecialchars($nombre) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- HEADER -->
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
                            <div
                                class="relative w-[320px] rounded-3xl bg-indigo-100 p-8 shadow-2xl ring-1 ring-gray-900/10 sm:p-10">
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
    </div>
</body>

</html>
