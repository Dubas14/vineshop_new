@extends('layouts.app')

@section('title', __('messages.dashboard_title'))

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ __('messages.welcome_user', ['name' => auth()->user()->name]) }}</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                    @lang('messages.logout')
                </button>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-gray-700">@lang('messages.dashboard_intro')</p>

            <!-- Additional dashboard content -->
            <div class="mt-4 space-y-2">
                <h2 class="text-xl font-semibold mb-2">@lang('messages.your_data')</h2>
                <p><span class="font-medium">Email:</span> {{ auth()->user()->email }}</p>
                <p><span class="font-medium">@lang('messages.registration_date')</span> {{ auth()->user()->created_at->format('d.m.Y') }}</p>
                <p>
                    <span class="font-medium">@lang('messages.personal_discount')</span>
                    {{ auth()->user()->discount ?? 0 }} %
                </p>
            </div>
            <div class="mt-6 space-y-2">
                <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline block">@lang('messages.my_orders')</a>
                <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline block">@lang('messages.edit_profile')</a>
                <a href="{{ route('favorites.index') }}" class="text-blue-600 hover:underline block">@lang('messages.favorite_products')</a>
            </div>
        </div>
    </div>
@endsection
