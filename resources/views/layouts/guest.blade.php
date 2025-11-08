<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Online Library') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Tailwind + Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Optional: line clamp for book descriptions */
            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    </head>

    <body class="font-inter bg-gray-50 text-gray-900 flex flex-col min-h-screen">
        <!-- Navbar -->
        <nav class="bg-white shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="text-2xl font-extrabold text-blue-700 hover:text-blue-800 transition">
                    📚 Online Library
                </a>
                <div class="flex items-center space-x-6 text-gray-700 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-blue-700 transition">Home</a>
                    <a href="{{ route('about') }}" class="hover:text-blue-700 transition">About</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700 transition">Admin</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-blue-700 transition">Login</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12 py-6">
            <div class="max-w-7xl mx-auto px-6 text-center text-sm text-gray-500">
                © {{ date('Y') }} Online Library. All rights reserved.
            </div>
        </footer>
    </body>
</html>
