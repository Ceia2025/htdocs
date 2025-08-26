<h1>Editar Usuario</h1>
<form method="post" action="index.php?action=user_update&id=<?= $user['id'] ?>">
    <input type="text" name="nombre" value="<?= $user['nombre'] ?>" required><br>
    <input type="email" name="email" value="<?= $user['email'] ?>" required><br>
    <input type="password" name="password" placeholder="Nueva contraseña (opcional)"><br>
    <select name="rol">
        <option value="admin" <?= $user['rol']=="admin"?"selected":"" ?>>Admin</option>
        <option value="docente" <?= $user['rol']=="docente"?"selected":"" ?>>Docente</option>
        <option value="registro" <?= $user['rol']=="registro"?"selected":"" ?>>Registro</option>
        <option value="asistencia" <?= $user['rol']=="asistencia"?"selected":"" ?>>Asistencia</option>
    </select><br>
    <button type="submit">Actualizar</button>
</form>
