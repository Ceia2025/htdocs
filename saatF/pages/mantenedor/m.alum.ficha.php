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
//include_once '../../includes/conexion.php';
include '../../includes/mensajes.php';

include '../../includes/config.php';
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark" >
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
</head>
<body>

<!-- Menu-->
    <div class="container">
		  <div class="md-4" >
		   
      </div>
    </div>  
<!--   FIN MENU   --> 



<div class="container-fluid">
<?php
              //              include('config.php');
                            $idAlumno      = ($_REQUEST['id']);
                            //$idAlumn  o      = (int) filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
                            $sqlAlumnos   = ("SELECT * FROM alum WHERE run='$idAlumno'");
                          /*  $sqlAlumnos = ("SELECT * FROM alum 
                            LEFT JOIN alum_familia ON alum.run = alum_familia.run_alum_familia 
                            LEFT JOIN alum_sociales ON alum.run = alum_sociales.run_alum_sociales 
                            LEFT JOIN alum_emergencia ON alum.run = alum_emergencia.run_alum_emergencia WHERE run='$idAlumno'");
*/
                            $queryAlumnos = mysqli_query($conn, $sqlAlumnos);
                            $totalAlumnos = mysqli_num_rows($queryAlumnos);
                            ?>
                           <?php
                              while ($dataAlumno = mysqli_fetch_array($queryAlumnos)) {  
                            ?>


        <div class="row">                
<P> </P>
                    <div class="col-md-2">
                        <IMG SRC="../../img/logo_chico.jpg" width="190" height="130">
                    </div>

                    <div class="col-md-5">
                    <P> </P>
                        <h3 class="page-header text-center">ACTUALIZACIÓN DE FICHA DEL ESTUDIANTE</h3>
                    </div>

                    <div class="col-md-2">
                    <h6><label>Año Escolar: <input type="text"  disabled placeholder="No Registra" value="<?php echo $dataAlumno['anio']; ?>"></label></h6>                    
                   

                    <div class="col-md-1">
                        
                        
                    </div>
        </div>
<!--
        <div class="row">                
                    <div class="col-md-2">
                    </div>

                    <div class="col-md-7">
                    <h3 class="page-header text-center">FICHA DEL ESTUDIANTE</h3> 

                    </div>

                    <div class="col-md-3">
                    </div>
        </div>
</div>

                              -->

    
<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9">
                        <div class="card">
                            <div class="header">
<h4 class="title">1. IDENTIFICACIÓN DEL ALUMNO</h4>
<br>
                            </div>

                            <div class="content">
<!--  INICIO DEL FORMULARIO  -->                                  
<form action="c.alum.ficha.guardar.php" method="post">
<!--   FILA 1   -->             <div class="row">
                                    <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>N° Alumno</label></h6>
                                            <h6>   <input type="text" name="matricula" id="matricula" class="form-control" disabled placeholder="No Registra" value="<?php echo $dataAlumno['matricula']; ?>"</h6>
                                            </div>
                                    </div>  

                                    <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>F.Incorporación</label></h6>
                                            <h6>   <input type="date" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['fincorpora']; ?>"></h6>
                                            </div>
                                    </div>

                                    <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Apellido Paterno</label></h6>
                                            <h6>   <input type="text" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['apaterno']; ?>"></h6>
                                            </div>
                                    </div>

                                    <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Apellido Materno</label></h6>
                                            <h6>   <input type="text" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['amaterno']; ?>"></h6>
                                            </div>
                                    </div>    
                                    
                                            <div class="col-md-2">
                                      <!--       <a class="btn btn-success" ><i class="bi bi-filetype-pdf"></i> DESCARGAR</a>
                                           <a href="" target="_blank" class="btn btn-success" ><i class="bi bi-filetype-pdf"></i> DESCARGAR</a>  -->
                                            </div>

                                </div>


<!--   FILA 2   -->             <div class="row">
                                    <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Nombres</label></h6>
                                            <h6><input type="text" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['nombres']; ?>"></h6>
                                            </div>
                                    </div>

                                    <div class="col-md-4">
                                            <div class="form-group">
                                            <h6><label>Nivel / Ciclo</label></h6>
                                            <h6><input type="text" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['descgrado']; ?>"></h6>
                                            </div>
                                    </div>

                                    <div class="col-md-1">
                                            <div class="form-group">
                                            <h6><label>Letra</label></h6>
                                            <h6><input type="text" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['letracurso']; ?>"></h6>
                                            </div>
                                    </div>

                                    <div class="col-md-3">
                                    <div class="form-group">
                                            <h6><label>Tipo de Enseñanza</label></h6>
                                            <h6><select class form-select name="tipoensenanza" id="tipoensenanza" class="form-control" disabled placeholder="Sin Registro">
                                            <?php
                                        //    require_once "config.php";
                                            $consulta_alum = "SELECT tipoensenanza FROM alum where  run='$idAlumno'";
                                            $resultado_alum = $conn->query($consulta_alum);
                                            //$tipoensenanza_actual = "";
                                           
                                            if ($resultado_alum->num_rows > 0) 
                                                {
                                            $fila_alum = $resultado_alum->fetch_assoc();
                                            $tipoensenanza_actual = $fila_alum['tipoensenanza'];
                                                }
                                            $opciones_adicionales = array("Ed. Básica Adultos con Oficio", "Ed. Básica Adultos sin Oficio", "Ed. Media HC Adultos", "Ed.Media Adultos TP industrial", "Ed. Media Adultos TP Técnica");
                                            ?>
                                            <?php
                                            echo "<option value='$tipoensenanza_actual'>$tipoensenanza_actual</option>";
                                         
                                            foreach ($opciones_adicionales as $opcion) 
                                                {
// Omitir la opción actual si ya se mostró como la primera opción
                                            if ($opcion !== $tipoensenanza_actual)   
                                                {
                                            echo "<option value='$opcion'>$opcion</option>";
                                                }
                                            }                                            
                                            ?>
                                            </select></h6>
                                            <br>
                                        </div>
                                    </div>
                                </div>


<!--   FILA 3   -->             <div class="row">
<div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Especialidad/Oficio</label></h6>
                                            <h6><select class form-select name="especialidad" id="especialidad" class="form-control" disabled placeholder="Sin Registro">
                                            <?php
                                          //  require_once "config.php";
                                            $consulta_alum = "SELECT especialidad FROM alum where  run='$idAlumno'";
                                            $resultado_alum = $conn->query($consulta_alum);
                                           $especialidad_actual = "";
     
                                            if ($resultado_alum->num_rows > 0) 
                                                {
                                            $fila_alum = $resultado_alum->fetch_assoc();
                                            $especialidad_actual = $fila_alum['especialidad'];
                                                }
                                            $opciones_adicionales = array("No Aplica", "Sin Oficio", "Ayudante Instalador Eléctrico", "Electricidad", "Atención de Párvulos");
                                            ?>
                                            <?php
                                          echo "<option value='$especialidad_actual'>$especialidad_actual</option>";
                                            foreach ($opciones_adicionales as $opcion) 
                                                {
// Omitir la opción actual si ya se mostró como la primera opción
                                            if ($opcion !== $especialidad_actual)   
                                                {
                                            echo "<option value='$opcion'>$opcion</option>";
                                                }
                                            }                                            
                                            ?>
                                            </select></h6>
                                            <br>
                                            </h6>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Cód. Enseñanza</label></h6>
                                            <h6><input type="text" class="form-control" name="codtipoense" id="codtipoense" disabled placeholder="Sin Registro" value= "<?php echo $dataAlumno['codtipoense']; ?>"></h6>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Run</label></h6>
                                            <h6><input type="text" class="form-control" name="run" id="run" disabled value="<?php echo $dataAlumno['run']; ?>-<?php echo $dataAlumno['digv']; ?>"></h6>
                                           
                                        </div>
                                        </div>

  <input type="hidden" name="idalum" value="<?php echo $dataAlumno['run']; ?>">
    
    
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>F. Nacimiento</label></h6>
                                            <h6>   <input type="date" class="form-control" name="fnac" id="fnac"disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['fnac']; ?>"></h6>
                                            </div>
                                        </div>
                                                                                
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Edad</label></h6>
                                            <h6><input type="text" class="form-control" name="edad" id="edad" disabled placeholder="Sin Registro" value="<?php		
//incluimos el fichero de conexion
                                            include_once '../../includes/conexion.php';
                                          
                                            $database = new Connection();
                                            $db = $database->open();
                                            try{	
                                            $sql = "SELECT fnac FROM alum WHERE run='$idAlumno'";
                                            foreach ($db->query($sql) as $row)
                                            {
// Calcular la edad para cada registro
                                            $fecha_nacimiento = new DateTime($row['fnac']);
                                            $fecha_actual = new DateTime();
                                            $diferencia = $fecha_nacimiento->diff($fecha_actual);
// Almacenar los resultados en un array
                                            $edad_array = array(
                                            'años' => $diferencia->y,
                                            'meses' => $diferencia->m,
                                            'días' => $diferencia->d
                                            );
                                            echo "" . $edad_array['años'] . " años," . $edad_array['meses'] . " meses, " . $edad_array['días'] . " días"; 
//Cerrar la Conexion                            
                                            $database->close();
                                            ?>"></h6>
                                            </div>
                                        </div>                                         
                                </div>
                       


<!--   FILA 4   -->             <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Edad al 30 de Junio</label></h6>
                                            <h6><input type="text" class="form-control" name="edad30junio" id="edad30junio"disabled placeholder="Sin Registro" value="<?php		
//incluimos el fichero de conexion
include_once '../../includes/conexion.php';
                                            $database = new Connection();
                                            $db = $database->open();                                    	
                                            $sql = "SELECT fnac FROM alum WHERE run='$idAlumno'";
                                            foreach ($db->query($sql) as $row)
                                            {
//Calcular la edad para cada registro
                                            $fecha_nacimiento = new DateTime($row['fnac']);
                                            $fecha_actual = new DateTime();
                                            $fecha_fin_periodo = new DateTime(date('Y') . '-06-30');
                                            $diferencia_edad = $fecha_nacimiento->diff($fecha_fin_periodo);
                                            $edad = $diferencia_edad->y;
                                            echo $edad;
                                            $database->close();
                                            }                                         
                                            ?>"></h6>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Celular 1</label></h6>
                                            <h6>   <input type="text" name="celular1" id="celular1" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['celular']; ?>"></h6>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Celular 2</label></h6>
                                            <h6><input type="text" name="celular2" id="celular2" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['telefono']; ?>"></h6>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                        <div class="form-group">
                                            <h6><label>Estado Civil</label></h6>
                                            <h6><select class form-select name="estado_civil" id="estado_civil" class="form-control" disabled placeholder="Sin Registro">
                                            <?php

                                          //  require_once "config.php";
                                            $consulta_alum = "SELECT estado_civil, run FROM alum where run='$idAlumno'";
                                            $resultado_alum = $conn->query($consulta_alum);
                                            $estado_civil_actual = "";

                                            if ($resultado_alum->num_rows > 0) {
// Si hay filas en la tabla alum, obtener el estado civil actual
                                            $fila_alum = $resultado_alum->fetch_assoc();
                                            $estado_civil_actual = $fila_alum['estado_civil'];
                                            }
                                            $opciones_adicionales = array("Soltero/a", "Casado/a", "Divorciado/a", "Viudo/a", "Separado/a");
                                            ?>

                                            <?php
// Mostrar el estado civil actual de la tabla alum como la primera opción
                                            echo "<option value='$estado_civil_actual'>$estado_civil_actual</option>";
// Iterar sobre las opciones adicionales y generar opciones para el ComboBox
                                            foreach ($opciones_adicionales as $opcion) 
                                            {
// Omitir la opción actual si ya se mostró como la primera opción
                                            if ($opcion !== $estado_civil_actual)   
                                            {
                                            echo "<option value='$opcion'>$opcion</option>";
                                            }
                                            }
                                            ?>
                                            </select></h6>
                                            <br>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                        <h6><label>Nombre del Cónyuge</label></h6>
                                            <?php
                                               // require_once "config.php";                                        
                                                $sql = "SELECT nomconyuge FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $nomconyuge = $row["nomconyuge"];
                                            }else{
                                               $nomconyuge = "";
                                            }
                                                ?> 
                                            <input type="text" class="form-control" name="nomconyuge" id="nomconyuge" disabled placeholder="Sin Registro" value="<?php echo $nomconyuge;?>">
                                        </div>
                                    </div>
                                </div>
                                       

                                <div class="row">
                                <div class="col-md-2">
                                        <div class="form-group">
                                        <h6><label>Vive con</label></h6>                    
                                        <h6><select class form-select name="vivecon" id="vivecon" class="form-control" disabled placeholder="Sin Registro">           
                                           <?php 

//require_once "config.php";

$consulta_alum = "SELECT vivecon FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$vivecon_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$vivecon_actual = $fila_alum['vivecon'];
}
$opciones_adicionales = array("Madre", "Padre",  "Esposo (a)", "Hijo (s)", "Sus Padres", "Abuelo (s)", "Sólo (a)", "Hermano (s)", "Tio (s)", "Pareja", "Residencia", "Otro");
?>

<?php
echo "<option value='$vivecon_actual'>$vivecon_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $vivecon_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>

                                            <br>
                                        </div>
                                    </div>



                                    <div class="col-md-2">
                                        <div class="form-group">
                                        <h6><label>Observaciones</label></h6>
                                            <?php
                                              //  require_once "config.php";                                        
                                                $sql = "SELECT viveconobs FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $viveconobs = $row["viveconobs"];
                                            }else{
                                               $viveconobs = "";
                                            }
                                                ?> 
                                            <input type="text" class="form-control" name="viveconobs" id="viveconobs" disabled placeholder="Sin Registro" value="<?php echo $viveconobs;?>">
                                        </div>
                                    </div>
                                


                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Nacionalidad</label></h6>                                           
                                            <h6><select class form-select name="nacionalidad" id="nacionalidad" class="form-control" disabled placeholder="Sin Registro">
                                            <option value="">Seleccionar</option>
                                           <?php 

                                       //    require_once "config.php";                                        
 // Consulta para obtener los datos de la tabla 'nacionalidad' para el combobox
                                            $sql_nacionalidades = "SELECT idnacionalidad, gentilicio_nac FROM nacionalidad";
                                            $result_nacionalidades = $conn->query($sql_nacionalidades);

