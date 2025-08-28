<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Curso</title>
</head>
<body>
    <h1>Editar Curso</h1>
    <form method="POST" action="index.php?action=curso_update&id=<?= $curso['id'] ?>">
        <label>Año:</label>
        <select name="anio_id" required>
            <?php foreach ($anios as $anio): ?>
                <option value="<?= $anio['id'] ?>" <?= $anio['id'] == $curso['anio_id'] ? 'selected' : '' ?>>
                    <?= $anio['anio'] ?> - <?= htmlspecialchars($anio['descripcion']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Nombre del curso:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($curso['nombre']) ?>" required>
        <br><br>

        <button type="submit">Actualizar</button>
    </form>
    <br>
    <a href="index.php?action=cursos">⬅️ Volver</a>
</body>
</html>
