<?php
$ruta = $_GET['url'] ?? 'home';

switch ($ruta) {
    case 'app':
        header('Location: app/');
        exit;
    case 'descarga':
        header('Location: descarga/');
        exit;
    case 'pdf':
        header('Location: CombentirVariosPDFEnUno/');
        exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CEIA Juanita Zúñiga</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">

    <div class="logo">
        <img src="app/public/img/LOGO CEIA.JPG" alt="Logo CEIA">
    </div>

    <h1>Centro de Educación Integrada de Adultos</h1>
    <h2>Juanita Zúñiga – Parral</h2>

    <p class="bienvenida">
        Bienvenido al portal institucional del CEIA Juanita Zúñiga.
    </p>

    <div class="acciones">
        <a href="app/public/index.php?action=login">Ingresar al Sistema</a>
        <a href="CombertirVariosPDFEnUno/index.php">Unir PDFs</a>
        <a href="imagenes/index.php">Imagenes</a>
        <a href="descarga/index.html">Descargas</a>
    </div>

</div>

<footer>
    © <?= date('Y') ?> CEIA Juanita Zúñiga - Parral - Desarrollador por Daniel Scarlazzetta
</footer>

</body>
</html>
