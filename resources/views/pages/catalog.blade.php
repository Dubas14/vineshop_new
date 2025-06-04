@extends('layouts.app')

@section('title', 'Каталог')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">
            Каталог @isset($category) — {{ $category->name }} @endisset
        </h1>

        @if($products->count())
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="border rounded p-4 shadow hover:shadow-md transition">
                        <a href="{{ route('product', $product->slug) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover mb-3">
                            <h2 class="font-semibold text-lg">{{ $product->name }}</h2>
                            <p class="text-gray-600">{{ number_format($product->price, 2) }} грн</p>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @else
            <p>Немає доступних товарів.</p>
        @endif
    </div>
@endsection
