@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center">
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0">{{ __('Show Transaction Details') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.dashboard') }}"> {{ __('Finance Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.transactions') }}"> {{ __('Transactions') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Show Transaction Details') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')						
	<div class="row justify-content-center">
		<div class="col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Transaction') }} ID: <span class="text-info">{{ $id->order_id }}</span></h3>
				</div>
				<div class="card-body pt-5">		

					<div class="row">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Transaction Date') }}: </h6>
							<span class="fs-14">{{ date_format($id->created_at, 'd M Y, H:i A') }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Total Price') }}: </h6>
							<span class="fs-14">{!! config('payment.default_system_currency_symbol') !!}{{ ucfirst($id->price) }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Payment Status') }}: </h6>
							<span class="fs-14">{{ __(ucfirst($id->status)) }}</span>
						</div>
					</div>

					<div class="row pt-5">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Plan Name') }}: </h6>
							<span class="fs-14">{{ ucfirst($id->plan_name) }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Words Included') }}: </h6>
							<span class="fs-14">{{ number_format($id->words) }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1">{{ __('Payment Gateway') }}: </h6>
							<span class="fs-14">{{ $id->gateway }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12 pt-5">
							<h6 class="font-weight-bold mb-1">{{ __('Payment Frequency') }}: </h6>
							<span class="fs-14">{{ ucfirst($id->frequency)}}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12 pt-5">
							<h6 class="font-weight-bold mb-1">{{ __('User Name') }}: </h6>
							<span class="fs-14">{{ $user->name }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12 pt-5">
							<h6 class="font-weight-bold mb-1">{{ __('User Email') }}: </h6>
							<span class="fs-14">{{ $user->email }}</span>
						</div>
						<div class="col-lg-4 col-md-4 col-12 pt-5">
							<h6 class="font-weight-bold mb-1">{{ __('Country') }}: </h6>
							<span class="fs-14">{{ $user->country }}</span>
						</div>
					</div>	

					<div class="row pt-7 pb-5">
						<div class="col-md-6 col-12">
							<h6 class="font-weight-bold mb-2">{{ __('Transaction Invoice') }}: </h6>
							<a href="{{ route('user.payments.invoice.show', $id) }}" class="btn btn-primary pl-5 pr-5">{{ __('Download Invoice') }}</a>						
						</div>
						@if ($id->gateway == 'BankTransfer')
							<div class="col-md-6 col-12">
								<h6 class="font-weight-bold mb-2">{{ __('Payment Confirmation') }}: </h6>
								@if (is_null($id->invoice))
									<span class="fs-14">{{ __('User did not upload any payment confirmation yet') }}</span>
								@else
									<a href="{{ URL::asset($id->invoice) }}" download class="btn btn-primary pl-5 pr-5">{{ __('Download Confirmation') }}</a>	
								@endif
													
							</div>
						@endif
					</div>	

					<!-- SAVE CHANGES ACTION BUTTON -->
					<div class="border-0 text-center mb-2 mt-7">
						<a href="{{ route('admin.finance.transactions') }}" class="btn btn-cancel pl-7 pr-7">{{ __('Return') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

