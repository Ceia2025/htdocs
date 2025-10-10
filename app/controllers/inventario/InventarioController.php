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

    public function showLugarGrupo($lugar, $codigo_general)
    {
        $inventario = new Inventario();
        $items = $inventario->getByLugarYCodigo($lugar, $codigo_general);

        require_once __DIR__ . '/../../views/inventario/show_lugar_grupo.php';
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

    public function delete($id)
    {
        $inventario = new Inventario();
        $inventario->delete($id);
        header("Location: index.php?action=inventario_index");
        exit;
    }

    // Exportando a exel, primera prueba
    public function exportExcel()
{
    // Incluimos el modelo
    $inventario = new Inventario();

    // Aplicar los mismos filtros que el index
    if (!empty($_GET)) {
        $items = $inventario->filter($_GET);
    } else {
        $items = $inventario->getAll();
    }

    // Definir nombre del archivo
    $filename = "INVENTARIOMUEBLES_" . date('Y-m-d') . ".xls";

    // Enviar cabeceras para forzar descarga como Excel
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Imprimir encabezados según formato oficial
    echo "<table border='1'>";
    echo "<tr>
            <th>Nivel educativo</th>
            <th>Individualización del bien</th>
            <th>Categorización</th>
            <th>Cantidad</th>
            <th>Estado de conservación</th>
            <th>Lugar físico</th>
            <th>Procedencia: Inversión o donación</th>
            <th>Procedencia: Donador o fondo de adquisición</th>
            <th>Procedencia: Fecha de adquisición</th>
          </tr>";

    // Imprimir los datos
    foreach ($items as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nivel_educativo']) . "</td>";
        echo "<td>" . htmlspecialchars($row['individualizacion']) . "</td>";
        echo "<td>" . htmlspecialchars($row['categorizacion']) . "</td>";
        echo "<td>" . htmlspecialchars($row['cantidad_total']) . "</td>";
        echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
        echo "<td>" . htmlspecialchars($row['lugar']) . "</td>";
        echo "<td>" . htmlspecialchars($row['procedencia_tipo']) . "</td>";
        echo "<td>" . htmlspecialchars($row['donador_fondo']) . "</td>";
        echo "<td>" . htmlspecialchars($row['fecha_adquisicion']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    exit;
}
}
