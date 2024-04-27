@extends('layouts.frontend')

@section('css')
    <link href="{{URL::asset('plugins/slick/slick.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('plugins/slick/slick-theme.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('plugins/aos/aos.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('plugins/animatedheadline/jquery.animatedheadline.css')}}" rel="stylesheet" />
@endsection

@section('menu')
    <div class="row no-gutters">
        <nav class="navbar navbar-expand-lg navbar-light w-100" id="navbar-responsive" style="background: white;">
            <a class="navbar-brand" href="{{ url('/') }}"><img id="brand-img"  src="{{ URL::asset('img/brand/logo.png') }}" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse section-links" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ url('/') }}">{{ __('Home') }} <span class="sr-only">(current)</span></a>
                    </li>
                    @php
                        $page_builders = \App\Models\PageBuilder::where('position', 'header')->get();
                    @endphp
                    @if(count($page_builders) > 0)
                        @foreach($page_builders as $page_builder)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->url() === route('page-builder.show', $page_builder->slug) ? 'scroll active' : 'text-dark' }}"
                                   href="{{ route('page-builder.show', $page_builder->slug) }}">
                                    {{ $page_builder->title }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
                @if (Route::has('login'))
                    <div id="login-buttons" class="pr-4">
                        <div class="dropdown header-locale" id="frontend-local">
                            <a class="icon" data-bs-toggle="dropdown">
                            <span class="fs-12 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000" fill="none">
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                    <path d="M3.6 9h16.8"></path>
                                    <path d="M3.6 15h16.8"></path>
                                    <path d="M11.5 3a17 17 0 0 0 0 18"></path>
                                    <path d="M12.5 3a17 17 0 0 1 0 18"></path>
                                    </svg>
                                {{ ucfirst(Config::get('locale')[App::getLocale()]['code']) }}</span>
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
                            <a href="{{ route('user.dashboard') }}" class="action-button dashboard-button pl-5 pr-5">{{ __('Dashboard') }}</a>
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
            </div>
        </nav>
    </div>
@endsection

@section('content')
    <div style="margin-top: 4%">
        {!! $htmlContent !!}
    </div>
@endsection

@section('js')
    <script src="{{URL::asset('plugins/slick/slick.min.js')}}"></script>
    <script src="{{URL::asset('plugins/aos/aos.js')}}"></script>
    <script src="{{URL::asset('plugins/animatedheadline/jquery.animatedheadline.min.js')}}"></script>
    <script src="{{URL::asset('js/frontend.js')}}"></script>
    <script type="text/javascript">
        $(function () {

            $('.word-container').animatedHeadline({
                animationType: "slide",
                animationDelay: 2500,
                barAnimationDelay: 3800,
                barWaiting: 800,
                lettersDelay: 50,
                typeLettersDelay: 150,
                selectionDuration: 500,
                typeAnimationDelay: 1300,
                revealDuration: 600,
                revealAnimationDelay: 1500
            });

            AOS.init();

        });
    </script>
@endsection
