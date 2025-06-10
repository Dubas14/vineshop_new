@extends('layouts.app')

@section('title', 'Мої замовлення')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Мої замовлення</h1>
        <ul class="space-y-2">
            @forelse($orders as $order)
                <li class="border p-4 rounded">
                    <a href="{{ route('orders.show', $order) }}" class="font-semibold">
                        Замовлення #{{ $order->id }} від {{ $order->created_at->format('d.m.Y') }}
                    </a>
                    <p>Статус: {{ $order->status }}</p>
                </li>
            @empty
                <li>У вас немає замовлень.</li>
            @endforelse
        </ul>
    </div>
@endsection
