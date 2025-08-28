<link rel="stylesheet" href="../utils/styles.css">

<div class="container">
    <h1>Editar Usuario</h1>

    <form method="post" action="index.php?action=user_update&id=<?= $user['id'] ?>" class="form-container">

        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" placeholder="Usuario" required>
        <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" placeholder="Nombre" required>
        <input type="text" name="ape_paterno" value="<?= htmlspecialchars($user['ape_paterno']) ?>" placeholder="Apellido Paterno" required>
        <input type="text" name="ape_materno" value="<?= htmlspecialchars($user['ape_materno']) ?>" placeholder="Apellido Materno" required>
        <input type="text" name="run" value="<?= htmlspecialchars($user['run']) ?>" placeholder="RUT" required>
        <input type="text" name="telefono" value="<?= htmlspecialchars($user['numero_telefonico']) ?>" placeholder="Teléfono" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Nueva contraseña (opcional)">

        <select name="rol_id" required>
            <option value="">-- Selecciona un rol --</option>
            <?php foreach ($roles as $rol): ?>
                <option value="<?= $rol['id'] ?>" <?= ($rol['id'] == $user['rol_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($rol['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Actualizar</button>
        <a href="index.php?action=dashboard" class="btn-secondary">Regresar</a>
    </form>
</div>