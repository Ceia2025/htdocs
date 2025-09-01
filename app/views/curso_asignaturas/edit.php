<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar relación Curso - Asignatura</title>
</head>
<body>
    <h1>Editar Relación Curso - Asignatura</h1>

    <form action="index.php?action=curso_asignaturas_update&id=<?= $cursoAsignatura['id'] ?>" method="post">
        <label for="curso_id">Curso:</label>
        <select name="curso_id" id="curso_id" required>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= $curso['id'] ?>"
                    <?= $curso['id'] == $cursoAsignatura['curso_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($curso['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="asignatura_id">Asignatura:</label>
        <select name="asignatura_id" id="asignatura_id" required>
            <?php foreach ($asignaturas as $asignatura): ?>
                <option value="<?= $asignatura['id'] ?>"
                    <?= $asignatura['id'] == $cursoAsignatura['asignatura_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($asignatura['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <button type="submit">Actualizar</button>
        <a href="index.php?action=curso_asignaturas">Cancelar</a>
    </form>
</body>
</html>
