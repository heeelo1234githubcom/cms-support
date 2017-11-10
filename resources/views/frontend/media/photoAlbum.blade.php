@extends('frontend.layouts.main_content')

@section('main_content')

    <div class="blog-posts">

        <h2 class="m-b-15">{{ $title }}</h2>

        <div class="row">
            <ul class="blog-list sort-destination p-none">

                @foreach($albums as $album)

                    <li class="col-sm-4 col-md-4 p-md isotope-item">
                        <div class="blog-item">
                            <a href="{{ route('album_detail', ['slug' => $album->slug]) }}" class="text-decoration-none">
                            <span class="thumb-info thumb-info-lighten m-b-10">
                                <span class="thumb-info-wrapper m-none">
                                    <img data-plugin-lazyload data-plugin-options="{'effect' : 'fadeIn'}" data-original="{{ $album->getCover() }}" class="img-responsive" alt="{{ $album->title }}">
                                </span>
                            </span>
                                <span class="blog-item-content">
                                <span class="category text-uppercase font-weight-semibold">{{ $album->title }}</span>
                            </span>
                            </a>
                        </div>
                    </li>

                    @if (($loop->iteration % 3) == 0 && ($loop->iteration != $albums->count()))
                        </ul>
                        <ul class="blog-list sort-destination p-none">
                    @endif

                @endforeach

            </ul>

            {{ $albums->links() }}

        </div>

    </div>

@stop