<h1>Editar Usuario</h1>
<form method="post" action="index.php?action=user_update&id=<?= $user['id'] ?>">

    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>
    <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required><br>
    <input type="text" name="ape_paterno" value="<?= htmlspecialchars($user['ape_paterno']) ?>" required><br>
    <input type="text" name="ape_materno" value="<?= htmlspecialchars($user['ape_materno']) ?>" required><br>
    <input type="text" name="run" value="<?= htmlspecialchars($user['run']) ?>" required><br>
    <input type="text" name="telefono" value="<?= htmlspecialchars($user['numero_telefonico']) ?>" required><br>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
    <input type="password" name="password" placeholder="Nueva contraseña (opcional)"><br>

    <select name="rol_id" required>
        <option value="">-- Selecciona un rol --</option>
        <?php foreach ($roles as $rol): ?>
            <option value="<?= $rol['id'] ?>" <?= ($rol['id'] == $user['rol_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($rol['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit">Actualizar</button>
</form>
