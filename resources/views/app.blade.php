<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="La piattaforma dedicata agli Studi di registrazione e alle Sale prova d'Italia. Cerca, scegli e contatta lo spazio creativo più adatto alle tue esigenze.">
        <meta name="keywords" content="studi di registrazione, sale prova, studio di registrazione, sala prove, registrazione, musica, prove, mixing, recording, mastering, rehearsal room, recording room, suonare, cantare, prove musicali">
        <meta name="robots" content="index, follow">
        <meta name="author" content="OrangeWeb">

        <meta property="og:site_name" content="Musigate">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Musigate - SuonoErgoSono">
        <meta property="og:description" content="La piattaforma dedicata agli Studi di registrazione e alle Sale prova d'Italia. Cerca, scegli e contatta lo spazio creativo più adatto alle tue esigenze.">
        <meta property="og:image" content="/og-image.jpg">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="690">

        <link rel="icon" href="/favicon.png">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
        <link rel="mask-icon" href="/img/pwa/mask-icon.svg" color="#020617">
        <link rel="apple-touch-icon" href="/img/pwa/apple-touch-icon.png" sizes="180x180">
        <meta name="theme-color" content="#020617">

        <title inertia>{{ config('app.name', 'Musigate') }}</title>

        @if (config('app.env') === 'production')
            <!-- PWA  -->
            <link rel="manifest" href="/build/manifest.webmanifest" type="application/manifest+json">
            <script src="/build/registerSW.js"></script>

            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-95DL9K2B2N"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', 'G-95DL9K2B2N');
            </script>

            <!-- Plausible -->
            <script defer data-domain="musigate.it" src="https://plausible.io/js/script.js"></script>
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Iubenda -->
        <script type="text/javascript">
            var _iub = _iub || [];
            _iub.csConfiguration = {"siteId":3813424,"cookiePolicyId":62455232,"lang":"it","storage":{"useSiteId":true}};
            </script>
        <script type="text/javascript" src="https://cs.iubenda.com/autoblocking/3813424.js"></script>
        <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>
        <script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans font-extralight text-slate-100 bg-slate-950">
        @inertia

        <div id="drawer"></div>
        <div id="flash-message"></div>
        <div id="modal"></div>
        <div id="spinner"></div>
    </body>
</html>
