<div class="col-md-3">
    <aside class="sidebar">

        {!! app('menu')->getSideBarMenu() !!}

        {!! app('promotion')->getSideBarPromotion() !!}

        @if (config('webConfigs.social_url'))

            <div class="fb-page" data-href="{{ config('webConfigs.social_url') }}" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="{{ config('webConfigs.social_url') }}" class="fb-xfbml-parse-ignore">
                    <a href="{{ config('webConfigs.social_url') }}">Facebook</a>
                </blockquote>
            </div>

        @endif

    </aside>
</div>