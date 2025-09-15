<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Editar Rol</title>
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
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="index.php?action=dashboard"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Dashboard
                                </a>
                                <a href="index.php?action=roles"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Roles
                                </a>
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
                <h1 class="text-3xl font-bold tracking-tight text-white">Editar Rol</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FORMULARIO EDITAR -->
                <div class="flex items-center justify-center">
                    <div class="bg-gray-700 p-6 rounded-2xl shadow-lg w-full max-w-md">
                        <h2 class="text-2xl font-bold text-white mb-6 text-center">Actualizar Rol</h2>

                        <form method="post" action="index.php?action=updateRole&id=<?= $role['id'] ?>" class="space-y-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-200">Nombre del Rol</label>
                                <input type="text" name="nombre" 
                                    value="<?= htmlspecialchars($role['nombre']) ?>" 
                                    required 
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-4 py-2 
                                    focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <button type="submit" 
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg 
                                transition duration-200 ease-in-out">
                                Actualizar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- VOLVER -->
                <div class="mt-8 flex items-center justify-center">
                    <a href="index.php?action=roles"
                        class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                        ⬅ Volver
                    </a>
                </div>

            </div>
        </main>
    </div>
</body>

</html>
