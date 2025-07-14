<div class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm mb-6">
    @if(!empty($product->category?->name))
        <div>
            <b>Категорія:</b>
            <a href="{{ route('catalog.byCategory', $product->category->id) }}"
               class="text-blue-700 underline hover:text-blue-900">{{ $product->category->name }}</a>
        </div>
    @endif
    @if(!empty($product->country))
        <div>
            <b>Країна:</b>
            <a href="{{ route('catalog', ['country' => $product->country]) }}"
               class="text-blue-700 underline hover:text-blue-900">{{ $product->country }}</a>
        </div>
    @endif
    @if(!empty($product->volume))
        <div>
            <b>Обʼєм:</b> {{ $product->volume }} мл
        </div>
    @endif
    @if(!empty($product->brand))
            <div>
                <b>Бренд:</b>
                <a href="{{ route('catalog', ['brand' => $product->brand]) }}"
                   class="text-blue-700 underline hover:text-blue-900">{{ $product->brand }}</a>
            </div>
    @endif
    @if(!empty($product->classification))
        <div>
            <b>Класифікація:</b> {{ $product->classification }}
        </div>
    @endif
    @if(!empty($product->manufacturer))
        <div>
            <b>Виробник:</b>
            <a href="{{ route('catalog', ['manufacturer' => $product->manufacturer]) }}"
               class="text-blue-700 underline hover:text-blue-900">{{ $product->manufacturer }}</a>
        </div>
    @endif
    @if(!empty($product->region))
        <div>
            <b>Регіон, субрегіон:</b> {{ $product->region }}
        </div>
    @endif
    @if(!empty($product->type))
        <div>
            <b>Тип:</b> {{ $product->type }}
        </div>
    @endif
    @if(!empty($product->package_type))
        <div>
            <b>Тип упаковки:</b> {{ $product->package_type }}
        </div>
    @endif
    @if(!empty($product->sugar_content))
        <div>
            <b>Вміст цукру:</b>
            <a href="{{ route('catalog', ['sugar' => $product->sugar_content]) }}"
               class="text-blue-700 underline hover:text-blue-900">{{ $product->sugar_content }}</a>
        </div>
    @endif
    @if(!empty($product->color))
        <div>
            <b>Колір:</b>
            <a href="{{ route('catalog', ['color' => $product->color]) }}"
               class="text-blue-700 underline hover:text-blue-900">{{ $product->color }}</a>
        </div>
    @endif

    {{-- Додавай інші поля аналогічно --}}
</div>
