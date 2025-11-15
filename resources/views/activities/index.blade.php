<x-guest-layout>
    <section class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <div>
                <p class="text-sm uppercase tracking-wide text-indigo-600 font-semibold">Galeri Kegiatan</p>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2">Kegiatan Perpustakaan</h1>
                <p class="text-gray-600 mt-3 max-w-2xl">
                    Ikuti berbagai kegiatan terbaru dari perpustakaan kami. Setiap momen kami dokumentasikan untuk Anda.
                </p>
            </div>
        </div>

        @if($activities->count() > 0)
            <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-3">
                @foreach($activities as $activity)
                    <article
                        class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300 cursor-pointer"
                        onclick="openActivityModal(@json(Storage::url($activity->image_path)), @json($activity->title), @json($activity->created_at->format('d M Y')), @json($activity->description))"
                    >
                        <div class="relative pt-[65%]">
                            <img
                                src="{{ Storage::url($activity->image_path) }}"
                                alt="{{ $activity->title }}"
                                loading="lazy"
                                class="absolute inset-0 h-full w-full object-cover"
                            >
                        </div>
                        <div class="p-6 flex flex-col h-full">
                            <p class="text-xs uppercase tracking-wide text-indigo-600 font-semibold">{{ $activity->created_at->format('d M Y') }}</p>
                            <h2 class="text-xl font-semibold text-gray-900 mt-2 line-clamp-2">{{ $activity->title }}</h2>
                            <p class="text-gray-600 mt-3 line-clamp-3">{{ $activity->description }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $activities->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Belum ada kegiatan</h2>
                <p class="text-gray-600">Kami akan segera mengunggah kegiatan terbaru. Nantikan update selanjutnya!</p>
            </div>
        @endif
    </section>

    <!-- Modal Detail Kegiatan -->
    <div
        id="activity-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
        onclick="if(event.target.id === 'activity-modal'){ closeActivityModal(); }"
    >
        <div class="bg-white max-w-lg w-full rounded-2xl shadow-xl overflow-hidden">
            <div class="relative pt-[60%] bg-gray-100">
                <img id="activity-modal-image" src="" alt="" class="absolute inset-0 h-full w-full object-cover">
            </div>
            <div class="p-6">
                <p id="activity-modal-date" class="text-xs uppercase tracking-wide text-indigo-600 font-semibold"></p>
                <h2 id="activity-modal-title" class="text-xl font-semibold text-gray-900 mt-2"></h2>
                <p id="activity-modal-description" class="text-gray-700 mt-4 whitespace-pre-line"></p>
                <div class="mt-6 text-right">
                    <button
                        type="button"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition"
                        onclick="closeActivityModal()"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openActivityModal(imageUrl, title, date, description) {
            const modal = document.getElementById('activity-modal');
            document.getElementById('activity-modal-image').src = imageUrl;
            document.getElementById('activity-modal-title').textContent = title;
            document.getElementById('activity-modal-date').textContent = date;
            document.getElementById('activity-modal-description').textContent = description;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeActivityModal() {
            const modal = document.getElementById('activity-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-guest-layout>

