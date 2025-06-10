@extends('layouts.app')

@section('title', 'Улюблені товари')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Улюблені товари</h1>
        <ul class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($products as $product)
                <li class="border p-4 rounded">
                    <h2 class="font-semibold">{{ $product->name }}</h2>
                    <p>{{ $product->price }} грн</p>
                    <form method="POST" action="{{ route('favorites.destroy', $product) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600">Видалити</button>
                    </form>
                </li>
            @empty
                <li>У вас немає улюблених товарів.</li>
            @endforelse
        </ul>
    </div>
@endsection
