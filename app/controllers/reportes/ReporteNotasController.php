<?php
require_once __DIR__ . '/../../models/Nota.php';
require_once __DIR__ . '/../../models/Matricula.php';
require_once __DIR__ . '/../../models/Alumno.php';
require_once __DIR__ . '/../../models/Anio.php';
require_once __DIR__ . '/../../models/Cursos.php';
require_once __DIR__ . '/../../models/Asignaturas.php';
require_once __DIR__ . '/../../models/CursoAsignatura.php';
require_once __DIR__ . '/../../models/CursoDocente.php';
require_once __DIR__ . '/../../models/Asistencia.php';
require_once __DIR__ . '/../../libs/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteNotasController
{
    private $notaModel;

    public function __construct()
    {
        $this->notaModel = new Nota();
    }

    /* ══════════════════════════════════════════════════════════
       PANEL PRINCIPAL DE REPORTES DE NOTAS
       Muestra selector de curso → asignatura → alumno
    ══════════════════════════════════════════════════════════ */
    public function index(): void
    {
        $anioModel = new Anio();
        $cursoModel = new Cursos();

        // ── ESTA LÍNEA es la clave — lee el GET o usa el año actual
        $anioId = (int) ($_GET['anio_id'] ?? (new CursoDocente())->getAnioActualId());
        $anios = $anioModel->getAll();

        $user = $_SESSION['user'];
        $rolId = $user['rol_id'];
        $userId = $user['id'];

        $rolesAdmin = [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_INSPECTOR_GENERAL, ROL_UTP];
        $cursoDocenteModel = new CursoDocente();

        if (in_array($rolId, $rolesAdmin)) {
            $cursos = $cursoDocenteModel->getAllConDocente($anioId);
            $esTodos = true;
        } else {
            $cursos = $cursoDocenteModel->getCursosByDocente($userId, $anioId);
            $esTodos = false;
        }

        require __DIR__ . '/../../views/reportes/notas/index.php';
    }

    /* ══════════════════════════════════════════════════════════
       PDF INDIVIDUAL DEL ALUMNO
       Todas las asignaturas · Ambos semestres · Columnas dinámicas
    ══════════════════════════════════════════════════════════ */
    public function pdfAlumno(): void
    {
        $matriculaId = (int) ($_GET['matricula_id'] ?? 0);
        if (!$matriculaId)
            die('Falta matricula_id.');

        $matriculaModel = new Matricula();
        $alumnoModel = new Alumno();
        $anioModel = new Anio();
        $cursoModel = new Cursos();
        $cursoAsignaturaModel = new CursoAsignatura();
        $cursoDocenteModel = new CursoDocente();
        $asistenciaModel = new Asistencia();

        $matricula = $matriculaModel->getById($matriculaId);
        if (!$matricula)
            die('Matrícula no encontrada.');

        $alumno = $alumnoModel->getById($matricula['alumno_id']);
        $curso = $cursoModel->getById($matricula['curso_id']);
        $anio = $anioModel->getById($matricula['anio_id']);
        $asignaturas = $cursoAsignaturaModel->getAsignaturasPorCurso($curso['id']);

        $asignaturasExcluidas = ['Inspectoría', 'Convivencia Escolar'];
        $asignaturas = array_filter($asignaturas, function ($a) use ($asignaturasExcluidas) {
            return !in_array($a['nombre'], $asignaturasExcluidas);
        });
        $asignaturas = array_values($asignaturas);

        // Profesor jefe
        $docente = $cursoDocenteModel->getDocenteDeCurso($curso['id'], $anio['id']);
        $docenteNombre = $docente
            ? trim(($docente['nombre'] ?? '') . ' ' . ($docente['ape_paterno'] ?? ''))
            : null;

        // Notas agrupadas por semestre y asignatura_id
        $notasSem = [1 => [], 2 => []];
        foreach ([1, 2] as $sem) {
            $rows = $this->notaModel->getByMatriculaAndSemestre($matriculaId, $sem);
            foreach ($rows as $n) {
                $notasSem[$sem][$n['asignatura_id']][] = $n;
            }
        }

        // Calcular máximo de notas por semestre (para columnas dinámicas)
        $maxCols = [1 => 0, 2 => 0];
        foreach ([1, 2] as $sem) {
            foreach ($notasSem[$sem] as $asigNotas) {
                $maxCols[$sem] = max($maxCols[$sem], count($asigNotas));
            }
            // Mínimo 1 columna para que la tabla no quede vacía
            if ($maxCols[$sem] === 0)
                $maxCols[$sem] = 1;
        }

        // Asistencia del alumno
        $todasAsist = $asistenciaModel->getAsistenciaCalendarioAlumno($matriculaId);
        $diasTrabajados = count($todasAsist);
        $diasInasistencia = count(array_filter($todasAsist, fn($v) => $v == 0));
        $diasPresente = $diasTrabajados - $diasInasistencia;
        $pctAsistencia = $diasTrabajados > 0
            ? round($diasPresente / $diasTrabajados * 100, 1)
            : null;

        $asistencia = [
            'dias_trabajados' => $diasTrabajados,
            'dias_inasistencia' => $diasInasistencia,
            'porcentaje' => $pctAsistencia,
        ];

        $logoPath = __DIR__ . '/../../public/img/LOGO CEIA.jpg';
        $observaciones = null;

        ob_start();
        require __DIR__ . '/../../views/reportes/notas/pdf_alumno.php';
        $html = ob_get_clean();

        $this->_streamPdf(
            $html,
            'letter',
            'portrait',
            'Informe_' . $alumno['apepat'] . '_' . $alumno['apemat'] . '_' . $anio['anio'] . '.pdf'
        );
    }

    /* ══════════════════════════════════════════════════════════
       PDF POR ASIGNATURA (todos los alumnos del curso en un ramo)
    ══════════════════════════════════════════════════════════ */
    public function pdfAsignatura(): void
    {
        $cursoId = (int) ($_GET['curso_id'] ?? 0);
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $asignaturaId = (int) ($_GET['asignatura_id'] ?? 0);
        $semestre = (int) ($_GET['semestre'] ?? 1);

        if (!$cursoId || !$anioId || !$asignaturaId)
            die('Faltan parámetros.');

        $cursoModel = new Cursos();
        $anioModel = new Anio();
        $asignaturaModel = new Asignaturas();

        $curso = $cursoModel->getById($cursoId);
        $anio = $anioModel->getById($anioId);
        $asignatura = $asignaturaModel->getById($asignaturaId);
        $asignaturasExcluidas = ['Inspectoría', 'Convivencia Escolar'];

        //Excluir las asignaturas
        if (in_array($asignatura['nombre'], $asignaturasExcluidas)) {
            die('Esta asignatura no está disponible en reportes.');
        }
        $alumnos = $this->notaModel->getByCursoYAnio($cursoId, $anioId);

        // Notas agrupadas por matricula_id
        $notasRaw = $this->notaModel->getNotasPorCursoAsignaturaSemestre(
            $cursoId,
            $anioId,
            $asignaturaId,
            $semestre
        );
        $notas = [];
        foreach ($notasRaw as $n) {
            $notas[$n['matricula_id']][] = $n;
        }

        // Columnas dinámicas: máximo de notas que tiene cualquier alumno
        $maxCols = 1;
        foreach ($notas as $ns) {
            $maxCols = max($maxCols, count($ns));
        }

        $logoPath = __DIR__ . '/../../public/img/LOGO CEIA.jpg';

        ob_start();
        require __DIR__ . '/../../views/reportes/notas/pdf_asignatura.php';
        $html = ob_get_clean();

        $nombreArchivo = 'Notas_' . $asignatura['nombre'] . '_'
            . $curso['nombre'] . '_Sem' . $semestre . '.pdf';

        $this->_streamPdf($html, 'A4', 'landscape', $nombreArchivo);
    }

    /* ══════════════════════════════════════════════════════════
       HELPER: renderizar y enviar PDF con Dompdf
    ══════════════════════════════════════════════════════════ */
    private function _streamPdf(
        string $html,
        string $paper,
        string $orientation,
        string $filename
    ): void {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }


}