<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Herbal Roots') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .auth-wrap{min-height:100vh;display:grid;place-items:center;padding:2rem 1rem;position:relative;overflow:hidden;
                background:linear-gradient(135deg,#0a3a37,#137a6e 55%,#072c2a)}
            .auth-wrap::before,.auth-wrap::after{content:'';position:absolute;border-radius:50%;filter:blur(10px);opacity:.24}
            .auth-wrap::before{background:#5fc2b3}.auth-wrap::after{background:#d9b26a}
            .auth-wrap::before{width:340px;height:340px;top:-80px;right:-60px}
            .auth-wrap::after{width:300px;height:300px;bottom:-70px;left:-50px}
            .auth-card{position:relative;z-index:2;width:100%;max-width:440px;background:#fff;border-radius:20px;
                padding:2.4rem 2.2rem;box-shadow:0 30px 60px rgba(0,0,0,.3)}
            .auth-logo{text-align:center;margin-bottom:1.4rem}.auth-logo img{height:54px;display:inline-block}
            .auth-tag{text-align:center;color:#5e726f;font-size:.92rem;margin-bottom:1.4rem}
            .auth-tabs{display:flex;background:#eef5f4;border-radius:50px;padding:5px;margin-bottom:1.6rem}
            .auth-tabs a{flex:1;text-align:center;padding:.6rem;border-radius:50px;font-weight:700;font-size:.92rem;
                color:#5e726f;text-decoration:none;transition:all .3s cubic-bezier(.22,.61,.36,1)}
            .auth-tabs a.active{background:linear-gradient(135deg,#137a6e,#0e4d49);color:#fff;
                box-shadow:0 6px 16px rgba(14,77,73,.32)}
            .auth-tabs a:not(.active):hover{color:#0e4d49}
            .auth-switch{text-align:center;margin-top:1.4rem;font-size:.9rem;color:#5e726f}
            .auth-switch a{color:#137a6e;font-weight:700;text-decoration:none}
            .auth-switch a:hover{text-decoration:underline}
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="auth-wrap">
            <div class="auth-card">
                <div class="auth-logo"><a href="/"><img src="{{ asset('images/logo.svg') }}" alt="Herbal Roots"></a></div>
                <p class="auth-tag">🌿 Welcome to natural wellness</p>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
