

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
			<h4 class="page-title mb-0"><?php echo e(__('Davinci Templates')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.davinci.dashboard')); ?>"> <?php echo e(__('Davinci Management')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('Davinci Templates')); ?></a></li>
			</ol>
		</div>
		<div class="page-rightheader">			
			<a id="activateAllTemplates" href="#" class="btn btn-primary mt-1"><?php echo e(__('Activate All')); ?></a>
			<a id="deactivateAllTemplates" href="#" class="btn btn-primary mt-1"><?php echo e(__('Deactivate All')); ?></a>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('All Templates')); ?></h3>
				</div>
				<div class="card-body pt-2">
					<!-- BOX CONTENT -->
					<div class="box-content">
						<!-- SET DATATABLE -->
						<table id='allTemplates' class='table' width='100%'>
								<thead>
									<tr>									
										<th width="3%"><?php echo e(__('Status')); ?></th> 
										<th width="7%"><?php echo e(__('Template Name')); ?></th>
										<th width="15%"><?php echo e(__('Template Description')); ?></th>
										<th width="2%"><?php echo e(__('Package')); ?></th>						
										<th width="1%"><?php echo e(__('New')); ?></th>						
										<th width="3%"><?php echo e(__('Updated On')); ?></th>	    										 						           	
										<th width="6%"><?php echo e(__('Actions')); ?></th>
									</tr>
								</thead>
						</table> <!-- END SET DATATABLE -->
					</div> <!-- END BOX CONTENT -->
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<!-- Data Tables JS -->
	<script src="<?php echo e(URL::asset('plugins/datatable/datatables.min.js')); ?>"></script>
	<script src="<?php echo e(URL::asset('plugins/sweetalert/sweetalert2.all.min.js')); ?>"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			// INITILIZE DATATABLE
			var table = $('#allTemplates').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				"order": [[3, "asc"]],
				colReorder: true,
				language: {
					"emptyTable": "<div><img id='no-results-img' src='<?php echo e(URL::asset('img/files/no-result.png')); ?>'><br>All Templates</div>",
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
				ajax: "<?php echo e(route('admin.davinci.templates')); ?>",
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
						data: 'custom-description',
						name: 'custom-description',
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


			// ACTIVATE ALL TEMPLATES
			$(document).on('click', '#activateAllTemplates', function(e) {

				e.preventDefault();

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'get',
					url: 'templates/activate/all',
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('<?php echo e(__('All Templates Activated')); ?>', '<?php echo e(__('All templates were successfully activated')); ?>', 'success');
							$("#allTemplates").DataTable().ajax.reload();
						}   
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});


			// ACTIVATE ALL TEMPLATES
			$(document).on('click', '#deactivateAllTemplates', function(e) {

				e.preventDefault();

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'get',
					url: 'templates/deactivate/all',
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('<?php echo e(__('All Templates Deactivated')); ?>', '<?php echo e(__('All templates were successfully deactivated')); ?>', 'success');
							$("#allTemplates").DataTable().ajax.reload();
						}   
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});

		});
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/admin/davinci/templates/index.blade.php ENDPATH**/ ?>