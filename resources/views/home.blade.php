<x-guest-layout>
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-blue-50">
        <div class="max-w-7xl mx-auto pt-12 pb-14 px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <div>
                    <p class="text-sm uppercase tracking-wide text-indigo-600 font-semibold">Perpustakaan Digital</p>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mt-3 leading-tight">
                        Temukan Pengetahuan Baru Setiap Hari
                    </h1>
                    <p class="mt-5 text-base md:text-lg text-gray-600">
                        Jelajahi rekomendasi buku edukatif berbahasa Indonesia, akses koleksi perpustakaan kami, dan ikuti berbagai kegiatan terbaru.
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                            Lihat Semua Buku
                        </a>
                        <a href="{{ route('activities.index') }}" class="inline-flex items-center px-6 py-3 border border-indigo-200 text-indigo-700 text-sm font-semibold rounded-lg hover:border-indigo-400 hover:text-indigo-800 transition">
                            Kegiatan Perpustakaan
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-200 rounded-full blur-3xl opacity-40"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-200 rounded-full blur-3xl opacity-40"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm shadow-xl rounded-3xl p-8 border border-indigo-100">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Kategori Populer</h2>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($categories->take(6) as $category)
                                <a href="{{ route('books.index', ['category' => $category->slug]) }}" class="flex items-center gap-3 p-3 rounded-2xl border border-indigo-50 hover:border-indigo-200 hover:bg-indigo-50 transition">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 font-semibold">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </span>
                                    <span class="text-gray-700 font-medium">{{ $category->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-sm uppercase tracking-wide text-indigo-600 font-semibold">Suasana Membaca</p>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">Mari Menikmati Waktu Bersama Buku</h2>
            </div>
        </div>

        @if($carouselActivities->count() > 0)
            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm overflow-hidden border border-indigo-50">
                <div class="relative pt-[50%] sm:pt-[45%] bg-gray-100">
                    <img
                        id="reading-carousel-image"
                        src="{{ Storage::url($carouselActivities->first()->image_path) }}"
                        alt="{{ $carouselActivities->first()->title }}"
                        loading="lazy"
                        class="absolute inset-0 h-full w-full object-cover"
                    >
                </div>
                <div class="p-4 sm:p-6">
                    <p id="reading-carousel-caption" class="text-sm sm:text-base text-gray-700">
                        {{ $carouselActivities->first()->title }}
                    </p>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const images = [
                        @foreach($carouselActivities as $activity)
                            {
                                url: @json(Storage::url($activity->image_path)),
                                caption: @json($activity->title),
                            },
                        @endforeach
                    ];

                    const imgEl = document.getElementById('reading-carousel-image');
                    const captionEl = document.getElementById('reading-carousel-caption');

                    if (!images.length) return;

                    let current = 0;

                    function updateCarousel() {
                        const item = images[current];
                        imgEl.src = item.url;
                        captionEl.textContent = item.caption;
                        current = (current + 1) % images.length;
                    }

                    // Mulai dari gambar pertama
                    updateCarousel();

                    // Ganti gambar setiap 4 detik
                    setInterval(updateCarousel, 4000);
                });
            </script>
        @else
            <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-indigo-50">
                <div class="relative pt-[50%] sm:pt-[45%] bg-gray-100 flex items-center justify-center">
                    <span class="text-gray-400 text-sm">Belum ada kegiatan untuk ditampilkan.</span>
                </div>
            </div>
        @endif
    </section>

    <section class="bg-slate-900 py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 md:gap-6 mb-9">
                <div>
                    <p class="text-sm uppercase tracking-wide text-emerald-400 font-semibold">Koleksi Lokal</p>
                    <h2 class="text-2xl md:text-3xl font-bold mt-2">Buku Perpustakaan Kami</h2>
                    <p class="text-slate-300 mt-2 max-w-2xl text-sm md:text-base">
                        Filter cepat berdasarkan kategori favorit Anda. Klik tombol kategori untuk menyesuaikan daftar.
                    </p>
                </div>
                @if($categories->count() > 0)
                    <div class="flex flex-wrap gap-2.5">
                        <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-full border border-slate-700 text-xs sm:text-sm {{ $selectedCategory ? 'text-slate-300 hover:border-emerald-400 hover:text-emerald-300' : 'bg-emerald-500 text-slate-900 border-emerald-500' }} transition">
                            Semua
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('home', ['category' => $category->slug]) }}"
                                class="px-3 py-1.5 rounded-full border border-slate-700 text-xs sm:text-sm {{ $selectedCategory === $category->slug ? 'bg-emerald-500 text-slate-900 border-emerald-500' : 'text-slate-300 hover:border-emerald-400 hover:text-emerald-300' }} transition">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($localBooks->count() > 0)
                <div class="grid grid-cols-2 gap-4 sm:gap-6 md:gap-8 md:grid-cols-3">
                    @foreach($localBooks as $book)
                        <article class="bg-white/10 border border-white/10 rounded-2xl p-5 sm:p-6 backdrop-blur flex flex-col h-full">
                            <div class="flex gap-4">
                                <img
                                    src="{{ $book->cover_url ?? 'https://via.placeholder.com/400x600?text=No+Cover' }}"
                                    alt="{{ $book->title }}"
                                    loading="lazy"
                                    class="w-24 h-36 object-cover rounded-lg flex-shrink-0"
                                >
                                <div class="flex flex-col flex-1 min-w-0">
                                    <p class="text-[11px] sm:text-xs uppercase tracking-wide text-emerald-300 font-semibold">{{ $book->category->name ?? 'Tanpa Kategori' }}</p>
                                    <h3 class="text-base sm:text-lg md:text-xl font-semibold text-white mt-1 line-clamp-2">{{ $book->title }}</h3>
                                    <p class="text-xs sm:text-sm text-slate-300 mt-1">oleh {{ $book->author }}</p>
                                </div>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-200 mt-4 line-clamp-4 flex-1">{{ Str::limit($book->description, 140) }}</p>
                            <div class="mt-4 sm:mt-5 flex flex-wrap gap-2.5">
                                <a href="{{ route('book.show', $book->id) }}"
                                   class="inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium bg-emerald-500 text-slate-900 rounded-lg hover:bg-emerald-400 transition">
                                    Detail Buku
                                </a>
                                @if($book->preview_link)
                                    <a href="{{ $book->preview_link }}" target="_blank"
                                       class="inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium bg-white text-slate-900 rounded-lg hover:bg-slate-100 transition">
                                        Baca Online
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-6 text-right">
                    <a href="{{ $selectedCategory ? route('books.index', ['category' => $selectedCategory]) : route('books.index') }}" class="inline-flex items-center text-xs sm:text-sm font-semibold text-emerald-300 hover:text-emerald-200">
                        Lihat semua koleksi →
                    </a>
                </div>
            @else
                <div class="bg-slate-800 rounded-2xl p-8 sm:p-10 text-center border border-slate-700">
                    <h3 class="text-xl sm:text-2xl font-semibold text-white mb-3">Belum ada buku dalam kategori ini</h3>
                    <p class="text-slate-400 text-sm">Silakan pilih kategori lainnya untuk melihat koleksi berbeda.</p>
                </div>
            @endif
        </div>
    </section>

    <section class="max-w-7xl mx-auto py-13 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 md:gap-6 mb-9">
            <div>
                <p class="text-sm uppercase tracking-wide text-indigo-600 font-semibold">Kegiatan Terbaru</p>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mt-2">Momen Seru di Perpustakaan</h2>
                <p class="text-gray-600 mt-2 max-w-2xl text-sm md:text-base">
                    Lihat sekilas kegiatan terbaru kami. Temukan lebih banyak dokumentasi di halaman kegiatan.
                </p>
            </div>
            <a href="{{ route('activities.index') }}" class="inline-flex items-center px-3 py-2 border border-indigo-200 text-indigo-700 text-xs sm:text-sm font-semibold rounded-lg hover:border-indigo-400 hover:text-indigo-800 transition">
                Lihat Semua Kegiatan
            </a>
        </div>

        @if($latestActivities->count() > 0)
            <div class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-4">
                @foreach($latestActivities as $activity)
                    <article class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
                        <div class="relative pt-[64%]">
                            <img
                                src="{{ Storage::url($activity->image_path) }}"
                                alt="{{ $activity->title }}"
                                loading="lazy"
                                class="absolute inset-0 h-full w-full object-cover"
                            >
                        </div>
                        <div class="p-4 sm:p-6 flex flex-col flex-1">
                            <p class="text-[11px] sm:text-xs uppercase tracking-wide text-indigo-600 font-semibold">{{ $activity->created_at->format('d M Y') }}</p>
                            <h3 class="text-sm sm:text-base font-semibold text-gray-900 mt-2 line-clamp-2">{{ $activity->title }}</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mt-2 line-clamp-3">{{ $activity->description }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-8 sm:p-10 text-center">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3">Belum ada kegiatan terbaru</h3>
                <p class="text-gray-600 text-sm">Kegiatan terbaru akan segera ditampilkan di sini.</p>
            </div>
        @endif
    </section>
</x-guest-layout>
