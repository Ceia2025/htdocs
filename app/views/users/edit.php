<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Editar Usuario</title>
</head>


<body class="h-screen">

    <div class="h-full isolate bg-gray-900 flex flex-col flex justify-center px-6 py-24 sm:py-32 lg:px-8">
        <div aria-hidden="true"
            class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
            <div style="clip-path:
                polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%,
                52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
                class="relative left-1/2 -z-10 aspect-1155/678 w-144.5 max-w-none -translate-x-1/2
                rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-40rem)] sm:w-288.75">
            </div>
        </div>

        <div class="h-screen flex items-center justify-center px-6">
            <div class="w-full max-w-2xl bg-gray-800/60 backdrop-blur-md rounded-2xl shadow-xl p-10 ">

                
                <!-- Título -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-white">Editar Usuario</h1>
                    <p class="text-gray-400 mt-2">Modifica los datos y actualiza la información del usuario</p>
                </div>

                <!-- Formulario -->
                <form method="post" action="index.php?action=user_update&id=<?= $user['id'] ?>" class="space-y-6">

                    <!-- Usuario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Usuario</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required
                            class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 
          focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Nombre completo -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Nombre</label>
                            <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required
                                class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 
            focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Apellido Paterno</label>
                            <input type="text" name="ape_paterno" value="<?= htmlspecialchars($user['ape_paterno']) ?>"
                                required class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 
            focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Apellido Materno</label>
                            <input type="text" name="ape_materno" value="<?= htmlspecialchars($user['ape_materno']) ?>"
                                required class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 
            focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- RUN y Teléfono -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-200">RUT</label>
                            <input type="text" name="run" value="<?= htmlspecialchars($user['run']) ?>" required class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 
            focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-200">Teléfono</label>
                            <input type="text" name="telefono"
                                value="<?= htmlspecialchars($user['numero_telefonico']) ?>" required class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 
            focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Correo electrónico</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 
          focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nueva contraseña (opcional)</label>
                        <input type="password" name="password" placeholder="••••••••" class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 
          focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <!-- Rol -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Rol</label>
                        <select name="rol_id" required class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2 
          focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">-- Selecciona un rol --</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= $rol['id'] ?>" <?= ($rol['id'] == $user['rol_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($rol['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-between items-center pt-6">
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-500 transition">
                            Actualizar
                        </button>
                        <a href="index.php?action=dashboard"
                            class="ml-4 px-6 py-2.5 rounded-lg bg-gray-700 text-white font-medium hover:bg-gray-600 transition">
                            Regresar
                        </a>
                    </div>

                </form>

            </div>

        </div>


    </div>

</body>

</html>




</html>