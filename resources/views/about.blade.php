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
            <h1 class="text-3xl font-bold text-gray-900 mb-8">About</h1>
            
            @if($about)
                <div class="bg-white rounded-lg shadow-md overflow-hidden p-8">
                    <div class="flex flex-col md:flex-row items-start gap-8">
                        @if($about->profile_image)
                            <img src="{{ $about->profile_image }}" alt="{{ $about->name }}" class="w-48 h-48 rounded-full object-cover mx-auto md:mx-0">
                        @else
                            <div class="w-48 h-48 rounded-full bg-gray-200 flex items-center justify-center mx-auto md:mx-0">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $about->name }}</h2>
                            <div class="prose max-w-none mb-6">
                                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $about->bio }}</p>
                            </div>
                            @if($about->contact_info)
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Contact Information</h3>
                                    <p class="text-gray-700 whitespace-pre-line">{{ $about->contact_info }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <p class="text-gray-500">About information is not available yet.</p>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>

