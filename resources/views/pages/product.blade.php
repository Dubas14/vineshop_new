@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container mx-auto px-4 py-8">

        {{-- Breadcrumbs --}}
        <div class="mb-4 text-sm text-gray-500">
            <a href="/">Головна</a> /
            <a href="{{ route('catalog.byCategory', $product->category->id) }}">
                {{ $product->category->name ?? '—' }}
            </a>
            <span>{{ $product->name }}</span>
        </div>

        {{-- Головний блок --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            {{-- Галерея --}}
            <div>
                @php
                    if ($product->image) {
                        $main = asset('storage/' . $product->image);
                    } elseif ($product->images->first()) {
                        $main = asset('storage/' . $product->images->first()->path);
                    } else {
                        $main = asset('images/no-image.png');
                    }
                @endphp

                <img id="main-image" src="{{ $main }}" alt="{{ $product->name }}" class="rounded-lg shadow w-full h-96 object-contain bg-white mx-auto">

                @if($product->images->count())
                    <div class="flex gap-2 mt-4">
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded border cursor-pointer" onclick="document.getElementById('main-image').src=this.src">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Інфо + корзина --}}
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $product->name }}</h1>
                <div class="mb-1 text-gray-500">Код: {{ $product->sku ?? '—' }}</div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-2xl font-bold text-red-600">{{ number_format($product->price, 2) }} грн</span>
                    @if($product->old_price)
                        <span class="text-lg text-gray-400 line-through">{{ number_format($product->old_price, 2) }} грн</span>
                    @endif
                </div>
                <div class="mb-4">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex items-center gap-3">
                        @csrf
                        <input type="number" name="quantity" value="1" min="1" class="w-16 border rounded px-2 py-1 text-center">
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                            <i class="fa fa-shopping-cart mr-1"></i> Додати до кошика
                        </button>
                    </form>
                </div>
                {{-- Короткі характеристики: partial --}}
                @include('partials.product-attributes', ['product' => $product])
            </div>
        </div>

        {{-- Таб-меню (опис/характеристики/відгуки/гарантія) --}}
        <div x-data="{ tab: 'info' }" class="mt-8">
            <div class="flex gap-4 border-b mb-6">
                <button :class="tab === 'info' ? 'font-bold border-b-2 border-red-600' : ''"
                        @click="tab = 'info'" class="py-2 px-4">Все про товар</button>
                <button :class="tab === 'chars' ? 'font-bold border-b-2 border-red-600' : ''"
                        @click="tab = 'chars'" class="py-2 px-4">Характеристики</button>
                <button :class="tab === 'reviews' ? 'font-bold border-b-2 border-red-600' : ''"
                        @click="tab = 'reviews'" class="py-2 px-4">Відгуки</button>
                <button :class="tab === 'guarantee' ? 'font-bold border-b-2 border-red-600' : ''"
                        @click="tab = 'guarantee'" class="py-2 px-4">Гарантія якості</button>
            </div>

            <div x-show="tab === 'info'">
                <h2 class="text-lg font-semibold mb-2">Опис</h2>
                <div class="mb-3 text-gray-800">{{ $product->description }}</div>
                {{-- Додаткові блоки: смак, аромат, колір, гастропоєднання --}}
                @if($product->taste)
                    <div class="mb-1"><b>Смак:</b> {{ $product->taste }}</div>
                @endif
                @if($product->aroma)
                    <div class="mb-1"><b>Аромат:</b> {{ $product->aroma }}</div>
                @endif
                @if($product->pairing)
                    <div class="mb-1"><b>Гастрономічне поєднання:</b> {{ $product->pairing }}</div>
                @endif
            </div>
            <div x-show="tab === 'chars'" class="pt-4">
                <h2 class="text-lg font-semibold mb-2">Характеристики</h2>
                {{-- Використовуємо partial --}}
                @include('partials.product-attributes', ['product' => $product])
            </div>
            <div x-show="tab === 'reviews'" class="pt-4">
                <h2 class="text-lg font-semibold mb-2">Відгуки</h2>
                <div>Тут буде блок з відгуками...</div>
            </div>
            <div x-show="tab === 'guarantee'" class="pt-4">
                <h2 class="text-lg font-semibold mb-2">Гарантія якості</h2>
                <div>Інформація про гарантію...</div>
            </div>
        </div>

        {{-- Рекомендації збоку --}}
        @if(isset($recommended) && count($recommended))
            <aside>
                <h3 class="text-xl font-bold mb-4">Рекомендації</h3>
                <div class="space-y-6">
                    @foreach($recommended as $rec)
                        <a href="{{ route('product', $rec->slug) }}" class="block border rounded-lg p-3 hover:shadow-lg">
                            <img src="{{ asset('storage/' . $rec->image) }}" alt="{{ $rec->name }}" class="w-full h-40 object-contain mb-2">
                            <div class="font-semibold">{{ $rec->name }}</div>
                            <div class="text-lg font-bold text-red-700">{{ number_format($rec->price, 2) }} грн</div>
                        </a>
                    @endforeach
                </div>
            </aside>
        @endif

        {{-- Доставка/18+ інфо --}}
        <div class="mt-8 grid md:grid-cols-2 gap-8 text-sm text-gray-600">
            <div>
                <b>Доставка по місту:</b> Мінімальне замовлення 1000 ₴. Самовивіз/курʼєр/Нова Пошта.
            </div>
            <div>
                <b>18+</b> Придбати алкогольні напої можуть особи, які досягли 18 років.
            </div>
        </div>
    </div>
@endsection
