<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" href="{{ asset('/vendor/laravel-maker/favicon.ico') }}">

    <title>Laravel Maker{{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>

    <!-- Style sheets-->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600" rel="stylesheet"/>
    @routes
    <link href="{{ asset(mix('laravel-maker.css', 'vendor/laravel-maker')) }}" rel="stylesheet" type="text/css">
    @inertiaHead
</head>
<body>
@inertia
<script src="{{ asset(mix('laravel-maker.js', 'vendor/laravel-maker')) }}"></script>
</body>
</html>
