<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stats -->
            <div class="flex gap-4 mb-6 overflow-x-auto pb-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 min-w-[180px]">
                    <h3 class="text-base font-semibold text-gray-700 mb-1">Total Buku</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalBooks }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 min-w-[180px]">
                    <h3 class="text-base font-semibold text-gray-700 mb-1">Total Kategori</h3>
                    <p class="text-2xl font-bold text-indigo-600">{{ $totalCategories }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 min-w-[180px]">
                    <h3 class="text-base font-semibold text-gray-700 mb-1">Total Kegiatan</h3>
                    <p class="text-2xl font-bold text-emerald-600">{{ $totalActivities }}</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4">
                    <h3 class="text-base font-semibold text-gray-700 mb-3">Menu Cepat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2.5">
                        <a href="{{ route('admin.books.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 transition-colors text-center w-full">
                            Tambah Buku
                        </a>
                        <a href="{{ route('admin.books.index') }}" class="bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700 transition-colors text-center w-full">
                            Kelola Buku
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="bg-indigo-600 text-white px-3 py-2 rounded text-sm hover:bg-indigo-700 transition-colors text-center w-full">
                            Kelola Kategori Buku
                        </a>
                        <a href="{{ route('admin.activities.index') }}" class="bg-emerald-600 text-white px-3 py-2 rounded text-sm hover:bg-emerald-700 transition-colors text-center w-full">
                            Kelola Kegiatan
                        </a>
                        <a href="{{ route('admin.about.edit') }}" class="bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 transition-colors text-center w-full">
                            Edit Halaman Tentang
                        </a>
                        <form action="{{ route('admin.import.google-books') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-purple-600 text-white px-3 py-2 rounded text-sm hover:bg-purple-700 transition-colors w-full" onclick="return confirm('Impor 10 buku edukatif berbahasa Indonesia?');">
                                Impor Buku Edukatif (ID)
                            </button>
                        </form>
                        <form action="{{ route('admin.import.google-novels') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-pink-600 text-white px-3 py-2 rounded text-sm hover:bg-pink-700 transition-colors w-full" onclick="return confirm('Impor 10 buku novel Indonesia & Inggris?');">
                                Impor Novel (ID & EN)
                            </button>
                        </form>
                        <form action="{{ route('admin.import.google-comics') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-yellow-500 text-white px-3 py-2 rounded text-sm hover:bg-yellow-600 transition-colors w-full" onclick="return confirm('Impor 10 buku komik anak-anak Indonesia?');">
                                Impor Komik Anak (ID)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recent Books -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <h3 class="text-base font-semibold text-gray-700 mb-3">Recent Books</h3>
                    @if($recentBooks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Author</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Kategori</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentBooks as $book)
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-900">{{ $book->title }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-500 hidden sm:table-cell">{{ $book->author }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-500 hidden md:table-cell">{{ $book->category->name ?? '-' }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-500">{{ $book->created_at->format('M d, Y') }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap font-medium">
                                                <a href="{{ route('admin.books.edit', $book->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No books yet.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-700">Kegiatan Terbaru</h3>
                        <a href="{{ route('admin.activities.index') }}" class="text-xs sm:text-sm text-indigo-600 hover:text-indigo-800">Lihat semua</a>
                    </div>
                    @if($recentActivities->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($recentActivities as $activity)
                                <div class="border border-gray-100 rounded-lg overflow-hidden shadow-sm">
                                    <img src="{{ Storage::disk('public')->exists($activity->image_path) ? Storage::url($activity->image_path) : 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="{{ $activity->title }}" class="h-32 w-full object-cover">
                                    <div class="p-3">
                                        <h4 class="text-sm font-semibold text-gray-800 mb-1 line-clamp-1">{{ $activity->title }}</h4>
                                        <p class="text-xs text-gray-600 line-clamp-2">{{ $activity->description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Belum ada kegiatan yang tercatat.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

