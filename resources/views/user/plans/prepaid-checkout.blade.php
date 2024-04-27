@extends('layouts.app')

@section('css')
	<!-- Telephone Input CSS -->
	<link href="{{URL::asset('plugins/telephoneinput/telephoneinput.css')}}" rel="stylesheet" >
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center">
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0"><i class="fa-solid fa-box-circle-check mr-2"></i>{{ __('Secure Checkout') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fa-solid fa-id-badge mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.plans') }}"> {{ __('Pricing Plans') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('One Time Payment Checkout') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')	
	<div class="row justify-content-center">
		<div class="col-lg-10 col-md-12 col-sm-12">
			<div class="card border-0 pt-2">
				<div class="card-body">	
					
					<form id="payment-form" action="{{ route('user.payments.pay.prepaid', ['type' => $type, 'id' => $id]) }}" promocode="{{ route('user.payments.promocodes.prepaid', $id) }}" method="POST" enctype="multipart/form-data">
						@csrf

						<div class="row">
							<div class="col-lg-8 col-md-6 col-sm-12 pr-4">
								<div class="checkout-wrapper-box pb-0">										

										<p class="checkout-title mt-2"><i class="fa-solid fa-lock-hashtag text-success mr-2"></i>{{ __('Secure Checkout') }}</p>

										<div class="divider mb-5">
											<div class="divider-text text-muted">
												<small>{{ __('Billing Details') }}</small>
											</div>
										</div>

										<div class="row">
											<div class="col-sm-6 col-md-6">
												<div class="input-box">
													<div class="form-group">
														<label class="form-label fs-11 font-weight-semibold">{{ __('First Name') }}</label>
														<input type="text" class="form-control @error('name') is-danger @enderror" name="name" value="{{ auth()->user()->name }}" required>
														@error('name')
															<p class="text-danger">{{ $errors->first('name') }}</p>
														@enderror									
													</div>
												</div>
											</div>
											<div class="col-sm-6 col-md-6">
												<div class="input-box">
													<div class="form-group">
														<label class="form-label fs-11 font-weight-semibold">{{ __('Last Name') }}</label>
														<input type="text" class="form-control @error('lastname') is-danger @enderror" name="lastname" value="{{ auth()->user()->name }}" required>
														@error('lastname')
															<p class="text-danger">{{ $errors->first('lastname') }}</p>
														@enderror									
													</div>
												</div>
											</div>						
											<div class="col-sm-6 col-md-6">
												<div class="input-box">
													<div class="form-group">
														<label class="form-label fs-11 font-weight-semibold">{{ __('Email Address') }}</label>
														<input type="email" class="form-control @error('email') is-danger @enderror" name="email" value="{{ auth()->user()->email }}">
														@error('email')
															<p class="text-danger">{{ $errors->first('email') }}</p>
														@enderror
													</div>
												</div>
											</div>
												
											<div class="col-sm-6 col-md-6">
												<div class="input-box">
													<div class="form-group">								
														<label class="form-label fs-11 font-weight-semibold">{{ __('Phone Number') }}</label>
														<input type="tel" class="fs-12 form-control @error('phone_number') is-danger @enderror" id="phone-number" name="phone_number" value="{{ auth()->user()->phone_number }}">
														@error('phone_number')
															<p class="text-danger">{{ $errors->first('phone_number') }}</p>
														@enderror
													</div>
												</div>
											</div>				
											<div class="col-md-12">
												<div class="input-box">
													<div class="form-group">
														<label class="form-label fs-11 font-weight-semibold">{{ __('Billing Address') }}</label>
														<input type="text" class="form-control @error('address') is-danger @enderror" name="address" value="{{ auth()->user()->address }}">
														@error('address')
															<p class="text-danger">{{ $errors->first('address') }}</p>
														@enderror
													</div>
												</div>
											</div>
											<div class="col-sm-6 col-md-4">
												<div class="input-box">
													<div class="form-group">
														<label class="form-label fs-11 font-weight-semibold">{{ __('City') }}</label>
														<input type="text" class="form-control @error('city') is-danger @enderror" name="city" value="{{ auth()->user()->city }}">
														@error('city')
															<p class="text-danger">{{ $errors->first('city') }}</p>
														@enderror
													</div>
												</div>
											</div>
											<div class="col-sm-6 col-md-3">
												<div class="input-box">
													<div class="form-group">
														<label class="form-label fs-11 font-weight-semibold">{{ __('Postal Code') }}</label>
														<input type="text" class="form-control @error('postal_code') is-danger @enderror" name="postal_code" value="{{ auth()->user()->postal_code }}">
														@error('postal_code')
															<p class="text-danger">{{ $errors->first('postal_code') }}</p>
														@enderror
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class="form-group">
													<label class="form-label fs-11 font-weight-semibold">{{ __('Country') }}</label>
													<select id="user-country" name="country" class="form-select">	
														@foreach(config('countries') as $value)
															<option value="{{ $value }}" @if(auth()->user()->country == $value) selected @endif>{{ $value }}</option>
														@endforeach										
													</select>
													@error('country')
														<p class="text-danger">{{ $errors->first('country') }}</p>
													@enderror
												</div>
											</div>
											<div class="col-md-12">
												<div class="input-box">
													<div class="form-group">
														<label class="form-label fs-11 font-weight-semibold">{{ __('VAT Number') }}</label>
														<input type="text" class="form-control @error('vat') is-danger @enderror" name="vat">
														@error('vat')
															<p class="text-danger">{{ $errors->first('vat') }}</p>
														@enderror
													</div>
												</div>
											</div>
										</div>


										<div class="divider mb-6">
											<div class="divider-text text-muted">
												<small>{{ __('Select Payment Option') }}</small>
											</div>
										</div>

										<div class="form-group" id="toggler">					
													
											<div class="text-center">
												<div class="btn-group btn-group-toggle w-100" data-toggle='buttons'>
													<div class="row w-100">
														@foreach ($payment_platforms as $payment_platform)													
															<div class="col-lg-4 col-md-6 col-sm-12">															
																<label class="gateway btn rounded p-0" href="#{{ $payment_platform->name }}Collapse" data-bs-toggle="collapse">
																	<input type="radio" class="gateway-radio" name="payment_platform" value="{{ $payment_platform->id }}">
																	<img src="{{ URL::asset($payment_platform->image) }}" 
																		 class="@if ($payment_platform->name == 'Paystack' || $payment_platform->name == 'Razorpay' || $payment_platform->name == 'PayPal') payment-image
																		 @elseif ($payment_platform->name == 'Braintree') payment-image-braintree
																		 @elseif ($payment_platform->name == 'Mollie') payment-image-mollie
																		 @elseif ($payment_platform->name == 'Coinbase') payment-image-coinbase
																		 @elseif ($payment_platform->name == 'Stripe') payment-image-stripe
																		 @elseif ($payment_platform->name == 'Iyzico') payment-image-iyzico
																		 @elseif ($payment_platform->name == 'Paddle') payment-image-paddle
																		 @elseif ($payment_platform->name == 'Flutterwave') payment-image-flutterwave
																		 @endif" alt="{{ $payment_platform->name }}">
																</label>
															</div>										
														@endforeach		
													</div>							
												</div>
											</div>
											
											@foreach ($payment_platforms as $payment_platform)
												@if ($payment_platform->name !== 'BankTransfer')
													<div id="{{ $payment_platform->name }}Collapse" class="collapse" data-bs-parent="#toggler">
														@includeIf('components.'.strtolower($payment_platform->name).'-collapse')
													</div>
												@else
													<div id="{{ $payment_platform->name }}Collapse" class="collapse" data-bs-parent="#toggler">
														<div class="text-center pb-2">
															<p class="text-muted fs-12 mb-4">{{ $bank['bank_instructions'] }}</p>
															<p class="text-muted fs-12 mb-4">Order ID: <span class="font-weight-bold text-primary">{{ $bank_order_id }}</span></p>
															<pre class="text-muted fs-12 mb-4">{{ $bank['bank_requisites'] }}</pre>															
														</div>
													</div>																										
												@endif
											@endforeach
										</div>

										<input type="hidden" name="value" id="hidden_value" value="{{ $total_value }}">
										<input type="hidden" name="currency" value="{{ $currency }}">
								</div>

								<div class="text-center pt-4 pb-1">
									<button type="submit" id="payment-button" class="btn btn-primary pl-8 pr-8 mb-4 fs-11 ripple">{{ __('Checkout Now') }}</button>
								</div>

							</div>

							<div class="col-lg-4 col-md-6 col-sm-12 pl-4">
								<div class="checkout-wrapper-box">

									<div class="divider mb-4">
										<div class="divider-text text-muted">
											<small>{{ __('Order Details') }}</small>
										</div>
									</div>

									<p class="checkout-title mt-2"><i class="fa fa-archive mr-2"></i>{{ __('Prepaid Plan Name') }}: <span class="text-info">{{ $id->plan_name }}</span></p>

									<div class="plan prepaid-plan">
										<div class="plan-cost-wrapper mt-3 text-center mb-2 mt-3 font-weight-bold"><span class="plan-cost">@if (config('payment.decimal_points') == 'allow') {{ number_format((float)$id->price, 2) }} @else {{ number_format($id->price) }} @endif</span><span class="prepaid-currency-sign text-muted">{{ $id->currency }}</span></div>
										<p class="fs-12 mb-3 text-muted">{{ __('Included Credits') }}</p>	
										<div class="credits-box">
											@if ($id->words != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Words Included') }}: <span class="ml-2 font-weight-bold text-primary" style="float: right">{{ number_format($id->words) }}</span></p>@endif
											 @if ($id->dalle_images != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Dalle Images Included') }}: <span class="ml-2 font-weight-bold text-primary" style="float: right">{{ number_format($id->dalle_images) }}</span></p>@endif
											 @if ($id->sd_images != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('SD Images Included') }}: <span class="ml-2 font-weight-bold text-primary" style="float: right">{{ number_format($id->sd_images) }}</span></p>@endif
											 @if ($id->characters != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Characters Included') }}: <span class="ml-2 font-weight-bold text-primary" style="float: right">{{ number_format($id->characters) }}</span></p>@endif																							
											 @if ($id->minutes != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Minutes Included') }}: <span class="ml-2 font-weight-bold text-primary" style="float: right">{{ number_format($id->minutes) }}</span></p>@endif	
										</div>																							                                                                          
									</div>	

									<div class="divider mb-4">
										<div class="divider-text text-muted">
											<small>{{ __('Purchase Summary') }}</small>
										</div>
									</div>

									<div>
										<p class="fs-12 p-family">{{ __('Subtotal') }} <span class="checkout-cost">@if (config('payment.decimal_points') == 'allow') {{ number_format((float)$id->price, 2, '.', '') }} @else {{ number_format($id->price) }} @endif {{ $id->currency }}</span></p>
										<p class="fs-12 p-family">{{ __('Taxes') }} <span class="text-muted">({{ config('payment.payment_tax') }}%)</span><span class="checkout-cost">@if (config('payment.decimal_points') == 'allow') {{ number_format((float)$tax_value, 2, '.', '') }} @else {{ number_format($tax_value) }} @endif {{ $id->currency }}</span></p>
									</div>

									<div class="divider mb-5">
										<div class="divider-text text-muted">
											<small>{{ __('Total') }}</small>
										</div>
									</div>

									<input type="hidden" name="type" value="{{ $type }}">

									<div class="input-box mb-5">
										<div class="input-group">								
											<input type="text" class="form-control border-right-0 promocode-field" name="promo_code" placeholder="{{ __('Promocode') }}">
											<label class="input-group-btn">
												<a class="btn btn-primary ripple" id="prepaid-promocode-apply">{{ __('Apply') }}</a>
											</label>											
										</div>
										<span id="promocode-error" class="d-none fs-12 text-danger"></span>
									</div>

									<div id="voucher-result" class="d-none">
										<p class="fs-12 p-family">{{ __('Discount Applied') }} </span><span class="checkout-cost"><span id="total_discount"></span></span></p>
									</div>
									
									<div>
										<p class="fs-12 p-family">{{ __('Total Due') }} </span><span class="checkout-cost text-info"><span id="total_payment"> @if (config('payment.decimal_points') == 'allow') {{ number_format((float)$total_value, 2, '.', '') }} @else {{ number_format($total_value) }} @endif</span> {{ $currency }}</span></p>
									</div>

								</div>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<!-- Telephone Input JS -->
	<script src="{{URL::asset('plugins/telephoneinput/telephoneinput.js')}}"></script>
	<script>
		$(function() {
			"use strict";
			
			$("#phone-number").intlTelInput();
		});

		$('#payment-button').on('click', function(e) {
			
			e.preventDefault();

			if($("input[type='radio'][name=payment_platform]:checked").val()) {
				document.getElementById("payment-form").submit();
			} else {
				toastr.warning('{{ __('Please select a payment gateway first') }}');
			}

		});


	</script>
@endsection




