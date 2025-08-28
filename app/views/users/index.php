<h1>Usuarios</h1>
<a href="index.php?action=user_create">➕ Crear nuevo</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Rut</th>
        <th>Username</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Telefono</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['run']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td>
                <?= htmlspecialchars($user['nombre']) ?>
                <?= htmlspecialchars($user['ape_paterno']) ?>
                <?= htmlspecialchars($user['ape_materno']) ?>
            </td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['numero_telefonico']) ?></td>
            <td><?= htmlspecialchars($user['rol']) ?></td>
            <td>
                <a href="index.php?action=user_edit&id=<?= $user['id'] ?>">Editar</a>
                <a href="index.php?action=user_delete&id=<?= $user['id'] ?>"
                    onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>

    
</table>

<a href="index.php?action=dashboard">Regresar</a>