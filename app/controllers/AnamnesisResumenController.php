<?php
require_once __DIR__ . '/../models/ResumenAnamnesis.php';
require_once __DIR__ . '/../models/Alumno.php';
require_once __DIR__ . '/../models/Anio.php';

class AnamnesisResumenController
{
    private $model;

    public function __construct()
    {
        $this->model = new ResumenAnamnesis();
    }

    public function formAnamnesis()
    {
        $alumnoId = $_GET['alumno_id'] ?? null;
        $anioId   = $_GET['anio_id'] ?? null;

        if (!$alumnoId) die("Falta alumno_id.");

        $alumnoModel = new Alumno();
        $anioModel   = new Anio();

        $alumno   = $alumnoModel->getById($alumnoId);
        $anios    = $anioModel->getAll();

        // Si no viene anio_id, usar el más reciente
        if (!$anioId && !empty($anios)) {
            $anioId = $anios[0]['id'];
        }

        $anamnesis = $this->model->getByAlumnoYAnio($alumnoId, $anioId);
        $historial = $this->model->getAllByAlumno($alumnoId);

        // Determinar si el alumno es mayor de edad
        $esMayor = !empty($alumno['fechanac']) 
            ? (new DateTime())->diff(new DateTime($alumno['fechanac']))->y >= 18
            : false;

        require __DIR__ . '/../views/anamnesis/form.php';
    }

    public function guardar()
    {
        $alumnoId = $_POST['alumno_id'] ?? null;
        $anioId   = $_POST['anio_id'] ?? null;

        if (!$alumnoId || !$anioId) {
            header("Location: index.php?action=alumnos");
            exit;
        }

        $this->model->save([
            'alumno_id'    => $alumnoId,
            'anio_id'      => $anioId,
            'realizado_por' => $_POST['realizado_por'] ?? '',
            'relacion'     => $_POST['relacion'] ?? null,
            'observaciones' => $_POST['observaciones'] ?? null,
            'created_by'   => $_SESSION['user']['id'] ?? null,
        ]);

        header("Location: index.php?action=anamnesis_form&alumno_id=$alumnoId&anio_id=$anioId&guardado=1");
        exit;
    }
}