<!DOCTYPE html>
<html class="h-full" lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Aplica el tema guardado ANTES de pintar, para evitar el flash de color -->
    <script>
        (function () {
            var saved = localStorage.getItem('saat-theme');
            document.documentElement.classList.toggle('dark', saved !== 'light');
        })();
    </script>

    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/app/public/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        /* Activa el modo oscuro por clase ".dark" en <html> en vez de prefers-color-scheme */
        @custom-variant dark (&:where(.dark, .dark *));

        @theme {
            --color-azul-oscuro: #0a1628;
            --color-azul-medio: #0d2247;
            --color-azul-vivo: #1a4fd6;
            --color-azul-claro: #3b82f6;
            --color-amarillo: #f5c518;
            --color-amarillo-claro: #fde68a;

            --font-sans: "DM Sans", sans-serif;
            --font-display: "Sora", sans-serif;
        }
    </style>

    <script src="//unpkg.com/alpinejs" defer></script>

    <title>SAAT</title>
</head>

<body class="h-full app-shell font-sans">
    <div class="min-h-full">