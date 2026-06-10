<?php
// ── REQUIRES ────────────────────────────────────────────────
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
require_once __DIR__ . '/../controllers/NotasController.php';
require_once __DIR__ . '/../controllers/AtrasoController.php';
require_once __DIR__ . '/../controllers/AnotacionController.php';
require_once __DIR__ . '/../controllers/AnamnesisResumenController.php';
require_once __DIR__ . '/../controllers/RetirosController.php';
require_once __DIR__ . '/../controllers/ProfesoresController.php';
require_once __DIR__ . '/../controllers/ProfesorCursoAsignaturaController.php';
require_once __DIR__ . '/../controllers/HorariosController.php';
require_once __DIR__ . '/../controllers/PerfilAcademicoAsistenciaController.php';
require_once __DIR__ . '/../controllers/AsistenciaController.php';
require_once __DIR__ . '/../controllers/inventario/InventarioController.php';
require_once __DIR__ . '/../controllers/procedencia/ProcedenciaController.php';
require_once __DIR__ . '/../controllers/inventario/CategorizacionController.php';
require_once __DIR__ . '/../controllers/reportes/ReporteController.php';
require_once __DIR__ . '/../controllers/reportes/ReporteDashboardController.php';
require_once __DIR__ . '/../controllers/reportes/ReporteNotasController.php';
require_once __DIR__ . '/../controllers/reportes/etnia/ReporteEtniaController.php';
require_once __DIR__ . '/../controllers/reportes/certificadoAlumnoRegular/CertificadoAlumnoRegularController.php';


// ── INSTANCIAS ───────────────────────────────────────────────
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
$atrasoController = new AtrasoController();
$anotacionController = new AnotacionController();
$retirosController = new RetirosController();
$notasController = new NotasController();
$reporteController = new ReporteController();
$reporteDashboard = new ReporteDashboardController();
$asistenciaController = new AsistenciaController();
$perfilAcademicoAsistencia = new PerfilAcademicoAsistenciaController();
$inventarioController = new InventarioController();
$procedenciaController = new ProcedenciaController();
$categorizacionController = new CategorizacionController();
$reporteEtnia = new ReporteEtniaController();
$reporteNotasController = new ReporteNotasController();

// ── PERMISOS GLOBAL ──────────────────────────────────────────
$auth->checkPermiso($action);

