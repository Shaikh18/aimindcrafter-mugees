@extends('layouts.auth')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    @if (config('frontend.maintenance') == 'on')			
        <div class="container h-100vh">
            <div class="row text-center h-100vh align-items-center">
                <div class="col-md-12">
                    <img src="{{ URL::asset('img/files/maintenance.png') }}" alt="Maintenance Image">
                    <h2 class="mt-4 font-weight-bold">{{ __('We are just tuning up a few things') }}.</h2>
                    <h5>{{ __('We apologize for the inconvenience but') }} <span class="font-weight-bold text-info">{{ config('app.name') }}</span> {{ __('is currenlty undergoing planned maintenance') }}.</h5>
                </div>
            </div>
        </div>
    @else
        @if (config('settings.registration') == 'enabled')
            <div class="container-fluid">                
                <div class="row login-background justify-content-center">

                    <div class="col-sm-12"> 
                        <div class="row justify-content-center subscribe-registration-background">
                            <div class="col-lg-8 col-md-12 col-sm-12 mx-auto">
                                <div class="card-body pt-8">

                                    <a class="navbar-brand register-logo" href="{{ url('/') }}"><img id="brand-img"  src="{{ URL::asset('img/brand/logo.png') }}" alt=""></a>
                            
                                    <div class="dropdown header-locale" id="frontend-local-login">
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
                                    
                                    <div class="registration-nav mb-8 mt-8">
                                        <div class="registration-nav-inner">					
                                            <div class="row text-center justify-content-center">
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="d-flex wizard-nav-text">
                                                        <div class="wizard-step-number current-step mr-3 fs-14" id="step-one-number">1</div>
                                                        <div class="wizard-step-title"><span class="font-weight-bold fs-14">{{ __('Create Account') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 1') }}</span></div>
                                                    </div>
                                                    <div>
                                                        <i class="fa-solid fa-chevrons-right wizard-nav-chevron" id="step-one-icon"></i>
                                                    </div>									
                                                </div>	
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="d-flex wizard-nav-text">
                                                        <div class="wizard-step-number mr-3 fs-14" id="step-two-number">2</div>
                                                        <div class="wizard-step-title responsive"><span class="font-weight-bold fs-14">{{ __('Select Your Plan') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 2') }}</span></div>
                                                    </div>	
                                                    <div>
                                                        <i class="fa-solid fa-chevrons-right wizard-nav-chevron" id="step-two-icon"></i>
                                                    </div>								
                                                </div>
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="d-flex wizard-nav-text">
                                                        <div class="wizard-step-number mr-3 fs-14" id="step-three-number">3</div>
                                                        <div class="wizard-step-title"><span class="font-weight-bold fs-14">{{ __('Payment') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 3') }}</span></div>
                                                    </div>								
                                                </div>
                                            </div>					
                                        </div>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('register.subscriber') }}" class="subscribe-first-step" id="registration-form" onsubmit="process()">
                                        @csrf                                
                                        
                                        <h3 class="text-center login-title mb-2">{{__('Create Your Account with')}} <span class="text-info"><a href="{{ url('/') }}">{{ config('app.name') }}</a></span></h3>
                                        <p class="fs-12 text-muted text-center mb-8">{{ __('Provide your personal information and click continue') }}</p>

                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <div class="input-box mb-4">                             
                                                    <label for="name" class="fs-12 font-weight-bold text-md-right">{{ __('First Name') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                    <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="off" autofocus placeholder="{{ __('Your First Names') }}" required>             
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror 
                                                                              
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-12">
                                                <div class="input-box mb-4">                             
                                                    <label for="name" class="fs-12 font-weight-bold text-md-right">{{ __('Last Name') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                    <input id="lastname" type="name" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" autocomplete="off" placeholder="{{ __('Your Last Names') }}" required>
                                                    @error('lastname')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror                            
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-12">
                                                <div class="input-box mb-4">                             
                                                    <label for="email" class="fs-12 font-weight-bold text-md-right">{{ __('Email Address') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off"  placeholder="{{ __('Email Address') }}" required>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror                        
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-12">
                                                <div class="input-box mb-4">                             
                                                    <label for="country" class="fs-12 font-weight-bold text-md-right">{{ __('Country') }}</label>
                                                    <select id="user-country" name="country" data-placeholder="{{ __('Select Your Country') }}" required>	
                                                        @foreach(config('countries') as $value)
                                                            <option value="{{ $value }}" @if(config('settings.default_country') == $value) selected @endif>{{ $value }}</option>
                                                        @endforeach										
                                                    </select>                         
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-12">
                                                <div class="input-box">                            
                                                    <label for="password-input" class="fs-12 font-weight-bold text-md-right">{{ __('Password') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                    <input id="password-input" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off" placeholder="{{ __('Password') }}">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror                        
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-12">
                                                <div class="input-box">
                                                    <label for="password-confirm" class="fs-12 font-weight-bold text-md-right">{{ __('Confirm Password') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>                       
                                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="off" placeholder="{{ __('Confirm Password') }}">                        
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mb-3">  
                                            <div class="d-flex">                        
                                                <label class="custom-switch">
                                                    <input type="checkbox" class="custom-switch-input" name="agreement" id="agreement" {{ old('remember') ? 'checked' : '' }} required>
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description fs-10 text-muted">{{__('By continuing, I agree with your')}} <a href="{{ route('terms') }}" class="text-info">{{__('Terms and Conditions')}}</a> {{__('and')}} <a href="{{ route('privacy') }}" class="text-info">{{__('Privacy Policies')}}</a></span>
                                                </label>   
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <div class="form-group mt-4">                        
                                                <button type="submit" class="btn btn-primary ripple font-weight-bold register-continue-button" id="continue">{{ __('Continue') }}</button>              
                                            </div>   
                                            <p class="fs-10 text-muted pt-3 mb-0">{{ __('Already have an account?') }}</p>
                                            <div class="text-center">
                                                <a href="{{ route('login') }}"  class="fs-12 font-weight-bold special-action-sign">{{ __('Sign In') }}</a>      
                                            </div>                                                                 
                                        </div>
                                    </form>
                                </div> 
                            </div>      
                        </div>
                    </div>
                </div>
            </div>
        @else
            <h5 class="text-center pt-9">{{__('New user registration is disabled currently')}}</h5>
        @endif
    @endif
@endsection

@section('js')
	<!-- Awselect JS -->
	<script src="{{URL::asset('plugins/awselect/awselect.min.js')}}"></script>
	<script src="{{URL::asset('js/awselect.js')}}"></script>
    <script type="text/javascript">
        let loading = `<span class="loading">
					<span style="background-color: #fff;"></span>
					<span style="background-color: #fff;"></span>
					<span style="background-color: #fff;"></span>
					</span>`;

        function process() {
            $('#continue').prop('disabled', true);
            let btn = document.getElementById('continue');					
            btn.innerHTML = loading;  
            document.querySelector('#loader-line')?.classList?.remove('hidden'); 
            return; 
        }

    </script>   
@endsection
