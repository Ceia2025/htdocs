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


    // Verificar permiso para una action
public function checkPermiso(string $action): void
{
    // Actions públicas no requieren sesión
    if (in_array($action, ['login', 'doLogin'])) {
        return;
    }

    // Verificar sesión
    $this->checkAuth();

    $permisos = require __DIR__ . '/../config/roles.php';

    // Si la action no está en el mapa o es null → solo necesita estar autenticado
    if (!array_key_exists($action, $permisos) || $permisos[$action] === null) {
        return;
    }

    $rolId = (int)($_SESSION['user']['rol_id'] ?? 0);

    if (!in_array($rolId, $permisos[$action])) {
        $_SESSION['error_acceso'] = "No tienes permisos para acceder a esa sección.";
        header("Location: index.php?action=dashboard");
        exit;
    }
}

// Helper estático para usar en vistas (ocultar botones)
public static function puede(string $action): bool
{
    if (!isset($_SESSION['user'])) return false;

    $permisos = require __DIR__ . '/../config/roles.php';

    if (!array_key_exists($action, $permisos) || $permisos[$action] === null) {
        return isset($_SESSION['user']);
    }

    $rolId = (int)($_SESSION['user']['rol_id'] ?? 0);
    return in_array($rolId, $permisos[$action]);
}
}
