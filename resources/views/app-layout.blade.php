@php use Laltu\LaravelMaker\Facades\LaravelMaker; @endphp
        <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>Laravel Maker</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600" rel="stylesheet"/>

    {!! LaravelMaker::css() !!}

    @livewireStyles

    {!! LaravelMaker::js() !!}
</head>
<body class="font-sans antialiased">
    <livewire:laravel-maker.dashboard/>
    @livewireScripts
    @stack('scripts')
</body>
</html>