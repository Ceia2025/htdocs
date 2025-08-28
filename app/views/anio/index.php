<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mantenedor de Años</title>
</head>
<body>
    <h1>Mantenedor de Años</h1>

    <a href="index.php?action=anio_create">➕ Crear Año</a>
    <br><br>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Año</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($anios)): ?>
                <?php foreach ($anios as $anio): ?>
                    <tr>
                        <td><?= htmlspecialchars($anio['id']) ?></td>
                        <td><?= htmlspecialchars($anio['anio']) ?></td>
                        <td><?= htmlspecialchars($anio['descripcion']) ?></td>
                        <td>
                            <a href="index.php?action=anio_edit&id=<?= $anio['id'] ?>">✏️ Editar</a> |
                            <a href="index.php?action=anio_delete&id=<?= $anio['id'] ?>" onclick="return confirm('¿Seguro que quieres eliminar este año?');">🗑 Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No hay años registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php?action=dashboard">⬅ Dashboard</a>
</body>
</html>
