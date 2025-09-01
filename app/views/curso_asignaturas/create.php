<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear relación Curso - Asignatura</title>
</head>
<body>
    <h1>Nueva Relación Curso - Asignatura</h1>

    <form action="index.php?action=curso_asignaturas_store" method="post">
        <label for="curso_id">Curso:</label>
        <select name="curso_id" id="curso_id" required>
            <option value="">-- Selecciona un curso --</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= $curso['id'] ?>"><?= htmlspecialchars($curso['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="asignatura_id">Asignatura:</label>
        <select name="asignatura_id" id="asignatura_id" required>
            <option value="">-- Selecciona una asignatura --</option>
            <?php foreach ($asignaturas as $asignatura): ?>
                <option value="<?= $asignatura['id'] ?>"><?= htmlspecialchars($asignatura['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <button type="submit">Guardar</button>
        <a href="index.php?action=curso_asignaturas">Cancelar</a>
    </form>
</body>
</html>
