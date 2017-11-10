
@if ($promotions->count())
<div class="tabs mb-xlg">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab"><i class="fa fa-star"></i> Khuyến mãi</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <ul class="simple-post-list">

                @foreach($promotions as $promotion)
                <li>
                    <div class="post-info">
                        <a href="{{ route('promotion_detail', ['slug' => $promotion->slug]) }}">{{ $promotion->title }}</a>
                    </div>
                </li>
                @endforeach

            </ul>
        </div>
    </div>
</div>
@endif
