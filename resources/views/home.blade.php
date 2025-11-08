<x-guest-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Our Book Collection</h1>

        @if($books->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($books as $book)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        {{-- Book Cover --}}
                        @if($book->cover_url)
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-64 object-cover">
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Cover</span>
                            </div>
                        @endif

                        {{-- Book Info --}}
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $book->title }}</h3>
                            <p class="text-gray-600 mb-3">by {{ $book->author }}</p>
                            <p class="text-gray-700 text-sm mb-4 line-clamp-3">
                                {{ Str::limit($book->description, 100) }}
                            </p>
                            <a href="{{ route('book.show', $book->id) }}"
                               class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors duration-200">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No books available yet.</p>
            </div>
        @endif
    </div>
</x-guest-layout>
