<header id="header" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyStartAt': 57, 'stickySetTop': '-57px', 'stickyChangeLogo': true}">
    <div class="header-body">
        <div class="header-container container">
            <div class="header-row">

                <div class="header-column">
                    <div class="header-logo">
                        <a href="{{ route('home_page') }}">
                            <img alt="Porto" width="75" height="80" data-sticky-width="56" data-sticky-height="60" data-sticky-top="40" src="{{ img('logo.jpg') }}">
                        </a>
                    </div>
                </div>

                <div class="header-column">

                    <div class="header-row">
                        <nav class="header-nav-top">
                            <ul class="nav nav-pills">

                                @if (config('webConfigs.intro_id'))
                                <li class="hidden-xs">
                                    <a href="{{ getIntroUrl() }}"><i class="fa fa-angle-right"></i> Giới thiệu</a>
                                </li>
                                @endif

                                <li class="hidden-xs">
                                    <a href="{{ route('contact_page') }}"><i class="fa fa-angle-right"></i> Liên hệ</a>
                                </li>

                                @if (config('webConfigs.hotline_number'))
                                <li>
                                    <span class="ws-nowrap"><i class="fa fa-phone"></i> Hỗ trợ 24 / 7 - {{ config('webConfigs.hotline_number') }}</span>
                                </li>
                                @endif
                            </ul>
                        </nav>
                    </div>

                    <div class="header-row">
                        <div class="header-nav">

                            <button class="btn header-btn-collapse-nav" data-toggle="collapse" data-target=".header-nav-main">
                                <i class="fa fa-bars"></i>
                            </button>

                            @if (config('webConfigs.social_url'))
                            <ul class="header-social-icons social-icons hidden-xs">
                                <li class="social-icons-facebook"><a href="{{ config('webConfigs.social_url') }}" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                            </ul>
                            @endif

                            {!! getMenu() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>