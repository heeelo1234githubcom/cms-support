
@if ($menu->count())
    @if ($title)<h4 class="heading-primary">{{ $title }}</h4>@endif
    <ul class="nav nav-list mb-xlg">
        @foreach($menu as $item)
        <li>
            <a href="{{ $item->url }}">{{ $item->title }}</a>

            {!! $item->getSubSideBarMenu() !!}

        </li>
        @endforeach
    </ul>
@endif
