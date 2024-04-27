

<?php $__env->startSection('content'); ?>
<div class="container-fluid h-100vh ">
    <div class="row login-background justify-content-center">
        <div class="col-md-6 col-sm-12" id="login-responsive"> 
            <div class="row justify-content-center">
                <div class="col-lg-7 mx-auto">
                    <div class="card-body pt-10">

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

                        <form method="POST" action="<?php echo e(route('login')); ?>" onsubmit="process()">
                            <?php echo csrf_field(); ?>                                       
        
                            <h3 class="text-center login-title mb-8"><?php echo e(__('Welcome Back to')); ?> <span class="text-info"><a href="<?php echo e(url('/')); ?>"><?php echo e(config('app.name')); ?></a></span></h3>
        
                            <?php if($message = Session::get('success')): ?>
                                <div class="alert alert-login alert-success"> 
                                    <strong><i class="fa fa-check-circle"></i> <?php echo e($message); ?></strong>
                                </div>
                                <?php endif; ?>
        
                                <?php if($message = Session::get('error')): ?>
                                <div class="alert alert-login alert-danger">
                                    <strong><i class="fa fa-exclamation-triangle"></i> <?php echo e($message); ?></strong>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(config('settings.oauth_login') == 'enabled'): ?>
                                <div class="divider">
                                    <div class="divider-text text-muted">
                                        <small><?php echo e(__('Sign In with Social Media')); ?></small>
                                    </div>
                                </div>
        
                                <div class="social-logins-box text-center">
                                    <?php if(config('services.facebook.enable') == 'on'): ?><a href="<?php echo e(url('/auth/redirect/facebook')); ?>" class="social-login-button" id="login-facebook">
                                        <svg class="mr-3" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M24 12C24 5.3726 18.6274 2.65179e-05 12 2.65179e-05C5.37258 2.65179e-05 0 5.3726 0 12C0 17.9896 4.38823 22.954 10.125 23.8542V15.4688H7.07813V12H10.125V9.35628C10.125 6.34878 11.9165 4.68753 14.6576 4.68753C15.9705 4.68753 17.3438 4.9219 17.3438 4.9219V7.87503H15.8306C14.3399 7.87503 13.875 8.80003 13.875 9.74902V12H17.2031L16.6711 15.4688H13.875V23.8542C19.6118 22.954 24 17.9896 24 12Z" fill="#1877F2"></path>
                                            <path d="M16.6711 15.4687L17.2031 12H13.875V9.74899C13.875 8.80001 14.3399 7.875 15.8306 7.875H17.3438V4.92187C17.3438 4.92187 15.9705 4.6875 14.6576 4.6875C11.9165 4.6875 10.125 6.34875 10.125 9.35625V12H7.07812V15.4687H10.125V23.8542C10.7359 23.9501 11.3621 24 12 24C12.6379 24 13.2641 23.9501 13.875 23.8542V15.4687H16.6711Z" fill="white"></path>
                                        </svg>
                                        <?php echo e(__('Sign In with Facebook')); ?></a>
                                    <?php endif; ?>
                                    <?php if(config('services.twitter.enable') == 'on'): ?><a href="<?php echo e(url('/auth/redirect/twitter')); ?>" class="social-login-button" id="login-twitter">
                                        <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="black" viewBox="0 0 16 16">
                                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                                        </svg>
                                        <?php echo e(__('Sign In with Twitter')); ?></a>
                                    <?php endif; ?>	
                                    <?php if(config('services.google.enable') == 'on'): ?><a href="<?php echo e(url('/auth/redirect/google')); ?>" class="social-login-button" id="login-google">
                                        <svg class="mr-3" width="22" height="22" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M23.001 12.2332C23.001 11.3699 22.9296 10.7399 22.7748 10.0865H12.7153V13.9832H18.62C18.501 14.9515 17.8582 16.4099 16.4296 17.3898L16.4096 17.5203L19.5902 19.935L19.8106 19.9565C21.8343 18.1249 23.001 15.4298 23.001 12.2332Z" fill="#4285F4"></path>
                                            <path d="M12.714 22.5C15.6068 22.5 18.0353 21.5666 19.8092 19.9567L16.4282 17.3899C15.5235 18.0083 14.3092 18.4399 12.714 18.4399C9.88069 18.4399 7.47596 16.6083 6.61874 14.0766L6.49309 14.0871L3.18583 16.5954L3.14258 16.7132C4.90446 20.1433 8.5235 22.5 12.714 22.5Z" fill="#34A853"></path>
                                            <path d="M6.62046 14.0767C6.39428 13.4234 6.26337 12.7233 6.26337 12C6.26337 11.2767 6.39428 10.5767 6.60856 9.92337L6.60257 9.78423L3.25386 7.2356L3.14429 7.28667C2.41814 8.71002 2.00146 10.3084 2.00146 12C2.00146 13.6917 2.41814 15.29 3.14429 16.7133L6.62046 14.0767Z" fill="#FBBC05"></path>
                                            <path d="M12.7141 5.55997C14.7259 5.55997 16.083 6.41163 16.8569 7.12335L19.8807 4.23C18.0236 2.53834 15.6069 1.5 12.7141 1.5C8.52353 1.5 4.90447 3.85665 3.14258 7.28662L6.60686 9.92332C7.47598 7.39166 9.88073 5.55997 12.7141 5.55997Z" fill="#EB4335"></path>
                                        </svg>
                                        <?php echo e(__('Sign In with Google')); ?></a>
                                    <?php endif; ?>	
                                    <?php if(config('services.linkedin.enable') == 'on'): ?><a href="<?php echo e(url('/auth/redirect/linkedin')); ?>" class="social-login-button" id="login-linkedin">
                                        <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                        </svg>
                                        <?php echo e(__('Sign In with Linkedin')); ?></a>
                                    <?php endif; ?>	
                                </div>
        
                                <div class="divider">
                                    <div class="divider-text text-muted">
                                        <small><?php echo e(__('or sign in with email')); ?></small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
        
                            <div class="input-box mb-4">                             
                                <label for="email" class="fs-12 font-weight-bold text-md-right"><?php echo e(__('Email Address')); ?></label>
                                <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" autocomplete="off" placeholder="<?php echo e(__('Email Address')); ?>" required>
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
        
                            <div class="input-box">                            
                                <label for="password" class="fs-12 font-weight-bold text-md-right"><?php echo e(__('Password')); ?></label>
                                <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" autocomplete="off" placeholder="<?php echo e(__('Password')); ?>" required>
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
        
                            <div class="form-group mb-3">  
                                <div class="d-flex">                        
                                    <label class="custom-switch">
                                        <input type="checkbox" class="custom-switch-input" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description"><?php echo e(__('Keep me logged in')); ?></span>
                                    </label>   
        
                                    <div class="ml-auto">
                                        <?php if(Route::has('password.request')): ?>
                                            <a class="text-info fs-12" href="<?php echo e(route('password.request')); ?>"><?php echo e(__('Forgot Your Password?')); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
        
                            <input type="hidden" name="recaptcha" id="recaptcha">
        
                            <div class="text-center">
                                <div class="form-group mb-0">                        
                                    <button type="submit" class="btn btn-primary font-weight-bold login-main-button" id="sign-in"><?php echo e(__('Sign In')); ?></button>              
                                </div>
            
                                <?php if(config('settings.registration') == 'enabled'): ?>
                                    <p class="fs-10 text-muted pt-3 mb-0"><?php echo e(__('New to ')); ?> <a href="<?php echo e(url('/')); ?>" class="special-action-sign"><?php echo e(config('app.name')); ?>?</a></p>
                                    <a href="<?php echo e(route('register')); ?>"  class="fs-12 font-weight-bold special-action-sign"><?php echo e(__('Sign Up')); ?></a> 
                                <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <!-- Tippy css -->
    <script src="<?php echo e(URL::asset('plugins/tippy/popper.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('plugins/tippy/tippy-bundle.umd.min.js')); ?>"></script>
    <script>
        tippy('[data-tippy-content]', {
                animation: 'scale-extreme',
                theme: 'material',
            });
    </script>
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
            $('#sign-in').prop('disabled', true);
            let btn = document.getElementById('sign-in');					
            btn.innerHTML = loading;  
            document.querySelector('#loader-line')?.classList?.remove('hidden'); 
            return; 
        }
    </script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/auth/login.blade.php ENDPATH**/ ?>