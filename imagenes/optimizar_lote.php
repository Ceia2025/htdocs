<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

set_time_limit(0);
ob_implicit_flush(true);
ob_end_flush();

$origenBase  = __DIR__ . '/original';
$destinoBase = __DIR__ . '/images';
$cancelFile  = __DIR__ . '/cancelar.flag';

@unlink($cancelFile);

/* ==========================
   FUNCIÓN OPTIMIZAR
========================== */
function optimizarImagen($origen, $destino, $maxAncho = 1400, $calidad = 80)
{
    $info = getimagesize($origen);
    if (!$info) return;

    $src = imagecreatefromjpeg($origen);

    // === ORIENTACIÓN EXIF ===
    $exif = @exif_read_data($origen);
    $orientacion = $exif['Orientation'] ?? 1;

    switch ($orientacion) {
        case 3:
            $src = imagerotate($src, 180, 0);
            break;
        case 6:
            $src = imagerotate($src, -90, 0);
            break;
        case 8:
            $src = imagerotate($src, 90, 0);
            break;
    }

    // 🔑 OBTENER TAMAÑO REAL DESPUÉS DE ROTAR
    $ancho = imagesx($src);
    $alto  = imagesy($src);

    // === ESCALADO PROPORCIONAL ===
    if ($ancho > $maxAncho) {
        $ratio = $ancho / $alto;
        $nuevoAncho = $maxAncho;
        $nuevoAlto = intval($nuevoAncho / $ratio);
    } else {
        $nuevoAncho = $ancho;
        $nuevoAlto = $alto;
    }

    $dst = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

    imagecopyresampled(
        $dst,
        $src,
        0, 0, 0, 0,
        $nuevoAncho,
        $nuevoAlto,
        $ancho,
        $alto
    );

    imagewebp($dst, $destino, $calidad);

    imagedestroy($src);
    imagedestroy($dst);
}


/* ==========================
   CONTAR TOTAL
========================== */
$imagenes = glob("$origenBase/*/*.{jpg,jpeg,JPG,JPEG}", GLOB_BRACE);
$total = count($imagenes);

echo "<script>parent.iniciarProgreso($total);</script>";
flush();

/* ==========================
   PROCESO
========================== */
$inicio = microtime(true);
$procesadas = 0;
$optimizadas = 0;

foreach ($imagenes as $img) {

    if (file_exists($cancelFile)) {
        echo "<script>parent.cancelado();</script>";
        exit;
    }

    $album = basename(dirname($img));
    $nombre = pathinfo($img, PATHINFO_FILENAME);

    $destinoDir = "$destinoBase/$album";
    if (!is_dir($destinoDir)) mkdir($destinoDir, 0755, true);

    $destino = "$destinoDir/$nombre.webp";

    if (!file_exists($destino)) {
        if (optimizarImagen($img, $destino)) {
            $optimizadas++;
        }
    }

    $procesadas++;

    $porcentaje = intval(($procesadas / $total) * 100);

    $tiempoActual = microtime(true);
    $transcurrido = $tiempoActual - $inicio;
    $tiempoEstimado = ($transcurrido / $procesadas) * ($total - $procesadas);

    echo "<script>
        parent.actualizarProgreso($porcentaje, " . intval($tiempoEstimado) . ");
    </script>";

    flush();
}

echo "<script>
    parent.finalizarProgreso($optimizadas, $total);
</script>";
