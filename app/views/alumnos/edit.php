<h2>Editar Alumno</h2>
<form action="index.php?action=alumno_update&id=<?= $alumno['id'] ?>" method="POST">
    <label>RUN:</label>
    <input type="text" name="run" value="<?= htmlspecialchars($alumno['run']) ?>" required><br>

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($alumno['nombre']) ?>" required><br>

    <label>Apellido Paterno:</label>
    <input type="text" name="apepat" value="<?= htmlspecialchars($alumno['apepat']) ?>" required><br>

    <label>Apellido Materno:</label>
    <input type="text" name="apemat" value="<?= htmlspecialchars($alumno['apemat']) ?>"><br>

    <label>Fecha de Nacimiento:</label>
    <input type="date" name="fechanac" value="<?= htmlspecialchars($alumno['fechanac']) ?>"><br>

    <label>¿Mayor de Edad?</label>
    <select name="mayoredad">
        <option value="Si" <?= $alumno['mayoredad'] === 'Si' ? 'selected' : '' ?>>Si</option>
        <option value="No" <?= $alumno['mayoredad'] === 'No' ? 'selected' : '' ?>>No</option>
    </select><br>

    <label>Número de Hijos:</label>
    <input type="number" name="numerohijos" min="0" value="<?= htmlspecialchars($alumno['numerohijos']) ?>"><br>

    <label>Teléfono:</label>
    <input type="text" name="telefono" value="<?= htmlspecialchars($alumno['telefono']) ?>"><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($alumno['email']) ?>"><br>

    <label>Sexo:</label>
    <select name="sexo" required>
        <option value="Femenino" <?= $alumno['sexo'] === 'Femenino' ? 'selected' : '' ?>>Femenino</option>
        <option value="Masculino" <?= $alumno['sexo'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
        <option value="Prefiere no identificar" <?= $alumno['sexo'] === 'Prefiere no identificar' ? 'selected' : '' ?>>Prefiere no identificar</option>
        <option value="Otro" <?= $alumno['sexo'] === 'Otro' ? 'selected' : '' ?>>Otro</option>
    </select><br>

    <label>Nacionalidad:</label>
    <input type="text" name="nacionalidades" value="<?= htmlspecialchars($alumno['nacionalidades']) ?>"><br>

    <label>Región:</label>
    <input type="text" name="region" value="<?= htmlspecialchars($alumno['region']) ?>"><br>

    <label>Ciudad:</label>
    <input type="text" name="ciudad" value="<?= htmlspecialchars($alumno['ciudad']) ?>"><br>

    <label>Etnia:</label>
    <select name="cod_etnia">
        <?php
        $etnias = [
            "Ninguna","Mapuche","Aymara","Rapa Nui","Lickan Antai (Atacameños)",
            "Quechua","Colla","Diaguita","Chango","Kawésqar","Yagán","Selk nam"
        ];
        foreach ($etnias as $etnia): ?>
            <option value="<?= $etnia ?>" <?= $alumno['cod_etnia'] === $etnia ? 'selected' : '' ?>>
                <?= $etnia ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Actualizar</button>
</form>
