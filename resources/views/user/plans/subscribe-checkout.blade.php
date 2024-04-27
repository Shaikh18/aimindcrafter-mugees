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
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Subscription Plan Checkout') }}</a></li>
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
					
					<form id="payment-form" action="{{ route('user.payments.pay', $id) }}" method="POST" enctype="multipart/form-data">
						@csrf

						<div class="row">
							<div class="col-lg-8 col-md-6 col-sm-12 pr-4">
								<div class="checkout-wrapper-box pb-0">							

									<p class="checkout-title mt-2"><i class="fa-solid fa-lock-hashtag mr-2 text-success"></i>{{ __('Secure Checkout') }}</p>

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

									<input type="hidden" name="value" value="{{ $total_value }}">
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

									<p class="checkout-title mt-2"><i class="fa fa-archive mr-2"></i>{{ __('Subscription Plan Name') }}: <span class="text-info">{{ $id->plan_name }}</span></p>

									<div class="plan">				
										<p class="plan-cost mb-3 mt-3 font-weight-bold text-primary">
											@if ($id->free)
												{{ __('Free') }}
											@else
												@if(config('payment.decimal_points') == 'allow'){{ number_format((float)$id->price, 2) }} @else{{ number_format($id->price) }} @endif {{ $id->currency }} <span class="fs-12 text-muted"><span class="mr-1">/</span> {{ __('for') }} {{ $id->payment_frequency }}</span>
											@endif
										</p>																					
										<p class="fs-12 mb-3 text-muted">{{ __('Included Features') }}</p>																	
										<ul class="fs-12 pl-3">		
											@if ($id->words == -1)
												<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li>
											@else	
												@if($id->words != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($id->words) }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li> @endif
											@endif
											@if (config('settings.image_feature_user') == 'allow')
												@if ($id->dalle_image_engine != 'none')
													@if ($id->dalle_images == -1)
														<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Dalle images / month') }}</span></li>
													@else
														@if($id->dalle_images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($id->dalle_images) }}</span> <span class="plan-feature-text">{{ __('Dalle images / month') }}</span></li> @endif
													@endif
												@endif																
											@endif
											@if (config('settings.image_feature_user') == 'allow')
												@if ($id->sd_image_engine != 'none')
													@if ($id->sd_images == -1)
														<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('SD images / month') }}</span></li>
													@else
														@if($id->sd_images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($id->sd_images) }}</span> <span class="plan-feature-text">{{ __('SD images / month') }}</span></li> @endif
													@endif
												@endif																	
											@endif
											@if (config('settings.whisper_feature_user') == 'allow')
												@if ($id->minutes == -1)
													<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li>
												@else
													@if($id->minutes != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($id->minutes) }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li> @endif
												@endif																	
											@endif
											@if (config('settings.voiceover_feature_user') == 'allow')
												@if ($id->characters == -1)
													<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li>
												@else
													@if($id->characters != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($id->characters) }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li> @endif
												@endif																	
											@endif
												@if($id->team_members != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $id->team_members }}</span> <span class="plan-feature-text">{{ __('team members') }}</span></li> @endif
											
											@if (config('settings.chat_feature_user') == 'allow')
												@if($id->chat_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Chats Feature') }}</span></li> @endif
											@endif
											@if (config('settings.image_feature_user') == 'allow')
												@if($id->image_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Images Feature') }}</span></li> @endif
											@endif
											@if (config('settings.voiceover_feature_user') == 'allow')
												@if($id->voiceover_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Voiceover Feature') }}</span></li> @endif
											@endif
											@if (config('settings.whisper_feature_user') == 'allow')
												@if($id->transcribe_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Speech to Text Feature') }}</span></li> @endif
											@endif
											@if (config('settings.code_feature_user') == 'allow')
												@if($id->code_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Code Feature') }}</span></li> @endif
											@endif
											@if($id->team_members) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Team Members Option') }}</span></li> @endif
											@foreach ( (explode(',', $id->plan_features)) as $feature )
												@if ($feature)
													<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ $feature }}</li>
												@endif																
											@endforeach															
										</ul>																
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



