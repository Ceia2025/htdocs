<?php
require_once __DIR__ . '/../../config/Connection.php';

class Inventario
{
    private $conn;
    private $table = "inventario";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Listado completo (solo activos)
    public function getAll()
    {
        $sql = "SELECT 
                    inv.id, inv.nombre, inv.tipo, inv.numero, inv.codigo_unico,
                    inv.naturaleza, inv.cantidad, inv.observaciones, inv.fecha_registro,
                    inv.nivel_id, inv.estado_id, inv.lugar_id, inv.procedencia_id,
                    ne.nombre AS nivel_educativo,
                    ec.nombre AS estado,
                    lf.nombre AS lugar,
                    p.tipo AS proc_tipo, p.donador_fondo AS proc_fondo, p.fecha_adquisicion AS proc_fecha
                FROM {$this->table} inv
                JOIN nivel_educativo    ne ON inv.nivel_id  = ne.id
                JOIN estado_conservacion ec ON inv.estado_id = ec.id
                JOIN lugar_fisico       lf ON inv.lugar_id  = lf.id
                LEFT JOIN procedencia   p  ON inv.procedencia_id = p.id
                WHERE inv.deleted_at IS NULL
                ORDER BY inv.nombre ASC, inv.numero ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listado con filtros (usado por exportExcel)
    public function filter($filters)
    {
        $conditions = ["inv.deleted_at IS NULL"];
        $params = [];

        if (!empty($filters['busqueda'])) {
            $conditions[] = "(
                LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(inv.nombre,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u'))
                    LIKE LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(:busqueda,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u'))
                OR LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(inv.tipo,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u'))
                    LIKE LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(:busqueda,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u'))
                OR UPPER(inv.codigo_unico) LIKE UPPER(:busqueda)
            )";
            $params[':busqueda'] = '%' . $filters['busqueda'] . '%';
        }
        if (!empty($filters['nivel_id'])) {
            $conditions[] = "inv.nivel_id = :nivel_id";
            $params[':nivel_id'] = $filters['nivel_id'];
        }
        if (!empty($filters['naturaleza'])) {
            $conditions[] = "inv.naturaleza = :naturaleza";
            $params[':naturaleza'] = $filters['naturaleza'];
        }
        if (!empty($filters['estado_id'])) {
            $conditions[] = "inv.estado_id = :estado_id";
            $params[':estado_id'] = $filters['estado_id'];
        }
        if (!empty($filters['lugar_id'])) {
            $conditions[] = "inv.lugar_id = :lugar_id";
            $params[':lugar_id'] = $filters['lugar_id'];
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);

        $sql = "SELECT 
                    inv.id, inv.nombre, inv.tipo, inv.numero, inv.codigo_unico,
                    inv.naturaleza, inv.cantidad, inv.observaciones, inv.fecha_registro,
                    inv.nivel_id, inv.estado_id, inv.lugar_id, inv.procedencia_id,
                    ne.nombre AS nivel_educativo,
                    ec.nombre AS estado,
                    lf.nombre AS lugar,
                    p.tipo AS proc_tipo, p.donador_fondo AS proc_fondo, p.fecha_adquisicion AS proc_fecha
                FROM {$this->table} inv
                JOIN nivel_educativo    ne ON inv.nivel_id  = ne.id
                JOIN estado_conservacion ec ON inv.estado_id = ec.id
                JOIN lugar_fisico       lf ON inv.lugar_id  = lf.id
                LEFT JOIN procedencia   p  ON inv.procedencia_id = p.id
                $where
                ORDER BY inv.nombre ASC, inv.numero ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Resumen agrupado por nombre + tipo + lugar (para el index)
    public function getAgrupado($filters = [])
    {
        $conditions = ["inv.deleted_at IS NULL"];
        $params = [];

        if (!empty($filters['busqueda'])) {
            $conditions[] = "(
                LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(inv.nombre,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u'))
                    LIKE LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(:busqueda,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u'))
                OR LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(inv.tipo,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u'))
                    LIKE LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(:busqueda,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u'))
                OR UPPER(inv.codigo_unico) LIKE UPPER(:busqueda)
            )";
            $params[':busqueda'] = '%' . $filters['busqueda'] . '%';
        }
        if (!empty($filters['nivel_id'])) {
            $conditions[] = "inv.nivel_id = :nivel_id";
            $params[':nivel_id'] = $filters['nivel_id'];
        }
        if (!empty($filters['naturaleza'])) {
            $conditions[] = "inv.naturaleza = :naturaleza";
            $params[':naturaleza'] = $filters['naturaleza'];
        }
        if (!empty($filters['estado_id'])) {
            $conditions[] = "inv.estado_id = :estado_id";
            $params[':estado_id'] = $filters['estado_id'];
        }
        if (!empty($filters['lugar_id'])) {
            $conditions[] = "inv.lugar_id = :lugar_id";
            $params[':lugar_id'] = $filters['lugar_id'];
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);

        $sql = "SELECT
                    inv.nombre, inv.tipo, inv.naturaleza, inv.lugar_id,
                    lf.nombre AS lugar,
                    COUNT(*)               AS unidades,
                    SUM(inv.cantidad)      AS stock_total,
                    MIN(ne.nombre)         AS nivel,
                    COUNT(DISTINCT inv.nivel_id)  AS niveles_distintos,
                    MIN(ec.nombre)         AS estado,
                    COUNT(DISTINCT inv.estado_id) AS estados_distintos
                FROM {$this->table} inv
                JOIN nivel_educativo    ne ON inv.nivel_id  = ne.id
                JOIN estado_conservacion ec ON inv.estado_id = ec.id
                JOIN lugar_fisico       lf ON inv.lugar_id  = lf.id
                $where
                GROUP BY inv.nombre, inv.tipo, inv.naturaleza, inv.lugar_id, lf.nombre
                ORDER BY lf.nombre ASC, inv.nombre ASC, inv.tipo ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Items individuales de un grupo (para el detalle)
    public function getGrupo($nombre, $tipo, $lugarId)
    {
        $tipoVacio = ($tipo === null || $tipo === '');
        $sql = "SELECT 
                    inv.id, inv.nombre, inv.tipo, inv.numero, inv.codigo_unico,
                    inv.naturaleza, inv.cantidad, inv.observaciones, inv.fecha_registro,
                    inv.nivel_id, inv.estado_id, inv.lugar_id, inv.procedencia_id,
                    ne.nombre AS nivel_educativo,
                    ec.nombre AS estado,
                    lf.nombre AS lugar,
                    p.tipo AS proc_tipo, p.donador_fondo AS proc_fondo, p.fecha_adquisicion AS proc_fecha
                FROM {$this->table} inv
                JOIN nivel_educativo    ne ON inv.nivel_id  = ne.id
                JOIN estado_conservacion ec ON inv.estado_id = ec.id
                JOIN lugar_fisico       lf ON inv.lugar_id  = lf.id
                LEFT JOIN procedencia   p  ON inv.procedencia_id = p.id
                WHERE inv.deleted_at IS NULL
                  AND LOWER(inv.nombre) = LOWER(:nombre)
                  AND inv.lugar_id = :lugar_id
                  AND " . ($tipoVacio ? "(inv.tipo IS NULL OR inv.tipo = '')" : "inv.tipo = :tipo") . "
                ORDER BY inv.numero ASC, inv.id ASC";
        $params = [':nombre' => $nombre, ':lugar_id' => $lugarId];
        if (!$tipoVacio)
            $params[':tipo'] = $tipo;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Un bien por ID (con procedencia)
    public function getById($id)
    {
        $sql = "SELECT 
                    inv.*,
                    ne.nombre AS nivel_educativo,
                    ec.nombre AS estado,
                    lf.nombre AS lugar,
                    p.tipo AS proc_tipo, p.donador_fondo AS proc_fondo, p.fecha_adquisicion AS proc_fecha
                FROM {$this->table} inv
                JOIN nivel_educativo    ne ON inv.nivel_id  = ne.id
                JOIN estado_conservacion ec ON inv.estado_id = ec.id
                JOIN lugar_fisico       lf ON inv.lugar_id  = lf.id
                LEFT JOIN procedencia   p  ON inv.procedencia_id = p.id
                WHERE inv.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Siguiente numero correlativo para un nombre
    public function getNextNumero($nombre)
    {
        $sql = "SELECT MAX(numero) AS max_num FROM {$this->table} WHERE LOWER(nombre) = LOWER(:nombre)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":nombre" => trim($nombre)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row['max_num'] ?? 0) + 1;
    }

    // Prefijo limpio (sin acentos ni espacios) a partir del nombre
    private function prefijo($nombre)
    {
        $nombre = trim($nombre);
        $buscar = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', ' '];
        $reemplazo = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', ''];
        $limpio = str_replace($buscar, $reemplazo, $nombre);
        $limpio = preg_replace('/[^A-Za-z0-9]/', '', $limpio);
        return strtoupper(substr($limpio, 0, 12));
    }

    // Sugerencia de codigo (AJAX): NOMBRE-001
    public function sugerirCodigo($nombre)
    {
        $numero = $this->getNextNumero($nombre);
        return [
            'numero' => $numero,
            'codigo_unico' => $this->prefijo($nombre) . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT),
        ];
    }

    // Tipos ya existentes (autocompletar)
    public function getTipos($nombre = null)
    {
        if (!empty($nombre)) {
            $sql = "SELECT DISTINCT tipo FROM {$this->table}
                    WHERE tipo IS NOT NULL AND tipo <> '' AND LOWER(nombre) = LOWER(:nombre) AND deleted_at IS NULL
                    ORDER BY tipo ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':nombre' => trim($nombre)]);
        } else {
            $sql = "SELECT DISTINCT tipo FROM {$this->table}
                    WHERE tipo IS NOT NULL AND tipo <> '' AND deleted_at IS NULL
                    ORDER BY tipo ASC";
            $stmt = $this->conn->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Crear bien (con procedencia opcional)
    public function create($data)
    {
        $numero = !empty($data['numero'])
            ? (int) $data['numero']
            : $this->getNextNumero($data['nombre']);

        $codigo = !empty($data['codigo_unico'])
            ? trim($data['codigo_unico'])
            : $this->prefijo($data['nombre']) . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO {$this->table}
                (nombre, tipo, numero, codigo_unico, naturaleza, cantidad,
                 nivel_id, estado_id, lugar_id, procedencia_id, observaciones, fecha_registro)
                VALUES
                (:nombre, :tipo, :numero, :codigo_unico, :naturaleza, :cantidad,
                 :nivel_id, :estado_id, :lugar_id, :procedencia_id, :observaciones, :fecha_registro)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":nombre" => trim($data['nombre']),
            ":tipo" => !empty($data['tipo']) ? trim($data['tipo']) : null,
            ":numero" => $numero,
            ":codigo_unico" => $codigo,
            ":naturaleza" => $data['naturaleza'] ?? 'inventariable',
            ":cantidad" => !empty($data['cantidad']) ? (int) $data['cantidad'] : 1,
            ":nivel_id" => $data['nivel_id'],
            ":estado_id" => $data['estado_id'],
            ":lugar_id" => $data['lugar_id'],
            ":procedencia_id" => !empty($data['procedencia_id']) ? $data['procedencia_id'] : null,
            ":observaciones" => !empty($data['observaciones']) ? trim($data['observaciones']) : null,
            ":fecha_registro" => !empty($data['fecha_registro']) ? $data['fecha_registro'] : date('Y-m-d'),
        ]);
    }

    // Actualizar bien (con procedencia)
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    nombre = :nombre,
                    tipo = :tipo,
                    numero = :numero,
                    codigo_unico = :codigo_unico,
                    naturaleza = :naturaleza,
                    cantidad = :cantidad,
                    nivel_id = :nivel_id,
                    estado_id = :estado_id,
                    lugar_id = :lugar_id,
                    procedencia_id = :procedencia_id,
                    observaciones = :observaciones,
                    fecha_registro = :fecha_registro
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":nombre" => trim($data['nombre']),
            ":tipo" => !empty($data['tipo']) ? trim($data['tipo']) : null,
            ":numero" => $data['numero'] !== '' ? (int) $data['numero'] : null,
            ":codigo_unico" => trim($data['codigo_unico']),
            ":naturaleza" => $data['naturaleza'] ?? 'inventariable',
            ":cantidad" => !empty($data['cantidad']) ? (int) $data['cantidad'] : 1,
            ":nivel_id" => $data['nivel_id'],
            ":estado_id" => $data['estado_id'],
            ":lugar_id" => $data['lugar_id'],
            ":procedencia_id" => !empty($data['procedencia_id']) ? $data['procedencia_id'] : null,
            ":observaciones" => !empty($data['observaciones']) ? trim($data['observaciones']) : null,
            ":fecha_registro" => !empty($data['fecha_registro']) ? $data['fecha_registro'] : null,
        ]);
    }

    // Crear varias fichas correlativas (hereda procedencia del $data)
    public function createLote($data, $n)
    {
        $data['numero'] = null;
        $data['codigo_unico'] = null;
        $data['naturaleza'] = $data['naturaleza'] ?? 'inventariable';
        $data['cantidad'] = 1;

        $creados = 0;
        $total = max(1, (int) $n);
        for ($i = 0; $i < $total; $i++) {
            if ($this->create($data))
                $creados++;
        }
        return $creados;
    }

    // Cambiar de lugar (para movimientos)
    public function cambiarLugar($id, $lugarDestinoId)
    {
        $sql = "UPDATE {$this->table} SET lugar_id = :lugar WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":lugar" => $lugarDestinoId, ":id" => $id]);
    }

    // Soft delete
    public function delete($id)
    {
        $sql = "UPDATE {$this->table} SET deleted_at = :hoy WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":hoy" => date('Y-m-d'), ":id" => $id]);
    }

    public function restore($id)
    {
        $sql = "UPDATE {$this->table} SET deleted_at = NULL WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    public function codigoExiste($codigo, $exceptId = null)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE codigo_unico = :codigo";
        $params = [":codigo" => trim($codigo)];
        if ($exceptId) {
            $sql .= " AND id <> :id";
            $params[":id"] = $exceptId;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    // Listado para exportar: ordenado por lugar fisico
    public function getParaExportar($filters = [])
    {
        $items = !empty($filters) ? $this->filter($filters) : $this->getAll();

        usort($items, function ($a, $b) {
            $cmp = strcasecmp($a['lugar'], $b['lugar']);
            if ($cmp !== 0)
                return $cmp;
            $cmp = strcasecmp($a['nombre'], $b['nombre']);
            if ($cmp !== 0)
                return $cmp;
            return ((int) $a['numero']) <=> ((int) $b['numero']);
        });

        return $items;
    }
}