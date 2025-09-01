<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Curso</title>
</head>
<body>
    <h1>Crear Curso</h1>
    <form method="POST" action="index.php?action=curso_store">
        <br><br>

        <label>Nombre del curso:</label>
        <input type="text" name="nombre" required>
        <br><br>

        <button type="submit">Guardar</button>
    </form>
    <br>
    <a href="index.php?action=cursos">⬅️ Volver</a>
</body>
</html>
