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
//include($_SERVER['DOCUMENT_ROOT'] . '/saat3/includes/config.php');
require '../../fpdf/fpdf.php';

class PDF extends FPDF
{

   // Cabecera de página
   function Header()
   {
      include($_SERVER['DOCUMENT_ROOT'] . '../includes/config.php'); //llamamos a la conexion BD

      $consulta_info = $conn->query(" select * from alum "); //traemos datos de la empresa desde BD
      $dato_info = $consulta_info->fetch_object();

      $this->Image($_SERVER['DOCUMENT_ROOT'].'/fpdf/logo.png', 8, 5, 45);
      $this->Image($_SERVER['DOCUMENT_ROOT'].'/img/logo_saat.jpg', 230, 5, 40); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
      $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(95); // Movernos a la derecha
      $this->SetTextColor(0, 0, 0); //color
      //creamos una celda o fila
      
       //creamos una celda o fila
       $this->Cell(70, 15, utf8_decode('C.E.I.A Juanita Zúñiga Fuentes'), 0, 1, 'C', 0); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea(1-0),posicion(L-C-R),ColorFondo(1-0)
       $this->Ln(1); // Salto de línea
       $this->SetTextColor(103); //color
/*
    /* UBICACION 
    $this->Cell(10);  // mover a la derecha
    $this->SetFont('Arial', 'B', 10);
    $this->Cell(80, 10, utf8_decode("Profesor Jefe : "), 0, 0, '', 0);
    $this->Ln(5);

    /* TELEFONO 
    $this->Cell(10);  // mover a la derecha
    $this->SetFont('Arial', 'B', 10);
    $this->Cell(59, 10, utf8_decode("Curso : "), 0, 0, '', 0);
    $this->Ln(5);

    /* COREEO 
    $this->Cell(10);  // mover a la derecha
    $this->SetFont('Arial', 'B', 10);
    $this->Cell(85, 10, utf8_decode("Total Curso : "), 0, 0, '', 0);
    $this->Ln(5);
   /* $this->Ln(5); 

*/

      /* TITULO DE LA TABLA */
      //color
      $this->SetTextColor(8, 86, 205);
      $this->Cell(100); // mover a la derecha
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(65, 9, utf8_decode("REPORTE TOTAL DE ALUMNOS "), 0, 1, 'C', 0);
      $this->Ln(5);


      /* CAMPOS DE LA TABLA */
      //color
      $this->SetFillColor(247, 249, 78); //colorFondo
      $this->SetTextColor(27, 124, 222); //colorTexto
      $this->SetDrawColor(163, 163, 163); //colorBorde
      $this->SetFont('Arial', 'B', 10);
      /*
      $this->Cell(15, 10, utf8_decode('N°'), 1, 0, 'C', 1);
      $this->Cell(80, 10, utf8_decode('RUN'), 1, 0, 'C', 1);
      $this->Cell(30, 10, utf8_decode('DV'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('NOMBRES'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('A.PATERNO'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('A.MATERNO'), 1, 1, 'C', 1);
*/
$this->Cell(10, 8, utf8_decode('N°'), 1, 0, 'C', 1);
$this->Cell(20, 8, utf8_decode('RUN'), 1, 0, 'C', 1);
$this->Cell(10, 8, utf8_decode('DV'), 1, 0, 'C', 1);
$this->Cell(25, 8, utf8_decode('A.PATERNO'), 1, 0, 'C', 1);
$this->Cell(25, 8, utf8_decode('A.MATERNO'), 1, 0, 'C', 1);
$this->Cell(40, 8, utf8_decode('NOMBRES'), 1, 0, 'C', 1);
$this->Cell(55, 8, utf8_decode('CURSO'), 1, 0, 'C', 1);
$this->Cell(55, 8, utf8_decode('TIPO'), 1, 0, 'C', 1);
$this->Cell(25, 8, utf8_decode('F. NAC'), 1, 1, 'C', 1);
   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
      date_default_timezone_set('America/Santiago'); // Establecer la zona horaria deseada, por ejemplo, Nueva York
      $hoy = date('d/m/Y H:i:s'); // Obtener fecha y hora actual en formato dd/mm/YYYY HH:MM:SS
      $this->Cell(500, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de página (fecha y hora)
  }
}

include '../../includes/config.php';
/* CONSULTA INFORMACION DEL HOSPEDAJE */

$pdf = new PDF();
$pdf->AddPage("landscape"); /* aqui entran dos para parametros (horientazion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$i = 0;
$pdf->SetFont('Arial', '', 8);
$pdf->SetDrawColor(163, 163, 163); //colorBorde

$consulta_reporte_alumnos = $conn->query("select run, digv, nombres, apaterno, amaterno, descgrado, tipoensenanza, fnac from alum ORDER BY codtipoense ASC, codgrado ASC ");


while ($datos_reporte = $consulta_reporte_alumnos->fetch_object()) {
   $i = $i + 1;
   /* TABLA */
   $pdf->Cell(10, 8, utf8_decode($i), 1, 0, 'C', 0);
   $pdf->Cell(20, 8, utf8_decode($datos_reporte->run), 1, 0, 'C', 0);
   $pdf->Cell(10, 8, utf8_decode($datos_reporte->digv), 1, 0, 'C', 0);
   
   $pdf->Cell(25, 8, utf8_decode($datos_reporte->apaterno), 1, 0, 'C', 0);
   $pdf->Cell(25, 8, utf8_decode($datos_reporte->amaterno), 1, 0, 'C', 0);
   $pdf->Cell(40, 8, utf8_decode($datos_reporte->nombres), 1, 0, 'C', 0);
   $pdf->Cell(55, 8, utf8_decode($datos_reporte->descgrado), 1, 0, 'C', 0);
   $pdf->Cell(55, 8, utf8_decode($datos_reporte->tipoensenanza), 1, 0, 'C', 0);
   $pdf->Cell(25, 8, utf8_decode($datos_reporte->fnac), 1, 1, 'C', 0);
}


$pdf->Output('Reporte_Alumnos_x_Curso.pdf', 'I');//nombreDescarga, Visor(I->visualizar - D->descargar)

