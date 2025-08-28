<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/RolesController.php';
require_once __DIR__ . '/../controllers/AnioController.php';
require_once __DIR__ . '/../controllers/CursoController.php';

$action = $_GET['action'] ?? 'login';
$auth = new AuthController();
$userController = new UserController();
$rolesController = new RolesController();
$anioController = new AnioController();
$cursosController = new CursosController();

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

    // CRUD Años
    case 'anios':
        $anioController->index();
        break;
    case 'anio_create':
        require __DIR__ . '/../views/anio/create.php';
        break;
    case 'anio_store':
        $anioController->create($_POST);
        break;
    case 'anio_edit':
        $anioController->edit($_GET['id']);
        break;
    case 'anio_update':
        $anioController->update($_GET['id'], $_POST);
        break;
    case 'anio_delete':
        $anioController->delete($_GET['id']);
        break;


    // CRUD Cursos
    case 'cursos':
        $cursosController->index();
        break;
    case 'curso_create':
        $cursosController->create();
        break;
    case 'curso_store':
        $cursosController->store($_POST);
        break;
    case 'curso_edit':
        $cursosController->edit($_GET['id']);
        break;
    case 'curso_update':
        $cursosController->update($_GET['id'], $_POST);
        break;
    case 'curso_delete':
        $cursosController->delete($_GET['id']);
        break;


    default:
        echo "Ruta no encontrada";
}
