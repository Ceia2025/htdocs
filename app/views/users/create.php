<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="../utils/styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Crear Usuario</h1>
        <form method="post" action="index.php?action=user_store">
            
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="text" name="username" placeholder="Nombre de usuario 'Alias'" required>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="ape_paterno" placeholder="Apellido Paterno" required>
            <input type="text" name="ape_materno" placeholder="Apellido Materno" required>
            <input type="text" name="run" placeholder="Rut" required>
            <input type="text" name="telefono" placeholder="Número telefónico" required>

            <select name="rol_id" required>
                <option value="">-- Selecciona un rol --</option>
                <?php foreach ($roles as $rol): ?>
                    <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="button-group">
                <button type="submit">Guardar</button>
                <a href="index.php?action=dashboard" class="btn-link">Regresar</a>
                <a href="index.php?action=dashboard" class="btn-link logout">Cerrar sesión</a>
            </div>
        </form>
    </div>
</body>

</html>