<?php $__env->startSection('content'); ?>

    <?php
    $slide = config('webConfigs.home_slide');
    ?>

    <?php if($slide == 'default'): ?>

        <?php echo $__env->make('frontend.home.default_slider', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php elseif($slide == 'slide'): ?>

        <?php echo app('slide')->getHomeSlides(); ?>


    <?php endif; ?>

    <?php echo listHomeServices(); ?>


    <div class="container">
        <div class="row">
            <hr class="tall">
        </div>
    </div>

    <div class="container m-t-20">

        <div class="row">
            <div class="col-md-12">

                <?php echo $content; ?>


            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>

    <?php if($slide == 'default'): ?>

        <script src="/assets/frontend/js/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
        <script src="/assets/frontend/js/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
        <script src="/assets/frontend/js/circle-flip-slideshow/js/jquery.flipshow.min.js"></script>

    <?php elseif($slide == 'slide'): ?>

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

    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>