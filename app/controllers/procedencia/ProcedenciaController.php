<?php
require_once __DIR__ . '/../../models/inventario/Procedencia.php';

class ProcedenciaController
{
    private $model;

    public function __construct()
    {
        $this->model = new Procedencia();
    }

    public function index()
    {
        $procedencias = $this->model->getAll();
        include __DIR__ . '/../../views/procedencia/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = $_POST['tipo'];
            $donador_fondo = $_POST['donador_fondo'];
            $fecha_adquisicion = $_POST['fecha_adquisicion'];

            $this->model->create($tipo, $donador_fondo, $fecha_adquisicion);
            header("Location: index.php?controller=procedencia&action=index");
            exit;
        }
        include __DIR__ . '/../../views/procedencia/create.php';
    }

    public function store($post)
    {
        $tipo = $post['tipo'];
        $donador_fondo = $post['donador_fondo'];
        $fecha_adquisicion = $post['fecha_adquisicion'];

        $this->model->create($tipo, $donador_fondo, $fecha_adquisicion);
        header("Location: index.php?controller=procedencia&action=procedencias");
        exit;
    }

    public function edit($id)
    {
        $procedencia = $this->model->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = $_POST['tipo'];
            $donador_fondo = $_POST['donador_fondo'];
            $fecha_adquisicion = $_POST['fecha_adquisicion'];

            $this->model->update($id, $tipo, $donador_fondo, $fecha_adquisicion);
            header("Location: index.php?controller=procedencia&action=index");
            exit;
        }
        include __DIR__ . '/../../views/procedencia/edit.php';
    }

    public function update($id, $post)
    {
        $tipo = $post['tipo'];
        $donador_fondo = $post['donador_fondo'];
        $fecha_adquisicion = $post['fecha_adquisicion'];

        $this->model->update($id, $tipo, $donador_fondo, $fecha_adquisicion);
        header("Location: index.php?controller=procedencia&action=procedencias");
        exit;
    }

    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?controller=procedencia&action=index");
        exit;
    }
}
