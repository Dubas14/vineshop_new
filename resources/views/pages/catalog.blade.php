@extends('layouts.app')

@section('title', __('messages.catalog'))

@section('content')
    <div class="container mx-auto px-4 py-8 products">
        <h1 class="text-2xl font-bold mb-6">
            @lang('messages.catalog')
            @isset($category)
                — {{ $category->name }}
            @endisset
        </h1>
        @if($products->count())
            <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6">
                @foreach($products as $product)
                    <div class="products-item border rounded p-4 shadow hover:shadow-md transition">
                        <a href="{{ route('product', $product->slug) }}">
                            @php $preview = $product->images->first()->path ?? $product->image; @endphp
                            <img src="{{ asset('storage/' . $preview) }}" alt="{{ $product->name }}" class="w-full h-48 object-contain products-item__img mb-3">
                            <h2 class="products-item__name font-semibold text-lg">{{ $product->name }}</h2>
                            <p class="products-item__price text-gray-600">{{ number_format($product->price, 2) }} грн</p>
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
