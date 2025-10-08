<?php
require_once __DIR__ . '/../../models/inventario/Inventario.php';
require_once __DIR__ . '/../../models/inventario/Catalogo.php';
require_once __DIR__ . '/../../models/inventario/Procedencia.php';
require_once __DIR__ . '/../../models/inventario/Individualizacion.php';

class InventarioController
{
    public function index()
    {
        $inventario = new Inventario();

        // Si hay filtros en GET, los aplicamos
        if (!empty($_GET)) {
            $items = $inventario->filter($_GET);
        } else {
            $items = $inventario->getAll();
        }

        // Contador de registros para el inventario
        $totalRegistros = count($items);
        $totalObjetos = array_reduce($items, function ($carry, $item) {
            return $carry + (int) ($item['cantidad'] ?? 0);
        }, 0);

        // Catálogos para los select
        $nivelesObj = new Catalogo("nivel_educativo");
        $niveles = $nivelesObj->getAll();

        $categorizacionesObj = new Catalogo("categorizacion");
        $categorizaciones = $categorizacionesObj->getAll();

        $estadosObj = new Catalogo("estado_conservacion");
        $estados = $estadosObj->getAll();

        $lugaresObj = new Catalogo("lugar_fisico");
        $lugares = $lugaresObj->getAll();

        require_once __DIR__ . '/../../views/inventario/index.php';
    }

    public function create()
    {
        $nivelesObj = new Catalogo("nivel_educativo");
        $niveles = $nivelesObj->getAll();

        $categorizacionesObj = new Catalogo("categorizacion");
        $categorizaciones = $categorizacionesObj->getAll();

        $estadosObj = new Catalogo("estado_conservacion");
        $estados = $estadosObj->getAll();

        $lugaresObj = new Catalogo("lugar_fisico");
        $lugares = $lugaresObj->getAll();

        $procedenciasObj = new Procedencia();
        $procedencias = $procedenciasObj->getAll();

        $individualizacionesObj = new Individualizacion();
        $individualizaciones = $individualizacionesObj->getAll();

        require_once __DIR__ . '/../../views/inventario/create.php';
    }

    public function store($post)
    {
        $inventario = new Inventario();
        $data = [
            "nivel_id" => $post['nivel_id'],
            "individualizacion_id" => $post['individualizacion_id'],
            "categorizacion_id" => $post['categorizacion_id'],
            "cantidad" => $post['cantidad'],
            "estado_id" => $post['estado_id'],
            "lugar_id" => $post['lugar_id'],
            "procedencia_id" => $post['procedencia_id'],
        ];
        $inventario->create($data);
        header("Location: index.php?action=inventario_index");
    }

    public function edit($id)
    {
        $inventario = new Inventario();
        $item = $inventario->getById($id);

        $nivelesObj = new Catalogo("nivel_educativo");
        $niveles = $nivelesObj->getAll();

        $categorizacionesObj = new Catalogo("categorizacion");
        $categorizaciones = $categorizacionesObj->getAll();

        $estadosObj = new Catalogo("estado_conservacion");
        $estados = $estadosObj->getAll();

        $lugaresObj = new Catalogo("lugar_fisico");
        $lugares = $lugaresObj->getAll();

        $procedenciasObj = new Procedencia();
        $procedencias = $procedenciasObj->getAll();

        $individualizacionesObj = new Individualizacion();
        $individualizaciones = $individualizacionesObj->getAll();

        require_once __DIR__ . '/../../views/inventario/edit.php';
    }

    public function update($id, $post)
    {
        $inventario = new Inventario();
        $data = [
            "nivel_id" => $post['nivel_id'],
            "individualizacion_id" => $post['individualizacion_id'],
            "categorizacion_id" => $post['categorizacion_id'],
            "cantidad" => $post['cantidad'],
            "estado_id" => $post['estado_id'],
            "lugar_id" => $post['lugar_id'],
            "procedencia_id" => $post['procedencia_id'],
        ];
        $inventario->update($id, $data);
        header("Location: index.php?action=inventario_index");
    }

    public function delete2($id)
    {
        $inventario = new Inventario();
        if ($inventario->delete($id)) {
            header("Location: index.php?action=inventario_index");
            exit;
        } else {
            echo "Error al eliminar el inventario.";
        }
    }
    public function delete($id)
    {
        $inventario = new Inventario();
        $inventario->delete($id);
        header("Location: index.php?action=inventario_index");
        exit;
    }
}