// Consulta para obtener el dato de la tabla 'alum' para seleccionar la opción predeterminada
                                            $sql_alum_nacionalidad = "SELECT nacionalidad FROM alum where run=$idAlumno";
                                            $result_alum_nacionalidad = $conn->query($sql_alum_nacionalidad);

                                            $row_alum_nacionalidad = $result_alum_nacionalidad->fetch_assoc();
                                            $nacionalidad_id_predeterminada = $row_alum_nacionalidad['nacionalidad'];
                                         // if($nacionalidad_id_predeterminada == 0)
                                           // {
                                            //    echo '<option value="0" selected>Sin Registro</option>';
                                            //}
                                           // else
                                            
// Verificar si hay resultados de la tabla 'nacionalidad' y mostrarlos en un combobox
                                            if ($result_nacionalidades->num_rows > 0) 
                                            {
//    echo '<select name="nacionalidad">';
                                            while ($row = $result_nacionalidades->fetch_assoc()) 
                                            {
                                            $id_nacionalidad = $row["idnacionalidad"];
                                            $gentilicio_nac = $row["gentilicio_nac"];
                                            
// Si el ID de la nacionalidad coincide con el predeterminado, marcarlo como seleccionado
                                            $selected = ($id_nacionalidad == $nacionalidad_id_predeterminada) ? "selected" : "";
//                                          echo '<option value="' . $id_nacionalidad . '" ' . $selected . '>' . $gentilicio_nac . '</option>';
                                            echo '<option value="' . $id_nacionalidad . '" ' . $selected . '>' . $gentilicio_nac . '</option>';
                                            }
   //echo '</select>';
                                            } 
                                            
                                            ?>
                                            </select></h6>
                                            <br>
                                            </h6>
                                            
                                            </div>
                                        </div>

                                            
                                        <div class="col-md-1">
                                            <div class="form-group">
                                            <h6><label>Genero</label></h6>
                                            <h6><input type="text" class="form-control" name="genero" id="genero" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['genero']; ?>"></h6>
                                            </div>
                                        </div>
                                        


                                        <div class="col-md-2">
                                        <div class="form-group">
                                        <h6><label>Cantidad de Hijos</label></h6>
                                        <?php
                                             //   require_once "config.php";                                        
                                                $sql = "SELECT nhijos FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $nhijos = $row["nhijos"];
                                            }else{
                                               $nhijos = "";
                                            }
                                                ?>    
                                            <input type="text" class="form-control" name="nhijos" id="nhijos" disabled placeholder="Sin Registro" value="<?php echo $nhijos;?>">



                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Hijos > 18</label></h6>
                                            <?php
                                              //  require_once "config.php";                                        
                                                $sql = "SELECT hijosmayores FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $hijosmayores = $row["hijosmayores"];
                                            }else{
                                               $hijosmayores = "";
                                            }
                                                ?>    
                                                <input type="text" class="form-control" name="hijosmayores" id="hijosmayores" disabled placeholder="Sin Registro" value="<?php echo $hijosmayores;?>">

                                            </div>
                                        </div>
                                </div>




<!--   FILA 5   -->             <div class="row">       
<div class="col-md-1">
                                        <div class="form-group">
                                            <h6><label>Trabaja</label></h6>
                                            <h6><select class form-select name="trabaja" id="trabaja" class="form-control" disabled placeholder="Sin Registro">
                                            <?php

                                          //  require_once "config.php";
                                            $consulta_alum = "SELECT trabaja FROM alum where run='$idAlumno'";
                                            $resultado_alum = $conn->query($consulta_alum);
                                            $trabaja_actual = "";

                                            if ($resultado_alum->num_rows > 0) {
// Si hay filas en la tabla alum, obtener el estado civil actual
                                            $fila_alum = $resultado_alum->fetch_assoc();
                                            $trabaja_actual = $fila_alum['trabaja'];
                                            }
                                            $opciones_adicionales = array("SI", "NO");
                                            ?>

                                            <?php
// Mostrar el estado civil actual de la tabla alum como la primera opción
                                            echo "<option value='$trabaja_actual'>$trabaja_actual</option>";
// Iterar sobre las opciones adicionales y generar opciones para el ComboBox
                                            foreach ($opciones_adicionales as $opcion) 
                                            {
// Omitir la opción actual si ya se mostró como la primera opción
                                            if ($opcion !== $trabaja_actual)   
                                            {
                                            echo "<option value='$opcion'>$opcion</option>";
                                            }
                                            }
                                            ?>
                                            </select></h6>
                                            <br>
                                            </div>
                                        </div>


                                        <div class="col-md-5">
                                            <div class="form-group">
                                            <h6><label>Dirección</label></h6>
                                            <h6><input type="text" name="direccion" id="direccion" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['direcc']; ?>"></h6>
                                            </div>
                                        </div>







                                        <div class="col-md-2">
                                            <div class="form-group">

                                            <h6><label>Comuna</label></h6>
                                                <input type="text" class="form-control" name="comunaestudiante" id="comunaestudiante" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['comuna']; ?>"></h6>
                                            </div>
                                        </div>


                   <!--                     <div class="col-md-1">
                                            <div class="form-group">
                                            <h6><label>Cod-Com</label></h6>
                                                <input type="text" class="form-control" name="codcomunaestudiante" id="codcomunaestudiante" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['codcomuna']; ?>"></h6>
                                            </div>
                     --                   </div>
                                        -->

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Sector</label></h6>                                            
                                            <h6><select class form-select name="sectorestudiante" id="sectorestudiante" class="form-control" disabled placeholder="Sin Registro">
                                            <?php
                                        //    require_once "config.php";
                                            $consulta_alum = "SELECT sectorestudiante FROM alum where  run='$idAlumno'";
                                            $resultado_alum = $conn->query($consulta_alum);
                                           $sectorestudiante_actual = "";
     
                                            if ($resultado_alum->num_rows > 0) 
                                                {
                                            $fila_alum = $resultado_alum->fetch_assoc();
                                            $sectorestudiante_actual = $fila_alum['sectorestudiante'];
                                                }
                                            $opciones_adicionales = array("Urbano", "Rural");
                                            ?>
                                            <?php
                                          echo "<option value='$sectorestudiante_actual'>$sectorestudiante_actual</option>";
                                            foreach ($opciones_adicionales as $opcion) 
                                                {
// Omitir la opción actual si ya se mostró como la primera opción
                                            if ($opcion !== $especialidad_actual)   
                                                {
                                            echo "<option value='$opcion'>$opcion</option>";
                                                }
                                            }                                            
                                            ?>
                                            </select></h6>
                                            <br>
                                            </h6>
                                            </div>

                                        </div>
                                    </div>

