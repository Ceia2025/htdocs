<?php
require_once __DIR__ . '/../app/email/controller/AlertaAtrasoController.php';

// Archivo de control: guarda la última semana (formato ISO "YYYY-Www") en que se envió
$controlFile = __DIR__ . '/.ultima_alerta_atrasos_semana';

$hoy = new DateTime();
$diaSemana = (int) $hoy->format('N'); // 1=lunes ... 7=domingo

// Solo se intenta enviar de lunes a viernes
if ($diaSemana > 5) {
    exit("No corresponde enviar hoy (fin de semana).\n");
}

$semanaActual = $hoy->format('o-W'); // Ej: "2026-W24"

$ultimaSemanaEnviada = file_exists($controlFile) ? trim(file_get_contents($controlFile)) : '';

// Si ya se envió (o se intentó sin éxito de forma definitiva) esta semana, no repetir
if ($ultimaSemanaEnviada === $semanaActual) {
    exit("La alerta de atrasos ya fue procesada esta semana ($semanaActual).\n");
}

$controller = new AlertaAtrasoController();
$resultado  = $controller->verificarYEnviarAlertaAtrasos();

echo date('Y-m-d H:i:s') . " - Resultado: $resultado\n";

// Marcamos la semana como "procesada" tanto si se envió como si no había atrasos
// (para no reintentar todos los días si efectivamente no hay nada que reportar).
// Si fue 'error_envio' NO marcamos, para que reintente al día siguiente.
if ($resultado === 'enviada' || $resultado === 'sin_atrasos') {
    file_put_contents($controlFile, $semanaActual);
}