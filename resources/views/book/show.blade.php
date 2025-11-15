<x-guest-layout>
    <section class="max-w-7xl mx-auto py-8 sm:py-10 lg:py-12 px-4 sm:px-6 lg:px-10">
        <a href="{{ url()->previous() === url()->current() ? route('books.index') : url()->previous() }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-indigo-600 hover:text-indigo-800 mb-4">
            ← Kembali
        </a>

        <div class="bg-white rounded-3xl shadow-lg overflow-hidden border border-indigo-50">
            <div class="grid md:grid-cols-[320px,1fr] gap-6 lg:gap-10">
                <div class="relative bg-slate-900 min-h-[320px]">
                    @if($coverApi)
                        <img src="{{ $coverApi }}" alt="{{ $book->title }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
                    @elseif($book->cover_url)
                        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-slate-400 text-lg">
                            Sampul tidak tersedia
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 flex items-center gap-2 text-xs sm:text-sm text-slate-200">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-indigo-600/90 text-white text-xs sm:text-sm">
                            {{ optional($book->category)->name ?? 'Informasi Buku' }}
                        </span>
                        <span class="text-slate-300">{{ $book->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="p-6 sm:p-9 lg:p-10 flex flex-col">
                    <p class="text-xs sm:text-sm uppercase tracking-wide text-indigo-600 font-semibold">{{ $book->author }}</p>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mt-3">{{ $book->title }}</h1>
                    <p class="mt-4 sm:mt-5 text-gray-600 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                        {{ $book->description }}
                    </p>
                    @if(!empty($additionalDetails))
                        <dl class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-5">
                            @foreach($additionalDetails as $label => $value)
                                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl px-3 py-2.5">
                                    <dt class="text-[11px] sm:text-xs uppercase tracking-wide text-indigo-500 font-semibold">{{ $label }}</dt>
                                    <dd class="text-xs sm:text-sm text-gray-700 mt-1">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                    <div class="mt-8 flex flex-wrap gap-4">
                        @if($previewLink)
                            <a href="{{ $previewLink }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                                Baca Pratinjau
                            </a>
                        @endif
                        @if(optional($book->category)->slug)
                            <a href="{{ route('books.index', ['category' => optional($book->category)->slug]) }}" class="inline-flex items-center px-5 py-2.5 border border-indigo-200 text-indigo-700 text-sm font-semibold rounded-lg hover:border-indigo-400 hover:text-indigo-800 transition">
                                Lihat Buku Sejenis
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($embedLink)
            @php
                $isGoogleBooks = \Illuminate\Support\Str::contains($embedLink, 'books.google.');
            @endphp

            <div class="mt-10 lg:mt-12 bg-white rounded-3xl shadow-lg border border-indigo-50 overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-indigo-50">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Pratinjau Buku</h2>
                    @if($isGoogleBooks)
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Pratinjau dari Google Books tidak dapat ditampilkan langsung karena kebijakan keamanan mereka.
                            Silakan gunakan tombol <span class="font-semibold">"Baca Pratinjau"</span> di atas untuk membuka pratinjau di tab baru.
                        </p>
                    @else
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Pratinjau ditampilkan langsung di halaman ini jika diizinkan oleh sumber.</p>
                    @endif
                </div>

                @unless($isGoogleBooks)
                    <div class="p-6 sm:p-8">
                        <iframe
                            src="{{ $embedLink }}"
                            class="w-full h-[400px] sm:h-[560px] rounded-xl border border-indigo-100"
                            allowfullscreen
                        ></iframe>
                    </div>
                @endunless
            </div>
        @endif
    </section>
</x-guest-layout>
