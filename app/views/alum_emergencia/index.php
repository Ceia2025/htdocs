<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contactos de Emergencia</title>
</head>
<body>
    <h1>📋 Contactos de Emergencia</h1>
    <a href="index.php?action=alum_emergencia_create">➕ Nuevo contacto</a>
    <br><br>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Alumno</th>
                <th>Nombre Contacto</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Relación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($emergencias)): ?>
                <?php foreach ($emergencias as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['id']) ?></td>
                        <td><?= htmlspecialchars($e['alumno_nombre'] . ' ' . $e['ape_paterno'] . ' ' . $e['ape_materno']) ?></td>
                        <td><?= htmlspecialchars($e['nombre_contacto']) ?></td>
                        <td><?= htmlspecialchars($e['telefono']) ?></td>
                        <td><?= htmlspecialchars($e['direccion']) ?></td>
                        <td><?= htmlspecialchars($e['relacion']) ?></td>
                        <td>
                            <a href="index.php?action=alum_emergencia_edit&id=<?= $e['id'] ?>">✏️ Editar</a> |
                            <a href="index.php?action=alum_emergencia_delete&id=<?= $e['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este contacto?')">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No hay contactos registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="index.php?action=dashboard">⬅️ Volver al Dashboard</a>
</body>
</html>
