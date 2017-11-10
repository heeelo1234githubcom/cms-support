@extends('frontend.layouts.main_content')

@section('main_content')

    <div class="blog-posts">

        <h2>{{ $title }}</h2>

        @foreach($subs as $sub)
            <article class="post post-large @if ($loop->last) no-border @endif">
                <div class="post-content">

                    <h2 class="font-size-medium"><a href="{{ route('service_detail', ['slug' => $sub->slug]) }}">&raquo; {{ $sub->title }}</a></h2>
                    <p class="line-height-27">{{ $sub->getDescription() }}</p>

                </div>
            </article>
        @endforeach

    </div>

@stop