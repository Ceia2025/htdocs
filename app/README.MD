Correo electronico


´´
app/
 ├─ config/
 │   └─ Connection.php             # Conexión PDO a la base de datos
 │
 ├─ models/
 │   ├─ Alumno.php
 │   ├─ AlumEmergencia.php
 │   ├─ Anio.php
 │   ├─ User.php
 │   └─ Role.php
 │
 ├─ controllers/
 │   ├─ AlumnoController.php
 │   ├─ AlumEmergenciaController.php
 │   ├─ AnioController.php
 │   ├─ AuthController.php
 │   ├─ RolesController.php
 │   └─ UsersController.php
 │
 ├─ views/
 │   ├─ layout/
 │   │   ├─ header.php
 │   │   ├─ navbar.php
 │   │   └─ footer.php
 │   │
 │   ├─ dashboard.php
 │   ├─ login.php
 │   ├─ alumnos/
 │   │   ├─ index.php
 │   │   ├─ create.php
 │   │   └─ edit.php
 │   ├─ alum_emergencia/
 │   │   ├─ index.php
 │   │   ├─ create.php
 │   │   └─ edit.php
 │   └─ roles/
 │       ├─ index.php
 │       ├─ create.php
 │       └─ edit.php
 │
 ├─ utils/
 │   └─ menu.json                   # Configuración de menú por rol
 │
 └─ index.php                        # Front controller

´´



<a href="index.php?action=login">Login</a>
<a href="index.php?action=doLogin">Procesar Login</a> <!-- normalmente es un <form action="index.php?action=doLogin" method="POST"> -->
<a href="index.php?action=logout">Cerrar sesión</a>
<a href="index.php?action=dashboard">Dashboard</a>



crud usuario
<a href="index.php?action=users">Listado de Usuarios</a>
<a href="index.php?action=user_create">Crear Usuario</a>
<a href="index.php?action=user_store">Guardar Usuario</a> <!-- normalmente va en un <form action="index.php?action=user_store" method="POST"> -->
<a href="index.php?action=user_edit&id=1">Editar Usuario</a> <!-- cambia el id -->
<a href="index.php?action=user_update&id=1">Actualizar Usuario</a> <!-- también suele ser <form ...> -->
<a href="index.php?action=user_delete&id=1">Eliminar Usuario</a>


crud roles
<a href="index.php?action=roles">Listado de Roles</a>
<a href="index.php?action=createRole">Crear Rol</a>
<a href="index.php?action=editRole&id=1">Editar Rol</a>
<a href="index.php?action=updateRole&id=1">Actualizar Rol</a> <!-- en un <form> -->
<a href="index.php?action=deleteRole&id=1">Eliminar Rol</a>



http://localhost:8080/app/public/index.php?action=notas_createGroup&curso_id=2&anio_id=3



http://localhost:8080/app/public/index.php?action=antecedente_escolar_updateProfile

documento institucionales
reglamentos institucionales(preguntar a ingrid)
muchas cgas xD