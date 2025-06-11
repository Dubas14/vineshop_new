@extends('layouts.app')

@section('title', __('messages.my_orders'))

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">@lang('messages.my_orders')</h1>
        <ul class="space-y-2">
            @forelse($orders as $order)
                <li class="border p-4 rounded">
                    <a href="{{ route('orders.show', $order) }}" class="font-semibold">
                        {{ __('messages.order_number_date', ['id' => $order->id, 'date' => $order->created_at->format('d.m.Y')]) }}
                    </a>
                    <p>@lang('messages.status') {{ $order->status }}</p>
                </li>
            @empty
                <li>@lang('messages.no_orders')</li>
            @endforelse
        </ul>
    </div>
@endsection
