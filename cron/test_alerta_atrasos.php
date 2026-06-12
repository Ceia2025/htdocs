<?php
require_once __DIR__ . '/../app/email/controller/AlertaAtrasoController.php';

$controller = new AlertaAtrasoController();
$resultado  = $controller->verificarYEnviarAlertaAtrasos();

echo "Resultado: $resultado\n";