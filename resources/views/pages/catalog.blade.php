@extends('layouts.app')

@section('title', __('messages.catalog'))

@section('content')
    <div class="container mx-auto px-4 py-8 products">
        <h1 class="text-2xl font-bold mb-6">
            @lang('messages.catalog')
            @isset($category)
                — <a href="{{ route('catalog', ['category' => urlencode($category->slug)]) }}" class="hover:underline">{{ $category->name }}</a>
            @endisset
        </h1>
        @if($products->count())
            <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6">
                @foreach($products as $product)
                    <div class="products-item border rounded p-4 shadow hover:shadow-md transition">
                        <a href="{{ route('product', $product->slug) }}">
                            @php
                                if ($product->image) {
                                    $imagePath = asset('storage/' . $product->image);
                                } elseif ($product->images->first()) {
                                    $imagePath = asset('storage/' . $product->images->first()->path);
                                } else {
                                    $imagePath = asset('images/no-image.png'); // розмісти no-image.png в /public/images/
                                }
                            @endphp
                            <img src="{{ $imagePath }}" alt="{{ $product->name }}" class="w-full h-48 object-contain products-item__img">
                            <div class="mt-2 space-y-1">
                                <h3 class="text-sm text-gray-900 font-medium">{{ $product->name }}</h3>
                                <p class="products-item__price text-red-600 font-semibold">{{ number_format($product->price, 2) }} грн</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 w-full flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <p>{{ __('messages.no_products') }}</p>
        @endif
    </div>
@endsection
