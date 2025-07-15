@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container mx-auto px-4 py-8">
        {{-- Хлібні крихти --}}
        <div class="mb-4 text-sm text-gray-500">
            <a href="/">Головна</a> /
            @if($product->category) {{-- Додана перевірка наявності категорії --}}
            <a href="{{ route('catalog', ['category' => urlencode($product->category->slug)]) }}">
                {{ $product->category->name }}
            </a> /
            @else
                <span>—</span> /
            @endif
            <span>{{ $product->name }}</span>
        </div>

        {{-- Головний блок --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            {{-- Галерея --}}
            <div>
                @php
                    // Оптимізовано вибір головного зображення
                    $main = $product->image
                        ? asset('storage/' . $product->image)
                        : ($product->images->first()
                            ? asset('storage/' . $product->images->first()->path)
                            : asset('images/no-image.png'));
                @endphp

                <img id="main-image" src="{{ $main }}" alt="{{ $product->name }}"
                     class="rounded-lg shadow w-full h-96 object-contain bg-white mx-auto">

                {{-- Мініатюри --}}
                @if($product->images->count())
                    <div class="flex gap-2 mt-4 overflow-x-auto py-2">
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $product->name }}"
                                 class="w-20 h-20 object-cover rounded border cursor-pointer hover:border-red-400 transition"
                                 onclick="document.getElementById('main-image').src=this.src">
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
                        <input type="number" name="quantity" value="1" min="1"
                               class="w-16 border rounded px-2 py-1 text-center">
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                            <i class="fa fa-shopping-cart mr-1"></i> Додати до кошика
                        </button>
                    </form>
                </div>
                {{-- Короткі характеристики --}}
                @include('partials.product-attributes', ['product' => $product])
            </div>
        </div>

        {{-- Таби --}}
        <div x-data="{ tab: 'info' }" class="mt-8">
            <div class="flex gap-4 border-b mb-6 overflow-x-auto">
                <button :class="tab === 'info' ? 'font-bold border-b-2 border-red-600' : 'text-gray-600'"
                        @click="tab = 'info'" class="py-2 px-4 whitespace-nowrap">Все про товар</button>
                <button :class="tab === 'chars' ? 'font-bold border-b-2 border-red-600' : 'text-gray-600'"
                        @click="tab = 'chars'" class="py-2 px-4 whitespace-nowrap">Характеристики</button>
                <button :class="tab === 'reviews' ? 'font-bold border-b-2 border-red-600' : 'text-gray-600'"
                        @click="tab = 'reviews'" class="py-2 px-4 whitespace-nowrap">Відгуки</button>
                <button :class="tab === 'guarantee' ? 'font-bold border-b-2 border-red-600' : 'text-gray-600'"
                        @click="tab = 'guarantee'" class="py-2 px-4 whitespace-nowrap">Гарантія якості</button>
            </div>

            {{-- Контент табів --}}
            <div x-show="tab === 'info'" class="prose max-w-none">
                <h2 class="text-lg font-semibold mb-2">Опис</h2>
                <div class="mb-3 text-gray-800">{!! nl2br(e($product->description)) !!}</div>

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
                @include('partials.product-attributes', ['product' => $product])
            </div>

            <div x-show="tab === 'reviews'" class="pt-4">
                <h2 class="text-lg font-semibold mb-2">Відгуки</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-center text-gray-500">Функція відгуків тимчасово недоступна</p>
                </div>
            </div>

            <div x-show="tab === 'guarantee'" class="pt-4">
                <h2 class="text-lg font-semibold mb-2">Гарантія якості</h2>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p>✅ Оригінальна продукція від офіційних дистриб'юторів</p>
                    <p>✅ Належні умови зберігання</p>
                    <p>✅ Контроль якості на кожному етапі</p>
                </div>
            </div>
        </div>

        {{-- Рекомендації --}}
        @if(isset($recommended) && $recommended->count())
            <div class="mt-12">
                <h3 class="text-xl font-bold mb-4">Рекомендуємо спробувати</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($recommended as $rec)
                        <a href="{{ route('product', $rec->slug) }}" class="block border rounded-lg p-3 hover:shadow-lg transition-shadow">
                            <img src="{{ $rec->image ? asset('storage/' . $rec->image) : asset('images/no-image.png') }}"
                                 alt="{{ $rec->name }}"
                                 class="w-full h-40 object-contain mb-2">
                            <div class="font-semibold line-clamp-2">{{ $rec->name }}</div>
                            <div class="text-lg font-bold text-red-700 mt-1">{{ number_format($rec->price, 2) }} грн</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Інфо блок --}}
        <div class="mt-8 grid md:grid-cols-2 gap-8 text-sm text-gray-600 bg-gray-50 p-4 rounded-lg">
            <div>
                <b>🚚 Доставка по Україні:</b>
                <ul class="list-disc pl-5 mt-1">
                    <li>Нова Пошта - від 70 грн</li>
                    <li>Самовивіз зі складу (м. Київ)</li>
                    <li>Безкоштовна доставка від 2000 грн</li>
                </ul>
            </div>
            <div>
                <b>🔞 18+</b>
                <p class="mt-1">Алкогольна продукція призначена лише для осіб, які досягли повноліття.</p>
            </div>
        </div>
    </div>
@endsection
