

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

                <h3 class="text-center login-title mb-8"><?php echo e(__('Welcome to')); ?> <span class="text-info"><?php echo e(config('app.name')); ?></span></h3>

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

                <div class="mb-6 fs-14">
                    <?php echo e(__('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.')); ?>

                </div>

                <form method="POST" action="<?php echo e(route('password.email')); ?>">
                    <?php echo csrf_field(); ?>       
                    
                    <div class="input-box mb-6">                             
                        <label for="email" class="fs-12 font-weight-bold text-md-right"><?php echo e(__('Email Address')); ?></label>
                        <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" autocomplete="off"  placeholder="Email Address" required>
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
                    
                    <div class="form-group mb-0 text-center">                        
                        <button type="submit" class="btn btn-primary login-main-button"><?php echo e(__('Email Password Reset Link')); ?></button>  
                        <p class="fs-10 text-muted mt-2"><?php echo e(__('or')); ?> <a class="text-info" href="<?php echo e(route('login')); ?>"><?php echo e(__('Sign In')); ?></a></p>                                                    
                    </div>

                </form>
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

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/auth/password/forgot-password.blade.php ENDPATH**/ ?>