<?php
// app/email/test_mail.php

require_once __DIR__ . '/services/Mailer.php';

$mailer = new Mailer();

$destinatario = 'scarlazzetta.sepu2026@gmail.com'; // 

$asunto = 'Prueba de correo SAAT (vía Brevo)';
$cuerpo = '<h2>¡Funciona!</h2><p>El sistema de alertas está configurado correctamente usando la API de Brevo.</p>';

$enviado = $mailer->enviar($destinatario, $asunto, $cuerpo);

if ($enviado) {
    echo '✅ Correo enviado correctamente';
} else {
    echo '❌ Error al enviar. Revisa el log de errores de PHP para más detalle.';
}