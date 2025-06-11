@extends('layouts.app')

@section('content')
    <div id="app">
        <cart></cart>
    </div>

    <div class="container mx-auto px-4 mt-6 text-right">
        <a href="{{ route('checkout') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            @lang('messages.checkout_proceed')
        </a>
    </div>
@endsection
