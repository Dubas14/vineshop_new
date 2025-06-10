{{-- resources/views/partials/banner_slider.blade.php --}}

<div class="mySwiperBanner relative mb-8 rounded-lg overflow-hidden shadow max-w-[1100px] mx-auto">
    <div class="swiper-wrapper">
        @foreach($banners as $banner)
            <div class="swiper-slide">
                <a href="{{ $banner['link_target'] ?? '#' }}">
                    <img src="{{ asset('storage/' . $banner['image']) }}"
                         alt="{{ $banner['title'] }}"
                         class="w-full h-[300px] object-cover" />
                </a>
            </div>
        @endforeach
    </div>
</div>
