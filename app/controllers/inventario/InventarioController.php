<?php
require_once __DIR__ . '/../../models/inventario/Inventario.php';
require_once __DIR__ . '/../../models/inventario/Movimiento.php';
require_once __DIR__ . '/../../models/inventario/Catalogo.php';

class InventarioController
{
    private $inventario;

    public function __construct()
    {
        $this->inventario = new Inventario();
    }

    // Catálogos que se mantienen (nivel, estado, lugar)
    private function catalogos()
    {
        return [
            'niveles' => (new Catalogo("nivel_educativo"))->getAll(),
            'estados' => (new Catalogo("estado_conservacion"))->getAll(),
            'lugares' => (new Catalogo("lugar_fisico"))->getAll(),
        ];
    }

    // Listado + filtros
    public function index()
    {
        // Solo consideramos filtros reales (ignorando 'action')
        $filtros = $_GET;
        unset($filtros['action']);

        $items = $this->inventario->getAgrupado($filtros);

        extract($this->catalogos());// $niveles, $estados, $lugares

        require_once __DIR__ . '/../../views/inventario/inventario_index.php';
    }

    // Formulario de creación
    public function create()
    {
        extract($this->catalogos());
        $tipos = $this->inventario->getTipos();
        require_once __DIR__ . '/../../views/inventario/inventario_create.php';
    }

    // Guardar nuevo bien
    // Guardar nuevo bien (soporta creación en lote para inventariables)
    public function store($post)
    {
        $naturaleza = $post['naturaleza'] ?? 'inventariable';
        $n = (int) ($post['cantidad_fichas'] ?? 1);

        if ($naturaleza === 'inventariable' && $n > 1) {
            $creados = $this->inventario->createLote($post, $n);
            header("Location: index.php?action=inventario_index&creados=$creados");
            exit;
        }

        $this->inventario->create($post);
        header("Location: index.php?action=inventario_index&creado=1");
        exit;
    }

    // AJAX: sugerir número + código a partir del nombre
    public function sugerirCodigo()
    {
        header('Content-Type: application/json; charset=utf-8');
        $nombre = $_GET['nombre'] ?? '';
        if (trim($nombre) === '') {
            echo json_encode(['numero' => 1, 'codigo_unico' => '']);
            exit;
        }
        echo json_encode($this->inventario->sugerirCodigo($nombre));
        exit;
    }
    public function tiposSugeridos()
    {
        header('Content-Type: application/json; charset=utf-8');
        $nombre = $_GET['nombre'] ?? null;
        echo json_encode($this->inventario->getTipos($nombre));
        exit;
    }

    // Formulario de edición
    public function edit($id)
    {
        $item = $this->inventario->getById($id);
        extract($this->catalogos());
        require_once __DIR__ . '/../../views/inventario/edit.php';
    }

    // Actualizar bien
    public function update($id, $post)
    {
        $this->inventario->update($id, $post);
        header("Location: index.php?action=inventario_index&actualizado=1");
        exit;
    }

    // Soft delete
    public function delete($id)
    {
        $this->inventario->delete($id);
        header("Location: index.php?action=inventario_index&eliminado=1");
        exit;
    }

    // Restaurar
    public function restore($id)
    {
        $this->inventario->restore($id);
        header("Location: index.php?action=inventario_index&restaurado=1");
        exit;
    }

    // Historial de movimientos de un bien
    public function movimientos($id)
    {
        $item = $this->inventario->getById($id);
        if (!$item) {
            header("Location: index.php?action=inventario_index");
            exit;
        }

        $movModel = new Movimiento();
        $movimientos = $movModel->getByInventario($id);

        $lugares = (new Catalogo("lugar_fisico"))->getAll();

        require_once __DIR__ . '/../../views/inventario/movimientos.php';
    }

    // Registrar un movimiento (cambio de lugar / observación con fecha)
    public function registrarMovimiento($post)
    {
        $inventarioId = $post['inventario_id'] ?? null;
        $lugarDestino = $post['lugar_destino_id'] ?? null;

        if (!$inventarioId) {
            header("Location: index.php?action=inventario_index");
            exit;
        }

        $item = $this->inventario->getById($inventarioId);
        $lugarOrigen = $item['lugar_id'] ?? null;

        $movModel = new Movimiento();
        $movModel->create([
            'inventario_id' => $inventarioId,
            'lugar_origen_id' => $lugarOrigen,
            'lugar_destino_id' => $lugarDestino ?: $lugarOrigen,
            'observacion' => $post['observacion'] ?? null,
            'fecha' => $post['fecha'] ?? date('Y-m-d'),
            'usuario_id' => $_SESSION['user']['id'] ?? null,
        ]);

        // Si cambió de lugar, actualizamos el lugar actual del bien
        if (!empty($lugarDestino) && $lugarDestino != $lugarOrigen) {
            $this->inventario->cambiarLugar($inventarioId, $lugarDestino);
        }

        header("Location: index.php?action=inventario_movimientos&id=$inventarioId&mov=1");
        exit;
    }

    // Exportar a Excel (mismos filtros que el index)
    public function exportExcel()
    {
        $filtros = $_GET;
        unset($filtros['action']);

        $items = !empty($filtros)
            ? $this->inventario->filter($_GET)
            : $this->inventario->getAll();

        $filename = "INVENTARIO_" . date('Y-m-d') . ".xls";

        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>N°</th>
                <th>Naturaleza</th>
                <th>Cantidad</th>
                <th>Nivel Educativo</th>
                <th>Estado</th>
                <th>Lugar Físico</th>
                <th>Observaciones</th>
                <th>Fecha Registro</th>
              </tr>";

        foreach ($items as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['codigo_unico']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tipo'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['numero'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['naturaleza']) . "</td>";
            echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nivel_educativo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
            echo "<td>" . htmlspecialchars($row['lugar']) . "</td>";
            echo "<td>" . htmlspecialchars($row['observaciones'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['fecha_registro'] ?? '') . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        exit;
    }

    public function detalleGrupo()
    {
        $nombre = $_GET['nombre'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        $lugarId = $_GET['lugar_id'] ?? null;

        $items = $this->inventario->getGrupo($nombre, $tipo, $lugarId);
        extract($this->catalogos()); // niveles, estados, lugares (para el modal)

        require_once __DIR__ . '/../../views/inventario/detalle_grupo.php';
    }
}