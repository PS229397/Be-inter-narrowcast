<!DOCTYPE html>
<html lang="en" class="fi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout Builder</title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="fi-body min-h-screen bg-[#0b0b0f] text-white antialiased">
    @livewire('layout-builder-test')
    @filamentScripts(withCore: true)
</body>
</html>
