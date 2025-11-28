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
        // Asegurar que el RUN esté correctamente formateado
        if (!empty($data['run'])) {
            // Aceptar "12.345.678-9" o "123456789" y normalizar
            $data['run'] = strtoupper(trim($data['run']));
            $data['run'] = str_replace(' ', '', $data['run']);
        } else {
            header("Location: index.php?action=profesor_edit&id=$id&error=run_vacio");
            exit;
        }

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
