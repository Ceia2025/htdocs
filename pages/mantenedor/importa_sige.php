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
?>
<?php
session_start();
include '../../includes/navbar.php';
include_once '../../includes/config.php';


// Procesar la limpieza de la tabla si se ha enviado la solicitud mediante POST
if(isset($_POST['limpiar_tabla'])) {
    // Verificar si se ha confirmado la eliminación
    if(isset($_POST['confirmacion']) && $_POST['confirmacion'] === "confirmado") {
        $sql = "TRUNCATE TABLE alum"; // Cambia 'nombre_tabla' por el nombre de tu tabla
        if ($conn->query($sql) === TRUE) {
            // Mensaje de éxito
            $message = "La tabla se ha limpiado correctamente.";
        } else {
            // Mensaje de error
            $message = "Error al limpiar la tabla: " . $conn->error;
        }
    } else {
        // Mensaje de confirmación
        $message = "Por favor, confirme que desea limpiar la tabla.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.E.I.A -> S.A.A.T. </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <script src="http://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>    
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#limpiar_btn").click(function(){
                $.post("<?php echo $_SERVER['PHP_SELF']; ?>", {limpiar_tabla: true}, function(data){
                    alert(data); // Muestra un mensaje de éxito o error
                });
            });
        });
    </script>

</head>
<body>

<div class="cargando">
    <div class="loader-outter"></div>
    <div class="loader-inner"></div>
</div>



   <!-- Menu-->
   <div class="container">
		  <div class="md-4" >
		   
      </div>
    </div>  
<!--   FIN MENU   --> 



  <P> </P>
  <div class="container mt-4">
	<h3 class="page-header text-center">IMPORTAR -> SIGE   <IMG SRC="../../img/logo_ceia.png" width="160" height="60" ALIGN=top></h3>

<div class="container">

<hr>
<br><br>


 <div class="row">
    <div class="col-md-5">
      <form action="recibe_excel_validando.php" method="POST" enctype="multipart/form-data"/>
      <!-- <h3 class="page-header text-center">CONSULTA ESTUDIANTE <IMG SRC="img/logo_ceia.png" width="160" height="60" ALIGN=top></h3> -->
      <h3 span>Elegir Archivo Excel</span></label></h3>
      <br><br>
     
        <div class="file-input text-center">
            <input  type="file" name="dataAlumno" id="file-input" class="file-input__input"/>
          <!--  <label class="file-input__label" for="file-input"> -->
              <label class="file-input__label" for="file-input">
              <i class="zmdi zmdi-upload zmdi-hc-2x"></i>
                       </div>
      <div class="text-center mt-2">
      <br> </br>
          <input type="submit" name="subir" class="btn btn-success btn-sm" value="Subir Excel"/>
      </div>

      </form>

      <div class="text-center mt-2">
        <!-- Mostrar el mensaje -->
        <?php if (!empty($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>

        <!-- Formulario para limpiar la tabla -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" class="btn btn-danger btn-sm" name="confirmacion" value="confirmado">
            <input type="submit" class="btn btn-danger btn-sm" name="limpiar_tabla" value="Limpiar Tabla" onclick="return confirm('¿Está seguro de que desea limpiar la tabla? Esta acción no se puede deshacer.');">
        </form>
        </div>
      </div>

    <div class="col-md-5">
  <?php
 // header("Content-Type: text/html;charset=utf-8");
 
  $sqlAlumnos = ("SELECT * FROM alum ORDER BY idalum ASC");
  $queryData   = mysqli_query($conn, $sqlAlumnos);
  $total_alum = mysqli_num_rows($queryData);
  ?>

      <h6 class="text-center">
        Cantidad de Alumnos <strong>(<?php echo $total_alum; ?>)</strong>
      </h6>

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
               <th>Run</th>
              <th>Nombres</th>
              <th>A_Paterno</th>
              <th>A_Materno</th>
              <th>Celular</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $i = 1;
            while ($data = mysqli_fetch_array($queryData)) { ?>
            <tr>
              <th scope="row"><?php echo $i++; ?></th>
              <td><?php echo $data['run']; ?></td>
              <td><?php echo $data['nombres']; ?></td>
              <td><?php echo $data['apaterno']; ?></td>
              <td><?php echo $data['amaterno']; ?></td>
              <td><?php echo $data['celular']; ?></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>

    </div>
  </div>

</div>


<script src="js/jquery.min.js"></script>
<script src="'js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(window).load(function() {
            $(".cargando").fadeOut(1000);
        });      
});
</script>
<div style = "height : 10px;"></div>
</div>
</div>

<script src="js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>

<!-- Bootstrap core JavaScript
    ================================================== -->   
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
