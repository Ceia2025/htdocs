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
require('../fpdf/fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        $this->Image('../fpdf/logo.png',10,8,40);
        $this->Image('../img/logo_saat.png', 160, 10, 35); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30,10,'REPORTE DE ANOTACIONES',0,0,'C');
        // Salto de línea
        $this->Ln(25);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');

        $this->SetY(-15); // Posición: a 1,5 cm del final
        $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
        date_default_timezone_set('America/Santiago'); // Establecer la zona horaria deseada, por ejemplo, Nueva York
        $hoy = date('d/m/Y H:i:s'); // Obtener fecha y hora actual en formato dd/mm/YYYY HH:MM:SS
        $this->Cell(350, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de página (fecha y hora)
    }

    // Función para mostrar los datos de una tabla
    function ShowTable($header, $data)
    {
        // Cabecera
        foreach($header as $col) {
            $this->Cell(40,7,$col,1);
        }
        $this->Ln();
        // Datos
        foreach($data as $row)
        {
            foreach($row as $col)
                $this->Cell(40,6,$col,1);
            $this->Ln();
        }
    }
}

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "saat2");

// Verificar la conexión
if ($mysqli->connect_errno) {
    echo "Falló la conexión a MySQL: " . $mysqli->connect_error;
    exit();
}

// Obtener el ID del alumno desde el formulario
$idalum = $_GET['idalum'];

//$idalum = '100749262';


// Consulta SQL para obtener los datos de la tabla "alum"
$query_alum = "SELECT run, digv, nombres, apaterno, amaterno, descgrado FROM alum WHERE run = '$idalum'";
$result_alum = $mysqli->query($query_alum);
$data_alum = $result_alum->fetch_assoc();

// Consulta SQL para obtener los datos de la tabla "atrasos"
$query_atrasos = "SELECT fatraso, hatraso, jatraso, profesor FROM atrasos WHERE alum_run = '$idalum'";
$result_atrasos = $mysqli->query($query_atrasos);
$data_atrasos = array();
while ($row = $result_atrasos->fetch_assoc()) {
    $data_atrasos[] = $row;
}

// Consulta SQL para obtener los datos de la tabla "anotaciones"
$query_anotaciones_negativa = "SELECT fanota, hanota, anotacion, profesor, categoria FROM anotaciones WHERE alum_run = '$idalum' and tipo ='NEGATIVA'";
$result_anotaciones_negativa = $mysqli->query($query_anotaciones_negativa);
$data_anotaciones_negativa = array();
while ($row = $result_anotaciones_negativa->fetch_assoc()) {
    $data_anotaciones_negativa[] = $row;
}

// Consulta SQL para obtener los datos de la tabla "anotaciones"
$query_anotaciones_positiva = "SELECT fanota, hanota, anotacion, profesor FROM anotaciones WHERE alum_run = '$idalum' and tipo ='POSITIVA'";
$result_anotaciones_positiva = $mysqli->query($query_anotaciones_positiva);
$data_anotaciones_positiva = array();
while ($row = $result_anotaciones_positiva->fetch_assoc()) {
    $data_anotaciones_positiva[] = $row;
}

    // Consulta SQL para obtener los datos de la tabla "anotaciones"
$query_anotaciones_otras = "SELECT fanota, hanota, anotacion, profesor FROM anotaciones WHERE alum_run = '$idalum' and tipo ='OTROS_REGISTROS'";
$result_anotaciones_otras = $mysqli->query($query_anotaciones_otras);
$data_anotaciones_otras = array();
while ($row = $result_anotaciones_otras->fetch_assoc()) {
    $data_anotaciones_otras[] = $row;
}

// Consulta SQL para obtener los datos de la tabla "salidas"
$query_salidas = "SELECT fsalida, hsalida, jsalida, profesor FROM salidas WHERE alum_run = '$idalum'";
$result_salidas = $mysqli->query($query_salidas);
$data_salidas = array();
while ($row = $result_salidas->fetch_assoc()) {
    $data_salidas[] = $row;
}

$mysqli->close();




// Crear un nuevo objeto PDF
$pdf = new PDF("P", "mm", "letter");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);



// Mostrar los datos de la tabla "alum"
$pdf->Cell(0,10,'INFORMACION DEL ALUMNO:',0,1);
$header_alum = array('RUN', 'DV', 'NOMBRE', 'APELLIDO P.', 'APELLIDO M.', 'CURSO');
$pdf->ShowTable($header_alum, array($data_alum));
$pdf->Ln(5);


// Mostrar los datos de la tabla "anotaciones"
$pdf->Cell(0,10,'ANOTACIONES NEGATIVAS:',0,1);
$header_anotaciones = array('FECHA', 'HORA', 'ANOTACION', 'DOCENTE O INSPECTOR', 'CATEGORIA');
$pdf->ShowTable($header_anotaciones, $data_anotaciones_negativa);
$pdf->Ln(5);


// Mostrar los datos de la tabla "anotaciones"
$pdf->Cell(0,10,'ANOTACIONES POSITIVAS:',0,1);
$header_anotaciones = array('FECHA', 'HORA', 'ANOTACION', 'DOCENTE O INSPECTOR');
$pdf->ShowTable($header_anotaciones, $data_anotaciones_positiva);
$pdf->Ln(5);


// Mostrar los datos de la tabla "anotaciones"
$pdf->Cell(0,10,'ANOTACIONES OTROS REGISTROS:',0,1);
$header_anotaciones = array('FECHA', 'HORA', 'ANOTACION', 'DOCENTE O INSPECTOR');
$pdf->ShowTable($header_anotaciones, $data_anotaciones_otras);
$pdf->Ln(5);


// Mostrar los datos de la tabla "atrasos"
$pdf->Cell(0,9,'DETALLE ATRASOS',0,1);
$header_atrasos = array('FECHA', 'HORA', 'ANOTACION', 'DOCENTE O INSPECTOR');
$pdf->ShowTable($header_atrasos, $data_atrasos);
$pdf->Ln(5);


// Mostrar los datos de la tabla "salidas"
$pdf->Cell(0,10,'DETALLE SALIDAS',0,1);
$header_salidas = array('FECHA', 'HORA', 'ANOTACION', 'DOCENTE O INSPECTOR');
$pdf->ShowTable($header_salidas, $data_salidas);

// Salida del PDF
$pdf->Output('Registro_de_Anotaciones.pdf', 'I');//nombreDescarga, Visor(I->visualizar - D->descargar)


?>