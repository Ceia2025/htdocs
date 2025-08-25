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

// Incluir el archivo de conexión
include '../../includes/mensajes.php';
include '../../includes/config.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Obtener el valor de la variable desde el formulario POST

$run = $_POST["idalum"];
$matricula = $_POST["matricula"];
$tipoensenanza = $_POST["tipoensenanza"];
$especialidad = $_POST["especialidad"];
$celular1 = $_POST["celular1"];
$celular2 = $_POST["celular2"];
$estado_civil = $_POST["estado_civil"];
$trabaja = $_POST["trabaja"];
$direccion = $_POST["direccion"];
$comunaestudiante = $_POST["comunaestudiante"];
$sectorestudiante = $_POST["sectorestudiante"];
$georreferencia = $_POST["georreferencia"];
$email = $_POST["email"];
$nacionalidad = $_POST["nacionalidad"];


// Consulta SQL para verificar si hay algún registro con el mismo run en la tabla alum
//  $consulta_alum = "SELECT run, matricula, celular, trabaja, telefono, email, direcc, comuna, cod_comuna, georreferencia, sectorestudiante, estado_civil, tipoensenanza, especialidad, nacionalidad   
//   FROM alum  WHERE run = '$run'";

    $consulta_alum = "SELECT * FROM alum  WHERE run = '$run'";

    $resultado_consulta_alum = $conn->query($consulta_alum);
    if ($resultado_consulta_alum->num_rows > 0) {
        // Si existe un registro con el mismo run, realizar una actualización
        $consulta_actualizar_alum = "UPDATE alum SET 
        matricula = '$matricula',
        trabaja = '$trabaja ',
        celular = '$celular1',
        telefono = '$celular2',
        email = '$email', 
        direcc = '$direccion',
        comuna = '$comunaestudiante',
        georreferencia = '$georreferencia',
        sectorestudiante = '$sectorestudiante',
        estado_civil = '$estado_civil', 
        tipoensenanza = '$tipoensenanza',
        especialidad = '$especialidad',
        nacionalidad = '$nacionalidad'
        WHERE run = '$run'";

        if ($conn->query($consulta_actualizar_alum) === TRUE) {
            echo "Registro TABLA ALUM actualizado correctamente";   
        // Redireccionar a otra página después de 5 segundos
     echo "<script>setTimeout(function() { window.location.href = 'm.alum.php'; }, 1000);</script>";
        } else {
            echo "Error al actualizar el registro TABLA ALUM: " . $conn->error;
        }
    } else {
        // Si no existe ningún registro con el mismo run, realizar una inserción
        $consulta_insertar_alum = "INSERT INTO alum (celular, matricula, trabaja, telefono, email, direcc, comuna, georreferencia, sectorestudiante, estado_civil, tipoensenanza, especialidad, nacionalidad)
        
        VALUES 
        ('$celular1', '$matricula', '$celular2', '$email', '$direccion', '$comunaestudiante', '$trabaja', '$georreferencia', '$sectorestudiante', '$estado_civil', '$tipoensenanza', '$especialidad', '$nacionalidad')  
        where  run = '$run'";

        if ($conn->query($consulta_insertar_alum) === TRUE) {
            echo "Nuevo registro insertado correctamente TABLA ALUM";
        } else {
            echo "Error al insertar nuevo registro TABLA ALUM: " . $conn->error;
        }
    }


//2. ANTECEDENTES ESCOLARES

