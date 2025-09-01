<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo Contacto de Emergencia</title>
</head>

<body>
    <h1>➕ Nuevo Contacto de Emergencia</h1>
    <form method="POST" action="index.php?action=alum_emergencia_store">
        <label>Alumno:</label>
        <select name="alumno_id" required>
            <option value="">-- Selecciona un alumno --</option>
            <?php foreach ($alumnos as $a): ?>
                <option value="<?= $a['id'] ?>">
                    <?= htmlspecialchars($a['nombre'] . " " . $a['ape_paterno'] . " " . $a['ape_materno']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Nombre del contacto:</label>
        <input type="text" name="nombre_contacto" required>
        <br><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" required>
        <br><br>

        <label>Dirección:</label>
        <input type="text" name="direccion">
        <br><br>

        <label>Relación:</label>
        <select name="relacion" required>
            <option value="">-- Selecciona --</option>
            <option value="Madre">Madre</option>
            <option value="Padre">Padre</option>
            <option value="Relación directa">Relación directa</option>
            <option value="Tutor Legal">Tutor Legal</option>
            <option value="Representante">Representante</option>
            <option value="Apoderado">Apoderado</option>
        </select>
        <br><br>

        <button type="submit">Guardar</button>
    </form>

    <br>
    <a href="index.php?action=alum_emergencia">⬅️ Volver</a>
</body>

</html>