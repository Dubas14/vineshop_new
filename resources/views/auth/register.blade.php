@extends('layouts.app')

@section('title', 'Реєстрація')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Реєстрація</h1>
        <form method="post" action="{{ route('register') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1" for="name">Ім'я</label>
                <input id="name" name="name" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="email">Email</label>
                <input id="email" name="email" type="email" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="password">Пароль</label>
                <input id="password" name="password" type="password" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="password_confirmation">Підтвердіть пароль</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="border p-2 w-full" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Зареєструватися</button>
        </form>
    </div>
@endsection