<!--   FILA 6   -->             <div class="row">                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <h6><label>Georreferenciación </label></h6>
                                            <h6><input type="text" name="georreferencia" id="georreferencia" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['georreferencia']; ?>"></h6>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <h6><label>Email</label></h6>
                                            <h6><input type="text" name="email" id="email" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['email']; ?>"></h6>
                                            </div>
                                        </div>
                                </div>

<!--   FILA 7    
VERIFICAR BOTONES-->  
                               <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                         
                                            </div>
                                        </div>    
                               
                                        <div class="col-md-2">
                                            <div class="form-group">
                            
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">    
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            </div>
                                        </div>
                                </div>

                            
<!--   FILA 8   -->             <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                           
                                            </div>
                                        </div>    

                                        <div class="col-md-3 text-center" style="padding-top:2%;">
                  <input type="button" class="btn btn-primary" class="bi bi-arrow-repeat" value="Actualizar" class="bi bi-arrow-repeat" name="actualizar" id="actualizar" onclick="habilitarCampos()">
                                        </div>

                                        <div class="col-md-3 text-center" style="padding-top:2%;">
                                        <input type="submit" value="Guardar" class="btn btn-success" class="bi bi-cloud-upload" style="display: none;" class="bi bi-cloud-upload"></i>
                                        </div>
                                                                                                                                              
                                        <div class="col-md-3">
                                            <div class="form-group">
                                           
                                            </div>
                                        </div>    
                                </div>
                                </div>


<!-- LINEA HORIZONTAL -->       <div class="row justify-content-md-center">
                                        <div class="col-md-12">
                                        <hr class="mb-3">                                
                                        </div>
                                </div>


<h4 class="title">2. ANTECEDENTES ESCOLARES </h4>
<br>
<!--   FILA 9   -->             <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">


                                            
                                            <h6><label>Región</label></h6>
                                           <h6><select class form-select name="region" id="region" class="form-control" disabled></h6>
                                           <option value="">Seleccionar Región</option>
<?php
//require_once "config.php";
$region = $conn->query("SELECT idregiones, cod_region, nom_region FROM regiones");

$sql_alum_region = "SELECT region from alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum_region = $conn->query($sql_alum_region);

$row_alum_region = $resultado_alum_region->fetch_assoc();
$region_id_predeterminada = $row_alum_region['region'];

if ($region->num_rows > 0)
{
                                           while ($row = $region->fetch_assoc()) 
                                           { 
                                            $id_cod_region = $row["cod_region"];
                                            $nom_region = $row["nom_region"];
                                        
                                            $selected = ($id_cod_region == $region_id_predeterminada) ? "selected" : "";
                                            echo '<option value="' . $id_cod_region . '" ' . $selected . '>' . $nom_region . '</option>';
                                           }
                                        }
                                            ?>
                                           </select>
                                            
                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Comuna</label></h6>
<?php                      
$sql_cod_comuna_existente  = "SELECT comuna FROM alum_sociales WHERE run_alum_sociales = '$idAlumno'"; // Cambia '$id_alumno' por el ID del alumno
$result_cod_comuna_existente = $conn->query($sql_cod_comuna_existente);

// Verificar si se encontraron resultados
if ($result_cod_comuna_existente->num_rows > 0) {
    // Si hay resultados, obtener el código de comuna existente
    $row_cod_comuna_existente = $result_cod_comuna_existente->fetch_assoc();
    $cod_comuna_existente = $row_cod_comuna_existente["comuna"];
} else {
    // Si no hay resultados, establecer un valor predeterminado
    $cod_comuna_existente = "";
}

// Obtener el nombre de la comuna existente
$sql_comuna_existente = "SELECT nom_comuna FROM regcomuna WHERE cod_comuna = '$cod_comuna_existente'";
$result_comuna_existente = $conn->query($sql_comuna_existente);

// Verificar si se encontraron resultados
if ($result_comuna_existente->num_rows > 0) {
    // Si hay resultados, obtener el nombre de la comuna existente
    $row_comuna_existente = $result_comuna_existente->fetch_assoc();
    $comuna_existente = $row_comuna_existente["nom_comuna"];
} else {
    // Si no hay resultados, establecer un valor predeterminado
    $comuna_existente = "";
}
?>


<h6><select class form-select name="comunaestablecimiento" id="comunaestablecimiento" class="form-control" disabled>
<option value="<?php echo $cod_comuna_existente; ?>"><?php echo $comuna_existente; ?></option>
<!-- Agrega un separador -->
<option disabled>─────</option>
<option value="">Seleccione una comuna</option>
        <!-- Aquí cargas las opciones adicionales de comuna -->
        <?php
        $sql_comunas = "SELECT cod_comuna, nom_comuna FROM regcomuna";
        $result_comunas = $conn->query($sql_comunas);
        while ($row_comuna = $result_comunas->fetch_assoc()) {
            $comuna_id = $row_comuna["cod_comuna"];
            $comuna_nombre = $row_comuna["nom_comuna"];
            // Si la comuna ya está seleccionada, no la agregamos nuevamente
            if ($comuna_id != $cod_comuna_existente) {
                echo "<option value='$comuna_id'>$comuna_nombre</option>";
            }
        }
        ?>
<!-- Opciones de comunas se cargarán dinámicamente mediante JavaScript -->
                                            </select>
                                            </h6>  
                                            </div>
                                        </div>


                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <h6><label>Procedencia Establecimiento</label></h6>
<?php                      
$sql_establecieminto = "SELECT establecimiento FROM alum_sociales WHERE run_alum_sociales = '$idAlumno'"; // Cambia '$id_alumno' por el ID del alumno
$resultado_existente = $conn->query($sql_establecieminto);
// Verificar si se encontraron resultados
if ($resultado_existente->num_rows > 0) {
    // Si hay resultados, obtener la comuna existente
    $row_establecimiento_existente = $resultado_existente->fetch_assoc();
    $establecieminto_existente = $row_establecimiento_existente["establecimiento"];
} else {
    // Si no hay resultados, establecer un valor predeterminado
    $establecieminto_existente = "";
}

// Obtener el nombre del establecimiento existente
$sql_establecimiento_existente = "SELECT nom FROM establecimientos WHERE rbd = '$establecieminto_existente'";
$result_establecimiento_existente = $conn->query($sql_establecimiento_existente);
// Verificar si se encontraron resultados
if ($result_establecimiento_existente->num_rows > 0) {
    // Si hay resultados, obtener el nombre de la comuna existente
    $row_establecimiento_existente = $result_establecimiento_existente->fetch_assoc();
    $establecimiento = $row_establecimiento_existente["nom"];
} else {
    // Si no hay resultados, establecer un valor predeterminado
    $establecimiento= "";
}
?>
  
<h6><select class form-select name="establecimiento" id="establecimiento" class="form-control" disabled>
<option value="<?php echo $establecieminto_existente; ?>"><?php echo $establecimiento; ?></option>
<!-- Agrega un separador -->
<option disabled>─────</option>
<option value="">Seleccione una Establecimiento</option>
<!-- Opciones de establecimientos se cargarán dinámicamente mediante JavaScript -->
                                            </select></h6>                                            
                                            </div>
                                        </div>
                                </div>






<!--   FILA 10   -->           <div class="row">   

                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <h6><label>Observación Establecimiento</label></h6>
                                            <?php
                                                                                    
                                                $sql = "SELECT obsestablecimiento FROM alum_sociales WHERE run_alum_sociales = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $obsestablecimientoactual = $row["obsestablecimiento"];
                                            }else{
                                               $obsestablecimientoactual = "";
                                            }
                                                ?> 



                                            <h6><input type="text" name="obsestablecimiento" id="obsestablecimiento" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $obsestablecimientoactual;?>">
                                            </div>
                                        </div>



                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Ultimo Curso Apro</label></h6>
                                            <h6><select class form-select name="ultimocursoaprobado" id="ultimocursoaprobado" disabled >
                             
    
                                                <?php 

//require_once "config.php";

$consulta_alum = "SELECT ultimocursoaprobado FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
//$ultimocursoaprobado_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$ultimocursoaprobado_actual = $fila_alum['ultimocursoaprobado'];
}
$opciones_adicionales = array("1°", "2°", "3°", "4°", "5°", "6°", "7°", "8°", "1 Medio",
"2° Medio", "3° Medio", "4° Medio");
?>

<?php
echo "<option value='$ultimocursoaprobado_actual'>$ultimocursoaprobado_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $ultimocursoaprobado_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}

?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <?php
// Suponiendo que $ultimo_anio_estudio contiene el valor almacenado en la tabla alum_sociales
$ultimo_anio_estudio = "No Sabe"; // Por defecto, si no hay ningún valor almacenado

// Consulta para obtener el último año de estudio
$sql = "SELECT ultimoanioestudio FROM alum_sociales  where run_alum_sociales ='$idAlumno'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si hay resultados, obtener el valor del último año de estudio
    $row = $result->fetch_assoc();
    $ultimo_anio_estudio = $row["ultimoanioestudio"];
}
?>


                                            <h6><label>Ulti año Estudio</label></h6>
                                            <h6><select class form-select name="ultimoanioestudio" id="ultimoanioestudio" class="form-control" disabled placeholder="Sin Registro">
                                            <?php
    // Mostrar el valor almacenado en la tabla como primera opción
    echo "<option value='$ultimo_anio_estudio'>$ultimo_anio_estudio</option>";

    // Obtener el año actual
    $anio_actual = date("Y");

    // Generar opciones desde el año actual hasta 1950
    for ($i = $anio_actual; $i >= 1950; $i--) {
        echo "<option value='$i'>$i</option>";
    }

    // Agregar opción "No Sabe"
    echo "<option value='No Sabe'>No Sabe</option>";
    ?>
</select>







</h6>
<br>
</div>
                                        </div>
                                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Repetidos</label></h6>
                                        <h6><select class form-select name="cursosrepetido" id="cursosrepetido" disabled >
                                     
    
                                                <?php 

//require_once "config.php";

$consulta_alum = "SELECT cursosrepetido FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$cursosrepetido_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$cursosrepetido_actual = $fila_alum['cursosrepetido'];
}
$opciones_adicionales = array("0", "1", "2", "3", "4", "5", "+ de 5");
?>

