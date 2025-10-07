<?php
require_once __DIR__ . '/../models/inventario/Individualizacion.php';

$term = $_GET['term'] ?? '';
$model = new Individualizacion();

$conn = (new Connection())->open();

$sql = "SELECT nombre, codigo_general 
        FROM individualizacion
        WHERE nombre LIKE :term
        GROUP BY nombre, codigo_general
        ORDER BY nombre ASC
        LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->execute([':term' => "%$term%"]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);