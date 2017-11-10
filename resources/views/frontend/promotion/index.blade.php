@extends('frontend.layouts.main_content')

@section('main_content')

    <div class="blog-posts">

        <h2>{{ $title }}</h2>

        @foreach($promotions as $promotion)
            <article class="post post-large @if ($loop->last) no-border @endif">
                <div class="post-content">

                    <h2 class="font-size-medium"><a href="{{ route('promotion_detail', ['slug' => $promotion->slug]) }}">&raquo; {{ $promotion->title }}</a></h2>
                    <p class="line-height-27">{{ $promotion->getDescription() }}</p>

                </div>
            </article>
        @endforeach

        {{ $promotions->links() }}

    </div>

@stop