

<?php $__env->startSection('content'); ?>
<div class="container-fluid justify-content-center">
    <div class="row h-100vh align-items-center login-background">
        <div class="col-md-6 col-sm-12 h-100" id="login-responsive">                
            <div class="card-body pr-10 pl-10 pt-10"> 
                
                <div class="dropdown header-locale" id="frontend-local-login">
                    <a class="icon" data-bs-toggle="dropdown">
                        <span class="fs-12 mr-4"><i class="fa-solid text-black fs-16 mr-2 fa-globe"></i><?php echo e(ucfirst(Config::get('locale')[App::getLocale()]['code'])); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
                        <div class="local-menu">
                            <?php $__currentLoopData = Config::get('locale'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($lang != App::getLocale()): ?>
                                    <a href="<?php echo e(route('locale', $lang)); ?>" class="dropdown-item d-flex">
                                        <div class="text-info"><i class="flag flag-<?php echo e($language['flag']); ?> mr-3"></i></div>
                                        <div>
                                            <span class="font-weight-normal fs-12"><?php echo e($language['display']); ?></span>
                                        </div>
                                    </a>                                        
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                
                <h3 class="text-center font-weight-bold mb-8"><?php echo e(__('Welcome to')); ?> <span class="text-info"><?php echo e(config('app.name')); ?></span></h3>
                
                <form method="POST" action="<?php echo e(route('verification.send')); ?>" id="verify-email">
                    <?php echo csrf_field(); ?>                      

                    <div class="mb-6 fs-14 text-center">
                        <?php echo e(__('Thank you for signing up with us! Before getting started, please verify your email address by typing the verification code we just emailed to you below.')); ?>

                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-12 col-sm-12 text-center">									
                            <div class="input-box">								
                                <h6><?php echo e(__('Email Verification Code')); ?></h6>
                                <div class="form-group">							    
                                    <input type="text" class="form-control <?php $__errorArgs = ['verificationcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="verificationcode" name="verificationcode" placeholder="<?php echo e(__('Enter your confirmation code here')); ?>" value="<?php echo e(old('verificationcode')); ?>" autocomplete="off">
                                    <?php $__errorArgs = ['verificationcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-danger"><?php echo e($errors->first('verificationcode')); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div> 
                                <button type="button" id="verify" class="btn btn-primary ripple pl-6 pr-6 fs-11 mt-2" style="text-transform: none;"><?php echo e(__('Verify')); ?></button>
                            </div> 
                        </div>	
                    </div>

                    <div class="mb-4 mt-5 fs-14 text-center">
                        <?php echo e(__('If you did not receive the email, we will gladly send you another one.')); ?>

                    </div>

                    <div class="form-group mb-0 text-center">                        
                        <button type="submit" class="btn btn-primary ripple pl-6 pr-6 fs-11" style="text-transform: none;"><?php echo e(__('Resend Email Verification Code')); ?></button>                                                                         
                    </div>
                
                </form>
                
                <div class="text-center">
                    <p class="fs-10 text-muted mt-2">or <a class="text-info" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><?php echo e(__('Logout')); ?></a></p> 
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                        <?php echo csrf_field(); ?>
                    </form>
                </div>

            </div>      
        </div>

        <div class="col-md-6 col-sm-12 text-center background-special h-100 align-middle p-0" id="login-background">
            <div class="login-bg">
                <img src="<?php echo e(URL::asset('img/frontend/backgrounds/login.webp')); ?>" alt="">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<script type="text/javascript">
        let loading = `<span class="loading">
					<span style="background-color: #fff;"></span>
					<span style="background-color: #fff;"></span>
					<span style="background-color: #fff;"></span>
					</span>`;

		$('#verify').on('click',function(e) {

            if(document.getElementById("verificationcode").value == '') {
                toastr.warning('<?php echo e(__('Please include your verification code first')); ?>');
                document.getElementById("verificationcode").classList.add('is-invalid');
                return;
            } else {
                let code = document.getElementById("verificationcode").value;
                code = code.trim();

                $.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: "POST",
				url: '/verify-email/confirm',
				data: {'verificationcode': code},
                beforeSend: function() {
					$('#verify').prop('disabled', true);
					let btn = document.getElementById('verify');					
					btn.innerHTML = loading;  
					document.querySelector('#loader-line')?.classList?.remove('opacity-on');         
				},
				success: function(data) {

					if (data['status'] == 'error') {
						toastr.error(data['message']);
                        $('#verify').prop('disabled', false);
						let btn = document.getElementById('verify');					
						btn.innerHTML = '<?php echo e(__('Verify')); ?>';
						document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
					} else {
                        $('#verify').prop('disabled', false);
						let btn = document.getElementById('verify');					
						btn.innerHTML = '<?php echo e(__('Verify')); ?>';
						document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
                        toastr.success('<?php echo e(__('Redirecting to your dashboard')); ?>');
                        window.location.replace('<?php echo e(url('user/dashboard')); ?>');
                    }

				},
				error: function(data) {
					toastr.error('<?php echo e(__('There was an issue with email verification, please contact support team')); ?>');
				}
			}).done(function(data) {})
            }

			
		});
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/auth/verify-email.blade.php ENDPATH**/ ?>