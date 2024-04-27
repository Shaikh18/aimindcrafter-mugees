

<?php $__env->startSection('page-header'); ?>
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center"> 
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0"><?php echo e(__('Edit Prepaid Plan')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.finance.dashboard')); ?>"> <?php echo e(__('Finance Management')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.finance.prepaid')); ?>"> <?php echo e(__('Prepaid Plans')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('Edit Prepaid Plan')); ?></a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>						
	<div class="row justify-content-center">
		<div class="col-lg-8 col-md-10 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('Edit Prepaid Plan')); ?></h3>
				</div>
				<div class="card-body pt-5">									
					<form action="<?php echo e(route('admin.finance.prepaid.update', $id)); ?>" method="POST" enctype="multipart/form-data">
						<?php echo method_field('PUT'); ?>
						<?php echo csrf_field(); ?>

						<div class="row">

							<div class="col-lg-6 col-md-6 col-sm-12">						
								<div class="input-box">	
									<h6><?php echo e(__('Plan Status')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="plan-status" name="plan-status" class="form-select" data-placeholder="<?php echo e(__('Select Plan Status')); ?>:">			
										<option value="active" <?php if($id->status == 'active'): ?> selected <?php endif; ?>><?php echo e(__('Active')); ?></option>
										<option value="closed" <?php if($id->status == 'closed'): ?> selected <?php endif; ?>><?php echo e(__('Closed')); ?></option>
									</select>
									<?php $__errorArgs = ['plan-status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<p class="text-danger"><?php echo e($errors->first('plan-status')); ?></p>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>	
								</div>						
							</div>
						
							<div class="col-lg-6 col-md-6col-sm-12">							
								<div class="input-box">								
									<h6><?php echo e(__('Plan Name')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </h6>
									<div class="form-group">							    
										<input type="text" class="form-control" id="plan-name" name="plan-name" value="<?php echo e($id->plan_name); ?>" required>
									</div> 
									<?php $__errorArgs = ['plan-name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<p class="text-danger"><?php echo e($errors->first('plan-name')); ?></p>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div> 						
							</div>
						</div>

						<div class="row mt-2">					

							<div class="col-lg-6 col-md-6col-sm-12">							
								<div class="input-box">								
									<h6><?php echo e(__('Price')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control" id="price" name="price" value="<?php echo e($id->price); ?>" required>
									</div> 
									<?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<p class="text-danger"><?php echo e($errors->first('price')); ?></p>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div> 						
							</div>

							<div class="col-lg-6 col-md-6col-sm-12">							
								<div class="input-box">								
									<h6><?php echo e(__('Currency')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="currency" name="currency" class="form-select" data-placeholder="<?php echo e(__('Select Currency')); ?>:">			
										<?php $__currentLoopData = config('currencies.all'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($key); ?>" <?php if($id->currency == $key): ?> selected <?php endif; ?>><?php echo e($value['name']); ?> - <?php echo e($key); ?> (<?php echo $value['symbol']; ?>)</option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
									<?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<p class="text-danger"><?php echo e($errors->first('currency')); ?></p>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div> 						
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">							
								<div class="input-box">								
									<h6><?php echo e(__('Featured Plan')); ?></h6>
									<select id="featured" name="featured" class="form-select" data-placeholder="<?php echo e(__('Select if Plan is Featured')); ?>:">		
										<option value=1><?php echo e(__('Yes')); ?></option>
										<option value=0 selected><?php echo e(__('No')); ?></option>
									</select>
								</div> 						
							</div>
						</div>

						<div class="card mt-6 special-shadow border-0">
							<div class="card-body">
								<h6 class="fs-12 font-weight-bold mb-5"><i class="fa-solid fa-box-circle-check text-info fs-14 mr-1 fw-2"></i><?php echo e(__('Included Features')); ?></h6>

								<div class="row">
									<div class="col-sm-12">
										<div class="input-box">	
											<h6><?php echo e(__('Target OpenAI Model')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<select id="model" name="model" class="form-select">			
												<option value="text-davinci-003" <?php if( $id->model  == 'text-davinci-003'): ?> selected <?php endif; ?>><?php echo e(__('Davinci')); ?> (<?php echo e(__('GPT 3')); ?>) (<?php echo e(__('Legacy')); ?>)</option>
												<option value="gpt-3.5-turbo" <?php if( $id->model  == 'gpt-3.5-turbo'): ?> selected <?php endif; ?>><?php echo e(__('GPT 3.5 Turbo')); ?></option>
												<option value="gpt-3.5-turbo-16k" <?php if( $id->model  == 'gpt-3.5-turbo-16k'): ?> selected <?php endif; ?>><?php echo e(__('GPT 3.5 Turbo')); ?> (<?php echo e(__('16K')); ?>)</option>
												<option value="gpt-4" <?php if( $id->model  == 'gpt-4'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4')); ?> (<?php echo e(__('8K')); ?>)</option>
												<option value="gpt-4-32k" <?php if( $id->model  == 'gpt-4-32k'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4')); ?> (<?php echo e(__('32K')); ?>)</option>												
												<option value="gpt-4-1106-preview" <?php if( $id->model  == 'gpt-4-1106-preview'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4 Turbo')); ?> (<?php echo e(__('Preview')); ?>)</option>
												<option value="gpt-4-vision-preview" <?php if( $id->model  == 'gpt-4-vision-preview'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4 Turbo with Vision')); ?> (<?php echo e(__('Preview')); ?>)</option>
											</select>
										</div>								
									</div>

									<div class="col-lg-6 col-md-12 col-sm-12">							
										<div class="input-box">								
											<h6><?php echo e(__('Total Words included in Plan')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">							    
												<input type="number" class="form-control" id="words" name="words" value="<?php echo e($id->words); ?>" required>
												<span class="text-muted fs-10"><?php echo e(__('For AI Templates and AI Chat features')); ?></span>
											</div> 
											<?php $__errorArgs = ['words'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p class="text-danger"><?php echo e($errors->first('words')); ?></p>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div> 						
									</div>

									<div class="col-lg-6 col-md-12 col-sm-12">							
										<div class="input-box">								
											<h6><?php echo e(__('Total Characters included in Plan')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">							    
												<input type="number" class="form-control" id="characters" name="characters" value="<?php echo e($id->characters); ?>" required>
												<span class="text-muted fs-10"><?php echo e(__('For AI Voiceover feature')); ?></span>
											</div> 
											<?php $__errorArgs = ['characters'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p class="text-danger"><?php echo e($errors->first('characters')); ?></p>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div> 						
									</div>

									<div class="col-lg-6 col-md-12 col-sm-12">							
										<div class="input-box">								
											<h6><?php echo e(__('Total Dalle Images included in Plan')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">							    
												<input type="number" class="form-control" id="dalle_images" name="dalle_images" value="<?php echo e($id->dalle_images); ?>" required>
												<span class="text-muted fs-10"><?php echo e(__('Valid for all images sizes')); ?></span>
											</div> 
											<?php $__errorArgs = ['dalle_images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p class="text-danger"><?php echo e($errors->first('dalle_images')); ?></p>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div> 						
									</div>

									<div class="col-lg-6 col-md-12 col-sm-12">							
										<div class="input-box">								
											<h6><?php echo e(__('Total Stable Diffusion Images included in Plan')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">							    
												<input type="number" class="form-control" id="sd_images" name="sd_images" value="<?php echo e($id->sd_images); ?>" required>
												<span class="text-muted fs-10"><?php echo e(__('Valid for all images sizes')); ?></span>
											</div> 
											<?php $__errorArgs = ['sd_images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p class="text-danger"><?php echo e($errors->first('sd_images')); ?></p>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div> 						
									</div>

									<div class="col-lg-6 col-md-12 col-sm-12">							
										<div class="input-box">								
											<h6><?php echo e(__('Total Minutes included in Plan')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">							    
												<input type="number" class="form-control" id="minutes" name="minutes" value="<?php echo e($id->minutes); ?>" required>
												<span class="text-muted fs-10"><?php echo e(__('For AI Speech to Text feature')); ?></span>
											</div> 
											<?php $__errorArgs = ['minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p class="text-danger"><?php echo e($errors->first('minutes')); ?></p>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div> 						
									</div>
								</div>
							</div>
						</div>


						<!-- ACTION BUTTON -->
						<div class="border-0 text-center mb-2 mt-1">
							<a href="<?php echo e(route('admin.finance.prepaid')); ?>" class="btn btn-cancel mr-2 pl-7 pr-7"><?php echo e(__('Return')); ?></a>
							<button type="submit" class="btn btn-primary pl-7 pr-7"><?php echo e(__('Save')); ?></button>							
						</div>				

					</form>					
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/admin/finance/plans/prepaid/edit.blade.php ENDPATH**/ ?>