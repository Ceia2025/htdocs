<?php
require_once __DIR__ . '/../models/Matricula.php';
require_once __DIR__ . '/../models/Alumno.php';
require_once __DIR__ . '/../models/Cursos.php';
require_once __DIR__ . '/../models/Anio.php';
// (más adelante sumaremos modelos de notas, asistencia, etc.)

class PerfilAcademicoController
{
    public function show($matricula_id)
    {
        $matriculaModel = new Matricula();
        $alumnoModel = new Alumno();
        $cursoModel = new Cursos();
        $anioModel = new Anio();

        // Obtener matrícula completa
        $matricula = $matriculaModel->getById($matricula_id);
        if (!$matricula) {
            echo "<h2>No se encontró la matrícula</h2>";
            exit;
        }

        // Datos relacionados
        $alumno = $alumnoModel->getById($matricula['alumno_id']);
        $curso = $cursoModel->getById($matricula['curso_id']);
        $anio = $anioModel->getById($matricula['anio_id']);

        // Más adelante: cargar notas, asistencia, etc.
        $notas = []; 
        $asistencia = []; 
        $anotaciones = []; 
        $atrasos = [];

        require __DIR__ . '/../views/perfil_academico/show.php';
    }
}
