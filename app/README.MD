app/
 ├─ models/
 │   ├─ User.php
 │   └─ Role.php
 ├─ controllers/
 │   ├─ AuthController.php
 │   └─ RolesController.php
 ├─ views/
 │   ├─ dashboard.php
 │   ├─ login.php
 │   └─ roles/
 │       ├─ index.php   (listado + crear)
 │       └─ edit.php    (editar)
 └─ index.php




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