
<?php $__env->startSection('css'); ?>
	<!-- Sweet Alert CSS -->
	<link href="<?php echo e(URL::asset('plugins/sweetalert/sweetalert2.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
<!-- PAGE HEADER -->
<div class="page-header mt-5-7 justify-content-center">
	<div class="page-leftheader text-center">
		<h4 class="page-title mb-0"><?php echo e(__('New Brand Voice')); ?></h4>
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item"><a href="<?php echo e(route('user.dashboard')); ?>"><i class="fa-solid fa-signature mr-2 fs-12"></i><?php echo e(__('User')); ?></a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('Brand Voice')); ?></a></li>
		</ol>
	</div>
</div>
<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>	
	<div class="row justify-content-center">
		<div class="col-lg-10 col-md-12 col-sm-12">
			<div class="card border-0">	
				<div class="card-header">
					<h3 class="card-title"><i class="fa-solid fa-signature mr-2 text-primary"></i><?php echo e(__('Brand Information')); ?></h3>
					<a href="<?php echo e(route('user.brand')); ?>" class="btn btn-cancel mr-2 pl-5 pr-5" style="margin-left: auto"><?php echo e(__('Back to Brands List')); ?></a>
				</div>			
				<div class="card-body pt-5">
					<form class="w-100" action="<?php echo e(route('user.brand.store')); ?>" method="POST" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>
						<div class="row">	

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6><?php echo e(__('Company / Brand Name')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" placeholder="<?php echo e(__('Provide company / brand name')); ?>">
										<?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('name')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6><?php echo e(__('Website')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="website" name="website" placeholder="<?php echo e(__('Enter company website URL')); ?>">
										<?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('website')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6><?php echo e(__('Industry')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['industry'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="industry" name="industry" placeholder="<?php echo e(__('List your company / brand industries that you focus on')); ?>">
										<?php $__errorArgs = ['industry'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('industry')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6><?php echo e(__('Tagline')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['tagline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tagline" name="tagline" placeholder="<?php echo e(__('Provide a catchy tagline for your company / brand')); ?>">
										<?php $__errorArgs = ['tagline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('tagline')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6><?php echo e(__('Target Audience')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['audience'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="audience" name="audience" placeholder="<?php echo e(__('Describe the primary target audience for your company / brand')); ?>">
										<?php $__errorArgs = ['audience'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('audience')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">
								<div class="input-box">
									<h6 ><?php echo e(__('Tone of Voice')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select name="tone" class="form-select">
										<option value="Professional" selected> <?php echo e(__('Professional')); ?></option>	
										<option value="Exciting"> <?php echo e(__('Exciting')); ?></option>	
										<option value="Friendly"> <?php echo e(__('Friendly')); ?></option>	
										<option value="Witty"> <?php echo e(__('Witty')); ?></option>	
										<option value="Humorous"> <?php echo e(__('Humorous')); ?></option>	
										<option value="Convincing"> <?php echo e(__('Convincing')); ?></option>	
										<option value="Empathetic"> <?php echo e(__('Empathetic')); ?></option>	
										<option value="Inspiring"> <?php echo e(__('Inspiring')); ?></option>	
										<option value="Supportive"> <?php echo e(__('Supportive')); ?></option>	
										<option value="Trusting"> <?php echo e(__('Trusting')); ?></option>	
										<option value="Playful"> <?php echo e(__('Playful')); ?></option>	
										<option value="Excited"> <?php echo e(__('Excited')); ?></option>	
										<option value="Positive"> <?php echo e(__('Positive')); ?></option>	
										<option value="Negative"> <?php echo e(__('Negative')); ?></option>	
										<option value="Engaging"> <?php echo e(__('Engaging')); ?></option>	
										<option value="Worried"> <?php echo e(__('Worried')); ?></option>	
										<option value="Urgent"> <?php echo e(__('Urgent')); ?></option>	
										<option value="Passionate"> <?php echo e(__('Passionate')); ?></option>	
										<option value="Informative"> <?php echo e(__('Informative')); ?></option>
										<option value="Funny"><?php echo e(__('Funny')); ?></option>
										<option value="Casual"> <?php echo e(__('Casual')); ?></option>																																																														
										<option value="Sarcastic"> <?php echo e(__('Sarcastic')); ?></option>																																																																																												
										<option value="Dramatic"> <?php echo e(__('Dramatic')); ?></option>	
									</select>
								</div>
							</div>

							<div class="col-sm-12">								
								<div class="input-box">								
									<h6 class="fs-11 mb-2 font-weight-semibold"><?php echo e(__('Company / Brand Description')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">						    
										<textarea type="text" rows=5 class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" placeholder="<?php echo e(__('Provide a brief description of your company / brand')); ?>"></textarea>
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

					
						<h3 class="card-title mt-3"><i class="fa-solid fa-grid-horizontal mr-2 text-primary"></i><?php echo e(__('Products or Services Information')); ?></h3>
					

						<div class="row">
							<div class="col-sm-12 mt-4 mb-5">
								<div class="form-group">								
									<div class="field input-group mb-4">
										<input type="text" class="form-control" name="names[]" placeholder="<?php echo e(__('Provide product / service name')); ?>" id="input-field-1">
										<input type="text" class="form-control" name="descriptions[]" placeholder="<?php echo e(__('Provide brief product / service description')); ?>">
										<select class="form-select mr-4" name="types[]">
											<option value="product" selected><?php echo e(__('Product')); ?></option>
											<option value="service"><?php echo e(__('Service')); ?></option>
											<option value="other"><?php echo e(__('Other')); ?></option>
										</select>
										<span onclick="addField(this)" class="btn btn-primary">
											<i class="fa fa-btn fa-plus"></i>
										</span>
										<span onclick="removeField(this)" class="btn btn-primary">
											<i class="fa fa-btn fa-minus"></i>
										</span>										
									</div>
									<div id="field-container"></div>
								</div>
							</div>

							
				
							<div class="col-md-12 col-sm-12 text-center mb-2">
								<button type="submit" class="btn btn-primary ripple pl-8 pr-8"><?php echo e(__('Create')); ?></button>	
							</div>	
							
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

		let i = 2;
		function addField(plusElement){

			let field_type = plusElement.previousElementSibling;
			let placeholder = field_type.previousElementSibling;	
			let name = placeholder.previousElementSibling;	
		
			// Stopping the function if the input field has no value.
			if(placeholder.previousElementSibling.value.trim() === ""){
				toastr.warning('<?php echo e(__('Please fill in all product / service information first')); ?>');
				return false;
			} else if (field_type.previousElementSibling.value.trim() === "") {
				toastr.warning('<?php echo e(__('Please fill in all product / service information first')); ?>');
				return false;
			}

			createButton(name.id);
			
			let new_field ='<div class="field input-group mb-4">' +
								'<input type="text" class="form-control" name="names[]" id="input-field-' + i + '" placeholder="<?php echo e(__('Provide product / service name')); ?>">' +
								'<input type="text" class="form-control" placeholder="<?php echo e(__('Provide bried product / service description')); ?>" name="descriptions[]">' +								
								'<select class="form-select mr-4" name="types[]">' +
									'<option value="product" selected><?php echo e(__('Product')); ?></option>' +
									'<option value="service"><?php echo e(__('Service')); ?></option>' +
									'<option value="other"><?php echo e(__('Other')); ?></option>' +
								'</select>' +
								'<span onclick="addField(this)" class="btn btn-primary">' +
									'<i class="fa fa-btn fa-plus"></i>' +
								'</span>' +
								'<span onclick="removeField(this)" class="btn btn-primary">' +
									'<i class="fa fa-btn fa-minus"></i>' +
								'</span>' +
							'</div>';
			i++;
   			$("#field-container").append(new_field);

			// Un hiding the minus sign.
			plusElement.nextElementSibling.style.display = "block"; 
			// Hiding the plus sign.
			plusElement.style.display = "none"; 
		}

		function removeField(minusElement){
			let plusElement = minusElement.previousElementSibling;
			let field_type = plusElement.previousElementSibling;
			let placeholder = field_type.previousElementSibling;	
			let name = placeholder.previousElementSibling;	

			minusElement.parentElement.remove();
		}

		function createButton(id) {
			let new_button = '<span onclick="insertText(this)" id="' + id+'-button" class="btn btn-primary mr-4 mb-2">' + id + '</span>';
		}



	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/user/brand/create.blade.php ENDPATH**/ ?>