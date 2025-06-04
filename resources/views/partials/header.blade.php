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
            <a href="{{ route('cart') }}">Кошик</a>
        </nav>
    </div>
</header>
