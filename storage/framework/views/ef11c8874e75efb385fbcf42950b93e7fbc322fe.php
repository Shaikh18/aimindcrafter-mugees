

<?php $__env->startSection('css'); ?>
	<!-- Data Table CSS -->
	<link href="<?php echo e(URL::asset('plugins/datatable/datatables.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
	<!-- PAGE HEADER -->
	<div class="page-header justify-content-center mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0"><?php echo e(__('Affiliate Program')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('user.dashboard')); ?>"><i class="fa-solid fa-badge-dollar mr-2 fs-12"></i><?php echo e(__('User')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo e(route('user.referral')); ?>"> <?php echo e(__('Affiliate Program')); ?></a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>	
	<div class="row justify-content-center">
		<div class="col-lg-10 col-md-12 col-sm-12 mb-4">
			<div class="card overflow-hidden border-0">
				<div class="card-body p-6 referral-body-bg">
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<div>
								<h3 class="card-title fs-20"><i class="fa-solid fa-badge-dollar mr-2 text-yellow"></i><?php echo e(__('Affiliate Program')); ?></h3>
								<?php if(config('payment.referral.payment.policy') == 'first'): ?>
									<p class="fs-14 text-muted"><?php echo e(__('Invite your friends and earn commissions from the first purchase they make')); ?>.</p>
								<?php else: ?>
									<p class="fs-14 text-muted"><?php echo e(__('Invite your friends and earn lifelong recurring commissions from every purchase they make')); ?>.</p>
								<?php endif; ?>								
							</div>
							<div class="mt-6">						
								<div class="input-box mb-0">		
									<h6 class="fs-12 font-weight-bold poppins"><?php echo e(__('Referral Link')); ?></h6>							
									<div class="form-group d-flex referral-social-icons">									 							    
										<input type="text" class="form-control" id="email" readonly value="<?php echo e(config('app.url')); ?>/?ref=<?php echo e(auth()->user()->referral_id); ?>">
										<div class="ml-2">
											<a href="" class="btn create-project pb-2" id="actions-copy" data-link="<?php echo e(config('app.url')); ?>/?ref=<?php echo e(auth()->user()->referral_id); ?>" data-tippy-content="<?php echo e(__('Copy Referral Link')); ?>"><i class="fa fa-link"></i></a>										
										</div>
									</div> 
								</div>							
							</div>
						</div>
						<div class="col-md-7 col-sm-12 justify-content-center">
							<div class="row justify-content-center pt-4">
								<div class="col-md-2"></div>
								<div class="col-md-4 col-sm-12 text-center">
									<h6 class="referral-value-heading fs-13 mb-0 font-weight-bold"><?php echo e(__('Referred')); ?></h6>
									<p class="referral-value fs-55 mb-0 font-weight-bold"><?php echo e($total_referred[0]['data']); ?></p>
									<p class="referral-value-footer fs-13 text-muted mb-0"><?php echo e(__('Total Referred Users')); ?></p>
									<p class="referral-value-footer fs-13 text-muted mb-0"><?php echo e(__('Invite more to Earn more')); ?></p>
								</div>
								<div class="col-md-6 col-sm-12 text-center">
									<h6 class="referral-value-heading fs-13 mb-0 font-weight-bold"><?php echo e(__('Earnings')); ?></h6>
									<p class="referral-value fs-55 mb-0 font-weight-bold"><?php echo config('payment.default_system_currency_symbol'); ?><?php echo e(number_format((float)$total_commission[0]['data'], 2, '.', '')); ?> <?php echo e(config('payment.default_currency')); ?></p>
									<p class="referral-value-footer fs-13 mb-0"><span class="text-muted"><?php echo e(__('Referral Commission Rate')); ?>:</span> <span class="font-weight-bold"><?php echo e(config('payment.referral.payment.commission')); ?>%</span></p>
									<p class="referral-value-footer fs-13 mb-0"><span class="text-muted"><?php echo e(__('Referral Program')); ?>:</span>
										<?php if(config('payment.referral.payment.policy') == 'first'): ?>
											<span class="font-weight-bold"><?php echo e(__('First Purchase')); ?></span>
										<?php else: ?>
											<span class="font-weight-bold"><?php echo e(__('All Purchases')); ?></span>
										<?php endif; ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-5 col-md-12 col-sm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body p-6">
					<h3 class="card-title fs-20 text-center"><?php echo e(__('How it Works')); ?></h3>
					
					<div class="row text-center justify-content-md-center mt-6">
						<div class="col-lg-3 col-md-3 col-sm-4">
							<div class="referral-icon-user">
								<i class="fa-solid fa-message-check"></i>
								<h6 class="mt-3 fs-12 font-weight-bold">1. <?php echo e(__('Send Invitation')); ?></h6>
								<p class="fs-12"><?php echo e(__('Send your referral link to your friends and tell them how cool is')); ?> <?php echo e(config('app.name')); ?>!</p>
							</div>							
						</div>
						<div class="col-lg-3 col-md-3 col-sm-4">
							<div class="referral-icon-user">
								<i class="fa-solid fa-user-check"></i>
								<h6 class="mt-3 fs-12 font-weight-bold">2. <?php echo e(__('Registration')); ?></h6>
								<p class="fs-12"><?php echo e(__('Let your friends register using your referral link that you shared')); ?>.</p>
							</div>														
						</div>
						<div class="col-lg-3 col-md-3 col-sm-4">
							<div class="referral-icon-user">
								<i class="fa-solid fa-badge-percent"></i>
								<h6 class="mt-3 fs-12 font-weight-bold">3. <?php echo e(__('Get Commissions')); ?></h6>
								<?php if(config('payment.referral.payment.policy') == 'first'): ?>
									<p class="fs-12"><?php echo e(__('Earn commission for their first subscription plan payments')); ?>.</p>
								<?php else: ?>
									<p class="fs-12"><?php echo e(__('Earn commission for all their subscription plan payments')); ?>.</p>
								<?php endif; ?>
							</div>							
						</div>
					</div>

					<form id="" action="<?php echo e(route('user.referral.email')); ?>" method="post" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>
						<div class="row mt-6 ml-4 mr-4">
							<div class="col-md-12">
								<h6 class="fs-12 font-weight-bold"><?php echo e(__('Invite your friends')); ?></h6>
								<div class="input-box">								
									<p class="fs-12 text-muted mb-2"><?php echo e(__('Insert your friends email address and send him an invitations to join')); ?> <?php echo e(config('app.name')); ?></p> 
									<div class="input-group file-browser">							    
										<input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> border-right-0 browse-file" id="email" name="email" placeholder="<?php echo e(__('Email address')); ?>" style="margin-right: 80px;">
										<label class="input-group-btn">
											<button class="btn btn-primary special-btn" id="invite-friends-button">
												<?php echo e(__('Invite')); ?>

											</button>
										</label>
									</div> 
									<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<p class="text-danger"><?php echo e($errors->first('email')); ?></p>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<input type="text" name="subject" value="Introduction to join <?php echo e(config('app.name')); ?>" hidden>
								<input type="text" name="message" value="I want to refer you to this awesome website that I'm using! You can register via this link: <?php echo e(config('app.url')); ?>/?ref=<?php echo e(auth()->user()->referral_id); ?>" hidden>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>

		<div class="col-lg-5 col-md-12 col-sm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body p-6">
					<h3 class="card-title fs-20 text-center"><?php echo e(__('Payout Request')); ?></h3>
					<p class="fs-12 text-center mb-2"><?php echo e(__('Set your payout details and receive your commissions')); ?></p>

					<form action="<?php echo e(route('user.referral.payout.store')); ?>" method="POST" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>				

							<div class="row text-center">
								<div class="col-md-12 col-sm-12">
									<div id="storage-type-radio" role="radiogroup">
										<div class="radio-control">
											<input type="radio" name="payment_method" class="input-control" id="PayPal" value="PayPal" <?php if(auth()->user()->referral_payment_method == 'PayPal'): ?> checked <?php endif; ?> style="vertical-align: middle;">
											<label for="PayPal" class="label-control fs-12">PayPal</label>
										</div>	
										<span  id="option-bank">
											<div class="radio-control">
												<input type="radio" name="payment_method" class="input-control" id="BankTransfer" value="BankTransfer" <?php if(auth()->user()->referral_payment_method == 'BankTransfer'): ?> checked <?php endif; ?> style="vertical-align: middle;">
												<label for="BankTransfer" class="label-control fs-12"><?php echo e(__('Bank Transfer')); ?></label>
											</div>
										</span>									
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12">								
									<div class="input-box">								
										<h6><?php echo e(__('PayPal ID')); ?></h6>
										<div class="form-group">							    
											<input type="text" class="form-control <?php $__errorArgs = ['paypal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="paypal" name="paypal" value="<?php echo e(auth()->user()->referral_paypal); ?>">
										</div> 
										<?php $__errorArgs = ['paypal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('paypal')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div>

								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="input-box">								
										<h6><?php echo e(__('Bank Account Requisites')); ?></h6> 
										<textarea class="form-control" name="bank_requisites" id="bank_requisites" rows="2" placeholder="Bank Name: 
Account Name/Full Name:
Account Number/IBAN:
BIC/Swift:
Routing Number:"><?php echo e(auth()->user()->referral_bank_requisites); ?></textarea>
										<?php $__errorArgs = ['bank_requisites'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('bank_requisites')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div>	

								<div class="col-lg-12 col-md-12 col-sm-12">								
									<div class="input-box">								
										<h6><?php echo e(__('Requested Amount')); ?></h6>
										<div class="form-group">							    
											<input type="number" class="form-control <?php $__errorArgs = ['payout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="payout" name="payout" value="<?php echo e(old('payout')); ?>" placeholder="<?php echo e(__('Minimum allowed amount is ')); ?><?php echo config('payment.default_system_currency_symbol'); ?><?php echo e(config('payment.referral.payment.threshold')); ?>">
										</div> 
										<?php $__errorArgs = ['payout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('payout')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div>

								<div class="col-md-12">
									<div class="border-0 text-center">
										<button type="submit" class="btn btn-primary"><?php echo e(__('Request')); ?></button>							
									</div>
								</div>
								
							</div>

					</form>

				</div>
			</div>
		</div>

		<div class="col-lg-10 col-md-12 col-sm-12 mt-4">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('My Payout Requests')); ?> <span class="text-muted">(<?php echo e(__('All Time')); ?>)</h3>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='payoutsTable' class='table' width='100%'>
						<thead>
							<tr>
								<th width="10%"><?php echo e(__('Requested Date')); ?></th>
								<th width="10%"><?php echo e(__('Request ID')); ?></th>																	
								<th width="7%"><?php echo e(__('Total Amount')); ?> (<?php echo e(config('payment.default_system_currency')); ?>)</th>																									
								<th width="10%"><?php echo e(__('Preferred Payment Gateway')); ?></th>
								<th width="10%"><?php echo e(__('Status')); ?></th>
								<th width="5%"><?php echo e(__('Actions')); ?></th>
							</tr>
						</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>

		<div class="col-lg-10 col-md-12 col-sm-12 mt-4">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('Earned Commissions')); ?> <span class="text-muted">(<?php echo e(__('All Time')); ?>)</span></h3>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='paymentsReferralTable' class='table' width='100%'>
						<thead>
							<tr>
								<th width="10%" class="fs-10"><?php echo e(__('Purchase Date')); ?></th>
								<th width="12%" class="fs-10"><?php echo e(__('Order ID')); ?></th>																
								<th width="10%" class="fs-10"><?php echo e(__('Total Payment')); ?> (<?php echo e(config('payment.default_system_currency')); ?>)</th>																									
								<th width="10%" class="fs-10"><?php echo e(__('Commision Rate')); ?></th>																									
								<th width="7%" class="fs-10"><?php echo e(__('Earned Commissions')); ?> (<?php echo e(config('payment.default_system_currency')); ?>)</th>
							</tr>
						</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>
	</div>

	<!-- MODAL -->
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel"><i class="mdi mdi-alert-circle-outline color-red"></i> <?php echo e(__('Confirm Payout Request Cancellation')); ?></h4>
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="deleteModalBody">
					<div>
						<!-- DELETE CONFIRMATION -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END MODAL -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<script src="<?php echo e(URL::asset('plugins/datatable/datatables.min.js')); ?>"></script>
	<script src="<?php echo e(URL::asset('js/link-share.js')); ?>"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			// INITILIZE DATATABLE
			var table = $('#payoutsTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				"order": [[ 0, "desc" ]],
				language: {
					search: "<i class='fa fa-search search-icon'></i>",
					lengthMenu: '_MENU_ ',
					paginate : {
						first    : '<i class="fa fa-angle-double-left"></i>',
						last     : '<i class="fa fa-angle-double-right"></i>',
						previous : '<i class="fa fa-angle-left"></i>',
						next     : '<i class="fa fa-angle-right"></i>'
					}
				},
				pagingType : 'full_numbers',
				processing: true,
				serverSide: true,
				ajax: "<?php echo e(route('user.referral.payout')); ?>",
				columns: [{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},
					{
						data: 'request_id',
						name: 'request_id',
						orderable: true,
						searchable: true
					},															
					{
						data: 'custom-total',
						name: 'custom-total',
						orderable: true,
						searchable: true
					},	
					{
						data: 'gateway',
						name: 'gateway',
						orderable: true,
						searchable: true
					},	
					{
						data: 'custom-status',
						name: 'custom-status',
						orderable: true,
						searchable: true
					},		
					{
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
					},
				]
			});

			// INITILIZE DATATABLE
			var table = $('#paymentsReferralTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				"order": [[ 0, "desc" ]],
				language: {
					search: "<i class='fa fa-search search-icon'></i>",
					"info": "<?php echo e(__('Showing page')); ?> _PAGE_ <?php echo e(__('of')); ?> _PAGES_",
					lengthMenu: '_MENU_ ',
					paginate : {
						first    : '<i class="fa fa-angle-double-left"></i>',
						last     : '<i class="fa fa-angle-double-right"></i>',
						previous : '<i class="fa fa-angle-left"></i>',
						next     : '<i class="fa fa-angle-right"></i>'
					}
				},
				pagingType : 'full_numbers',
				processing: true,
				serverSide: true,
				ajax: "<?php echo e(route('user.referral.referrals')); ?>",
				columns: [{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},
					{
						data: 'order_id',
						name: 'order_id',
						orderable: true,
						searchable: true
					},									
					{
						data: 'custom-payment',
						name: 'custom-payment',
						orderable: true,
						searchable: true
					},	
					{
						data: 'custom-rate',
						name: 'custom-rate',
						orderable: true,
						searchable: true
					},					
					{
						data: 'custom-commission',
						name: 'custom-commission',
						orderable: true,
						searchable: true
					},				

				]
			});

			// DELETE CONFIRMATION MODAL
			$(document).on('click', '#deletePayoutButton', function(event) {
				event.preventDefault();
				let href = $(this).attr('data-attr');
				$.ajax({
					url: href
					, beforeSend: function() {
						$('#loader').show();
					},
					// return the result
					success: function(result) {
						$('#deleteModal').modal("show");
						$('#deleteModalBody').html(result).show();
					}
					, error: function(jqXHR, testStatus, error) {
						console.log(error);
						alert("Page " + href + " cannot open. Error:" + error);
						$('#loader').hide();
					}
					, timeout: 8000
				})
			});

		});
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/user/referrals/index.blade.php ENDPATH**/ ?>