// ── ROUTER ───────────────────────────────────────────────────
switch ($action) {

    // ── AUTH ─────────────────────────────────────────────────
    case 'login':
        $auth->login();
        break;
    case 'doLogin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->doLogin($_POST);
        }
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'dashboard':
        $auth->dashboard();
        break;

    // ── USUARIOS ─────────────────────────────────────────────
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

    // ── ROLES ────────────────────────────────────────────────
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

    // ── AÑOS ─────────────────────────────────────────────────
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

    // ── CURSOS ───────────────────────────────────────────────
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

    // ── ALUMNOS ──────────────────────────────────────────────
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
        $alumnosController->search();
        break;
    case 'alumnos_store_stepper':
        $alumnosController->storeStepper($_POST);
        break;
    case 'check_run_exists':
        $alumnosController->checkRunExists();
        break;
    case 'alumno_retire':
        $alumnosController->retire($_GET['id']);
        break;
    case 'alumno_restore':
        $alumnosController->restore($_GET['id']);
        break;
    case 'alumno_search_ajax_matricula':
        $alumnosController->searchAjaxMatricula();
        break;
    case 'alumno_pdf':
        $alumnosController->pdf($_GET['id'] ?? null);
        break;
    case 'alumnos_stepper':
        require '../views/alumnos/stepper/form_stepper.php';
        break;
    case 'listado_por_anio':
        $alumnosController->listadoPorAnio();
        break;

    // ── PERFIL ACADÉMICO ─────────────────────────────────────
    case 'perfil_academico':
        $perfilController = new PerfilAcademicoController();
        $perfilController->show($_GET['id']);
        break;

    // ── MATRÍCULAS ───────────────────────────────────────────
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
    case 'matricula_numero_lista':
        $matriculaController->numeroLista();
        break;
    case 'matricula_guardar_lista':
        $matriculaController->guardarNumeroLista();
        break;
    case 'matricula_retirar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $matriculaController->retirar(null);
        }
        break;
    case 'matricula_reintegrar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $matriculaController->reintegrar();
        }
        break;

    // ── ASIGNATURAS ──────────────────────────────────────────
    case 'asignaturas':
        $asignaturas->index();
        break;
    case 'asignatura_create':
        $asignaturas->create();
        break;
    case 'asignatura_store':
        $asignaturas->store($_POST);
        break;
    case 'asignatura_edit':
        $asignaturas->edit($_GET['id']);
        break;
    case 'asignatura_update':
        $asignaturas->update($_GET['id'], $_POST);
        break;
    case 'asignatura_delete':
        $asignaturas->delete($_GET['id']);
        break;

    // ── CURSO - ASIGNATURA ───────────────────────────────────
    case 'curso_asignaturas':
        $cursoAsignaruta->index();
        break;
    case 'curso_asignaturas_create':
        $cursoAsignaruta->create();
        break;
    case 'curso_asignaturas_store':
        $cursoAsignaruta->store($_POST);
        break;
    case 'curso_asignaturas_edit':
        if (!isset($_GET['id']))
            die("Falta el ID de la relación");
        $cursoAsignaruta->edit($_GET['id']);
        break;
    case 'curso_asignaturas_update':
        if (!isset($_GET['id']))
            die("Falta el ID para actualizar");
        $cursoAsignaruta->update($_GET['id'], $_POST);
        break;
    case 'curso_asignaturas_delete':
        if (!isset($_GET['id']))
            die("Falta el ID para eliminar");
        $cursoAsignaruta->destroy($_GET['id']);
        break;

    // ── CONTACTOS DE EMERGENCIA ──────────────────────────────
    case 'alum_emergencia':
        $alumnoemergencia->index();
        break;
    case 'alum_emergencia_create':
        $alumnoemergencia->create();
        break;
    case 'alum_emergencia_store':
        $alumnoemergencia->store($_POST);
        break;
    case 'alum_emergencia_edit':
        $alumnoemergencia->edit($_GET['id']);
        break;
    case 'alum_emergencia_update':
        $alumnoemergencia->update($_GET['id'], $_POST);
        break;
    case 'alum_emergencia_delete':
        $alumnoemergencia->delete($_GET['id']);
        break;
    case 'alum_emergencia_createProfile':
        $alumnoemergencia->createProfile($_GET['alumno_id']);
        break;
    case 'alum_emergencia_storeProfile':
        $alumnoemergencia->storeProfile($_POST);
        break;
    case 'alum_emergencia_deleteProfile':
        $alumnoemergencia->deleteProfile($_GET['id'], $_GET['alumno_id']);
        break;
    case 'alumno_search_ajax':
        require_once __DIR__ . '/../models/Alumno.php';
        $alumnoModel = new Alumno();
        $results = $alumnoModel->searchAlumnoEmergencia($_GET['term'] ?? '');
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;

    // ── ANTECEDENTES FAMILIARES ──────────────────────────────
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
        $antecedenteFamiliarController->editProfile($_GET['alumno_id']);
        break;
    case 'antecedentefamiliar_updateProfile':
        $antecedenteFamiliarController->updateProfile($_POST);
        break;

    // ── ANTECEDENTES ESCOLARES ───────────────────────────────
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
        $antecedenteEscolarController->editProfile($_GET['alumno_id']);
        break;
    case 'antecedente_escolar_updateProfile':
        $antecedenteEscolarController->updateProfile($_POST);
        break;

    // ── ANAMNESIS ────────────────────────────────────────────
    case 'anamnesis_form':
        if (!AuthController::puede('anamnesis_form')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        (new AnamnesisResumenController())->formAnamnesis();
        break;
    case 'anamnesis_guardar':
        if (!AuthController::puede('anamnesis_guardar')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AnamnesisResumenController())->guardar();
        }
        break;

    // ── ASISTENCIA (nueva) ───────────────────────────────────
    case 'asistencias':
        $perfilAcademicoAsistencia->index();
        break;
    case 'asistencia_select_context':
        break;
    case 'asistencia_create_form':
        $perfilAcademicoAsistencia->showCreateForm();
        break;
    case 'asistencia_store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $perfilAcademicoAsistencia->create();
        } else {
            header("Location: index.php?action=asistencias");
        }
        break;
    case 'asistencia_edit':
        $perfilAcademicoAsistencia->edit($_GET['id'] ?? null);
        break;
    case 'asistencia_update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $perfilAcademicoAsistencia->update($_GET['id'] ?? null);
        } else {
            header("Location: index.php?action=asistencias");
        }
        break;
    case 'asistencia_delete':
        $perfilAcademicoAsistencia->delete($_GET['id'] ?? null);
        break;
    case 'alumno_perfil':
        $perfilAcademicoAsistencia->getAsistenciaByAlumnoId($_GET['id'] ?? null);
        break;

    // ── ASISTENCIA (libro de clases) ─────────────────────────
    case 'libro_clases':
        $asistenciaController->libroClases();
        break;
    case 'libro_clases_pdf':
        $asistenciaController->libroClasesPdf();
        break;
    case 'asistencia_cursos':
        $asistenciaController->listarCursos();
        break;
    case 'form_asistencia_masiva':
        $asistenciaController->formMasiva();
        break;
    case 'resumen_curso':
        $asistenciaController->resumenCurso();
        break;
    case 'guardar_asistencia_masiva':
        $asistenciaController->guardarAsistenciaMasiva();
        break;
    case 'asistencia_pdf':
        $asistenciaController->asistencia_pdf(
            $_GET['anio_id'] ?? null,
            $_GET['curso_id'] ?? null
        );
        break;

    case 'eliminar_asistencia_dia':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $asistenciaController->eliminarAsistenciaDia();
        }
        break;

    // ── NOTAS ────────────────────────────────────────────────
    case 'notas':
        $notasController->index();
        break;
    case 'notas_index':
        $notasController->indexByAlumno($_GET['matricula_id']);
        break;
    case 'notas_createGroup':
        $notasController->createGroup($_GET['curso_id'], $_GET['anio_id']);
        break;
    case 'notas_storeGroup':
        $notasController->storeGroup($_GET['curso_id'], $_GET['anio_id'], $_POST);
        break;
    case 'notas_edit':
        $notasController->edit($_GET['id']);
        break;
    case 'notas_update':
        $notasController->update($_GET['id'], $_POST);
        break;
    case 'notas_delete':
        $notasController->delete((int) ($_GET['id'] ?? 0));
        break;
    case 'notas_panel':
        if (!AuthController::puede('notas_panel')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $notasController->panelCursos();
        break;
    case 'notas_panel_asignaturas':
        if (!AuthController::puede('notas_panel_asignaturas')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $notasController->panelAsignaturas();
        break;
    case 'notas_panel_asignatura':
        if (!AuthController::puede('notas_panel_asignatura')) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $notasController->panelNotasAsignatura();
        break;

    // ── ATRASOS ──────────────────────────────────────────────
    case 'atrasos_registro':
        $atrasoController->formRegistro();
        break;
    case 'atrasos_guardar':
        $atrasoController->guardar();
        break;
    case 'atrasos_buscar_alumno':
        $atrasoController->buscarAlumno();
        break;
    case 'atrasos_lista_curso':
        $atrasoController->listarPorCurso();
        break;
    case 'atrasos_lista_alumno':
        $atrasoController->listarPorAlumno();
        break;
    case 'atrasos_eliminar':
        $atrasoController->eliminar();
        break;
    case 'atrasos_buscar_alumnos':
        $atrasoController->buscarAlumnos();
        break;
    case 'atrasos_actualizar_hora':
        $atrasoController->actualizarHora();
        break;
    case 'atrasos_pdf':
        $atrasoController->atrasos_pdf();
        break;
    case 'atraso_alumno_pdf':
        $atrasoController->atraso_alumno_pdf();
        break;
    case 'atrasos_historial':
        $atrasoController->historial();
        break;
    case 'atrasos_historial_pdf':
        $atrasoController->historial_pdf();
        break;

    // ── ANOTACIONES ──────────────────────────────────────────
    case 'anotaciones':
        $anotacionController->index();
        break;
    case 'anotacion_create':
        $anotacionController->create();
        break;
    case 'anotacion_store':
        $anotacionController->store($_POST);
        break;
    case 'anotacion_ver':
        $anotacionController->verAlumno();
        break;
    case 'anotacion_delete':
        $anotacionController->delete($_GET['id'] ?? null);
        break;
    case 'anotacion_buscar_alumno':
        $anotacionController->buscarAlumnoAjax();
        break;
    case 'anotacion_asignaturas':
        $anotacionController->getAsignaturasAjax();
        break;
    case 'anotaciones_pdf':
        $anotacionController->exportarPdf();
        break;
    case 'anotaciones_individual_pdf':
        $anotacionController->exportarIndividualPdf();
        break;

    // ── RETIROS ──────────────────────────────────────────────
    case 'retiros':
        $retirosController->index();
        break;
    case 'retiros_create':
        $retirosController->create();
        break;
    case 'retiros_edit':
        $retirosController->edit((int) ($_GET['id'] ?? 0));
        break;
    case 'retiros_delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $retirosController->delete((int) ($_GET['id'] ?? 0));
        }
        break;
    case 'retiros_buscar_alumnos':
        $retirosController->buscarAlumnos();
        break;
    case 'retiros_reportes':
        $retirosController->reportes();
        break;
    case 'retiros_reporte':
        $retirosController->generarReporte();
        break;
    case 'retiros_buscar_contactos':
        $retirosController->buscarContactos();
        break;

    case 'reportes_etnia':
        $reporteEtnia->index();
        break;

    // ← AGREGAR ESTOS DOS:
    case 'reporte_etnia_pdf':
        $reporteEtnia->pdfEtnia();
        break;
    case 'reporte_etnia_csv':
        $reporteEtnia->csvEtnia();
        break;

    case 'reportes_cert_alumno_regular':
        (new CertificadoAlumnoRegularController())->index();
        break;

    case 'cert_alumno_regular_buscar':
        (new CertificadoAlumnoRegularController())->buscar();
        break;

    // AJAX: datos del alumno (para la card previa en el buscador)
    case 'cert_alumno_regular_datos':
        header('Content-Type: application/json; charset=utf-8');
        require_once __DIR__ . '/../models/reportes/certificadoAlumnoRegular/CertificadoAlumnoRegular.php';
        $model = new CertificadoAlumnoRegular();
        $id = (int) ($_GET['alumno_id'] ?? 0);
        $data = $id ? $model->getDatosAlumno($id) : null;
        if ($data) {
            $data['nombre_completo'] = trim(
                $data['nombre'] . ' ' . $data['apepat'] . ' ' . ($data['apemat'] ?? '')
            );
        }
        echo json_encode($data ?? ['error' => 'no encontrado']);
        exit;

    case 'cert_alumno_regular_pdf':
        (new CertificadoAlumnoRegularController())->pdfNormal();
        break;

    case 'cert_alumno_regular_pdf_asistencia':
        (new CertificadoAlumnoRegularController())->pdfConAsistencia();
        break;



    // ── PROFESORES ───────────────────────────────────────────
    case 'profesores':
        (new ProfesoresController())->index();
        break;
    case 'profesor_create':
        (new ProfesoresController())->create();
        break;
    case 'profesor_store':
        (new ProfesoresController())->store($_POST);
        break;
    case 'profesor_edit':
        (new ProfesoresController())->edit($_GET['id']);
        break;
    case 'profesor_update':
        (new ProfesoresController())->update($_GET['id'], $_POST);
        break;
    case 'profesor_delete':
        (new ProfesoresController())->delete($_GET['id']);
        break;

    // ── PROFESOR - CURSO - ASIGNATURA ────────────────────────
    case 'profesor_curso_asignatura':
        (new ProfesorCursoAsignaturaController())->index();
        break;
    case 'pca_create':
        (new ProfesorCursoAsignaturaController())->create();
        break;
    case 'pca_store':
        (new ProfesorCursoAsignaturaController())->store($_POST);
        break;
    case 'pca_edit':
        (new ProfesorCursoAsignaturaController())->edit($_GET['id']);
        break;
    case 'pca_update':
        (new ProfesorCursoAsignaturaController())->update($_GET['id'], $_POST);
        break;
    case 'pca_delete':
        (new ProfesorCursoAsignaturaController())->delete($_GET['id']);
        break;
    case 'pca_asignaturas_por_curso':
        (new ProfesorCursoAsignaturaController())->getAsignaturasPorCurso();
        break;

    // ── HORARIOS ─────────────────────────────────────────────
    case 'horarios_pca':
        (new HorariosController())->indexPorAsignacion($_GET['pca_id']);
        break;
    case 'horario_store':
        (new HorariosController())->store($_POST);
        break;
    case 'horario_delete':
        (new HorariosController())->delete($_GET['id'], $_GET['pca_id']);
        break;

    // ── SUPLENCIAS ───────────────────────────────────────────
    case 'suplencias':
        (new SuplenciasController())->index();
        break;
    case 'suplencia_store':
        (new SuplenciasController())->store($_POST);
        break;
    case 'suplencia_delete':
        (new SuplenciasController())->delete($_GET['id']);
        break;

    // ── CURSO DOCENTE ────────────────────────────────────────
    case 'curso_docente':
        require_once '../controllers/CursoDocenteController.php';
        (new CursoDocenteController())->index();
        break;
    case 'curso_docente_create':
        require_once '../controllers/CursoDocenteController.php';
        (new CursoDocenteController())->create();
        break;
    case 'curso_docente_store':
        require_once '../controllers/CursoDocenteController.php';
        (new CursoDocenteController())->store();
        break;
    case 'curso_docente_delete':
        require_once '../controllers/CursoDocenteController.php';
        (new CursoDocenteController())->destroy();
        break;

    // ── INVENTARIO ───────────────────────────────────────────
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
        $inventarioController->edit($_GET['id']);
        break;
    case 'inventario_update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventarioController->update($_GET['id'], $_POST);
        }
        break;
    case 'inventario_delete':
        $inventarioController->delete($_GET['id']);
        break;
    case 'inventario_showLugarGrupo':
        $inventarioController->showLugarGrupo($_GET['lugar'], $_GET['codigo_general']);
        break;
    case 'inventario_exportExcel':
        $inventarioController->exportExcel();
        break;

    // ── PROCEDENCIA ──────────────────────────────────────────
    case 'procedencias':
        $procedenciaController->index();
        break;
    case 'procedencia_create':
        $procedenciaController->create();
        break;
    case 'procedencia_store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $procedenciaController->store($_POST);
        }
        break;
    case 'procedencia_edit':
        if (isset($_GET['id'])) {
            $procedenciaController->edit($_GET['id']);
        }
        break;
    case 'procedencia_update':
        if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $procedenciaController->update($_GET['id'], $_POST);
        }
        break;
    case 'procedencia_delete':
        if (isset($_GET['id'])) {
            $procedenciaController->delete($_GET['id']);
        }
        break;

    // ── CATEGORIZACIÓN ───────────────────────────────────────
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

    // ── REPORTES ─────────────────────────────────────────────
    case 'reportes':
        $reporteDashboard->index();
        break;
    case 'reportes_asistencia':
        $reporteController->index();
        break;
    case 'reporte_csv_curso':
        $reporteController->csvCurso();
        break;
    case 'reporte_csv_general':
        $reporteController->csvGeneral();
        break;
    case 'reporte_pdf_curso':
        $reporteController->pdfCurso();
        break;
    case 'reporte_pdf_general':
        $reporteController->pdfGeneral();
        break;
    case 'reportes_notas_pdf_consolidado':
        $reporteNotasController->pdfConsolidado();
        break;

    // ── REPORTES DE NOTAS ────────────────────────────────────
    case 'reportes_notas':
        (new ReporteNotasController())->index();
        break;
    case 'reportes_notas_pdf_alumno':
        (new ReporteNotasController())->pdfAlumno();
        break;
    case 'reportes_notas_pdf_asignatura':
        (new ReporteNotasController())->pdfAsignatura();
        break;

    // ── APIs JSON ────────────────────────────────────────────
    case 'api_alumnos_curso':
        require_once __DIR__ . '/../models/Nota.php';
        header('Content-Type: application/json');
        echo json_encode(
            (new Nota())->getByCursoYAnio(
                (int) ($_GET['curso_id'] ?? 0),
                (int) ($_GET['anio_id'] ?? 0)
            )
        );
        exit;

    case 'api_asignaturas_curso':
        require_once __DIR__ . '/../models/CursoAsignatura.php';
        header('Content-Type: application/json');
        echo json_encode(
            (new CursoAsignatura())->getAsignaturasPorCurso(
                (int) ($_GET['curso_id'] ?? 0)
            )
        );
        exit;

    case 'notas_ajax_store':
        $notasController->ajaxStore();
        break;

    case 'notas_ajax_update':
        $notasController->ajaxUpdate();
        break;

    case 'notas_ajax_update_fecha':
        $notasController->ajaxUpdateFecha();
        break;

    // ── DEFAULT ──────────────────────────────────────────────
    default:
        echo "<h1>Ruta no encontrada</h1>";
        break;
}
