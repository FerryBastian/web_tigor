<nav x-data="{ open: false }" class="bg-white shadow-md sticky top-0 z-50 border-b border-gray-100">
    <!-- Primary Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Section (Logo + Links) -->
            <div class="flex items-center space-x-10">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('home') }}"
                        class="flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-blue-600" />
                        <span class="font-bold text-lg text-blue-700">Taman Bacaan Masyarakat</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden sm:flex sm:space-x-8">
                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.books.index')" :active="request()->routeIs('admin.books.*')">
                            Kelola Buku
                        </x-nav-link>
                        <x-nav-link :href="route('admin.shelf-books.index')" :active="request()->routeIs('admin.shelf-books.*')">
                            Rak Buku
                        </x-nav-link>
                        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                            Kelola Kategori
                        </x-nav-link>
                        <x-nav-link :href="route('admin.activities.index')" :active="request()->routeIs('admin.activities.*')">
                            Kelola Kegiatan
                        </x-nav-link>
                        <x-nav-link :href="route('admin.about.edit')" :active="request()->routeIs('admin.about.*')">
                            Edit Halaman Tentang
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>
                        <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.index')">
                            Buku
                        </x-nav-link>
                        <x-nav-link :href="route('activities.index')" :active="request()->routeIs('activities.index')">
                            Kegiatan
                        </x-nav-link>
                        <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                            Tentang
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right Section (User Dropdown) -->
            <div class="hidden sm:flex items-center space-x-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-semibold rounded-lg text-gray-700 bg-gray-50 hover:bg-gray-100 hover:text-blue-700 focus:outline-none transition">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5.25 7.5L10 12.25L14.75 7.5" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-600 hover:text-blue-700 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{'hidden': open, 'inline-flex': !open }"
                              class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div :class="{'block': open, 'hidden': !open}" class="sm:hidden bg-white border-t border-gray-200">
        <div class="py-4 px-4 space-y-2">
            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.books.index')" :active="request()->routeIs('admin.books.*')">
                    Kelola Buku
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.shelf-books.index')" :active="request()->routeIs('admin.shelf-books.*')">
                    Rak Buku
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                    Kelola Kategori
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.activities.index')" :active="request()->routeIs('admin.activities.*')">
                    Kelola Kegiatan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.about.edit')" :active="request()->routeIs('admin.about.*')">
                    Edit Halaman Tentang
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('books.index')" :active="request()->routeIs('books.index')">
                    Buku
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('activities.index')" :active="request()->routeIs('activities.index')">
                    Kegiatan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">
                    Tentang
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Mobile Profile -->
        <div class="border-t border-gray-200 py-3 px-4 space-y-2">
            <div>
                <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
