@extends('layouts.app')

@section('title', 'Вхід')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Вхід</h1>
        <form method="post" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1" for="email">Email</label>
                <input id="email" name="email" type="email" class="border p-2 w-full" required autofocus>
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="password">Пароль</label>
                <input id="password" name="password" type="password" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label><input type="checkbox" name="remember"> Запам'ятати мене</label>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Увійти</button>
        </form>
    </div>
@endsection
