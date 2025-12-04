<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - TerraHome</title>
    
    {{-- Vite Resources --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased flex flex-col min-h-screen">

    {{-- INCLUDE NAVBAR --}}
    @include('partials.navbar-home')

    {{-- CONTENT (Dynamic) --}}
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    {{-- INCLUDE FOOTER --}}
    @include('partials.footer')

</body>
</html>