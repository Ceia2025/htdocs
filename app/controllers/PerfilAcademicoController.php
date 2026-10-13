<?php
require_once __DIR__ . '/../models/Matricula.php';
require_once __DIR__ . '/../models/Alumno.php';
require_once __DIR__ . '/../models/Cursos.php';
require_once __DIR__ . '/../models/Anio.php';
require_once __DIR__ . '/../models/CursoAsignatura.php';
require_once __DIR__ . '/../models/Asignaturas.php';
require_once __DIR__ . '/../models/Nota.php';
require_once __DIR__ . '/../libs/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class PerfilAcademicoController
{
    public function show($matricula_id)
    {
        $matriculaModel = new Matricula();
        $alumnoModel = new Alumno();
        $cursoModel = new Cursos();
        $anioModel = new Anio();
        $cursoAsignaturaModel = new CursoAsignatura();
        $notaModel = new Nota();

        // Obtener matrícula
        $matricula = $matriculaModel->getById($matricula_id);
        if (!$matricula) {
            echo "<h2>No se encontró la matrícula</h2>";
            exit;
        }

        // Obtener datos relacionados
        $alumno = $alumnoModel->getById($matricula['alumno_id']);
        $curso = $cursoModel->getById($matricula['curso_id']);
        $anio = $anioModel->getById($matricula['anio_id']);

        // Obtener asignaturas del curso
        $asignaturas = $cursoAsignaturaModel->getAsignaturasPorCurso($curso['id']);

        // Determinar semestre actual automáticamente
        $mesActual = intval(date('n')); // mes actual en número (1–12)
        $semestreActual = null;
        $estadoSemestre = ''; // texto informativo: 'abierto', 'cerrado', etc.

        if ($mesActual >= 3 && $mesActual <= 7) {
            // Marzo a Julio → 1er semestre activo
            $semestreActual = 1;
            $estadoSemestre = 'Primer semestre en curso';
        } elseif ($mesActual >= 8 && $mesActual <= 12) {
            // Agosto a Diciembre → 2° semestre activo
            $semestreActual = 2;
            $estadoSemestre = 'Segundo semestre en curso';
        } else {
            // Enero y Febrero → fuera de rango académico
            $semestreActual = 2; // normalmente termina en diciembre
            $estadoSemestre = 'Fuera de plazo (semestre cerrado)';
        }

        // Si el usuario selecciona otro semestre manualmente (por GET)
        $semestreSeleccionado = intval($_GET['semestre'] ?? $semestreActual);

        // Obtener notas del alumno según semestre
        $notas = $notaModel->getByMatriculaAndSemestre($matricula_id, $semestreSeleccionado);

        $asistenciaModel = new Asistencia();
        $asistenciaMap = $asistenciaModel->getAsistenciaCalendarioAlumno((int) $matricula_id);

        // Anotaciones del alumno
        $anotacionModel = new Anotacion();
        $anotaciones = $anotacionModel->getByMatricula($matricula_id, null); // null = ambos semestres

        // Resumen de anotaciones
        $resumenAnotaciones = [
            'total' => count($anotaciones),
            'positivas' => count(array_filter($anotaciones, fn($a) => $a['tipo'] === 'Positiva')),
            'leves' => count(array_filter($anotaciones, fn($a) => $a['tipo'] === 'Leve')),
            'graves' => count(array_filter($anotaciones, fn($a) => $a['tipo'] === 'Grave')),
            'gravisimas' => count(array_filter($anotaciones, fn($a) => $a['tipo'] === 'Gravísima')),
            'registros' => count(array_filter($anotaciones, fn($a) => $a['tipo'] === 'Registro')),
        ];

        // Atrasos del alumno (ambos semestres)
        require_once __DIR__ . '/../models/Atraso.php';
        $atrasoModel = new Atraso();
        $atrasos = $atrasoModel->getByMatricula((int) $matricula_id);

        // Asistencia para el calendario
        $asistenciaModel = new Asistencia();
        $asistenciaMap = $asistenciaModel->getAsistenciaCalendarioAlumno((int) $matricula_id);

        require __DIR__ . '/../views/perfil_academico/show.php';


    }

    /* ==========================================
       PDF DEL PERFIL ACADÉMICO (secciones elegidas)
    ========================================== */
    public function pdf($matricula_id, $secciones = [])
    {
        $matricula_id = (int) $matricula_id;
        $permitidas = ['notas', 'asistencia', 'anotaciones', 'atrasos'];
        $secciones = array_values(array_intersect($permitidas, (array) $secciones));

        if (!$matricula_id || empty($secciones)) {
            die("Faltan parámetros: matrícula o secciones.");
        }

        $matriculaModel = new Matricula();
        $alumnoModel = new Alumno();
        $cursoModel = new Cursos();
        $anioModel = new Anio();

        $matricula = $matriculaModel->getById($matricula_id);
        if (!$matricula) {
            die("No se encontró la matrícula.");
        }

        $alumno = $alumnoModel->getById($matricula['alumno_id']);
        $curso = $cursoModel->getById($matricula['curso_id']);
        $anio = $anioModel->getById($matricula['anio_id']);

        // ── Notas (ambos semestres) ──
        $notasPorSemestre = [];
        if (in_array('notas', $secciones, true)) {
            $notaModel = new Nota();
            $todas = $notaModel->getByMatricula($matricula_id);

            foreach ([1, 2] as $sem) {
                $fechasPorAsig = $notaModel->getFechasPorAsignaturaCurso(
                    (int) $matricula['curso_id'],
                    (int) $matricula['anio_id'],
                    $sem
                );

                $porAsig = [];
                foreach ($todas as $n) {
                    if ((int) $n['semestre'] !== $sem) {
                        continue;
                    }
                    $id = (int) $n['asignatura_id'];
                    $porAsig[$id]['nombre'] = $n['asignatura_nombre'];
                    $porAsig[$id]['notas'][] = $n;
                }

                $filas = [];
                foreach ($porAsig as $id => $datos) {
                    $notasAsig = $datos['notas'];
                    usort($notasAsig, fn($a, $b) => [$a['fecha'], $a['id']] <=> [$b['fecha'], $b['id']]);

                    $esperadas = count($fechasPorAsig[$id] ?? []);
                    $filas[] = [
                        'nombre' => $datos['nombre'],
                        'notas' => $notasAsig,
                        'esperadas' => $esperadas,
                        'promedio' => Nota::promedioCompleto($notasAsig, $esperadas),
                    ];
                }
                usort($filas, fn($a, $b) => strcmp($a['nombre'], $b['nombre']));

                $promedios = array_values(array_filter(
                    array_column($filas, 'promedio'),
                    fn($p) => $p !== null
                ));

                $notasPorSemestre[$sem] = [
                    'asignaturas' => $filas,
                    'promedio_general' => Nota::promedio($promedios),
                ];
            }
        }

        // ── Asistencia ──
        $resumenAsistencia = null;
        $asistenciaDiaria = [];
        if (in_array('asistencia', $secciones, true)) {
            $asistenciaModel = new Asistencia();
            $mapa = $asistenciaModel->getAsistenciaCalendarioAlumno($matricula_id);

            $presentes = 0;
            $ausentes = 0;
            $fechasAusente = [];

            foreach ($mapa as $fecha => $valor) {
                if (is_array($valor)) {
                    $valor = $valor['presente'] ?? null;
                }
                if ($valor === null) {
                    continue;
                }

                $fechaIso = date('Y-m-d', strtotime($fecha));

                if ((int) $valor === 1) {
                    $presentes++;
                    $asistenciaDiaria[$fechaIso] = 'presente';
                } else {
                    $ausentes++;
                    $fechasAusente[] = $fechaIso;
                    $asistenciaDiaria[$fechaIso] = 'ausente';
                }
            }
            sort($fechasAusente);
            ksort($asistenciaDiaria);

            $totalClases = $presentes + $ausentes;
            $resumenAsistencia = [
                'total' => $totalClases,
                'presentes' => $presentes,
                'ausentes' => $ausentes,
                'pct' => $totalClases > 0 ? round($presentes / $totalClases * 100, 1) : null,
                'fechas_ausente' => $fechasAusente,
            ];
        }

        // ── Anotaciones ──
        $anotaciones = [];
        $resumenAnotaciones = [];
        if (in_array('anotaciones', $secciones, true)) {
            $anotacionModel = new Anotacion();
            $anotaciones = $anotacionModel->getByMatricula($matricula_id, null);

            $resumenAnotaciones = [
                'total' => count($anotaciones),
                'positivas' => count(array_filter($anotaciones, fn($a) => $a['tipo'] === 'Positiva')),
                'leves' => count(array_filter($anotaciones, fn($a) => $a['tipo'] === 'Leve')),
                'graves' => count(array_filter($anotaciones, fn($a) => $a['tipo'] === 'Grave')),
                'gravisimas' => count(array_filter($anotaciones, fn($a) => $a['tipo'] === 'Gravísima')),
                'registros' => count(array_filter($anotaciones, fn($a) => $a['tipo'] === 'Registro')),
            ];
        }

        // ── Atrasos ──
        $atrasos = [];
        $resumenAtrasos = [];
        if (in_array('atrasos', $secciones, true)) {
            require_once __DIR__ . '/../models/Atraso.php';
            $atrasoModel = new Atraso();
            $atrasos = $atrasoModel->getByMatricula($matricula_id);

            $justificados = count(array_filter($atrasos, fn($a) => (int) $a['justificado'] === 1));
            $resumenAtrasos = [
                'total' => count($atrasos),
                'justificados' => $justificados,
                'injustificados' => count($atrasos) - $justificados,
                'sem1' => count(array_filter($atrasos, fn($a) => (int) $a['semestre'] === 1)),
                'sem2' => count(array_filter($atrasos, fn($a) => (int) $a['semestre'] === 2)),
            ];
        }

        ob_start();
        require __DIR__ . '/../views/perfil_academico/perfil_pdf.php';
        $html = ob_get_clean();

        if (ob_get_length()) {
            ob_end_clean();
        }

        try {
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();

            $apellidos = preg_replace('/[^\p{L}\p{N}_-]/u', '', str_replace(' ', '_', $alumno['apepat'] . '_' . $alumno['apemat']));
            header('Content-Type: application/pdf');
            $dompdf->stream("Perfil_Academico_{$apellidos}.pdf", ["Attachment" => true]);
            exit;
        } catch (Exception $e) {
            die("Error al generar PDF: " . $e->getMessage());
        }
    }
}