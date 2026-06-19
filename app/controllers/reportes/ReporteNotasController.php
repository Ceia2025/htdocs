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
       PANEL PRINCIPAL
    ══════════════════════════════════════════════════════════ */
    public function index(): void
    {

        $anioModel = new Anio();
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

            // Si el profesor no tiene cursos asignados, mostrar todos
            if (empty($cursos)) {
                $cursos = $cursoDocenteModel->getAllConDocente($anioId);
            }

            $esTodos = false;
        }

        require __DIR__ . '/../../views/reportes/notas/index.php';
    }

    /* ══════════════════════════════════════════════════════════
       PDF INDIVIDUAL DEL ALUMNO
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
        $asignaturas = array_values(array_filter(
            $asignaturas,
            fn($a) => !in_array($a['nombre'], $asignaturasExcluidas)
        ));

        $docente = $cursoDocenteModel->getDocenteDeCurso($curso['id'], $anio['id']);
        $docenteNombre = $docente
            ? trim(($docente['nombre'] ?? '') . ' ' . ($docente['ape_paterno'] ?? ''))
            : null;

        $notasSem = [1 => [], 2 => []];
        foreach ([1, 2] as $sem) {
            $rows = $this->notaModel->getByMatriculaAndSemestre($matriculaId, $sem);
            foreach ($rows as $n) {
                $notasSem[$sem][$n['asignatura_id']][] = $n;
            }
        }

        $maxCols = [1 => 0, 2 => 0];
        foreach ([1, 2] as $sem) {
            foreach ($notasSem[$sem] as $asigNotas) {
                $maxCols[$sem] = max($maxCols[$sem], count($asigNotas));
            }
            if ($maxCols[$sem] === 0)
                $maxCols[$sem] = 1;
        }

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
       PDF POR ASIGNATURA
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
        if (in_array($asignatura['nombre'], $asignaturasExcluidas))
            die('Esta asignatura no está disponible en reportes.');

        $alumnos = $this->notaModel->getByCursoYAnio($cursoId, $anioId);
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
       PDF CONSOLIDADO DE NOTAS
    ══════════════════════════════════════════════════════════ */
    public function pdfConsolidado(): void
    {
        $cursoId = (int) ($_GET['curso_id'] ?? 0);
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $semestre = (int) ($_GET['semestre'] ?? 1);

        if (!$cursoId || !$anioId)
            die('Faltan parámetros (curso_id, anio_id).');

        $cursoModel = new Cursos();
        $anioModel = new Anio();
        $cursoAsignaturaModel = new CursoAsignatura();

        $curso = $cursoModel->getById($cursoId);
        $anio = $anioModel->getById($anioId);

        if (!$curso || !$anio)
            die('Curso o año no encontrado.');

        $asignaturasExcluidas = ['Inspectoría', 'Convivencia Escolar'];
        $asignaturas = array_values(array_filter(
            $cursoAsignaturaModel->getAsignaturasPorCurso($cursoId),
            fn($a) => !in_array($a['nombre'], $asignaturasExcluidas)
        ));

        $alumnos = $this->notaModel->getByCursoYAnio($cursoId, $anioId);

        $notas = [];
        foreach ($asignaturas as $asig) {
            $asigId = $asig['id'];
            $rawNotas = $this->notaModel->getNotasPorCursoAsignaturaSemestre(
                $cursoId,
                $anioId,
                $asigId,
                $semestre
            );
            $notas[$asigId] = [];
            foreach ($rawNotas as $n) {
                $notas[$asigId][$n['matricula_id']][] = $n;
            }
        }

        $logoPath = __DIR__ . '/../../public/img/LOGO CEIA.jpg';

        ob_start();
        require __DIR__ . '/../../views/reportes/notas/pdfConsolidado.php';
        $html = ob_get_clean();

        $nombreArchivo = 'Consolidado_'
            . preg_replace('/\s+/', '_', $curso['nombre'])
            . '_Sem' . $semestre . '_' . $anio['anio'] . '.pdf';

        $this->_streamPdf($html, 'letter', 'portrait', $nombreArchivo);
    }

    /* ══════════════════════════════════════════════════════════
       API JSON RANKING
    ══════════════════════════════════════════════════════════ */
    public function apiRanking(): void
    {
        header('Content-Type: application/json');

        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $semestre = (int) ($_GET['semestre'] ?? 1);

        if (!$anioId) {
            echo json_encode(['error' => 'Falta anio_id']);
            exit;
        }

        $cursoAsignaturaModel = new CursoAsignatura();
        $cursoDocenteModel = new CursoDocente();
        $asignaturasExcluidas = ['Inspectoría', 'Convivencia Escolar'];

        $cursos = $cursoDocenteModel->getAllConDocente($anioId);

        $resultAlumnos = [];
        $resultAsignaturas = [];
        $pendientesAlumnos = [];  // [cursoNombre] => [matricula_id, ...]
        $pendientesAsignaturas = [];  // [{asignatura, curso}, ...]

        foreach ($cursos as $curso) {
            $cursoId = $curso['curso_id'];
            $cursoNombre = $curso['curso'] ?? $curso['curso_nombre'] ?? '—';

            $alumnos = $this->notaModel->getByCursoYAnio($cursoId, $anioId);
            $asignaturas = array_values(array_filter(
                $cursoAsignaturaModel->getAsignaturasPorCurso($cursoId),
                fn($a) => !in_array($a['nombre'], $asignaturasExcluidas)
            ));
            $totalAsigs = count($asignaturas);

            // Notas indexadas [asigId][matriculaId]
            $notas = [];
            foreach ($asignaturas as $asig) {
                $asigId = $asig['id'];
                $rawNotas = $this->notaModel->getNotasPorCursoAsignaturaSemestre(
                    $cursoId,
                    $anioId,
                    $asigId,
                    $semestre
                );
                $notas[$asigId] = [];
                foreach ($rawNotas as $n) {
                    $notas[$asigId][$n['matricula_id']][] = $n;
                }
            }

            // ── Promedio por alumno + detección de pendientes ────────────────
            $filaAlumnos = [];
            foreach ($alumnos as $alumno) {
                if (!empty($alumno['fecha_retiro']))
                    continue;
                $mid = $alumno['matricula_id'];
                $proms = [];

                foreach ($asignaturas as $asig) {
                    $ns = $notas[$asig['id']][$mid] ?? [];
                    if (count($ns) > 0) {
                        $proms[] = array_sum(array_column($ns, 'nota')) / count($ns);
                    }
                }

                $conNotas = count($proms);
                // Tiene al menos una nota pero no en todas las asignaturas → pendiente
                if ($conNotas > 0 && $conNotas < $totalAsigs) {
                    $pendientesAlumnos[$cursoNombre][] = $mid;
                }

                $promedio = $conNotas > 0 ? round(array_sum($proms) / $conNotas, 1) : null;

                $filaAlumnos[] = [
                    'matricula_id' => $mid,
                    'nombre' => $alumno['nombre'],
                    'apepat' => $alumno['apepat'],
                    'apemat' => $alumno['apemat'],
                    'promedio' => $promedio,
                    'pendiente' => $conNotas > 0 && $conNotas < $totalAsigs,
                ];
            }

            // Ordenar de menor a mayor (null al final)
            usort($filaAlumnos, function ($a, $b) {
                if ($a['promedio'] === null && $b['promedio'] === null)
                    return 0;
                if ($a['promedio'] === null)
                    return 1;
                if ($b['promedio'] === null)
                    return -1;
                return $a['promedio'] <=> $b['promedio'];
            });

            $resultAlumnos[$cursoNombre] = $filaAlumnos;

            // ── Promedio por asignatura + detección de pendientes ────────────
            $alumnosActivos = count(array_filter($alumnos, fn($a) => empty($a['fecha_retiro'])));

            foreach ($asignaturas as $asig) {
                $asigId = $asig['id'];
                $vals = [];

                foreach ($alumnos as $alumno) {
                    if (!empty($alumno['fecha_retiro']))
                        continue;
                    $ns = $notas[$asigId][$alumno['matricula_id']] ?? [];
                    if (count($ns) > 0) {
                        $vals[] = array_sum(array_column($ns, 'nota')) / count($ns);
                    }
                }

                $alumnosConNota = count($vals);
                // Hay alumnos con nota pero no todos tienen → pendiente
                if ($alumnosConNota > 0 && $alumnosConNota < $alumnosActivos) {
                    $pendientesAsignaturas[] = [
                        'asignatura' => $asig['nombre'],
                        'curso' => $cursoNombre,
                    ];
                }

                $promAsig = $alumnosConNota > 0 ? round(array_sum($vals) / $alumnosConNota, 1) : null;
                $resultAsignaturas[] = [
                    'asignatura' => $asig['nombre'],
                    'curso' => $cursoNombre,
                    'promedio' => $promAsig,
                ];
            }
        }

        // Ordenar asignaturas de menor a mayor (null al final)
        usort($resultAsignaturas, function ($a, $b) {
            if ($a['promedio'] === null && $b['promedio'] === null)
                return 0;
            if ($a['promedio'] === null)
                return 1;
            if ($b['promedio'] === null)
                return -1;
            return $a['promedio'] <=> $b['promedio'];
        });

        echo json_encode([
            'alumnos' => $resultAlumnos,
            'asignaturas' => $resultAsignaturas,
            'pendientes_alumnos' => $pendientesAlumnos,
            'pendientes_asignaturas' => $pendientesAsignaturas,
        ]);
        exit;
    }

    /* ══════════════════════════════════════════════════════════
       PDF RANKING
    ══════════════════════════════════════════════════════════ */
    public function pdfRanking(): void
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $semestre = (int) ($_GET['semestre'] ?? 1);
        $tipo = in_array($_GET['tipo'] ?? '', ['alumnos', 'asignaturas'])
            ? $_GET['tipo'] : 'alumnos';

        if (!$anioId)
            die('Falta anio_id.');

        $anioModel = new Anio();
        $cursoAsignaturaModel = new CursoAsignatura();
        $cursoDocenteModel = new CursoDocente();
        $asignaturasExcluidas = ['Inspectoría', 'Convivencia Escolar'];

        $anio = $anioModel->getById($anioId);
        $cursos = $cursoDocenteModel->getAllConDocente($anioId);

        $rankingAlumnos = [];
        $rankingAsignaturas = [];
        $pendientesAsignaturas = [];  // ← nuevo

        foreach ($cursos as $curso) {
            $cursoId = $curso['curso_id'];
            $cursoNombre = $curso['curso'] ?? $curso['curso_nombre'] ?? '—';

            $alumnos = $this->notaModel->getByCursoYAnio($cursoId, $anioId);
            $asignaturas = array_values(array_filter(
                $cursoAsignaturaModel->getAsignaturasPorCurso($cursoId),
                fn($a) => !in_array($a['nombre'], $asignaturasExcluidas)
            ));
            $totalAsigs = count($asignaturas);  // ← nuevo

            $notas = [];
            foreach ($asignaturas as $asig) {
                $asigId = $asig['id'];
                $rawNotas = $this->notaModel->getNotasPorCursoAsignaturaSemestre(
                    $cursoId,
                    $anioId,
                    $asigId,
                    $semestre
                );
                $notas[$asigId] = [];
                foreach ($rawNotas as $n) {
                    $notas[$asigId][$n['matricula_id']][] = $n;
                }
            }

            // Alumnos
            $filaAlumnos = [];
            $alumnosActivos = 0;
            foreach ($alumnos as $alumno) {
                if (!empty($alumno['fecha_retiro']))
                    continue;
                $alumnosActivos++;
                $mid = $alumno['matricula_id'];
                $proms = [];
                foreach ($asignaturas as $asig) {
                    $ns = $notas[$asig['id']][$mid] ?? [];
                    if (count($ns) > 0)
                        $proms[] = array_sum(array_column($ns, 'nota')) / count($ns);
                }
                $conNotas = count($proms);
                $filaAlumnos[] = [
                    'nombre' => $alumno['nombre'],
                    'apepat' => $alumno['apepat'],
                    'apemat' => $alumno['apemat'],
                    'promedio' => $conNotas > 0 ? round(array_sum($proms) / $conNotas, 1) : null,
                    'pendiente' => $conNotas > 0 && $conNotas < $totalAsigs,  // ← nuevo
                ];
            }
            usort($filaAlumnos, function ($a, $b) {
                if ($a['promedio'] === null)
                    return 1;
                if ($b['promedio'] === null)
                    return -1;
                return $a['promedio'] <=> $b['promedio'];
            });
            $rankingAlumnos[$cursoNombre] = $filaAlumnos;

            // Asignaturas
            foreach ($asignaturas as $asig) {
                $asigId = $asig['id'];
                $vals = [];
                foreach ($alumnos as $alumno) {
                    if (!empty($alumno['fecha_retiro']))
                        continue;
                    $ns = $notas[$asigId][$alumno['matricula_id']] ?? [];
                    if (count($ns) > 0)
                        $vals[] = array_sum(array_column($ns, 'nota')) / count($ns);
                }
                $alumnosConNota = count($vals);
                // ← nuevo: detectar pendiente
                if ($alumnosConNota > 0 && $alumnosConNota < $alumnosActivos) {
                    $pendientesAsignaturas[] = [
                        'asignatura' => $asig['nombre'],
                        'curso' => $cursoNombre,
                    ];
                }
                $rankingAsignaturas[] = [
                    'asignatura' => $asig['nombre'],
                    'curso' => $cursoNombre,
                    'promedio' => $alumnosConNota > 0 ? round(array_sum($vals) / $alumnosConNota, 1) : null,
                ];
            }
        }

        usort($rankingAsignaturas, function ($a, $b) {
            if ($a['promedio'] === null)
                return 1;
            if ($b['promedio'] === null)
                return -1;
            return $a['promedio'] <=> $b['promedio'];
        });

        ob_start();
        require __DIR__ . '/../../views/reportes/notas/pdf_ranking.php';
        $html = ob_get_clean();

        $nombreArchivo = 'Ranking_' . ucfirst($tipo) . '_'
            . ($anio['anio'] ?? '') . '_Sem' . $semestre . '.pdf';

        $this->_streamPdf($html, 'letter', 'portrait', $nombreArchivo);
    }

    /* ══════════════════════════════════════════════════════════
       HELPER PDF
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
    public function pdfRankingCursos()
    {
        require_once __DIR__ . '/../../models/Nota.php';
        require_once __DIR__ . '/../../models/Anio.php';

        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $semestre = (int) ($_GET['semestre'] ?? 1);

        if (!$anioId)
            die("Falta anio_id.");

        $notaModel = new Nota();
        $anioModel = new Anio();

        $cursos = $notaModel->getPromediosPorCurso($anioId, $semestre);
        $anioNombre = $anioModel->getById($anioId)['anio'] ?? $anioId;

        ob_start();
        require __DIR__ . '/../../views/reportes/notas/rankingCursos_pdf.php';
        $html = ob_get_clean();

        try {
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();

            header('Content-Type: application/pdf');
            $dompdf->stream("RankingCursos_{$anioNombre}_Sem{$semestre}.pdf", ['Attachment' => true]);
            exit;
        } catch (Exception $e) {
            die("Error al generar PDF: " . $e->getMessage());
        }
    }
}