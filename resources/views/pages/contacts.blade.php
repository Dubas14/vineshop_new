@extends('layouts.app')

@section('title', __('messages.contacts'))
@section('content')
    <div class="container mx-auto px-4 py-8 space-y-2">
        <h1 class="text-2xl font-bold mb-4">@lang('messages.contacts')</h1>
        <p>{{ __('messages.contact_intro') }}</p>
        <ul class="list-disc list-inside">
            <li>Email: <a href="mailto:info@vineshop.test" class="text-blue-600 hover:underline">info@vineshop.test</a></li>
            <li>@lang('messages.phone'): <a href="tel:+3800000000" class="text-blue-600 hover:underline">+38 (000) 000-00-00</a></li>
        </ul>
    </div>
@endsection
