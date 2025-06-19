@php
    $count = array_sum(array_column(session('cart', []), 'quantity'));
@endphp

<header class="bg-white shadow relative z-50" x-data="{ mobileOpen: false }" @close-mobile.window="mobileOpen = false">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold">Vineshop</a>

        {{-- Гамбургер --}}
        <button @click="mobileOpen = !mobileOpen" class="md:hidden focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Десктоп меню --}}
        <nav class="hidden md:flex space-x-4 items-center">
            <x-header-links :categories="$categories" :count="$count" />
        </nav>
    </div>

    {{-- Мобільне меню --}}
    <nav x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="md:hidden bg-white border-t shadow-md px-4 pb-4 space-y-2"
    >
        <x-header-links :categories="$categories" :count="$count" mobile />
    </nav>
</header>

{{-- 🔁 Скріпт для перемикання мови --}}
<script>
    function setLocale(lang) {
        fetch('/api/set-locale', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ locale: lang })
        }).then(() => {
            location.reload();
        });
    }
</script>
