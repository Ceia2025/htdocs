<h1>Crear Usuario</h1>
<form method="post" action="index.php?action=user_store">
    <input type="text" name="nombre" placeholder="Nombre" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <select name="rol">
        <option value="admin">Admin</option>
        <option value="docente">Docente</option>
        <option value="registro">Registro</option>
        <option value="asistencia">Asistencia</option>
    </select><br>
    <button type="submit">Guardar</button>
</form>
