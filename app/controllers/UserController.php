<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../config/Connection.php';
//require __DIR__ . '/../views/users/index.php';

class UserController
{
    private $model;
    private $conn;

    public function __construct()
    {
        $this->model = new User(); //clase Connection
        $database = new Connection();   // usamos tu clase Connection
        $this->conn = $database->open(); // abrimos la conexión
        //$this->model = new User($this->conn);
    }

    // Mostrar lista
    public function index()
    {
        $users = $this->model->getAll();
        require __DIR__ . '/../views/users/index.php';
    }

    // Mostrar formulario de crear
    public function create()
    {
        $roleModel = new Role();
        $roles = $roleModel->getAll();

        require __DIR__ . '/../views/users/create.php';
    }

    // Guardar nuevo
    public function store($data)
    {
        if (empty($data['rol_id'])) {
            die("Debes seleccionar un rol válido.");
        }

        $this->model->create(
            $data['username'],
            $data['password'],
            (int) $data['rol_id'], // Aseguramos que sea número
            $data['nombre'],
            $data['ape_paterno'],
            $data['ape_materno'],
            $data['run'],
            $data['email'],
            $data['telefono']
        );
        header("Location: index.php?action=users");
    }

    // Mostrar formulario editar
    public function edit($id)
    {
        $user = $this->model->getById($id);
        $roleModel = new Role();
        $roles = $roleModel->getAll();

        require __DIR__ . '/../views/users/edit.php';
    }

    // Guardar cambios
    public function update($id, $data)
    {
        try {
            if (!empty($data['password'])) {
                $sql = "UPDATE usuarios2 
                    SET username = :username,
                        nombre = :nombre,
                        ape_paterno = :ape_paterno,
                        ape_materno = :ape_materno,
                        run = :run,
                        numero_telefonico = :telefono,
                        email = :email,
                        rol_id = :rol_id,
                        password = :password
                    WHERE id = :id";
            } else {
                $sql = "UPDATE usuarios2 
                    SET username = :username,
                        nombre = :nombre,
                        ape_paterno = :ape_paterno,
                        ape_materno = :ape_materno,
                        run = :run,
                        numero_telefonico = :telefono,
                        email = :email,
                        rol_id = :rol_id
                    WHERE id = :id";
            }

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':ape_paterno', $data['ape_paterno']);
            $stmt->bindParam(':ape_materno', $data['ape_materno']);
            $stmt->bindParam(':run', $data['run']);
            $stmt->bindParam(':telefono', $data['telefono']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':rol_id', $data['rol_id'], PDO::PARAM_INT);

            if (!empty($data['password'])) {
                $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
                $stmt->bindParam(':password', $hashedPassword);
            }

            $stmt->execute();

            header("Location: index.php?action=users");
            exit;
        } catch (PDOException $e) {
            die("Error al actualizar usuario: " . $e->getMessage());
        }
    }




    // Eliminar
    public function destroy($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=users");
    }
}
