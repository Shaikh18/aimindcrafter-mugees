@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Subscription Plans') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fa-solid fa-box-circle-check mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('user.plans') }}"> {{ __('Pricing Plans') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')	
	<div class="card border-0 pt-2">
		<div class="card-body">			
			
			@if ($monthly || $yearly || $prepaid || $lifetime)

				<div class="tab-menu-heading text-center">
					<div class="tabs-menu dark-theme-target" >								
						<ul class="nav">
							@if ($prepaid)						
								<li><a href="#prepaid" class="@if (!$monthly && !$yearly && $prepaid && !$lifetime) active @else '' @endif" data-bs-toggle="tab"> {{ __('Prepaid Plans') }}</a></li>
							@endif							
							@if ($monthly)
								<li><a href="#monthly_plans" class="@if (($monthly && $prepaid && $yearly) || ($monthly && !$prepaid && !$yearly) || ($monthly && $prepaid && !$yearly) || ($monthly && !$prepaid && $yearly)) active @else '' @endif" data-bs-toggle="tab"> {{ __('Monthly Plans') }}</a></li>
							@endif	
							@if ($yearly)
								<li><a href="#yearly_plans" class="@if ((!$monthly && !$prepaid && $yearly && !$lifetime) || (!$monthly && $prepaid && $yearly && !$lifetime) || (!$monthly && $prepaid && $yearly && $lifetime))  active @else '' @endif" data-bs-toggle="tab"> {{ __('Yearly Plans') }}</a></li>
							@endif
							@if ($lifetime)
								<li><a href="#lifetime" class="@if ((!$monthly && !$yearly && !$prepaid &&  $lifetime) || (!$monthly && !$yearly && $prepaid &&  $lifetime)) active @else '' @endif" data-bs-toggle="tab"> {{ __('Lifetime Plans') }}</a></li>
							@endif								
						</ul>
					</div>
				</div>

			

				<div class="tabs-menu-body">
					<div class="tab-content">

						@if ($prepaid)
							<div class="tab-pane @if ((!$monthly && $prepaid) && (!$yearly && $prepaid)) active @else '' @endif" id="prepaid">

								@if ($prepaids->count())
													
									<div class="row justify-content-md-center">
									
										@foreach ( $prepaids as $prepaid )																			
											<div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">
												<div class="price-card pl-3 pr-3 pt-2 mb-6">
													<div class="card p-4 pl-5 prepaid-cards @if ($prepaid->featured) price-card-border @endif">
														@if ($prepaid->featured)
															<span class="plan-featured-prepaid">{{ __('Most Popular') }}</span>
														@endif
														<div class="plan prepaid-plan">
															<div class="plan-title">{{ $prepaid->plan_name }} </div>
															<div class="plan-cost-wrapper mt-2 text-center mb-3 p-1"><span class="plan-cost">@if (config('payment.decimal_points') == 'allow') {{ number_format((float)$prepaid->price, 2) }} @else {{ number_format($prepaid->price) }} @endif</span><span class="prepaid-currency-sign text-muted">{{ $prepaid->currency }}</span></div>
															<p class="fs-12 mb-3 text-muted">{{ __('Package for') }} <span class="font-weight-bold">
																@switch($prepaid->model)
																	@case('text-davinci-003')
																		{{ __('GPT 3') }}
																		@break
																	@case('gpt-3.5-turbo')
																		{{ __('GPT 3.5 Turbo') }}
																		@break
																	@case('gpt-3.5-turbo-16k')
																		{{ __('GPT 3.5 Turbo (16K)') }}
																		@break
																	@case('gpt-4')
																		{{ __('GPT 4 (8K)') }}
																		@break
																	@case('gpt-4-32k')
																		{{ __('GPT 4 (32K)') }}
																		@break
																	@case('gpt-4-1106-preview')
																		{{ __('GPT 4 Turbo') }}
																		@break
																	@case('gpt-4-vision-preview')
																		{{ __('GPT 4 Turbo (Vision)') }}
																		@break
																	@default																		
																@endswitch
																 {{ __('model') }}</p>	
															<p class="fs-12 mb-3 text-muted">{{ __('Included Credits') }}</p>	
															<div class="credits-box">
																@if ($prepaid->words != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Words Included') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->words) }}</span></p>@endif
																 @if ($prepaid->dalle_images != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Dalle Images Included') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->dalle_images) }}</span></p>@endif
																 @if ($prepaid->sd_images != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('SD Images Included') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->sd_images) }}</span></p>@endif
																 @if ($prepaid->characters != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Characters Included') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->characters) }}</span></p>@endif																							
																 @if ($prepaid->minutes != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Minutes Included') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->minutes) }}</span></p>@endif	
															</div>
															<div class="text-center action-button mt-2 mb-2">
																<a href="{{ route('user.prepaid.checkout', ['type' => 'prepaid', 'id' => $prepaid->id]) }}" class="btn btn-primary-pricing">{{ __('Select Package') }}</a> 
															</div>																								                                                                          
														</div>							
													</div>	
												</div>							
											</div>										
										@endforeach						

									</div>

								@else
									<div class="row text-center">
										<div class="col-sm-12 mt-6 mb-6">
											<h6 class="fs-12 font-weight-bold text-center">{{ __('No Prepaid plans were set yet') }}</h6>
										</div>
									</div>
								@endif

							</div>			
						@endif	

						@if ($monthly)	
							<div class="tab-pane @if (($monthly && $prepaid) || ($monthly && !$prepaid) || ($monthly && !$yearly)) active @else '' @endif" id="monthly_plans">

								@if ($monthly_subscriptions->count())		

									<div class="row justify-content-md-center">

										@foreach ( $monthly_subscriptions as $subscription )																			
											<div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">
												<div class="pt-2 ml-2 mr-2 h-100 prices-responsive pb-6">
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
																@if (auth()->user()->plan_id == $subscription->id)
																	<a href="#" class="btn btn-primary-pricing">{{ __('Subscribed') }}</a> 
																@else
																	<a href="{{ route('user.plan.subscribe', $subscription->id) }}" class="btn btn-primary-pricing">{{ __('Subscribe Now') }}</a>
																@endif                                               														
															</div>
															<p class="fs-12 mb-3 text-muted">{{ __('Included Features') }}</p>																		
															<ul class="fs-12 pl-3">	
																@if ($subscription->words == -1)
																	<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li>
																@else	
																	@if($subscription->words != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->words) }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li> @endif
																@endif
																@if (config('settings.image_feature_user') == 'allow')
																	@if ($subscription->dalle_image_engine != 'none')
																		@if ($subscription->dalle_images == -1)
																			<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Dalle images / month') }}</span></li>
																		@else
																			@if($subscription->dalle_images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->dalle_images) }}</span> <span class="plan-feature-text">{{ __('Dalle images / month') }}</span></li> @endif
																		@endif
																	@endif																
																@endif
																@if (config('settings.image_feature_user') == 'allow')
																	@if ($subscription->sd_image_engine != 'none')
																		@if ($subscription->sd_images == -1)
																			<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('SD images / month') }}</span></li>
																		@else
																			@if($subscription->sd_images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->sd_images) }}</span> <span class="plan-feature-text">{{ __('SD images / month') }}</span></li> @endif
																		@endif
																	@endif																	
																@endif
																@if (config('settings.whisper_feature_user') == 'allow')
																	@if ($subscription->minutes == -1)
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li>
																	@else
																		@if($subscription->minutes != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->minutes) }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li> @endif
																	@endif																	
																@endif
																@if (config('settings.voiceover_feature_user') == 'allow')
																	@if ($subscription->characters == -1)
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li>
																	@else
																		@if($subscription->characters != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->characters) }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li> @endif
																	@endif																	
																@endif
																	@if($subscription->team_members != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->team_members) }}</span> <span class="plan-feature-text">{{ __('team members') }}</span></li> @endif
																
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
							<div class="tab-pane @if (($yearly && $prepaid) && ($yearly && !$prepaid) && ($yearly && !$monthly)) active @else '' @endif" id="yearly_plans">

								@if ($yearly_subscriptions->count())		

									<div class="row justify-content-md-center">

										@foreach ( $yearly_subscriptions as $subscription )																			
											<div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">
												<div class="pt-2 ml-2 mr-2 h-100 prices-responsive pb-6">
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
																@if (auth()->user()->plan_id == $subscription->id)
																	<a href="#" class="btn btn-primary-pricing">{{ __('Subscribed') }}</a> 
																@else
																	<a href="{{ route('user.plan.subscribe', $subscription->id) }}" class="btn btn-primary-pricing">{{ __('Subscribe Now') }}</a>
																@endif                                                														
															</div>
															<p class="fs-12 mb-3 text-muted">{{ __('Included Features') }}</p>																	
															<ul class="fs-12 pl-3">		
																@if ($subscription->words == -1)
																	<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li>
																@else	
																	@if($subscription->words != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->words) }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li> @endif
																@endif
																@if (config('settings.image_feature_user') == 'allow')
																	@if ($subscription->dalle_image_engine != 'none')
																		@if ($subscription->dalle_images == -1)
																			<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Dalle images / month') }}</span></li>
																		@else
																			@if($subscription->dalle_images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->dalle_images) }}</span> <span class="plan-feature-text">{{ __('Dalle images / month') }}</span></li> @endif
																		@endif
																	@endif																
																@endif
																@if (config('settings.image_feature_user') == 'allow')
																	@if ($subscription->sd_image_engine != 'none')
																		@if ($subscription->sd_images == -1)
																			<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('SD images / month') }}</span></li>
																		@else
																			@if($subscription->sd_images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->sd_images) }}</span> <span class="plan-feature-text">{{ __('SD images / month') }}</span></li> @endif
																		@endif
																	@endif																	
																@endif
																@if (config('settings.whisper_feature_user') == 'allow')
																	@if ($subscription->minutes == -1)
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li>
																	@else
																		@if($subscription->minutes != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->minutes) }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li> @endif
																	@endif																	
																@endif
																@if (config('settings.voiceover_feature_user') == 'allow')
																	@if ($subscription->characters == -1)
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li>
																	@else
																		@if($subscription->characters != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->characters) }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li> @endif
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
												<div class="pt-2 ml-2 mr-2 h-100 prices-responsive pb-6">
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
																	{!! config('payment.default_system_currency_symbol') !!}@if(config('payment.decimal_points') == 'allow'){{ number_format((float)$subscription->price, 2) }} @else{{ number_format($subscription->price) }} @endif<span class="fs-12 text-muted"><span class="mr-1">/</span> {{ __('for lifetime') }}</span>
																@endif
															</p>																					
															<div class="text-center action-button mt-2 mb-5">
																@if (auth()->user()->plan_id == $subscription->id)
																	<a href="#" class="btn btn-primary-pricing">{{ __('Subscribed') }}</a> 
																@else
																	<a href="{{ route('user.prepaid.checkout', ['type' => 'lifetime', 'id' => $subscription->id]) }}" class="btn btn-primary-pricing">{{ __('Subscribe Now') }}</a>
																@endif                                                 														
															</div>
															<p class="fs-12 mb-3 text-muted">{{ __('Included Features') }}</p>																	
															<ul class="fs-12 pl-3">		
																@if ($subscription->words == -1)
																	<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li>
																@else	
																	@if($subscription->words != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->words) }}</span> <span class="plan-feature-text">{{ __('words / month') }}</span></li> @endif
																@endif
																@if (config('settings.image_feature_user') == 'allow')
																	@if ($subscription->dalle_image_engine != 'none')
																		@if ($subscription->dalle_images == -1)
																			<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Dalle images / month') }}</span></li>
																		@else
																			@if($subscription->dalle_images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->dalle_images) }}</span> <span class="plan-feature-text">{{ __('Dalle images / month') }}</span></li> @endif
																		@endif
																	@endif																
																@endif
																@if (config('settings.image_feature_user') == 'allow')
																	@if ($subscription->sd_image_engine != 'none')
																		@if ($subscription->sd_images == -1)
																			<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('SD images / month') }}</span></li>
																		@else
																			@if($subscription->sd_images != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->sd_images) }}</span> <span class="plan-feature-text">{{ __('SD images / month') }}</span></li> @endif
																		@endif
																	@endif																	
																@endif
																@if (config('settings.whisper_feature_user') == 'allow')
																	@if ($subscription->minutes == -1)
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li>
																	@else
																		@if($subscription->minutes != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->minutes) }}</span> <span class="plan-feature-text">{{ __('minutes / month') }}</span></li> @endif
																	@endif																	
																@endif
																@if (config('settings.voiceover_feature_user') == 'allow')
																	@if ($subscription->characters == -1)
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li>
																	@else
																		@if($subscription->characters != 0) <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->characters) }}</span> <span class="plan-feature-text">{{ __('characters / month') }}</span></li> @endif
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
						<h6 class="fs-12 font-weight-bold text-center">{{ __('No Subscriptions or Prepaid plans were set yet') }}</h6>
					</div>
				</div>
			@endif

		</div>
	</div>
@endsection


