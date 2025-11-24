<?php
require_once __DIR__ . '/../models/Horario.php';
require_once __DIR__ . '/../models/ProfesorCursoAsignatura.php';

class HorariosController
{
    private $model;

    public function __construct()
    {
        $this->model = new Horario();
    }

    public function indexPorAsignacion($pca_id)
    {
        $horarios = $this->model->getByPca($pca_id);
        $pcaModel = new ProfesorCursoAsignatura();
        $asignacion = $pcaModel->getById($pca_id);

        require __DIR__ . '/../views/horarios/index.php';
    }

    public function store($data)
    {
        $this->model->create($data);
        header("Location: index.php?action=horarios_pca&pca_id=" . $data['pca_id']);
        exit;
    }

    public function delete($id, $pca_id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=horarios_pca&pca_id=" . $pca_id);
        exit;
    }
}
