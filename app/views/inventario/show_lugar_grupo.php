<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 text-white">
    <h1 class="text-2xl font-bold mb-6">
        Detalle del lugar: <?= htmlspecialchars($_GET['lugar'] ?? '') ?><br>
        Código general: <?= htmlspecialchars($_GET['codigo_general'] ?? '') ?>
    </h1>

    <div class="overflow-x-auto rounded-lg shadow">
        <table class="w-full border-collapse bg-gray-800 text-left text-sm text-gray-300">
            <thead class="bg-gray-700 text-gray-200">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Nivel Educativo</th>
                    <th class="px-6 py-3">Individualización</th>
                    <th class="px-6 py-3">Categorización</th>
                    <th class="px-6 py-3">Cantidad</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Código Específico</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                        <td class="px-6 py-3"><?= $item['id'] ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($item['nivel_educativo']) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($item['individualizacion']) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($item['categorizacion']) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($item['cantidad']) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($item['estado']) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($item['codigo_especifico']) ?></td>
                        <td class="px-6 py-3 text-center space-x-2">
                            <a href="index.php?action=inventario_edit&id=<?= $item['id'] ?>"
                                class="text-indigo-400 hover:text-indigo-300 font-medium">
                                ✏️ Editar
                            </a>
                            <a href="index.php?action=inventario_delete&id=<?= $item['id'] ?>"
                                onclick="return confirm('¿Seguro que deseas eliminar este registro?');"
                                class="text-red-400 hover:text-red-300 font-medium">
                                🗑️ Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        <a href="index.php?action=inventario_index"
           class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
           ⬅️ Volver al Inventario
        </a>
    </div>
</main>