<?php
// Configuración: carpeta de destino (la misma del script)
//$uploadDir = __DIR__ . "C:/Users/Felipe/Desktop/videos/";
$uploadDir = __DIR__ . "/";

// Si se envió un archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $file = $_FILES['video'];
    
    // Verificar que no hubo errores
    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = basename($file['name']);
        $targetPath = $uploadDir . $filename;

        // Validar tipo de archivo (solo videos básicos)
        $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg', 'video/MKV'];
        if (in_array($file['type'], $allowedTypes)) {
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                echo "<p>✅ Video subido correctamente: $filename</p>";
            } else {
                echo "<p>❌ Error al mover el archivo.</p>";
            }
        } else {
            echo "<p>⚠️ Tipo de archivo no permitido.</p>";
        }
    } else {
        echo "<p>⚠️ Error en la subida.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Videos</title>
</head>
<body>
    <h1>Subir Video</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="video" accept="video/*" required>
        <button type="submit">Subir</button>
    </form>

    <h2>Videos Subidos</h2>
    <?php
    // Listar archivos de video en la carpeta
    $videos = glob($uploadDir . "*.{mp4,webm,ogg}", GLOB_BRACE);
    if ($videos) {
        foreach ($videos as $video) {
            $basename = basename($video);
            echo "<div>
                    <p>$basename</p>
                    <video src='$basename' controls width='400'></video>
                  </div><hr>";
        }
    } else {
        echo "<p>No hay videos subidos aún.</p>";
    }
    ?>
</body>
</html>