<?php
echo "<option value='$cursosrepetido_actual'>$cursosrepetido_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $cursosrepetido_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}

?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>20 %</label></h6>
                                            <h6><select class form-select name="veinteporciento" id="veinteporciento" class="form-control" disabled placeholder="Sin Registro">
                                          
                                           <?php 

//require_once "config.php";

$consulta_alum = "SELECT veinteporciento FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$veinteporciento_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$veinteporciento_actual = $fila_alum['veinteporciento'];
}
$opciones_adicionales = array("SI", "NO");
?>

<?php
echo "<option value='$veinteporciento_actual'>$veinteporciento_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $veinteporciento_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Informe del 20%</label></h6>
                                            <h6><select class form-select name="informe20porciento" id="informe20porciento" class="form-control" disabled placeholder="Sin Registro">
                                            
                                           <?php 

//require_once "config.php";

$consulta_alum = "SELECT informe20porciento FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$infoveinteporciento_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$infoveinteporciento_actual = $fila_alum['informe20porciento'];
}
$opciones_adicionales = array("SI", "NO", "PENDIENTE");
?>

<?php
echo "<option value='$infoveinteporciento_actual'>$infoveinteporciento_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $infoveinteporciento_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>
                              



                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Pertenece Etnia</label></h6>
                                            <h6><select class form-select name="perteneceetnia" id="perteneceetnia" class="form-control" disabled placeholder="Sin Registro">
                                           
                                           <?php 

//require_once "config.php";

$consulta_alum = "SELECT perteneceetnia FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$perteneceetnia_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$perteneceetnia_actual = $fila_alum['perteneceetnia'];
}
$opciones_adicionales = array("SI", "NO");
?>

<?php
echo "<option value='$perteneceetnia_actual'>$perteneceetnia_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $perteneceetnia_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <h6><label>¿Que Pueblo Originario?</label></h6>
                                            <h6><select class form-select name="pueblooriginario" id="pueblooriginario" class="form-control" disabled placeholder="Sin Registro">
                                           
                                           <?php 

//require_once "config.php";

$consulta_alum = "SELECT pueblooriginario FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$pueblooriginario_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$pueblooriginario_actual = $fila_alum['pueblooriginario'];
}
$opciones_adicionales = array("No Pertenece", "Colla", "Diaguita ", "Rapa Nui", "Mapuche", "Kawásqar", "Yagán");
?>

<?php
echo "<option value='$pueblooriginario_actual'>$pueblooriginario_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $pueblooriginario_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Embarazo</label></h6>
                                            <h6><select class form-select name="embarazo" id="embarazo" class="form-control" disabled placeholder="Sin Registro">
                                            
                                           <?php 
//require_once "config.php";

$consulta_alum = "SELECT embarazo FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$embarazo_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$embarazo_actual = $fila_alum['embarazo'];
}
$opciones_adicionales = array("SI", "NO");
?>

<?php
echo "<option value='$embarazo_actual'>$embarazo_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $embarazo_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>
                               
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Semanas</label></h6>
                                                <h6><select class form-select name="semanas" id="semanas" class="form-control" disabled placeholder="Sin Registro">
                                                
                                           <?php 

//require_once "config.php";

$consulta_alum = "SELECT semanas FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$semanas_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$semanas_actual = $fila_alum['semanas'];
}
$opciones_adicionales = array("5 Semanas", "6 Semanas", "7 Semanas", "8 Semanas", "9 Semanas", "10 Semanas", "11 Semanas", "12 Semanas", "13 Semanas",
"14 Semanas", "15 Semanas", "16 Semanas", "17 Semanas", "18 Semanas", "19 Semanas", "20 Semanas", "21 Semanas", "22 Semanas", "23 Semanas",
"24 Semanas", "25 Semanas", "26 Semanas", "27 Semanas", "28 Semanas", "29 Semanas", "30 Semanas", "31 Semanas", "32 Semanas", "33 Semanas",
 "34 Semanas", "35 Semanas", "36 Semanas", "37 Semanas");
?>

<?php
echo "<option value='$semanas_actual'>$semanas_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $semanas_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>
                                </div>

<!--   FILA 12   -->           <div class="row">              
<div class="col-md-12">
                                            <div class="form-group">

                                            <?php
                                      //  require_once "config.php";  
                                            $sql = "SELECT infosalud FROM alum_sociales WHERE run_alum_sociales = '$idAlumno'";
                                            $resultado_alum = $conn->query($sql);
                                                                          
                                            if ($resultado_alum->num_rows > 0) {
                                            $fila_alum = $resultado_alum->fetch_assoc();
                                            $infosalud_actual = $fila_alum['infosalud'];
                                                }
                                            else 
                                            echo        
                                            $infosalud_actual = "";
                                            ?>
                                            <h6><label>información de Salud</label></h6>
                                            <h6><textarea rows="3" name="infosalud" id="infosalud" class="form-control" disabled ><?php echo $infosalud_actual;?></textarea></h6>
                                            </h6>
                                            </div>
                                        </div>
                                 </div> 

<!--   FILA 13   -->           <div class="row">                                      
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Ev. Psicológica / Psicopedagóga</label></h6>
                                            <h6><select class form-select name="evpsicologica" id="evpsicologica" class="form-control" disabled placeholder="Sin Registro">
                                         
                                          <?php 
//require_once "config.php";

$consulta_alum = "SELECT evpsicologica FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$evpsicologica_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$evpsicologica_actual = $fila_alum['evpsicologica'];
}
$opciones_adicionales = array("No Aplica", "Psicológica", "Psicopedagógica", "Sin Evaluación");
?>

<?php
echo "<option value='$evpsicologica_actual'>$evpsicologica_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $evpsicologica_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Problemas de Aprendizaje</label></h6>
                                            <h6><select class form-select name="probleaprendizaje" id="probleaprendizaje" class="form-control" disabled placeholder="Sin Registro">
                                            
                                           <?php 
//require_once "config.php";

$consulta_alum = "SELECT problemaaprendizaje FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$problemaaprendizaje_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$problemaaprendizaje_actual = $fila_alum['problemaaprendizaje'];
}
$opciones_adicionales = array("Sin", "Con");
?>

<?php
echo "<option value='$problemaaprendizaje_actual'>$problemaaprendizaje_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $problemaaprendizaje_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="form-group">
                                            <h6><label>P.I.E.</label></h6>
                                            <h6><select class form-select name="pie" id="pie" class="form-control" disabled placeholder="Sin Registro">
                                           
                                           <?php 
                                         //  require_once "config.php";

$consulta_alum = "SELECT pie FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$pie_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$pie_actual = $fila_alum['pie'];
}
$opciones_adicionales = array("SI", "NO");
?>

<?php
echo "<option value='$pie_actual'>$pie_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $pie_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Chile Solidario</label></h6>
                                                <h6><select class form-select name="chilesolida" id="chilesolida" class="form-control" disabled placeholder="Sin Registro">
                                               
                                           <?php 
//require_once "config.php";

$consulta_alum = "SELECT chilesolida FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$chilesolida_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$chilesolida_actual = $fila_alum['chilesolida'];
}
$opciones_adicionales = array("Si", "No", "No Sabe", "No Aplica");
?>

<?php
echo "<option value='$chilesolida_actual'>$chilesolida_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $chilesolida_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>¿Cúal?</label></h6>
                                                <h6><select class form-select name="chilesolidacual" id="chilesolidacual" class="form-control" disabled placeholder="Sin Registro">
                                            
                                           <?php 
//require_once "config.php";

$consulta_alum = "SELECT chilesolidacual FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$chilesolidacual_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$chilesolidacual_actual = $fila_alum['chilesolidacual'];
}
$opciones_adicionales = array("Priotario", "Preferente", "Incremento", "Pro-retención");
?>

<?php
echo "<option value='$chilesolidacual_actual'>$chilesolidacual_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $chilesolidacual_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>
                  
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Sistema de Salud</label></h6>
                                                <h6><select class form-select name="sistemasalud" id="sistemasalud" class="form-control" disabled placeholder="Sin Registro">
                                            
                                           <?php 
//require_once "config.php";

$consulta_alum = "SELECT sistemasalud FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$sistemasalud_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$sistemasalud_actual = $fila_alum['sistemasalud'];
}
$opciones_adicionales = array("No Sabe", "Fonasa", "Banmedica", "Cruz Blanca", "Colmena", "Más Vida", "ConSalud", "Vida tres", "Dipreca");
?>

<?php
echo "<option value='$sistemasalud_actual'>$sistemasalud_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $sistemasalud_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>
                                </div>


<!--   FILA 14   -->           <div class="row">                                       
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Letra Fonasa</label></h6>
                                                <h6><select class form-select name="letrafonasa" id="letrafonasa" class="form-control" disabled placeholder="Sin Registro">
                                              
                                           <?php 
//require_once "config.php";

$consulta_alum = "SELECT letrafonasa FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$sistemasalud_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$letrafonasa_actual = $fila_alum['letrafonasa'];
}
$opciones_adicionales = array("No Sabe", "A", "B", "C", "D");
?>

<?php
echo "<option value='$letrafonasa_actual'>$letrafonasa_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $letrafonasa_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Seguro de Salud</label></h6>
    
                                                <h6><select class form-select name="segurosalud" id="segurosalud" class="form-control" disabled placeholder="Sin Registro">
                                               
                                           <?php 
//require_once "config.php";

$consulta_alum = "SELECT segurosalud FROM alum_sociales where run_alum_sociales ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$segurosalud_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$segurosalud_actual = $fila_alum['segurosalud'];
}
$opciones_adicionales = array("No", "Si", "No Sabe");
?>

<?php
echo "<option value='$segurosalud_actual'>$segurosalud_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $segurosalud_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>¿Cúal?</label></h6>
                                            <?php
                                           //     require_once "config.php";                                        
                                                $sql = "SELECT segurocual FROM alum_sociales WHERE run_alum_sociales = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $segurocual = $row["segurocual"];
                                            }else{
                                               $segurocual = "";
                                            }
                                                ?> 

                                            <input type="text" name="segurocual" id="segurocual" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $segurocual;?>">

                                                
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">   
                                                <h6><label>Reg.Soc.Hogares</label></h6>
                                                <h6><select class form-select name="regsocial" id="regsocial" class="form-control" disabled>
             <?php 

//require_once "config.php";

