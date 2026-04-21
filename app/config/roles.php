<?php
// IDs de roles según tu tabla roles2
defined('ROL_ADMINISTRADOR') || define('ROL_ADMINISTRADOR', 1);
defined('ROL_SOPORTE') || define('ROL_SOPORTE', 2);
defined('ROL_DIRECCION') || define('ROL_DIRECCION', 3);
defined('ROL_ADMINISTRATIVO') || define('ROL_ADMINISTRATIVO', 4);
defined('ROL_INSPECTOR_GENERAL') || define('ROL_INSPECTOR_GENERAL', 5);
defined('ROL_DOCENTE') || define('ROL_DOCENTE', 6);
defined('ROL_ASISTENTE_SOCIAL') || define('ROL_ASISTENTE_SOCIAL', 7);
defined('ROL_ANOTACIONES') || define('ROL_ANOTACIONES', 9);
defined('ROL_ASISTENCIAS') || define('ROL_ASISTENCIAS', 10);
defined('ROL_ATRASOS') || define('ROL_ATRASOS', 11);

return [
    // null = cualquier usuario autenticado puede acceder
    'login' => null,
    'doLogin' => null,
    'logout' => null,
    'dashboard' => null,

    // ── USUARIOS ──
    'users' => [ROL_ADMINISTRADOR, ROL_SOPORTE],
    'user_create' => [ROL_ADMINISTRADOR],
    'user_store' => [ROL_ADMINISTRADOR],
    'user_edit' => [ROL_ADMINISTRADOR, ROL_SOPORTE],
    'user_update' => [ROL_ADMINISTRADOR, ROL_SOPORTE],
    'user_delete' => [ROL_ADMINISTRADOR],

    // ── ROLES ──
    'roles' => [ROL_ADMINISTRADOR],
    'createRole' => [ROL_ADMINISTRADOR],
    'editRole' => [ROL_ADMINISTRADOR],
    'updateRole' => [ROL_ADMINISTRADOR],
    'deleteRole' => [ROL_ADMINISTRADOR],

    // ── AÑOS ──
    'anios' => [ROL_ADMINISTRADOR, ROL_SOPORTE],
    'anio_create' => [ROL_ADMINISTRADOR],
    'anio_store' => [ROL_ADMINISTRADOR],
    'anio_edit' => [ROL_ADMINISTRADOR],
    'anio_update' => [ROL_ADMINISTRADOR],
    'anio_delete' => [ROL_ADMINISTRADOR],

    // ── CURSOS ──
    'cursos' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION],
    'curso_create' => [ROL_ADMINISTRADOR],
    'curso_store' => [ROL_ADMINISTRADOR],
    'curso_edit' => [ROL_ADMINISTRADOR],
    'curso_update' => [ROL_ADMINISTRADOR],
    'curso_delete' => [ROL_ADMINISTRADOR],

    // ── ASIGNATURAS ──
    'asignaturas' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION],
    'asignatura_create' => [ROL_ADMINISTRADOR],
    'asignatura_store' => [ROL_ADMINISTRADOR],
    'asignatura_edit' => [ROL_ADMINISTRADOR],
    'asignatura_update' => [ROL_ADMINISTRADOR],
    'asignatura_delete' => [ROL_ADMINISTRADOR],

    // ── CURSO-ASIGNATURA ──
    'curso_asignaturas' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION],
    'curso_asignaturas_create' => [ROL_ADMINISTRADOR],
    'curso_asignaturas_store' => [ROL_ADMINISTRADOR],
    'curso_asignaturas_edit' => [ROL_ADMINISTRADOR],
    'curso_asignaturas_update' => [ROL_ADMINISTRADOR],
    'curso_asignaturas_delete' => [ROL_ADMINISTRADOR],

    // ── ALUMNOS ──
    'alumnos' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL, ROL_ANOTACIONES, ROL_DOCENTE],
    'alumno_create' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alumnos_store' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alumno_edit' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL],
    'alumno_update' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alumno_delete' => [ROL_ADMINISTRADOR],
    'alumno_profile' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL, ROL_ANOTACIONES, ROL_DOCENTE],
    'alumno_search' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL, ROL_ANOTACIONES, ROL_DOCENTE],
    'alumnos_stepper' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alumnos_store_stepper' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alumno_retire' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alumno_restore' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alumno_pdf' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_ADMINISTRATIVO, ROL_DOCENTE],
    'listado_por_anio' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL],
    'check_run_exists' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alumno_search_ajax_matricula' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alumno_perfil' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL],

    // ── MATRÍCULAS ──
    'matriculas' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_ADMINISTRATIVO, ROL_DOCENTE],
    'matricula_create' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'matricula_store' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'matricula_edit' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'matricula_update' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'matricula_delete' => [ROL_ADMINISTRADOR],

    // ── ANOTACIONES ──
    'anotaciones' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL, ROL_ANOTACIONES, ROL_DOCENTE],
    'anotacion_create' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL, ROL_ANOTACIONES],
    'anotacion_store' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL, ROL_ANOTACIONES, ROL_DOCENTE],
    'anotacion_ver' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL, ROL_ANOTACIONES, ROL_DOCENTE],
    'anotacion_delete' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL],
    'anotacion_buscar_alumno' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL, ROL_ANOTACIONES, ROL_DOCENTE],
    'anotacion_asignaturas' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL, ROL_ANOTACIONES, ROL_DOCENTE],

    // ── ASISTENCIAS ──
    'asistencias' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_INSPECTOR_GENERAL, ROL_ASISTENCIAS, ROL_ADMINISTRATIVO, ROL_DOCENTE],
    'asistencia_cursos' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENCIAS, ROL_ADMINISTRATIVO, ROL_DOCENTE],
    'form_asistencia_masiva' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENCIAS, ROL_ADMINISTRATIVO, ROL_DOCENTE],
    'guardar_asistencia_masiva' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENCIAS, ROL_ADMINISTRATIVO,],
    'asistencia_create_form' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENCIAS, ROL_ADMINISTRATIVO, ROL_DOCENTE],
    'asistencia_store' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENCIAS, ROL_ADMINISTRATIVO, ROL_DOCENTE],
    'asistencia_edit' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENCIAS, ROL_ADMINISTRATIVO,],
    'asistencia_update' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ASISTENCIAS, ROL_ADMINISTRATIVO, ROL_DOCENTE],
    'asistencia_delete' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'resumen_curso' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_INSPECTOR_GENERAL, ROL_ASISTENCIAS, ROL_ADMINISTRATIVO, ROL_DOCENTE],
    'libro_clases' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL, ROL_DOCENTE],

    // ── ATRASOS ──
    'atrasos_registro' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ATRASOS,],
    'atrasos_guardar' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ATRASOS],
    'atrasos_buscar_alumno' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ATRASOS, ROL_DOCENTE],
    'atrasos_buscar_alumnos' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ATRASOS, ROL_DOCENTE],
    'atrasos_lista_curso' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_INSPECTOR_GENERAL, ROL_ATRASOS, ROL_DOCENTE],
    'atrasos_lista_alumno' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_INSPECTOR_GENERAL, ROL_ATRASOS, ROL_DOCENTE],
    'atrasos_eliminar' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL],

    // ── PROFESORES ──
    'profesores' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION],
    'profesor_create' => [ROL_ADMINISTRADOR],
    'profesor_store' => [ROL_ADMINISTRADOR],
    'profesor_edit' => [ROL_ADMINISTRADOR],
    'profesor_update' => [ROL_ADMINISTRADOR],
    'profesor_delete' => [ROL_ADMINISTRADOR],
    'profesor_curso_asignatura' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION],
    'pca_create' => [ROL_ADMINISTRADOR],
    'pca_store' => [ROL_ADMINISTRADOR],
    'pca_edit' => [ROL_ADMINISTRADOR],
    'pca_update' => [ROL_ADMINISTRADOR],
    'pca_delete' => [ROL_ADMINISTRADOR],
    'pca_asignaturas_por_curso' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION],

    // ── INVENTARIO ──
    'inventario_index' => [ROL_ADMINISTRADOR, ROL_SOPORTE],
    'inventario_create' => [ROL_ADMINISTRADOR],
    'inventario_store' => [ROL_ADMINISTRADOR],
    'inventario_edit' => [ROL_ADMINISTRADOR],
    'inventario_update' => [ROL_ADMINISTRADOR],
    'inventario_delete' => [ROL_ADMINISTRADOR],
    'inventario_exportExcel' => [ROL_ADMINISTRADOR, ROL_SOPORTE],
    'inventario_showLugarGrupo' => [ROL_ADMINISTRADOR, ROL_SOPORTE],
    'procedencias' => [ROL_ADMINISTRADOR],
    'procedencia_create' => [ROL_ADMINISTRADOR],
    'procedencia_store' => [ROL_ADMINISTRADOR],
    'procedencia_edit' => [ROL_ADMINISTRADOR],
    'procedencia_update' => [ROL_ADMINISTRADOR],
    'procedencia_delete' => [ROL_ADMINISTRADOR],
    'categorizaciones' => [ROL_ADMINISTRADOR],
    'categorizacion_create' => [ROL_ADMINISTRADOR],
    'categorizacion_store' => [ROL_ADMINISTRADOR],
    'categorizacion_edit' => [ROL_ADMINISTRADOR],
    'categorizacion_update' => [ROL_ADMINISTRADOR],
    'categorizacion_delete' => [ROL_ADMINISTRADOR],

    // ── ANTECEDENTES ──
    'alum_emergencia' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_ADMINISTRATIVO],
    'alum_emergencia_create' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alum_emergencia_store' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alum_emergencia_edit' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL],
    'alum_emergencia_update' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alum_emergencia_delete' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alum_emergencia_createProfile' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL],
    'alum_emergencia_storeProfile' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'alum_emergencia_deleteProfile' => [ROL_ADMINISTRADOR],
    'alumno_search_ajax' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_ADMINISTRATIVO],
    'antecedentefamiliar' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_ADMINISTRATIVO],
    'antecedentefamiliar_create' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'antecedentefamiliar_store' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'antecedentefamiliar_edit' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'antecedentefamiliar_update' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'antecedentefamiliar_delete' => [ROL_ADMINISTRADOR],
    'antecedentefamiliar_editProfile' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL],
    'antecedentefamiliar_updateProfile' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'antecedente_escolar' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_ADMINISTRATIVO],
    'antecedente_escolar_create' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'antecedente_escolar_store' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'antecedente_escolar_edit' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'antecedente_escolar_update' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],
    'antecedente_escolar_delete' => [ROL_ADMINISTRADOR],
    'antecedente_escolar_editProfile' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL],
    'antecedente_escolar_updateProfile' => [ROL_ADMINISTRADOR, ROL_ADMINISTRATIVO],

    // ── NOTAS ──
    'notas' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_DOCENTE],
    'notas_index' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_DOCENTE],
    'notas_createGroup' => [ROL_ADMINISTRADOR, ROL_DOCENTE],
    'notas_storeGroup' => [ROL_ADMINISTRADOR, ROL_DOCENTE],
    'notas_edit' => [ROL_ADMINISTRADOR, ROL_DOCENTE],
    'notas_update' => [ROL_ADMINISTRADOR, ROL_DOCENTE],
    'notas_delete' => [ROL_ADMINISTRADOR],

    // ── PERFIL ACADÉMICO ──
    'perfil_academico' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION, ROL_ADMINISTRATIVO, ROL_INSPECTOR_GENERAL, ROL_ASISTENTE_SOCIAL, ROL_DOCENTE],

    // ── HORARIOS ──
    'horarios_pca' => [ROL_ADMINISTRADOR, ROL_SOPORTE, ROL_DIRECCION],
    'horario_store' => [ROL_ADMINISTRADOR],
    'horario_delete' => [ROL_ADMINISTRADOR],


    // ── RETIROS ──
    'retiros' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ATRASOS],
    'retiros_create' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ATRASOS],
    'retiros_reportes' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL],
    'retiros_edit' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL],
    'retiros_delete' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL],
    'retiros_reporte' => [ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL, ROL_ATRASOS],

    // ── PROFESOR JEFE POR CURSO ──
    'curso_docente' => [ROL_ADMINISTRADOR, ROL_DIRECCION, ROL_INSPECTOR_GENERAL],
    'curso_docente_create' => [ROL_ADMINISTRADOR, ROL_DIRECCION],
    'curso_docente_store' => [ROL_ADMINISTRADOR, ROL_DIRECCION],
    'curso_docente_delete' => [ROL_ADMINISTRADOR, ROL_DIRECCION],
];