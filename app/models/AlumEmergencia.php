<?php
require_once __DIR__ . '/../config/Connection.php';

class AlumEmergencia
{
    private $conn;
    private $table = "alum_emergencia2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener todos los contactos de un alumno
    public function findByAlumno($alumno_id)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE alumno_id = :alumno_id
                ORDER BY FIELD(tipo, 'padre_madre_tutor','apoderado','emergencia')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumno_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener contactos agrupados por tipo (útil para la vista del perfil y PDF)
    public function findByAlumnoAgrupado($alumno_id)
    {
        $todos = $this->findByAlumno($alumno_id);
        $grupos = [
            'padre_madre_tutor' => [],
            'apoderado'         => [],
            'emergencia'        => [],
        ];
        foreach ($todos as $c) {
            $grupos[$c['tipo']][] = $c;
        }
        return $grupos;
    }

    // Obtener un contacto por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un contacto nuevo
    public function create($alumno_id, $nombre, $telefono, $direccion, $relacion,
                           $tipo = 'emergencia', $run_contacto = null,
                           $email = null, $celular = null,
                           $comuna = null, $observacion = null)
    {
        $sql = "INSERT INTO {$this->table}
                    (alumno_id, tipo, nombre_contacto, run_contacto, telefono,
                     email, celular, direccion, comuna, relacion, observacion)
                VALUES
                    (:alumno_id, :tipo, :nombre_contacto, :run_contacto, :telefono,
                     :email, :celular, :direccion, :comuna, :relacion, :observacion)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':alumno_id'      => $alumno_id,
            ':tipo'           => $tipo,
            ':nombre_contacto'=> $nombre,
            ':run_contacto'   => $run_contacto,
            ':telefono'       => $telefono,
            ':email'          => $email,
            ':celular'        => $celular,
            ':direccion'      => $direccion,
            ':comuna'         => $comuna,
            ':relacion'       => $relacion,
            ':observacion'    => $observacion,
        ]);
    }

    // Crear desde array (útil para el stepper y formularios POST)
    public function createFromArray($alumno_id, array $data)
    {
        return $this->create(
            $alumno_id,
            $data['nombre_contacto'] ?? null,
            $data['telefono']        ?? null,
            $data['direccion']       ?? null,
            $data['relacion']        ?? null,
            $data['tipo']            ?? 'emergencia',
            $data['run_contacto']    ?? null,
            $data['email']           ?? null,
            $data['celular']         ?? null,
            $data['comuna']          ?? null,
            $data['observacion']     ?? null,
        );
    }

    // Actualizar un contacto
    public function update($id, array $data)
    {
        $sql = "UPDATE {$this->table} SET
                    tipo            = :tipo,
                    nombre_contacto = :nombre_contacto,
                    run_contacto    = :run_contacto,
                    telefono        = :telefono,
                    email           = :email,
                    celular         = :celular,
                    direccion       = :direccion,
                    comuna          = :comuna,
                    relacion        = :relacion,
                    observacion     = :observacion
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'             => $id,
            ':tipo'           => $data['tipo']            ?? 'emergencia',
            ':nombre_contacto'=> $data['nombre_contacto'] ?? null,
            ':run_contacto'   => $data['run_contacto']    ?? null,
            ':telefono'       => $data['telefono']        ?? null,
            ':email'          => $data['email']           ?? null,
            ':celular'        => $data['celular']         ?? null,
            ':direccion'      => $data['direccion']       ?? null,
            ':comuna'         => $data['comuna']          ?? null,
            ':relacion'       => $data['relacion']        ?? null,
            ':observacion'    => $data['observacion']     ?? null,
        ]);
    }

    // Eliminar un contacto
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Eliminar todos los contactos de un alumno
    public function deleteByAlumno($alumno_id)
    {
        $sql = "DELETE FROM {$this->table} WHERE alumno_id = :alumno_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':alumno_id' => $alumno_id]);
    }

    // Vínculos disponibles para selects
    public static function relacionesDisponibles(): array
    {
        return [
            'Madre', 'Padre', 'Hermano', 'Hermana',
            'Abuelo', 'Abuela', 'Tío', 'Tía',
            'Amigo', 'Amiga', 'Esposo', 'Esposa',
            'Apoderado', 'Apoderado Suplente',
            'Tutor', 'Tutor Legal', 'Representante',
        ];
    }
}