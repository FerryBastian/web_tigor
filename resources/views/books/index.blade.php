<x-guest-layout>
    <section class="bg-gradient-to-r from-blue-50 via-white to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid gap-8 lg:grid-cols-2 lg:gap-12 items-center">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-blue-600 font-semibold mb-4">Katalog Buku</p>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">
                        Jelajahi Koleksi Buku Perpustakaan Kami
                    </h1>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        Temukan beragam buku yang tersedia di perpustakaan, lengkap dengan kategori dan rekomendasi
                        dari Google Books untuk membantu Anda belajar lebih banyak.
                    </p>
                </div>
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-200 rounded-3xl blur-3xl opacity-40"></div>
                    <!-- <img
                        src="https://images.unsplash.com/photo-1529159418453-b52a7b2a7e89?auto=format&fit=crop&w=900&q=80"
                        alt="Rak buku perpustakaan"
                        class="relative rounded-3xl shadow-lg ring-4 ring-white/70"
                    > -->

                    <div class="relative mt-6 bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-blue-100 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-semibold text-gray-800">Rak buku perpustakaan</h3>
                            @if(isset($shelfBooks) && $shelfBooks->count() > 0)
                                <span class="text-[11px] text-gray-500">{{ $shelfBooks->count() }} judul</span>
                            @endif
                        </div>

                        @if(isset($shelfBooks) && $shelfBooks->count() > 0)
                            <ul class="space-y-1 max-h-48 overflow-y-auto text-sm text-gray-700">
                                @foreach($shelfBooks as $item)
                                    <li class="flex items-start gap-2">
                                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                                        <span class="leading-snug">{{ $item->title }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-xs text-gray-500">Belum ada judul yang ditambahkan. Tambahkan dari menu admin &gt; Rak Buku.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Filter Berdasarkan Kategori</h2>
                <p class="text-gray-600 mt-2">Pilih kategori untuk melihat buku sesuai minat Anda.</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('books.index') }}"
               class="px-4 py-2 rounded-full border {{ !$categorySlug ? 'border-blue-500 text-white bg-blue-500' : 'border-gray-200 text-gray-700 hover:border-blue-400 hover:text-blue-600' }}">
                Semua
            </a>
            @foreach($categories as $category)
                <a href="{{ route('books.index', ['category' => $category->slug]) }}"
                   class="px-4 py-2 rounded-full border {{ $categorySlug === $category->slug ? 'border-blue-500 text-white bg-blue-500' : 'border-gray-200 text-gray-700 hover:border-blue-400 hover:text-blue-600' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Buku Koleksi Perpustakaan</h2>
                <p class="text-gray-600 mt-2">
                    Menampilkan {{ $books->total() }} buku{{ $categorySlug ? ' dalam kategori ' . $categories->firstWhere('slug', $categorySlug)?->name : '' }}.
                </p>
            </div>
        </div>

        @if($books->count() > 0)
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6">
                @foreach($books as $book)
                    <article class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden p-4 sm:p-5 flex flex-col h-full">
                        <div class="flex gap-4">
                            <img
                                src="{{ $book->cover_url ?? 'https://via.placeholder.com/400x600?text=NO+COVER' }}"
                                alt="{{ $book->title }}"
                                loading="lazy"
                                class="w-20 h-28 sm:w-24 sm:h-32 object-cover rounded-lg flex-shrink-0 bg-gray-100"
                            >
                            <div class="flex flex-col flex-1 min-w-0">
                                <p class="text-[11px] sm:text-xs uppercase tracking-wide {{ $book->category ? 'text-blue-600' : 'text-gray-500' }} font-semibold">
                                    {{ $book->category->name ?? 'Tidak Berkategori' }}
                                </p>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mt-1 line-clamp-2">{{ $book->title }}</h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">oleh {{ $book->author }}</p>
                            </div>
                        </div>
                        <p class="hidden md:block text-sm text-gray-600 mt-3 line-clamp-3 flex-1">{{ Str::limit($book->description, 150) }}</p>
                        <div class="mt-4 flex flex-wrap gap-2.5">
                            <a href="{{ route('book.show', $book->id) }}"
                               class="inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                                Lihat Detail
                            </a>
                            @if($book->preview_link)
                                <a href="{{ $book->preview_link }}" target="_blank"
                                   class="inline-flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                    Baca Online
                                </a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $books->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak ada buku ditemukan.</h3>
                <p class="text-gray-600">Coba pilih kategori lainnya atau kembali lagi nanti.</p>
            </div>
        @endif
    </section>

    <section class="bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-2xl font-semibold">Rekomendasi Buku Edukatif</h2>
                    <p class="text-slate-300 mt-2">
                        Rekomendasi otomatis dari Google Books dengan fokus buku pelajaran, sejarah, dan pengetahuan umum.
                    </p>
                </div>
            </div>

            @if($recommendedBooks->count() > 0)
                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6">
                    @foreach($recommendedBooks as $book)
                        <article class="bg-white/10 border border-white/10 rounded-2xl p-6 backdrop-blur">
                            <div class="flex gap-4">
                                <img
                                    src="{{ $book['thumbnail'] ?? 'https://via.placeholder.com/128x180?text=NO+COVER' }}"
                                    alt="{{ $book['title'] }}"
                                    loading="lazy"
                                    class="w-24 h-36 object-cover rounded-lg flex-shrink-0"
                                >
                                <div>
                                    <h3 class="text-lg font-semibold text-white line-clamp-2">{{ $book['title'] }}</h3>
                                    <p class="text-sm text-slate-300 mt-1">{{ $book['authors'] }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-200 mt-4 line-clamp-4">{{ $book['description'] }}</p>
                            <div class="mt-6 flex flex-wrap gap-3">
                                <a href="{{ route('book.show', 'google-' . $book['google_id']) }}"
                                   class="inline-flex items-center px-4 py-2 text-sm font-medium bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                    Detail Buku
                                </a>
                                @if(!empty($book['preview_link']))
                                    <a href="{{ $book['preview_link'] }}" target="_blank"
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium bg-white text-slate-900 rounded-lg hover:bg-slate-100 transition">
                                        Baca Pratinjau
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="bg-white/10 border border-white/10 rounded-2xl p-10 text-center">
                    <h3 class="text-xl font-semibold text-white mb-2">Tidak ada rekomendasi saat ini</h3>
                    <p class="text-slate-300">Silakan coba lagi nanti untuk melihat rekomendasi terbaru.</p>
                </div>
            @endif
        </div>
    </section>
</x-guest-layout>

