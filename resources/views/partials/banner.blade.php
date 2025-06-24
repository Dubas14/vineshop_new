<section class="relative w-full h-[400px] md:h-[500px] bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ asset('images/banner.jpg') }}')">
    <!-- Gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-black/30"></div>

    <!-- Content container with smooth entrance animation -->
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center text-white px-4 max-w-4xl space-y-6 animate-fade-in-up">
            <!-- Title with improved typography -->
            <h1 class="text-3xl md:text-5xl font-bold leading-tight tracking-tight">
                @lang('messages.banner_title')
            </h1>

            <!-- Subtitle with decorative elements -->
            <div class="relative inline-block">
                <p class="text-lg md:text-xl font-light max-w-2xl mx-auto relative z-10">
                    @lang('messages.banner_subtitle')
                </p>
                <div class="absolute -bottom-2 left-1/4 w-1/2 h-0.5 bg-amber-400 rounded-full transform -translate-x-1/4"></div>
            </div>

            <!-- Animated CTA button -->
            <div class="pt-4">
                <a href="{{ route('catalog') }}"
                   class="inline-flex items-center justify-center bg-amber-500 hover:bg-amber-400 text-black font-bold px-8 py-4 rounded-full shadow-lg transform transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 group">
                    <span>@lang('messages.banner_button')</span>
                    <svg class="ml-2 w-5 h-5 transition-transform duration-300 group-hover:translate-x-1"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Scrolling indicator -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 animate-bounce">
        <div class="w-6 h-10 rounded-full border-2 border-white flex justify-center">
            <div class="w-1 h-3 bg-white rounded-full mt-2 animate-scroll"></div>
        </div>
    </div>
</section>
