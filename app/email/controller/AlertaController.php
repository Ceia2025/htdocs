<?php
// app/email/AlertaController.php

require_once __DIR__ . '/../../config/Connection.php'; // ← corregir esto
require_once __DIR__ . '/../models/AlertaAsistencia.php';  // ← este está bien
require_once __DIR__ . '/../services/Mailer.php';

class AlertaController
{
    public function verificarYEnviarAlertaAusencias(int $cursoId, int $anioId, string $fecha): string
    {
        try {
            $alertaModel = new AlertaAsistencia();
            $alumnosAusentes = $alertaModel->detectarAusenciasConsecutivas($cursoId, $anioId, $fecha);

            if (empty($alumnosAusentes))
                return 'sin_ausencias';

            $destinatarios = $alertaModel->getEmailsDestinatarios();
            $emails = array_column($destinatarios, 'email');

            if (empty($emails)) {
                error_log('AlertaAsistencia: no hay destinatarios con email.');
                return 'error_envio';
            }

            $curso = $alumnosAusentes[0]['curso'] ?? "Curso ID $cursoId";
            $umbral = $alertaModel->getUmbral();

            ob_start();
            require __DIR__ . '/../views/alerta_ausencias.php';
            $html = ob_get_clean();

            $mailer = new Mailer();
            $enviado = $mailer->enviar(
                $emails,
                "Alerta de asistencia — $curso — " . date('d/m/Y', strtotime($fecha)),
                $html
            );

            return $enviado ? 'enviada' : 'error_envio';

        } catch (\Throwable $e) {
            error_log('AlertaAsistencia excepción: ' . $e->getMessage());
            return 'error_envio';
        }
    }
}