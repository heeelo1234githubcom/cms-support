
<ul>
    @foreach($subs as $sub)
        <li><a href="{{ $sub->url }}">{{ $sub->title }}</a></li>
    @endforeach
</ul>