$consulta_alum = "SELECT regsocial FROM alum_sociales WHERE run_alum_sociales = '$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$reghogares_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$reghogares_actual = $fila_alum['regsocial'];
}
$opciones_adicionales = array("10%","20%", "30%", "40%", "50%", "60%", "70%", "80%", "90%", "100%");
?>

<?php
echo "<option value='$reghogares_actual'>$reghogares_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $reghogares_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
          </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <!--       
                                                <h6><label></label></h6>
                                                <input type="text" name="repitecurso" id="repitecurso" class="form-control" disabled placeholder="Sin Registro" value="">
                                                -->
                                            </div>
                                        </div>
                                </div>

                                <div class="row justify-content-md-center">
                                    <div class="col-md-12">
<!-- LINEA HORIZONTAL -->             <hr class="mb-3">                                
                                    </div>
                                </div>





<h4 class="title">3. ANTECEDENTES FAMILIARES</h4>
<br>

<!--   FILA 15   -->            <div class="row">                                      
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Nivel Educacional Padre</label></h6>
                 <h6><select class form-select name="neducpadre" id="neducpadre" class="form-control" disabled>
             
                                           <?php 

//require_once "config.php";

$consulta_alum = "SELECT neducpadre FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$neducpadre_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$neducpadre_actual = $fila_alum['neducpadre'];
}
$opciones_adicionales = array("Básica incompleta", "Básica Completa", "Media incompleta", "Media Completa", "Técnica incompleta", "Técnica Completa", "Superior Incompleta", "Superior Completa");
?>

<?php
echo "<option value='$neducpadre_actual'>$neducpadre_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $neducpadre_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
<br>
</div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Nivel / Ciclo</label></h6>
                                            <h6><select class form-select name="nivelciclopadre" id="nivelciclopadre" disabled >                                       
                                                <?php 

//equire_once "config.php";

$consulta_alum = "SELECT nivelciclopadre FROM alum_familia where run_alum_familia ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$nivelciclopadre_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$nivelciclopadre_actual = $fila_alum['nivelciclopadre'];
}
$opciones_adicionales = array("1°", "2°", "3°", "4°", "5°", "6°", "7°", "8°", "1 Medio",
"2° Medio", "3° Medio", "4° Medio");
?>

<?php
echo "<option value='$nivelciclopadre_actual'>$nivelciclopadre_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $nivelciclopadre_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}

?>
</select></h6>
</div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Nivel Educacional Madre</label></h6>
                                            <h6><select class form-select name="neducmadre" id="neducmadre" class="form-control" disabled>
                                           <?php 
$consulta_alum = "SELECT neducmadre FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$neducmadre_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$neducmadre_actual = $fila_alum['neducmadre'];
}
$opciones_adicionales = array("Básica incompleta", "Básica Completa", "Media incompleta", "Media Completa", "Técnica incompleta", "Técnica Completa", "Superior Incompleta", "Superior Completa");
?>

<?php
echo "<option value='$neducmadre_actual'>$neducmadre_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $neducmadre_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
</div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Nivel / Ciclo</label></h6>
                                            <h6><select class form-select name="nivelciclomadre" id="nivelciclomadre" disabled >
                                                <?php 
$consulta_alum = "SELECT nivelciclomadre FROM alum_familia where run_alum_familia ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$nivelciclomadre_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$nivelciclomadre_actual = $fila_alum['nivelciclomadre'];
}
$opciones_adicionales = array("1°", "2°", "3°", "4°", "5°", "6°", "7°", "8°", "1 Medio",
"2° Medio", "3° Medio", "4° Medio");
?>

<?php
echo "<option value='$nivelciclomadre_actual'>$nivelciclomadre_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $nivelciclomadre_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
</div>
                                        </div>
                                </div>

                                <div class="row justify-content-md-center">
                                    <div class="col-md-12">
<!-- LINEA HORIZONTAL -->             <hr class="mb-3">                                
                                    </div>
                                </div>




<!--   FILA 16   -->            <div class="row">                                      
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <h6><label>Nombre Tutor Legal</label></h6>

                                            <?php
                                              //  require_once "config.php";                                        
                                                $sql = "SELECT nomtutor FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $nomtutor = $row["nomtutor"];
                                            }else{
                                               $nomtutor = "";
                                            }
                                                ?> 
                                                <input type="text" name="nomtutor" id="nomtutor" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $nomtutor;?>">

                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Run</label></h6>
                                            <?php
                                                                                   
                                                $sql = "SELECT runtutor FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $runtutor = $row["runtutor"];
                                            }else{
                                               $runtutor = "";
                                            }
                                                ?> 
                                                <input type="text" name="runtutor" id="runtutor" class="form-control" disabled placeholder="Sin Registro"  value="<?php echo $runtutor;?>">
                                            </div>
                                        </div>



                                       <div class="col-md-6">
                                            <div class="form-group">
                                            <h6><label>Dirección</label></h6>
                                            <?php
                                            //   require_once "config.php";                                        
                                                $sql = "SELECT directutor FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $directutor = $row["directutor"];
                                            }else{
                                               $directutor = "";
                                            }
                                                ?> 

                                            <h6><input type="text" name="directutor" id="directutor" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $directutor;?>">
                                            </div>
                                           </h6>
                                            </div>
                                </div>


<!--   FILA 17   -->            <div class="row">                                      
                                    <div class="col-md-4">
                                            <div class="form-group">
                                            <h6><label>Email</label></h6>
                                            <?php
                                                                                
                                                $sql = "SELECT emailtutor FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $emailtutor = $row["emailtutor"];
                                            }else{
                                               $emailtutor = "";
                                            }
                                                ?> 
                                                <input type="text" name="emailtutor" id="emailtutor" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $emailtutor;?>">
                                            </div>
                                        </div>


                                      <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Comuna</label></h6>
                                            <h6><select class form-select name="comunatutor" id="comunatutor" class="form-control" disabled placeholder="Sin Registro">
                                            <option value="">Seleccionar</option>
                                           <?php 

                                                                               
                                            $sql_comunas = "SELECT id_regcomuna, nom_comuna FROM regcomuna where cod_region = '7'";
                                            $result_comunas = $conn->query($sql_comunas);

// Consulta para obtener el dato de la tabla 'alum_social' para seleccionar la opción predeterminada
                                            $sql_comunatutor = "SELECT comunatutor FROM alum_familia where run_alum_familia = '$idAlumno'";
                                            $result_alum_comunatutor = $conn->query($sql_comunatutor);



                                            $row_alum_comunatutor = $result_alum_comunatutor->fetch_assoc();
                                            $comuna_id_predeterminada = $row_alum_comunatutor['comunatutor'];
                                       //  if($comuna_id_predeterminada == 0)
                                         //   {
                                           //     echo '<option value="0" selected>Sin Registro</option>';
                                            //}
                                           //else
                                            
// Verificar si hay resultados de la tabla 'nacionalidad' y mostrarlos en un combobox
                                            if ($result_comunas->num_rows > 0) 
                                            {
//    echo '<select name="nacionalidad">';
                                            while ($row = $result_comunas->fetch_assoc()) 
                                            {
                                            $idcomunas = $row["id_regcomuna"];
                                            $nom = $row["nom_comuna"];

                                            $selected = ($idcomunas == $comuna_id_predeterminada) ? "selected" : "";
//                                          echo '<option value="' . $id_nacionalidad . '" ' . $selected . '>' . $gentilicio_nac . '</option>';
                                            echo '<option value="' . $idcomunas . '" ' . $selected . '>' . $nom . '</option>';
                                            }

                                            } 
                                            
                                            ?>
                                            </select></h6>
                                            <br>
                                            </h6>
                     
                                            </div>
                                        </div>

                                       <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Sector</label></h6>
                                            <h6><select class form-select name="sectortutor" id="sectortutor" class="form-control" disabled placeholder="Sin Registro">
                                           
                                            <?php
                          //                  require_once "config.php";
                                            $consulta_alum = "SELECT sectortutor FROM alum_familia where  run_alum_familia='$idAlumno'";
                                            $resultado_alum = $conn->query($consulta_alum);
                                            $sectortutor_actual = "";
                                

                                            if ($resultado_alum->num_rows > 0) 
                                                {
                                            $fila_alum = $resultado_alum->fetch_assoc();
                                            $sectortutor_actual = $fila_alum['sectortutor'];
                                                }
                                            $opciones_adicionales = array("Urbano", "Rural");
                                            ?>

                                            <?php
                                           echo "<option value='$sectortutor_actual'>$sectortutor_actual</option>";
                                            foreach ($opciones_adicionales as $opcion) 
                                                {
// Omitir la opción actual si ya se mostró como la primera opción
                                            if ($opcion !== $sectortutor_actual)   
                                                {
                                            echo "<option value='$opcion'>$opcion</option>";
                                                }
                                            }                                            
                                            ?>
                                            </select></h6>
                                            <br>
                                            </h6>                                           
                                        </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Celular</label></h6>
                                            <?php
                                                                             
                                                $sql = "SELECT celututor FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $celututortutor = $row["celututor"];
                                            }else{
                                               $celututortutor = "";
                                            }
                                                ?> 

                                            <h6><input type="text" name="celututor" id="celututor" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $celututortutor;?>">
</h6>
                                            </div>
                                        </div>


                                         <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Vínculo</label></h6>
                                            <h6><select class form-select name="vinculotutor" id="vinculotutor" disabled >
                                                <?php 

//require_once "config.php";

$consulta_alum = "SELECT vinculotutor FROM alum_familia where run_alum_familia ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$vinculotutor_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$vinculotutor_actual = $fila_alum['vinculotutor'];
}
$opciones_adicionales = array("Madre", "Padre", "Esposo (a)", "Pareja", "Abuelo (a)", "Abuelos", "Tío (a)", "Hermano (a)", "Tutor (a)",
"Tutor (a) Acta Tribunales", "Por Poder Simple", "Otros");
?>

<?php
echo "<option value='$vinculotutor_actual'>$vinculotutor_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $vinculotutor_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
</div>
                                        </div>

                                </div>

                                <div class="row justify-content-md-center">
                                    <div class="col-md-12">
<!-- LINEA HORIZONTAL -->             <hr class="mb-3">                                
                                    </div>
                                </div>

