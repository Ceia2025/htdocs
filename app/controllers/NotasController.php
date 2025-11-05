<?php
require_once __DIR__ . '/../models/Nota.php';
require_once __DIR__ . '/../models/Asignaturas.php';
require_once __DIR__ . '/../models/Matricula.php';

class NotasController
{
    private $model;

    public function __construct()
    {
        $this->model = new Nota();
    }

    // Listado general
    public function index()
    {
        $notas = $this->model->getAll();
        require __DIR__ . '/../views/notas/index.php';
    }

    // Formulario de ingreso masivo
    public function createGroup($curso_id, $anio_id)
    {
        $alumnos = $this->model->getByCursoYAnio($curso_id, $anio_id);

        $asignaturasModel = new Asignaturas();
        $asignaturas = $asignaturasModel->getAll();

        require __DIR__ . '/../views/notas/createGroup.php';
    }

    // Guardar notas en grupo
    public function storeGroup($curso_id, $anio_id, $data)
    {
        $semestre = intval($data['semestre'] ?? 1);

        $this->model->createMultiple(
            $curso_id,
            $anio_id,
            $data['asignatura_id'],
            $data['fecha'],
            $data['notas'],
            $semestre
        );

        header("Location: index.php?action=perfil_curso&id=$curso_id&anio_id=$anio_id&semestre=$semestre");
        exit;
    }

    // Perfil académico
    public function indexProfile($matricula_id)
    {
        $semestre = intval($_GET['semestre'] ?? 1);
        $notas = $this->model->getByMatriculaAndSemestre($matricula_id, $semestre);
        require __DIR__ . '/../views/notas/indexProfile.php';
    }

    // Editar nota
    public function edit($id)
    {
        $nota = $this->model->getById($id);

        $asignaturasModel = new Asignaturas();
        $asignaturas = $asignaturasModel->getAll();

        require __DIR__ . '/../views/notas/edit.php';
    }

    // Actualizar
    public function update($id, $data)
    {
        $val = floatval($data['nota']);
        if ($val < 1.0 || $val > 7.0) {
            echo "<script>alert('La nota debe estar entre 1.0 y 7.0'); window.history.back();</script>";
            exit;
        }

        $data['semestre'] = intval($data['semestre'] ?? 1);
        $this->model->update($id, $data);

        header("Location: index.php?action=notas");
        exit;
    }

    // Eliminar
    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=notas");
        exit;
    }
}
