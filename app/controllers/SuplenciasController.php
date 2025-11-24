<?php
require_once __DIR__ . '/../models/Suplencia.php';
require_once __DIR__ . '/../models/Profesor.php';

class SuplenciasController
{
    private $model;

    public function __construct()
    {
        $this->model = new Suplencia();
    }

    public function index()
    {
        $desde = $_GET['desde'] ?? date('Y-m-01');
        $hasta = $_GET['hasta'] ?? date('Y-m-t');

        $suplencias = $this->model->getByRangoFechas($desde, $hasta);
        require __DIR__ . '/../views/suplencias/index.php';
    }

    public function store($data)
    {
        $this->model->create($data);
        header("Location: index.php?action=suplencias");
        exit;
    }

    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=suplencias");
        exit;
    }
}
