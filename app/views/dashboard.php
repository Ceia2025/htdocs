<?php
require_once __DIR__ . "/../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$rol = $user['rol'];
$nombre = $user['nombre'];

include __DIR__ . "/layout/header.php";
?>
<?php if (!empty($_SESSION['error_acceso'])): ?>
    <div id="toast-acceso" class="toast-shell fixed top-6 right-6 z-50 flex items-center gap-3 border border-red-500/50 text-red-400
           rounded-2xl px-5 py-4 shadow-2xl shadow-red-900/30 transition-all duration-500 max-w-sm">
        <span class="text-2xl">🚫</span>
        <p class="text-sm font-medium"><?= htmlspecialchars($_SESSION['error_acceso']) ?></p>
        <button onclick="cerrarToast()" class="ml-auto text-faint hover:text-strong text-lg leading-none">✕</button>
    </div>
    <script>
        // Desaparece automáticamente a los 4 segundos
        setTimeout(() => cerrarToast(), 4000);

        function cerrarToast() {
            const t = document.getElementById('toast-acceso');
            if (!t) return;
            t.style.opacity = '0';
            t.style.transform = 'translateY(-10px)';
            setTimeout(() => t.remove(), 400);
        }
    </script>
    <?php unset($_SESSION['error_acceso']); ?>
<?php endif ?>

<?php
include __DIR__ . "/layout/navbar.php";
?>

<header class="dashboard-header shadow-md">
    <div class="mx-auto max-w-7xl px-6 py-8 text-white relative z-10">
        <h1 class="text-3xl font-bold tracking-tight font-display">
            👋 Bienvenido <?= htmlspecialchars($nombre) ?>
        </h1>
        <h3 class="text-blue-100/90 font-medium mt-1">
            Rol: <span class="font-semibold"><?= htmlspecialchars($rol) ?></span>
        </h3>
    </div>
</header>

<main class="min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">

            <?php
            // Asigna un ícono según palabras clave del título, sin depender
            // de que menu.json tenga un campo de ícono propio.
            function saat_card_icon_key(string $title): string
            {
                $t = mb_strtolower($title);
                if (str_contains($t, 'reglamento')) return 'doc';
                if (str_contains($t, 'matrícula') || str_contains($t, 'matricula') || str_contains($t, 'ficha')) return 'user';
                if (str_contains($t, 'asistencia') || str_contains($t, 'atraso')) return 'clock';
                if (str_contains($t, 'docente') || str_contains($t, 'profesor')) return 'cap';
                if (str_contains($t, 'curso') || str_contains($t, 'asignatura') || str_contains($t, 'plan de estudio') || str_contains($t, 'carga académica') || str_contains($t, 'carga academica') || str_contains($t, 'año académico') || str_contains($t, 'ano academico')) return 'book';
                if (str_contains($t, 'nota')) return 'star';
                if (str_contains($t, 'retiro')) return 'exit';
                if (str_contains($t, 'reporte') || str_contains($t, 'anotacion') || str_contains($t, 'anotación')) return 'chart';
                return 'grid';
            }

            $saat_icons = [
                'doc'   => '<rect x="6" y="3" width="12" height="18" rx="1.5"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/>',
                'user'  => '<circle cx="12" cy="8" r="3.2"/><path d="M5.5 19c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/>',
                'clock' => '<circle cx="12" cy="12" r="8"/><line x1="12" y1="7" x2="12" y2="12"/><line x1="12" y1="12" x2="16" y2="14"/>',
                'cap'   => '<polygon points="12,4 21,9 12,14 3,9"/><line x1="7" y1="11" x2="7" y2="15"/><line x1="17" y1="11" x2="17" y2="15"/><line x1="7" y1="15" x2="17" y2="15"/>',
                'book'  => '<rect x="4" y="4" width="16" height="16" rx="1.5"/><line x1="12" y1="4" x2="12" y2="20"/><line x1="7" y1="8" x2="9" y2="8"/><line x1="15" y1="8" x2="17" y2="8"/>',
                'star'  => '<polygon points="12,3.5 14.6,9 20.5,9.8 16.2,13.9 17.3,19.7 12,16.8 6.7,19.7 7.8,13.9 3.5,9.8 9.4,9"/>',
                'exit'  => '<path d="M9 4H6.5A2.5 2.5 0 0 0 4 6.5v11A2.5 2.5 0 0 0 6.5 20H9"/><line x1="20" y1="12" x2="11" y2="12"/><polyline points="16,7 21,12 16,17"/>',
                'chart' => '<line x1="5" y1="20" x2="5" y2="11"/><line x1="12" y1="20" x2="12" y2="6"/><line x1="19" y1="20" x2="19" y2="14"/><line x1="3" y1="20" x2="21" y2="20"/>',
                'grid'  => '<rect x="4" y="4" width="7" height="7" rx="1.2"/><rect x="13" y="4" width="7" height="7" rx="1.2"/><rect x="4" y="13" width="7" height="7" rx="1.2"/><rect x="13" y="13" width="7" height="7" rx="1.2"/>',
            ];

            $menu = json_decode(file_get_contents("../utils/menu.json"), true);
            $delay = 0;
            if (isset($menu[$rol])) {
                foreach ($menu[$rol] as $item):
                    $iconKey = saat_card_icon_key($item['title']);
                    ?>
                    <div style="animation-delay: <?= $delay ?>s"
                        class="dashboard-card animate-fadeIn group relative flex flex-col h-full p-7 rounded-2xl shadow-lg transition-all duration-300">

                        <div class="card-icon-badge mb-4">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <?= $saat_icons[$iconKey] ?>
                            </svg>
                        </div>

                        <h3 class="dashboard-card-title text-lg font-semibold mb-1.5 font-display">
                            <?= htmlspecialchars($item['title']) ?>
                        </h3>
                        <p class="dashboard-card-desc text-sm leading-relaxed flex-1">
                            <?= htmlspecialchars($item['desc']) ?>
                        </p>

                        <a href="<?= htmlspecialchars($item['url']) ?>"
                            class="card-cta mt-5 inline-flex items-center gap-1.5 text-sm font-semibold self-start">
                            Ir
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12" />
                                <polyline points="13,6 19,12 13,18" />
                            </svg>
                        </a>
                    </div>
                    <?php $delay += 0.12;
                endforeach;
            } else {
                echo "<p class='text-red-500'>No hay menús disponibles para este rol.</p>";
            }
            ?>

        </div>
    </div>
</main>

<?php include __DIR__ . "/layout/footer.php"; ?>