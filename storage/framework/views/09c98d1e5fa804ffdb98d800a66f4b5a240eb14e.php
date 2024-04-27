

<?php $__env->startSection('page-header'); ?>
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0"><?php echo e(__('User Information')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-id-badge mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.user.dashboard')); ?>"> <?php echo e(__('User Management')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.user.list')); ?>"><?php echo e(__('User List')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('View User Information')); ?></a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<!-- USER PROFILE PAGE -->
	<div class="row">
		<div class="col-xl-3 col-lg-3 col-md-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('Personal Information')); ?></h3>
				</div>
				<div class="overflow-hidden p-0">
					<div class="row">
						<div class="col-sm-12 border-bottom">
							<div class="text-center p-2">
								<div class="d-flex w-100">
									<div class="flex w-100">
										<h4 class="mb-3 mt-3 font-weight-800 fs-16"><?php if($user->available_words == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_words + $user->available_words_prepaid)); ?> <?php endif; ?></h4>
										<h6 class="fs-12 mb-3"><?php echo e(__('Words Left')); ?></h6>
									</div>			
									<div class="flex w-100">
										<h4 class="mb-3 mt-3 font-weight-800 fs-16"><?php if($user->available_dalle_images == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_dalle_images + $user->available_dalle_images_prepaid + $user->available_sd_images + $user->available_sd_images_prepaid)); ?> <?php endif; ?></h4>
										<h6 class="fs-12 mb-3"><?php echo e(__('DE/SD Images Left')); ?></h6>
									</div>	
								</div>	
								<div class="d-flex w-100">
									<div class="flex w-100">
										<h4 class="mb-3 mt-3 font-weight-800 fs-16"><?php if($user->available_chars == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_chars + $user->available_chars_prepaid)); ?> <?php endif; ?></h4>
										<h6 class="fs-12 mb-3"><?php echo e(__('Characters Left')); ?></h6>
									</div>			
									<div class="flex w-100">
										<h4 class="mb-3 mt-3 font-weight-800 fs-16"><?php if($user->available_minutes == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_minutes + $user->available_minutes_prepaid)); ?> <?php endif; ?></h4>
										<h6 class="fs-12 mb-3"><?php echo e(__('Minutes Left')); ?></h6>
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="widget-user-image overflow-hidden mx-auto mt-5"><img alt="User Avatar" class="rounded-circle" src="<?php if($user->profile_photo_path): ?> <?php echo e($user->profile_photo_path); ?> <?php else: ?> <?php echo e(URL::asset('img/users/avatar.jpg')); ?> <?php endif; ?>"></div>
				<div class="card-body text-center">				
					<div>
						<h4 class="mb-1 mt-1 font-weight-bold fs-16"><?php echo e($user->name); ?></h4>
						<h6 class="text-muted fs-12"><?php echo e($user->job_role); ?></h6>
						<a href="<?php echo e(route('admin.user.edit', [$user->id])); ?>" class="btn btn-primary mt-3 mb-2 mr-2"><i class="fa-solid fa-pencil mr-1"></i> <?php echo e(__('Update Profile')); ?></a>
						<a href="<?php echo e(route('admin.user.credit', [$user->id])); ?>" class="btn btn-primary mt-3 mb-2"><i class="fa-solid fa-scroll-old mr-1"></i><?php echo e(__('Update Credits')); ?></a>
						<a href="<?php echo e(route('admin.user.subscription', [$user->id])); ?>" class="btn btn-primary mt-3 mb-2"><i class="fa-solid fa-box-circle-check mr-1"></i><?php echo e(__('Add Subscription')); ?></a>
					</div>
				</div>
				
				<div class="card-body pt-0">
					<div class="table-responsive">
						<table class="table mb-0">
							<tbody>
								<tr>
									<td class="py-2 px-0 border-top-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Full Name')); ?> </span>
									</td>
									<td class="py-2 px-0 border-top-0"><?php echo e($user->name); ?></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Email')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->email); ?></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('User Status')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e(ucfirst($user->status)); ?></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('User Group')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e(ucfirst($user->group)); ?></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Referral ID')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->referral_id); ?></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Registered On')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->created_at); ?></td>
								</tr>								
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Last Updated On')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->updated_at); ?></td>
								</tr>								
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Job Role')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->job_role); ?></td>
								</tr>								
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Company')); ?></span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->company); ?></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Website')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->website); ?></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Address')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->address); ?></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Postal Code')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->postal_code); ?></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('City')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->city); ?></td>
								</tr>
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Country')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->country); ?></td>
								</tr>								
								<tr>
									<td class="py-2 px-0">
										<span class="font-weight-semibold w-50"><?php echo e(__('Phone')); ?> </span>
									</td>
									<td class="py-2 px-0"><?php echo e($user->phone_number); ?></td>
								</tr>
							</tbody>
						</table>
						<div class="border-0 text-right mb-2 mt-2">
							<a href="<?php echo e(route('admin.user.list')); ?>" class="btn btn-primary"><?php echo e(__('Return')); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-9 col-lg-9 col-md-12">
			<div class="row">

				<div class="row">

					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="row">
							<div class="col-lg-6 col-md-12 col-sm-12">
								<div class="card overflow-hidden border-0">
									<div class="card-body d-flex">
										<div class="usage-info w-100">
											<p class=" mb-3 fs-12 font-weight-bold"><?php echo e(__('Words Generated')); ?></p>
											<h2 class="mb-2 number-font fs-20"><?php echo e(number_format($data['words'])); ?> <span class="text-muted fs-18"><?php echo e(__('words')); ?></span></h2>
										</div>
										<div class="usage-icon w-100 text-right">
											<i class="fa-solid fa-scroll-old"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12 col-sm-12">
								<div class="card overflow-hidden border-0">
									<div class="card-body d-flex">
										<div class="usage-info w-100">
											<p class=" mb-3 fs-12 font-weight-bold"><?php echo e(__('Images Created')); ?></p>
											<h2 class="mb-2 number-font fs-20"><?php echo e(number_format($data['images'])); ?> <span class="text-muted fs-18"><?php echo e(__('images')); ?></span></h2>
										</div>
										<div class="usage-icon w-100 text-right">
											<i class="fa-solid fa-image-landscape"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12 col-sm-12">
								<div class="card overflow-hidden border-0">
									<div class="card-body d-flex">
										<div class="usage-info w-100">
											<p class=" mb-3 fs-12 font-weight-bold"><?php echo e(__('Characters Synthesized')); ?></p>
											<h2 class="mb-2 number-font fs-20"><?php echo e(number_format($data['characters'])); ?> <span class="text-muted fs-18"><?php echo e(__('characters')); ?></span></h2>
										</div>
										<div class="usage-icon w-100 text-right">
											<i class="fa-solid fa-waveform-lines"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12 col-sm-12">
								<div class="card overflow-hidden border-0">
									<div class="card-body d-flex">
										<div class="usage-info w-100">
											<p class=" mb-3 fs-12 font-weight-bold"><?php echo e(__('Minutes Transcribed')); ?></p>
											<h2 class="mb-2 number-font fs-20"><?php echo e(number_format((float)$data['minutes']/60, 2)); ?> <span class="text-muted fs-18"><?php echo e(__('minutes')); ?></span></h2>
										</div>
										<div class="usage-icon w-100 text-right">
											<i class="fa-solid fa-folder-music"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="card mb-5 border-0">
						<div class="card-header d-inline border-0">
							<div class="d-flex">
								<div class="w-100">
									<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-box-open mr-4 text-info"></i><?php echo e(__('Subscription')); ?></h3>
								</div>
								<div class="w-30">
									<div class="form-group mt-3">
										<label class="custom-switch">
											<input type="checkbox" name="hidden-plans" class="custom-switch-input" id="hidden-plans" onchange="toggleHidden()" <?php if( $user->hidden_plan): ?> checked <?php endif; ?>>
											<span class="custom-switch-indicator"></span>
											<span class="custom-switch-description"><?php echo e(__('Show Hidden Plans to this User')); ?></span>
										</label>
									</div>
								</div>
							</div>
							<?php if($user_subscription == ''): ?>
								<div>
									<h3 class="card-title fs-24 font-weight-800"><?php echo e(__('Free Trial')); ?></h3>
								</div>
								<div class="mb-1">
									<span class="fs-12 text-muted"><?php echo e(__('No Subscription ')); ?> / <?php echo config('payment.default_system_currency_symbol'); ?>0.00 <?php echo e(__('Per Month')); ?></span>
								</div>
							<?php else: ?>
								<div>
									<h3 class="card-title fs-24 font-weight-800"><?php if($user_subscription->payment_frequency == 'monthly'): ?> <?php echo e(__('Monthly Subscription')); ?> <?php elseif($user_subscription->payment_frequency == 'yearly'): ?> <?php echo e(__('Yearly Subscription')); ?> <?php else: ?> <?php echo e(__('Lifetime Subscription')); ?> <?php endif; ?></h3>
								</div>
								<div class="mb-1">
									<span class="fs-12 text-muted"><?php echo e($user_subscription->plan_name); ?> <?php echo e(__('Plan')); ?> / <?php echo config('payment.default_system_currency_symbol'); ?><?php echo e($user_subscription->price); ?> <?php if($user_subscription->payment_frequency == 'monthly'): ?> <?php echo e(__('Per Month')); ?> <?php elseif($user_subscription->payment_frequency == 'yearly'): ?> <?php echo e(__('Per Year')); ?> <?php else: ?> <?php echo e(__('One Time Payment')); ?> <?php endif; ?></span>
								</div>
							<?php endif; ?>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<?php if($user_subscription == ''): ?>
									<span class="fs-12 text-muted"><?php echo e(__('Total words available')); ?>: <?php if($user->available_words == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_words)); ?> <?php endif; ?>.</span> <span class="fs-12 text-muted"><?php echo e(__('Total prepaid words available ')); ?> <?php echo e(number_format($user->available_words_prepaid)); ?>. </span>
								<?php else: ?>
									<span class="fs-12 text-muted"><?php echo e(__('Total words available via subscription plan')); ?>: <?php if($user->available_words == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_words)); ?> <?php endif; ?>.</span> <span class="fs-12 text-muted"><?php echo e(__('Total prepaid words available ')); ?>: <?php echo e(number_format($user->available_words_prepaid)); ?>. </span>
								<?php endif; ?>
							</div>
							<div class="progress mb-4">
								<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning subscription-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo e($progress['words']); ?>%"></div>
							</div>
							<?php if($subscription): ?> 
							<div class="mb-3">
								<?php if($user_subscription->payment_frequency == 'lifetime'): ?>
									<span class="fs-12 text-muted"><?php echo e(__('Subscription renewal date')); ?>: <?php echo e(__('Never')); ?></span>
								<?php else: ?>
									<span class="fs-12 text-muted"><?php echo e(__('Subscription renewal date')); ?>: <?php echo e($subscription->active_until); ?> </span>
								<?php endif; ?>									
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="card border-0">
						<div class="card-header d-inline border-0">
							<div>
								<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-scroll-old mr-4 text-info"></i><?php echo e(__('Words & Images Generated')); ?> <span class="text-muted">(<?php echo e(__('Current Year')); ?>)</span></h3>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<!-- Chart JS -->
	<script src="<?php echo e(URL::asset('plugins/chart/chart.min.js')); ?>"></script>
	<script type="text/javascript">
		$(function() {
	
			'use strict';

			let usageData = JSON.parse(`<?php echo $chart_data['word_usage']; ?>`);
			let usageDataset = Object.values(usageData);
			let delayed;

			let ctx = document.getElementById('chart-user-usage');
			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['<?php echo e(__('Jan')); ?>', '<?php echo e(__('Feb')); ?>', '<?php echo e(__('Mar')); ?>', '<?php echo e(__('Apr')); ?>', '<?php echo e(__('May')); ?>', '<?php echo e(__('Jun')); ?>', '<?php echo e(__('Jul')); ?>', '<?php echo e(__('Aug')); ?>', '<?php echo e(__('Sep')); ?>', '<?php echo e(__('Oct')); ?>', '<?php echo e(__('Nov')); ?>', '<?php echo e(__('Dec')); ?>'],
					datasets: [{
						label: '<?php echo e(__('Words Generated')); ?>',
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
								stepSize: 2000,
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

		});

		function toggleHidden() {

			var formData = new FormData();
			formData.append("status", $('#hidden-plans').is(':checked') );
			formData.append("user_id", <?php echo e($user->id); ?>);

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'post',
				url: '/admin/users/plan',
				data: formData,
				processData: false,
				contentType: false,
				success: function (data) {
					if (data['status'] == 200) {
						toastr.success('Hidden subscription plans visibility updated');								
					} else {
						toastr.error('There was an issue setting hidden plan visibility status');
					}      
				},
				error: function(data) {
					toastr.error('There was an issue setting hidden plan visibility status');
				}
			})
		}
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/admin/users/list/show.blade.php ENDPATH**/ ?>