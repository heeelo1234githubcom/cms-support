@extends('frontend.layouts.main_content')

@section('main_content')

    <div class="blog-posts single-post">

        <article class="post post-large blog-single-post">

            <div class="post-content">

                <h2>{{ $promotion->title }}</h2>

                <div class="line-height-27">
                    {!! $promotion->content !!}
                </div>

                @if (config('webConfigs.facebook_app_id') || config('webConfigs.social_url'))

                    <div class="post-block post-share">
                        <div class="fb-comments" data-href="{{ URL::current() }}" width="100%" data-numposts="10"></div>
                    </div>

                    <div class="fb-share-button right" data-href="{{ URL::current() }}" data-layout="button_count" data-size="large" data-mobile-iframe="false">
                        <a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(URL::current()) }}">Share</a>
                    </div>

                @endif

            </div>

        </article>

    </div>

@stop