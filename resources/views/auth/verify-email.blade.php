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
                
                <form method="POST" action="{{ route('verification.send') }}" id="verify-email">
                    @csrf                      

                    <div class="mb-6 fs-14 text-center">
                        {{ __('Thank you for signing up with us! Before getting started, please verify your email address by typing the verification code we just emailed to you below.') }}
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-12 col-sm-12 text-center">									
                            <div class="input-box">								
                                <h6>{{ __('Email Verification Code') }}</h6>
                                <div class="form-group">							    
                                    <input type="text" class="form-control @error('verificationcode') is-danger @enderror" id="verificationcode" name="verificationcode" placeholder="{{ __('Enter your confirmation code here') }}" value="{{ old('verificationcode') }}" autocomplete="off">
                                    @error('verificationcode')
                                        <p class="text-danger">{{ $errors->first('verificationcode') }}</p>
                                    @enderror
                                </div> 
                                <button type="button" id="verify" class="btn btn-primary ripple pl-6 pr-6 fs-11 mt-2" style="text-transform: none;">{{ __('Verify') }}</button>
                            </div> 
                        </div>	
                    </div>

                    <div class="mb-4 mt-5 fs-14 text-center">
                        {{ __('If you did not receive the email, we will gladly send you another one.') }}
                    </div>

                    <div class="form-group mb-0 text-center">                        
                        <button type="submit" class="btn btn-primary ripple pl-6 pr-6 fs-11" style="text-transform: none;">{{ __('Resend Email Verification Code') }}</button>                                                                         
                    </div>
                
                </form>
                
                <div class="text-center">
                    <p class="fs-10 text-muted mt-2">or <a class="text-info" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a></p> 
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>

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

@section('js')
	<script type="text/javascript">
        let loading = `<span class="loading">
					<span style="background-color: #fff;"></span>
					<span style="background-color: #fff;"></span>
					<span style="background-color: #fff;"></span>
					</span>`;

		$('#verify').on('click',function(e) {

            if(document.getElementById("verificationcode").value == '') {
                toastr.warning('{{ __('Please include your verification code first') }}');
                document.getElementById("verificationcode").classList.add('is-invalid');
                return;
            } else {
                let code = document.getElementById("verificationcode").value;
                code = code.trim();

                $.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: "POST",
				url: '/verify-email/confirm',
				data: {'verificationcode': code},
                beforeSend: function() {
					$('#verify').prop('disabled', true);
					let btn = document.getElementById('verify');					
					btn.innerHTML = loading;  
					document.querySelector('#loader-line')?.classList?.remove('opacity-on');         
				},
				success: function(data) {

					if (data['status'] == 'error') {
						toastr.error(data['message']);
                        $('#verify').prop('disabled', false);
						let btn = document.getElementById('verify');					
						btn.innerHTML = '{{ __('Verify') }}';
						document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
					} else {
                        $('#verify').prop('disabled', false);
						let btn = document.getElementById('verify');					
						btn.innerHTML = '{{ __('Verify') }}';
						document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
                        toastr.success('{{ __('Redirecting to your dashboard') }}');
                        window.location.replace('{{ url('user/dashboard') }}');
                    }

				},
				error: function(data) {
					toastr.error('{{ __('There was an issue with email verification, please contact support team') }}');
				}
			}).done(function(data) {})
            }

			
		});
	</script>
@endsection