$region = $_POST["region"];
$comunaestablecimiento = $_POST["comunaestablecimiento"];
$establecimiento = $_POST["establecimiento"];
$obsestablecimiento = $_POST["obsestablecimiento"];
$ultimocursoaprobado = $_POST["ultimocursoaprobado"];
$ultimoanioestudio = $_POST["ultimoanioestudio"];
$cursosrepetido = $_POST["cursosrepetido"];
$veinteporciento = $_POST["veinteporciento"];
$informe20porciento = $_POST["informe20porciento"];
$perteneceetnia = $_POST["perteneceetnia"];
$embarazo = $_POST["embarazo"];
$semanas = $_POST["semanas"];
$pueblooriginario = $_POST["pueblooriginario"];
$infosalud = $_POST["infosalud"];
$evpsicologica = $_POST["evpsicologica"];
$probleaprendizaje = $_POST["probleaprendizaje"];
$pie = $_POST["pie"];
$chilesolida = $_POST["chilesolida"];
$chilesolidacual = $_POST["chilesolidacual"];
$sistemasalud = $_POST["sistemasalud"];
$letrafonasa = $_POST["letrafonasa"];
$segurosalud = $_POST["segurosalud"];
$segurocual = $_POST["segurocual"];
$regsocial = $_POST["regsocial"];



// Consulta SQL para verificar si hay algún registro con el mismo run en la tabla alum
/* $consulta_sociales = "SELECT run_alum_sociales, region, comuna, establecimiento, ultimocursoaprobado, ultimoanioestudio, infosalud,
cursosrepetido, veinteporciento, informe20porciento, perteneceetnia, embarazo, semanas, pueblooriginario, evpsicologica, problemaaprendizaje,
pie, chilesolida, chilesolidacual, sistemasalud, letrafonasa, segurosalud, segurosocial, segurocual
FROM alum_sociales 
WHERE run_alum_sociales = '$run'";
*/

$consulta_sociales = "SELECT * FROM alum_sociales WHERE run_alum_sociales = '$run'";


$resultado_consulta_sociales = $conn->query($consulta_sociales);
if ($resultado_consulta_sociales->num_rows > 0) {

    $consulta_actualizar_sociales = "UPDATE alum_sociales SET 
    infosalud = '$infosalud',
    region = '$region', 
    comuna = '$comunaestablecimiento', 
    establecimiento = '$establecimiento', 
    obsestablecimiento = '$obsestablecimiento', 
    ultimocursoaprobado = '$ultimocursoaprobado', 
    ultimoanioestudio = '$ultimoanioestudio',
    cursosrepetido = '$cursosrepetido', 
    veinteporciento = '$veinteporciento', 
    informe20porciento = '$informe20porciento', 
    perteneceetnia = '$perteneceetnia',
    embarazo = '$embarazo', 
    semanas = '$semanas', 
    pueblooriginario = '$pueblooriginario', 
    evpsicologica = '$evpsicologica', 
    problemaaprendizaje = '$probleaprendizaje',
    pie = '$pie', 
    chilesolida = '$chilesolida', 
    chilesolidacual = '$chilesolidacual', 
    sistemasalud = '$sistemasalud',
    letrafonasa = '$letrafonasa', 
    segurosalud = '$segurosalud', 
    segurocual = '$segurocual',
    regsocial = '$regsocial'
    WHERE
     run_alum_sociales = '$run'";

    if ($conn->query($consulta_actualizar_sociales) === TRUE) {
        echo "--  Registro actualizado correctamente TABLA SOCIAL --";   
    } else {
        echo "-- Error al actualizar el registro TABLA SOCIAL: -- " . $conn->error;
    }
    
} 

else {
    $consulta_insertar_sociales = "INSERT INTO alum_sociales (run_alum_sociales, infosalud, region, comuna, establecimiento, obsestablecimiento,
    ultimocursoaprobado, ultimoanioestudio, cursosrepetido, veinteporciento, informe20porciento, perteneceetnia, embarazo, semanas, pueblooriginario,
    evpsicologica, problemaaprendizaje, pie, chilesolida, chilesolidacual, sistemasalud, letrafonasa, segurosalud, segurocual, regsocial)
    VALUES 
    ('$run', '$infosalud', '$region', '$comunaestablecimiento', '$establecimiento', '$obsestablecimiento',  '$ultimocursoaprobado', '$ultimoanioestudio', '$cursosrepetido',
     '$veinteporciento', '$informe20porciento', '$perteneceetnia', '$embarazo', '$semanas', '$pueblooriginario', '$evpsicologica',
      '$probleaprendizaje', '$pie', '$chilesolida', '$chilesolidacual', '$sistemasalud', '$letrafonasa', '$segurosalud', '$segurocual', '$regsocial')";

    if ($conn->query($consulta_insertar_sociales) === TRUE) {
        echo "--  Nuevo registro insertado correctamente TABLA SOCIAL  --";
    } else {
        echo "--  Error al insertar nuevo registro TABLA SOCIAL: " . $conn->error;
    }
}


