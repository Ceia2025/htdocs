<?php
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User();
    }

    // Mostrar formulario login
    public function login()
    {
        require __DIR__ . '/../views/login.php';
    }

    // Procesar login
    public function doLogin($data)
    {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                "id" => $user['id'],
                "nombre" => $user['nombre'],
                "email" => $user['email'],
                "rol_id" => $user['rol_id'],
                "rol" => $user['rol'] // 👈 aquí va el nombre del rol (viene del JOIN en findByEmail)
            ];
            header("Location: index.php?action=dashboard");
            exit;
        } else {
            $error = "Credenciales incorrectas";
            require __DIR__ . '/../views/login.php';
        }
    }


    // Verificar autenticación
    public function checkAuth()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }
    }

    // 👇 Nuevo: verificar si el usuario tiene rol específico
    public function requireRole($rolId)
    {
        $this->checkAuth(); // primero confirmo que esté logeado
        if ($_SESSION['user']['rol_id'] != $rolId) {
            die("🚫 Acceso denegado. No tienes permisos.");
        }
    }

    // Logout
    public function logout()
    {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }

    // Dashboard
    public function dashboard()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }
        $user = $_SESSION['user'];
        require __DIR__ . '/../views/dashboard.php';
    }
}
