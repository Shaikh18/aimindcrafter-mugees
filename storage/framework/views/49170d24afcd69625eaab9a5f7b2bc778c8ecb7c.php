
<?php $__env->startSection('css'); ?>
	<link href="<?php echo e(URL::asset('plugins/sweetalert/sweetalert2.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0"><?php echo e(__('Activation')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa fa-sliders mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(url('#')); ?>"> <?php echo e(__('General Settings')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo e(url('#')); ?>"> <?php echo e(__('Activation')); ?></a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<div class="row justify-content-center">
		<div class="col-xl-4 col-lg-4 col-sm-12">
			<div class="card border-0">				
				<div class="card-body pt-7 pl-7 pr-7 pb-6">
					<form method="POST" action="<?php echo e(route('admin.settings.activation.store')); ?>" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>
						
						<div class="row">

							<div class="col-sm-12">								
								<div class="text-center mb-7">
									<div class="mb-7">
										<img src="<?php echo e(URL::asset('/img/files/lock.webp')); ?>" alt="" style="width:200px">
									</div>
									<h3 class="card-title fs-18"><?php echo e(__('License Status')); ?>: <?php if($notification): ?> <span class="text-success font-weight-bold"><?php echo e(__('Activated')); ?></span> <?php else: ?> <span class="text-danger fs-24 font-weight-bold"><?php echo e(__('Not Activated')); ?></span><?php endif; ?></h3>
									<h3 class="card-title fs-12 mt-6 font-weight-bold"><?php echo e(__('License Type')); ?>: <span class="text-primary font-weight-bold" style="padding: 0.2rem 1.5rem; margin-left: 0.5rem; border-radius: 1rem; background:#e1f0ff; "><?php echo e($type); ?></span></h3>
								</div>
							</div>

							<div class="col-sm-12 col-md-12">
								<div class="input-box">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold text-muted"><?php echo e(__('Your Activation Code')); ?></label>
										<input type="text" class="form-control <?php $__errorArgs = ['license'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="license" value="<?php echo e($information['license']); ?>" required>
										<?php $__errorArgs = ['license'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('license')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-12">
								<div class="input-box mb-1">
									<div class="form-group mb-1">
										<label class="form-label fs-12 font-weight-bold text-muted"><?php echo e(__('Your Envato Username')); ?></label>
										<input type="text" class="form-control <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="username" value="<?php echo e($information['username']); ?>" required>
										<?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('username')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>									
									</div>
								</div>
								
							</div>
						</div>
						<div class="card-footer border-0 text-center pb-2 pt-5 pr-0">							
							<?php if(!$notification): ?>
								<button type="submit" class="btn btn-primary pl-7 pr-7"><?php echo e(__('Activate')); ?></button>						
							<?php else: ?>
								<a class="btn btn-primary pl-7 pr-7" id="deactivateButton"><?php echo e(__('Deactivate')); ?></a>
							<?php endif; ?>							
						</div>		
					</form>
				</div>
			</div>
		</div>
	</div>
	
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<script src="<?php echo e(URL::asset('plugins/sweetalert/sweetalert2.all.min.js')); ?>"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";


			$(document).on('click', '#deactivateButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '<?php echo e(__('Confirm License Deactivation')); ?>',
					text: '<?php echo e(__('Are you sure you want to deactivate your license?')); ?>',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: '<?php echo e(__('Yes, Deactivate')); ?>',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: '/admin/settings/activation/destroy',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('<?php echo e(__('License Deactivated')); ?>', '<?php echo e(__('License has been successfully deactivated')); ?>', 'success');	
									setTimeout(function(){
										window.location.reload();
									}, 2000);							
								} else {
									Swal.fire('<?php echo e(__('Dectivation Failed')); ?>', '<?php echo e(__('There was an error while deactivating your license')); ?>', 'error');
								}      
							},
							error: function(data) {
								Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
							}
						})
					} 
				})
			});
	
		});
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/admin/settings/activation/index.blade.php ENDPATH**/ ?>