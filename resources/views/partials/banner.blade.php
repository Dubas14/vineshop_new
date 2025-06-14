<section class="relative bg-cover bg-center h-96" style="background-image: url('{{ asset('images/banner.jpg') }}')">
    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
        <div class="text-center text-white space-y-4">
            <h1 class="text-4xl md:text-5xl font-bold">@lang('messages.banner_title')</h1>
            <p class="text-lg">@lang('messages.banner_subtitle')</p>
            <a href="{{ route('catalog') }}" class="inline-block bg-white text-black font-bold px-6 py-3 rounded shadow hover:bg-gray-200 transition">
                @lang('messages.banner_button')
            </a>
        </div>
    </div>
</section>