//3. ANTECEDENTES FAMILIARES
$neducpadre = $_POST["neducpadre"];
$nivelciclopadre = $_POST["nivelciclopadre"];
$neducmadre = $_POST["neducmadre"];
$nivelciclomadre = $_POST["nivelciclomadre"];
$nomtutor = $_POST["nomtutor"];
$runtutor = $_POST["runtutor"];
$directutor = $_POST["directutor"];
$emailtutor = $_POST["emailtutor"];
$comunatutor = $_POST["comunatutor"];
$sectortutor = $_POST["sectortutor"];
$celututor = $_POST["celututor"];
$vinculotutor = $_POST["vinculotutor"];
$nomrepresentante = $_POST["nomrepresentante"];
$runrepresentante = $_POST["runrepresentante"];
$direcrepresentante = $_POST["direcrepresentante"];
$emailrepresentante = $_POST["emailrepresentante"];
$comunarepresentante = $_POST["comunarepresentante"];
$sectorrepresentante = $_POST["sectorrepresentante"];
$celurepresentante = $_POST["celurepresentante"];
$vinculorepresentante = $_POST["vinculorepresentante"];
$nomconyuge = $_POST["nomconyuge"];
$nhijos = $_POST["nhijos"];
$vivecon = $_POST["vivecon"];
$viveconobs = $_POST["viveconobs"];
$hijosmayores = $_POST["hijosmayores"];


    // Consulta SQL para verificar si hay algún registro con el mismo run en la tabla alum
  /*  $consulta_alum_familia = "SELECT run_alum_familia, neducpadre, nivelciclopadre, neducmadre, nivelciclomadre, nomtutor, runtutor, directutor, 
    emailtutor, comunatutor, sectortutor, celututor, vinculotutor, nomrepresentante, runrepresentante, direcrepresentante, emailrepresentante, 
    comunarepresentante, sectorrepresentante, celurepresentante, vinculorepresentante, nomconyuge, nhijos, vivecon, hijosmayores
    FROM alum_familia  WHERE run_alum_familia = '$run'";
*/

    $consulta_alum_familia = "SELECT * FROM alum_familia WHERE run_alum_familia = '$run'";

    $resultado_consulta_familia = $conn->query($consulta_alum_familia);
    if ($resultado_consulta_familia->num_rows > 0) {

// Si existe un registro con el mismo run, realizar una actualización
        $consulta_actualizar_familia = "UPDATE alum_familia SET run_alum_familia = '$run', neducpadre = '$neducpadre', nivelciclopadre =  '$nivelciclopadre', 
        neducmadre = '$neducmadre', nivelciclomadre = '$nivelciclomadre', nomtutor = '$nomtutor', runtutor = '$runtutor', directutor = '$directutor',
        emailtutor = '$emailtutor', comunatutor = '$comunatutor', sectortutor = '$sectortutor', celututor = '$celututor', vinculotutor = '$vinculotutor', 
        nomrepresentante = '$nomrepresentante', runrepresentante = '$runrepresentante', direcrepresentante = '$direcrepresentante', emailrepresentante = '$emailrepresentante', 
        comunarepresentante = '$comunarepresentante', sectorrepresentante = '$sectorrepresentante', celurepresentante = '$celurepresentante', vinculorepresentante = '$vinculorepresentante', 
        nomconyuge = '$nomconyuge', nhijos = '$nhijos', vivecon = '$vivecon', viveconobs = '$viveconobs', hijosmayores = '$hijosmayores'
        WHERE run_alum_familia = '$run'";


        if ($conn->query($consulta_actualizar_familia) === TRUE) {
            echo "Registro actualizado correctamente TABLA FAMILIA";

        // Redireccionar a otra página después de 5 segundos
     //  echo "<script>setTimeout(function() { window.location.href = 'm.alum.php'; }, 1000);</script>";
        
    } else {
            echo "Error al actualizar el registro TABLA FAMILIA: " . $conn->error;
        }
    } else {

        // Si no existe ningún registro con el mismo run, realizar una inserción
        $consulta_insertar_familia = "INSERT INTO alum_familia (run_alum_familia, neducpadre, nivelciclopadre, neducmadre, nivelciclomadre, 
        nomtutor, runtutor, directutor, emailtutor, comunatutor, sectortutor, celututor, vinculotutor, nomrepresentante, runrepresentante, direcrepresentante, 
        emailrepresentante, comunarepresentante, sectorrepresentante, celurepresentante, vinculorepresentante, nomconyuge, nhijos, vivecon, viveconobs, hijosmayores)
        VALUES 
        
        ('$run', '$neducpadre', '$nivelciclopadre', '$neducmadre', '$nivelciclomadre', '$nomtutor', '$runtutor', '$directutor', '$emailtutor', 
        '$comunatutor', '$sectortutor', '$celututor', '$vinculotutor', '$nomrepresentante', '$runrepresentante', '$direcrepresentante', '$emailrepresentante', 
        '$comunarepresentante', '$sectorrepresentante', '$celurepresentante', '$vinculorepresentante', '$nomconyuge', '$nhijos', '$vivecon', '$viveconobs',
        '$hijosmayores')";

        if ($conn->query($consulta_insertar_familia) === TRUE) {
            echo "Nuevo registro insertado correctamente TABLA FAMILIA";
        } else {
            echo "Error al insertar nuevo registro TABLA FAMILIA: " . $conn->error;
        }
    }


