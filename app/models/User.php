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
    public function create($nombre, $email, $password, $rol)
    {
        $sql = "INSERT INTO $this->table (nombre, email, password, rol_id)
                VALUES (:nombre, :email, :password, :rol_id)";
        $stmt = $this->conn->prepare($sql);
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        return $stmt->execute([
            ":nombre" => $nombre,
            ":email" => $email,
            ":password" => $hashed,
            ":rol_id" => $rol
        ]);
    }

    // Listar todos
    public function getAll(): array
    {
        $sql = "
            SELECT 
                u.id,
                u.username,
                u.nombre,
                u.email,
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
    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT u.*, r.nombre AS rol 
            FROM usuarios2 u
            JOIN roles2 r ON r.id = u.rol_id
            WHERE u.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    

    //Obtener por Email
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios2 WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
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
