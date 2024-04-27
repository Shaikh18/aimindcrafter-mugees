

<?php $__env->startSection('page-header'); ?>
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center">
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0"><?php echo e(__('Update User Credits')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-id-badge mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.user.dashboard')); ?>"> <?php echo e(__('User Management')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.user.list')); ?>"><?php echo e(__('User List')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('Update User Credits')); ?></a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<div class="row justify-content-center">
		<div class="col-lg-8 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><i class="fa-solid fa-id-badge mr-2 text-primary fs-14"></i><?php echo e(__('Update User Credits')); ?></h3>
				</div>
				<div class="card-body">
					<form method="POST" action="<?php echo e(route('admin.user.increase', [$user->id])); ?>" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>
						
						<div class="row">

							<div class="col-sm-12 col-md-12 mt-2">
								<div>
									<p class="fs-12 mb-2"><?php echo e(__('Full Name')); ?>: <span class="font-weight-bold ml-2 text-primary"><?php echo e($user->name); ?></span></p>
									<p class="fs-12 mb-2"><?php echo e(__('Email Address')); ?>: <span class="font-weight-bold ml-2"><?php echo e($user->email); ?></span></p>
									<p class="fs-12 mb-2"><?php echo e(__('User Group')); ?>: <span class="font-weight-bold ml-2"><?php echo e(ucfirst($user->group)); ?></span></p>
								</div>
								<div class="row mt-4">
									<div class="col-sm-12 col-md-6">
										<p class="fs-12 mb-2"><?php echo e(__('Available Words')); ?>: <span class="font-weight-bold ml-2"><?php if($user->available_words == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_words)); ?> <?php endif; ?></span></p>
										<p class="fs-12 mb-2"><?php echo e(__('Available Dalle Images')); ?>: <span class="font-weight-bold ml-2"><?php if($user->available_dalle_images == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_dalle_images )); ?> <?php endif; ?></span></p>
										<p class="fs-12 mb-2"><?php echo e(__('Available Stable Diffusion Images')); ?>: <span class="font-weight-bold ml-2"><?php if($user->available_sd_images == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_sd_images )); ?> <?php endif; ?></span></p>
										<p class="fs-12 mb-2"><?php echo e(__('Available Characters')); ?>: <span class="font-weight-bold ml-2"><?php if($user->available_chars == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_chars)); ?> <?php endif; ?></span></p>
										<p class="fs-12 mb-2"><?php echo e(__('Available Minutes')); ?>: <span class="font-weight-bold ml-2"><?php if($user->available_minutes == -1): ?> <?php echo e(__('Unlimited')); ?> <?php else: ?> <?php echo e(number_format($user->available_minutes)); ?> <?php endif; ?></span></p>
									</div>
									<div class="col-sm-12 col-md-6">
										<p class="fs-12 mb-2"><?php echo e(__('Available Prepaid Words')); ?>: <span class="font-weight-bold ml-2"><?php echo e(number_format($user->available_words_prepaid)); ?></span></p>
										<p class="fs-12 mb-2"><?php echo e(__('Available Prepaid Dalle Images')); ?>: <span class="font-weight-bold ml-2"><?php echo e(number_format($user->available_dalle_images_prepaid)); ?></span></p>
										<p class="fs-12 mb-2"><?php echo e(__('Available Prepaid Stable Diffusion Images')); ?>: <span class="font-weight-bold ml-2"><?php echo e(number_format($user->available_sd_images_prepaid)); ?></span></p>
										<p class="fs-12 mb-2"><?php echo e(__('Available Prepaid Characters')); ?>: <span class="font-weight-bold ml-2"><?php echo e(number_format($user->available_chars_prepaid)); ?></span></p>
										<p class="fs-12 mb-2"><?php echo e(__('Available Prepaid Minutes')); ?>: <span class="font-weight-bold ml-2"><?php echo e(number_format($user->available_minutes_prepaid)); ?></span></p>
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-scroll-old mr-2 text-info"></i><?php echo e(__('User Word Credits')); ?></label>
										<input type="number" class="form-control <?php $__errorArgs = ['words'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value=<?php echo e($user->available_words); ?> name="words">
										<span class="text-muted fs-10"><?php echo e(__('Set as -1 for unlimited words')); ?></span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-scroll-old mr-2 text-info"></i><?php echo e(__('User Prepaid Word Credits')); ?></label>
										<input type="number" class="form-control <?php $__errorArgs = ['words_prepaid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value=<?php echo e($user->available_words_prepaid); ?> name="words_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-image mr-2 text-info"></i><?php echo e(__('User Dalle Image Credits')); ?></label>
										<input type="number" class="form-control <?php $__errorArgs = ['dalle-images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value=<?php echo e($user->available_dalle_images); ?> name="dalle-images">
										<span class="text-muted fs-10"><?php echo e(__('Set as -1 for unlimited images')); ?></span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-image mr-2 text-info"></i><?php echo e(__('User Prepaid Dalle Image Credits')); ?></label>
										<input type="number" class="form-control <?php $__errorArgs = ['dalle_images_prepaid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value=<?php echo e($user->available_dalle_images_prepaid); ?> name="dalle_images_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-image mr-2 text-info"></i><?php echo e(__('User Stable Diffusion Image Credits')); ?></label>
										<input type="number" class="form-control <?php $__errorArgs = ['sd-images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value=<?php echo e($user->available_sd_images); ?> name="sd-images">
										<span class="text-muted fs-10"><?php echo e(__('Set as -1 for unlimited images')); ?></span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-image mr-2 text-info"></i><?php echo e(__('User Prepaid Stable Diffusion Image Credits')); ?></label>
										<input type="number" class="form-control <?php $__errorArgs = ['sd_images_prepaid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value=<?php echo e($user->available_sd_images_prepaid); ?> name="sd_images_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-waveform-lines mr-2 text-info"></i><?php echo e(__('User Character Credits')); ?></label>
										<input type="number" class="form-control <?php $__errorArgs = ['chars'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value=<?php echo e($user->available_chars); ?> name="chars">	
										<span class="text-muted fs-10"><?php echo e(__('Set as -1 for unlimited characters')); ?></span>							
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-waveform-lines mr-2 text-info"></i><?php echo e(__('User Prepaid Character Credits')); ?></label>
										<input type="number" class="form-control <?php $__errorArgs = ['chars_prepaid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value=<?php echo e($user->available_chars_prepaid); ?> name="chars_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-folder-music mr-2 text-info"></i><?php echo e(__('User Minutes Credits')); ?></label>
										<input type="number" class="form-control <?php $__errorArgs = ['minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value=<?php echo e($user->available_minutes); ?> name="minutes">
										<span class="text-muted fs-10"><?php echo e(__('Set as -1 for unlimited minutes')); ?></span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-800"><i class="fa-solid fa-folder-music mr-2 text-info"></i><?php echo e(__('User Prepaid Minutes Credits')); ?></label>
										<input type="number" class="form-control <?php $__errorArgs = ['minutes_prepaid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value=<?php echo e($user->available_minutes_prepaid); ?> name="minutes_prepaid">									
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer border-0 text-center pr-0">							
							<a href="<?php echo e(route('admin.user.list')); ?>" class="btn btn-cancel mr-2"><?php echo e(__('Return')); ?></a>
							<button type="submit" class="btn btn-primary"><?php echo e(__('Update')); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/admin/users/list/increase.blade.php ENDPATH**/ ?>