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
                        <td class="p-2">
                            <form method="POST" action="{{ route('cart.update', $id) }}" class="flex items-center space-x-2">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border rounded px-2 py-1 text-center">
                                <button class="text-blue-600 hover:underline" type="submit">Оновити</button>
                            </form>
                        </td>
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

            {{-- Форма оформлення замовлення --}}
            <form method="POST" action="{{ route('order.store') }}" class="mt-8 space-y-4 bg-gray-100 p-6 rounded shadow">
                @csrf
                <h2 class="text-xl font-semibold mb-4">Оформлення замовлення</h2>

                <div>
                    <label for="name" class="block font-medium mb-1">Ваше імʼя</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="w-full border px-3 py-2 rounded"
                        required
                        minlength="2"
                        maxlength="100"
                    >
                </div>

                <div>
                    <label for="phone" class="block font-medium mb-1">Телефон</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="w-full border px-3 py-2 rounded"
                        required
                        pattern="^\+?[0-9\s\-]{10,15}$"
                        title="Введіть коректний номер телефону (10–15 цифр)"
                    >
                </div>

                <div>
                    <label for="email" class="block font-medium mb-1">Email (необовʼязково)</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="w-full border px-3 py-2 rounded"
                        placeholder="you@example.com"
                    >
                </div>

                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                    Оформити замовлення
                </button>
            </form>

        @else
            <p>Кошик порожній.</p>
        @endif
    </div>
@endsection
