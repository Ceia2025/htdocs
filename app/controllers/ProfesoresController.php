<?php
require_once __DIR__ . '/../models/Profesor.php';

class ProfesoresController
{
    private $model;

    public function __construct()
    {
        $this->model = new Profesor();
    }

    public function index()
    {
        $profesores = $this->model->getAll();
        require __DIR__ . '/../views/profesores/index.php';
    }

    public function create()
    {
        require __DIR__ . '/../views/profesores/create.php';
    }

    public function store($data)
    {
        $this->model->create($data);
        header("Location: index.php?action=profesores");
        exit;
    }

    public function edit($id)
    {
        $profesor = $this->model->getById($id);
        require __DIR__ . '/../views/profesores/edit.php';
    }

    public function update($id, $data)
    {
        $this->model->update($id, $data);
        header("Location: index.php?action=profesores");
        exit;
    }

    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=profesores");
        exit;
    }
}
