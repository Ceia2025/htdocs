<h1>Editar Rol</h1>
<a href="index.php?action=roles">⬅ Volver</a>


<form method="post" action="index.php?action=updateRole&id=<?= $role['id'] ?>">
    <input type="text" name="nombre" value="<?= htmlspecialchars($role['nombre']) ?>" required>
    <button type="submit">Actualizar</button>
</form>