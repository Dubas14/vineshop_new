@extends('layouts.app')

@section('title', 'Головна')

@section('content')

    <div class="banners grid gap-4 mb-8">
        @foreach($banners as $banner)
            <a href="{{ $banner['link'] ?? '#' }}">
                <img src="{{ asset('storage/' . $banner['image']) }}">
            </a>
        @endforeach
    </div>

    @include('partials.banner')

    {{-- 🏷 Категорії (плитки) --}}
    <section class="py-10 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6">Категорії</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('catalog', ['category' => $category->slug]) }}"
                       class="block rounded overflow-hidden shadow hover:shadow-lg transition">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-40 object-cover">
                        <div class="p-3 text-center font-semibold">{{ $category->name }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 🍷 Товари --}}
    <section class="py-10 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6">Топ товари</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <a href="{{ route('product', $product->slug) }}"
                       class="block bg-white rounded-lg overflow-hidden shadow hover:shadow-md transition">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        <div class="p-3 space-y-1">
                            <div class="text-sm text-gray-500">{{ $product->category->name ?? '' }}</div>
                            <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                            <div class="text-red-600 font-bold">{{ number_format($product->price, 2) }} грн</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

@endsection
