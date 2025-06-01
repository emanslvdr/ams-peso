<nav x-data="{ open: false }" class="gradient-background shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-4">
                <!-- Logo / App Name -->
                <span class="logo text-xl">AMS</span>
                <!-- Main Navigation Links (Desktop) -->
                <div class="hidden sm:flex gap-6 ml-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Home
                    </x-nav-link>
                    <x-nav-link :href="route('user.application.index')" :active="request()->routeIs('user.application.*')">
                        Application
                    </x-nav-link>
                </div>
            </div>
           <!-- Profile Dropdown (Desktop) -->
<div class="hidden sm:flex items-center gap-4">
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" @keydown.escape="open = false"
            class="flex items-center gap-3 px-3 py-1.5 rounded-full bg-white border border-gray-200 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-blue-200 transition"
            aria-label="User menu" type="button">
            {{-- Avatar or Initials --}}
            @php
                $user = Auth::user();
                $initials = collect(explode(' ', $user->name))->map(fn($part) => strtoupper(substr($part,0,1)))->join('');
            @endphp
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                     alt="Avatar"
                     class="w-9 h-9 rounded-full object-cover border-2  shadow-sm" />
            @else
                <span class="w-9 h-9 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold text-lg border-2 border-blue-200 shadow-sm">
                    {{ $initials }}
                </span>
            @endif
            <span class="font-medium text-gray-900">{{ $user->name }}</span>
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <!-- Dropdown -->
        <div x-show="open" @click.away="open = false" x-transition
             class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-50">
                <div class="flex items-center gap-3">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                             alt="Avatar"
                             class="w-9 h-9 rounded-full object-cover border border-blue-100" />
                    @else
                        <span class="w-9 h-9 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold text-lg border border-blue-100">
                            {{ $initials }}
                        </span>
                    @endif
                    <div>
                        <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="block px-4 py-2 text-gray-700 hover:bg-gray-50 transition">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left block px-4 py-2 text-gray-700 hover:bg-gray-50 transition">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>

            <!-- Hamburger Menu (Mobile) -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Responsive Mobile Nav -->
    <div :class="{'block': open, 'hidden': !open}" class="sm:hidden transition-all duration-200">
        <div class="pt-3 pb-2 px-4 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('user.application.index')" :active="request()->routeIs('user.application.*')">
                Application
            </x-responsive-nav-link>
        </div>
        <div class="border-t border-gray-200 pt-4 pb-2 px-4">
            <div class="flex items-center gap-3 mb-2">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border">
                @else
                    <span class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold">{{ $initials }}</span>
                @endif
                <div>
                    <div class="font-medium text-base text-gray-900">{{ $user->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
                </div>
            </div>
            <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
