<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Online Library') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-gray-50 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        @include('layouts.navigation')


        <!-- Page Heading -->
        @isset($header)
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200 shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-semibold text-blue-800">{{ $header }}</h1>
                </div>
            </div>
        @endisset

        <!-- Page Content -->
        <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto py-4 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Online Library. All rights reserved.
        </footer>
    </div>
</body>

</html>