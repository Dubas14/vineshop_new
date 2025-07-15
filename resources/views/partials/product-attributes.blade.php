<div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm mb-6">
    {{-- Категорія --}}
    @if($product->category?->name)
        <div class="py-1 border-b border-gray-100">
            <b>Категорія:</b>
            <a href="{{ route('catalog.byCategory', $product->category->id) }}"
               class="text-blue-600 hover:text-blue-800 underline ml-1 transition">
                {{ $product->category->name }}
            </a>
        </div>
    @endif

    {{-- Країна --}}
    @if($product->country)
        <div class="py-1 border-b border-gray-100">
            <b>Країна:</b>
            <a href="{{ route('catalog', ['country' => $product->country]) }}"
               class="text-blue-600 hover:text-blue-800 underline ml-1 transition">
                {{ $product->country }}
            </a>
        </div>
    @endif

    {{-- Бренд --}}
    @if($product->brand)
        <div class="py-1 border-b border-gray-100">
            <b>Бренд:</b>
            <a href="{{ route('catalog', ['brand' => $product->brand]) }}"
               class="text-blue-600 hover:text-blue-800 underline ml-1 transition">
                {{ $product->brand }}
            </a>
        </div>
    @endif

    {{-- Виробник --}}
    @if($product->manufacturer)
        <div class="py-1 border-b border-gray-100">
            <b>Виробник:</b>
            <a href="{{ route('catalog', ['manufacturer' => $product->manufacturer]) }}"
               class="text-blue-600 hover:text-blue-800 underline ml-1 transition">
                {{ $product->manufacturer }}
            </a>
        </div>
    @endif

    {{-- Об'єм --}}
    @if($product->volume)
        <div class="py-1 border-b border-gray-100">
            <b>Об'єм:</b>
            <a href="{{ route('catalog', ['volume' => $product->volume]) }}"
               class="text-blue-600 hover:text-blue-800 underline ml-1 transition">
                {{ $product->volume }} мл
            </a>
        </div>
    @endif

    {{-- Міцність --}}
    @if($product->alcohol)
        <div class="py-1 border-b border-gray-100">
            <b>Міцність:</b>
            <a href="{{ route('catalog', ['alcohol' => $product->alcohol]) }}"
               class="text-blue-600 hover:text-blue-800 underline ml-1 transition">
                {{ $product->alcohol }}%
            </a>
        </div>
    @endif

    {{-- Вміст цукру --}}
    @if($product->sugar_content)
        <div class="py-1 border-b border-gray-100">
            <b>Вміст цукру:</b>
            <a href="{{ route('catalog', ['sugar_content' => $product->sugar_content]) }}"
               class="text-blue-600 hover:text-blue-800 underline ml-1 transition">
                {{ $product->sugar_content }}
            </a>
        </div>
    @endif

    {{-- Колір --}}
    @if($product->color)
        <div class="py-1 border-b border-gray-100">
            <b>Колір:</b>
            <a href="{{ route('catalog', ['color' => $product->color]) }}"
               class="text-blue-600 hover:text-blue-800 underline ml-1 transition">
                {{ $product->color }}
            </a>
        </div>
    @endif

    {{-- Тип --}}
    @if($product->type)
        <div class="py-1 border-b border-gray-100">
            <b>Тип:</b>
            <a href="{{ route('catalog', ['type' => $product->type]) }}"
               class="text-blue-600 hover:text-blue-800 underline ml-1 transition">
                {{ $product->type }}
            </a>
        </div>
    @endif
</div>
