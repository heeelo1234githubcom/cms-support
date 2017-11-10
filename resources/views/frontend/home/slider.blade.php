<?php
/* @var $slides \Illuminate\Support\Collection|\App\Models\Slide[] */
?>
@if ($slides->count())
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="nivo-slider">
                <div class="slider-wrapper theme-default">
                    <div id="nivoSlider" class="nivoSlider">

                        @foreach($slides as $slide)
                            <img src="{{ $slide->path }}" data-thumb="{{ $slide->path }}" alt="{{ $slide->title }}" />
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
