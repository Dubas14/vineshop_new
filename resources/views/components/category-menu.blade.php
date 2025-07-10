@props(['categories', 'level' => 0, 'mobile' => false])

@foreach($categories as $category)
    <a href="{{ route('catalog', ['category' => $category->slug]) }}"
       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $level === 0 ? 'font-bold' : '' }}"
       @if($mobile) @click="$dispatch('close-mobile')" @endif>
        {!! str_repeat('&mdash; ', $level) !!}{{ $category->name }}
    </a>
    @if($category->children && $category->children->count())
        @include('components.category-menu', [
            'categories' => $category->children,
            'level' => $level + 1,
            'mobile' => $mobile
        ])
    @endif
@endforeach
