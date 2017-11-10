
@if ($services->count())
    <h4 class="heading-primary">Dịch vụ</h4>
    <ul class="nav nav-list mb-xlg">
        @foreach($services as $service)
        <li><a href="{{ route('service_detail', ['slug' => $service->slug]) }}">{{ $service->title }}</a></li>
        @endforeach
    </ul>
@endif
