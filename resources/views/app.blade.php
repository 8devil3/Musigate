<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Musigate è il motore di ricerca degli Studi di registrazione e delle Sale prova d'Italia">
        <meta name="keywords" content="studi di registrazione, sale prova, studio di registrazione, sala prove">
        <meta name="author" content="Musigate">
    
        <link rel="icon" href="/favicon.png">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
        <link rel="mask-icon" href="/img/pwa/mask-icon.svg" color="#020617">
        <link rel="apple-touch-icon" href="/img/pwa/apple-touch-icon.png" sizes="180x180">
        <meta name="theme-color" content="#020617">

        <!-- PWA 
        <link rel="manifest" href="/build/manifest.webmanifest" type="application/manifest+json">
        <script src="/build/registerSW.js"></script> -->

        <title inertia>{{ config('app.name', 'Musigate') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Fontawesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans font-extralight text-slate-100 bg-slate-950">
        @inertia

        <div id="spinner"></div>
    </body>
</html>
