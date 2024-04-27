

<?php $__env->startSection('page-header'); ?>
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center">
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0"> <?php echo e(__('Appearance Settings')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa fa-globe mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(url('#')); ?>"> <?php echo e(__('Frontend Management')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo e(url('#')); ?>"> <?php echo e(__('SEO & Logos')); ?></a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>					
	<div class="row justify-content-center">
		<div class="col-lg-5 col-md-12 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('Setup Appearance Settings')); ?></h3>
				</div>
				<div class="card-body">
				
					<form action="<?php echo e(route('admin.settings.appearance.store')); ?>" method="POST" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>

						<div class="row">
							<div class="col-md-6 col-sm-12">									
								<div class="input-box">								
									<h6><?php echo e(__('SEO Title')); ?></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="title" name="title" value="<?php echo e($information['title']); ?>" autocomplete="off">
										<?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('title')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>	

							<div class="col-md-6 col-sm-12">									
								<div class="input-box">								
									<h6><?php echo e(__('SEO Author')); ?></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['author'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="author" name="author" value="<?php echo e($information['author']); ?>" autocomplete="off">
										<?php $__errorArgs = ['author'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('author')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>

							<div class="col-md-12 col-sm-12">									
								<div class="input-box">								
									<h6><?php echo e(__('SEO Keywords')); ?></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="keywords" name="keywords" value="<?php echo e($information['keywords']); ?>" autocomplete="off">
										<?php $__errorArgs = ['keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('keywords')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>
							
							<div class="col-md-12 col-sm-12">									
								<div class="input-box">								
									<h6><?php echo e(__('SEO Description')); ?></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" value="<?php echo e($information['description']); ?>" autocomplete="off">
										<?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('description')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>							
						
						</div>
						
						<div class="card border-0 special-shadow">
							<div class="card-body">

								<h6 class="fs-12 font-weight-bold mb-4"><?php echo e(__('Primary Logo')); ?></h6>

								<div class="row">

									<div class="col-sm-12 col-md-6">
										<div class="input-box border">
											<img src="<?php echo e(URL::asset('img/brand/logo.png')); ?>" alt="Main Logo">
										</div>
									</div>

									<div class="col-sm-12 col-md-6">
										<div class="input-box">
											<label class="form-label fs-12"><?php echo e(__('Select Logo')); ?> <span class="text-muted">(<?php echo e(__('Recommended Size')); ?>)</span></label>
											<div class="input-group file-browser">									
												<input type="text" class="form-control border-right-0 browse-file" placeholder="240px by 70px PNG image" readonly>
												<label class="input-group-btn">
													<span class="btn btn-primary special-btn">
														<?php echo e(__('Browse')); ?> <input type="file" name="main_logo" style="display: none;">
													</span>
												</label>
											</div>
											<?php $__errorArgs = ['main_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p class="text-danger"><?php echo e($errors->first('main_logo')); ?></p>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
									</div>					

								</div>
							</div>
						</div>

						<div class="card border-0 special-shadow">
							<div class="card-body">

								<h6 class="fs-12 font-weight-bold mb-4"><?php echo e(__('Footer Logo')); ?></h6>

								<div class="row">

									<div class="col-sm-12 col-md-6">
										<div class="input-box border bg-dark">
											<img src="<?php echo e(URL::asset('img/brand/logo-white.png')); ?>" alt="Footer Logo">
										</div>
									</div>

									<div class="col-sm-12 col-md-6">
										<div class="input-box">
											<label class="form-label fs-12"><?php echo e(__('Select Logo')); ?> <span class="text-muted">(<?php echo e(__('Recommended Size')); ?>)</span></label>
											<div class="input-group file-browser">									
												<input type="text" class="form-control border-right-0 browse-file" placeholder="240px by 70px PNG image" readonly>
												<label class="input-group-btn">
													<span class="btn btn-primary special-btn">
														<?php echo e(__('Browse')); ?> <input type="file" name="footer_logo" style="display: none;">
													</span>
												</label>
											</div>
											<?php $__errorArgs = ['footer_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p class="text-danger"><?php echo e($errors->first('footer_logo')); ?></p>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
									</div>					

								</div>
							</div>
						</div>

						<div class="card border-0 special-shadow">
							<div class="card-body">

								<h6 class="fs-12 font-weight-bold mb-4"><?php echo e(__('Secondary Minimized Logo')); ?></h6>

								<div class="row">

									<div class="col-sm-12 col-md-6">
										<div class="input-box">
											<img src="<?php echo e(URL::asset('img/brand/favicon.png')); ?>" class="border" alt="Main Logo">
										</div>
									</div>

									<div class="col-sm-12 col-md-6">
										<div class="input-box">
											<label class="form-label fs-12"><?php echo e(__('Select Logo')); ?> <span class="text-muted">(<?php echo e(__('Recommended Size')); ?>)</span></label>
											<div class="input-group file-browser">									
												<input type="text" class="form-control border-right-0 browse-file" placeholder="68px by 68px PNG Image" readonly>
												<label class="input-group-btn">
													<span class="btn btn-primary special-btn">
														<?php echo e(__('Browse')); ?> <input type="file" name="minimized_logo" style="display: none;">
													</span>
												</label>
											</div>
											<?php $__errorArgs = ['minimized_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p class="text-danger"><?php echo e($errors->first('minimized_logo')); ?></p>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
									</div>					

								</div>
							</div>
						</div>

						<div class="card border-0 special-shadow">
							<div class="card-body">

								<h6 class="fs-12 font-weight-bold mb-4"><?php echo e(__('Favicon')); ?></h6>

								<div class="row">

									<div class="col-sm-12 col-md-6">
										<div class="input-box">
											<img src="<?php echo e(URL::asset('img/brand/favicon.ico')); ?>" class="border w-20 mt-3" alt="Favicon Logo">
										</div>
									</div>

									<div class="col-sm-12 col-md-6">
										<div class="input-box">
											<label class="form-label fs-12"><?php echo e(__('Select Favicon')); ?> <span class="text-muted">(<?php echo e(__('Recommended Size')); ?>)</span></label>
											<div class="input-group file-browser">									
												<input type="text" class="form-control border-right-0 browse-file" placeholder="32px by 32px ICO Format" readonly>
												<label class="input-group-btn">
													<span class="btn btn-primary special-btn">
														<?php echo e(__('Browse')); ?> <input type="file" name="favicon_logo" style="display: none;">
													</span>
												</label>
											</div>
											<?php $__errorArgs = ['favicon_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p class="text-danger"><?php echo e($errors->first('favicon_logo')); ?></p>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
									</div>					

								</div>
							</div>
						</div>

						<!-- SAVE CHANGES ACTION BUTTON -->
						<div class="border-0 text-center mb-2 mt-1">
							<a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-cancel mr-2 pl-7 pr-7"><?php echo e(__('Cancel')); ?></a>
							<button type="submit" class="btn btn-primary pl-7 pr-7"><?php echo e(__('Save')); ?></button>							
						</div>				

					</form>

				</div>
			</div>
		</div>
	</div>	
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/admin/frontend/appearance/index.blade.php ENDPATH**/ ?>