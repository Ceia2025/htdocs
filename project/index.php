<?php
require_once("config/Database.php");
require_once("models/RecordModel.php");

$db = (new Database())->getConnection();
$recordModel = new RecordModel($db);

// Obtener registros
$stmt = $db->prepare("SELECT * FROM records ORDER BY id ASC");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Subir archivo TXT</title>
  <style>
    table {
      border-collapse: collapse;
      width: 70%;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
    th {
      background: #f4f4f4;
    }
  </style>
</head>
<body>
  <h1>Subir archivo .txt</h1>
  <form action="controllers/UploadController.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file" accept=".txt" required>
    <button type="submit" name="upload">Subir</button>
  </form>

  <?php if (count($records) > 0): ?>
    <h2>Datos en la base de datos</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>RUT</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Fecha</th>
      </tr>
      <?php foreach ($records as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['rut']) ?></td>
        <td><?= htmlspecialchars($row['nombre']) ?></td>
        <td><?= htmlspecialchars($row['descp']) ?></td>
        <td><?= htmlspecialchars($row['fecha']) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No hay registros en la base de datos.</p>
  <?php endif; ?>
</body>
</html>
