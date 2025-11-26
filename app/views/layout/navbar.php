<!-- NAVBAR COMPLETO -->
<nav class="relative z-40 bg-gray-900/70 backdrop-blur-md shadow-lg border-b border-gray-700/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between w-full">

            <!-- LOGO HAMBURGUESA -->
            <div class="flex items-center gap-3">
                <img src="img/logo.jpg" alt="Logo CEIA" class="size-12 rounded-full shadow-md" />

                <!-- Botón móvil -->
                <button id="menu-toggle"
                    class="md:hidden inline-flex items-center justify-center p-3 rounded-lg text-gray-200 hover:text-white hover:bg-gray-700/60 transition ml-auto">

                    <svg id="menu-icon" class="size-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.2">
                        <path id="icon-open" stroke-linecap="round" stroke-linejoin="round"
                            d="M4 7h16M4 12h16M4 17h16" />
                        <path id="icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- ======== MENU DESKTOP ======== -->
            <div class="hidden md:flex items-center space-x-6">

                <a href="index.php?action=dashboard"
                    class="text-gray-300 hover:text-white hover:bg-purple-600/20 px-3 py-2 rounded-lg transition">
                    Dashboard
                </a>

                <!-- USUARIOS -->
                <div class="relative">
                    <button class="nav-btn flex items-center gap-2 text-gray-300 hover:text-white hover:bg-purple-600/20 px-3 py-2 rounded-lg transition"
                        data-menu="usuarios">
                        Usuarios
                        <svg class="w-5 h-5 text-gray-400 transition" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round"
                                stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <div id="menu-usuarios"
                        class="dropdown hidden absolute left-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-lg shadow-lg origin-top">
                        <a href="index.php?action=users"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Lista de usuarios</a>
                        <a href="index.php?action=roles"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Roles</a>
                    </div>
                </div>

                <!-- CURSOS -->
                <div class="relative">
                    <button class="nav-btn flex items-center gap-2 text-gray-300 hover:text-white hover:bg-purple-600/20 px-3 py-2 rounded-lg transition"
                        data-menu="cursos">
                        Cursos
                        <svg class="w-5 h-5 text-gray-400 transition" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round"
                                stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <div id="menu-cursos"
                        class="dropdown hidden absolute left-0 mt-2 w-56 bg-gray-800 border border-gray-700 rounded-lg shadow-lg origin-top">
                        <a href="index.php?action=cursos"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Listado Cursos</a>
                        <a href="index.php?action=asignaturas"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Asignaturas</a>
                        <a href="index.php?action=curso_asignaturas"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Cursos y Asignaturas</a>
                    </div>
                </div>

                <!-- INVENTARIO -->
                <div class="relative">
                    <button class="nav-btn flex items-center gap-2 text-gray-300 hover:text-white hover:bg-purple-600/20 px-3 py-2 rounded-lg transition"
                        data-menu="inventario">
                        Inventario
                        <svg class="w-5 h-5 text-gray-400 transition" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round"
                                stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <div id="menu-inventario"
                        class="dropdown hidden absolute left-0 mt-2 w-56 bg-gray-800 border border-gray-700 rounded-lg shadow-lg origin-top">
                        <a href="index.php?action=inventario_index"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Inventario</a>
                        <a href="index.php?action=procedencias"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Procedencia</a>
                        <a href="index.php?action=categorizaciones"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Individualización</a>
                    </div>
                </div>

                <!-- ALUMNOS -->
                <div class="relative">
                    <button class="nav-btn flex items-center gap-2 text-gray-300 hover:text-white hover:bg-purple-600/20 px-3 py-2 rounded-lg transition"
                        data-menu="alumnos">
                        Alumnos
                        <svg class="w-5 h-5 text-gray-400 transition" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round"
                                stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <div id="menu-alumnos"
                        class="dropdown hidden absolute left-0 mt-2 w-56 bg-gray-800 border border-gray-700 rounded-lg shadow-lg origin-top">
                        <a href="index.php?action=alumnos"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Listado alumnos</a>
                        <a href="index.php?action=alum_emergencia"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Datos Emergencia</a>
                        <a href="index.php?action=antecedente_escolar"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Antecedentes Escolares</a>
                        <a href="index.php?action=antecedentefamiliar"
                            class="block px-4 py-2 text-gray-300 hover:bg-purple-600/20 hover:text-white">Antecedentes Familiares</a>
                    </div>
                </div>
            </div>

            <!-- USUARIO -->
            <div class="hidden md:flex items-center space-x-3">
                <button class="p-1 text-gray-300 hover:text-white hover:bg-purple-600/20 rounded-full transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        class="size-6 text-gray-300">
                        <path
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="text-right">
                    <span class="text-white font-semibold block capitalize"><?= htmlspecialchars($nombre) ?></span>
                    <a href="index.php?action=logout" class="text-red-400 hover:text-red-500 text-sm">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MENÚ MÓVIL ============ -->
    <div id="mobile-menu"
        class="md:hidden hidden opacity-0 transform -translate-y-5 transition-all duration-300 bg-gray-900/95 border-t border-gray-700/40 backdrop-blur-md">

        <div class="px-4 py-4 space-y-3">

            <a href="index.php?action=dashboard"
                class="block px-3 py-2 rounded-md text-gray-200 hover:bg-purple-600/20 transition">Dashboard</a>

            <!-- USUARIOS -->
            <button class="mobile-toggle flex justify-between items-center w-full px-3 py-2 rounded-md text-gray-200 hover:bg-purple-600/20 transition"
                data-target="mobile-users">
                Usuarios
                <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-linecap="round"
                        stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="mobile-users" class="hidden pl-6 space-y-2">
                <a href="index.php?action=users" class="block text-gray-300 py-1">Lista</a>
                <a href="index.php?action=roles" class="block text-gray-300 py-1">Roles</a>
            </div>

            <!-- CURSOS -->
            <button class="mobile-toggle flex justify-between items-center w-full px-3 py-2 rounded-md text-gray-200 hover:bg-purple-600/20 transition"
                data-target="mobile-cursos">
                Cursos
                <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-linecap="round"
                        stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="mobile-cursos" class="hidden pl-6 space-y-2">
                <a href="index.php?action=cursos" class="block text-gray-300 py-1">Listado</a>
                <a href="index.php?action=asignaturas" class="block text-gray-300 py-1">Asignaturas</a>
                <a href="index.php?action=curso_asignaturas" class="block text-gray-300 py-1">Curso y Asignatura</a>
            </div>

            <!-- INVENTARIO -->
            <button class="mobile-toggle flex justify-between items-center w-full px-3 py-2 rounded-md text-gray-200 hover:bg-purple-600/20 transition"
                data-target="mobile-inventario">
                Inventario
                <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-linecap="round"
                        stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="mobile-inventario" class="hidden pl-6 space-y-2">
                <a href="index.php?action=inventario_index" class="block text-gray-300 py-1">Inventario</a>
                <a href="index.php?action=procedencias" class="block text-gray-300 py-1">Procedencia</a>
                <a href="index.php?action=categorizaciones" class="block text-gray-300 py-1">Individualización</a>
            </div>

            <!-- ALUMNOS -->
            <button class="mobile-toggle flex justify-between items-center w-full px-3 py-2 rounded-md text-gray-200 hover:bg-purple-600/20 transition"
                data-target="mobile-alumnos">
                Alumnos
                <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-linecap="round"
                        stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="mobile-alumnos" class="hidden pl-6 space-y-2">
                <a href="index.php?action=alumnos" class="block text-gray-300 py-1">Listado</a>
                <a href="index.php?action=alum_emergencia" class="block text-gray-300 py-1">Emergencia</a>
                <a href="index.php?action=antecedente_escolar" class="block text-gray-300 py-1">Escolares</a>
                <a href="index.php?action=antecedentefamiliar" class="block text-gray-300 py-1">Familiares</a>
            </div>

            <hr class="border-gray-700 my-2">

            <!-- Usuario -->
            <div class="pt-2">
                <p class="text-gray-300 font-semibold capitalize"><?= htmlspecialchars($nombre) ?></p>
                <a href="index.php?action=logout" class="text-red-400 hover:text-red-500 text-sm">Cerrar sesión</a>
            </div>
        </div>
    </div>

