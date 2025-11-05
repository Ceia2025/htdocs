<?php
require_once __DIR__ . '/../models/Matricula.php';
require_once __DIR__ . '/../models/Alumno.php';
require_once __DIR__ . '/../models/Cursos.php';
require_once __DIR__ . '/../models/Anio.php';
require_once __DIR__ . '/../models/CursoAsignatura.php';
require_once __DIR__ . '/../models/Asignaturas.php';
require_once __DIR__ . '/../models/Nota.php';

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

        // Enviar a la vista
        require __DIR__ . '/../views/perfil_academico/show.php';
    }
}
