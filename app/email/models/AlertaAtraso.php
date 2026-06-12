<?php
require_once __DIR__ . '/../../config/Connection.php';
require_once __DIR__ . '/../../models/Atraso.php';

class AlertaAtraso
{
    private $conn;
    private Atraso $atrasoModel;

    // Roles que reciben el reporte semanal de atrasos
    private array $rolesDestinatarios = [1, 5]; // ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
        $this->atrasoModel = new Atraso();
    }

    public function getEmailsDestinatarios(): array
    {
        $placeholders = implode(',', array_fill(0, count($this->rolesDestinatarios), '?'));
        $sql = "SELECT DISTINCT u.email, u.nombre, u.ape_paterno
                FROM usuarios2 u
                WHERE u.rol_id IN ($placeholders)
                  AND u.email IS NOT NULL
                  AND u.email != ''";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($this->rolesDestinatarios);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca la última semana (lunes a domingo) con al menos un atraso,
     * comenzando por la semana pasada y retrocediendo hasta $maxSemanas.
     *
     * Retorna ['desde'=>..,'hasta'=>..,'atrasos'=>[...]] o null si no hay nada.
     */
    public function buscarUltimaSemanaConAtrasos(?string $fechaReferencia = null, int $maxSemanas = 8): ?array
    {
        $fechaReferencia = $fechaReferencia ?? date('Y-m-d');

        // Lunes de la semana actual (referencia)
        $hoy = new DateTime($fechaReferencia);
        $diaSemana = (int) $hoy->format('N'); // 1=lunes ... 7=domingo
        $lunesActual = clone $hoy;
        $lunesActual->modify('-' . ($diaSemana - 1) . ' days');

        // i=1 => semana pasada, i=2 => hace dos semanas, etc.
        for ($i = 1; $i <= $maxSemanas; $i++) {
            $lunes = clone $lunesActual;
            $lunes->modify('-' . (7 * $i) . ' days');

            $domingo = clone $lunes;
            $domingo->modify('+6 days');

            $desde = $lunes->format('Y-m-d');
            $hasta = $domingo->format('Y-m-d');

            $anioId = $this->atrasoModel->getAnioIdPorFecha($hasta)
                   ?? $this->atrasoModel->getAnioIdPorFecha($desde);

            if (!$anioId) {
                continue;
            }

            $atrasos = $this->atrasoModel->getByCursoFiltrado(null, $anioId, null, $desde, $hasta);

            if (!empty($atrasos)) {
                return [
                    'desde'   => $desde,
                    'hasta'   => $hasta,
                    'atrasos' => $atrasos,
                ];
            }
        }

        return null;
    }
}