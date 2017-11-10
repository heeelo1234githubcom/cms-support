<?php
/* @var \Illuminate\Support\Collection|\App\Models\Menu[] $subs */
$total = $subs->count();
$columns = 3;
$itemPerColumn = (int) ceil($total / 3);
if ($itemPerColumn < 3) {
    $itemPerColumn = 3;
}
?>
<ul class="dropdown-menu">
    <li>
        <div class="dropdown-mega-content">
            <div class="row">

                <div class="col-md-{{ $columns }}">
                    <ul class="dropdown-mega-sub-nav">
                        @foreach($subs as $sub)
                            @if (0 === ($loop->iteration % $itemPerColumn) && $total != $itemPerColumn)
                                    </ul>
                                </div>
                                <div class="col-md-{{ $columns }}">
                                    <ul class="dropdown-mega-sub-nav">
                            @endif

                            <li><a href="{{ $sub->getUrl() }}">{{ $sub->title }}</a></li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </li>
</ul>