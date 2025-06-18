<header class="bg-white shadow relative z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold">Vineshop</a>

        <nav class="flex space-x-4 items-center">
            <a href="{{ route('home') }}">@lang('messages.home')</a>

            {{-- Categories --}}
            <div x-data="{ open: false }" x-cloak class="relative" @mouseenter="open = true" @mouseleave="open = false">
                <button class="hover:underline focus:outline-none">@lang('messages.categories')</button>
                <div x-show="open"
                     x-transition
                     class="absolute left-0 mt-2 w-48 bg-white border rounded shadow-lg z-50"
                     @mouseenter="open = true" @mouseleave="open = false">
                    @foreach($categories as $category)
                        <a href="{{ route('catalog', ['category' => $category->slug]) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('catalog') }}">@lang('messages.catalog')</a>
            <a href="{{ route('about') }}">@lang('messages.about')</a>
            <a href="{{ route('contacts') }}">@lang('messages.contacts')</a>

            @php
                $count = array_sum(array_column(session('cart', []), 'quantity'));
            @endphp
            <a href="{{ route('cart') }}">
                @lang('messages.cart')
                @if($count)
                    ({{ $count }})
                @endif
            </a>

            @auth
                <a href="{{ route('dashboard') }}" class="text-sm font-medium">{{ __('messages.dashboard_title') }}</a>
            @else
                {{-- Account --}}
                <div x-data="{ open: false }" x-cloak class="relative" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="text-sm font-medium focus:outline-none">
                        @lang('messages.account')
                    </button>
                    <div x-show="open"
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50"
                         @mouseenter="open = true" @mouseleave="open = false">
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">@lang('messages.login')</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">@lang('messages.register')</a>
                    </div>
                </div>
            @endauth

            {{-- Language switch --}}
            <div class="flex gap-2">
                <a href="#" onclick="setLocale('uk')" class="{{ app()->getLocale() === 'uk' ? 'font-bold underline' : '' }}">UA</a>
                <a href="#" onclick="setLocale('en')" class="{{ app()->getLocale() === 'en' ? 'font-bold underline' : '' }}">EN</a>
            </div>
        </nav>
    </div>
</header>

{{-- 🔁 Скріпт для перемикання мови --}}
<script>
    function setLocale(lang) {
        fetch('/api/set-locale', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ locale: lang })
        }).then(() => {
            location.reload();
        });
    }
</script>
