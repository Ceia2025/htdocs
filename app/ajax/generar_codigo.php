<?php
require_once __DIR__ . '/../models/inventario/Individualizacion.php';
require_once __DIR__ . '/../config/Connection.php';

$codigo_general = $_GET['codigo_general'] ?? null;

if (!$codigo_general) {
    echo json_encode(['error' => 'Código general no recibido']);
    exit;
}

$conn = (new Connection())->open();

$stmt = $conn->prepare("SELECT MAX(codigo_especifico) as max_cod FROM individualizacion WHERE codigo_general = :codigo_general");
$stmt->execute([':codigo_general' => $codigo_general]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$nuevo_codigo = ($row['max_cod'] ?? 0) + 1;

echo json_encode(['nuevo_codigo' => $nuevo_codigo]);