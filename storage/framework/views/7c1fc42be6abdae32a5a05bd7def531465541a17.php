

<?php $__env->startSection('page-header'); ?>
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0"><?php echo e(__('View Prepaid Plan')); ?></h4>
			<ol class="breadcrumb mb-2">
				<ol class="breadcrumb mb-2">
					<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.finance.dashboard')); ?>"> <?php echo e(__('Finance Management')); ?></a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.finance.prepaid')); ?>"> <?php echo e(__('Prepaid Plans')); ?></a></li>
					<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('View Prepaid Plan')); ?></a></li>
				</ol>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>						
	<div class="row">
		<div class="col-lg-6 col-md-6 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('Plan Name')); ?>: <span class="text-info"><?php echo e($id->plan_name); ?></span> </h3>
				</div>
				<div class="card-body pt-5">		

					<div class="row">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Created Date')); ?>: </h6>
							<span class="fs-14"><?php echo e($id->created_at); ?></span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Plan Type')); ?>: </h6>
							<span class="fs-14"><?php echo e(__('Prepaid')); ?></span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Plan Name')); ?>: </h6>
							<span class="fs-14"><?php echo e(ucfirst($id->plan_name)); ?></span>
						</div>
					</div>

					<div class="row pt-5">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Plan Status')); ?>: </h6>
							<span class="fs-14"><?php echo e(ucfirst($id->status)); ?></span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Price')); ?>: </h6>
							<span class="fs-14"><?php echo e($id->price); ?></span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Currency')); ?>: </h6>
							<span class="fs-14"><?php echo e($id->currency); ?></span>
						</div>
					</div>

					<div class="row pt-5">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Pricing Plan')); ?>: </h6>
							<span class="fs-14"><?php echo e(ucfirst($id->pricing_plan)); ?></span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Included Words')); ?>: </h6>
							<span class="fs-14"><?php echo e(number_format($id->words)); ?></span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Included Characters')); ?>: </h6>
							<span class="fs-14"><?php echo e(ucfirst($id->characters)); ?></span>
						</div>						
					</div>	
					
					<div class="row pt-5">
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Included Dalle Images')); ?>: </h6>
							<span class="fs-14"><?php echo e(number_format($id->dalle_images)); ?></span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Included SD Images')); ?>: </h6>
							<span class="fs-14"><?php echo e(number_format($id->sd_images)); ?></span>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<h6 class="font-weight-bold mb-1"><?php echo e(__('Included Minutes')); ?>: </h6>
							<span class="fs-14"><?php echo e(number_format($id->minutes)); ?></span>
						</div>
					</div>

					<!-- SAVE CHANGES ACTION BUTTON -->
					<div class="border-0 text-right mb-2 mt-7">
						<a href="<?php echo e(route('admin.finance.prepaid')); ?>" class="btn btn-cancel mr-2"><?php echo e(__('Cancel')); ?></a>
						<a href="<?php echo e(route('admin.finance.prepaid.edit', $id)); ?>" class="btn btn-primary"><?php echo e(__('Edit Plan')); ?></a>						
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/admin/finance/plans/prepaid/show.blade.php ENDPATH**/ ?>