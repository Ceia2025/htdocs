<h1>Roles</h1>
<a href="index.php?action=dashboard">⬅ Volver</a>

<h2>Listado</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($roles as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['nombre']) ?></td>
            <td>
                <a href="index.php?action=editRole&id=<?= $r['id'] ?>">Editar</a> |
                <a href="index.php?action=deleteRole&id=<?= $r['id'] ?>" onclick="return confirm('¿Eliminar este rol?')">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Nuevo Rol</h2>
<form method="post" action="index.php?action=createRole">
    <input type="text" name="nombre" placeholder="Nombre del rol" required>
    <button type="submit">Guardar</button>
</form>

<a href="index.php?action=dashboard">Dashboard</a>
