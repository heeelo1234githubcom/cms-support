@extends('frontend.layouts.main_content')

@section('main_content')

    <div class="blog-posts">

        <h2 class="m-b-15">{{ $title }}</h2>

        @if ($media->count())
        <div class="row">
            <ul class="blog-list sort-destination p-none">

                @foreach($media as $photo)

                    <li class="col-sm-4 col-md-4 p-md isotope-item">
                        <div class="blog-item">
                            <a href="{{ $photo->file }}" class="text-decoration-none" data-gallery="{{ $photo->album_id }}" data-toggle="lightbox" data-footer="{{ $photo->title }}">
                                <span class="thumb-info thumb-info-lighten m-b-10">
                                    <span class="thumb-info-wrapper m-none">
                                        <img data-plugin-lazyload data-plugin-options="{'effect' : 'fadeIn'}" data-original="{{ $photo->file }}" class="img-responsive" alt="{{ $photo->title }}">
                                    </span>
                                </span>
                            </a>
                            <span class="blog-item-content">
                                <span class="category">{{ $photo->title }}</span>
                            </span>
                        </div>
                    </li>

                    @if (($loop->iteration % 3) == 0 && ($loop->iteration != $media->count()))
                        </ul>
                        <ul class="blog-list sort-destination p-none">
                    @endif

                @endforeach

            </ul>
        </div>
        @endif

        @if ( !$media->count())
            Chưa có dữ liệu.
        @endif

    </div>

@stop