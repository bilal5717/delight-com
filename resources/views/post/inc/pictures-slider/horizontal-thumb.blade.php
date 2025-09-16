<div class="posts-image">
   
    <?php 
    // Fetch the post directly from the database with all necessary relationships
    $post = \App\Models\Post::with(['category', 'pictures'])
        ->where('id', $post->id)
        ->first();
    
    $titleSlug = \Illuminate\Support\Str::slug($post->title);
    ?>

        @php
            // Refresh the booking status directly from database
            $bookingStatus = \App\Models\Post::where('id', $post->id)
                ->value('booking_required');
        @endphp
        @if ($bookingStatus == '1')   
            <h1 class="pricetag bookNowTag">
                {!! t('available_for_booking') !!}
            </h1>
        @endif
    @if (!in_array($post->category->type, ['not-salable']))
        <h1 class="pricetag">
            @php
                $price = is_numeric($post->price) ? $post->price : null;
            @endphp
            @if ($price > 0)
                {!! \App\Helpers\Number::money($price) !!}
            @elseif ($price === 0)
                {!! t('free_as_price') !!}
            @else
                {!! \App\Helpers\Number::money(' --') !!}
            @endif
        </h1>
    @endif
   
    @if ($post->pictures->isNotEmpty())
        <ul class="bxslider">
            @foreach($post->pictures as $key => $image)
                <li class="slide-item">
                    <img src="{{ imgUrl($image->filename, 'big') }}" alt="{{ $titleSlug . '-big-' . $key }}">
                </li>
            @endforeach
        </ul>
        <div class="product-view-thumb-wrapper">
            <ul id="bx-pager" class="product-view-thumb">
                @foreach($post->pictures as $key => $image)
                    <li>
                        <a class="thumb-item-link" data-slide-index="{{ $key }}" href="#" data-big-image="{{ imgUrl($image->filename, 'big') }}">
                            <img src="{{ imgUrl($image->filename, 'small') }}" alt="{{ $titleSlug . '-small-' . $key }}">
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        @php $defaultImage = imgUrl(config('larapen.core.picture.default'), 'big'); @endphp
        <ul class="bxslider">
            <li class="slide-item">
                <img src="{{ $defaultImage }}" alt="img">
            </li>
        </ul>
        <div class="product-view-thumb-wrapper">
            <ul id="bx-pager" class="product-view-thumb">
                <li>
                    <a class="thumb-item-link" data-slide-index="0" href="#" data-big-image="{{ $defaultImage }}">
                        <img src="{{ imgUrl(config('larapen.core.picture.default'), 'small') }}" alt="img">
                    </a>
                </li>
            </ul>
        </div>
    @endif
	@if ($post->pictures->count() > 1)
            <!-- Custom buttons -->
            <div class="controls-direction-btns">
                <button id="prev-slide" class="slider-btn"></button>
                <button id="next-slide" class="slider-btn"></button>
            </div>
    @endif
    
</div>




@section('after_scripts')
    @parent
    <script>
        $(document).ready(function () {
			var $slides = $('.bxslider .slide-item');
            var totalSlides = $slides.length;
            var currentIndex = 0;
            var mainSlider = $('.bxslider').bxSlider({
                touchEnabled: {{ ($post->pictures->count() > 1) ? 'true' : 'false' }},
                speed: 300,
                pagerCustom: '#bx-pager',
                adaptiveHeight: true,
                nextText: '{{ t('bxslider.nextText') }}',
                prevText: '{{ t('bxslider.prevText') }}',
                startText: '{{ t('bxslider.startText') }}',
                stopText: '{{ t('bxslider.stopText') }}',
                onSlideAfter: function ($slideElement, oldIndex, newIndex) {
                    @if (!userBrowser('Chrome'))
                        $('#bx-pager li:not(.bx-clone)').eq(newIndex).find('a.thumb-item-link').addClass('active');
                    @endif
                }
            });

            @if (userBrowser('Chrome'))
                $('#bx-pager').addClass('m-3');
                $('#bx-pager .thumb-item-link').unwrap();
            @else
                var thumbSlider = $('.product-view-thumb').bxSlider(bxSliderSettings());
                $(window).on('resize', function() {
                    thumbSlider.reloadSlider(bxSliderSettings());
                });
            @endif

			  function showSlide(index) {
                $slides.hide();
                $slides.eq(index).fadeIn();
                $('#bx-pager a').removeClass('active');
                $('#bx-pager a').eq(index).addClass('active');
            }

			function goToNextSlide() {
                currentIndex = (currentIndex + 1) % totalSlides;
                showSlide(currentIndex);
            }

            function goToPrevSlide() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                showSlide(currentIndex);
            }
			function goToSlide(index) {
                currentIndex = index;
                showSlide(currentIndex);
            }
			$('#next-slide').on('click', function (e) {
                e.preventDefault();
                goToNextSlide();
            });

            $('#prev-slide').on('click', function (e) {
                e.preventDefault();
                goToPrevSlide();
            });
            $('.thumb-item-link').hover(function() {
                var index = $(this).data('slide-index');
                goToSlide(index);
            });
			showSlide(currentIndex);
          $(document).ready(function(){
                $(".slide-item , .controls-direction-btns").hover(function(){
                    $('.slider-btn').show();
                    }, function(){
                    $('.slider-btn').hide();
                });
            });
            
            function bxSliderSettings() {
                var smSettings = {
                    slideWidth: 65,
                    minSlides: 1,
                    maxSlides: 4,
                    slideMargin: 5,
                    adaptiveHeight: true,
                    pager: false
                };
                var mdSettings = {
                    slideWidth: 100,
                    minSlides: 1,
                    maxSlides: 4,
                    slideMargin: 5,
                    adaptiveHeight: true,
                    pager: false
                };
                var lgSettings = {
                    slideWidth: 100,
                    minSlides: 3,
                    maxSlides: 6,
                    pager: false,
                    slideMargin: 10,
                    adaptiveHeight: true
                };

                if ($(window).width() <= 640) {
                    return smSettings;
                } else if ($(window).width() > 640 && $(window).width() < 768) {
                    return mdSettings;
                } else {
                    return lgSettings;
                }
            }
        });
    </script>
@endsection
