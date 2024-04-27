@extends('layouts.frontend')

@section('css')
    <link href="{{URL::asset('plugins/slick/slick.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('plugins/slick/slick-theme.css')}}" rel="stylesheet" />
@endsection

@section('menu')
    @include('layouts.secondary-menu')
@endsection

@section('content')

    <div class="container-fluid secondary-background">
        <div class="row text-center">
            <div class="col-md-12">
                <div class="section-title">
                    <!-- SECTION TITLE -->
                    <div class="text-center mb-9 mt-9 pt-7" id="contact-row">

                        <h6 class="fs-30 mt-6 font-weight-bold text-center">{{ __('About Us') }}</h6>
                        <p class="fs-12 text-center text-muted mb-5"><span>{{ __('We are your trusted AI solutions partner') }}</p>


                    </div> <!-- END SECTION TITLE -->
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION - ABOUT US
    ========================================================-->
    <section id="about-wrapper" class="secondary-background">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 policy">  
                    <div class="card border-0 p-4 pt-7 pb-7 mb-9 special-border-right special-border-left">              
                        <div class="card-body"> 

                            <div class="mb-4">
                                {!! $pages['about'] !!}
                            </div>
                        
                        </div>     
                    </div>  
                </div>
            </div>
        </div>
    </section>


    <!-- SECTION - BLOGS
    ========================================================-->
    @if (config('frontend.blogs_section') == 'on')
        <section id="blog-wrapper" class="contact-background">

            <div class="container pt-4 text-center">

                <!-- SECTION TITLE -->
                <div class="row mb-7">
                    <div class="title">
                        <p class="m-2">{{ __('Stay up to Date') }}</p>
                        <h3 class="fs-30">{{ __('Our Latest') }} <span>{{ __('Blogs') }}</span></h3>                        
                    </div>
                </div> <!-- END SECTION TITLE --> 
                                
            </div> <!-- END CONTAINER -->

            <div class="container">

                @if ($blog_exists)
                    
                    <!-- BLOGS -->
                    <div class="row" id="blogs">
                        @foreach ( $blogs as $blog )
                        <div class="blog" data-aos="zoom-in" data-aos-delay="100" data-aos-once="true" data-aos-duration="400">			
                            <div class="blog-box">
                                <div class="blog-img">
                                    <a href="{{ route('blogs.show', $blog->url) }}"><img src="{{ URL::asset($blog->image) }}" alt="Blog Image"></a>
                                    <span>{{ $blog->created_by }}</span>
                                </div>
                                <div class="blog-info mt-0">
                                    <h6 class="blog-date text-left mt-1 mb-4">{{ date('F j, Y', strtotime($blog->created_at)) }}</h6>
                                    <h5 class="blog-title fs-20 text-left mb-4"><a href="{{ route('blogs.show', $blog->url) }}">{{ __($blog->title) }}</a></h5>  
                                    <h6><a href="{{ route('blogs.show', $blog->url) }}">{{ __('Read More') }}</a> <i class="fa-solid fa-chevrons-right"></i></h6>                                   
                                </div>
                            </div>                        
                        </div> 
                        @endforeach
                    </div> 
                    

                    <!-- ROTATORS BUTTONS -->
                    <div class="blogs-nav">
                        <a class="blogs-prev"><i class="fa fa-chevron-left"></i></a>
                        <a class="blogs-next"><i class="fa fa-chevron-right"></i></a>                                
                    </div>

                @else
                    <div class="row text-center">
                        <div class="col-sm-12 mt-6 mb-6">
                            <h6 class="fs-12 font-weight-bold text-center">{{ __('No blog articles were published yet') }}</h6>
                        </div>
                    </div>
                @endif

                <div class="text-center blog-all mt-6">
                    <a href="#">{{ __('Show more') }} <i class="fa-solid fa-chevrons-right fs-10"></i></a>
                </div>

            </div> <!-- END CONTAINER -->

            {!! adsense_frontend_blogs_728x90() !!}
            
        </section> <!-- END SECTION BLOGS -->
    @endif
@endsection

@section('curve')
    <div class="container-fluid" id="curve-container">
        <div class="curve-box">
            <div class="overflow-hidden">
                <svg class="curve secodary-curve" preserveAspectRatio="none" width="1440" height="86" viewBox="0 0 1440 86" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 85.662C240 29.1253 480 0.857 720 0.857C960 0.857 1200 29.1253 1440 85.662V0H0V85.662Z"></path>
                </svg>
            </div>
        </div>
    </div>
@endsection

@section('js')
	<script src="{{URL::asset('plugins/slick/slick.min.js')}}"></script>   
    <script src="{{URL::asset('js/minimize.js')}}"></script> 
    <script type="text/javascript">
        $(document).ready(function()  {

            "use strict";

            $('#blogs').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: true,
            arrows: true,
            nextArrow: $('.blogs-next'),
            prevArrow: $('.blogs-prev'),
            autoplay: false,
            autoplaySpeed: 2000, 
            speed: 1000,
            infinite: true,
            responsive: [
                {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,         
                }
                },
                {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                }
                },
                {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                }
                },
            ]
            });

        });
        
    </script>
@endsection
