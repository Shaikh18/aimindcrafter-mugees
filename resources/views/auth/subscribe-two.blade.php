@extends('layouts.auth')

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
            <div class="container-fluid h-100vh ">                
                <div class="row login-background justify-content-center">

                    <div class="col-sm-12" id="login-responsive"> 
                        <div class="row justify-content-center subscribe-registration-background">
                            <div class="col-lg-8 col-md-12 col-sm-12 mx-auto">
                                <div class="card-body pt-8">

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
                                                        <div class="wizard-step-number mr-3 fs-14 current-step" id="step-two-number">2</div>
                                                        <div class="wizard-step-title responsive"><span class="font-weight-bold fs-14">{{ __('Select Your Plan') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 2') }}</span></div>
                                                    </div>	
                                                    <div>
                                                        <i class="fa-solid fa-chevrons-right wizard-nav-chevron" id="step-two-icon"></i>
                                                    </div>								
                                                </div>
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="d-flex wizard-nav-text">
                                                        <div class="wizard-step-number mr-3 fs-14" id="step-three-number">3</div>
                                                        <div class="wizard-step-title"><span class="font-weight-bold fs-14">{{ __('Payment') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 3') }}</span></div>
                                                    </div>								
                                                </div>
                                            </div>					
                                        </div>
                                    </div>                         

                                    <div id="registration-prices" class="subscribe-second-step">

                                        <h3 class="text-center login-title mb-2">{{__('Select Your Plan')}} </h3>
                                        <p class="fs-12 text-muted text-center mb-8">{{ __('Choose your subscription plan and click continue') }}</p>

                                        @if ($monthly || $yearly || $lifetime)
                            
                                            <div class="tab-menu-heading text-center">
                                                <div class="tabs-menu">								
                                                    <ul class="nav">							
                                                        @if ($monthly)
                                                            <li><a href="#monthly_plans" class="@if (($monthly && $yearly) || ($monthly && !$yearly) || ($monthly && !$yearly) || ($monthly && $yearly)) active @else '' @endif" data-bs-toggle="tab"> {{ __('Monthly Plans') }}</a></li>
                                                        @endif	
                                                        @if ($yearly)
                                                            <li><a href="#yearly_plans" class="@if (!$monthly && $yearly) active @else '' @endif" data-bs-toggle="tab"> {{ __('Yearly Plans') }}</a></li>
                                                        @endif		
                                                        @if ($lifetime)
                                                            <li><a href="#lifetime" class="@if (!$monthly && !$yearly &&  $lifetime) active @else '' @endif" data-bs-toggle="tab"> {{ __('Lifetime Plans') }}</a></li>
                                                        @endif							
                                                    </ul>
                                                </div>
                                            </div>
                            
                                        
                            
                                            <div class="tabs-menu-body">
                                                <div class="tab-content">                            
                            
                                                    @if ($monthly)	
                                                        <div class="tab-pane @if (($monthly) || ($monthly && !$lifetime) || ($monthly && !$yearly)) active @else '' @endif" id="monthly_plans">
                            
                                                            @if ($monthly_subscriptions->count())		
                            
                                                                <div class="row justify-content-md-center">
                            
                                                                    @foreach ( $monthly_subscriptions as $subscription )																			
                                                                        <div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">
                                                                            <div class="pt-2 ml-2 mr-2 h-100 prices-responsive">
                                                                                <div class="card p-5 mb-4 pl-7 pr-7 h-100 price-card @if ($subscription->featured) price-card-border @endif">
                                                                                    @if ($subscription->featured)
                                                                                        <span class="plan-featured">{{ __('Most Popular') }}</span>
                                                                                    @endif
                                                                                    <div class="plan">			
                                                                                        <div class="plan-title">{{ $subscription->plan_name }}</div>	
                                                                                        <p class="plan-cost mb-5">																					
                                                                                            @if ($subscription->free)
                                                                                                {{ __('Free') }}
                                                                                            @else
                                                                                                {!! config('payment.default_system_currency_symbol') !!}@if(config('payment.decimal_points') == 'allow'){{ number_format((float)$subscription->price, 2) }} @else{{ number_format($subscription->price) }} @endif<span class="fs-12 text-muted"><span class="mr-1">/</span> {{ __('per month') }}</span>
                                                                                            @endif   
                                                                                        </p>                                                                         
                                                                                        <div class="text-center action-button mt-2 mb-5">
                                                                                            <a href="{{ route('register.subscriber.payment', ['id' => $subscription->id]) }}" class="btn btn-primary-pricing">{{ __('Select') }}</a>                                               														
                                                                                        </div>
                                                                                        <p class="fs-12 mb-3 text-muted">{{ __('Included Features') }}</p>																		
                                                                                        <ul class="fs-12 pl-3">		
                                                                                            @if ($subscription->words == -1)
                                                                                                <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li>
                                                                                            @else	
                                                                                                @if($subscription->words != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->words }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.image_feature_user') == 'allow')
                                                                                                @if ($subscription->images == -1)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('images / month') }}</span></li>
                                                                                                @else
                                                                                                    @if($subscription->images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->images }}</span> <span class="plan-feature-text">{{ __('images / month') }}</span></li> @endif
                                                                                                @endif																	
                                                                                            @endif
                                                                                            @if (config('settings.whisper_feature_user') == 'allow')
                                                                                                @if ($subscription->minutes == -1)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li>
                                                                                                @else
                                                                                                    @if($subscription->minutes != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->minutes }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li> @endif
                                                                                                @endif																	
                                                                                            @endif
                                                                                            @if (config('settings.voiceover_feature_user') == 'allow')
                                                                                                @if ($subscription->characters == -1)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li>
                                                                                                @else
                                                                                                    @if($subscription->characters != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->characters }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li> @endif
                                                                                                @endif																	
                                                                                            @endif
                                                                                                @if($subscription->team_members != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->team_members }}</span> <span class="plan-feature-text">{{ __('team members') }}</span></li> @endif
                                                                                            
                                                                                            @if (config('settings.chat_feature_user') == 'allow')
                                                                                                @if($subscription->chat_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Chats Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.image_feature_user') == 'allow')
                                                                                                @if($subscription->image_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Images Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.voiceover_feature_user') == 'allow')
                                                                                                @if($subscription->voiceover_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Voiceover Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.whisper_feature_user') == 'allow')
                                                                                                @if($subscription->transcribe_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Speech to Text Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.code_feature_user') == 'allow')
                                                                                                @if($subscription->code_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Code Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if($subscription->team_members) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Team Members Option') }}</span></li> @endif
                                                                                            @foreach ( (explode(',', $subscription->plan_features)) as $feature )
                                                                                                @if ($feature)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ $feature }}</li>
                                                                                                @endif																
                                                                                            @endforeach															
                                                                                        </ul>																
                                                                                    </div>					
                                                                                </div>	
                                                                            </div>							
                                                                        </div>										
                                                                    @endforeach
                            
                                                                </div>	
                                                            
                                                            @else
                                                                <div class="row text-center">
                                                                    <div class="col-sm-12 mt-6 mb-6">
                                                                        <h6 class="fs-12 font-weight-bold text-center">{{ __('No Subscriptions plans were set yet') }}</h6>
                                                                    </div>
                                                                </div>
                                                            @endif					
                                                        </div>	
                                                    @endif	
                                                    
                                                    @if ($yearly)	
                                                        <div class="tab-pane @if (($yearly) && ($yearly && !$lifetime) && ($yearly && !$monthly)) active @else '' @endif" id="yearly_plans">
                            
                                                            @if ($yearly_subscriptions->count())		
                            
                                                                <div class="row justify-content-md-center">
                            
                                                                    @foreach ( $yearly_subscriptions as $subscription )																			
                                                                        <div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">
                                                                            <div class="pt-2 ml-2 mr-2 h-100 prices-responsive">
                                                                                <div class="card p-5 mb-4 pl-7 pr-7 h-100 price-card @if ($subscription->featured) price-card-border @endif">
                                                                                    @if ($subscription->featured)
                                                                                        <span class="plan-featured">{{ __('Most Popular') }}</span>
                                                                                    @endif
                                                                                    <div class="plan">			
                                                                                        <div class="plan-title">{{ $subscription->plan_name }}</div>																						
                                                                                        <p class="plan-cost mb-5">
                                                                                            @if ($subscription->free)
                                                                                                {{ __('Free') }}
                                                                                            @else
                                                                                                {!! config('payment.default_system_currency_symbol') !!}@if(config('payment.decimal_points') == 'allow'){{ number_format((float)$subscription->price, 2) }} @else{{ number_format($subscription->price) }} @endif<span class="fs-12 text-muted"><span class="mr-1">/</span> {{ __('per year') }}</span>
                                                                                            @endif    
                                                                                        </p>                                                                            
                                                                                        <div class="text-center action-button mt-2 mb-5">
                                                                                            <a href="{{ route('register.subscriber.payment', ['id' => $subscription->id]) }}" class="btn btn-primary-pricing">{{ __('Select') }}</a>                                               														
                                                                                        </div>
                                                                                        <p class="fs-12 mb-3 text-muted">{{ __('Included Features') }}</p>																	
                                                                                        <ul class="fs-12 pl-3">		
                                                                                            @if ($subscription->words == -1)
                                                                                                <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li>
                                                                                            @else	
                                                                                                @if($subscription->words != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->words }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.image_feature_user') == 'allow')
                                                                                                @if ($subscription->images == -1)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('images / month') }}</span></li>
                                                                                                @else
                                                                                                    @if($subscription->images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->images }}</span> <span class="plan-feature-text">{{ __('images / month') }}</span></li> @endif
                                                                                                @endif																	
                                                                                            @endif
                                                                                            @if (config('settings.whisper_feature_user') == 'allow')
                                                                                                @if ($subscription->minutes == -1)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li>
                                                                                                @else
                                                                                                    @if($subscription->minutes != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->minutes }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li> @endif
                                                                                                @endif																	
                                                                                            @endif
                                                                                            @if (config('settings.voiceover_feature_user') == 'allow')
                                                                                                @if ($subscription->characters == -1)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li>
                                                                                                @else
                                                                                                    @if($subscription->characters != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->characters }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li> @endif
                                                                                                @endif																	
                                                                                            @endif
                                                                                                @if($subscription->team_members != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->team_members }}</span> <span class="plan-feature-text">{{ __('team members') }}</span></li> @endif
                                                                                            
                                                                                            @if (config('settings.chat_feature_user') == 'allow')
                                                                                                @if($subscription->chat_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Chats Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.image_feature_user') == 'allow')
                                                                                                @if($subscription->image_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Images Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.voiceover_feature_user') == 'allow')
                                                                                                @if($subscription->voiceover_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Voiceover Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.whisper_feature_user') == 'allow')
                                                                                                @if($subscription->transcribe_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Speech to Text Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.code_feature_user') == 'allow')
                                                                                                @if($subscription->code_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Code Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if($subscription->team_members) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Team Members Option') }}</span></li> @endif
                                                                                            @foreach ( (explode(',', $subscription->plan_features)) as $feature )
                                                                                                @if ($feature)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ $feature }}</li>
                                                                                                @endif																
                                                                                            @endforeach															
                                                                                        </ul>																
                                                                                    </div>					
                                                                                </div>	
                                                                            </div>							
                                                                        </div>											
                                                                    @endforeach
                            
                                                                </div>	
                                                            
                                                            @else
                                                                <div class="row text-center">
                                                                    <div class="col-sm-12 mt-6 mb-6">
                                                                        <h6 class="fs-12 font-weight-bold text-center">{{ __('No Subscriptions plans were set yet') }}</h6>
                                                                    </div>
                                                                </div>
                                                            @endif					
                                                        </div>
                                                    @endif	
                                                    
                                                    @if ($lifetime)
                                                        <div class="tab-pane @if ((!$monthly && $lifetime) && (!$yearly && $lifetime)) active @else '' @endif" id="lifetime">
            
                                                            @if ($lifetime_subscriptions->count())                                                    
                                                                
                                                                <div class="row justify-content-md-center">
                                                                
                                                                    @foreach ( $lifetime_subscriptions as $subscription )																			
                                                                        <div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">
                                                                            <div class="pt-2 ml-2 mr-2 h-100 prices-responsive">
                                                                                <div class="card p-5 mb-4 pl-7 pr-7 h-100 price-card @if ($subscription->featured) price-card-border @endif">
                                                                                    @if ($subscription->featured)
                                                                                        <span class="plan-featured">{{ __('Most Popular') }}</span>
                                                                                    @endif
                                                                                    <div class="plan">			
                                                                                        <div class="plan-title">{{ $subscription->plan_name }}</div>
                                                                                        <p class="plan-cost mb-5">
                                                                                            @if ($subscription->free)
                                                                                                {{ __('Free') }}
                                                                                            @else
                                                                                                {!! config('payment.default_system_currecy_symbol') !!}@if(config('payment.decimal_points') == 'allow'){{ number_format((float)$subscription->price, 2) }} @else{{ number_format($subscription->price) }} @endif<span class="fs-12 text-muted"><span class="mr-1">/</span> {{ __('for lifetime') }}</span>
                                                                                            @endif	
                                                                                        </p>																				
                                                                                        <div class="text-center action-button mt-2 mb-5">
                                                                                            <a href="{{ route('register.subscriber.payment', ['id' => $subscription->id]) }}" class="btn btn-primary-pricing">{{ __('Select') }}</a>                                               														
                                                                                        </div>
                                                                                        <p class="fs-12 mb-3 text-muted">{{ __('Included Features') }}</p>																	
                                                                                        <ul class="fs-12 pl-3">		
                                                                                            @if ($subscription->words == -1)
                                                                                                <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li>
                                                                                            @else	
                                                                                                @if($subscription->words != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->words }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.image_feature_user') == 'allow')
                                                                                                @if ($subscription->images == -1)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('images / month') }}</span></li>
                                                                                                @else
                                                                                                    @if($subscription->images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->images }}</span> <span class="plan-feature-text">{{ __('images / month') }}</span></li> @endif
                                                                                                @endif																	
                                                                                            @endif
                                                                                            @if (config('settings.whisper_feature_user') == 'allow')
                                                                                                @if ($subscription->minutes == -1)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li>
                                                                                                @else
                                                                                                    @if($subscription->minutes != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->minutes }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li> @endif
                                                                                                @endif																	
                                                                                            @endif
                                                                                            @if (config('settings.voiceover_feature_user') == 'allow')
                                                                                                @if ($subscription->characters == -1)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li>
                                                                                                @else
                                                                                                    @if($subscription->characters != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->characters }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li> @endif
                                                                                                @endif																	
                                                                                            @endif
                                                                                                @if($subscription->team_members != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ $subscription->team_members }}</span> <span class="plan-feature-text">{{ __('team members') }}</span></li> @endif
                                                                                            
                                                                                            @if (config('settings.chat_feature_user') == 'allow')
                                                                                                @if($subscription->chat_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Chats Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.image_feature_user') == 'allow')
                                                                                                @if($subscription->image_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Images Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.voiceover_feature_user') == 'allow')
                                                                                                @if($subscription->voiceover_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Voiceover Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.whisper_feature_user') == 'allow')
                                                                                                @if($subscription->transcribe_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Speech to Text Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if (config('settings.code_feature_user') == 'allow')
                                                                                                @if($subscription->code_feature) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Code Feature') }}</span></li> @endif
                                                                                            @endif
                                                                                            @if($subscription->team_members) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Team Members Option') }}</span></li> @endif
                                                                                            @foreach ( (explode(',', $subscription->plan_features)) as $feature )
                                                                                                @if ($feature)
                                                                                                    <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ $feature }}</li>
                                                                                                @endif																
                                                                                            @endforeach															
                                                                                        </ul>																
                                                                                    </div>					
                                                                                </div>	
                                                                            </div>							
                                                                        </div>											
                                                                    @endforeach					
            
                                                                </div>
            
                                                            @else
                                                                <div class="row text-center">
                                                                    <div class="col-sm-12 mt-6 mb-6">
                                                                        <h6 class="fs-12 font-weight-bold text-center">{{ __('No lifetime plans were set yet') }}</h6>
                                                                    </div>
                                                                </div>
                                                            @endif
            
                                                        </div>	
                                                    @endif	
                                                </div>
                                            </div>
                                        
                                        @else
                                            <div class="row text-center">
                                                <div class="col-sm-12 mt-6 mb-6">
                                                    <h6 class="fs-12 font-weight-bold text-center">{{ __('No Subscription plans were set yet') }}</h6>
                                                </div>
                                            </div>
                                        @endif
            
                                        <div class="text-center">
                                            <p class="mb-0 mt-2"><i class="fa-solid fa-shield-check text-success mr-2"></i><span class="text-muted fs-12">{{ __('PCI DSS Compliant') }}</span></p>
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


