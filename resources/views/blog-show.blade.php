@extends('layouts.frontend')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
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
                    <div class="text-center mb-9 mt-9 pt-9" id="contact-row">

                        <h6 class="fs-30 mt-6 font-weight-bold text-center">{{ $blog->title }}</h6>
                        <p class="fs-12 text-center text-muted mb-5"><span><i class="mdi mdi-account-edit mr-1"></i>{{ $blog->created_by }}</span> / <span><i class="mdi mdi-alarm mr-1"></i>{{ date('F j, Y', strtotime($blog->created_at)) }}</span></p>


                    </div> <!-- END SECTION TITLE -->
                </div>
            </div>
        </div>
    </div>

    <section id="blog-wrapper" class="secondary-background">

        <div class="container">

            <div class="row justify-content-md-center">

                <div class="col-md-12 col-sm-12">
                    <div class="blog mb-7">
                        <img src="{{ URL::asset($blog->image) }}" alt="Blog Image" class="main-image">
                    </div>
                    
                    <div class="fs-14 main-text" id="blog-view-mobile">{!! $blog->body !!}</div>
                </div>
     
            </div>        
        </div>

    </section>
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
	<!-- Awselect JS -->
	<script src="{{URL::asset('plugins/awselect/awselect.min.js')}}"></script>
	<script src="{{URL::asset('js/awselect.js')}}"></script>  
    <script src="{{URL::asset('js/minimize.js')}}"></script> 
@endsection