<!--   FILA 18   -->            <div class="row">                                      
                                                                         <div class="col-md-4">
                                            <div class="form-group">
                                            <h6><label>Nombre Representante o Apoderado</label></h6>

                                            <?php
                                                                                    
                                                $sql = "SELECT nomrepresentante FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $nomrepresentante = $row["nomrepresentante"];
                                            }else{
                                               $nomrepresentante = "";
                                            }
                                                ?> 
                                                <input type="text" name="nomrepresentante" id="nomrepresentante" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $nomrepresentante;?>">

                                            </div>
                                        </div>

                                       <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Run</label></h6>
                                            <?php
                                                                                 
                                                $sql = "SELECT runrepresentante FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $runrepresentante = $row["runrepresentante"];
                                            }else{
                                               $runrepresentante = "";
                                            }
                                                ?> 
                                                <input type="text" name="runrepresentante" id="runrepresentante" class="form-control" disabled placeholder="Sin Registro"  value="<?php echo $runrepresentante;?>">
                                            </div>
                                        </div>

                                       <div class="col-md-6">
                                            <div class="form-group">
                                            <h6><label>Dirección</label></h6>
                                            <?php
                                                                                     
                                                $sql = "SELECT direcrepresentante FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $direcrepresentante = $row["direcrepresentante"];
                                            }else{
                                               $direcrepresentante = "";
                                            }
                                                ?> 

                                            <h6><input type="text" name="direcrepresentante" id="direcrepresentante" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $direcrepresentante;?>">
                                            </div>
                                           </h6>
                                            </div>
                                </div>

<!--   FILA 19   -->            <div class="row">                                      
                                                                             <div class="col-md-4">
                                            <div class="form-group">
                                            <h6><label>Email</label></h6>
                                            <?php
                                                                              
                                                $sql = "SELECT emailrepresentante FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $emailrepresentante = $row["emailrepresentante"];
                                            }else{
                                               $emailrepresentante = "";
                                            }
                                                ?> 
                                                <input type="text" name="emailrepresentante" id="emailrepresentante" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $emailrepresentante;?>">
                                            </div>
                                        </div>

                                       <div class="col-md-3">
                                            <div class="form-group">
                                            <h6><label>Comuna</label></h6>       
                                            <h6><select class form-select name="comunarepresentante" id="comunarepresentante" class="form-control" disabled placeholder="Sin Registro">
                                            <option value="">Seleccionar</option>
                                           <?php 

                                                                          
                                            $sql_comunas = "SELECT id_regcomuna, nom_comuna FROM regcomuna where cod_region = '7'";
                                            $result_comunas = $conn->query($sql_comunas);



// Consulta para obtener el dato de la tabla 'alum_social' para seleccionar la opción predeterminada
                                            $sql_comunarepresentante = "SELECT comunarepresentante FROM alum_familia where run_alum_familia = '$idAlumno'";
                                            $result_alum_comunarepresentante = $conn->query($sql_comunarepresentante);



                                            $row_alum_comunarepresentante = $result_alum_comunarepresentante->fetch_assoc();
                                            $comuna_id_predeterminada = $row_alum_comunarepresentante['comunarepresentante'];
                                        // if($comuna_id_predeterminada == 0)
                                          //  {
                                            //    echo '<option value="0" selected>Sin Registro</option>';
                                            //}
                                           //else
                                            
// Verificar si hay resultados de la tabla 'nacionalidad' y mostrarlos en un combobox
                                            if ($result_comunas->num_rows > 0) 
                                            {
//    echo '<select name="nacionalidad">';
                                            while ($row = $result_comunas->fetch_assoc()) 
                                            {
                                            $idcomunas = $row["id_regcomuna"];
                                            $nom= $row["nom_comuna"];

                                            $selected = ($idcomunas == $comuna_id_predeterminada) ? "selected" : "";
                                            echo '<option value="' . $idcomunas . '" ' . $selected . '>' . $nom . '</option>';
                                            }

                                            } 
                                            
                                            ?>
                                            </select></h6>
                                            <br>
                                            </h6>
                     
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Sector</label></h6>
                                            <h6><select class form-select name="sectorrepresentante" id="sectorrepresentante" class="form-control" disabled placeholder="Sin Registro">
                                           
                                            <?php
                          //                  require_once "config.php";
                                            $consulta_alum = "SELECT sectorrepresentante FROM alum_familia where  run_alum_familia='$idAlumno'";
                                            $resultado_alum = $conn->query($consulta_alum);
                                            $sectorrepresentante_actual = "";
                                

                                            if ($resultado_alum->num_rows > 0) 
                                                {
                                            $fila_alum = $resultado_alum->fetch_assoc();
                                            $sectorrepresentante_actual = $fila_alum['sectorrepresentante'];
                                                }
                                            $opciones_adicionales = array("Urbano", "Rural");
                                            ?>

                                            <?php
                                           echo "<option value='$sectorrepresentante_actual'>$sectorrepresentante_actual</option>";
                                            foreach ($opciones_adicionales as $opcion) 
                                                {
// Omitir la opción actual si ya se mostró como la primera opción
                                            if ($opcion !== $sectorrepresentante_actual)   
                                                {
                                            echo "<option value='$opcion'>$opcion</option>";
                                                }
                                            }                                            
                                            ?>
                                            </select></h6>
                                            <br>
                                            </h6>                                           
                                        </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Celular</label></h6>
                                            <?php
                                                                                   
                                                $sql = "SELECT celurepresentante FROM alum_familia WHERE run_alum_familia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $celurepresentante = $row["celurepresentante"];
                                            }else{
                                               $celurepresentante = "";
                                            }
                                                ?> 

                                            <h6><input type="text" name="celurepresentante" id="celurepresentante" class="form-control" disabled placeholder="Sin Registro" value="<?php echo $celurepresentante;?>">
</h6>
                                            </div>
                                        </div>

                                      <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Vínculo</label></h6>
                                            <h6><select class form-select name="vinculorepresentante" id="vinculorepresentante" disabled >
                                                <?php 

$consulta_alum = "SELECT vinculorepresentante FROM alum_familia where run_alum_familia ='$idAlumno'";
$resultado_alum = $conn->query($consulta_alum);
$vinculorepresentante_actual = " ";

if ($resultado_alum->num_rows > 0) {

$fila_alum = $resultado_alum->fetch_assoc();
$vinculorepresentante_actual = $fila_alum['vinculorepresentante'];
}
$opciones_adicionales = array("Madre", "Padre", "Esposo (a)", "Pareja", "Abuelo (a)", "Abuelos", "Tío (a)", "Hermano (a)", "Tutor (a)",
"Tutor (a) Acta Tribunales", "Por Poder Simple", "Otros");
?>

<?php
echo "<option value='$vinculorepresentante_actual'>$vinculorepresentante_actual</option>";
foreach ($opciones_adicionales as $opcion) 
{
if ($opcion !== $vinculorepresentante_actual)   
{
echo "<option value='$opcion'>$opcion</option>";
}
}
?>
</select></h6>
</div>
                                        </div>

                                </div>


                                <div class="row justify-content-md-center">
                                    <div class="col-md-12">
<!-- LINEA HORIZONTAL -->             <hr class="mb-3">                                
                                    </div>
                                </div>

<h4 class="title">4. AVISAR EN CASO DE EMERGENCIA / Observaciones</h4>
                                <br>

<!--   FILA 20   -->          <div class="row">                                      
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <h6><label>Nombre</label></h6>
                                            <?php
                                                                                    
                                                $sql = "SELECT nomemergencia FROM alum_emergencia WHERE run_alum_emergencia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $nomemergencia = $row["nomemergencia"];
                                            }else{
                                               $nomemergencia = "";
                                            }
                                                ?> 

                                                <input type="text" name="nomemergencia" id="nomemergencia" class="form-control" disabled  value="<?php echo $nomemergencia;?>">
 
                                               

                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <h6><label>Celular</label></h6>
                                            <?php
                                                                                    
                                                $sql = "SELECT celuemergencia FROM alum_emergencia WHERE run_alum_emergencia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $celuemergencia = $row["celuemergencia"];
                                            }else{
                                               $celuemergencia = "";
                                            }
                                                ?> 
                                                <input type="text" name="celuemergencia" id="celuemergencia" class="form-control" disabled value="<?php echo $celuemergencia;?>">                                             
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <h6><label>Dirección</label></h6>
                                            <?php
                                                                                  
                                                $sql = "SELECT direcemergencia FROM alum_emergencia WHERE run_alum_emergencia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $direcemergencia = $row["direcemergencia"];
                                            }else{
                                               $direcemergencia = "";
                                            }
                                                ?> 

                                            <h6><input type="text" name="direcemergencia" id="direcemergencia" class="form-control" disabled value="<?php echo $direcemergencia;?>">

                                        </div>
                                        </div>
                                </div>

<!--   FILA 21   -->           <div class="row">              
                                        <div class="col-md-12">
                                            <div class="form-group">

                                            <?php
                                     //  
                                            $sql = "SELECT observaemergencia FROM alum_emergencia WHERE run_alum_emergencia = '$idAlumno'";
                                            $resultado_alum = $conn->query($sql);
                                                                          
                                            if ($resultado_alum->num_rows > 0) {
                                            $fila_alum = $resultado_alum->fetch_assoc();
                                            $observaemergencia_actual = $fila_alum['observaemergencia'];
                                                }
                                            else 
                                            echo        
                                            $observaemergencia_actual = "";
                                            ?>
                                            <h6><label>Observación</label></h6>
                                            <h6><textarea rows="3" name="observa" id="observa" class="form-control" disabled ><?php echo $observaemergencia_actual;?></textarea></h6>
                                            </h6>
                                            </div>
                                        </div>
                                 </div> 
                  
  </form>


  <script>
    function updateCodComuna() {
        var selectedComuna = document.getElementById("comunaestablecimiento").value;
        document.getElementById("cod_comuna").value = selectedComuna;
    }
