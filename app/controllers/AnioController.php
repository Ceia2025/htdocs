<?php
require_once __DIR__ . '/../models/Anio.php';

class AnioController
{
    private $anioModel;

    public function __construct()
    {
        $this->anioModel = new Anio();
    }

    public function index()
    {
        $anios = $this->anioModel->getAll();
        require __DIR__ . '/../views/anio/index.php';
    }

    public function create($data)
    {
        if (!empty($data['anio'])) {
            $this->anioModel->create(
                $data['anio'],
                $data['descripcion'] ?? null,
                $data['sem1_inicio'] ?? null,
                $data['sem1_fin'] ?? null,
                $data['sem2_inicio'] ?? null,
                $data['sem2_fin'] ?? null
            );
        }
        header("Location: index.php?action=anios");
        exit;
    }

    public function edit($id)
    {
        $anio = $this->anioModel->getById($id);
        require __DIR__ . '/../views/anio/edit.php';
    }

    public function update($id, $data)
    {
        if (!empty($data['anio'])) {
            $this->anioModel->update(
                $id,
                $data['anio'],
                $data['descripcion'] ?? null,
                $data['sem1_inicio'] ?? null,
                $data['sem1_fin'] ?? null,
                $data['sem2_inicio'] ?? null,
                $data['sem2_fin'] ?? null
            );
        }
        header("Location: index.php?action=anios");
        exit;
    }

    public function delete($id)
    {
        $this->anioModel->delete($id);
        header("Location: index.php?action=anios");
        exit;
    }
}