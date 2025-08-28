<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Año</title>
</head>
<body>
    <h1>Crear Año</h1>

    

    <form method="post" action="index.php?action=anio_store">
        <label for="anio">Año:</label>
        <input type="text" name="anio" id="anio" required><br><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea name="descripcion" id="descripcion"></textarea><br><br>

        <button type="submit">Guardar</button>
    </form>

    <br>
    <a href="index.php?action=anios">⬅ Volver</a>
</body>
</html>
