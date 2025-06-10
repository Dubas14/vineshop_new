@extends('layouts.app')

@section('title', 'Мій кабінет')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Вітаємо, {{ auth()->user()->name }}!</h1>
        <p>Це ваш кабінет.</p>
    </div>
@endsection
