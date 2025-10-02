<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Inventario</title>
    <link rel="stylesheet" href="path_to_your_css.css">
</head>
<body>
    <h1>Eliminar Registro de Inventario</h1>
    <p>¿Estás seguro que deseas eliminar el registro:</p>
    <ul>
        <li><strong>ID:</strong> <?= $item['id'] ?></li>
        <li><strong>Individualización:</strong> <?= htmlspecialchars($item['individualizacion']) ?></li>
        <li><strong>Código:</strong> <?= htmlspecialchars($item['codigo_general']) ?> - <?= $item['codigo_especifico'] ?></li>
    </ul>

    <form action="index.php?action=inventario_destroy&id=<?= $item['id'] ?>" method="POST">
        <button type="submit">Sí, eliminar</button>
        <a href="index.php?action=inventario_index">Cancelar</a>
    </form>
</body>
</html>
