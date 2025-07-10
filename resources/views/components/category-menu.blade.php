@props([
    'categories',
    'level' => 0,
    'mobile' => false,
    'activeCategory' => null
])

@foreach($categories as $category)
    @php
        $isActive = $activeCategory && ($activeCategory->id == $category->id || ($activeCategory->ancestors && $activeCategory->ancestors->contains($category->id)));
        $hasChildren = $category->children->isNotEmpty();
    @endphp

    <div x-data="{ open: false }"
         @if(!$mobile) @mouseenter="open = true" @mouseleave="open = false" @endif
         class="relative group">

        <!-- Основна категорія -->
        <a href="{{ route('catalog', ['category' => $category->slug]) }}"
           @if($mobile) @click="open = !open; $event.preventDefault()" @endif
           class="flex items-center justify-between px-4 py-3 text-sm rounded-lg
                  hover:bg-gray-50 hover:text-indigo-600 transition-colors
                  {{ $isActive ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-700' }}
                  {{ $level > 0 ? 'pl-' . ($level * 4 + 4) : '' }}">
            <span>{{ $category->name }}</span>
            @if($hasChildren && !$mobile)
                <span class="ml-2">&rarr;</span>
            @endif
        </a>

        <!-- Підкатегорії (з'являються праворуч) -->
        @if($hasChildren)
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-x-2"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-2"
                 @if($mobile) @click.outside="open = false" @endif
                 class="{{ $mobile ? 'pl-4' : 'absolute left-full top-0 ml-1 min-w-[200px] bg-white border border-gray-200 rounded-lg shadow-lg z-10' }}">
                @include('components.category-menu', [
                    'categories' => $category->children,
                    'level' => $level + 1,
                    'mobile' => $mobile,
                    'activeCategory' => $activeCategory
                ])
            </div>
        @endif
    </div>
@endforeach
