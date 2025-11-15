<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $activity->title }}
            </h2>
            <a href="{{ route('admin.activities.edit', $activity) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Edit Kegiatan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <img src="{{ Storage::url($activity->image_path) }}" alt="{{ $activity->title }}" class="w-full h-72 object-cover">
                <div class="p-6">
                    <p class="text-sm text-gray-500 mb-2">Diperbarui: {{ $activity->updated_at->format('d M Y H:i') }}</p>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ $activity->title }}</h3>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $activity->description }}</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.activities.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali ke daftar kegiatan</a>
            </div>
        </div>
    </div>
</x-app-layout>

