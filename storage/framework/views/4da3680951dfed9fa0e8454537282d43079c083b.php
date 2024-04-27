<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('plugins/slick/slick.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('plugins/slick/slick-theme.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('menu'); ?>
    <?php echo $__env->make('layouts.secondary-menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="container-fluid secondary-background">
        <div class="row text-center">
            <div class="col-md-12">
                <div class="section-title">
                    <!-- SECTION TITLE
                    <div class="text-center mb-9 mt-9 pt-7" id="contact-row">

                        <h6 class="fs-30 mt-6 font-weight-bold text-center"><?php echo e(__('Contact Us')); ?></h6>
                        <p class="fs-12 text-center text-muted mb-5"><span><?php echo e(__('We are always here right by your side')); ?></p>


                    </div> <!-- END SECTION TITLE
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION - CONTACT US
    ========================================================  -->
    <section id="contact-wrapper" class="secondary-background">
        <div class="container">
         
            <div class="row justify-content-md-center">
                <div class="col-sm-12 mb-5">
                    <div class="card mb-4 border-0 special-border-right special-border-left">
                        <div class="card-body p-0">					
                            <div class="row text-center">
                                <div class="col-lg-4 col-sm-12">
                                    <div class="contact-info-box">
                                        <div class="contact-icon">
                                            <i class="fa-solid fa-location-dot mb-4 fs-25 text-primary"></i>
                                        </div>
                                           <!-- 
                                        <div class="contact-title">
                                            <h6><?php echo e(__('Our Location')); ?></h6>
                                            <p><?php echo e(__('Visit us at our local office. We would love to get to know in person.')); ?></p>
                                        </div>
                                        <div class="contact-info">
                                            <p class="text-muted mb-0 fs-12">409 Oliver Street, 59018, Bozeman, MT, USA</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-12">
                                    <div class="contact-info-box">
                                        <div class="contact-icon">
                                            <i class="fa-solid fa-envelope mb-4 fs-25 text-primary"></i>
                                        </div>
                                        <div class="contact-title">
                                            <h6><?php echo e(__('Email Us')); ?></h6>
                                            <p><?php echo e(__('Drop us an email and you will receive a reply within a short time.')); ?></p>
                                        </div>
                                        <div class="contact-info">
                                            <a class="text-muted fs-12" href="mailto:info@davinci.ai" rel="nofollow">info@davinci.ai</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-12">
                                    <div class="contact-info-box">
                                        <div class="contact-icon">
                                            <i class="fa-solid fa-phone-volume mb-4 fs-25 text-primary"></i>
                                        </div>
                                        <div class="contact-title">
                                            <h6><?php echo e(__('Call Us')); ?></h6>
                                            <p><?php echo e(__('Give us a call. Our Experts are ready to talk to you.')); ?></p>
                                        </div>
                                        <div class="contact-info">
                                            <a class="text-muted fs-12" href="tel:+1 (313) 425 7856" rel="nofollow">+1 (313) 425 7856</a>
                                        </div>  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                       
                </div>      
            </div>     
            
            <div class="row mt-9">                
                        
                <div class="col-md-6 col-sm-12" data-aos="fade-left" data-aos-delay="300" data-aos-once="true" data-aos-duration="700">
                    <img class="w-70" src="<?php echo e(URL::asset('img/files/about.svg')); ?>" alt="">
                </div>

                <div class="col-md-6 col-sm-12" data-aos="fade-right" data-aos-delay="300" data-aos-once="true" data-aos-duration="700">
                    <form id="" action="<?php echo e(route('contact')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <h6 class="fs-16 font-weight-extra-bold"><?php echo e(__('Get in Touch with Us')); ?></h6>
                        <p class="fs-14 text-muted"><?php echo e(__('Reach out to us at any time and we will be happy to assist you')); ?></p>
                        <div class="row justify-content-md-center">
                            <div class="col-md-6 col-sm-12">
                                <div class="input-box mb-4">                             
                                    <input id="name" type="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name" value="<?php echo e(old('name')); ?>" autocomplete="off" placeholder="<?php echo e(__('First Name')); ?>" required>
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
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="input-box mb-4">                             
                                    <input id="lastname" type="text" class="form-control <?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="lastname" value="<?php echo e(old('lastname')); ?>" autocomplete="off" placeholder="<?php echo e(__('Last Name')); ?>" required>
                                    <?php $__errorArgs = ['lastname'];
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
                            </div>
                        </div>

                        <div class="row justify-content-md-center">
                            <div class="col-md-6 col-sm-12">
                                <div class="input-box mb-4">                             
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
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="input-box mb-4">                             
                                    <input id="phone" type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="phone" value="<?php echo e(old('phone')); ?>" autocomplete="off"  placeholder="<?php echo e(__('Phone Number')); ?>" required>
                                    <?php $__errorArgs = ['phone'];
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
                            </div>
                        </div>

                        <div class="row justify-content-md-center">
                            <div class="col-md-12 col-sm-12">
                                <div class="input-box">							
                                    <textarea class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="message" rows="10" required placeholder="<?php echo e(__('Message')); ?>"></textarea>
                                    <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-danger"><?php echo e($errors->first('message')); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>	
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="recaptcha" id="recaptcha">
                        
                        <div class="row justify-content-md-center text-center">
                            <!-- ACTION BUTTON -->
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary special-action-button"><?php echo e(__('Get in Touch')); ?></button>							
                            </div>
                        </div>
                    
                    </form>

                </div>                   
                
            </div>
        </div>
    </section>

    
    <!-- SECTION - BANNER
     ========================================================
   
    <section id="banner-wrapper" class="contact-background">

        <div class="container">

            <!-- SECTION TITLE 
            <div class="mb-7 text-center">

                <h6><?php echo e(__('Join the 10.000+ Companies trusting')); ?> <?php echo e(config('app.name')); ?></h6>

            </div> <!-- END SECTION TITLE

            <div class="row" id="partners">
                        
                <div class="partner" data-aos="flip-down" data-aos-delay="100" data-aos-once="true" data-aos-duration="400">					
                    <div class="partner-image d-flex">
                        <div>
                            <img src="<?php echo e(URL::asset('img/frontend/logos/logo-1.svg')); ?>" alt="partner">
                        </div>
                    </div>	
                </div>    
                
                <div class="partner" data-aos="flip-down" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">					
                    <div class="partner-image d-flex">
                        <div>
                            <img src="<?php echo e(URL::asset('img/frontend/logos/logo-2.svg')); ?>" alt="partner">
                        </div>
                    </div>	
                </div> 

                <div class="partner" data-aos="flip-down" data-aos-delay="300" data-aos-once="true" data-aos-duration="400">					
                    <div class="partner-image d-flex">
                        <div>
                            <img src="<?php echo e(URL::asset('img/frontend/logos/logo-3.svg')); ?>" alt="partner">
                        </div>
                    </div>	
                </div> 

                <div class="partner" data-aos="flip-down" data-aos-delay="400" data-aos-once="true" data-aos-duration="400">					
                    <div class="partner-image d-flex">
                        <div>
                            <img src="<?php echo e(URL::asset('img/frontend/logos/logo-4.svg')); ?>" alt="partner">
                        </div>
                    </div>	
                </div> 

                <div class="partner" data-aos="flip-down" data-aos-delay="500" data-aos-once="true" data-aos-duration="400">					
                    <div class="partner-image d-flex">
                        <div>
                            <img src="<?php echo e(URL::asset('img/frontend/logos/logo-5.svg')); ?>" alt="partner">
                        </div>
                    </div>	
                </div> 

                <div class="partner" data-aos="flip-down" data-aos-delay="600" data-aos-once="true" data-aos-duration="400">					
                    <div class="partner-image d-flex">
                        <div>
                            <img src="<?php echo e(URL::asset('img/frontend/logos/logo-6.svg')); ?>" alt="partner">
                        </div>
                    </div>	
                </div> 

                <div class="partner" data-aos="flip-down" data-aos-delay="600" data-aos-once="true" data-aos-duration="400">					
                    <div class="partner-image d-flex">
                        <div>
                            <img src="<?php echo e(URL::asset('img/frontend/logos/logo-3.svg')); ?>" alt="partner">
                        </div>
                    </div>	
                </div> 
            </div>
        </div>

    </section> <!-- END SECTION BANNER -->
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

<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(URL::asset('plugins/slick/slick.min.js')); ?>"></script>  
    <script src="<?php echo e(URL::asset('js/minimize.js')); ?>"></script> 
    <script type="text/javascript">
        $(document).ready(function()  {

            "use strict";
          
            $('#partners').slick({
               slidesToShow: 6,
               slidesToScroll: 1,
               dots: false,
               arrows: false,
               autoplay: false,
               autoplaySpeed: 2000, 
               speed: 1000,
               infinite: true,
               responsive: [
                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    infinite: true,         
                  }
                },
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                  }
                },
                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                  }
                },
              ]
            });
          
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/contact.blade.php ENDPATH**/ ?>