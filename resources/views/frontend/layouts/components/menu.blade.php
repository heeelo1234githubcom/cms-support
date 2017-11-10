<?php
/* @var \Illuminate\Support\Collection|\App\Models\Menu[] $menu */
/* @var \App\Models\Menu $item */
?>

<div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 collapse">
    <nav>
        <ul class="nav nav-pills" id="mainNav">

            <li>
                <a href="{{ route('home_page') }}">
                    Trang chủ
                </a>
            </li>

            @if ($menu->count())
                @foreach($menu as $item)

                    <?php $hasSubs = !!($item->subs->count()); ?>

                    <li class="{{ $item->getLiClasses($hasSubs) }}">
                        <a class="{{ $item->dropDownClass($hasSubs) }}" href="{{ $item->getUrl() }}">
                            {{ $item->title }}
                        </a>

                        @if ($hasSubs)
                            {!! $item->getSubMenu() !!}
                        @endif
                    </li>

                @endforeach
            @endif

            <li>
                <a href="{{ route('promotion_page') }}">
                    Khuyến mãi
                </a>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle">
                    Hình ảnh & Video
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('photo_page') }}"><i class="fa fa-file-image-o"></i> Hình ảnh</a></li>
                    <li><a href="{{ route('video_page') }}"><i class="fa fa-file-video-o"></i> Video</a></li>
                </ul>
            </li>

            <li>
                <a href="{{ route('contact_page') }}">
                    Liên hệ
                </a>
            </li>

        </ul>
    </nav>
</div>
