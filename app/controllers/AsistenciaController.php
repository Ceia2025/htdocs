<?php
require_once __DIR__ . '/../models/Asistencia.php';

class AsistenciaController
{
    private $model;

    public function __construct()
    {
        $this->model = new Asistencia();
    }

    /* ==========================================
       FORMULARIO ASISTENCIA MASIVA
    ========================================== */
    public function formMasiva()
    {
        $cursoId = $_GET['curso_id'] ?? null;
        $anioId = $_GET['anio_id'] ?? null;
        $fecha = $_GET['fecha'] ?? date("Y-m-d");

        if (!$cursoId || !$anioId) {
            die("Faltan parámetros.");
        }

        if ($fecha > date("Y-m-d")) {
            die("No se puede registrar asistencia futura.");
        }

        $alumnos = $this->model->getAlumnosPorCurso($cursoId, $anioId);
        $curso = $this->model->getCurso($cursoId);

        require "../views/asistencia/masiva.php";
    }

    /* ==========================================
       RESUMEN CURSO
    ========================================== */
    public function resumenCurso()
    {
        $cursoId = $_GET['curso_id'] ?? null;
        $anioId = $_GET['anio_id'] ?? null;

        if (!$cursoId || !$anioId) {
            die("Faltan parámetros para el resumen.");
        }

        $inicio = date("Y-03-01");
        $fin = date("Y-m-d");

        $porcentaje = $this->model->porcentajeCurso(
            $cursoId,
            $anioId,
            $inicio,
            $fin
        );

        require "../views/asistencia/resumen_curso.php";
    }

    /* ==========================================
   LISTAR CURSOS PARA ASISTENCIA
========================================== */
    public function listarCursos()
    {
        $anioId = $_GET['anio_id'] ?? $this->model->getAnioActualId();

        if (!$anioId) {
            die("No existe año académico configurado.");
        }

        $anios = $this->model->getAnios();
        $cursos = $this->model->getCursosConMatricula($anioId);

        require "../views/asistencia/cursos.php";
    }

    /* ==========================================
       GUARDAR ASISTENCIA MASIVA
    ========================================== */
    public function guardarAsistenciaMasiva()
    {
        $cursoId = $_POST['curso_id'];
        $anioId = $_POST['anio_id'];
        $fecha = $_POST['fecha'];

        $presentes = $_POST['presentes'] ?? [];

        $alumnos = $this->model->getAlumnosPorCurso($cursoId, $anioId);

        foreach ($alumnos as $alumno) {

            $presente = in_array($alumno['matricula_id'], $presentes) ? 1 : 0;

            $this->model->guardarAsistencia(
                $alumno['matricula_id'],
                $fecha,
                $presente
            );
        }

        header("Location: index.php?action=asistencia_cursos&anio_id=$anioId");
    }



    /*
    LIBRO DE CLASS
    */

    public function libroClases()
    {

        $cursoId = $_GET['curso_id'];
        $anioId = $_GET['anio_id'];

        $curso = $this->model->getCurso($cursoId);

        $alumnos = $this->model->getAlumnosPorCurso($cursoId, $anioId);

        $asistencia = $this->model->getAsistenciaLibro($cursoId, $anioId);

        require "../views/asistencia/libro_clases.php";

    }
}