</script>



  <script>
        $(document).ready(function(){
            $('#region').change(function(){
                var regionID = $(this).val();
                if(regionID){
                    $.ajax({
                        type:'POST',
                        url:'getComunas.php',
                        data:'region_id='+regionID,
                        success:function(html){
                            $('#comunaestablecimiento').html(html);
                        }
                    });
                }else{
                    $('#comunaestablecimiento').html('<option value="">Selecciona una comuna</option>');
                    $('#establecimiento').html('<option value="">Selecciona un establecimiento</option>');
                }
            });

            $('#comunaestablecimiento').change(function(){
                var comunaID = $(this).val();
                if(comunaID){
                    $.ajax({
                        type:'POST',
                        url:'getEstablecimientos.php',
                        data:'comuna_id='+comunaID,
                        success:function(html){
                            $('#establecimiento').html(html);
                        }
                    });
                }else{
                    $('#establecimiento').html('<option value="">Selecciona un establecimiento</option>');
                }
            });
        });
    </script>

  

  <script>
        function habilitarCampos() {
            document.getElementById('matricula').disabled = false;
            document.getElementById('tipoensenanza').disabled = false;
            document.getElementById('especialidad').disabled = false;
            document.getElementById('celular1').disabled = false;
            document.getElementById('celular2').disabled = false;
            document.getElementById('estado_civil').disabled = false;
            document.getElementById('nomconyuge').disabled = false;
            document.getElementById('vivecon').disabled = false;
            document.getElementById('viveconobs').disabled = false;
            document.getElementById('nhijos').disabled = false;
            document.getElementById('hijosmayores').disabled = false;
            document.getElementById('nacionalidad').disabled = false;
            document.getElementById('trabaja').disabled = false;
            document.getElementById('direccion').disabled = false;
            document.getElementById('comunaestudiante').disabled = false;
            document.getElementById('sectorestudiante').disabled = false;
            document.getElementById('georreferencia').disabled = false;
            document.getElementById('email').disabled = false;
            document.getElementById('region').disabled = false;
            document.getElementById('establecimiento').disabled = false;
            document.getElementById('obsestablecimiento').disabled = false;
            document.getElementById('comunaestablecimiento').disabled = false;
            document.getElementById('ultimocursoaprobado').disabled = false;
            document.getElementById('ultimoanioestudio').disabled = false;
            document.getElementById('cursosrepetido').disabled = false;
            document.getElementById('veinteporciento').disabled = false;
            document.getElementById('informe20porciento').disabled = false;
            document.getElementById('perteneceetnia').disabled = false;
            document.getElementById('embarazo').disabled = false;
            document.getElementById('semanas').disabled = false;
            document.getElementById('pueblooriginario').disabled = false;
            document.getElementById('infosalud').disabled = false;
            document.getElementById('evpsicologica').disabled = false;
            document.getElementById('probleaprendizaje').disabled = false;
            document.getElementById('pie').disabled = false;
            document.getElementById('chilesolida').disabled = false;
            document.getElementById('chilesolidacual').disabled = false;
            document.getElementById('sistemasalud').disabled = false;
            document.getElementById('letrafonasa').disabled = false;
            document.getElementById('segurosalud').disabled = false;
            document.getElementById('segurocual').disabled = false;
            document.getElementById('regsocial').disabled = false;

            document.getElementById('neducpadre').disabled = false;
            document.getElementById('nivelciclopadre').disabled = false;
            document.getElementById('neducmadre').disabled = false;
            document.getElementById('nivelciclomadre').disabled = false;
            document.getElementById('nomtutor').disabled = false;
            document.getElementById('runtutor').disabled = false;
            document.getElementById('directutor').disabled = false;
            document.getElementById('emailtutor').disabled = false;
            document.getElementById('comunatutor').disabled = false;
            document.getElementById('sectortutor').disabled = false;
            document.getElementById('celututor').disabled = false;
            document.getElementById('vinculotutor').disabled = false;
            document.getElementById('nomrepresentante').disabled = false;
            document.getElementById('runrepresentante').disabled = false;
            document.getElementById('direcrepresentante').disabled = false;
            document.getElementById('emailrepresentante').disabled = false;
            document.getElementById('comunarepresentante').disabled = false;
            document.getElementById('sectorrepresentante').disabled = false;
            document.getElementById('celurepresentante').disabled = false;
            document.getElementById('vinculorepresentante').disabled = false;
            document.getElementById('nomemergencia').disabled = false;
            document.getElementById('celuemergencia').disabled = false;
            document.getElementById('direcemergencia').disabled = false;
            document.getElementById('observa').disabled = false;



            document.querySelector('input[type="button"]').style.display = 'none';
            document.querySelector('input[type="submit"]').style.display = 'block';
        }
    </script>


<!--   FIN DEL FORMULARIO  --> 


            </div>
        </div>
        <P>
    &nbsp;
</p>
    </div>



<!-- SEGUNDA COLUMNA -->

                    <div class="col-md-3">
                        <div class="row">
                                <div class="col-md-3">
                                    <div class="content">
                                        <div class="author">
                                     <a href="#"> </a>
<!--      FOTO DEL ALUMNO                              <img class="avatar border-gray" src="fotosalumnos/22308879.jpg" alt="..."/> -->
                                        </div>
                                    </div>
                                </div>


<form action="./reportes/registro_anotaciones.php" method="GET" target="_blank">
<input type="hidden" name="idalum" disabled placeholder="Sin Registro"  value="<?php echo $dataAlumno['run']; ?>">   



<div class="row">
<div class="col-md-3">
    <div class="form-group">
        <h7><label>SAAT</label></h7>
        <input type="text" class="form-control" disabled placeholder="Sin Registro" value="
           
<?php
// Conexión a la base de datos (reemplaza con tus propios detalles)
$servername = "localhost";
$username = "root";
$password = "";
$database = "saat2";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el valor del campo "alum_run" del formulario
$idAlumno = $_REQUEST['id'];

// Consulta SQL para contar repeticiones del valor en la tabla "ANOTACIONES POSITIVAS"

$sql = "SELECT COUNT(*) AS repeticiones FROM anotaciones WHERE alum_run = '$idAlumno' AND  tipo='NEGATIVA' AND categoria='GMA' "; 

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si la consulta retornó resultados
if ($result->num_rows >= 0) {
    // Obtener el resultado como un array asociativo
    $row = $result->fetch_assoc();
    
    // Imprimir el número de repeticiones
    echo "{$row['repeticiones']}";
} else {
    echo "{$row['0']}";
}
// Cerrar la conexión
$conn->close();
?>"></h6>

</div>
</div>



<div class="col-md-4">
    <div class="form-group">
        <h7><label>SUSPENSIONES</label></h7>
        <?php
            // Conexión a la base de datos (reemplaza con tus propios detalles)
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "saat2";

            // Crea la conexión
            $conn = new mysqli($servername, $username, $password, $database);

            // Verifica la conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Obtener el valor del campo "alum_run" del formulario
            $idAlumno = $_REQUEST['id'];

            // Consulta para obtener la cantidad de suspensiones
            $sql = "SELECT COUNT(*) AS repeticiones FROM suspenciones WHERE alum_run = '$idAlumno'"; 

            // Ejecutar la consulta
            $result = $conn->query($sql);

            // Verificar si la consulta retornó resultados
            if ($result->num_rows > 0) {
                // Obtener el resultado como un array asociativo
                $row = $result->fetch_assoc();
                
                // Obtener el número de suspensiones
                $repeticiones = $row['repeticiones'];

                // Definir clases de Bootstrap según la cantidad de suspensiones
                $class = '';
                if ($repeticiones > 0) {
                    $class = 'bg-danger text-white'; // Si hay suspensiones, fondo rojo
                } else {
                    $class = 'bg-success text-white'; // Si no hay suspensiones, fondo verde
                }

                // Output del input con las clases correspondientes
                echo '<input type="text" class="form-control text-center ' . $class . '" disabled value="' . $repeticiones . '">';

            } else {
                // Si no hay suspensiones, se muestra "Sin Registro" en fondo verde
                echo '<input type="text" class="form-control bg-success text-white text-center" disabled value="Sin Registro">';
            }

            // Cerrar la conexión
            $conn->close();
        ?>
    </div>
</div>



<div class="col-md-5">
    <div class="form-group">
        <h7><label>ESTADO</label></h7>
        <?php
        // Conexión a la base de datos (reemplaza con tus propios detalles)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "saat2";
        //$idAlumno = "21794263";

        // Crea la conexión
        $conn = new mysqli($servername, $username, $password, $database);

        // Verifica la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Consulta para obtener la fecha de la tabla
        $consulta = "SELECT fretiro FROM alum WHERE run='$idAlumno'"; // Cambia "tu_tabla" por el nombre de tu tabla y ajusta la condición WHERE según tu estructura

        $resultado = $conn->query($consulta);

        if ($resultado->num_rows > 0) {
            // Extraer la fecha de la fila obtenida
            $fila = $resultado->fetch_assoc();
            $fecha = $fila["fretiro"];

            // Fecha específica para comparar
            $fecha_especifica = "1900-01-01"; // Cambia esta fecha por la que necesites
            $fecha_cero = "0000-00-00"; // Cambia esta fecha por la que necesites
            //$fecha_nula = 'null';

            // Definir clases de Bootstrap según el estado
            $class = '';
            if ($fecha == $fecha_especifica) {
                $class = 'bg-success text-white'; // Activo, fondo verde
                $resultado = "ACTIVO";
            } else {
                if ($fecha == $fecha_cero) {
                    $class = 'bg-warning text-dark'; // No registra, fondo amarillo
                    $resultado = "No Registra";
                } else {
                    $class = 'bg-danger text-white'; // Retirado, fondo rojo
                    $resultado = "RETIRADO";
                }
            }

            // Output del input con las clases correspondientes
            echo '<input type="text" class="form-control ' . $class . '" disabled placeholder="' . $resultado . '" value="' . $resultado . '">';

        } else {
            echo '<input type="text" class="form-control bg-warning text-dark" disabled placeholder="Sin Registro" value="Sin Registro">';
        }

        // Cerrar la conexión
        $conn->close();
        ?>
    </div>
</div>
</div>   

<p></p>

<!--   FILA 1   -->     <div class="row">
 

                                <div class="col-md-4">
                                    <div class="form-group">
                                                <h7><label>PROMEDIO</label></h7>
                                                <input type="text" class="form-control text-center" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['promedio']; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                                <h7><label>ASISTENCIA</label></h7>
                                                <input type="text" class="form-control text-center" disabled placeholder="Sin Registro" value="<?php echo $dataAlumno['asistencia']; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
            <div class="form-group">
                                            <h6><label>N°EMERGENCIA</label></h6>
                                            <?php
                                              include '../../includes/config.php';                                      
                                                $sql = "SELECT celuemergencia FROM alum_emergencia WHERE run_alum_emergencia = '$idAlumno'";
                                                $result = $conn->query($sql);
