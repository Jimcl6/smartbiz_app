<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SmartBiz') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen">
    <div id="app">
        <!-- Vue 3 App will mount here -->
    </div>
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</body>
</html>
