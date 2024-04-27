@extends('layouts.app')

@section('page-header')
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Add Subscription') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-id-badge mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.user.dashboard') }}"> {{ __('User Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.user.list') }}">{{ __('User List') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Add Subscription') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<div class="row">
		<div class="col-lg-6 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Assign Subscription Plan') }}</h3>
				</div>
				<div class="card-body">
					<form method="POST" action="{{ route('admin.user.assign', [$user->id]) }}" enctype="multipart/form-data">
						@csrf
						
						<div class="row">

							<div class="col-sm-12 col-md-12">
								<div>
									<p class="fs-14 mb-2">{{ __('User Full Name') }}: <span class="font-weight-bold ml-1">{{ $user->name }}</span></p>
									<p class="fs-14 mb-2">{{ __('User Email Address') }}: <span class="font-weight-bold ml-1">{{ $user->email }}</span></p>
									<p class="fs-14 mb-2">{{ __('Registered On') }}: <span class="font-weight-bold ml-1">{{ $user->created_at }}</span></p>
									<p class="fs-14 mb-4">{{ __('Current subscription plan of the user') }}: <span class="font-weight-bold ml-1">{{ $plan }}</span></p>
								</div>

								<div class="input-box">
									<label class="form-label fs-14">{{ __('Subscription Plans') }}</label>
									<select name="plan" class="form-select">	
										@foreach ($plans as $plan)
											<option value={{ $plan->id }}>{{ ucfirst($plan->payment_frequency) }} - {{ $plan->plan_name }} ({{ $plan->price }} {{ $plan->currency }}) - {{ ucfirst($plan->status) }} {{ __('Plan') }}</option>
										@endforeach																			
									</select>
								</div>
							</div>
						</div>
						<div class="card-footer border-0 text-center pr-0">							
							<a href="{{ route('admin.user.list') }}" class="btn btn-cancel mr-2">{{ __('Return') }}</a>
							<button type="submit" class="btn btn-primary">{{ __('Assign') }}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
