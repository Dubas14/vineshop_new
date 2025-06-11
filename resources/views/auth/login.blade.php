@extends('layouts.app')

@section('title', __('messages.login_title'))

@section('content')
    <div class="container mx-auto p-4 max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">@lang('messages.login_title')</h1>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('login') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input id="email" name="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">@lang('messages.password')</label>
                <input id="password" name="password" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" class="form-checkbox">
                    <span class="ml-2">@lang('messages.remember_me')</span>
                </label>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    @lang('messages.login_button')
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('password.request') }}">
                    @lang('messages.forgot_password')
                </a>
            </div>
        </form>
    </div>
@endsection
