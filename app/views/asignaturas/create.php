<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Asignatura</title>
</head>
<body>
    <h1>Crear Asignatura</h1>
    <form method="POST" action="index.php?action=asignatura_store">
        <br><br>

        <label>Nombre de la Asignatura:</label>
        <input type="text" name="nombre" required>
        <br><br>

        <label>Descripción:</label>
        <textarea name="descp" rows="4" cols="40" required></textarea>
        <br><br>

        <button type="submit">Guardar</button>
    </form>
    <br>
    <a href="index.php?action=asignaturas">⬅️ Volver</a>
</body>
</html>
