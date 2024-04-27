

<?php $__env->startSection('css'); ?>
	<!-- Data Table CSS -->
	<link href="<?php echo e(URL::asset('plugins/awselect/awselect.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(config('frontend.maintenance') == 'on'): ?>			
        <div class="container h-100vh">
            <div class="row text-center h-100vh align-items-center">
                <div class="col-md-12">
                    <img src="<?php echo e(URL::asset('img/files/maintenance.png')); ?>" alt="Maintenance Image">
                    <h2 class="mt-4 font-weight-bold"><?php echo e(__('We are just tuning up a few things')); ?>.</h2>
                    <h5><?php echo e(__('We apologize for the inconvenience but')); ?> <span class="font-weight-bold text-info"><?php echo e(config('app.name')); ?></span> <?php echo e(__('is currenlty undergoing planned maintenance')); ?>.</h5>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php if(config('settings.registration') == 'enabled'): ?>
            <div class="container-fluid h-100vh ">
                <div class="row login-background justify-content-center">
                    <div class="col-md-6 col-sm-12" id="login-responsive"> 
                        <div class="row justify-content-center">
                            <div class="col-lg-7 mx-auto">
                                <div class="card-body pt-8">

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
                                    
                                    <form method="POST" action="<?php echo e(route('register')); ?>" onsubmit="process()">
                                        <?php echo csrf_field(); ?>                                
                                        
                                        <h3 class="text-center login-title mb-8"><?php echo e(__('Sign Up to')); ?> <span class="text-info"><a href="<?php echo e(url('/')); ?>"><?php echo e(config('app.name')); ?></a></span></h3>

                                        <?php if(config('settings.oauth_login') == 'enabled'): ?>
                                            <div class="divider">
                                                <div class="divider-text text-muted">
                                                    <small><?php echo e(__('Continue With Your Social Media Account')); ?></small>
                                                </div>
                                            </div>

                                            <div class="social-logins-box text-center">
                                                <?php if(config('services.facebook.enable') == 'on'): ?><a href="<?php echo e(url('/auth/redirect/facebook')); ?>" class="social-login-button" id="login-facebook"><i class="fa-brands fa-facebook mr-2 fs-16"></i><?php echo e(__('Sign In with Facebook')); ?></a><?php endif; ?>
                                                <?php if(config('services.twitter.enable') == 'on'): ?><a href="<?php echo e(url('/auth/redirect/twitter')); ?>" class="social-login-button" id="login-twitter"><i class="fa-brands fa-twitter mr-2 fs-16"></i><?php echo e(__('Sign In with Twitter')); ?></a><?php endif; ?>	
                                                <?php if(config('services.google.enable') == 'on'): ?><a href="<?php echo e(url('/auth/redirect/google')); ?>" class="social-login-button" id="login-google"><i class="fa-brands fa-google mr-2 fs-16"></i><?php echo e(__('Sign In with Google')); ?></a><?php endif; ?>	
                                                <?php if(config('services.linkedin.enable') == 'on'): ?><a href="<?php echo e(url('/auth/redirect/linkedin')); ?>" class="social-login-button" id="login-linkedin"><i class="fa-brands fa-linkedin mr-2 fs-16"></i><?php echo e(__('Sign In with Linkedin')); ?></a><?php endif; ?>	
                                            </div>

                                            <div class="divider">
                                                <div class="divider-text text-muted">
                                                    <small><?php echo e(__('or register with email')); ?></small>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="input-box mb-4">                             
                                            <label for="name" class="fs-12 font-weight-bold text-md-right"><?php echo e(__('Full Name')); ?></label>
                                            <input id="name" type="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name" value="<?php echo e(old('name')); ?>" autocomplete="off" autofocus placeholder="<?php echo e(__('First and Last Names')); ?>">
                                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="invalid-feedback" role="alert">
                                                    <?php echo e($message); ?>

                                                </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>                            
                                        </div>

                                        <div class="input-box mb-4">                             
                                            <label for="email" class="fs-12 font-weight-bold text-md-right"><?php echo e(__('Email Address')); ?></label>
                                            <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" autocomplete="off"  placeholder="<?php echo e(__('Email Address')); ?>" required>
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="invalid-feedback" role="alert">
                                                    <?php echo e($message); ?>

                                                </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>                            
                                        </div>

                                        <div class="input-box mb-4">                             
                                            <label for="country" class="fs-12 font-weight-bold text-md-right"><?php echo e(__('Country')); ?></label>
                                            <select id="user-country" name="country" data-placeholder="<?php echo e(__('Select Your Country')); ?>" required>	
                                                <?php $__currentLoopData = config('countries'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($value); ?>" <?php if(config('settings.default_country') == $value): ?> selected <?php endif; ?>><?php echo e($value); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>										
                                            </select>
                                            <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="invalid-feedback" role="alert">
                                                    <?php echo e($message); ?>

                                                </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>                            
                                        </div>

                                        <div class="input-box">                            
                                            <label for="password-input" class="fs-12 font-weight-bold text-md-right"><?php echo e(__('Password')); ?></label>
                                            <input id="password-input" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="off" placeholder="<?php echo e(__('Password')); ?>">
                                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="invalid-feedback" role="alert">
                                                    <?php echo e($message); ?>

                                                </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>                            
                                        </div>

                                        <div class="input-box">
                                            <label for="password-confirm" class="fs-12 font-weight-bold text-md-right"><?php echo e(__('Confirm Password')); ?></label>                       
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="off" placeholder="<?php echo e(__('Confirm Password')); ?>">                        
                                        </div>

                                        <div class="form-group mb-3">  
                                            <div class="d-flex">                        
                                                <label class="custom-switch">
                                                    <input type="checkbox" class="custom-switch-input" name="agreement" id="agreement" <?php echo e(old('remember') ? 'checked' : ''); ?> required>
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description fs-10 text-muted"><?php echo e(__('By continuing, I agree with your')); ?> <a href="<?php echo e(route('terms')); ?>" class="text-info"><?php echo e(__('Terms and Conditions')); ?></a> <?php echo e(__('and')); ?> <a href="<?php echo e(route('privacy')); ?>" class="text-info"><?php echo e(__('Privacy Policies')); ?></a></span>
                                                </label>   
                                            </div>
                                        </div>

                                        <input type="hidden" name="recaptcha" id="recaptcha">

                                        <div class="text-center">
                                            <div class="form-group mb-0">                        
                                                <button type="submit" class="btn btn-primary font-weight-bold login-main-button" id="register-button"><?php echo e(__('Sign Up')); ?></button>              
                                            </div>                        
                                        
                                            <p class="fs-10 text-muted pt-3 mb-0"><?php echo e(__('Already have an account?')); ?></p>
                                            <div class="text-center">
                                                <a href="<?php echo e(route('login')); ?>"  class="fs-12 font-weight-bold special-action-sign"><?php echo e(__('Sign In')); ?></a>      
                                            </div>                                                                                   
                                        </div>
                                    </form>
                                </div> 
                            </div>      
                        </div>
                    </div>
                        
                    <div class="col-md-6 col-sm-12 text-center background-special align-middle p-0" id="login-background">
                        <div class="login-bg">
                            <img src="<?php echo e(URL::asset('img/frontend/backgrounds/login.webp')); ?>" alt="">
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <h5 class="text-center pt-9"><?php echo e(__('New user registration is disabled currently')); ?></h5>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<!-- Awselect JS -->
	<script src="<?php echo e(URL::asset('plugins/awselect/awselect.min.js')); ?>"></script>
	<script src="<?php echo e(URL::asset('js/awselect.js')); ?>"></script>
    <?php if(config('services.google.recaptcha.enable') == 'on'): ?>
         <!-- Google reCaptcha JS -->
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo e(config('services.google.recaptcha.site_key')); ?>"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute('<?php echo e(config('services.google.recaptcha.site_key')); ?>', {action: 'contact'}).then(function(token) {
                    if (token) {
                    document.getElementById('recaptcha').value = token;
                    }
                });
            });
        </script>
    <?php endif; ?>

    <script type="text/javascript">
        let loading = `<span class="loading">
					<span style="background-color: #fff;"></span>
					<span style="background-color: #fff;"></span>
					<span style="background-color: #fff;"></span>
					</span>`;

        function process() {
            $('#register-button').prop('disabled', true);
            let btn = document.getElementById('register-button');					
            btn.innerHTML = loading;  
            document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
            return; 
        }
    </script>
   
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/auth/register.blade.php ENDPATH**/ ?>