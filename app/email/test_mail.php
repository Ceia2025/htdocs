<?php
require __DIR__ . '/../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


$mail = new PHPMailer(true);

try {
    // Configuración SMTP Outlook
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'scarlazzetta.sepu@gmail.com';   // la cuenta nueva que creaste
    $mail->Password   = 'kake whxb evtg vesg';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    // Remitente y destinatario
    $mail->setFrom('scarlazzetta.sepu@gmail.com', 'Sistema SAAT');
    $mail->addAddress('scarlazzetta.sepu2026@gmail.com'); // donde quieres recibir la prueba

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Prueba de correo SAAT';
    $mail->Body    = '<h2>¡Funciona!</h2><p>El sistema de alertas está configurado correctamente.</p>';

    $mail->send();
    echo '✅ Correo enviado correctamente';

} catch (Exception $e) {
    echo '❌ Error: ' . $mail->ErrorInfo;
}