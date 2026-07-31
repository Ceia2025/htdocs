<?php
require_once __DIR__ . '/../models/User.php';

class RecuperarController
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new User();
    }

    // Normaliza el RUN: quita puntos, guion y espacios; K en mayúscula.
    // Así "12.345.678-9", "12345678-9" y "123456789" se comparan igual.
    private function normalizarRun($run)
    {
        return strtoupper(str_replace(['.', '-', ' '], '', trim((string) $run)));
    }

    // ── PASO 1: mostrar formulario de identificación ─────────
    public function index()
    {
        require __DIR__ . '/../views/recuperar.php';
    }

    // ── PASO 1: procesar identificación (usuario/email + RUN) ─
    public function check($data)
    {
        $login = trim($data['login'] ?? '');
        $run = $data['run'] ?? '';

        $user = $this->model->findByLogin($login);

        // Respuesta genérica: no revelamos si el usuario existe o si falló el RUN
        if (!$user || $this->normalizarRun($run) !== $this->normalizarRun($user['run'])) {
            $error = "Los datos no coinciden. Verifica tu usuario/correo y tu RUN.";
            require __DIR__ . '/../views/recuperar.php';
            return;
        }

        // Identidad verificada → habilitamos el Paso 2 con un permiso temporal
        $_SESSION['reset_ok'] = [
            'user_id' => (int) $user['id'],
            'expira' => time() + 600,   // 10 minutos de validez
        ];

        header("Location: index.php?action=recuperar_nueva");
        exit;
    }

    // ── PASO 2: mostrar formulario de nueva contraseña ───────
    public function nueva()
    {
        if (!$this->resetHabilitado()) {
            header("Location: index.php?action=recuperar");
            exit;
        }
        require __DIR__ . '/../views/recuperar_nueva.php';
    }

    // ── PASO 2: guardar la nueva contraseña ──────────────────
    public function guardar($data)
    {
        if (!$this->resetHabilitado()) {
            header("Location: index.php?action=recuperar");
            exit;
        }

        $pass1 = $data['password'] ?? '';
        $pass2 = $data['password2'] ?? '';

        if (strlen($pass1) < 6) {
            $error = "La contraseña debe tener al menos 6 caracteres.";
            require __DIR__ . '/../views/recuperar_nueva.php';
            return;
        }
        if ($pass1 !== $pass2) {
            $error = "Las contraseñas no coinciden.";
            require __DIR__ . '/../views/recuperar_nueva.php';
            return;
        }

        $userId = $_SESSION['reset_ok']['user_id'];
        $this->model->updatePassword($userId, $pass1);

        // Consumimos el permiso: un solo uso
        unset($_SESSION['reset_ok']);

        $_SESSION['reset_exito'] = "Tu contraseña fue restablecida. Ya puedes iniciar sesión.";
        header("Location: index.php?action=login");
        exit;
    }

    // Valida que exista un permiso de reset vigente (no caducado)
    private function resetHabilitado(): bool
    {
        if (empty($_SESSION['reset_ok'])) {
            return false;
        }
        if (time() > ($_SESSION['reset_ok']['expira'] ?? 0)) {
            unset($_SESSION['reset_ok']);
            return false;
        }
        return true;
    }
}