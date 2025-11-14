<!-- NAVBAR -->
<nav class="bg-gray-900/70 backdrop-blur-md shadow-lg border-b border-gray-700/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between w-full">

            <!-- LOGO + BOTÓN HAMBURGUESA -->
            <div class="flex items-center gap-3">
                <img src="img/logo.jpg" alt="Logo CEIA" class="size-12 rounded-full shadow-md" />

                <!-- Botón hamburguesa (solo móvil) -->
                <button id="menu-toggle"
                    class="md:hidden inline-flex items-center justify-center p-3 rounded-lg text-gray-200 hover:text-white hover:bg-gray-700/60 transition ml-auto">

                    <svg id="menu-icon" class="size-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.2">
                        <!-- Icono hamburguesa -->
                        <path id="icon-open" stroke-linecap="round" stroke-linejoin="round"
                            d="M4 7h16M4 12h16M4 17h16" />

                        <!-- Icono X -->
                        <path id="icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- MENU DESKTOP -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="index.php?action=dashboard"
                    class="text-gray-300 hover:text-white hover:bg-purple-600/20 px-3 py-2 rounded-lg transition">Dashboard</a>

                <a href="#"
                    class="text-gray-300 hover:text-white hover:bg-purple-600/20 px-3 py-2 rounded-lg transition">Equipos</a>

                <a href="#"
                    class="text-gray-300 hover:text-white hover:bg-purple-600/20 px-3 py-2 rounded-lg transition">Proyectos</a>

                <a href="#"
                    class="text-gray-300 hover:text-white hover:bg-purple-600/20 px-3 py-2 rounded-lg transition">Calendario</a>

                <a href="#"
                    class="text-gray-300 hover:text-white hover:bg-purple-600/20 px-3 py-2 rounded-lg transition">caca
                    de perro</a>
            </div>

            <!-- USUARIO / DESKTOP -->
            <div class="hidden md:flex items-center space-x-3">
                <button type="button"
                    class="p-1 text-gray-300 hover:text-white hover:bg-purple-600/20 rounded-full transition">
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

    <!-- MENÚ MÓVIL ANIMADO -->
    <div id="mobile-menu"
        class="md:hidden hidden opacity-0 transform -translate-y-5 transition-all duration-300 bg-gray-900/95 border-t border-gray-700/40 backdrop-blur-md">

        <div class="px-4 py-4 space-y-3">
            <a href="index.php?action=dashboard"
                class="block px-3 py-2 rounded-md text-gray-200 hover:bg-purple-600/20 transition">Dashboard</a>

            <a href="#" class="block px-3 py-2 rounded-md text-gray-200 hover:bg-purple-600/20 transition">Equipos</a>

            <a href="#" class="block px-3 py-2 rounded-md text-gray-200 hover:bg-purple-600/20 transition">Proyectos</a>

            <a href="#"
                class="block px-3 py-2 rounded-md text-gray-200 hover:bg-purple-600/20 transition">Calendario</a>

            <a href="#" class="block px-3 py-2 rounded-md text-gray-200 hover:bg-purple-600/20 transition">caca de
                perro</a>

            <hr class="border-gray-700">

            <div class="pt-2">
                <p class="text-gray-300 font-semibold capitalize"><?= htmlspecialchars($nombre) ?></p>
                <a href="index.php?action=logout" class="text-red-400 hover:text-red-500 text-sm">Cerrar sesión</a>
            </div>
        </div>

    </div>

</nav>

<!-- ANIMACIÓN JS -->
<script>
    const menuToggle = document.getElementById("menu-toggle");
    const menu = document.getElementById("mobile-menu");

    const iconOpen = document.getElementById("icon-open");
    const iconClose = document.getElementById("icon-close");

    let open = false;

    menuToggle.addEventListener("click", () => {
        open = !open;

        if (open) {
            menu.classList.remove("hidden");
            setTimeout(() => {
                menu.classList.remove("opacity-0", "-translate-y-5");
            }, 10);

            iconOpen.classList.add("hidden");
            iconClose.classList.remove("hidden");
        } else {
            menu.classList.add("opacity-0", "-translate-y-5");
            iconOpen.classList.remove("hidden");
            iconClose.classList.add("hidden");

            setTimeout(() => {
                if (!open) menu.classList.add("hidden");
            }, 300);
        }
    });
</script>