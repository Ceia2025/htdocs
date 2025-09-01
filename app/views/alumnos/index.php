<h2>Listado de Alumnos</h2>
<a href="index.php?action=alumno_create">➕ Nuevo Alumno</a>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>RUN</th>
            <th>Nombre Completo</th>
            <th>Fecha Nac.</th>
            <th>Sexo</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Nacionalidad</th>
            <th>Región</th>
            <th>Ciudad</th>
            <th>Etnia</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($alumnos)): ?>
        <?php foreach ($alumnos as $alumno): ?>
            <tr>
                <td><?= htmlspecialchars($alumno['id']) ?></td>
                <td><?= htmlspecialchars($alumno['run']) ?></td>
                <td><?= htmlspecialchars($alumno['nombre'] . " " . $alumno['apepat'] . " " . $alumno['apemat']) ?></td>
                <td><?= htmlspecialchars($alumno['fechanac']) ?></td>
                <td><?= htmlspecialchars($alumno['sexo']) ?></td>
                <td><?= htmlspecialchars($alumno['email']) ?></td>
                <td><?= htmlspecialchars($alumno['telefono']) ?></td>
                <td><?= htmlspecialchars($alumno['nacionalidades']) ?></td>
                <td><?= htmlspecialchars($alumno['region']) ?></td>
                <td><?= htmlspecialchars($alumno['ciudad']) ?></td>
                <td><?= htmlspecialchars($alumno['cod_etnia']) ?></td>
                <td>
                    <a href="index.php?action=alumno_edit&id=<?= $alumno['id'] ?>">✏️ Editar</a> |
                    <a href="index.php?action=alumno_delete&id=<?= $alumno['id'] ?>" onclick="return confirm('¿Eliminar este alumno?')">🗑️ Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="12">No hay alumnos registrados</td></tr>
    <?php endif; ?>
    </tbody>
</table>
