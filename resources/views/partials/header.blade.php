<header class="bg-white shadow relative z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold">Vineshop</a>

        <nav class="flex space-x-4 items-center">
            <a href="{{ route('home') }}">Головна</a>

            {{-- Категорії --}}
            <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                <button class="hover:underline focus:outline-none">Категорії</button>
                <div x-show="open"
                     x-transition
                     class="absolute left-0 mt-2 w-48 bg-white border rounded shadow-lg z-50"
                     @mouseenter="open = true" @mouseleave="open = false">

                    @foreach(\App\Models\Category::all() as $category)
                        <a href="{{ route('catalog', ['category' => $category->slug]) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ $category->name }}
                        </a>
                    @endforeach

                </div>
            </div>

            <a href="{{ route('catalog') }}">Каталог</a>
            @php
                $count = array_sum(array_column(session('cart', []), 'quantity'));
            @endphp

            <a href="{{ route('cart') }}">
                Кошик
                @if($count)
                    ({{ $count }})
                @endif
            </a>

            @auth
                <a href="{{ route('dashboard') }}" class="text-sm font-medium">Мій кабінет</a>
            @else
                {{-- Обліковий запис --}}
                <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="text-sm font-medium focus:outline-none">
                        Обліковий запис
                    </button>
                    <div x-show="open"
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50"
                         @mouseenter="open = true" @mouseleave="open = false">

                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Увійти</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Реєстрація</a>
                    </div>
                </div>
        @endauth
    </div>
</header>
