{{-- resources/views/partials/banner_slider.blade.php --}}

<div class="swiper mySwiper relative mb-8 rounded-lg overflow-hidden shadow max-w-[1100px] mx-auto">
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

    <!-- Кнопки -->
    <div class="swiper-button-next text-white"></div>
    <div class="swiper-button-prev text-white"></div>

    <!-- Пагінація -->
    <div class="swiper-pagination"></div>
</div>

<script src="https://unpkg.com/swiper@9/swiper-bundle.min.js"></script>
<script>
    new Swiper(".mySwiper", {
        loop: true,
        autoplay: {
            delay: 5000,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
</script>
