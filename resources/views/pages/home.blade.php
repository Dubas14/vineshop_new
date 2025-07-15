@extends('layouts.app')

@section('title', __('messages.home'))

@section('content')
    <div class="banners grid gap-4 mb-8">
        @include('partials.banner_slider')
    </div>

    @include('partials.banner')

    {{-- 🍷 Products --}}
    <section class="py-10 bg-gray-50 products">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6">@lang('messages.top_products')</h2>
            <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6">
                @foreach($products as $product)
                    <a href="{{ route('product', $product->slug) }}"
                       class="products-item block bg-white rounded-lg overflow-hidden shadow hover:shadow-md transition">
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
                        <div class="p-3 space-y-1">
                            <div class="products-item__category text-sm text-gray-500">
                                @if($product->category)
                                    <a href="{{ route('catalog', ['category' => urlencode($product->category->slug)]) }}" class="hover:underline">
                                        {{ $product->category->name }}
                                    </a>
                                @endif
                            </div>
                            <h3 class="products-item__name text-lg font-semibold">{{ $product->name }}</h3>
                            <div class="products-item__price text-red-600 font-bold">{{ number_format($product->price, 2) }} грн</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

@endsection


