<?php
	$themeClass = '';
	if (!empty($_COOKIE['theme'])) {
		if ($_COOKIE['theme'] == 'dark') {
			$themeClass = 'dark-theme';
		} else if ($_COOKIE['theme'] == 'light') {
			$themeClass = 'light-theme';
		}  
	}
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
	<head>
		<!-- METADATA -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="" name="description">
		<meta content="" name="author">
		<meta name="keywords" content=""/>
		
        <!-- CSRF TOKEN -->
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <!-- TITLE -->
        <title><?php echo e(config('app.name', 'Davinci')); ?></title>
        
        <?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

	</head>

	<body class="app sidebar-mini <?php echo $themeClass; ?>">

		<div id="loader-line" class="hidden"></div>

		<!-- PAGE -->
		<div class="page">
			<div class="page-main smart-main-page">

				<!-- APP CONTENT -->			
				<div class="smart-content">

					<div class="side-app">

						<?php echo $__env->make('layouts.nav-top-smart', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        

						<?php echo $__env->yieldContent('page-header'); ?>

						<?php echo $__env->yieldContent('content'); ?>						

                    </div>                   
                </div>
                <!-- END APP CONTENT -->               

            </div>		
        </div><!-- END PAGE -->
        
		<?php echo $__env->make('layouts.footer-backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>        

	</body>
</html>


<?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/layouts/smart.blade.php ENDPATH**/ ?>