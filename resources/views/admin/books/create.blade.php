<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Book') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.books.store') }}">
                        @csrf

                        <!-- Category -->
                        <div class="mb-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Pilih kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Author -->
                        <div class="mb-4">
                            <x-input-label for="author" :value="__('Author')" />
                            <x-text-input id="author" class="block mt-1 w-full" type="text" name="author"
                                :value="old('author')" required />
                            <x-input-error :messages="$errors->get('author')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="5"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Cover URL -->
                        <div class="mb-4">
                            <x-input-label for="cover_url" :value="__('Cover URL (optional)')" />
                            <x-text-input id="cover_url" class="block mt-1 w-full" type="url" name="cover_url"
                                :value="old('cover_url')" />
                            <x-input-error :messages="$errors->get('cover_url')" class="mt-2" />
                        </div>


                        <!-- Preview Link -->
                        <div class="mb-4">
                            <x-input-label for="preview_link" :value="__('Preview Link (optional)')" />
                            <x-text-input id="preview_link" class="block mt-1 w-full" type="url" name="preview_link"
                                :value="old('preview_link')" />
                            <x-input-error :messages="$errors->get('preview_link')" class="mt-2" />
                        </div>

                        
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('admin.books.index') }}"
                                class="text-gray-600 hover:text-gray-800">Cancel</a>
                            <x-primary-button>
                                {{ __('Create Book') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>