@extends('layouts.app')

@section('title', __('messages.about'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">@lang('messages.about')</h1>
        <p class="mb-2">Ласкаво просимо до Vineshop. Ми пропонуємо широкий вибір якісних вин з усього світу.</p>
        <p>Наша мета &mdash; зробити вибір та покупку вина максимально зручною для вас.</p>
    </div>
@endsection