// 4. AVISAR EN CASO DE EMERGENCIA / Observaciones

$nomemergencia = $_POST["nomemergencia"];
$celuemergencia = $_POST["celuemergencia"];
$direcemergencia = $_POST["direcemergencia"];
$observa = $_POST["observa"];

// Consulta SQL para verificar si hay algún registro con el mismo run en la tabla alum
    $consulta_emergencia = "SELECT run_alum_emergencia, nomemergencia, celuemergencia, direcemergencia, observaemergencia 
    FROM alum_emergencia WHERE  run_alum_emergencia = '$run'";

    $resultado_consulta_emergencia = $conn->query($consulta_emergencia);
        if ($resultado_consulta_emergencia->num_rows > 0) {

// Si existe un registro con el mismo run, realizar una actualización
        $consulta_actualizar_emergencia = "UPDATE alum_emergencia SET nomemergencia = '$nomemergencia', celuemergencia = '$celuemergencia', 
        direcemergencia = '$direcemergencia',  observaemergencia = '$observa' WHERE run_alum_emergencia = '$run'";

        if ($conn->query($consulta_actualizar_emergencia) === TRUE) {
            echo "Registro actualizado correctamente TABLA FAMILIA";   
// Redireccionar a otra página después de 5 segundos
//  echo "<script>setTimeout(function() { window.location.href = 'm.alum.php'; }, 1000);</script>";
        } else {
            echo "Error al actualizar el registro TABLA FAMILIA: " . $conn->error;
        }
    }

    else {
// Si no existe ningún registro con el mismo run, realizar una inserción
        $consulta_insertar_emergencia = "INSERT INTO alum_emergencia (run_alum_emergencia, nomemergencia, celuemergencia, direcemergencia, observaemergencia)
        VALUES 
        ('$run', '$nomemergencia', '$celuemergencia', '$direcemergencia', '$observa')";

        if ($conn->query($consulta_insertar_emergencia) === TRUE) {
            echo "Nuevo registro insertado correctamente TABLA FAMILIA";
        } else {
            echo "Error al insertar nuevo registro TABLA FAMILIA: " . $conn->error;
        }
    }





}
// Cerrar la conexión
$conn->close();
?>
