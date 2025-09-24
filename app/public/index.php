<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/RolesController.php';
require_once __DIR__ . '/../controllers/AnioController.php';
require_once __DIR__ . '/../controllers/CursoController.php';
require_once __DIR__ . '/../controllers/AlumnoController.php';
require_once __DIR__ . '/../controllers/AsignaturasController.php';
require_once __DIR__ . '/../controllers/CursoAsignaturaController.php';
require_once __DIR__ . '/../controllers/AlumEmergenciaController.php';


$action = $_GET['action'] ?? 'login';
$auth = new AuthController();
$userController = new UserController();
$rolesController = new RolesController();
$anioController = new AnioController();
$cursosController = new CursosController();
$alumnosController = new AlumnosController();
$asignaturas = new AsignaturasController();
$cursoAsignaruta = new CursoAsignaturaController();
$alumnoemergencia = new AlumEmergenciaController();



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



    // CRUD Alumnos
    case 'alumnos':
        $alumnosController->index();
        break;
    case 'alumno_create':
        $alumnosController->create();
        break;
    case 'alumno_store':
        $alumnosController->store($_POST);
        break;
    case 'alumno_edit':
        $alumnosController->edit($_GET['id']);
        break;
    case 'alumno_update':
        $alumnosController->update($_GET['id'], $_POST);
        break;
    case 'alumno_delete':
        $alumnosController->delete($_GET['id']);
        break;
    case 'alumno_profile':
        $alumnosController->profile($_GET['id']);
        break;
    case 'alumno_search':
        $controller = new AlumnosController();
        $controller->search();
        break;

    case 'asignaturas':
        $asignaturas = new AsignaturasController();
        $asignaturas->index();
        break;

    case 'asignatura_create':
        $asignaturas = new AsignaturasController();
        $asignaturas->create();
        break;

    case 'asignatura_store':
        $asignaturas = new AsignaturasController();
        $asignaturas->store($_POST);
        break;

    case 'asignatura_edit':
        $asignaturas = new AsignaturasController();
        $asignaturas->edit($_GET['id']);
        break;

    case 'asignatura_update':
        $asignaturas = new AsignaturasController();
        $asignaturas->update($_GET['id'], $_POST);
        break;

    case 'asignatura_delete':
        $asignaturas = new AsignaturasController();
        $asignaturas->delete($_GET['id']);
        break;


    case 'curso_asignaturas':
        $cursoAsignaruta = new CursoAsignaturaController();
        $cursoAsignaruta->index();
        break;

    case 'curso_asignaturas_create':
        $cursoAsignaruta = new CursoAsignaturaController();
        $cursoAsignaruta->create();
        break;

    case 'curso_asignaturas_store':
        $cursoAsignaruta = new CursoAsignaturaController();
        $cursoAsignaruta->store($_POST);
        break;

    case 'curso_asignaturas_edit':
        if (!isset($_GET['id'])) {
            die("Falta el ID de la relación");
        }
        $cursoAsignaruta = new CursoAsignaturaController();
        $cursoAsignaruta->edit($_GET['id']);
        break;

    case 'curso_asignaturas_update':
        if (!isset($_GET['id'])) {
            die("Falta el ID para actualizar");
        }
        $cursoAsignaruta = new CursoAsignaturaController();
        $cursoAsignaruta->update($_GET['id'], $_POST);
        break;

    case 'curso_asignaturas_delete':
        if (!isset($_GET['id'])) {
            die("Falta el ID para eliminar");
        }
        $cursoAsignaruta = new CursoAsignaturaController();
        $cursoAsignaruta->destroy($_GET['id']);
        break;


    // Alumno emergencia
    case 'alum_emergencia':
        $alumnoemergencia = new AlumEmergenciaController();
        $alumnoemergencia->index();
        break;

    case 'alum_emergencia_create':
        $alumnoemergencia = new AlumEmergenciaController();
        $alumnoemergencia->create();
        break;

    case 'alum_emergencia_store':
        $alumnoemergencia = new AlumEmergenciaController();
        $alumnoemergencia->store($_POST);
        break;

    case 'alum_emergencia_edit':
        $alumnoemergencia = new AlumEmergenciaController();
        $alumnoemergencia->edit($_GET['id']);
        break;

    case 'alum_emergencia_update':
        $alumnoemergencia = new AlumEmergenciaController();
        $alumnoemergencia->update($_GET['id'], $_POST);
        break;

    case 'alum_emergencia_delete':
        $alumnoemergencia = new AlumEmergenciaController();
        $alumnoemergencia->delete($_GET['id']);
        break;
    case 'alumno_search_ajax':
        require_once __DIR__ . '/../models/Alumno.php';
        $alumnoModel = new Alumno();
        $term = $_GET['term'] ?? '';
        $results = $alumnoModel->searchAlumnoEmergencia($term);
        header('Content-Type: application/json');
        echo json_encode($results);
        exit; // Muy importante para que no cargue otra vista

    default:
        echo "Ruta no encontrada";
}
