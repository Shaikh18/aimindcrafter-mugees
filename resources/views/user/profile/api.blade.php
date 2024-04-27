@extends('layouts.app')

@section('css')
	<!-- Telephone Input CSS -->
	<link href="{{URL::asset('plugins/telephoneinput/telephoneinput.css')}}" rel="stylesheet" >
@endsection

@section('page-header')
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Personal API Keys') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-id-badge mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{route('user.profile')}}"> {{ __('My Profile') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Personal API Keys') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<!-- EDIT USER PROFILE PAGE -->
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-sm-12">
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
								<a href="{{ route('user.security.2fa') }}" class="fs-13"><i class="fa fa-shield-check mr-1"></i> {{ __('2FA Authentication') }}</a>
							</div>
						</div>
						@if (auth()->user()->group == 'user' || auth()->user()->group == 'admin')
							@if (config('settings.personal_openai_api') == 'allow' || config('settings.personal_sd_api') == 'allow')
								<div class="col-sm-12">
									<div class="text-center pb-3">
										<a href="{{ route('user.profile.api') }}" class="fs-13 text-primary"><i class="fa-solid fa-key mr-1"></i> {{ __('Personal API Keys') }}</a>
									</div>
								</div>
							@endif
						@elseif (!is_null(auth()->user()->plan_id))
							@if ($check_api_feature->personal_openai_api || $check_api_feature->personal_sd_api)
								<div class="col-sm-12">
									<div class="text-center pb-3">
										<a href="{{ route('user.profile.api') }}" class="fs-13 text-primary"><i class="fa-solid fa-key mr-1"></i> {{ __('Personal API Keys') }}</a>
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

		<div class="col-xl-9 col-lg-8 col-sm-12">
			<form method="POST" class="w-100" action="{{ route('user.profile.api.store') }}" enctype="multipart/form-data">
				@method('PUT')
				@csrf

				<div class="card border-0">
					<div class="card-header">
						<h3 class="card-title"><i class="fa-sharp fa-solid fa-sliders mr-2 text-primary"></i>{{ __('Personal API Keys') }}</h3>
					</div>
					<div class="card-body pb-0">	
						@if (auth()->user()->group == 'user' || auth()->user()->group == 'admin')
							@if (config('settings.personal_openai_api') == 'allow')
								<div class="row">
									<div class="col-sm-12">								
										<!-- ACCESS KEY -->
										<div class="input-box">								
											<h6>{{ __('Enter Your OpenAI Secret Key') }}</h6>
											<div class="form-group">							    
												<input type="text" class="form-control @error('openai-key') is-danger @enderror" id="openai-key" name="openai-key" value="{{ auth()->user()->personal_openai_key }}" autocomplete="off">
												@error('openai-key')
													<p class="text-danger">{{ $errors->first('openai-key') }}</p>
												@enderror
											</div> 
										</div> <!-- END ACCESS KEY -->
									</div>
								</div>
							@endif

							@if (config('settings.personal_sd_api') == 'allow')
								<div class="row">
									<div class="col-sm-12">								
										<!-- ACCESS KEY -->
										<div class="input-box">								
											<h6>{{ __('Enter Your Stable Diffusion API Key') }}</h6>
											<div class="form-group">							    
												<input type="text" class="form-control @error('sd-key') is-danger @enderror" id="sd-key" name="sd-key" value="{{ auth()->user()->personal_sd_key }}" autocomplete="off">
												@error('sd-key')
													<p class="text-danger">{{ $errors->first('sd-key') }}</p>
												@enderror
											</div> 
										</div> <!-- END ACCESS KEY -->
									</div>
								</div>
							@endif							
						@endif				
						
						@if (!is_null(auth()->user()->plan_id))
							@if ($check_api_feature->personal_openai_api)
								<div class="row">
									<div class="col-sm-12">								
										<!-- ACCESS KEY -->
										<div class="input-box">								
											<h6>{{ __('Enter Your OpenAI Secret Key') }}</h6>
											<div class="form-group">							    
												<input type="text" class="form-control @error('openai-key') is-danger @enderror" id="openai-key" name="openai-key" value="{{ auth()->user()->personal_openai_key }}" autocomplete="off">
												@error('openai-key')
													<p class="text-danger">{{ $errors->first('openai-key') }}</p>
												@enderror
											</div> 
										</div> <!-- END ACCESS KEY -->
									</div>
								</div>
							@endif

							@if ($check_api_feature->personal_sd_api)
								<div class="row">
									<div class="col-sm-12">								
										<!-- ACCESS KEY -->
										<div class="input-box">								
											<h6>{{ __('Enter Your Stable Diffusion API Key') }}</h6>
											<div class="form-group">							    
												<input type="text" class="form-control @error('sd-key') is-danger @enderror" id="sd-key" name="sd-key" value="{{ auth()->user()->personal_sd_key }}" autocomplete="off">
												@error('sd-key')
													<p class="text-danger">{{ $errors->first('sd-key') }}</p>
												@enderror
											</div> 
										</div> <!-- END ACCESS KEY -->
									</div>
								</div>
							@endif
						@endif
						
						<div class="card-footer border-0 text-center mb-2 pt-0">
							<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>							
						</div>					
					</div>				
				</div>
			</form>
		</div>
	</div>
	<!-- EDIT USER PROFILE PAGE --> 
@endsection
