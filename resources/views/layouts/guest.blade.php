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

<body class="font-inter bg-gray-50 text-gray-900 flex flex-col min-h-screen overflow-x-hidden">

    {{-- Navbar tampil hanya jika bukan halaman login/register --}}
    @if(!request()->routeIs('login') && !request()->routeIs('register') && !request()->routeIs('password.*'))
        <nav x-data="{ open: false }" class="bg-white shadow-md sticky top-0 z-50 border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-12 sm:h-14">
                    <a href="{{ route('home') }}" class="text-lg sm:text-2xl font-extrabold text-blue-700 hover:text-blue-800 transition">
                        📚 Taman Bacaan Masyarakat
                    </a>

                    <div class="hidden sm:flex items-center gap-4 text-gray-700 font-medium text-sm sm:text-base">
                        <a href="{{ route('home') }}" class="hover:text-blue-700 transition {{ request()->routeIs('home') ? 'text-blue-700' : '' }}">Dashboard</a>
                        <a href="{{ route('books.index') }}" class="hover:text-blue-700 transition {{ request()->routeIs('books.index') ? 'text-blue-700' : '' }}">Buku</a>
                        <a href="{{ route('activities.index') }}" class="hover:text-blue-700 transition {{ request()->routeIs('activities.index') ? 'text-blue-700' : '' }}">Kegiatan</a>
                        <a href="{{ route('about') }}" class="hover:text-blue-700 transition {{ request()->routeIs('about') ? 'text-blue-700' : '' }}">About</a>
                        @auth
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="hover:text-blue-700 transition">Akun</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-blue-700 transition">Login</a>
                        @endauth
                    </div>

                    <div class="sm:hidden flex items-center ml-auto">
                        <button @click="open = !open" type="button" class="p-2 rounded-md text-gray-600 hover:text-blue-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div x-show="open" x-transition class="sm:hidden pb-3 mt-1 text-sm font-medium text-gray-700 flex justify-end">
                    <div class="w-full max-w-xs text-right space-y-1">
                        <a href="{{ route('home') }}" class="block py-2 border-t border-gray-100 hover:text-blue-700 transition {{ request()->routeIs('home') ? 'text-blue-700' : '' }}">Dashboard</a>
                        <a href="{{ route('books.index') }}" class="block py-2 hover:text-blue-700 transition {{ request()->routeIs('books.index') ? 'text-blue-700' : '' }}">Buku</a>
                        <a href="{{ route('activities.index') }}" class="block py-2 hover:text-blue-700 transition {{ request()->routeIs('activities.index') ? 'text-blue-700' : '' }}">Kegiatan</a>
                        <a href="{{ route('about') }}" class="block py-2 hover:text-blue-700 transition {{ request()->routeIs('about') ? 'text-blue-700' : '' }}">About</a>
                    @auth
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="block py-2 hover:text-blue-700 transition">Akun</a>
                    @else
                        <a href="{{ route('login') }}" class="block py-2 hover:text-blue-700 transition">Login</a>
                    @endauth
                    </div>
                </div>
            </div>
        </nav>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    {{-- Footer juga disembunyikan di halaman login/register --}}
    @if(!request()->routeIs('login') && !request()->routeIs('register') && !request()->routeIs('password.*'))
        <footer class="bg-white border-t mt-8 py-4">
            <div class="max-w-7xl mx-auto px-6 text-center text-xs sm:text-sm text-gray-500">
                © {{ date('Y') }} Online Library. All rights reserved.
            </div>
        </footer>
    @endif
</body>
</html>
