@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded-lg shadow">
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
                <p class="text-gray-600 mb-2">Категорія: <strong>{{ $product->category->name ?? '—' }}</strong></p>
                <p class="text-xl text-red-600 font-semibold mb-4">{{ number_format($product->price, 2) }} грн</p>

                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Додати в кошик
                    </button>
                </form>

                <div>
                    <h2 class="text-lg font-semibold mb-2">Опис товару</h2>
                    <p class="text-gray-700">{{ $product->description }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
