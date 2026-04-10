<?php
require_once __DIR__ . '/../models/Retiro.php';
require_once __DIR__ . '/../models/Alumno.php';
require_once __DIR__ . '/../libs/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class RetirosController
{
    private Retiro $retiroModel;
    private Alumno $alumnoModel;

    private array $rolesEscritura = ['admin', 'administrador', 'inspector', 'Inspector general y Convivencia escolar', 'secretaria'];

    public function __construct()
    {
        $this->retiroModel = new Retiro();
        $this->alumnoModel = new Alumno();
    }

    // ─── HELPERS ────────────────────────────────────────────────────────────────

    private function puedeEditar(): bool
    {
        return in_array($_SESSION['user']['rol'] ?? '', $this->rolesEscritura);
    }

    private function getAnios(): array
    {
        require_once __DIR__ . '/../config/Connection.php';
        $db = new Connection();
        return $db->open()
            ->query("SELECT * FROM anios2 ORDER BY anio DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCursos(): array
    {
        require_once __DIR__ . '/../config/Connection.php';
        $db = new Connection();
        return $db->open()
            ->query("SELECT * FROM cursos2 ORDER BY nombre")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getAnioById(int $id): array|false
    {
        require_once __DIR__ . '/../config/Connection.php';
        $db = new Connection();
        $stmt = $db->open()->prepare("SELECT * FROM anios2 WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getAlumnosBusqueda(string $q): array
    {
        require_once __DIR__ . '/../config/Connection.php';
        $db = new Connection();
        $conn = $db->open();

        $sql = "SELECT a.id AS alumno_id, a.nombre, a.apepat, a.apemat, a.run, a.codver, a.fechanac, 
                       m.id AS matricula_id, c.nombre AS curso, an.anio, an.id AS anio_id
                FROM   alumnos2 a
                JOIN   matriculas2 m ON m.alumno_id = a.id
                JOIN   cursos2    c ON c.id = m.curso_id
                JOIN   anios2    an ON an.id = m.anio_id
                WHERE  a.deleted_at IS NULL
                  AND  an.anio = YEAR(CURDATE())
                  AND  (a.run LIKE :q OR a.nombre LIKE :q OR a.apepat LIKE :q OR a.apemat LIKE :q)
                ORDER  BY a.apepat, a.apemat, a.nombre
                LIMIT  15";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':q' => "%{$q}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ─── INDEX ───────────────────────────────────────────────────────────────────

    public function index(): void
    {
        $user = $_SESSION['user'];
        $puedeEditar = $this->puedeEditar();

        $filtros = [
            'anio_id' => $_GET['anio_id'] ?? '',
            'curso_id' => $_GET['curso_id'] ?? '',
            'alumno_id' => $_GET['alumno_id'] ?? '',
            'semestre' => $_GET['semestre'] ?? '',
            'justificado' => $_GET['justificado'] ?? '',
            'extraordinario' => $_GET['extraordinario'] ?? '',
        ];

        $retiros = $this->retiroModel->getAll($filtros);
        $topAlumnos = $this->retiroModel->getTopAlumnos($filtros, 0);
        $anios = $this->getAnios();
        $cursos = $this->getCursos();

        // Resumen rápido para las tarjetas
        $resumen = [
            'total' => count($retiros),
            'justificados' => count(array_filter($retiros, fn($r) => $r['justificado'] === 'Si')),
            'injustificados' => count(array_filter($retiros, fn($r) => $r['justificado'] === 'No')),
            'extraordinarios' => count(array_filter($retiros, fn($r) => $r['extraordinario'] === 'Si')),
        ];

        include __DIR__ . '/../views/retiros/index.php';
    }

    // ─── BÚSQUEDA AJAX ──────────────────────────────────────────────────────────

    public function buscarAlumnos(): void
    {
        header('Content-Type: application/json');
        $q = trim($_GET['q'] ?? '');
        if (strlen($q) < 2) {
            echo json_encode([]);
            return;
        }
        echo json_encode($this->getAlumnosBusqueda($q));
    }

    // ─── CREATE ──────────────────────────────────────────────────────────────────

    public function create(): void
    {
        $user = $_SESSION['user'];
        $anios = $this->getAnios();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $required = ['alumno_id', 'anio_id', 'fecha_retiro', 'hora_retiro', 'motivo', 'justificado'];
            foreach ($required as $campo) {
                if (empty($_POST[$campo])) {
                    $error = "El campo «{$campo}» es obligatorio.";
                    break;
                }
            }

            if (!$error) {
                $anio_id = (int) $_POST['anio_id'];
                $alumno_id = (int) $_POST['alumno_id'];

                $matricula_id = $this->retiroModel->getMatriculaId($alumno_id, $anio_id);
                if (!$matricula_id) {
                    $error = 'El alumno no tiene matrícula en el año seleccionado.';
                } else {
                    $anio = $this->getAnioById($anio_id);
                    $semestre = $this->retiroModel->calcularSemestre($_POST['fecha_retiro'], $anio);

                    $ok = $this->retiroModel->create([
                        'matricula_id' => $matricula_id,
                        'fecha_retiro' => $_POST['fecha_retiro'],
                        'hora_retiro' => $_POST['hora_retiro'],
                        'motivo' => $_POST['motivo'],
                        'observacion' => trim($_POST['observacion'] ?? ''),
                        'justificado' => $_POST['justificado'],
                        'extraordinario' => isset($_POST['extraordinario']) ? 'Si' : 'No',
                        'quien_retira' => trim($_POST['quien_retira'] ?? ''),
                        'semestre' => $semestre,
                        'registrado_por' => $user['id'] ?? null,
                    ]);

                    if ($ok) {
                        header('Location: index.php?action=retiros&creado=1');
                        exit;
                    }
                    $error = 'Error al guardar el retiro. Inténtalo de nuevo.';
                }
            }
        }

        include __DIR__ . '/../views/retiros/create.php';
    }

    // ─── EDIT ────────────────────────────────────────────────────────────────────

    public function edit(int $id): void
    {
        $user = $_SESSION['user'];
        $retiro = $this->retiroModel->getById($id);
        $error = null;

        if (!$retiro) {
            http_response_code(404);
            exit('Retiro no encontrado.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $required = ['fecha_retiro', 'hora_retiro', 'motivo', 'justificado'];
            foreach ($required as $campo) {
                if (empty($_POST[$campo])) {
                    $error = "El campo «{$campo}» es obligatorio.";
                    break;
                }
            }

            if (!$error) {
                $anio = $this->getAnioById($retiro['anio_id']);
                $semestre = $this->retiroModel->calcularSemestre($_POST['fecha_retiro'], $anio);

                $ok = $this->retiroModel->update($id, [
                    'fecha_retiro' => $_POST['fecha_retiro'],
                    'hora_retiro' => $_POST['hora_retiro'],
                    'motivo' => $_POST['motivo'],
                    'observacion' => trim($_POST['observacion'] ?? ''),
                    'justificado' => $_POST['justificado'],
                    'extraordinario' => isset($_POST['extraordinario']) ? 'Si' : 'No',
                    'quien_retira' => trim($_POST['quien_retira'] ?? ''),
                    'semestre' => $semestre,
                ]);

                if ($ok) {
                    header('Location: index.php?action=retiros&editado=1');
                    exit;
                }
                $error = 'Error al actualizar el retiro.';
            }
        }

        include __DIR__ . '/../views/retiros/edit.php';
    }

    // ─── DELETE ──────────────────────────────────────────────────────────────────

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->retiroModel->delete($id);
        }
        header('Location: index.php?action=retiros&eliminado=1');
        exit;
    }

    // ─── REPORTES ────────────────────────────────────────────────────────────────

    public function reportes(): void
    {
        $anios = $this->getAnios();
        $cursos = $this->getCursos();

        // Traer alumnos con su curso_id para poder filtrar en el front
        require_once __DIR__ . '/../config/Connection.php';
        $db = new Connection();
        $stmt = $db->open()->query(
            "SELECT a.id, a.nombre, a.apepat, a.apemat, a.run,
                m.curso_id, c.nombre AS curso
         FROM   alumnos2 a
         JOIN   matriculas2 m ON m.alumno_id = a.id
         JOIN   cursos2    c ON c.id = m.curso_id
         JOIN   anios2    an ON an.id = m.anio_id
         WHERE  a.deleted_at IS NULL
           AND  an.anio = YEAR(CURDATE())
         ORDER  BY a.apepat, a.apemat, a.nombre"
        );
        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/retiros/reportes.php';
    }

    public function generarReporte(): void
    {
        $tipo = $_GET['tipo'] ?? 'general';
        $anio_id = (int) ($_GET['anio_id'] ?? 0);
        $curso_id = (int) ($_GET['curso_id'] ?? 0);
        $alumno_id = (int) ($_GET['alumno_id'] ?? 0);
        $semestre = $_GET['semestre'] ?? '';
        $formato = $_GET['formato'] ?? 'html';

        $filtros = array_filter([
            'anio_id' => $anio_id ?: null,
            'curso_id' => $tipo !== 'alumno' ? ($curso_id ?: null) : null,
            'alumno_id' => $alumno_id ?: null,
            'semestre' => $semestre ?: null,
        ]);

        $retiros = $this->retiroModel->getAll($filtros);
        $porSemestre = $this->retiroModel->getResumenPorSemestre($filtros);
        $porMotivo = $this->retiroModel->getResumenPorMotivo($filtros);
        $porCurso = $this->retiroModel->getResumenPorCurso($filtros);
        $topAlumnos = $this->retiroModel->getTopAlumnos($filtros);
        $media = $anio_id ? $this->retiroModel->getMediaRetiros($anio_id) : null;
        $anioActual = $anio_id ? $this->getAnioById($anio_id) : null;
        $anios = $this->getAnios();
        $cursos = $this->getCursos();
        $alumnos = $this->alumnoModel->getAll();

        if ($formato === 'pdf') {
            $this->exportarPDF($tipo, compact(
                'tipo',
                'retiros',
                'porSemestre',
                'porMotivo',
                'porCurso',
                'topAlumnos',
                'media',
                'anioActual',
                'filtros',
                'semestre'
            ));
            return;
        }

        include __DIR__ . '/../views/retiros/reporte_resultado.php';
    }

    private function exportarPDF(string $tipo, array $datos): void
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);

        ob_start();
        extract($datos);
        include __DIR__ . '/../views/retiros/reporte_pdf.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('reporte_retiros_' . date('Ymd_His') . '.pdf', ['Attachment' => true]);
        exit;
    }
}