<?php
/* @var $services \Illuminate\Support\Collection|\App\Models\Service[] */
?>
@if ($services->count())

<div class="container m-t-20">

    <h2 class="text-primary m-b-10">Dịch vụ Nha Khoa</h2>

    <div class="row">
        <ul class="blog-list sort-destination p-none">

            @foreach($services as $service)

                <li class="col-sm-4 col-md-4 p-md isotope-item">
                    <div class="blog-item">
                        <a href="{{ route('service_detail', ['slug' => $service->slug]) }}" class="text-decoration-none">
                            <span class="thumb-info thumb-info-lighten m-b-10">
                                <span class="thumb-info-wrapper m-none">
                                    <img data-plugin-lazyload data-plugin-options="{'effect' : 'fadeIn'}" data-original="{{ $service->getPublicCover() }}" class="img-responsive" alt="{{ $service->title }}">
                                </span>
                            </span>
                            <span class="blog-item-content">
                                <span class="category text-uppercase font-weight-semibold">{{ $service->title }}</span>
                                {{--<p class="mb-xlg">{{ $service->getDescription() }}</p>--}}
                            </span>
                        </a>
                    </div>
                </li>

                @if (($loop->iteration % 3) == 0 && ($loop->iteration != $services->count()))
                    </ul>
                    <ul class="blog-list sort-destination p-none">
                @endif

            @endforeach

        </ul>

    </div>
</div>
@endif
