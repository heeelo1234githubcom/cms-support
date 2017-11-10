@extends('frontend.layouts.main')

@section('content')

    <?php
    $slide = config('webConfigs.home_slide');
    ?>

    @if ($slide == 'default')

        @include('frontend.home.default_slider')

    @elseif ($slide == 'slide')

        {!! app('slide')->getHomeSlides() !!}

    @endif

    {!! listHomeServices() !!}

    <div class="container">
        <div class="row">
            <hr class="tall">
        </div>
    </div>

    <div class="container m-t-20">

        <div class="row">
            <div class="col-md-12">

                {!! $content !!}

            </div>
        </div>

    </div>

@stop

@section('page-script')

    @if ($slide == 'default')

        <script src="/assets/frontend/js/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
        <script src="/assets/frontend/js/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
        <script src="/assets/frontend/js/circle-flip-slideshow/js/jquery.flipshow.min.js"></script>

    @elseif ($slide == 'slide')

        <script src="/assets/frontend/js/circle-flip-slideshow/js/jquery.flipshow.min.js"></script>
        <script src="/assets/frontend/js/nivo-slider/jquery.nivo.slider.min.js"></script>
        <script>
            (function($) {
                'use strict';
                /*
                 Nivo Slider
                 */
                if ($.isFunction($.fn.nivoSlider)) {
                    $('#nivoSlider').nivoSlider({
                        effect: 'random',
                        slices: 15,
                        boxCols: 8,
                        boxRows: 4,
                        animSpeed: 500,
                        pauseTime: 3000,
                        startSlide: 0,
                        directionNav: true,
                        controlNav: true,
                        controlNavThumbs: false,
                        pauseOnHover: true,
                        manualAdvance: false,
                        prevText: 'Prev',
                        nextText: 'Next',
                        randomStart: false,
                        beforeChange: function(){},
                        afterChange: function(){},
                        slideshowEnd: function(){},
                        lastSlide: function(){},
                        afterLoad: function(){}
                    });
                }
            }).apply(this, [jQuery]);
        </script>

    @endif

@stop