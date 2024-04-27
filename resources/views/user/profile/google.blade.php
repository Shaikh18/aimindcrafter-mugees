@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('2FA Authentication') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-id-badge mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{route('user.profile')}}"> {{ __('My Profile') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('2FA Authentication') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<!-- USER PROFILE PAGE -->
	<div class="row">

		<div class="col-xl-3 col-lg-3 col-md-12">
			<div class="card border-0" id="dashboard-background">
				<div class="widget-user-image overflow-hidden mx-auto mt-5"><img alt="User Avatar" class="rounded-circle" src="@if(auth()->user()->profile_photo_path){{ asset(auth()->user()->profile_photo_path) }} @else {{ URL::asset('img/users/avatar.jpg') }} @endif"></div>
				<div class="card-body text-center">
					<div>
						<h4 class="mb-1 mt-1 font-weight-bold text-primary fs-16">{{ auth()->user()->name }}</h4>
						<h6 class="font-weight-bold fs-12">{{ auth()->user()->job_role }}</h6>
					</div>
				</div>
				<div class="card-footer p-0">								
					<div class="row text-center pt-4 pb-4">
						<div class="col-sm">
							<h4 class="mb-3 mt-1 font-weight-800 text-primary fs-16">@if (auth()->user()->available_words == -1) {{ __('Unlimited') }} @else {{ App\Services\HelperService::userAvailableWords() }} @endif</h4>
							<h6 class="fs-12">{{ __('Words Left') }}</h6>
						</div>
						@role('user|subscriber|admin')
							@if (config('settings.image_feature_user') == 'allow')
								<div class="col-sm">
									<h4 class="mb-3 mt-1 font-weight-800 text-primary fs-16">@if (auth()->user()->available_dalle_images == -1) {{ __('Unlimited') }} @else {{ App\Services\HelperService::userAvailableImages() }} @endif</h4>
									<h6 class="fs-12">{{ __('DE/SD Images Left') }}</h6>
								</div>
							@endif
						@endrole
					</div>
					@if (config('settings.voiceover_feature_user') == 'allow' || config('settings.whisper_feature_user') == 'allow')
						<div class="row text-center pb-4">
							@role('user|subscriber|admin')
								@if (config('settings.voiceover_feature_user') == 'allow')
									<div class="col-sm">
										<h4 class="mb-3 mt-1 font-weight-800 text-primary fs-16">@if (auth()->user()->available_chars == -1) {{ __('Unlimited') }} @else {{ App\Services\HelperService::userAvailableChars() }} @endif</h4>
										<h6 class="fs-12">{{ __('Characters Left') }}</h6>
									</div>
								@endif
							@endrole
							@role('user|subscriber|admin')
								@if (config('settings.whisper_feature_user') == 'allow')
									<div class="col-sm">
										<h4 class="mb-3 mt-1 font-weight-800 text-primary fs-16">@if (auth()->user()->available_minutes == -1) {{ __('Unlimited') }} @else {{ App\Services\HelperService::userAvailableMinutes() }} @endif</h4>
										<h6 class="fs-12">{{ __('Minutes Left') }}</h6>
									</div>
								@endif
							@endrole
						</div>
					@endif															
				</div>
				<div class="card-footer p-0">
					<div class="row" id="profile-pages">
						<div class="col-sm-12">
							<div class="text-center pt-4">
								<a href="{{ route('user.profile') }}" class="fs-13"><i class="fa fa-user-shield mr-1"></i> {{ __('View Profile') }}</a>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="text-center pt-3">
								<a href="{{ route('user.profile.defaults') }}" class="fs-13"><i class="fa-sharp fa-solid fa-sliders mr-1"></i> {{ __('Set Defaults') }}</a>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="text-center p-3 ">
								<a href="{{ route('user.security') }}" class="fs-13"><i class="fa fa-lock-hashtag mr-1"></i> {{ __('Change Password') }}</a>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="text-center pb-4">
								<a href="{{ route('user.security.2fa') }}" class="fs-13 text-primary"><i class="fa fa-shield-check mr-1"></i> {{ __('2FA Authentication') }}</a>
							</div>
						</div>
						@if (auth()->user()->group == 'user' || auth()->user()->group == 'admin')
							@if (config('settings.personal_openai_api') == 'allow' || config('settings.personal_sd_api') == 'allow')
								<div class="col-sm-12">
									<div class="text-center pb-3">
										<a href="{{ route('user.profile.api') }}" class="fs-13"><i class="fa-solid fa-key mr-1"></i> {{ __('Personal API Keys') }}</a>
									</div>
								</div>
							@endif
						@elseif (!is_null(auth()->user()->plan_id))
							@if ($check_api_feature->personal_openai_api || $check_api_feature->personal_sd_api)
								<div class="col-sm-12">
									<div class="text-center pb-3">
										<a href="{{ route('user.profile.api') }}" class="fs-13"><i class="fa-solid fa-key mr-1"></i> {{ __('Personal API Keys') }}</a>
									</div>
								</div>
							@endif
						@endif	
						<div class="col-sm-12">
							<div class="text-center pb-4">
								<a href="{{ route('user.profile.delete') }}" class="fs-13"><i class="fa fa-user-xmark mr-1"></i> {{ __('Delete Account') }}</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-9 col-lg-9 col-md-12">
			@if (auth()->user()->google2fa_enabled == false)
				<div class="card border-0">
					<div class="card-header">
						<h3 class="card-title"><i class="fa-solid fa-shield-check mr-2 text-success"></i>{{ __('Activate 2FA Authentication') }}</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-6 col-md-12 col-sm-12">

								<h6 class="fs-12 font-weight-bold mb-4">{{ __('In order to enable enhanced security measures, setup Google Two Factor Authentication for Login.') }}</span></h6>

								<p class="fs-12">{{ __('Scan the QR code below or use setup key on your Google Authenticator app to add your account.') }}</p>

								@foreach ($errors->all() as $error)
									<p class="text-danger">{{ $error }}</p>
									@endforeach 
									
								<div class="text-center w-100">
									{!! $qr_code !!}
								</div>
									
								<div class="input-box">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold">{{ __('Setup Key') }}</label>
										<input type="text" class="form-control" autocomplete="off" value="{{ $google_data }}" readonly>
									</div>
								</div>

								<p class="fs-12">{{ __('Google Authenticator is a multifactor authentication application for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator app on your mobile device.') }}</p>
								
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">
								<form method="POST" action="{{ route('user.security.2fa.activate') }}" enctype="multipart/form-data">

									@csrf
			
									<div class="input-box">
										<div class="form-group">
											<label class="form-label fs-12 font-weight-bold">{{ __('Enter Google Authenticator OTP') }}<span class="text-required"><i class="fa-solid fa-asterisk"></i></span></label>
											<input type="text" name="key" class="form-control @error('key') is-invalid @enderror" autocomplete="off" maxlength="6" required>
											@error('key')
												<span class="invalid-feedback" role="alert">
													{{ $message }}
												</span>
											@enderror  
										</div>
									</div>

									<div class="card-footer border-0 p-0 text-center">
										<button type="submit" class="btn btn-primary pl-6 pr-6">{{ __('Activate') }}</button>							
									</div>	
								</form>	
							</div>
						</div>				
					</div>				
				</div>
			@elseif (auth()->user()->google2fa_enabled == true)
				<div class="card border-0">
					<div class="card-header">
						<h3 class="card-title"><i class="fa-solid fa-shield-check mr-2 text-danger"></i>{{ __('Deactivate 2FA Authentication') }}</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<form method="POST" action="{{ route('user.security.2fa.deactivate') }}" enctype="multipart/form-data">

									@csrf
			
									<div class="input-box">
										<div class="form-group">
											<label class="form-label fs-12 font-weight-bold">{{ __('Enter Google Authenticator OTP') }}<span class="text-required"><i class="fa-solid fa-asterisk"></i></span></label>
											<input type="text" name="key" class="form-control @error('key') is-invalid @enderror" autocomplete="off" maxlength="6" required>
											@error('key')
												<span class="invalid-feedback" role="alert">
													{{ $message }}
												</span>
											@enderror  
										</div>
									</div>

									<div class="card-footer border-0 p-0 text-center mb-3">
										<button type="submit" class="btn btn-primary pl-6 pr-6">{{ __('Deactivate') }}</button>							
									</div>	
								</form>	
							</div>
						</div>				
					</div>				
				</div>
			@endif
		</div>
	</div>
	<!-- END USER PROFILE PAGE -->
@endsection

