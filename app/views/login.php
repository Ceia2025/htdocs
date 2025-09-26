<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Login</title>
</head>

<body class="h-screen">
    <div class="h-full isolate bg-gray-900 flex flex-col justify-center px-6 py-24 sm:py-32 lg:px-8">
        <div aria-hidden="true"
            class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
            <div style="clip-path:
                polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%,
                52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
                class="relative left-1/2 -z-10 aspect-1155/678 w-144.5 max-w-none -translate-x-1/2
                rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-40rem)] sm:w-288.75">
            </div>
        </div>

        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">Login Sistema SAAT</h2>
            <p class="mt-2 text-lg text-gray-400">Recuerde ingresar con su correo y contraseña</p>
        </div>

        <!-- 🚀 Formulario conectado a PHP -->
        <form action="index.php?action=doLogin" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
            <div class="grid grid-cols-1 gap-y-6">

                <!-- Email o Username -->
                <div>
                    <label for="login" class="block text-sm font-semibold text-white">Usuario o Email</label>
                    <div class="mt-2.5">
                        <input id="login" type="text" name="login" required class="block w-[330px] rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1
                            -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2
                            focus:-outline-offset-2 focus:outline-indigo-500" />
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-semibold text-white">Contraseña</label>
                        <div class="text-sm">
                            <a href="#" class="font-semibold text-indigo-400 hover:text-indigo-300">¿Olvidó la
                                contraseña?</a>
                        </div>
                    </div>
                    <div class="mt-2.5">
                        <input id="password" type="password" name="password" required class="block w-[330px] rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1
                            -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2
                            focus:-outline-offset-2 focus:outline-indigo-500" />
                    </div>
                </div>

            </div>

            <!-- Botón -->
            <div class="mt-10">
                <button type="submit" class="block w-[330px] rounded-md bg-indigo-500 px-3.5 py-2.5 text-center text-sm
                    font-semibold text-white shadow-xs hover:bg-indigo-400 focus-visible:outline-2
                    focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                    Ingresar
                </button>
            </div>

            <!-- Mostrar error de PHP si existe -->
            <?php if (!empty($error)): ?>
                <p class="mt-4 text-red-500 text-center"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>