<?php
// ------------------------------------------------------------

// Autor: Felipe Gutierrez Alfaro
// Fecha de creación: 2024-11-01
// Versión: 1.0
// 
// Copyright (c) 2024 Felipe Gutierrez Alfaro. Todos los derechos reservados.
// 
// Este código fuente está sujeto a derechos de autor y ha sido facilitado 
// de manera gratuita y de por vida a la Escuela Juanita Zuñiga Fuentes CEIA PARRAL.
// 
// Restricciones y condiciones:
// - Este código NO puede ser vendido, ni redistribuido.
// - Solo la Escuela Juanita Zuñiga Fuentes tiene derecho a usar este código sin costo y modificarlo a su gusto.
// - No se otorga ninguna garantía implícita o explícita. Uso bajo su propia responsabilidad.
// - El establecimiento puede utilizar imágenes a su gusto y asignar el nombre que estime conveniente al sistema.
// 
// Contacto: 
// Email: felipe.gutierrez.alfaro@gmail.com


//     Reconstruccion DS  Lunes 25 de agosto del     2025
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$role = $_SESSION['role'] ?? ''; // Se asegura que $role esté definida
$username = $_SESSION['username'] ?? 'Usuario'; // Obtener el nombre de usuario
$appVersion = 'v 1.5'; // Define la versión de la aplicación
?>


<!--     Reconstruccion DS  Lunes 25 de agosto del     2025-->


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">





