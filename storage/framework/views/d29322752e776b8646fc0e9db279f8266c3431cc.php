<div class="secondary-navbar">
    <div class="row no-gutters">
        <nav class="navbar navbar-expand-lg navbar-light w-100" id="navbar-responsive">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>"><img id="brand-img"  src="<?php echo e(URL::asset('img/brand/logo.png')); ?>" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse section-links" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link scroll active" href="<?php echo e(url('/')); ?>/#main-wrapper"><?php echo e(__('Home')); ?> <span class="sr-only">(current)</span></a>
                    </li>	
                    <?php if(config('frontend.features_section') == 'on'): ?>
                        <li class="nav-item">
                            <a class="nav-link scroll" href="<?php echo e(url('/')); ?>/#features-wrapper"><?php echo e(__('Features')); ?></a>
                        </li>
                    <?php endif; ?>	
                    <?php if(config('frontend.pricing_section') == 'on'): ?>
                        <li class="nav-item">
                            <a class="nav-link scroll" href="<?php echo e(url('/')); ?>/#prices-wrapper"><?php echo e(__('Pricing')); ?></a>
                        </li>
                    <?php endif; ?>							
                    <?php if(config('frontend.faq_section') == 'on'): ?>
                        <li class="nav-item">
                            <a class="nav-link scroll" href="<?php echo e(url('/')); ?>/#faq-wrapper"><?php echo e(__('FAQs')); ?></a>
                        </li>
                    <?php endif; ?>	
                    <?php if(config('frontend.blogs_section') == 'on'): ?>
                        <li class="nav-item">
                            <a class="nav-link scroll" href="<?php echo e(url('/')); ?>/#blog-wrapper"><?php echo e(__('Blogs')); ?></a>
                        </li>
                    <?php endif; ?>										
                </ul>                    
            </div>
            <?php if(Route::has('login')): ?>
                    <div id="login-buttons">
                        <div class="dropdown header-locale" id="frontend-local">
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

                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('user.templates')); ?>" class="action-button dashboard-button pl-5 pr-5"><?php echo e(__('Dashboard')); ?></a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="" id="login-button"><?php echo e(__('Sign In')); ?></a>

                            <?php if(config('settings.registration') == 'enabled'): ?>
                                <?php if(Route::has('register')): ?>
                                    <a href="<?php echo e(route('register')); ?>" class="ml-2 action-button register-button pl-5 pr-5"><?php echo e(__('Sign Up')); ?></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
        </nav>
    </div>
</div><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/layouts/secondary-menu.blade.php ENDPATH**/ ?>