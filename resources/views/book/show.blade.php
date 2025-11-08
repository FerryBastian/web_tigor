<x-guest-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">Online Library</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900">Home</a>
                        <a href="{{ route('about') }}" class="text-gray-700 hover:text-gray-900">About</a>
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900">Admin</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Back to Books</a>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="md:flex">
                    @if($book->cover_url)
                        <div class="md:w-1/3">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="md:w-1/3 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No Cover</span>
                        </div>
                    @endif
                    <div class="md:w-2/3 p-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $book->title }}</h1>
                        <p class="text-xl text-gray-600 mb-6">by {{ $book->author }}</p>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
                        </div>
                        <div class="mt-6 text-sm text-gray-500">
                            <p>Added: {{ $book->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

