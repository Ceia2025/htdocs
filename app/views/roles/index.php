<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<main class="min-h-screen bg-gray-900">
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Gestión de Roles</h1>
            <p class="mt-2 text-sm text-indigo-400 font-medium italic">Configuración de permisos y niveles de acceso</p>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="sticky top-8 bg-gray-800 p-6 rounded-3xl border border-white/10 shadow-2xl">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-600/20 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Nuevo Rol</h2>
                    </div>

                    <form method="post" action="index.php?action=createRole" class="space-y-5">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Nombre
                                del Rol</label>
                            <input type="text" name="nombre" required placeholder="Ej: Administrador"
                                class="w-full rounded-xl bg-gray-900 border border-gray-700 text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all placeholder:text-gray-600">
                        </div>

                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-indigo-900/20 active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Guardar Rol
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="overflow-hidden bg-gray-800/40 rounded-3xl border border-gray-800 shadow-xl">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-950/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Nombre del Rol</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-800">
                            <?php foreach ($roles as $r): ?>
                                <tr class="hover:bg-gray-700/30 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-indigo-400 border border-white/5">
                                                <?= strtoupper(substr($r['nombre'], 0, 1)) ?>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-200 capitalize">
                                                <?= htmlspecialchars($r['nombre']) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="index.php?action=editRole&id=<?= $r['id'] ?>"
                                            class="inline-flex p-2 bg-gray-900 text-gray-400 hover:text-white hover:bg-indigo-600 rounded-lg transition-all"
                                            title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="index.php?action=deleteRole&id=<?= $r['id'] ?>"
                                            onclick="return confirm('¿Seguro que deseas eliminar este rol?')"
                                            class="inline-flex p-2 bg-red-900/20 text-red-400 hover:bg-red-600 hover:text-white rounded-lg transition-all"
                                            title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (empty($roles)): ?>
                        <div class="py-12 text-center">
                            <p class="text-gray-500 italic text-sm">No hay roles configurados actualmente.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="index.php?action=dashboard"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 text-gray-300 rounded-xl hover:bg-gray-700 hover:text-white transition-all border border-gray-700 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . "/../layout/footer.php"; ?>