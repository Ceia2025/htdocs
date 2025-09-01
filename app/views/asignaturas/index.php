<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Asignaturas</title>
</head>
<body>
    <h1>Asignaturas</h1>
    <a href="index.php?action=asignatura_create">➕ Crear Asignatura</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de la Asignatura</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($asignaturas)): ?>
                <?php foreach ($asignaturas as $asignatura): ?>
                    <tr>
                        <td><?= htmlspecialchars($asignatura['id']) ?></td>
                        <td><?= htmlspecialchars($asignatura['nombre']) ?></td>
                        <td><?= htmlspecialchars($asignatura['descp']) ?></td>
                        <td>
                            <a href="index.php?action=asignatura_edit&id=<?= $asignatura['id'] ?>">✏️ Editar</a>
                            <a href="index.php?action=asignatura_delete&id=<?= $asignatura['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar esta asignatura?')">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No hay asignaturas registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>
    <a href="index.php?action=dashboard">⬅️ Volver al Dashboard</a>
</body>
</html>
