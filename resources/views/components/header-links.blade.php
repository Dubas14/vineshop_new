@props(['categories' => [], 'count' => 0, 'mobile' => false])

<nav class="{{ $mobile ? 'flex flex-col space-y-2' : 'flex items-center space-x-6' }}">
    <!-- Домашня сторінка -->
    <a href="{{ route('home') }}"
       class="hover:underline"
       @if($mobile) @click="$dispatch('close-mobile')" @endif>
        @lang('messages.home')
    </a>

    <!-- Категорії з випадаючим меню -->
    <div x-data="{ open: false }"
         class="relative"
         @if(!$mobile) @mouseenter="open = true" @mouseleave="open = false" @endif>
        <button @click="open = !open"
                class="hover:underline focus:outline-none flex items-center">
            @lang('messages.categories')
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path x-show="open" d="M5 15l7-7 7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div x-show="open"
             x-transition
             class="{{ $mobile ? 'pl-4' : 'absolute left-0 mt-2 min-w-[200px] bg-white border rounded shadow-lg z-50' }}"
             @if(!$mobile) @mouseenter="open = true" @mouseleave="open = false" @endif>
            @foreach($categories as $category)
                @include('components.category-menu', [
                    'categories' => [$category], // Передаємо як масив з одним елементом
                    'level' => 0,
                    'mobile' => $mobile
                ])
            @endforeach
        </div>
    </div>

    <!-- Інші пункти меню -->
    <a href="{{ route('catalog') }}"
       class="hover:underline"
       @if($mobile) @click="$dispatch('close-mobile')" @endif>
        @lang('messages.catalog')
    </a>

    <a href="{{ route('about') }}"
       class="hover:underline"
       @if($mobile) @click="$dispatch('close-mobile')" @endif>
        @lang('messages.about')
    </a>

    <a href="{{ route('contacts') }}"
       class="hover:underline"
       @if($mobile) @click="$dispatch('close-mobile')" @endif>
        @lang('messages.contacts')
    </a>

    <!-- Кошик -->
    <a href="{{ route('cart') }}"
       class="hover:underline flex items-center"
       @if($mobile) @click="$dispatch('close-mobile')" @endif>
        @lang('messages.cart')
        @if($count)
            <span class="ml-1">({{ $count }})</span>
        @endif
    </a>

    <!-- Акаунт/Авторизація -->
    @auth
        <a href="{{ route('dashboard') }}"
           class="text-sm font-medium hover:underline"
           @if($mobile) @click="$dispatch('close-mobile')" @endif>
            {{ __('messages.dashboard_title') }}
        </a>
    @else
        <div x-data="{ open: false }"
             class="relative"
             @if(!$mobile) @mouseenter="open = true" @mouseleave="open = false" @endif>
            <button @click="open = !open"
                    class="text-sm font-medium hover:underline focus:outline-none">
                @lang('messages.account')
            </button>
            <div x-show="open"
                 x-transition
                 class="{{ $mobile ? 'pl-4' : 'absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50' }}">
                <a href="{{ route('login') }}"
                   class="block px-4 py-2 text-sm hover:bg-gray-100"
                   @if($mobile) @click="$dispatch('close-mobile')" @endif>
                    @lang('messages.login')
                </a>
                <a href="{{ route('register') }}"
                   class="block px-4 py-2 text-sm hover:bg-gray-100"
                   @if($mobile) @click="$dispatch('close-mobile')" @endif>
                    @lang('messages.register')
                </a>
            </div>
        </div>
    @endauth

    <!-- Перемикач мови -->
    <div class="flex space-x-2 {{ $mobile ? 'mt-2' : '' }}">
        <a href="#"
           @click.prevent="document.cookie = 'locale=uk'; localStorage.setItem('locale','uk'); location.reload();"
           class="text-sm hover:underline">UA</a>
        <a href="#"
           @click.prevent="document.cookie = 'locale=en'; localStorage.setItem('locale','en'); location.reload();"
           class="text-sm hover:underline">EN</a>
    </div>
</nav>
