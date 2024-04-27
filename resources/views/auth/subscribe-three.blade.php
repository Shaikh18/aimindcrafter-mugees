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
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <div class="pt-8">

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
                                                        <div class="wizard-step-number current-step mr-3 fs-14" id="step-one-number"><i class="fa-solid fa-check"></i></div>
                                                        <div class="wizard-step-title"><span class="font-weight-bold fs-14">{{ __('Create Account') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 1') }}</span></div>
                                                    </div>
                                                    <div>
                                                        <i class="fa-solid fa-chevrons-right wizard-nav-chevron current-sign" id="step-one-icon"></i>
                                                    </div>									
                                                </div>	
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="d-flex wizard-nav-text">
                                                        <div class="wizard-step-number mr-3 fs-14 current-step" id="step-two-number"><i class="fa-solid fa-check"></i></div>
                                                        <div class="wizard-step-title responsive"><span class="font-weight-bold fs-14">{{ __('Select Your Plan') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 2') }}</span></div>
                                                    </div>	
                                                    <div>
                                                        <i class="fa-solid fa-chevrons-right wizard-nav-chevron current-sign" id="step-two-icon"></i>
                                                    </div>								
                                                </div>
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="d-flex wizard-nav-text">
                                                        <div class="wizard-step-number mr-3 fs-14 current-step" id="step-three-number">3</div>
                                                        <div class="wizard-step-title"><span class="font-weight-bold fs-14">{{ __('Payment') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 3') }}</span></div>
                                                    </div>								
                                                </div>
                                            </div>					
                                        </div>
                                    </div>                                


                                    <div id="payment" class="subscribe-third-step">

                                        <h3 class="text-center login-title mb-2">{{__('Payment Method')}} </h3>
                                        <p class="fs-12 text-muted text-center mb-8">{{ __('Please provide your billing information and select your payment method') }}</p>

                                        <div class="row justify-content-center">
                                            <div class="col-lg-10 col-md-12 col-sm-12">                                                
                                                <div class="card-body">	
                                                    
                                                    <form id="payment-form" action="{{ route('user.payments.pay', $id) }}" method="POST" enctype="multipart/form-data" onsubmit="process()">
                                                        @csrf
                                
                                                        <div class="row">
                                                            <div class="col-lg-8 col-md-6 col-sm-12 pr-4">
                                                                <div class="checkout-wrapper-box pb-0 background-white">							
                                
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
                                                                                    <label class="form-label fs-11 font-weight-semibold">{{ __('First Name') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                                                    <input type="text" class="form-control @error('name') is-danger @enderror" name="name" required>
                                                                                    @error('name')
                                                                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                                                                    @enderror									
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6 col-md-6">
                                                                            <div class="input-box">
                                                                                <div class="form-group">
                                                                                    <label class="form-label fs-11 font-weight-semibold">{{ __('Last Name') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                                                    <input type="text" class="form-control @error('lastname') is-danger @enderror" name="lastname" required>
                                                                                    @error('lastname')
                                                                                        <p class="text-danger">{{ $errors->first('lastname') }}</p>
                                                                                    @enderror									
                                                                                </div>
                                                                            </div>
                                                                        </div>						
                                                                        <div class="col-sm-6 col-md-6">
                                                                            <div class="input-box">
                                                                                <div class="form-group">
                                                                                    <label class="form-label fs-11 font-weight-semibold">{{ __('Email Address') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                                                    <input type="email" class="form-control @error('email') is-danger @enderror" name="email" required>
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
                                                                                    <input type="tel" class="fs-12 form-control @error('phone_number') is-danger @enderror" id="phone-number" name="phone_number">
                                                                                    @error('phone_number')
                                                                                        <p class="text-danger">{{ $errors->first('phone_number') }}</p>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>				
                                                                        <div class="col-md-12">
                                                                            <div class="input-box">
                                                                                <div class="form-group">
                                                                                    <label class="form-label fs-11 font-weight-semibold">{{ __('Billing Address') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                                                    <input type="text" class="form-control @error('address') is-danger @enderror" name="address" required>
                                                                                    @error('address')
                                                                                        <p class="text-danger">{{ $errors->first('address') }}</p>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6 col-md-4">
                                                                            <div class="input-box">
                                                                                <div class="form-group">
                                                                                    <label class="form-label fs-11 font-weight-semibold">{{ __('City') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                                                    <input type="text" class="form-control @error('city') is-danger @enderror" name="city" required>
                                                                                    @error('city')
                                                                                        <p class="text-danger">{{ $errors->first('city') }}</p>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6 col-md-3">
                                                                            <div class="input-box">
                                                                                <div class="form-group">
                                                                                    <label class="form-label fs-11 font-weight-semibold">{{ __('Postal Code') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                                                    <input type="text" class="form-control @error('postal_code') is-danger @enderror" name="postal_code" required>
                                                                                    @error('postal_code')
                                                                                        <p class="text-danger">{{ $errors->first('postal_code') }}</p>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <div class="form-group">
                                                                                <label class="form-label fs-11 font-weight-semibold">{{ __('Country') }} <span class="text-required-register"><i class="fa-solid fa-asterisk"></i></span></label>
                                                                                <select id="user-country" name="country" class="form-select">	
                                                                                    @foreach(config('countries') as $value)
                                                                                        <option value="{{ $value }}" @if(config('settings.default_country') == $value) selected @endif>{{ $value }}</option>
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
                                
                                                                    <input type="hidden" name="value" id="hidden_value">
                                                                    <input type="hidden" name="currency" id="hidden_currency">																															
                                                                    
                                                                </div>
                                
                                                                <div class="text-center pt-4 pb-1">
                                                                    <button type="submit" id="payment-button" class="btn btn-primary pl-7 pr-7 mb-4 fs-11 ripple">{{ __('Checkout Now') }}</button>
                                                                </div>
                                
                                                            </div>
                                
                                                            <div class="col-lg-4 col-md-6 col-sm-12 pl-4">
                                                                <div class="checkout-wrapper-box background-white">
                                
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
            $('#payment-button').prop('disabled', true);
            let btn = document.getElementById('payment-button');					
            btn.innerHTML = loading;  
            document.querySelector('#loader-line')?.classList?.remove('hidden'); 
            return; 
        }

    </script>   
@endsection