</nav>

<script>
document.addEventListener("DOMContentLoaded", () => {

    //DROPDOWNS
    let activeMenu = null;
    document.querySelectorAll(".nav-btn").forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            const id = "menu-" + btn.dataset.menu;
            const target = document.getElementById(id);

            if (activeMenu && activeMenu !== target)
                activeMenu.classList.add("hidden");

            target.classList.toggle("hidden");
            activeMenu = target.classList.contains("hidden") ? null : target;
        });
    });

    document.addEventListener("click", () => {
        if (activeMenu) activeMenu.classList.add("hidden");
        activeMenu = null;
    });


    //hamburguesa
    const menuToggle = document.getElementById("menu-toggle");
    const menu = document.getElementById("mobile-menu");
    const iconOpen = document.getElementById("icon-open");
    const iconClose = document.getElementById("icon-close");

    let isOpen = false;

    menuToggle.addEventListener("click", () => {
        isOpen = !isOpen;

        if (isOpen) {
            menu.classList.remove("hidden");
            setTimeout(() => menu.classList.remove("opacity-0", "-translate-y-5"), 10);
            iconOpen.classList.add("hidden");
            iconClose.classList.remove("hidden");
        } else {
            menu.classList.add("opacity-0", "-translate-y-5");
            iconOpen.classList.remove("hidden");
            iconClose.classList.add("hidden");

            setTimeout(() => {
                if (!isOpen) menu.classList.add("hidden");
            }, 300);
        }
    });


    // MOBILE 
    document.querySelectorAll(".mobile-toggle").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.target;
            const box = document.getElementById(id);
            box.classList.toggle("hidden");
        });
    });

});
</script>
