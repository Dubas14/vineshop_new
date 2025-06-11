@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                @php
                    $main = $product->images->first()->path ?? $product->image;
                @endphp
                <img id="main-image" src="{{ asset('storage/' . $main) }}" alt="{{ $product->name }}" class="rounded-lg shadow max-w-md max-h-96 object-contain mx-auto">

                @if($product->images->count())
                    <div class="flex gap-2 mt-4">
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded cursor-pointer" onclick="document.getElementById('main-image').src=this.src">
                        @endforeach
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
                <p class="text-gray-600 mb-2">@lang('messages.category'): <strong>{{ $product->category->name ?? '—' }}</strong></p>
                <p class="text-xl text-red-600 font-semibold mb-4">{{ number_format($product->price, 2) }} грн</p>

                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        @lang('messages.add_to_cart')
                    </button>
                </form>

                <div>
                    <h2 class="text-lg font-semibold mb-2">@lang('messages.product_description')</h2>
                    <p class="text-gray-700">{{ $product->description }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
