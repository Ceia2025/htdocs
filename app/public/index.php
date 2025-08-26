<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';

$action = $_GET['action'] ?? 'login';
$auth = new AuthController();
$userController = new UserController();

switch ($action) {
    case 'login':
        $auth->login(); // muestra formulario
        break;
    case 'doLogin':
        $auth->doLogin($_POST); // procesa POST
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'dashboard':
        $auth->dashboard();
        break;

    // CRUD usuarios
    case 'users':
        $userController->index();
        break;
    case 'user_create':
        $userController->create();
        break;
    case 'user_store':
        $userController->store($_POST);
        break;
    case 'user_edit':
        $userController->edit($_GET['id']);
        break;
    case 'user_update':
        $userController->update($_GET['id'], $_POST);
        break;
    case 'user_delete':
        $userController->destroy($_GET['id']);
        break;

    default:
        echo "Ruta no encontrada";
}
