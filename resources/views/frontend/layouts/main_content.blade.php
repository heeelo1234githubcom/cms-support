@extends('frontend.layouts.main')

@section('content')

    <section class="page-header page-header-color page-header-primary">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="{{ route('home_page') }}">Trang chủ</a></li>
                        @if (isset($breadcrumb))
                            <li>{!! $breadcrumb !!}</li>
                        @endif
                        <li class="active">{{ $title }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <div class="col-md-9">

                @yield('main_content')

            </div>

            @include('frontend.layouts.components.side_bar')

        </div>
    </div>

@stop