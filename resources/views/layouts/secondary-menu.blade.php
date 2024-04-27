<div class="secondary-navbar">
    <div class="row no-gutters">
        <nav class="navbar navbar-expand-lg navbar-light w-100" id="navbar-responsive">
            <a class="navbar-brand" href="{{ url('/') }}"><img id="brand-img"  src="{{ URL::asset('img/brand/logo.png') }}" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse section-links" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link scroll active" href="{{ url('/') }}/#main-wrapper">{{ __('Home') }} <span class="sr-only">(current)</span></a>
                    </li>	
                    @if (config('frontend.features_section') == 'on')
                        <li class="nav-item">
                            <a class="nav-link scroll" href="{{ url('/') }}/#features-wrapper">{{ __('Features') }}</a>
                        </li>
                    @endif	
                    @if (config('frontend.pricing_section') == 'on')
                        <li class="nav-item">
                            <a class="nav-link scroll" href="{{ url('/') }}/#prices-wrapper">{{ __('Pricing') }}</a>
                        </li>
                    @endif							
                    @if (config('frontend.faq_section') == 'on')
                        <li class="nav-item">
                            <a class="nav-link scroll" href="{{ url('/') }}/#faq-wrapper">{{ __('FAQs') }}</a>
                        </li>
                    @endif	
                    @if (config('frontend.blogs_section') == 'on')
                        <li class="nav-item">
                            <a class="nav-link scroll" href="{{ url('/') }}/#blog-wrapper">{{ __('Blogs') }}</a>
                        </li>
                    @endif										
                </ul>                    
            </div>
            @if (Route::has('login'))
                    <div id="login-buttons">
                        <div class="dropdown header-locale" id="frontend-local">
                            <a class="icon" data-bs-toggle="dropdown">
                                <span class="fs-12 mr-4"><i class="fa-solid text-black fs-16 mr-2 fa-globe"></i>{{ ucfirst(Config::get('locale')[App::getLocale()]['code']) }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
                                <div class="local-menu">
                                    @foreach (Config::get('locale') as $lang => $language)
                                        @if ($lang != App::getLocale())
                                            <a href="{{ route('locale', $lang) }}" class="dropdown-item d-flex">
                                                <div class="text-info"><i class="flag flag-{{ $language['flag'] }} mr-3"></i></div>
                                                <div>
                                                    <span class="font-weight-normal fs-12">{{ $language['display'] }}</span>
                                                </div>
                                            </a>                                        
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @auth
                            <a href="{{ route('user.templates') }}" class="action-button dashboard-button pl-5 pr-5">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="" id="login-button">{{ __('Sign In') }}</a>

                            @if (config('settings.registration') == 'enabled')
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-2 action-button register-button pl-5 pr-5">{{ __('Sign Up') }}</a>
                                @endif
                            @endif
                        @endauth
                    </div>
                @endif
        </nav>
    </div>
</div>