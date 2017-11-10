
<ul class="dropdown-menu">
    @foreach($subs as $sub)

        <li><a href="{{ $sub->getUrl() }}">{{ $sub->title }}</a></li>

    @endforeach
</ul>