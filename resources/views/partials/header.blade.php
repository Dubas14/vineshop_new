<header class="bg-white shadow relative z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold">Vineshop</a>

        @php
            $categories = \App\Models\Category::all();
        @endphp

        <nav class="flex items-center space-x-6 relative z-50">
            <a href="{{ route('home') }}" class="hover:underline">Головна</a>

            <!-- Категорії з випадаючим меню -->
            <div x-data="{ open: false }" class="relative z-50" @mouseenter="open = true" @mouseleave="open = false">
                <button class="hover:underline">Категорії</button>
                <div
                    x-show="open"
                    x-transition
                    class="absolute left-0 top-full mt-2 w-48 bg-white shadow-lg rounded-md overflow-hidden border z-50"
                >
                    @foreach ($categories as $category)
                        <a href="{{ route('catalog', ['category' => $category->slug]) }}"
                           class="block px-4 py-2 hover:bg-gray-100">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('catalog') }}" class="hover:underline">Каталог</a>
            <a href="{{ route('cart') }}" class="hover:underline">Кошик</a>
        </nav>
    </div>
</header>
