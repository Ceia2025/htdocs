<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>➕ Crear Alumno</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="h-full bg-gray-900">

    <div class="min-h-full">

        <!-- NAVBAR -->
        <nav class="bg-gray-800/50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <img src="../img/logo.jpg" alt="Logo" class="size-12 rounded-full" />
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="index.php?action=dashboard"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Dashboard
                                </a>
                                <a href="index.php?action=alumnos"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-white bg-gray-700">
                                    Alumnos
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
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">Crear Alumno</h1>
            </div>
        </header>

        <!-- MAIN -->
        <main>
            <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

                <!-- FORM -->
                <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
                    <form action="index.php?action=alumno_store" method="POST" class="space-y-6">

                        <!-- Grid de 2 columnas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- RUN -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">RUN</label>
                                <input type="text" name="run" required
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Codigo Verificador -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Código Verificador</label>
                                <input type="text" name="codver" required
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Nombre</label>
                                <input type="text" name="nombre" required
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Apellido Paterno -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Apellido Paterno</label>
                                <input type="text" name="apepat" required
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Apellido Materno -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Apellido Materno</label>
                                <input type="text" name="apemat"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Fecha de Nacimiento</label>
                                <input type="date" name="fechanac"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Número de Hijos -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Número de Hijos</label>
                                <input type="number" name="numerohijos" min="0"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Teléfono</label>
                                <input type="text" name="telefono"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Email</label>
                                <input type="email" name="email"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- Sexo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Sexo</label>
                                <select name="sexo" required
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                                    <option value="F">Femenino</option>
                                    <option value="M">Masculino</option>
                                </select>
                            </div>

                            <!-- Nacionalidad -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Nacionalidad</label>
                                <input type="text" name="nacionalidades"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <!-- 
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Región</label>
                                <input type="text" name="region"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-200">Ciudad</label>
                                <input type="text" name="ciudad"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                            </div>
                            -->





                            <!-- Región -->
                            <div>
                                <label class="block text-sm font-medium text-gray-200">Región</label>
                                <select id="region" name="region"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                                    <option value="">Seleccione una región</option>
                                </select>
                            </div>

                            <!-- Ciudad/Comuna -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-200">Ciudad / Comuna</label>
                                <select id="ciudad" name="ciudad"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                                    <option value="">Seleccione una ciudad</option>
                                </select>
                            </div>






                            <!-- Etnia -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-200">Etnia</label>
                                <select name="cod_etnia"
                                    class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-indigo-500 focus:outline-none">
                                    <option value="Ninguna">Ninguna</option>
                                    <option value="Mapuche">Mapuche</option>
                                    <option value="Aymara">Aymara</option>
                                    <option value="Rapa Nui">Rapa Nui</option>
                                    <option value="Lickan Antai (Atacameños)">Lickan Antai (Atacameños)</option>
                                    <option value="Quechua">Quechua</option>
                                    <option value="Colla">Colla</option>
                                    <option value="Diaguita">Diaguita</option>
                                    <option value="Chango">Chango</option>
                                    <option value="Kawésqar">Kawésqar</option>
                                    <option value="Yagán">Yagán</option>
                                    <option value="Selk nam">Selk nam</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex space-x-4 pt-4">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                                Guardar
                            </button>
                            <a href="index.php?action=alumnos"
                                class="w-full text-center inline-block rounded-lg bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 transition duration-200 ease-in-out">
                                Cancelar
                            </a>
                        </div>

                    </form>
                </div>

            </div>
        </main>

    </div>

</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const regionSelect = document.getElementById("region");
        const ciudadSelect = document.getElementById("ciudad");

        // Cargar JSON desde carpeta utils
        fetch("../utils/comunas-regiones.json")
            .then(response => response.json())
            .then(data => {
                // Llenar el select de regiones
                data.regiones.forEach(r => {
                    const option = document.createElement("option");
                    option.value = r.region;
                    option.textContent = r.region;
                    regionSelect.appendChild(option);
                });

                // Cuando cambie la región, cargar comunas
                regionSelect.addEventListener("change", () => {
                    ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>'; // limpiar
                    const selectedRegion = regionSelect.value;
                    const region = data.regiones.find(r => r.region === selectedRegion);
                    if (region) {
                        region.comunas.forEach(c => {
                            const option = document.createElement("option");
                            option.value = c;
                            option.textContent = c;
                            ciudadSelect.appendChild(option);
                        });
                    }
                });
            })
            .catch(err => console.error("Error cargando comunas-regiones.json:", err));
    });
</script>


</html>