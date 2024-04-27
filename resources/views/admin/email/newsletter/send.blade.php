@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center"> 
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0">{{ __('Send Emails') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fa-solid fa-envelope-circle-check mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{route('admin.email.newsletter')}}"> {{ __('Newsletter') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Send Emails') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')						
	<div class="row justify-content-center">
		<div class="col-lg-4 col-md-8 col-sm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Send Emails') }}: <span class="font-weight-bold text-primary">{{ $id->name }}</span></h3>
				</div>
				<div class="card-body pt-5">									
					<form action="" method="POST" enctype="multipart/form-data" id="send-form">
						@csrf

						<div class="col-sm-12">						
							<div class="input-box">	
								<h6>{{ __('Newsletter Type') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
								<select id="type" name="type" class="form-select">			
									<option value="all" selected>{{ __('Send to All Users') }}</option>
									<option value="one">{{ __('Send only to One User') }}</option>
								</select>
								@error('type')
									<p class="text-danger">{{ $errors->first('type') }}</p>
								@enderror	
							</div>						
						</div>
									
						<div class="col-sm-12">							
							<div class="input-box">								
								<h6>{{ __('Email Address') }} <span class="text-required"></h6>
								<div class="form-group">							    
									<input type="email" class="form-control" id="email" name="email">
								</div> 
								@error('email')
									<p class="text-danger">{{ $errors->first('email') }}</p>
								@enderror
							</div> 						
						</div>

						<input type="hidden" name="email_id" value="{{ $id->id }}">


						<!-- ACTION BUTTON -->
						<div class="border-0 text-center mb-2 mt-1">
							<a href="{{ route('admin.email.newsletter') }}" class="btn btn-cancel ripple mr-2 pl-7 pr-7">{{ __('Return') }}</a>
							<button type="button" class="btn btn-primary ripple pl-7 pr-7" id="send">{{ __('Send') }}</button>							
						</div>				

					</form>					
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
		$(function () {

			"use strict";

			let loading = `<span class="loading">
						<span style="background-color: #fff;"></span>
						<span style="background-color: #fff;"></span>
						<span style="background-color: #fff;"></span>
						</span>`;

			$('#send').on('click', function() {
				let form = new FormData(document.getElementById('send-form'));
     
				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'POST',
					url: '/admin/email/newsletter/send',
					data: form,
					contentType: false,
					processData: false,
					cache: false,		
					beforeSend: function() {
						$('#send').prop('disabled', true);
						let btn = document.getElementById('send');					
						btn.innerHTML = loading;  
						document.querySelector('#loader-line')?.classList?.remove('opacity-on');         
					},
					complete: function() {
						$('#send').prop('disabled', false);
						let btn = document.getElementById('send');					
						btn.innerHTML = '{{ __('Send') }}';
						document.querySelector('#loader-line')?.classList?.add('opacity-on');  
					},
					success: function (data) {	

						if (data['status'] == 'success') {							
							toastr.success(data['message']);
						} else {	
							toastr.error(data['message']);
						}
					},
					
					error: function(err) {

						if (err.status == 422) { 

							$.each(err.responseJSON.errors, function (i, error) {
								var el = $(document).find('[name="'+i+'"]');
								toastr.error(error[0]);
								el.after($('<span style="color: red; font-size: 11px;">'+error[0]+'</span>'));
							});
						}
					}
				});	
			});

		});
	</script>
@endsection