<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/index.php">S.A.A.T.</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
      aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav me-auto">



        <!-- INGRESSOS Section - Only for 'admin', 'reg_anotacion', 'reg_asistencia' -->
        <?php

        if ($role == 'admin' || $role == 'soporte' || $role == 'reg_anotacion' || $role == 'reg_asistencia' || $role == 'inspector' || $role == 'reg_porteria' || $role == 'administrativo'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">INGRESOS</a>
            <ul class="dropdown-menu">

              <?php if ($role == 'admin' || $role == 'soporte' || $role == 'reg_anotacion' || $role == 'inspector' || $role == 'director'): ?>
                <li><a class="dropdown-item" href="/pages/ingresos/in.anota.php">ANOTACIONES</a></li>
              <?php endif; ?>

              <?php if ($role == 'admin' || $role == 'soporte' || $role == 'reg_porteria'): ?>
                <li><a class="dropdown-item" href="/pages/ingresos/in.atraso.php">ATRASOS</a></li>
                <li><a class="dropdown-item" href="/pages/ingresos/in.salida.php">SALIDAS</a></li>
              <?php endif; ?>

              <?php if ($role == 'admin' || $role == 'soporte' || $role == 'reg_asistencia'): ?>
                <li><a class="dropdown-item" href="/pages/ingresos/in.asistencia.php">ASISTENCIA</a></li>
              <?php endif; ?>

              <?php if ($role == 'admin' || $role == 'soporte' || $role == 'administrativo'): ?>
                <li><a class="dropdown-item" href="/pages/ingresos/in.notas.php">NOTAS</a></li>
              <?php endif; ?>

            </ul>
          </li>

        <?php endif; ?>




        <!-- CONSULTA Section - Only for 'admin' and 'consulta' roles -->
        <?php if ($role == 'admin' || $role == 'soporte' || $role == 'consulta' || $role == 'docente' || $role == 'reg_anotacion' || $role == 'reg_asistencia' || $role == 'asistente_social' || $role == 'reg_porteria' || $role == 'inspector' || $role == 'director' || $role == 'administrativo'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="/pages/consultas/c.alum.php" role="button"
              aria-expanded="false">CONSULTA</a>
          </li>
        <?php endif; ?>


        <!-- REPORTES Section - Only for 'admin', 'docente', and 'consulta' -->
        <?php if ($role == 'admin' || $role == 'soporte' || $role == 'docente' || $role == 'consulta' || $role == 'reg_anotacion' || $role == 'reg_asistencia' || $role == 'reg_porteria' || $role == 'inspector' || $role == 'director' || $role == 'asistente_social' || $role == 'administrativo'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">REPORTES</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/pages/consultas/l.alum.reg.php">ANOTACIONES</a></li>
              <li><a class="dropdown-item" href="/pages/consultas/r.alum.curso.php">RANCKING ANOTACIONES x CURSO</a></li>
              <li><a class="dropdown-item" href="/pages/consultas/a.mostrar_asistencia.php">ASISTENCIAS x CURSO</a></li>
              <li><a class="dropdown-item" href="/pages/consultas/c.mostrar_notas.php">NOTAS x CURSO</a></li>
              <li><a class="dropdown-item" href="/pages/consultas/c.notas_alumno.php">NOTAS x ALUMNO</a></li>
              <li><a class="dropdown-item" href="/pages/reportes/r.alum.men_may.php">ALUMNOS x EDADES</a></li>
              <li><a class="dropdown-item" href="/pages/reportes/r.alum.hijos.php">ALUMNOS CON HIJOS</a></li>
              <li><a class="dropdown-item" href="/pages/consultas/c.nacionalidades.php">NACIONALIDADES</a></li>
              <li><a class="dropdown-item" href="/pages/consultas/c.pueblo.originarios.php">PUEBLOS ORIGINARIOS</a></li>
            </ul>
          </li>
        <?php endif; ?>


        <!-- LISTADOS Section - Only for 'admin' and 'docente' -->
        <?php if ($role == 'admin' || $role == 'soporte' || $role == 'docente' || $role == 'consulta' || $role == 'reg_anotacion' || $role == 'reg_asistencia' || $role == 'reg_porteria' || $role == 'inspector' || $role == 'asistente_social' || $role == 'director' || $role == 'administrativo'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">LISTADOS</a>
            <ul class="dropdown-menu">


              <li><a class="dropdown-item" href="/pages/listados/listar_docentes.php">DOCENTES</a></li>
              <li><a class="dropdown-item" href="/pages/listados/listar_asignaturas.php">ASIGNATURAS</a></li>
              <li><a class="dropdown-item" href="/pages/listados/listar_cursos.php">CURSOS</a></li>
              <li><a class="dropdown-item" href="/pages/listados/listado_asignaturas_docentes.php">ASIGNATURAS Y SUS
                  DOCENTES</a></li>
              <li><a class="dropdown-item" href="/pages/listados/listado_docentes_asignaturas.php">DOCENTES ->
                  ASIGNATRURAS</a></li>
              <li><a class="dropdown-item" href="/pages/listados/listar_curso_horario.php">JORNADA DEL CURSO</a></li>
              <li><a class="dropdown-item" href="/pages/listados/ver_horario_curso">HORARIO DEL CURSO</a></li>


            </ul>
          </li>
        <?php endif; ?>


        <!-- MANTENEDOR Section - Only for 'admin' role -->
        <?php if ($role == 'admin' || $role == 'soporte' || $role == 'reg_asistencia' || $role == 'asistente_social' || $role == 'administrativo'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">MANTENEDOR</a>
            <ul class="dropdown-menu">

              <!-- NOTAS -->
              <?php if ($role == 'admin' || $role == 'soporte' || $role == 'inspector' || $role == 'director' || $role == 'administrativo'): ?>
                <li><a class="dropdown-item" href="/pages/mantenedor/m.notas.php">ACTUALIZA NOTAS</a></li>
              <?php endif; ?>

              <!-- FICHA ALUMNO -->
              <?php if ($role == 'admin' || $role == 'soporte' || $role == 'reg_asistencia' || $role == 'asistente_social' || $role == 'inspector' || $role == 'director' || $role == 'administrativo'): ?>
                <li><a class="dropdown-item" href="/pages/mantenedor/m.alum.php">ACTUALIZA FICHA ALUMNO</a></li>
              <?php endif; ?>

              <!-- ANOTACIONES -->
              <?php if ($role == 'admin' || $role == 'soporte' || $role == 'inspector' || $role == 'director' || $role == 'administrativo'): ?>
                <li><a class="dropdown-item" href="/pages/mantenedor/m.alum.reg.php">ACTUALIZAR ANOTACIONES</a></li>
              <?php endif; ?>

              <!-- USUARIOS -->
              <?php if ($role == 'admin' || $role == 'soporte'): ?>
                <li><a class="dropdown-item" href="/admin/gestion_usuarios.php">GESTION DE USUARIOS</a></li>
              <?php endif; ?>

              <!-- CALENDARIO -->
              <?php if ($role == 'admin' || $role == 'soporte' || $role == 'administrativo'): ?>
                <li><a class="dropdown-item" href="/pages/mantenedor/modificar_calendario.php">CALENDARIO ESCOLAR</a></li>
              <?php endif; ?>

              <!-- SIGE -->
              <?php if ($role == 'admin' || $role == 'soporte'): ?>
                <li><a class="dropdown-item" href="/pages/mantenedor/importa_sige.php">IMPORTAR SIGE</a></li>
              <?php endif; ?>
              <li><a class="dropdown-item" href="/pages/mantenedor/f.agregar_docente.php">AGREGAR NUEVO DOCENTE</a></li>
              <li><a class="dropdown-item" href="/pages/mantenedor/f.agregar_asignatura.php">AGREGAR NUEVO ASIGNATURA</a>
              </li>
              <li><a class="dropdown-item" href="/pages/mantenedor/f.agregar_curso.php">AGREGAR NUEVO CURSO</a></li>
              <li><a class="dropdown-item" href="/pages/mantenedor/f.agregar_horario.php">AGREGAR NUEVO HORARIO</a></li>
              <li><a class="dropdown-item" href="/pages/mantenedor/f.asignar_docente_asignatura.php">ASIGNAR DOCENTE A
                  ASIGNATURA</a></li>
              <li><a class="dropdown-item" href="/pages/mantenedor/f.asignar_horario_curso.php">ASIGNAR HORARIO A
                  CURSO</a></li>
              <li><a class="dropdown-item" href="/pages/mantenedor/f.asignar_clase.php">ASIGNAR ASIGNATURA A CURSO</a>
              </li>
            </ul>
          </li>
        <?php endif; ?>



        <!-- GRAFICOS' -->

        <?php if ($role == 'admin' || $role == 'soporte'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">GRAFICOS</a>
            <ul class="dropdown-menu">

              <li><a class="dropdown-item" href="../../mantenimiento.php">ALUMNOS POR CURSO</a></li>

            </ul>
          </li>
        <?php endif; ?>




        <!-- CERTIFICADOS' -->

        <?php if ($role == 'admin' || $role == 'soporte' || $role == 'docente' || $role == 'consulta' || $role == 'reg_anotacion' || $role == 'reg_asistencia' || $role == 'reg_porteria' || $role == 'inspector' || $role == 'asistente_social' || $role == 'director' || $role == 'administrativo'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">CERTIFICADOS</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/pages/certificados/informe_notas.php">INFORME DE NOTAS x ALUMNO</a></li>
              <li><a class="dropdown-item" href="/pages/certificados/cierre_anual.php">RENDIMIENTO ACADEMICOCIERRE
                  ANUAL</a></li>

            </ul>
          </li>
        <?php endif; ?>

      </ul>


      <!-- User and Logout Section on the Right -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <span class="navbar-text text-white me-3">Bienvenido, <?php echo htmlspecialchars($username); ?></span>
        </li>
        <li class="nav-item">
          <a class="btn btn-danger btn-sm me-3" href="/pages/logout.php" role="button">CERRAR SESION</a>
        </li>

        <li class="nav-item">
          <a class="navbar-text text-white-sm me-3 nav-link" href="/admin/cambiar_clave.php">Cambiar Contraseña</a>
        </li>


        <li class="nav-item">
          <span class="navbar-text text-white-sm small">Versión <?php echo $appVersion; ?></span>
        </li>
      </ul>

    </div>
  </div>
</nav>