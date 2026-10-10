<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Convedya'))</title>

    <script>
        if (sessionStorage.getItem('cv-trans')) {
            document.documentElement.classList.add('from-mic');
            sessionStorage.removeItem('cv-trans');
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400..." rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .cv-loader{
            position:fixed; inset:0; z-index:9999;
            background:radial-gradient(ellipse 60% 50% at 50% 45%, #1a1030 0%, #0A0A12 70%);
            display:flex; flex-direction:column; align-items:center; justify-content:center; gap:22px;
            opacity:0; visibility:hidden; pointer-events:none;
            transition:opacity .3s ease, visibility .3s ease;
        }
        .cv-loader.show, .from-mic .cv-loader{ opacity:1; visibility:visible; pointer-events:auto; }
        .from-mic.cv-done .cv-loader{ opacity:0; visibility:hidden; pointer-events:none; }

        .cv-track{ position:relative; width:320px; height:96px; }
        .cv-roll{
             position:absolute; left:0; bottom:14px; width:84px;
             animation:cv-move 1.6s linear infinite;
        }
        .cv-roll img{
             width:100%; display:block;
             animation:cv-spin 1.6s linear infinite;
        }
        .cv-roll::after{
            content:''; position:absolute; left:8px; right:8px; bottom:-12px; height:10px;
            border-radius:50%; background:rgba(127,0,255,.4); filter:blur(4px);
        }
        @keyframes cv-move{
            0%{ transform:translateX(-90px); opacity:0; }
            15%{ opacity:1; }
            85%{ opacity:1; }
            100%{ transform:translateX(330px); opacity:0; }
        }
        @keyframes cv-spin{ from{ transform:rotate(0); } to{ transform:rotate(570deg); } }

        .cv-text{ margin:0; font-family:'Sora',sans-serif; font-weight:600; font-size:15px; color:#FFF6FF; }
        .cv-text span{ animation:cv-dot 1.2s infinite; opacity:.2; }
        .cv-text span:nth-child(2){ animation-delay:.2s; }
        .cv-text span:nth-child(3){ animation-delay:.4s; }
        @keyframes cv-dot{ 0%,100%{opacity:.2} 40%{opacity:1} }

        @media (prefers-reduced-motion:reduce){
            .cv-roll, .cv-roll img, .cv-text span{ animation:none; opacity:1; }
        }
    </style>


    @stack('styles')
</head>

<body>
    <div class="cv-loader" role="status" aria-live="polite">
        <div class="cv-track">
            <div class="cv-roll"><img src="{{ asset('images/veya-face.png') }}" alt=""></div>
        </div>
        <p class="cv-text">Menyiapkan percakapan<span>.</span><span>.</span><span>.</span></p>
    </div>

    @yield('content')

    @stack('scripts')

    <script>
        if (document.documentElement.classList.contains('from-mic')) {
            var t0 = performance.now();
            var hideLoader = function () {
                var wait = Math.max(0, 900 - (performance.now() - t0));
                setTimeout(function () {
                    document.documentElement.classList.add('cv-done');
                }, wait);
            };
            if (document.readyState === 'complete') hideLoader();
            else window.addEventListener('load', hideLoader);
        }
    </script>
</body>
</html>
