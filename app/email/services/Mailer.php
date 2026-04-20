<?php
// app/services/Mailer.php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private string $host     = 'smtp.gmail.com';
    private int    $port     = 587;
    private string $usuario  = 'scarlazzetta.sepu@gmail.com';      // ← cambia esto
    private string $password = 'kake whxb evtg vesg'; // ← cambia esto
    private string $nombreRemitente = 'Sistema SAAT - CEIA Parral';

    /**
     * Envía un correo HTML a uno o varios destinatarios.
     * $destinatarios: string o array de emails.
     * Retorna true si se envió, false si hubo error.
     */
    public function enviar(string|array $destinatarios, string $asunto, string $cuerpoHtml): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $this->host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->usuario;
            $mail->Password   = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->port;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($this->usuario, $this->nombreRemitente);

            $lista = is_array($destinatarios) ? $destinatarios : [$destinatarios];
            foreach ($lista as $email) {
                if (!empty(trim($email))) {
                    $mail->addAddress(trim($email));
                }
            }

            if (empty($mail->getToAddresses())) {
                error_log('Mailer: no hay destinatarios válidos.');
                return false;
            }

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpoHtml;

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log('Mailer error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}