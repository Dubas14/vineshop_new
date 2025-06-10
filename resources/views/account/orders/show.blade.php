@extends('layouts.app')

@section('title', 'Замовлення #' . $order->id)

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Замовлення #{{ $order->id }}</h1>
        <p class="mb-2">Статус: {{ $order->status }}</p>
        <p class="mb-4">Оформлено: {{ $order->created_at->format('d.m.Y H:i') }}</p>

        <table class="w-full border">
            <thead>
            <tr>
                <th class="p-2 border">Товар</th>
                <th class="p-2 border">Кількість</th>
                <th class="p-2 border">Ціна</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td class="p-2 border">{{ $item->product->name ?? 'Товар' }}</td>
                    <td class="p-2 border">{{ $item->quantity }}</td>
                    <td class="p-2 border">{{ $item->price }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
