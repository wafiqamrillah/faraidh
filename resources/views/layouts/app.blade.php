<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Faraidh') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="bg-gray-300 font-sans antialiased">
        <div class="fixed w-full top-0 z-30">
            @livewire('layouts.navigation.top')
        </div>

        <!-- Page Content -->
        <main class="container mx-auto mt-14 mb-16 px-2 md:px-4 lg:px-8">
            @livewire('layouts.page-content', ['content' => base64_encode($slot)])
            {{-- {{$slot}} --}}
        </main>

        <div class="fixed w-full bottom-0 z-30">
            @livewire('layouts.navigation.bottom')
        </div>

        @stack('modals')

        @livewire('layouts.modal')

        @livewireScripts
        <x-livewire-alert::scripts/>
    </body>
</html>
