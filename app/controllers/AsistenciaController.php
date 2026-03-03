<?php

require_once __DIR__ . '/../models/Asistencia.php';

class AsistenciaController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new Asistencia($db);
    }

    /* ==========================================
       FORMULARIO ASISTENCIA MASIVA
    ========================================== */
    public function formMasiva()
    {
        $cursoId = $_GET['curso_id'] ?? null;
        $anioId  = $_GET['anio_id'] ?? null;
        $fecha   = $_GET['fecha'] ?? date("Y-m-d");

        if (!$cursoId || !$anioId) {
            die("Faltan parámetros.");
        }

        if ($fecha > date("Y-m-d")) {
            die("No se puede registrar asistencia futura.");
        }

        $alumnos = $this->model->getAlumnosPorCurso($cursoId, $anioId);

        require "../views/asistencia/masiva.php";
    }

    /* ==========================================
       RESUMEN CURSO
    ========================================== */
    public function resumenCurso($cursoId, $anioId)
    {
        $inicio = date("Y-03-01");
        $fin    = date("Y-m-d");

        $porcentaje = $this->model->porcentajeCurso(
            $cursoId,
            $anioId,
            $inicio,
            $fin
        );

        require "../views/asistencia/resumen_curso.php";
    }

    /* ==========================================
       GUARDAR MASIVA
    ========================================== */
    public function guardarMasiva()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Acceso inválido.");
        }

        $cursoId     = $_POST['curso_id'];
        $anioId      = $_POST['anio_id'];
        $fecha       = $_POST['fecha'];
        $asistencias = $_POST['asistencia'] ?? [];

        if ($fecha > date("Y-m-d")) {
            die("No se puede guardar asistencia futura.");
        }

        $this->model->guardarAsistenciaMasiva(
            $cursoId,
            $anioId,
            $fecha,
            $asistencias
        );

        header("Location: index.php?action=form_asistencia_masiva&curso_id={$cursoId}&anio_id={$anioId}&fecha={$fecha}");
        exit;
    }
}