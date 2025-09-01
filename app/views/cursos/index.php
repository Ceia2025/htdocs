<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Cursos</title>
</head>
<body>
    <h1>Cursos</h1>
    <a href="index.php?action=curso_create">➕ Crear curso</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Curso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cursos)): ?>
                <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td><?= htmlspecialchars($curso['id']) ?></td>
                        <td><?= htmlspecialchars($curso['nombre']) ?></td>
                        <td>
                            <a href="index.php?action=curso_edit&id=<?= $curso['id'] ?>">✏️ Editar</a>
                            <a href="index.php?action=curso_delete&id=<?= $curso['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este curso?')">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No hay cursos registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>
    <a href="index.php?action=dashboard">⬅️ Volver al Dashboard</a>
</body>
</html>
