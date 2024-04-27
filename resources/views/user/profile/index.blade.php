@extends('layouts.app')

@section('css')
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('My Account') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-id-badge mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('My Account') }}</a></li>
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
						<h4 class="mb-1 mt-1 text-primary font-weight-bold fs-16">{{ auth()->user()->name }}</h4>
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
								<a href="{{ route('user.profile.edit') }}" class="fs-13"><i class="fa fa-calendar-lines-pen mr-1"></i> {{ __('Update Profile') }}</a>
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
							<div class="text-center pb-3">
								<a href="{{ route('user.security.2fa') }}" class="fs-13"><i class="fa fa-shield-check mr-1"></i> {{ __('2FA Authentication') }}</a>
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
			<div class="card border-0">
				<div class="card-body">
					<h4 class="card-title mb-4 mt-1">{{ __('Personal Details') }}</h4>
					<div class="table-responsive">
						<table class="table mb-0">
							<tbody>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50">{{ __('Full Name') }} </span>
									</td>
									<td class="py-2 px-0">{{ auth()->user()->name }}</td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50">{{ __('Email') }} </span>
									</td>
									<td class="py-2 px-0">{{ auth()->user()->email }}</td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50">{{ __('Referral ID') }} </span>
									</td>
									<td class="py-2 px-0"><span class="referral-value">{{ auth()->user()->referral_id }}</span> <a href="#" class="referral-edit-small" id="edit-referral">{{ __('Edit') }}</a></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50">{{ __('Referral Link') }} </span>
									</td>
									<td class="py-2 px-0">{{ config('app.url') }}/?ref=<span class="referral-value">{{ auth()->user()->referral_id }}</span></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50">{{ __('Job Role') }}</span>
									</td>
									<td class="py-2 px-0">{{ auth()->user()->job_role }}</td>
								</tr>								
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50">{{ __('Company') }}</span>
									</td>
									<td class="py-2 px-0">{{ auth()->user()->company }}</td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50">{{ __('Website') }} </span>
									</td>
									<td class="py-2 px-0">{{ auth()->user()->website }}</td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50">{{ __('City') }} </span>
									</td>
									<td class="py-2 px-0">{{ auth()->user()->city }}</td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50">{{ __('Country') }} </span>
									</td>
									<td class="py-2 px-0">{{ auth()->user()->country }}</td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50">{{ __('Phone') }} </span>
									</td>
									<td class="py-2 px-0">{{ auth()->user()->phone_number }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-9 col-lg-9 col-md-12">
			<div class="row">

				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="row">
						<div class="col-lg-4 col-md-6 col-sm-12">
							<div class="card overflow-hidden border-0">
								<div class="card-body d-flex">
									<div class="usage-info w-100">
										<p class=" mb-3 fs-12 font-weight-bold">{{ __('Documents Created') }}</p>
										<h2 class="mb-2 number-font fs-20">{{ number_format($data['contents']) }} <span class="text-muted fs-18">{{ __('documents') }}</span></h2>
									</div>
									<div class="usage-icon text-right">
										<i class="fa-solid fa-folder-grid"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-sm-12">
							<div class="card overflow-hidden border-0">
								<div class="card-body d-flex">
									<div class="usage-info w-100">
										<p class=" mb-3 fs-12 font-weight-bold">{{ __('Words Generated') }}</p>
										<h2 class="mb-2 number-font fs-20">{{ number_format($data['words']) }} <span class="text-muted fs-18">{{ __('words') }}</span></h2>
									</div>
									<div class="usage-icon text-right">
										<i class="fa-solid fa-scroll-old"></i>
									</div>
								</div>
							</div>
						</div>
						@role('user|subscriber|admin')
            				@if (config('settings.image_feature_user') == 'allow')
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="card overflow-hidden border-0">
										<div class="card-body d-flex">
											<div class="usage-info w-100">
												<p class=" mb-3 fs-12 font-weight-bold">{{ __('Images Created') }}</p>
												<h2 class="mb-2 number-font fs-20">{{ number_format($data['images']) }} <span class="text-muted fs-18">{{ __('images') }}</span></h2>
											</div>
											<div class="usage-icon text-right">
												<i class="fa-solid fa-image-landscape"></i>
											</div>
										</div>
									</div>
								</div>
							@endif
						@endrole
						@role('user|subscriber|admin')
            				@if (config('settings.voiceover_feature_user') == 'allow')
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="card overflow-hidden border-0">
										<div class="card-body d-flex">
											<div class="usage-info w-100">
												<p class=" mb-3 fs-12 font-weight-bold">{{ __('Voiceover Tasks') }}</p>
												<h2 class="mb-2 number-font fs-20">{{ number_format($data['synthesized']) }} <span class="text-muted fs-18">{{ __('tasks') }}</span></h2>
											</div>
											<div class="usage-icon text-right">
												<i class="fa-sharp fa-solid fa-waveform-lines"></i>
											</div>
										</div>
									</div>
								</div>
							@endif
						@endrole
						@role('user|subscriber|admin')
            				@if (config('settings.whisper_feature_user') == 'allow')
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="card overflow-hidden border-0">
										<div class="card-body d-flex">
											<div class="usage-info w-100">
												<p class=" mb-3 fs-12 font-weight-bold">{{ __('Audio Transcribed') }}</p>
												<h2 class="mb-2 number-font fs-20">{{ number_format($data['transcribed']) }} <span class="text-muted fs-18">{{ __('audio files') }}</span></h2>
											</div>
											<div class="usage-icon text-right">
												<i class="fa-sharp fa-solid fa-folder-music"></i>
											</div>
										</div>
									</div>
								</div>
							@endif
						@endrole
						@role('user|subscriber|admin')
            				@if (config('settings.code_feature_user') == 'allow')
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="card overflow-hidden border-0">
										<div class="card-body d-flex">
											<div class="usage-info w-100">
												<p class=" mb-3 fs-12 font-weight-bold">{{ __('Codes Generated') }}</p>
												<h2 class="mb-2 number-font fs-20">{{ number_format($data['codes']) }} <span class="text-muted fs-18">{{ __('codes') }}</span></h2>
											</div>
											<div class="usage-icon text-right">
												<i class="fa-solid fa-square-code"></i>
											</div>
										</div>
									</div>
								</div>
							@endif
						@endrole
					</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="card mb-5 border-0">
						<div class="card-header d-inline border-0">
							<div>
								<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-box-open mr-4 text-info"></i>{{ __('Subscription') }}</h3>
							</div>
							@if ($user_subscription == '')
								<div>
									<h3 class="card-title fs-24 font-weight-800">{{ __('Free Trial') }}</h3>
								</div>
								<div class="mb-1">
									<span class="fs-12 text-muted">{{ __('No Subscription') }} / {!! config('payment.default_system_currency_symbol') !!}0.00 {{ __('Per Month') }}</span>
								</div>
							@else
								<div>
									<h3 class="card-title fs-24 font-weight-800">@if ($user_subscription->payment_frequency == 'monthly') {{ __('Monthly Subscription') }} @elseif ($user_subscription->payment_frequency == 'yearly') {{ __('Yearly Subscription') }} @else {{ __('Lifetime Subscription') }} @endif</h3>
								</div>
								<div class="mb-1">
									<span class="fs-12 text-muted">{{ $user_subscription->plan_name }} {{ __('Plan') }} / {!! config('payment.default_system_currency_symbol') !!}{{ $user_subscription->price }} @if ($user_subscription->payment_frequency == 'monthly') {{ __('Per Month') }} @elseif($user_subscription->payment_frequency == 'yearly') {{ __('Per Year') }} @else {{ __('One Time Payment') }} @endif</span>
								</div>
							@endif
						</div>
						<div class="card-body">
							<div class="mb-3">
								@if ($user_subscription == '')
								<span class="fs-12 text-muted">{{ __('Total words available via subscription plan') }}: @if (auth()->user()->available_words == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_words) }} @endif.</span> <span class="fs-12 text-muted">{{ __('Total prepaid words available ') }}: {{ number_format(auth()->user()->available_words_prepaid) }}. </span>
								@else
									<span class="fs-12 text-muted">{{ __('Total words available via subscription plan') }} {{ number_format(auth()->user()->available_words) }} {{ __('out of') }} {{ number_format(auth()->user()->total_words) }}. </span> <span class="fs-12 text-muted">{{ __('Total prepaid words available') }} {{ number_format(auth()->user()->available_words_prepaid) }}. </span>
								@endif
							</div>
							<div class="progress mb-4">
								<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning subscription-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress['words'] }}%"></div>
							</div>
							@if ($subscription) 
								<div class="mb-3">
									@if ($user_subscription->payment_frequency == 'lifetime')
										<span class="fs-12 text-muted">{{ __('Subscription renewal date') }}: {{ __('Never') }}</span>
									@else
										<span class="fs-12 text-muted">{{ __('Subscription renewal date') }}: {{ $subscription->active_until }} </span>
									@endif									
								</div>
							@endif
						</div>
					</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="card border-0">
						<div class="card-header d-inline border-0">
							<div>
								<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-scroll-old mr-4 text-info"></i>{{ __('Words & Images Generated') }} <span class="text-muted">({{ __('Current Year') }})</span></h3>
							</div>
						</div>
						<div class="card-body">
							<div class="chartjs-wrapper-demo">
								<canvas id="chart-user-usage" class="h-330"></canvas>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
	<!-- END USER PROFILE PAGE -->
