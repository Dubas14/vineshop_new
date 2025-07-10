@props(['categories' => [], 'count' => 0, 'mobile' => false])

<a href="{{ route('home') }}" @if($mobile) @click="$dispatch('close-mobile')" @endif>
    @lang('messages.home')
</a>

{{-- Категории --}}
<div x-data="{ open: false }" x-cloak class="relative" @mouseenter="open = true" @mouseleave="open = false">
    <button class="hover:underline focus:outline-none">
        @lang('messages.categories')
    </button>
    <div x-show="open"
         x-transition
         class="absolute left-0 mt-2 w-48 bg-white border rounded shadow-lg z-50"
         @mouseenter="open = true" @mouseleave="open = false">
        @include('components.category-menu', [
            'categories' => $categories,
            'level' => 0,
            'mobile' => $mobile
        ])
    </div>
</div>

<a href="{{ route('catalog') }}" @if($mobile) @click="$dispatch('close-mobile')" @endif>
    @lang('messages.catalog')
</a>

<a href="{{ route('about') }}" @if($mobile) @click="$dispatch('close-mobile')" @endif>
    @lang('messages.about')
</a>

<a href="{{ route('contacts') }}" @if($mobile) @click="$dispatch('close-mobile')" @endif>
    @lang('messages.contacts')
</a>

<a href="{{ route('cart') }}" @if($mobile) @click="$dispatch('close-mobile')" @endif>
    @lang('messages.cart')
    @if($count)
        ({{ $count }})
    @endif
</a>

@auth
    <a href="{{ route('dashboard') }}"
       class="text-sm font-medium"
       @if($mobile) @click="$dispatch('close-mobile')" @endif>
        {{ __('messages.dashboard_title') }}
    </a>
@else
    <div x-data="{ open: false }" x-cloak class="relative" @mouseenter="open = true" @mouseleave="open = false">
        <button class="text-sm font-medium focus:outline-none">
            @lang('messages.account')
        </button>
        <div x-show="open"
             x-transition
             class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50"
             @mouseenter="open = true" @mouseleave="open = false">
            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm hover:bg-gray-100"
               @if($mobile) @click="$dispatch('close-mobile')" @endif>
                @lang('messages.login')
            </a>
            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm hover:bg-gray-100"
               @if($mobile) @click="$dispatch('close-mobile')" @endif>
                @lang('messages.register')
            </a>
        </div>
    </div>
@endauth

{{-- Переключение языка --}}
<div class="flex space-x-1 mt-2 md:mt-0" x-data>
    <a href="#" @click.prevent="document.cookie = 'locale=uk'; localStorage.setItem('locale','uk'); location.reload();"
       class="text-sm">UA</a>
    <a href="#" @click.prevent="document.cookie = 'locale=en'; localStorage.setItem('locale','en'); location.reload();"
       class="text-sm">EN</a>
</div>
