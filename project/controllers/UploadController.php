<?php
require_once("../config/Database.php");
require_once("../models/RecordModel.php");

if (isset($_POST['upload'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        
        $fileTmp = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

        $allowedExt = ["txt"];

        if (in_array(strtolower($fileExt), $allowedExt)) {
            
            $db = (new Database())->getConnection();
            $recordModel = new RecordModel($db);

            $file = fopen($fileTmp, "r");

            // Saltar cabecera
            $header = fgets($file);

            while (($line = fgets($file)) !== false) {
                $line = trim($line);
                if ($line == "") continue;

                $data = explode(",", $line);

                if (count($data) == 5) {
                    $id     = trim($data[0]);
                    $rut    = trim($data[1]);
                    $nombre = trim($data[2]);
                    $desc   = trim($data[3]);
                    $fecha  = trim($data[4]);

                    if (!$recordModel->exists($id, $rut)) {
                        $recordModel->insert($id, $rut, $nombre, $desc, $fecha);
                    }
                }
            }
            fclose($file);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cargando...</title>
  <script>
    // Redirigir después de 2 segundos
    setTimeout(() => {
      window.location.href = "../index.php";
    }, 2000);
  </script>
</head>
<body>
  <h2>Procesando archivo, por favor espere...</h2>
</body>
</html>
