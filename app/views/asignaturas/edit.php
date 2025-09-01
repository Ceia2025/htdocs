<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Asignatura</title>
</head>
<body>
    <h1>Editar Asignatura</h1>
    <form method="POST" action="index.php?action=asignatura_update&id=<?= $asignatura['id'] ?>">
        <br><br>

        <label>Nombre de la Asignatura:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($asignatura['nombre']) ?>" required>
        <br><br>

        <label>Descripción:</label>
        <textarea name="descp" rows="4" cols="40" required><?= htmlspecialchars($asignatura['descp']) ?></textarea>
        <br><br>

        <button type="submit">Actualizar</button>
    </form>
    <br>
    <a href="index.php?action=asignaturas">⬅️ Volver</a>
</body>
</html>
