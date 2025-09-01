<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Relaciones Curso - Asignatura</title>
</head>
<body>
    <h1>Relaciones Curso - Asignatura</h1>

    <a href="index.php?action=curso_asignaturas_create">➕ Nueva relación</a>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Curso</th>
                <th>Asignatura</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cursoAsignaturas as $ca): ?>
                <tr>
                    <td><?= htmlspecialchars($ca['id']) ?></td>
                    <td><?= htmlspecialchars($ca['curso']) ?></td>
                    <td><?= htmlspecialchars($ca['asignatura']) ?></td>
                    <td>
                        <a href="index.php?action=curso_asignaturas_edit&id=<?= $ca['id'] ?>">✏️ Editar</a> |
                        <a href="index.php?action=curso_asignaturas_delete&id=<?= $ca['id'] ?>"
                           onclick="return confirm('¿Estás seguro de eliminar esta relación?');">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php?action=dashboard">⬅ Volver</a>


</body>
</html>
