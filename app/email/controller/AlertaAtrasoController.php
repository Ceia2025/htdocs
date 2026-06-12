<?php
require_once __DIR__ . '/../../config/Connection.php';
require_once __DIR__ . '/../models/AlertaAtraso.php';
require_once __DIR__ . '/../services/Mailer.php';

class AlertaAtrasoController
{
    public function verificarYEnviarAlertaAtrasos(): string
    {
        try {
            $alertaModel = new AlertaAtraso();
            $resultado   = $alertaModel->buscarUltimaSemanaConAtrasos();

            if ($resultado === null) {
                return 'sin_atrasos';
            }

            $destinatarios = $alertaModel->getEmailsDestinatarios();
            $emails        = array_column($destinatarios, 'email');

            if (empty($emails)) {
                error_log('AlertaAtraso: no hay destinatarios con email.');
                return 'error_envio';
            }

            $atrasos = $resultado['atrasos'];
            $desde   = $resultado['desde'];
            $hasta   = $resultado['hasta'];

            // Agrupar por curso -> alumno
            $agrupado = [];
            foreach ($atrasos as $a) {
                $cursoNombre  = $a['curso'];
                $alumnoNombre = $a['apepat'] . ' ' . $a['apemat'] . ', ' . $a['nombre'];
                $agrupado[$cursoNombre][$alumnoNombre][] = $a;
            }
            ksort($agrupado);

            // Totales
            $totalAtrasos       = count($atrasos);
            $totalInjustificados = count(array_filter($atrasos, fn($a) => (int) $a['justificado'] === 0));
            $totalAlumnos       = count(array_unique(array_map(fn($a) => $a['matricula_id'], $atrasos)));

            ob_start();
            require __DIR__ . '/../views/alerta_atrasos.php';
            $html = ob_get_clean();

            $mailer  = new Mailer();
            $enviado = $mailer->enviar(
                $emails,
                'Reporte semanal de atrasos — ' . date('d/m/Y', strtotime($desde)) . ' al ' . date('d/m/Y', strtotime($hasta)),
                $html
            );

            return $enviado ? 'enviada' : 'error_envio';

        } catch (\Throwable $e) {
            error_log('AlertaAtraso excepción: ' . $e->getMessage());
            return 'error_envio';
        }
    }
}