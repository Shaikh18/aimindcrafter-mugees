

<?php $__env->startSection('menu'); ?>
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
                            <a class="nav-link scroll active" href="#main-wrapper"><?php echo e(__('Home')); ?> <span class="sr-only">(current)</span></a>
                        </li>	
                        <?php if(config('frontend.features_section') == 'on'): ?>
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#features-wrapper"><?php echo e(__('Features')); ?></a>
                            </li>
                        <?php endif; ?>	
                        <?php if(config('frontend.pricing_section') == 'on'): ?>
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#prices-wrapper"><?php echo e(__('Pricing')); ?></a>
                            </li>
                        <?php endif; ?>							
                        <?php if(config('frontend.faq_section') == 'on'): ?>
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#faq-wrapper"><?php echo e(__('FAQs')); ?></a>
                            </li>
                        <?php endif; ?>	
                        <?php if(config('frontend.blogs_section') == 'on'): ?>
                            <li class="nav-item">
                                <a class="nav-link scroll" href="#blog-wrapper"><?php echo e(__('Blogs')); ?></a>
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
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid secondary-background">
        <div class="row text-center">
            <div class="col-md-12">
                <div class="section-title">
                    <!-- SECTION TITLE -->
                    <div class="text-center mb-9 mt-9 pt-7" id="contact-row">

                        <h6 class="fs-30 mt-6 font-weight-bold text-center"><?php echo e(__('Terms and Conditions')); ?></h6>
                        <p class="fs-12 text-center text-muted mb-5"><span><?php echo e(__('We guarantee your data privacy')); ?></p>

                    </div> <!-- END SECTION TITLE -->
                </div>
            </div>
        </div>
    </div>

    <section id="about-wrapper" class="secondary-background">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 policy">  
                    <div class="card border-0 p-4 pt-7 pb-7 mb-9 special-border-right special-border-left">              
                        <div class="card-body"> 

                            <div class="mb-7">
                                <?php echo $pages['terms']; ?>

                            </div>
        
                            <div class="form-group mt-6 text-center">                        
                                <a href="<?php echo e(route('register')); ?>" class="btn btn-primary mr-2"><?php echo e(__('I Agree, Sign me Up')); ?></a> 
                                <a href="<?php echo e(route('login')); ?>" class="btn btn-primary mr-2"><?php echo e(__('I Agree, Let me Login')); ?></a>                               
                            </div>
                        
                        </div>     
                    </div>  
                </div>
            </div>
        </div>
    </section>
    <?php $__env->startSection('js'); ?>
        <script src="<?php echo e(URL::asset('js/minimize.js')); ?>"></script>
    <?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('curve'); ?>
    <div class="container-fluid" id="curve-container">
        <div class="curve-box">
            <div class="overflow-hidden">
                <svg class="curve secodary-curve" preserveAspectRatio="none" width="1440" height="86" viewBox="0 0 1440 86" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 85.662C240 29.1253 480 0.857 720 0.857C960 0.857 1200 29.1253 1440 85.662V0H0V85.662Z"></path>
                </svg>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/service-terms.blade.php ENDPATH**/ ?>