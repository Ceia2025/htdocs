<?php
require_once __DIR__ . '/../../models/inventario/Individualizacion.php';

class CategorizacionController
{
    private $categorizacionModel;

    public function __construct() {
        $this->categorizacionModel = new Individualizacion(); // Modelo actualizado
    }

    // Listar todas las categorizaciones (antes individualizaciones)
    public function index() {
        $categorizaciones = $this->categorizacionModel->getAll();
        require __DIR__ . '/../../views/categorizacion/index.php';
    }

    // Crear nueva categorización
    public function create($data) {
        $nombre = $data['nombre'];
        $codigo_general = $data['codigo_general'];
        $codigo_especifico = $data['codigo_especifico'] ?? null;

        $result = $this->categorizacionModel->create($nombre, $codigo_general, $codigo_especifico);

        if ($result === "DUPLICATE") {
            header("Location: index.php?action=categorizacion_create&error=duplicado");
            exit;
        }

        header("Location: index.php?action=categorizaciones");
        exit;
    }

    // Editar categorización por ID
    public function edit($id) {
        $categorizacion = $this->categorizacionModel->getById($id);
        require __DIR__ . '/../../views/categorizacion/edit.php';
    }

    // Actualizar categorización
    public function update($id, $data) {
        $nombre = $data['nombre'];
        $codigo_general = $data['codigo_general'];
        $codigo_especifico = $data['codigo_especifico'];

        $result = $this->categorizacionModel->update($id, $nombre, $codigo_general, $codigo_especifico);

        if ($result === "DUPLICATE") {
            header("Location: index.php?action=categorizacion_edit&id=$id&error=duplicado");
            exit;
        }

        header("Location: index.php?action=categorizaciones");
        exit;
    }

    // Eliminar categorización
    public function delete($id) {
        $this->categorizacionModel->delete($id);
        header("Location: index.php?action=categorizaciones");
        exit;
    }
}
