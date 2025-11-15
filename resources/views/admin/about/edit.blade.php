<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit About Information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.about.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $about->name ?? '')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <x-input-label for="bio" :value="__('Bio')" />
                            <textarea id="bio" name="bio" rows="6" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('bio', $about->bio ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                        </div>

                        <!-- Profile Image URL -->
                        <div class="mb-4">
                            <x-input-label for="profile_image" :value="__('Profile Image URL (optional)')" />
                            <x-text-input id="profile_image" class="block mt-1 w-full" type="url" name="profile_image" :value="old('profile_image', $about->profile_image ?? '')" />
                            <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
                        </div>

                        <!-- Owner Name -->
                        <div class="mb-4">
                            <x-input-label for="owner_name" :value="__('Nama Pemilik / Pengelola')" />
                            <x-text-input id="owner_name" class="block mt-1 w-full" type="text" name="owner_name" :value="old('owner_name', $about->owner_name ?? '')" required />
                            <x-input-error :messages="$errors->get('owner_name')" class="mt-2" />
                        </div>

                        <!-- History -->
                        <div class="mb-4">
                            <x-input-label for="history" :value="__('Sejarah Perpustakaan')" />
                            <textarea id="history" name="history" rows="6" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('history', $about->history ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('history')" class="mt-2" />
                        </div>

                        <!-- Contact Info -->
                        <div class="mb-4">
                            <x-input-label for="contact_info" :value="__('Contact Information (optional)')" />
                            <textarea id="contact_info" name="contact_info" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('contact_info', $about->contact_info ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('contact_info')" class="mt-2" />
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="phone_number" :value="__('Nomor Telepon')" />
                                <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $about->phone_number ?? '')" required />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="bank_account" :value="__('Nomor Rekening')" />
                                <x-text-input id="bank_account" class="block mt-1 w-full" type="text" name="bank_account" :value="old('bank_account', $about->bank_account ?? '')" required />
                                <x-input-error :messages="$errors->get('bank_account')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Update About Information') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

