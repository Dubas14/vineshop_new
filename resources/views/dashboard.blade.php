@extends('layouts.app')

@section('title', 'Мій кабінет')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Вітаємо, {{ auth()->user()->name }}!</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                    Вийти
                </button>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-gray-700">Це ваш особистий кабінет.</p>

            <!-- Додатковий контент кабінету -->
            <div class="mt-4 space-y-2">
                <h2 class="text-xl font-semibold mb-2">Ваші дані:</h2>
                <p><span class="font-medium">Email:</span> {{ auth()->user()->email }}</p>
                <p><span class="font-medium">Дата реєстрації:</span> {{ auth()->user()->created_at->format('d.m.Y') }}</p>
            </div>
            <div class="mt-6 space-y-2">
                <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline block">Мої замовлення</a>
                <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline block">Редагувати профіль</a>
                <a href="{{ route('favorites.index') }}" class="text-blue-600 hover:underline block">Улюблені товари</a>
            </div>
        </div>
    </div>
@endsection
