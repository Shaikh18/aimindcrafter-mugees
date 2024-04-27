
<?php $__env->startSection('css'); ?>
	<!-- Data Table CSS -->
	<link href="<?php echo e(URL::asset('plugins/datatable/datatables.min.css')); ?>" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="<?php echo e(URL::asset('plugins/sweetalert/sweetalert2.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
<!-- PAGE HEADER -->
<div class="page-header mt-5-7">
	<div class="page-leftheader">
		<h4 class="page-title mb-0"><?php echo e(__('Custom Templates')); ?></h4>
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
			<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.davinci.dashboard')); ?>"> <?php echo e(__('Davinci Management')); ?></a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('Custom Templates')); ?></a></li>
		</ol>
	</div>
	<div class="page-rightheader">
		<a href="<?php echo e(route('admin.davinci.custom.category')); ?>" class="btn btn-primary mt-1"><?php echo e(__('Category Manager')); ?></a>
	</div>
</div>
<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>	
	<div class="row">
		<div class="col-sm-12">
			<div class="card border-0">	
				<div class="card-header">
					<h3 class="card-title"><i class="fa-solid fa-microchip-ai mr-2 text-primary"></i><?php echo e(__('Custom Template Generator')); ?></h3>
				</div>			
				<div class="card-body pt-5">
					<form class="w-100" action="<?php echo e(route('admin.davinci.custom.store')); ?>" method="POST" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>
						<div class="row">	

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6><?php echo e(__('Template Name')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" placeholder="<?php echo e(__('Provide Template Name')); ?>">
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
									<h6><?php echo e(__('Template Description')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" placeholder="<?php echo e(__('Provide Template Description')); ?>">
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
								
							<div class="col-lg-6 col-md-12 col-sm-12">
								<div class="input-box">
									<h6><?php echo e(__('Template Category')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="image-feature-user" name="category" class="form-select" data-placeholder="<?php echo e(__('Select template category')); ?>">
										<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($category->code); ?>"> <?php echo e(__(ucfirst($category->name))); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>																																																													
									</select>
								</div>
							</div>	

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6><?php echo e(__('Template Icon')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('You will need to get it from fontawesome.com')); ?>"></i></h6>
									<div class="form-group">							    
										<input type="text" class="form-control <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="icon" name="icon" placeholder="ex: <i class='fa-solid fa-books'></i>">
										<?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('icon')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="input-box">
									<h6><?php echo e(__('Templates Package')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="templates-admin" name="package" class="form-select" data-placeholder="<?php echo e(__('Set Templates Package')); ?>">
										<option value="free"><?php echo e(__('Free Package')); ?></option>
										<option value="all" selected><?php echo e(__('Standard Package')); ?></option>
										<option value="professional"> <?php echo e(__('Professional Package')); ?></option>																															
										<option value="premium"> <?php echo e(__('Premium Package')); ?></option>																																																																																																									
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-6 col-md-12 col-sm-12 mt-2">
								<div class="form-group">
									<label class="custom-switch">
										<input type="checkbox" name="activate" class="custom-switch-input" checked>
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description"><?php echo e(__('Activate Template')); ?></span>
									</label>
								</div>
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12 mt-2">
								<div class="form-group">
									<label class="custom-switch">
										<input type="checkbox" name="tone" class="custom-switch-input">
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description"><?php echo e(__('Include Tone of Voice field')); ?></span>
									</label>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12 mt-4 mb-5">
								<div class="form-group">								
									<h6 class="fs-11 mb-2 font-weight-semibold"><?php echo e(__('User Input Fields')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="field input-group mb-4">
										<input type="hidden" name="code[]" value="input-field-1">
										<input type="text" class="form-control" name="names[]" placeholder="<?php echo e(__('Enter Input Field Title (Required)')); ?>" id="input-field-1">
										<input type="text" class="form-control" name="placeholders[]" placeholder="<?php echo e(__('Enter Input Field Description')); ?> (<?php echo e(__('Required')); ?>)">
										<select class="form-select mr-4" name="input_field[]" onchange="notifyUser(this)">
											<option value="input" selected><?php echo e(__('Input Field')); ?></option>
											<option value="textarea"><?php echo e(__('Textarea Field')); ?></option>
											<option value="select"><?php echo e(__('Select List Field')); ?></option>
											<option value="checkbox"><?php echo e(__('Checkbox List Field')); ?></option>
											<option value="radio"><?php echo e(__('Radio Buttons Field')); ?></option>
										</select>
										<select class="form-select" name="status_field[]">
											<option value="optional" selected><?php echo e(__('Optional')); ?></option>
											<option value="required"><?php echo e(__('Required')); ?></option>
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

							<div class="col-sm-12">								
								<div class="input-box">								
									<h6 class="fs-11 mb-2 font-weight-semibold"><?php echo e(__('Custom Prompt')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">
										<div id="field-buttons"></div>							    
										<textarea type="text" rows=10 class="form-control <?php $__errorArgs = ['prompt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="prompt" name="prompt"></textarea>
										<?php $__errorArgs = ['prompt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<p class="text-danger"><?php echo e($errors->first('prompt')); ?></p>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									</div> 
								</div> 
							</div>
				
							<div class="col-md-12 col-sm-12 text-center mb-2">
								<button type="submit" class="btn btn-primary pl-5 pr-5"><?php echo e(__('Create Template')); ?></button>	
							</div>	
							
						</div>
					</form>
				</div>
			</div>
		</div>


		<div class="col-lg-12 col-md-12 col-sm-12 mt-4">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('Custom Templates List')); ?></h3>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='allTemplates' class='table' width='100%'>
						<thead>
							<tr>									
								<th width="3%"><?php echo e(__('Status')); ?></th> 
								<th width="7%"><?php echo e(__('Template Name')); ?></th>
								<th width="14%"><?php echo e(__('Template Description')); ?></th>
								<th width="2%"><?php echo e(__('Package')); ?></th>						
								<th width="1%"><?php echo e(__('New')); ?></th>						
								<th width="3%"><?php echo e(__('Updated On')); ?></th>	    										 						           	
								<th width="7%"><?php echo e(__('Actions')); ?></th>
							</tr>
						</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<script src="<?php echo e(URL::asset('plugins/sweetalert/sweetalert2.all.min.js')); ?>"></script>
	<!-- Data Tables JS -->
	<script src="<?php echo e(URL::asset('plugins/datatable/datatables.min.js')); ?>"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			let table = $('#allTemplates').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				"order": [[3, "asc"]],
				language: {
					"emptyTable": "<div><img id='no-results-img' src='<?php echo e(URL::asset('img/files/no-result.png')); ?>'><br>No custom templates created yet</div>",
					"info": "<?php echo e(__('Showing page')); ?> _PAGE_ <?php echo e(__('of')); ?> _PAGES_",
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
				ajax: "<?php echo e(route('admin.davinci.custom')); ?>",
				columns: [
					{
						data: 'custom-status',
						name: 'custom-status',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-name',
						name: 'custom-name',
						orderable: true,
						searchable: true
					},				
					{
						data: 'description',
						name: 'description',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-package',
						name: 'custom-package',
						orderable: true,
						searchable: true
					},			
					{
						data: 'custom-new',
						name: 'custom-new',
						orderable: true,
						searchable: true
					},					
					{
						data: 'updated-on',
						name: 'updated-on',
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


			// UPDATE DESCRIPTION
			$(document).on('click', '.editButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '<?php echo e(__('Update Description')); ?>',
					showCancelButton: true,
					confirmButtonText: '<?php echo e(__('Update')); ?>',
					reverseButtons: true,
					input: 'text',
				}).then((result) => {
					if (result.value) {
						var formData = new FormData();
						formData.append("name", result.value);
						formData.append("id", $(this).attr('id'));
						formData.append("type", $(this).attr('type'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'templates/template/update',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('<?php echo e(__('Description Update')); ?>', '<?php echo e(__('Description has been successfully updated')); ?>', 'success');
									$("#allTemplates").DataTable().ajax.reload();
								} else {
									Swal.fire('<?php echo e(__('Update Error')); ?>', '<?php echo e(__('Description was not updated correctly')); ?>', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Update Error', data.responseJSON['error'], 'error');
							}
						})
					} else if (result.dismiss !== Swal.DismissReason.cancel) {
						Swal.fire('<?php echo e(__('No Description Entered')); ?>', '<?php echo e(__('Make sure to provide a new description before updating')); ?>', 'error')
					}
				})
			});


			// UPDATE PACKAGE
			$(document).on('click', '.changeButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '<?php echo e(__('Update Template Package')); ?>',
					input: 'select',
					inputOptions: {
						all: '<?php echo e(__('All Templates')); ?>',
						free: '<?php echo e(__('Only Free Templates')); ?>',
						standard: '<?php echo e(__('Up to Standard Templates')); ?>',
						professional: '<?php echo e(__('Up to Professional Templates')); ?>',
						premium: '<?php echo e(__('Up to Premium Templates')); ?>'
					},
					inputPlaceholder: '<?php echo e(__('Set Template Package')); ?>',
					showCancelButton: true,
					confirmButtonText: '<?php echo e(__('Update')); ?>',
					reverseButtons: true,
				}).then((result) => {
					if (result.value) {
						var formData = new FormData();
						formData.append("name", result.value);
						formData.append("id", $(this).attr('id'));
						formData.append("type", $(this).attr('type'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'templates/template/changepackage',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('<?php echo e(__('Template Package Update')); ?>', '<?php echo e(__('Template package has been successfully updated')); ?>', 'success');
									$("#allTemplates").DataTable().ajax.reload();
								} else {
									Swal.fire('<?php echo e(__('Update Error')); ?>', '<?php echo e(__('Template Package was not changed properly')); ?>', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Update Error', data.responseJSON['error'], 'error');
							}
						})
					} 
				})
			});


			// SET AS NEW TEMPLATE
			$(document).on('click', '.newButton', function(e) {

				e.preventDefault();

				var formData = new FormData();
				formData.append("id", $(this).attr('id'));
				formData.append("type", $(this).attr('type'));

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: 'templates/template/setnew',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('<?php echo e(__('Template Update')); ?>', '<?php echo e(__('Template has been successfully set as new')); ?>', 'success');
							$("#allTemplates").DataTable().ajax.reload();
						} else {
							Swal.fire('<?php echo e(__('Template Update')); ?>', '<?php echo e(__('New tag has been successfully removed from template')); ?>', 'success');
							$("#allTemplates").DataTable().ajax.reload();
						}      
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});


			// ACTIVATE TEMPLATE
			$(document).on('click', '.activateButton', function(e) {

				e.preventDefault();

				var formData = new FormData();
				formData.append("id", $(this).attr('id'));
				formData.append("type", $(this).attr('type'));

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: 'templates/template/activate',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('<?php echo e(__('Template Activated')); ?>', '<?php echo e(__('Template has been activated successfully')); ?>', 'success');
							$("#allTemplates").DataTable().ajax.reload();
						} else {
							Swal.fire('<?php echo e(__('Template Already Active')); ?>', '<?php echo e(__('Selected template is already activated')); ?>', 'warning');
						}      
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});


			// DEACTIVATE TEMPLATE
			$(document).on('click', '.deactivateButton', function(e) {

				e.preventDefault();

				var formData = new FormData();
				formData.append("id", $(this).attr('id'));
				formData.append("type", $(this).attr('type'));

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: 'templates/template/deactivate',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('<?php echo e(__('Template Deactivated')); ?>', '<?php echo e(__('Template has been deactivated successfully')); ?>', 'success');
							$("#allTemplates").DataTable().ajax.reload();
						} else {
							Swal.fire('<?php echo e(__('Template Already Deactive')); ?>', '<?php echo e(__('Template is already deactivated')); ?>', 'warning');
						}      
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});


			// DELETE CUSTOM TEMPLATE
			$(document).on('click', '.deleteTemplate', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '<?php echo e(__('Confirm Template Deletion')); ?>',
					text: '<?php echo e(__('It will permanently delete this custom template')); ?>',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: '<?php echo e(__('Delete')); ?>',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'templates/template/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('<?php echo e(__('Custom Template Deleted')); ?>', '<?php echo e(__('Custom template has been successfully deleted')); ?>', 'success');	
									$("#allTemplates").DataTable().ajax.reload();								
								} else {
									Swal.fire('<?php echo e(__('Template Delete Failed')); ?>', '<?php echo e(__('There was an error while deleting this custom template')); ?>', 'error');
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

		let i = 2;
		function addField(plusElement){

			let required_type = plusElement.previousElementSibling;
			let field_type = required_type.previousElementSibling;
			let placeholder = field_type.previousElementSibling;	
			let name = placeholder.previousElementSibling;	
		
			// Stopping the function if the input field has no value.
			if(placeholder.previousElementSibling.value.trim() === ""){
				return false;
			}

			createButton(name.id);
			
			let new_field ='<div class="field input-group mb-4">' +
								'<input type="hidden" name="code[]" value="input-field-' + i + '">' +
								'<input type="text" class="form-control" name="names[]" id="input-field-' + i + '" placeholder="<?php echo e(__('Enter Input Field Title (Required)')); ?>">' +
								'<input type="text" class="form-control" placeholder="<?php echo e(__('Enter Input Field Description')); ?> (<?php echo e(__('Required')); ?>)" name="placeholders[]">' +								
								'<select class="form-select mr-4" name="input_field[]" onchange="notifyUser(this)">' +
									'<option value="input" selected><?php echo e(__('Input Field')); ?></option>' +
									'<option value="textarea"><?php echo e(__('Textarea Field')); ?></option>' +
									'<option value="select"><?php echo e(__('Select List Field')); ?></option>' +
									'<option value="checkbox"><?php echo e(__('Checkbox List Field')); ?></option>' +
									'<option value="radio"><?php echo e(__('Radio Buttons Field')); ?></option>' +
								'</select>' +
								'<select class="form-select" name="status_field[]">' +
									'<option value="optional" selected><?php echo e(__('Optional')); ?></option>' +
									'<option value="required"><?php echo e(__('Required')); ?></option>' +
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

			deleteButton(name.id);

			minusElement.parentElement.remove();
		}

		function createButton(id) {
			let new_button = '<span onclick="insertText(this)" id="' + id+'-button" class="btn btn-primary mr-4 mb-2">' + id + '</span>';
   			$("#field-buttons").append(new_button);
		}

		function deleteButton(id) {
			let button = document.getElementById(id + '-button');
			button.remove();
		}

		function insertText(value) {
			insertToPrompt(" ###" + value.innerHTML + "### ");
		}

		function insertToPrompt(text) {
			var curPos = document.getElementById("prompt").selectionStart;
			let x = $("#prompt").val();
			$("#prompt").val(x.slice(0, curPos) + text + x.slice(curPos));
		}

		function notifyUser(input) {
			let placeholder = input.previousElementSibling;

			switch (input.value) {
				case 'input':
				case 'textarea':
					placeholder.setAttribute('placeholder', 'Enter Input Field Description (Required)');
					break;
				case 'select':
					placeholder.setAttribute('placeholder', 'Enter Comma separated Options for Select List (Required)');
					break;
				case 'checkbox':
					placeholder.setAttribute('placeholder', 'Enter Comma separated Values for Checkboxes (Required)');
					break;
				case 'radio':
					placeholder.setAttribute('placeholder', 'Enter Comma separated Values for Radio Buttons (Required)');
					break;
				default:
					placeholder.setAttribute('placeholder', 'Enter Input Field Description (Required)');
					break;
			}
		}
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/admin/davinci/custom/index.blade.php ENDPATH**/ ?>