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
require_once __DIR__ . '/../controllers/AntecedenteFamiliarController.php';
require_once __DIR__ . '/../controllers/AntecedenteEscolarController.php';
require_once __DIR__ . '/../controllers/MatriculaController.php';
require_once __DIR__ . '/../controllers/PerfilAcademicoController.php';
require_once __DIR__ . '/../controllers/AntecedenteEscolarController.php';
require_once __DIR__ . '/../controllers/NotasController.php';






//Inventario
require_once __DIR__ . '/../controllers/inventario/InventarioController.php';
require_once __DIR__ . '/../controllers/procedencia/ProcedenciaController.php';
require_once __DIR__ . '/../controllers/inventario/CategorizacionController.php';

// Instancias de controladores
$inventarioController = new InventarioController();
$procedenciaController = new ProcedenciaController();
$categorizacionController = new CategorizacionController();

// Instancias de aplicación SAAT
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
$antecedenteFamiliarController = new AntecedenteFamiliarController();
$antecedenteEscolarController = new AntecedenteEscolarController();
$matriculaController = new MatriculaController();





switch ($action) {
    case 'login':
        $auth->login(); // muestra formulario
        break;
    //case 'doLogin':
    //  $auth->doLogin($_POST); // procesa POST
    //break;
    case 'doLogin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->doLogin($_POST);
        }
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

    //Përfil academico
    case 'perfil_academico':
        $perfilController = new PerfilAcademicoController();
        $perfilController->show($_GET['id']);
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
    case 'alumnos_store':
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

    case 'alumnos_stepper':
        $alumnosController->createStepper(); // formulario stepper
        break;

    case 'alumnos_store_stepper':
        $alumnosController->storeStepper($_POST); // guardar todo
        break;

    case 'check_run_exists':
        require_once __DIR__ . '/../controllers/AlumnosController.php';
        $controller = new AlumnosController();
        $controller->checkRunExists();
        break;

    case 'alumno_retire':
        $controller = new AlumnosController();
        $controller->retire($_GET['id']);
        break;

    case 'alumno_restore':
        $controller = new AlumnosController();
        $controller->restore($_GET['id']);
        break;

    // Asignaturas
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


    // Relacion de cursos y asignatura
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

    case 'alum_emergencia_createProfile':
        $alumnoemergencia = new AlumEmergenciaController();
        $alumnoemergencia->createProfile($_GET['alumno_id']);
        break;
    case 'alum_emergencia_storeProfile':
        $alumnoemergencia = new AlumEmergenciaController();
        $alumnoemergencia->storeProfile($_POST);
        break;
    case 'alum_emergencia_deleteProfile':
        $alumnoemergencia = new AlumEmergenciaController();
        $alumnoemergencia->deleteProfile($_GET['id'], $_GET['alumno_id']);
        break;


    //Inventario
    case 'inventario_index':
        $inventarioController->index();
        break;
    case 'inventario_create':
        $inventarioController->create();
        break;
    case 'inventario_store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventarioController->store($_POST);
        }
        break;
    case 'inventario_edit':
        $id = $_GET['id'];
        $inventarioController->edit($id);
        break;
    case 'inventario_update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'];
            $inventarioController->update($id, $_POST);
        }
        break;

    case 'inventario_delete':
        $inventarioController->delete($_GET['id']);
        break;


    //Antecedentes Familiares
    case 'antecedentefamiliar':
        $antecedenteFamiliarController->index();
        break;

    case 'antecedentefamiliar_create':
        $antecedenteFamiliarController->createForm();
        break;

    case 'antecedentefamiliar_store':
        $antecedenteFamiliarController->create($_POST);
        break;

    case 'antecedentefamiliar_edit':
        $antecedenteFamiliarController->edit($_GET['id']);
        break;

    case 'antecedentefamiliar_update':
        $antecedenteFamiliarController->update($_GET['id'], $_POST);
        break;

    case 'antecedentefamiliar_delete':
        $antecedenteFamiliarController->delete($_GET['id']);
        break;
    case 'antecedentefamiliar_editProfile':
        $controller = new AntecedenteFamiliarController();
        $controller->editProfile($_GET['alumno_id']);
        break;
    case 'antecedentefamiliar_updateProfile':
        $controller = new AntecedenteFamiliarController();
        $controller->updateProfile($_POST);
        break;


    //Procedencia SOLO PARA EL INVENTARIO
    case 'procedencias':
        $procedenciaController->index();
        break;
    case 'procedencia_store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $procedenciaController->store($_POST); // procesa POST y redirige
        }
        break;
    case 'procedencia_edit':
        if (isset($_GET['id'])) {
            $procedenciaController->edit($_GET['id']);
        } else {
            echo "ID de procedencia no especificado";
        }
        break;
    case 'procedencia_update':
        if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $procedenciaController->update($_GET['id'], $_POST); // CORRECTO
        }
        break;
    case 'procedencia_delete':
        if (isset($_GET['id'])) {
            $procedenciaController->delete($_GET['id']);
        } else {
            echo "ID de procedencia no especificado";
        }
        break;

    case 'inventario_showLugarGrupo':
        $controller = new InventarioController();
        $controller->showLugarGrupo($_GET['lugar'], $_GET['codigo_general']);
        break;


    case 'procedencia_create':
        $procedenciaController->create(); // solo muestra formulario
        break;

    //Expotal Inventario a excel
    case 'inventario_exportExcel':
        $controller = new InventarioController();
        $controller->exportExcel();
        break;

    // Categporizacion
    case 'categorizaciones':
        $categorizacionController->index();
        break;

    case 'categorizacion_create':
        require __DIR__ . '/../views/categorizacion/create.php';
        break;

    case 'categorizacion_store':
        $categorizacionController->create($_POST);
        break;

    case 'categorizacion_edit':
        $categorizacionController->edit($_GET['id']);
        break;

    case 'categorizacion_update':
        $categorizacionController->update($_GET['id'], $_POST);
        break;

    case 'categorizacion_delete':
        $categorizacionController->delete($_GET['id']);
        break;

    //Antecenters escolares
    case 'antecedente_escolar':
        $antecedenteEscolarController->index();
        break;
    case 'antecedente_escolar_create':
        $antecedenteEscolarController->create();
        break;
    case 'antecedente_escolar_store':
        $antecedenteEscolarController->store($_POST);
        break;
    case 'antecedente_escolar_edit':
        $antecedenteEscolarController->edit($_GET['id']);
        break;
    case 'antecedente_escolar_update':
        $antecedenteEscolarController->update($_GET['id'], $_POST);
        break;
    case 'antecedente_escolar_delete':
        $antecedenteEscolarController->delete($_GET['id']);
        break;
    case 'antecedente_escolar_editProfile':
        $controller = new AntecedenteEscolarController();
        $controller->editProfile($_GET['alumno_id']);
        break;

    case 'antecedente_escolar_updateProfile':
        $controller = new AntecedenteEscolarController();
        $controller->updateProfile($_POST);
        break;


    // CRUD MATRÍCULAS
    case 'matriculas':
        $matriculaController->index();
        break;

    case 'matricula_create':
        $matriculaController->create();
        break;

    case 'matricula_store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $matriculaController->store($_POST);
        }
        break;

    case 'matricula_edit':
        if (isset($_GET['id'])) {
            $matriculaController->edit($_GET['id']);
        }
        break;

    case 'matricula_update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            $matriculaController->update($_GET['id'], $_POST);
        }
        break;

    case 'matricula_delete':
        if (isset($_GET['id'])) {
            $matriculaController->delete($_GET['id']);
        }
        break;

    /*
case 'alumno_search_ajax':
    require_once __DIR__ . '/../models/Alumno.php';
    $alumnoModel = new Alumno();
    $term = $_GET['term'] ?? '';
    $results = $alumnoModel->searchAlumnoEmergencia($term);
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;*/


    case 'notas':
        $notasController = new NotasController();
        $notasController->index();
        break;

    case 'notas_index':
        require_once __DIR__ . '/../controllers/NotasController.php';
        $controller = new NotasController();
        $controller->indexByAlumno($_GET['matricula_id']);
        break;

    case 'notas_createGroup':
        $notasController = new NotasController();
        $notasController->createGroup($_GET['curso_id'], $_GET['anio_id']);
        break;

    case 'notas_storeGroup':
        $notasController = new NotasController();
        $notasController->storeGroup($_GET['curso_id'], $_GET['anio_id'], $_POST);
        break;

    case 'notas_edit':
        $notasController = new NotasController();
        $notasController->edit($_GET['id']);
        break;

    case 'notas_update':
        $notasController = new NotasController();
        $notasController->update($_GET['id'], $_POST);
        break;

    case 'notas_delete':
        $notasController = new NotasController();
        $notasController->delete($_GET['id']);
        break;

    default:
        echo "<h1>Ruta no encontrada</h1>";
        break;
}