// Verificar si se encontró un resultado
                                                if ($result->num_rows > 0) {
    // Obtener el valor de nomconyuge
                                                $row = $result->fetch_assoc();
                                                $celuemergencia = $row["celuemergencia"];
                                            }else{
                                               $celuemergencia = "";
                                            }
                                                ?> 
                                                <input type="text" name="celuemergencia" id="celuemergencia" class="form-control bg-danger text-dark" disabled value="<?php echo $celuemergencia;?>">
                                            </h6>
                                            </div>
                                    </div>
                        </div>


                        <div class="row justify-content-md-center">
                                    <div class="col-md-12">
<!-- LINEA HORIZONTAL -->             <hr class="mb-3">                                
                                    </div>
                        </div>


                        <?php
$runAlumno = $dataAlumno['run'];  // Obtener el run del alumno
?>
<h6>
    <a href="l.alum.reg.ver.php?run=<?php echo urlencode($runAlumno); ?>" class="title p-1 text-danger-emphasis border border-danger-subtle rounded-2" align="center" style="text-decoration: none; color: inherit;">
        VER REGISTRO ANOTACIONES
    </a>
</h6>
<!--   FILA 2   -->    
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <h7><label>NEGATIVAS</label></h7>
            <input type="text" class="form-control bg-danger text-center" disabled placeholder="Sin Registro" value="
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "saat2";
            $conn = new mysqli($servername, $username, $password, $database);
            if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
                }
            $idAlumno = $_REQUEST['id'];
            $sql = "SELECT COUNT(*) AS repeticiones FROM anotaciones WHERE alum_run = '$idAlumno' AND  tipo='N'"; 
            $result = $conn->query($sql);

            if ($result->num_rows >= 0) {
            $row = $result->fetch_assoc();
            echo "{$row['repeticiones']}";
            } else {
            echo "{$row['0']}";
            }
            $conn->close();
            ?>"></h6>
        </div>
    </div>


    <div class="col-md-4">
                                        <div class="form-group">
                                                <h7><label>POSITIVAS</label></h7>
                                                <input type="text" class="form-control bg-success text-center" disabled placeholder="Sin Registro" value="
   
<?php
// Conexión a la base de datos (reemplaza con tus propios detalles)
$servername = "localhost";
$username = "root";
$password = "";
$database = "saat2";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el valor del campo "alum_run" del formulario
$idAlumno = $_REQUEST['id'];

// Consulta SQL para contar repeticiones del valor en la tabla "ANOTACIONES POSITIVAS"

$sql = "SELECT COUNT(*) AS repeticiones FROM anotaciones WHERE alum_run = '$idAlumno' AND  tipo='P' "; 

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si la consulta retornó resultados
if ($result->num_rows >= 0) {
    // Obtener el resultado como un array asociativo
    $row = $result->fetch_assoc();
    
    // Imprimir el número de repeticiones
    echo "{$row['repeticiones']}";
} else {
    echo "{$row['0']}";
}
// Cerrar la conexión
$conn->close();
?>"></h6>
        </div>
    </div>



    <div class="col-md-4">
                                        <div class="form-group">
                                                <h7><label>OTRAS</label></h7>
                                                <input type="text" class="form-control bg-primary text-center" disabled placeholder="Sin Registro" value="
           
<?php
// Conexión a la base de datos (reemplaza con tus propios detalles)
$servername = "localhost";
$username = "root";
$password = "";
$database = "saat2";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el valor del campo "alum_run" del formulario
$idAlumno = $_REQUEST['id'];

// Consulta SQL para contar repeticiones del valor en la tabla "ANOTACIONES POSITIVAS"

$sql = "SELECT COUNT(*) AS repeticiones FROM anotaciones WHERE alum_run = '$idAlumno' AND  tipo='O' "; 

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si la consulta retornó resultados
if ($result->num_rows >= 0) {
    // Obtener el resultado como un array asociativo
    $row = $result->fetch_assoc();
    
    // Imprimir el número de repeticiones
    echo "{$row['repeticiones']}";
} else {
    echo "{$row['0']}";
}
// Cerrar la conexión
$conn->close();
?>"></h6>

</div>
    </div>
    </div>

    <div class="row justify-content-md-center">
                                    <div class="col-md-12">
<!-- LINEA HORIZONTAL -->             <hr class="mb-3">                                
                                    </div>
                        </div>










                        <h6 class="title" align="center">DETALLES ANOTACIONES NEGATIVAS</h6>

<!--   FILA 2   -->    
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <h7><label>LEVES</label></h7>
            <input type="text" class="form-control bg-secondary text-dark text-center font-weight-bold" disabled placeholder="Sin Registro" value=" 
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "saat2";
            $conn = new mysqli($servername, $username, $password, $database);
            if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
                }
            $idAlumno = $_REQUEST['id'];
            $sql = "SELECT COUNT(*) AS repeticiones FROM anotaciones WHERE alum_run = '$idAlumno' AND  tipo='N' AND categoria='L'"; 
            $result = $conn->query($sql);

            if ($result->num_rows >= 0) {
            $row = $result->fetch_assoc();
            echo "{$row['repeticiones']}";
            } else {
            echo "{$row['0']}";
            }
            $conn->close();
            ?>"></h6>
        </div>
    </div>


    <div class="col-md-4">
                                        <div class="form-group">
                                                <h7><label>GRAVES</label></h7>
                                                <input type="text" class="form-control bg-warning text-dark text-center font-weight-bold" disabled placeholder="Sin Registro" value="
   
<?php
// Conexión a la base de datos (reemplaza con tus propios detalles)
$servername = "localhost";
$username = "root";
$password = "";
$database = "saat2";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el valor del campo "alum_run" del formulario
$idAlumno = $_REQUEST['id'];

// Consulta SQL para contar repeticiones del valor en la tabla "ANOTACIONES POSITIVAS"

$sql = "SELECT COUNT(*) AS repeticiones FROM anotaciones WHERE alum_run = '$idAlumno' AND  tipo='N' AND categoria='G'"; 

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si la consulta retornó resultados
if ($result->num_rows >= 0) {
    // Obtener el resultado como un array asociativo
    $row = $result->fetch_assoc();
    
    // Imprimir el número de repeticiones
    echo "{$row['repeticiones']}";
} else {
    echo "{$row['0']}";
}
// Cerrar la conexión
$conn->close();
?>"></h6>
        </div>
    </div>



    <div class="col-md-4">
                                        <div class="form-group">
                                                <h7><label>GRAVÍSIMAS</label></h7>
                                                <input type="text" class="form-control bg-danger text-dark text-center font-weight-bold" disabled placeholder="Sin Registro" value="
           
<?php
// Conexión a la base de datos (reemplaza con tus propios detalles)
$servername = "localhost";
$username = "root";
$password = "";
$database = "saat2";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el valor del campo "alum_run" del formulario
$idAlumno = $_REQUEST['id'];

// Consulta SQL para contar repeticiones del valor en la tabla "ANOTACIONES POSITIVAS"

$sql = "SELECT COUNT(*) AS repeticiones FROM anotaciones WHERE alum_run = '$idAlumno' AND  tipo='N' AND categoria='GMA' "; 

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si la consulta retornó resultados
if ($result->num_rows >= 0) {
    // Obtener el resultado como un array asociativo
    $row = $result->fetch_assoc();
    
    // Imprimir el número de repeticiones
    echo "{$row['repeticiones']}";
} else {
    echo "{$row['0']}";
}
// Cerrar la conexión
$conn->close();
?>"></h6>

</div>
    </div>
    </div>

    <div class="row justify-content-md-center">
                                    <div class="col-md-12">
<!-- LINEA HORIZONTAL -->             <hr class="mb-3">                                
                                    </div>
                        </div>


<h6 class="title" align="center">REGISTRO ATRASOS Y SALIDAS</h6>

<!--   FILA 3   -->    
<div class="row">
<div class="col-md-4">
        <div class="form-group">
            <h7><label>ATRASOS</label></h7>
            <input type="text" class="form-control text-center" disabled placeholder="Sin Registro" value=" 
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "saat2";
            $conn = new mysqli($servername, $username, $password, $database);
            if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
                }
            $idAlumno = $_REQUEST['id'];
            $sql = "SELECT COUNT(*) AS repeticiones FROM atrasos WHERE alum_run = '$idAlumno'"; 
            $result = $conn->query($sql);

            if ($result->num_rows >= 0) {
            $row = $result->fetch_assoc();
            echo "{$row['repeticiones']}";
            } else {
            echo "{$row['0']}";
            }
            $conn->close();
            ?>"></h6>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">

        </div>
    </div>


    <div class="col-md-4">
        <div class="form-group">
            <h7><label>SALIDAS</label></h7>
            <input type="text" class="form-control text-center" disabled placeholder="Sin Registro" value=" 
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "saat2";
            $conn = new mysqli($servername, $username, $password, $database);
            if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
                }
            $idAlumno = $_REQUEST['id'];
            $sql = "SELECT COUNT(*) AS repeticiones FROM salidas WHERE alum_run = '$idAlumno'"; 
            $result = $conn->query($sql);

            if ($result->num_rows >= 0) {
            $row = $result->fetch_assoc();
            echo "{$row['repeticiones']}";
            } else {
            echo "{$row['0']}";
            }
            $conn->close();
            ?>"></h6>
        </div>
    </div>

</div>


<div class="row justify-content-md-center">
            <div class="col-md-12">
<!-- LINEA HORIZONTAL -->   <hr class="mb-3">                                
            </div>
</div>
<div class="row">
       

                                    <div class="col-md-2">
           <!--                          <button type="submit"  class="btn btn-success" ><i class="bi bi-filetype-pdf"></i> DESCARGAR REGISTROS</button>
                                   <a href="./reportes/registro_anotaciones.php" target="_blank" class="btn btn-success" ><i class="bi bi-filetype-pdf"></i> DESCARGAR REGISTROS</a>
                                        -->                        
        </form>               
            </div>

                            </div>

                            <div class="row justify-content-md-center">
                                    <div class="col-md-12">
<!-- LINEA HORIZONTAL -->           <hr class="mb-3">                                


                         
                        </div>
                    </div>
                                <?php 
                                      }
                                    }
                                  catch(PDOException $e){
                                    echo "Hubo un problema en la conexión: " . $e->getMessage();
                                    }
                                }         
    //Cerrar la Conexion
    //$database->close();
                                    
    
?>

          </div>
     </div>
</div>



<script src="js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>

<!-- Bootstrap core JavaScript
    ================================================== -->   
<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>  -->
<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
