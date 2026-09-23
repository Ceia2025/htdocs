<?php
// app/email/services/Mailer.php
class Mailer
{
    private string $apiKey = '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';
    private string $apiUrl = 'https://api.brevo.com/v3/smtp/email';
    private string $emailRemitente  = 'scarlazzetta.sepu@gmail.com';
    private string $nombreRemitente = 'Sistema SAAT - CEIA Parral - 2026';

    /**
     * Envía un correo HTML a uno o varios destinatarios vía API de Brevo.
     * $destinatarios: string o array de emails.
     * Retorna true si se envió, false si hubo error.
     */
    public function enviar(string|array $destinatarios, string $asunto, string $cuerpoHtml): bool
    {
        $lista = is_array($destinatarios) ? $destinatarios : [$destinatarios];

        $to = [];
        foreach ($lista as $email) {
            $email = trim($email);
            if (!empty($email)) {
                $to[] = ['email' => $email];
            }
        }

        if (empty($to)) {
            error_log('Mailer: no hay destinatarios válidos.');
            return false;
        }

        $payload = [
            'sender' => [
                'name'  => $this->nombreRemitente,
                'email' => $this->emailRemitente,
            ],
            'to'          => $to,
            'subject'     => $asunto,
            'htmlContent' => $cuerpoHtml,
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'accept: application/json',
                'api-key: ' . $this->apiKey,
                'content-type: application/json',
            ],
            CURLOPT_TIMEOUT        => 15,
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            error_log('Mailer (Brevo) error de conexión: ' . $curlError);
            return false;
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        error_log('Mailer (Brevo) error HTTP ' . $httpCode . ': ' . $response);
        return false;
    }
}