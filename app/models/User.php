<?php
require_once __DIR__ . '/../config/Connection.php';

class User
{
    private $conn;
    private $table = "usuarios2"; // 👈 tu tabla

    public function __construct()
    {
        $database = new Connection();
        $this->conn = $database->open();
    }

    // Crear usuario
    public function create(
        $username,
        $password,
        $rol_id,
        $nombre,
        $ape_paterno,
        $ape_materno,
        $run,
        $email,
        $telefono
    ) {
        $sql = "INSERT INTO $this->table (
                username, password, rol_id, nombre, ape_paterno, ape_materno, run, email, numero_telefonico
            ) VALUES (
                :username, :password, :rol_id, :nombre, :ape_paterno, :ape_materno, :run, :email, :telefono
            )";

        $stmt = $this->conn->prepare($sql);
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        return $stmt->execute([
            ":username" => $username,
            ":password" => $hashed,
            ":rol_id" => $rol_id,
            ":nombre" => $nombre,
            ":ape_paterno" => $ape_paterno,
            ":ape_materno" => $ape_materno,
            ":run" => $run,
            ":email" => $email,
            ":telefono" => $telefono,
        ]);
    }

    // Obtener todos getAll
    public function getAll(): array
    {
        $sql = "
            SELECT 
                u.id,
                u.username,
                u.nombre,
                u.ape_paterno,
                u.ape_materno,
                u.run,
                u.email,
                u.numero_telefonico,
                u.rol_id,
                r.nombre AS rol
            FROM usuarios2 u
            JOIN roles2 r ON r.id = u.rol_id
            ORDER BY u.id
        ";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener uno
    public function getById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Obtener por ID
    public function findById($id)
    {
        $sql = "
            SELECT u.*, r.nombre AS rol
            FROM usuarios2 u
            JOIN roles2 r ON r.id = u.rol_id
            WHERE u.id = :id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    //Obtener por Email
    public function findByEmail($email)
    {
        $sql = "
        SELECT u.*, r.nombre AS rol
        FROM usuarios2 u
        JOIN roles2 r ON r.id = u.rol_id
        WHERE u.email = :email
        LIMIT 1
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Actualizar
    public function update($id, $nombre, $email, $rol, $password = null)
    {
        if ($password) {
            $sql = "UPDATE $this->table SET nombre=:nombre, email=:email, rol_id=:rol, password=:password WHERE id=:id";
            $stmt = $this->conn->prepare($sql);
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            return $stmt->execute([
                ":id" => $id,
                ":nombre" => $nombre,
                ":email" => $email,
                ":rol_id" => $rol,
                ":password" => $hashed
            ]);
        } else {
            $sql = "UPDATE $this->table SET nombre=:nombre, email=:email, rol_id=:rol WHERE id=:id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ":id" => $id,
                ":nombre" => $nombre,
                ":email" => $email,
                ":rol" => $rol
            ]);
        }
    }

    // Eliminar
    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
