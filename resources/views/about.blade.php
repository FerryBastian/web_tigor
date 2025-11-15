<x-guest-layout>
    <section class="bg-gradient-to-br from-indigo-50 via-white to-blue-50 border-b border-indigo-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid md:grid-cols-[220px,1fr] gap-10 items-center">
                <div class="flex md:block justify-center">
                    @if($about && $about->profile_image)
                        <img src="{{ $about->profile_image }}" alt="{{ $about->owner_name ?? $about->name }}" loading="lazy" class="w-48 h-48 rounded-full object-cover shadow-lg border-4 border-white">
                    @else
                        <div class="w-48 h-48 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 text-4xl font-semibold shadow-lg border-4 border-white">
                            📚
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-sm uppercase tracking-wide text-indigo-600 font-semibold">Tentang Perpustakaan</p>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3">
                        {{ $about->name ?? 'Perpustakaan Kami' }}
                    </h1>
                    <p class="mt-4 text-gray-600 text-lg leading-relaxed">
                        {{ $about->bio ?? 'Informasi perpustakaan akan segera diperbarui.' }}
                    </p>
                    @if($about && $about->owner_name)
                        <p class="mt-6 text-sm text-gray-500">
                            Dikelola oleh <span class="font-semibold text-gray-800">{{ $about->owner_name }}</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($about)
            <div class="grid lg:grid-cols-[2fr,1fr] gap-12">
                <article class="bg-white rounded-3xl shadow-md border border-indigo-50 p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Sejarah Perpustakaan</h2>
                    <div class="space-y-4 text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $about->history ?? 'Sejarah perpustakaan belum tersedia.' }}
                    </div>
                </article>

                <aside class="space-y-6">
                    <div class="bg-white rounded-3xl shadow-md border border-indigo-50 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                        <dl class="space-y-3 text-gray-700">
                            @if($about->phone_number)
                                <div>
                                    <dt class="text-sm text-gray-500 uppercase">Nomor Telepon</dt>
                                    <dd class="text-base font-medium">{{ $about->phone_number }}</dd>
                                </div>
                            @endif
                            @if($about->contact_info)
                                <div>
                                    <dt class="text-sm text-gray-500 uppercase">Alamat & Kontak</dt>
                                    <dd class="text-base font-medium whitespace-pre-line">{{ $about->contact_info }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="bg-indigo-600 rounded-3xl shadow-md text-white p-6">
                        <h3 class="text-lg font-semibold mb-3">Dukungan Perpustakaan</h3>
                        <p class="text-indigo-100">Dukung pengembangan koleksi dan kegiatan perpustakaan melalui donasi.</p>
                        @if($about->bank_account)
                            <div class="mt-4 p-4 bg-indigo-700/40 rounded-2xl border border-indigo-500">
                                <p class="text-sm text-indigo-200 uppercase">Nomor Rekening</p>
                                <p class="text-xl font-semibold tracking-wide">{{ $about->bank_account }}</p>
                            </div>
                        @else
                            <p class="mt-4 text-indigo-200">Informasi rekening belum tersedia.</p>
                        @endif
                    </div>
                </aside>
            </div>
        @else
            <div class="bg-white rounded-3xl shadow-sm p-12 text-center">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Informasi belum tersedia</h2>
                <p class="text-gray-600">Admin dapat mengisi informasi melalui halaman pengaturan tentang.</p>
            </div>
        @endif
    </section>
</x-guest-layout>
