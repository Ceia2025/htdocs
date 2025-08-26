<h1>Usuarios</h1>
<a href="index.php?action=user_create">➕ Crear nuevo</a>
<table border="1">
    <tr>
        <th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th>
    </tr>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['nombre'] ?></td>
            <td><?= $u['email'] ?></td>
            <td><?= $u['rol_id'] ?></td>
            <td>
                <a href="index.php?action=user_edit&id=<?= $u['id'] ?>">Editar</a>
                <a href="index.php?action=user_delete&id=<?= $u['id'] ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
