@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-md">
        <h1 class="text-2xl font-bold mb-6">Оформлення замовлення</h1>
        <form action="{{ route('order.store') }}" method="POST" class="space-y-4 bg-gray-100 p-6 rounded shadow">
            @csrf
            <div>
                <label for="name" class="block font-medium mb-1">Ваше імʼя</label>
                <input type="text" id="name" name="name" required class="w-full border px-3 py-2 rounded" value="{{ old('name', auth()->user()->name ?? '') }}">
            </div>
            <div>
                <label for="phone" class="block font-medium mb-1">Телефон</label>
                <input type="text" id="phone" name="phone" required class="w-full border px-3 py-2 rounded" value="{{ old('phone', auth()->user()->phone ?? '') }}">
            </div>
            <div>
                <label for="email" class="block font-medium mb-1">Email</label>
                <input type="email" id="email" name="email" class="w-full border px-3 py-2 rounded" value="{{ old('email', auth()->user()->email ?? '') }}">
            </div>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Оформити замовлення</button>
        </form>
    </div>
@endsection
