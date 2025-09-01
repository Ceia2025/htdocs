<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Contacto de Emergencia</title>
</head>
<body>
    <h1>✏️ Editar Contacto</h1>
    <form method="POST" action="index.php?action=alum_emergencia_update&id=<?= $emergencia['id'] ?>">
        <label>Alumno:</label>
        <select name="alumno_id" required>
            <?php foreach ($alumnos as $a): ?>
                <option value="<?= $a['id'] ?>" <?= ($a['id'] == $emergencia['alumno_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['nombre'] . " " . $a['ape_paterno'] . " " . $a['ape_materno']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Nombre del contacto:</label>
        <input type="text" name="nombre_contacto" value="<?= htmlspecialchars($emergencia['nombre_contacto']) ?>" required>
        <br><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($emergencia['telefono']) ?>" required>
        <br><br>

        <label>Dirección:</label>
        <input type="text" name="direccion" value="<?= htmlspecialchars($emergencia['direccion']) ?>">
        <br><br>

        <label>Relación:</label>
        <select name="relacion" required>
            <?php
            $opciones = ['Madre', 'Padre', 'Relación directa', 'Tutor Legal', 'Representante', 'Apoderado'];
            foreach ($opciones as $op):
            ?>
                <option value="<?= $op ?>" <?= ($op == $emergencia['relacion']) ? 'selected' : '' ?>><?= $op ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <button type="submit">Actualizar</button>
    </form>

    <br>
    <a href="index.php?action=alum_emergencia">⬅️ Volver</a>
</body>
</html>
