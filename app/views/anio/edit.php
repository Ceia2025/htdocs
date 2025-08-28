<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Año</title>
</head>
<body>
    <h1>Editar Año</h1>

    <form method="post" action="index.php?action=anio_update&id=<?= $anio['id'] ?>">
        <label for="anio">Año:</label>
        <input type="text" name="anio" id="anio" value="<?= htmlspecialchars($anio['anio']) ?>" required><br><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea name="descripcion" id="descripcion"><?= htmlspecialchars($anio['descripcion']) ?></textarea><br><br>

        <button type="submit">Actualizar</button>
    </form>

    <br>
    <a href="index.php?action=anios">⬅ Volver</a>
</body>
</html>
