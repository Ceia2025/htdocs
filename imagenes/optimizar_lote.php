<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

set_time_limit(0); // importante para muchas imágenes

$origenBase = __DIR__ . '/original';
$destinoBase = __DIR__ . '/images';

function optimizarImagen($origen, $destino, $maxAncho = 1400, $calidad = 80)
{

    $info = getimagesize($origen);
    if (!$info)
        return;

    [$ancho, $alto] = $info;

    $ratio = $ancho / $alto;

    if ($ancho > $maxAncho) {
        $nuevoAncho = $maxAncho;
        $nuevoAlto = intval($nuevoAncho / $ratio);
    } else {
        $nuevoAncho = $ancho;
        $nuevoAlto = $alto;
    }

    $src = imagecreatefromjpeg($origen);
    $dst = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

    imagecopyresampled(
        $dst,
        $src,
        0,
        0,
        0,
        0,
        $nuevoAncho,
        $nuevoAlto,
        $ancho,
        $alto
    );

    imagewebp($dst, $destino, $calidad);

    imagedestroy($src);
    imagedestroy($dst);
}

$albums = scandir($origenBase);

foreach ($albums as $album) {

    if ($album === '.' || $album === '..')
        continue;

    $rutaOrigenAlbum = "$origenBase/$album";
    $rutaDestinoAlbum = "$destinoBase/$album";

    if (!is_dir($rutaOrigenAlbum))
        continue;

    if (!is_dir($rutaDestinoAlbum)) {
        mkdir($rutaDestinoAlbum, 0755, true);
    }

    foreach (glob("$rutaOrigenAlbum/*.{jpg,jpeg,JPG,JPEG}", GLOB_BRACE) as $img) {

        $nombre = pathinfo($img, PATHINFO_FILENAME);
        $destino = "$rutaDestinoAlbum/$nombre.webp";

        // 🔑 solo optimiza si NO existe
        if (!file_exists($destino)) {
            optimizarImagen($img, $destino);
        }
    }
}

echo "Optimización finalizada correctamente";
