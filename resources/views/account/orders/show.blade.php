@extends('layouts.app')

@section('title', __('messages.order_number', ['id' => $order->id]))

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.order_number', ['id' => $order->id]) }}</h1>
        <p class="mb-2">@lang('messages.status') {{ $order->status }}</p>
        <p class="mb-4">@lang('messages.placed_at') {{ $order->created_at->format('d.m.Y H:i') }}</p>

        <table class="w-full border">
            <thead>
            <tr>
                <th class="p-2 border">@lang('messages.product')</th>
                <th class="p-2 border">@lang('messages.quantity')</th>
                <th class="p-2 border">@lang('messages.price')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td class="p-2 border">{{ $item->product->name ?? __('messages.product') }}</td>
                    <td class="p-2 border">{{ $item->quantity }}</td>
                    <td class="p-2 border">{{ $item->price }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr class="font-semibold">
                <td class="p-2 border text-left">@lang('messages.total'):</td>
                <td class="p-2 border">{{ $order->items->sum('quantity') }}</td>
                <td class="p-2 border">{{ $order->items->sum(fn($i) => $i->price * $i->quantity) }}</td>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection
