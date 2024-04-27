
<?php $__env->startSection('css'); ?>
	<!-- Data Table CSS -->
	<link href="<?php echo e(URL::asset('plugins/datatable/datatables.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
<!-- PAGE HEADER -->
<div class="page-header mt-5-7">
	<div class="page-leftheader">
		<h4 class="page-title mb-0"><?php echo e(__('Search Results Data')); ?></h4>
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item"><a href="<?php echo e(route('user.dashboard')); ?>"><i class="fa fa-search mr-2 fs-12"></i><?php echo e(__('User')); ?></a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo e(url('#')); ?>"> <?php echo e(__('Search Results')); ?></a></li>
		</ol>
	</div>
</div>
<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('Seach Keyword')); ?>: <span class="text-info"><?php echo e($searchValue); ?></span></h3>
				</div>
				<div class="card-body pt-4">
					<!-- SET DATATABLE -->
					<table id='resultsTable' class='table' width='100%'>
							<thead>
								<tr>
									<th width="7%"><?php echo e(__('Workbook')); ?></th> 
									<th width="15%"><?php echo e(__('Document Name')); ?></th> 
									<th width="9%"><?php echo e(__('Template Used')); ?></th>
									<th width="5%"><?php echo e(__('Created On')); ?></th> 
									<th width="5%"><?php echo e(__('Language')); ?></th>								
									<th width="5%"><?php echo e(__('Words Used')); ?></th>								           								    						           	
									<th width="5%"><?php echo e(__('Actions')); ?></th>						           	   						           	
								</tr>
							</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<!-- Data Tables JS -->
<script src="<?php echo e(URL::asset('plugins/datatable/datatables.min.js')); ?>"></script>
<script type="text/javascript">
	$(function () {
		
		"use strict";


		var data = <?php echo $data; ?>;

		let table = $('#resultsTable').DataTable({
			"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
			responsive: true,
			colReorder: true,
			"order": [[ 6, "desc" ]],
			language: {
				"emptyTable": "<div><img id='no-results-img' src='<?php echo e(URL::asset('img/files/no-result.png')); ?>'><br><?php echo e(__('No search results were found')); ?></div>",
				"info": "<?php echo e(__('Showing page')); ?> _PAGE_ <?php echo e(__('of')); ?> _PAGES_",
			},
			"bFilter": false,
			"paging": false,
			pagingType : 'full_numbers',
			processing: false,
			serverSide: false,
			data: data.original.data,
			columns: [
				{
					data: 'workbook',
					name: 'workbook',
					orderable: true,
					searchable: true
				},
				{
					data: 'custom-title',
					name: 'custom-title',
					orderable: true,
					searchable: true
				},
				{
					data: 'template_name',
					name: 'template_name',
					orderable: true,
					searchable: true
				},
				{
					data: 'created-on',
					name: 'created-on',
					orderable: true,
					searchable: true
				},		
							
				{
					data: 'custom-language',
					name: 'custom-language',
					orderable: true,
					searchable: true
				},
				{
					data: 'tokens',
					name: 'tokens',
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

	});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/user/search/index.blade.php ENDPATH**/ ?>