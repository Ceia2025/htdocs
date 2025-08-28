<h1>Crear Usuario</h1>
<form method="post" action="index.php?action=user_store">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <input type="text" name="username" placeholder="Nombre de usuario 'Alias'" required><br>
    <input type="text" name="nombre" placeholder="Nombre" required><br>
    <input type="text" name="ape_paterno" placeholder="Apellido Paterno" required><br>
    <input type="text" name="ape_materno" placeholder="Apellido Materno" required><br>
    <input type="text" name="run" placeholder="Rut" required><br>
    <input type="text" name="telefono" placeholder="Número teléfonico" required><br>

    <select name="rol_id" required>
        <option value="">-- Selecciona un rol --</option>
        <?php foreach ($roles as $rol): ?>
            <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Guardar</button>
    
    <a href="index.php?action=dashboard">Regresar</a>

<a href="index.php?action=dashboard">Cerrar sesión</a>
    
</form>