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
        $auth->logout(); //Cierra la seción
        break;
    case 'dashboard':
        $auth->dashboard(); //Redirije al Dashboard
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


    // CRUD Roles
    case 'roles':
        $rolesController->index();
        break;
    case 'createRole':
        $rolesController->create($_POST);
        break;
    case 'editRole':
        $rolesController->edit($_GET['id']);
        break;
    case 'updateRole':
        $rolesController->update($_GET['id'], $_POST);
        break;
    case 'deleteRole':
        $rolesController->delete($_GET['id']);
        break;

    default:
        echo "Ruta no encontrada";
}
