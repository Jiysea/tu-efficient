<!-- Sidebar Navigation Menu -->
<nav :class="{ 'block': open, 'hidden': !open }"
    class="fixed inset-y-0 left-0 w-64 z-30 bg-white border-l border-gray-200 transform transition-transform duration-300 ease-in-out hidden sm:hidden">
    <div class="h-full flex flex-col">
        <button @click="open = ! open"
            class="fixed top-3 left-2 p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Logo -->
        {{-- <div class="flex items-center h-16 px-4 border-gray-200">
            <a href="{{ route('index') }}" class="shrink-0 flex flex-row items-center">
                <x-application-logo class="block h-9 w-auto" />
                <p class="flex font-bold text-lg text-red-500 ml-3">Chat<span class="text-red-700">Dug</span></p>
            </a>
        </div> --}}

        <!-- Navigation Links -->
        <div class="flex-1 px-4 py-6 space-y-1">
            <h1 class="mt-6 mb-2 pb-2 text-md text-gray-500 border-b border-gray-200">Navigation</h1>
            <x-responsive-nav-link :href="route('index')" :active="request()->routeIs('index')">
                {{ __('Chats') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users')" :active="request()->routeIs('users')">
                {{ __('Users') }}
            </x-responsive-nav-link>
        </div>

        <!-- Settings Options -->
        <div class="px-4 py-6">
            <div class="pt-4 border-t border-gray-200 font-medium text-base text-gray-800">{{ Auth::user()->name }}
            </div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Edit Profile') }}
                </x-responsive-nav-link>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
