@extends('layouts.app')

@section('page-header')
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center">
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0">{{ __('Update User Credits') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-id-badge mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.user.dashboard') }}"> {{ __('User Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.user.list') }}">{{ __('User List') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Update User Credits') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-8 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><i class="fa-solid fa-id-badge mr-2 text-primary fs-14"></i>{{ __('Update User Credits') }}</h3>
				</div>
				<div class="card-body">
					<form method="POST" action="{{ route('admin.user.increase', [$user->id]) }}" enctype="multipart/form-data">
						@csrf
						
						<div class="row">

							<div class="col-sm-12 col-md-12 mt-2">
								<div>
									<p class="fs-12 mb-2">{{ __('Full Name') }}: <span class="font-weight-bold ml-2 text-primary">{{ $user->name }}</span></p>
									<p class="fs-12 mb-2">{{ __('Email Address') }}: <span class="font-weight-bold ml-2">{{ $user->email }}</span></p>
									<p class="fs-12 mb-2">{{ __('User Group') }}: <span class="font-weight-bold ml-2">{{ ucfirst($user->group) }}</span></p>
								</div>
								<div class="row mt-4">
									<div class="col-sm-12 col-md-6">
										<p class="fs-12 mb-2">{{ __('Available Words') }}: <span class="font-weight-bold ml-2">@if ($user->available_words == -1) {{ __('Unlimited') }} @else {{ number_format($user->available_words) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Dalle Images') }}: <span class="font-weight-bold ml-2">@if ($user->available_dalle_images == -1) {{ __('Unlimited') }} @else {{ number_format($user->available_dalle_images ) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Stable Diffusion Images') }}: <span class="font-weight-bold ml-2">@if ($user->available_sd_images == -1) {{ __('Unlimited') }} @else {{ number_format($user->available_sd_images ) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Characters') }}: <span class="font-weight-bold ml-2">@if ($user->available_chars == -1) {{ __('Unlimited') }} @else {{ number_format($user->available_chars) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Minutes') }}: <span class="font-weight-bold ml-2">@if ($user->available_minutes == -1) {{ __('Unlimited') }} @else {{ number_format($user->available_minutes) }} @endif</span></p>
									</div>
									<div class="col-sm-12 col-md-6">
										<p class="fs-12 mb-2">{{ __('Available Prepaid Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->available_words_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Dalle Images') }}: <span class="font-weight-bold ml-2">{{ number_format($user->available_dalle_images_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Stable Diffusion Images') }}: <span class="font-weight-bold ml-2">{{ number_format($user->available_sd_images_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Characters') }}: <span class="font-weight-bold ml-2">{{ number_format($user->available_chars_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Minutes') }}: <span class="font-weight-bold ml-2">{{ number_format($user->available_minutes_prepaid) }}</span></p>
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Word Credits') }}</label>
										<input type="number" class="form-control @error('words') is-danger @enderror" value={{ $user->available_words }} name="words">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid Word Credits') }}</label>
										<input type="number" class="form-control @error('words_prepaid') is-danger @enderror" value={{ $user->available_words_prepaid }} name="words_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-image mr-2 text-info"></i>{{ __('User Dalle Image Credits') }}</label>
										<input type="number" class="form-control @error('dalle-images') is-danger @enderror" value={{ $user->available_dalle_images }} name="dalle-images">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited images') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-image mr-2 text-info"></i>{{ __('User Prepaid Dalle Image Credits') }}</label>
										<input type="number" class="form-control @error('dalle_images_prepaid') is-danger @enderror" value={{ $user->available_dalle_images_prepaid }} name="dalle_images_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-image mr-2 text-info"></i>{{ __('User Stable Diffusion Image Credits') }}</label>
										<input type="number" class="form-control @error('sd-images') is-danger @enderror" value={{ $user->available_sd_images }} name="sd-images">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited images') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-image mr-2 text-info"></i>{{ __('User Prepaid Stable Diffusion Image Credits') }}</label>
										<input type="number" class="form-control @error('sd_images_prepaid') is-danger @enderror" value={{ $user->available_sd_images_prepaid }} name="sd_images_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-waveform-lines mr-2 text-info"></i>{{ __('User Character Credits') }}</label>
										<input type="number" class="form-control @error('chars') is-danger @enderror" value={{ $user->available_chars }} name="chars">	
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited characters') }}</span>							
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-waveform-lines mr-2 text-info"></i>{{ __('User Prepaid Character Credits') }}</label>
										<input type="number" class="form-control @error('chars_prepaid') is-danger @enderror" value={{ $user->available_chars_prepaid }} name="chars_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-folder-music mr-2 text-info"></i>{{ __('User Minutes Credits') }}</label>
										<input type="number" class="form-control @error('minutes') is-danger @enderror" value={{ $user->available_minutes }} name="minutes">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited minutes') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-folder-music mr-2 text-info"></i>{{ __('User Prepaid Minutes Credits') }}</label>
										<input type="number" class="form-control @error('minutes_prepaid') is-danger @enderror" value={{ $user->available_minutes_prepaid }} name="minutes_prepaid">									
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer border-0 text-center pr-0">							
							<a href="{{ route('admin.user.list') }}" class="btn btn-cancel mr-2">{{ __('Return') }}</a>
							<button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