@endsection

@section('js')
	<!-- Chart JS -->
	<script src="{{URL::asset('plugins/chart/chart.min.js')}}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script>
		$(function() {
	
			'use strict';

			let usageData = JSON.parse(`<?php echo $chart_data['word_usage']; ?>`);
			let usageDataset = Object.values(usageData);
			let usageData2 = JSON.parse(`<?php echo $chart_data['image_usage']; ?>`);
			let usageDataset2 = Object.values(usageData2);
			let delayed;

			let ctx = document.getElementById('chart-user-usage');
			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['{{ __('Jan') }}', '{{ __('Feb') }}', '{{ __('Mar') }}', '{{ __('Apr') }}', '{{ __('May') }}', '{{ __('Jun') }}', '{{ __('Jul') }}', '{{ __('Aug') }}', '{{ __('Sep') }}', '{{ __('Oct') }}', '{{ __('Nov') }}', '{{ __('Dec') }}'],
					datasets: [{
						label: '{{ __('Images Created') }}',
						data: usageDataset2,
						backgroundColor: '#FF9D00',
						borderWidth: 1,
						borderRadius: 20,
						barPercentage: 0.5,
						fill: true
					},{
						label: '{{ __('Words Generated') }}',
						data: usageDataset,
						backgroundColor: '#007bff',
						borderWidth: 1,
						borderRadius: 20,
						barPercentage: 0.5,
						fill: true
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false,
						labels: {
							display: false
						}
					},
					responsive: true,
					animation: {
						onComplete: () => {
							delayed = true;
						},
						delay: (context) => {
							let delay = 0;
							if (context.type === 'data' && context.mode === 'default' && !delayed) {
								delay = context.dataIndex * 50 + context.datasetIndex * 5;
							}
							return delay;
						},
					},
					scales: {
						y: {
							stacked: true,
							ticks: {
								beginAtZero: true,
								font: {
									size: 10
								},
								stepSize: 50000,
							},
							grid: {
								color: '#ebecf1',
								borderDash: [3, 2]                            
							}
						},
						x: {
							stacked: true,
							ticks: {
								font: {
									size: 10
								}
							},
							grid: {
								color: '#ebecf1',
								borderDash: [3, 2]                            
							}
						},
					},
					plugins: {
						tooltip: {
							cornerRadius: 10,
							xPadding: 10,
							yPadding: 10,
							backgroundColor: '#000000',
							titleColor: '#FF9D00',
							yAlign: 'bottom',
							xAlign: 'center',
						},
						legend: {
							position: 'bottom',
							labels: {
								boxWidth: 10,
								font: {
									size: 10
								}
							}
						}
					}
					
				}
			});


			// UPDATE DESCRIPTION
			$(document).on('click', '#edit-referral', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Change Referral ID') }}',
					showCancelButton: true,
					confirmButtonText: '{{ __('Change') }}',
					reverseButtons: true,
					input: 'text',
				}).then((result) => {
					if (result.value) {
						var formData = new FormData();
						formData.append("value", result.value);
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'profile/change/referral',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data['status'] == 'success') {
									Swal.fire('{{ __('Referral ID Updated') }}', '{{ __('Referral ID has been successfully changed') }}', 'success');
									$('.referral-value').html(result.value);
								} else {
									Swal.fire('{{ __('Referral ID Update Error') }}', data['message'], 'warning');
								}      
							},
							error: function(data) {
								Swal.fire('Update Error', data.responseJSON['error'], 'error');
							}
						})
					} else if (result.dismiss !== Swal.DismissReason.cancel) {
						Swal.fire('{{ __('No Referral ID Entered') }}', '{{ __('Make sure to provide a new referral ID before changing') }}', 'warning')
					}
				})
			});
		});
	</script>
@endsection
