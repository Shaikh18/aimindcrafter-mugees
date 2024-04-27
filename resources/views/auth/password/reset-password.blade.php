@extends('layouts.auth')

@section('content')
<div class="container-fluid justify-content-center">
    <div class="row h-100vh align-items-center login-background">
        <div class="col-md-6 col-sm-12 h-100" id="login-responsive">                
            <div class="card-body pr-10 pl-10 pt-10">

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

                <h3 class="text-center font-weight-bold mb-8">{{__('Welcome to')}} <span class="text-info">{{ config('app.name') }}</span></h3>

                @if ($message = Session::get('success'))
                    <div class="alert alert-login alert-success"> 
                        <strong><i class="fa fa-check-circle"></i> {{ $message }}</strong>
                    </div>
                    @endif

                    @if ($message = Session::get('error'))
                    <div class="alert alert-login alert-danger">
                        <strong><i class="fa fa-exclamation-triangle"></i> {{ $message }}</strong>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf       

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    
                    <div class="divider">
                        <div class="divider-text text-muted">
                            <small>{{__('Provide a New Password')}}</small>
                        </div>
                    </div>

                    <div class="input-box">                             
                        <label for="email" class="fs-12 font-weight-bold text-md-right">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off"  placeholder="Email Address" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror                            
                    </div>

                    <div class="input-box">                            
                        <label for="password" class="fs-12 font-weight-bold text-md-right">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror                            
                    </div>

                    <div class="input-box">
                        <label for="password-confirm" class="fs-12 font-weight-bold text-md-right">{{ __('Confirm Password') }}</label>                       
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="off" placeholder="Confirm Password">                        
                    </div>
                    
                    <div class="form-group mb-0 text-center">                        
                        <button type="submit" class="btn btn-primary mr-2">{{ __('Reset Password') }}</button>                                                     
                    </div>

                </form>
            </div>       
        </div>
        
        <div class="col-md-6 col-sm-12 text-center background-special h-100 align-middle p-0" id="login-background">
            <div class="login-bg">
                <img src="{{ URL::asset('img/frontend/backgrounds/login.webp') }}" alt="">
            </div>
        </div>
    </div>
</div>
@endsection
