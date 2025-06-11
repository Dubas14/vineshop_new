@extends('layouts.app')

@section('title', __('messages.about'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">@lang('messages.about')</h1>
        <p class="mb-2">{{ __('messages.about_intro1') }}</p>
        <p>{{ __('messages.about_intro2') }}</p>
    </div>
@endsection
