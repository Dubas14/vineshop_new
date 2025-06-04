@extends('layouts.app')

@section('title', 'Кошик')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Ваш кошик</h1>

        @if(session('cart') && count(session('cart')))
            <table class="min-w-full bg-white shadow rounded">
                <thead>
                <tr>
                    <th class="p-2">Назва</th>
                    <th class="p-2">Ціна</th>
                    <th class="p-2">Кількість</th>
                    <th class="p-2">Сума</th>
                    <th class="p-2">Дії</th>
                </tr>
                </thead>
                <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $id => $item)
                    @php $sum = $item['price'] * $item['quantity']; $total += $sum; @endphp
                    <tr>
                        <td class="p-2">{{ $item['name'] }}</td>
                        <td class="p-2">{{ number_format($item['price'], 2) }} грн</td>
                        <td class="p-2">{{ $item['quantity'] }}</td>
                        <td class="p-2">{{ number_format($sum, 2) }} грн</td>
                        <td class="p-2">
                            <form method="POST" action="{{ route('cart.remove', $id) }}">
                                @csrf
                                <button class="text-red-600 hover:underline">Видалити</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <strong>Загальна сума:</strong> {{ number_format($total, 2) }} грн
            </div>

            <form method="POST" action="{{ route('cart.clear') }}" class="mt-4">
                @csrf
                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Очистити кошик
                </button>
            </form>

        @else
            <p>Кошик порожній.</p>
        @endif
    </div>
@endsection
