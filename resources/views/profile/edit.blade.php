@extends('layouts.app')

@section('title', 'Профіль')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Профіль</h1>
        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')
            <div class="mb-4">
                <label class="block mb-1" for="name">Ім'я</label>
                <input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="email">Email</label>
                <input id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="phone">Телефон</label>
                <input id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="password">Новий пароль</label>
                <input id="password" name="password" type="password" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="password_confirmation">Підтвердити пароль</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="border p-2 w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Зберегти</button>
        </form>
    </div>
@endsection
