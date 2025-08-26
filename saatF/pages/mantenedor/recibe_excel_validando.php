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
require('../../includes/config.php');
$tipo       = $_FILES['dataAlumno']['type'];
$tamanio    = $_FILES['dataAlumno']['size'];
$archivotmp = $_FILES['dataAlumno']['tmp_name'];
$lineas     = file($archivotmp);

$i = 0;

foreach ($lineas as $linea) {
    $cantidad_registros = count($lineas);
    $cantidad_regist_agregados =  ($cantidad_registros - 1);

    if ($i != 0) {

        $datos = explode(";", $linea);
       
        $anio                   = !empty($datos[0])  ? ($datos[0]) : '';
		$rbd                    = !empty($datos[1])  ? ($datos[1]) : '';
        $codtipoense            = !empty($datos[2])  ? ($datos[2]) : '';
        $codgrado               = !empty($datos[3])  ? ($datos[3]) : '';
        $descgrado              = !empty($datos[4])  ? ($datos[4]) : '';
        $letracurso             = !empty($datos[5])  ? ($datos[5]) : '';
		$run                    = !empty($datos[6])  ? ($datos[6]) : '';
        $digv                   = !empty($datos[7])  ? ($datos[7]) : '';
        $genero                 = !empty($datos[8])  ? ($datos[8]) : '';
        $nombres                = !empty($datos[9])  ? ($datos[9]) : '';
        $apaterno               = !empty($datos[10])  ? ($datos[10]) : '';
		$amaterno               = !empty($datos[11])  ? ($datos[11]) : '';
        $direccion              = !empty($datos[12])  ? ($datos[12]) : '';
        $comuna                 = !empty($datos[13])  ? ($datos[13]) : '';
        $codcomuna              = !empty($datos[14])  ? ($datos[14]) : '';
        $email                  = !empty($datos[15])  ? ($datos[15]) : '';
        $telefono               = !empty($datos[16])  ? ($datos[16]) : '';
        $celular                = !empty($datos[17])  ? ($datos[17]) : '';
        $fnac                   = !empty($datos[18])  ? ($datos[18]) : '';
        $codetnia               = !empty($datos[19])  ? ($datos[19]) : '';
		$fincorporacion         = !empty($datos[20])  ? ($datos[20]) : '';
        $fretiro                = !empty($datos[21])  ? ($datos[21]) : '';
        $asistencia             = !empty($datos[22])  ? ($datos[22]) : '';
        $promedio               = !empty($datos[23])  ? ($datos[23]) : '';


       
if( !empty($run) ){
    $checkemail_duplicidad = ("SELECT run FROM alum WHERE run='".($run)."' ");
            $ca_dupli = mysqli_query($conn, $checkemail_duplicidad);
            $cant_duplicidad = mysqli_num_rows($ca_dupli);
        }   

//No existe Registros Duplicados
if ( $cant_duplicidad == 0 ) { 

$insertarData = "INSERT INTO alum( 
            run,
            digv,
			nombres,
            apaterno,
            amaterno,
            fnac,
            celular,
            telefono,
            email,
            direcc,
            comuna,
            codcomuna,
            genero,
            fincorpora,
            fretiro,
            codetnia,
            rbd,
            anio,
            codtipoense,
            codgrado,
            descgrado,
            letracurso,
            asistencia,
            promedio

) VALUES(
            '$run',
            '$digv',
			'$nombres',
            '$apaterno',
            '$amaterno',
            '$fnac',
            '$celular',
            '$telefono',
            '$email',
            '$direccion',
            '$comuna',
            '$codcomuna',
            '$genero',
            '$fincorporacion',
            '$fretiro',
            '$codetnia',
            '$rbd',
            '$anio',
            '$codtipoense',
            '$codgrado',
            '$descgrado',
            '$letracurso',
            '$asistencia',
            '$promedio'
    
)";
mysqli_query($conn, $insertarData);
} 


/**Caso Contrario actualizo el o los Registros ya existentes*/
else{
    $updateData =  ("UPDATE alum SET 
        email='" .$email. "',
        direcc='" .$direccion. "',
        comuna='" .$comuna. "',
        codcomuna='" .$codcomuna. "',
        fincorpora='" .$fincorporacion. "',
        fretiro='" .$fretiro. "',
        codtipoense='" .$codtipoense. "',
        codgrado='" .$codgrado. "',
        descgrado='" .$descgrado. "',
        WHERE run='".$run."'
    ");
    $result_update = mysqli_query($conn, $updateData);
    } 
  }

 $i++;
}

// Indicar que el proceso se ha realizado correctamente
echo "Proceso completado correctamente.";

// Redirigir a la página anterior después de un breve retraso
header("refresh:2;url={$_SERVER['HTTP_REFERER']}");
exit;
?>
