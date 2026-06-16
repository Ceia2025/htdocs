<!-- NAVBAR COMPLETO -->
<nav class="navbar-shell relative z-40 shadow-lg">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between w-full">

            <!-- LOGO HAMBURGUESA -->
            <div class="flex items-center gap-3">
                <img src="img/logo.jpg" alt="Logo CEIA" class="size-12 rounded-full shadow-md ring-2 ring-amarillo/40" />

                <!-- Botón móvil -->
                <button id="menu-toggle"
                    class="icon-btn md:hidden inline-flex items-center justify-center p-3 rounded-lg transition ml-auto">

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
                    class="nav-link px-3 py-2 rounded-lg transition">
                    Inicio
                </a>

                <!-- USUARIOS -->
                <div class="relative">
                    <button class="nav-btn nav-link flex items-center gap-2 px-3 py-2 rounded-lg transition"
                        data-menu="usuarios">
                        Usuarios
                        <svg class="w-5 h-5 opacity-70 transition" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round"
                                stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <div id="menu-usuarios"
                        class="dropdown dropdown-panel hidden absolute left-0 mt-2 w-48 rounded-lg origin-top">
                        <a href="index.php?action=users"
                            class="dropdown-link block px-4 py-2">Lista de usuarios</a>
                        <a href="index.php?action=roles"
                            class="dropdown-link block px-4 py-2">Roles</a>
                    </div>
                </div>

                <!-- CURSOS -->
                <div class="relative">
                    <button class="nav-btn nav-link flex items-center gap-2 px-3 py-2 rounded-lg transition"
                        data-menu="cursos">
                        Cursos
                        <svg class="w-5 h-5 opacity-70 transition" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round"
                                stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <div id="menu-cursos"
                        class="dropdown dropdown-panel hidden absolute left-0 mt-2 w-56 rounded-lg origin-top">
                        <a href="index.php?action=cursos"
                            class="dropdown-link block px-4 py-2">Listado Cursos</a>
                        <a href="index.php?action=asignaturas"
                            class="dropdown-link block px-4 py-2">Asignaturas</a>
                        <a href="index.php?action=curso_asignaturas"
                            class="dropdown-link block px-4 py-2">Cursos y Asignaturas</a>
                    </div>
                </div>

                <!-- INVENTARIO -->
                <div class="relative">
                    <button class="nav-btn nav-link flex items-center gap-2 px-3 py-2 rounded-lg transition"
                        data-menu="inventario">
                        Inventario
                        <svg class="w-5 h-5 opacity-70 transition" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round"
                                stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <div id="menu-inventario"
                        class="dropdown dropdown-panel hidden absolute left-0 mt-2 w-56 rounded-lg origin-top">
                        <a href="index.php?action=inventario_index"
                            class="dropdown-link block px-4 py-2">Inventario</a>
                        <a href="index.php?action=procedencias"
                            class="dropdown-link block px-4 py-2">Procedencia</a>
                        <a href="index.php?action=categorizaciones"
                            class="dropdown-link block px-4 py-2">Individualización</a>
                    </div>
                </div>

                <!-- ALUMNOS -->
                <div class="relative">
                    <button class="nav-btn nav-link flex items-center gap-2 px-3 py-2 rounded-lg transition"
                        data-menu="alumnos">
                        Alumnos
                        <svg class="w-5 h-5 opacity-70 transition" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round"
                                stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <div id="menu-alumnos"
                        class="dropdown dropdown-panel hidden absolute left-0 mt-2 w-56 rounded-lg origin-top">
                        <a href="index.php?action=listado_por_anio"
                            class="dropdown-link block px-4 py-2">Listado alumnos</a>
                        <a href="index.php?action=alumnos"
                            class="dropdown-link block px-4 py-2">Ficha Escolar</a>
                        <a href="index.php?action=alum_emergencia"
                            class="dropdown-link block px-4 py-2">Datos Emergencia</a>
                        <a href="index.php?action=antecedente_escolar"
                            class="dropdown-link block px-4 py-2">Antecedentes Escolares</a>
                        <a href="index.php?action=antecedentefamiliar"
                            class="dropdown-link block px-4 py-2">Antecedentes Familiares</a>
                    </div>
                </div>


                <div class="relative">
                    <a href="https://administrativo2026ceia.vercel.app/#home" target="_blank" rel="noopener noreferrer"
                        class="nav-link px-3 py-2 rounded-lg transition inline-block">Gestión Administrativa</a>
                </div>

            </div>

            <!-- USUARIO -->
            <div class="hidden md:flex items-center space-x-3">

                <!-- Toggle tema claro/oscuro -->
                <button data-theme-toggle class="theme-toggle-btn" title="Cambiar tema" aria-label="Cambiar tema">
                    <svg class="sun-icon dark:hidden" width="18" height="18" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1.5M12 19.5V21M4.22 4.22l1.06 1.06M18.72 18.72l1.06 1.06M3 12h1.5M19.5 12H21M4.22 19.78l1.06-1.06M18.72 5.28l1.06-1.06M12 7.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z" />
                    </svg>
                    <svg class="moon-icon hidden dark:block" width="18" height="18" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                </button>

                <button class="icon-btn p-1 rounded-full transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        class="size-6">
                        <path
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="text-right">
                    <span class="text-strong font-semibold block capitalize"><?= htmlspecialchars($nombre) ?></span>
                    <a href="index.php?action=logout" class="text-red-400 hover:text-red-500 text-sm">Cerrar sesión</a>
                </div>
            </div>

        </div>
    </div>

    <!-- ============ MENÚ MÓVIL ============ -->
    <div id="mobile-menu"
        class="md:hidden hidden opacity-0 transform -translate-y-5 transition-all duration-300 mobile-menu-shell">

        <div class="px-4 py-4 space-y-3">

            <a href="index.php?action=dashboard"
                class="nav-link block px-3 py-2 rounded-md transition">Dashboard</a>

            <!-- USUARIOS -->
            <button class="mobile-toggle nav-link flex justify-between items-center w-full px-3 py-2 rounded-md transition"
                data-target="mobile-users">
                Usuarios
                <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-linecap="round"
                        stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="mobile-users" class="hidden pl-6 space-y-2">
                <a href="index.php?action=users" class="dropdown-link block py-1 px-2 rounded-md">Lista</a>
                <a href="index.php?action=roles" class="dropdown-link block py-1 px-2 rounded-md">Roles</a>
            </div>

            <!-- CURSOS -->
            <button class="mobile-toggle nav-link flex justify-between items-center w-full px-3 py-2 rounded-md transition"
                data-target="mobile-cursos">
                Cursos
                <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-linecap="round"
                        stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="mobile-cursos" class="hidden pl-6 space-y-2">
                <a href="index.php?action=cursos" class="dropdown-link block py-1 px-2 rounded-md">Listado</a>
                <a href="index.php?action=asignaturas" class="dropdown-link block py-1 px-2 rounded-md">Asignaturas</a>
                <a href="index.php?action=curso_asignaturas" class="dropdown-link block py-1 px-2 rounded-md">Curso y Asignatura</a>
            </div>

            <!-- INVENTARIO -->
            <button class="mobile-toggle nav-link flex justify-between items-center w-full px-3 py-2 rounded-md transition"
                data-target="mobile-inventario">
                Inventario
                <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-linecap="round"
                        stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="mobile-inventario" class="hidden pl-6 space-y-2">
                <a href="index.php?action=inventario_index" class="dropdown-link block py-1 px-2 rounded-md">Inventario</a>
                <a href="index.php?action=procedencias" class="dropdown-link block py-1 px-2 rounded-md">Procedencia</a>
                <a href="index.php?action=categorizaciones" class="dropdown-link block py-1 px-2 rounded-md">Individualización</a>
            </div>

            <!-- ALUMNOS -->
            <button class="mobile-toggle nav-link flex justify-between items-center w-full px-3 py-2 rounded-md transition"
                data-target="mobile-alumnos">
                Alumnos
                <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-linecap="round"
                        stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div id="mobile-alumnos" class="hidden pl-6 space-y-2">
                <a href="index.php?action=listado_por_anio" class="dropdown-link block py-1 px-2 rounded-md">Listado</a>
                <a href="index.php?action=alumnos" class="dropdown-link block py-1 px-2 rounded-md">Ficha Escolar</a>
                <a href="index.php?action=alum_emergencia" class="dropdown-link block py-1 px-2 rounded-md">Emergencia</a>
                <a href="index.php?action=antecedente_escolar" class="dropdown-link block py-1 px-2 rounded-md">Escolares</a>
                <a href="index.php?action=antecedentefamiliar" class="dropdown-link block py-1 px-2 rounded-md">Familiares</a>
            </div>

            <a href="https://administrativo2026ceia.vercel.app/#home" target="_blank" rel="noopener noreferrer"
                class="nav-link block px-3 py-2 rounded-md transition">Gestión Administrativa</a>

            <!-- Toggle tema claro/oscuro -->
            <div class="flex items-center justify-between px-3 py-2">
                <span class="text-soft text-sm">Tema</span>
                <button data-theme-toggle class="theme-toggle-btn" title="Cambiar tema" aria-label="Cambiar tema">
                    <svg class="sun-icon dark:hidden" width="18" height="18" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1.5M12 19.5V21M4.22 4.22l1.06 1.06M18.72 18.72l1.06 1.06M3 12h1.5M19.5 12H21M4.22 19.78l1.06-1.06M18.72 5.28l1.06-1.06M12 7.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z" />
                    </svg>
                    <svg class="moon-icon hidden dark:block" width="18" height="18" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>
                </button>
            </div>

            <hr class="divider-soft my-2">

            <!-- Usuario -->
            <div class="pt-2">
                <p class="text-soft font-semibold capitalize"><?= htmlspecialchars($nombre) ?></p>
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


    // TEMA CLARO / OSCURO (persistente vía localStorage)
    document.querySelectorAll("[data-theme-toggle]").forEach(btn => {
        btn.addEventListener("click", () => {
            const isDark = !document.documentElement.classList.contains("dark");
            document.documentElement.classList.toggle("dark", isDark);
            localStorage.setItem("saat-theme", isDark ? "dark" : "light");
        });
    });

});
</script>