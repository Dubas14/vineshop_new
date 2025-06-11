@extends('layouts.app')

@section('title', __('messages.profile_title'))

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">@lang('messages.profile_title')</h1>
        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')
            <div class="mb-4">
                <label class="block mb-1" for="name">@lang('messages.name')</label>
                <input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="email">Email</label>
                <input id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="phone">@lang('messages.phone')</label>
                <input id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="password">@lang('messages.new_password')</label>
                <input id="password" name="password" type="password" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block mb-1" for="password_confirmation">@lang('messages.confirm_password')</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="border p-2 w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">@lang('messages.save')</button>
        </form>
    </div>
@endsection
