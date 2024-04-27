@extends('layouts.frontend')

@section('menu')
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
                            <a class="nav-link scroll active" href="#main-wrapper">{{ __('Home') }} <span class="sr-only">(current)</span></a>
                        </li>	
                        @if (config('frontend.features_section') == 'on')
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#features-wrapper">{{ __('Features') }}</a>
                            </li>
                        @endif	
                        @if (config('frontend.pricing_section') == 'on')
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#prices-wrapper">{{ __('Pricing') }}</a>
                            </li>
                        @endif							
                        @if (config('frontend.faq_section') == 'on')
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#faq-wrapper">{{ __('FAQs') }}</a>
                            </li>
                        @endif	
                        @if (config('frontend.blogs_section') == 'on')
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#blog-wrapper">{{ __('Blogs') }}</a>
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
@endsection

@section('content')
    <div class="container-fluid secondary-background">
        <div class="row text-center">
            <div class="col-md-12">
                <div class="section-title">
                    <!-- SECTION TITLE -->
                    <div class="text-center mb-9 mt-9 pt-7" id="contact-row">

                        <h6 class="fs-30 mt-6 font-weight-bold text-center">{{ __('Terms and Conditions') }}</h6>
                        <p class="fs-12 text-center text-muted mb-5"><span>{{ __('We guarantee your data privacy') }}</p>

                    </div> <!-- END SECTION TITLE -->
                </div>
            </div>
        </div>
    </div>

    <section id="about-wrapper" class="secondary-background">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 policy">  
                    <div class="card border-0 p-4 pt-7 pb-7 mb-9 special-border-right special-border-left">              
                        <div class="card-body"> 

                            <div class="mb-7">
                                {!! $pages['terms'] !!}
                            </div>
        
                            <div class="form-group mt-6 text-center">                        
                                <a href="{{ route('register') }}" class="btn btn-primary mr-2">{{ __('I Agree, Sign me Up') }}</a> 
                                <a href="{{ route('login') }}" class="btn btn-primary mr-2">{{ __('I Agree, Let me Login') }}</a>                               
                            </div>
                        
                        </div>     
                    </div>  
                </div>
            </div>
        </div>
    </section>
    @section('js')
        <script src="{{URL::asset('js/minimize.js')}}"></script>
    @endsection
@endsection

@section('curve')
    <div class="container-fluid" id="curve-container">
        <div class="curve-box">
            <div class="overflow-hidden">
                <svg class="curve secodary-curve" preserveAspectRatio="none" width="1440" height="86" viewBox="0 0 1440 86" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 85.662C240 29.1253 480 0.857 720 0.857C960 0.857 1200 29.1253 1440 85.662V0H0V85.662Z"></path>
                </svg>
            </div>
        </div>
    </div>
@endsection

