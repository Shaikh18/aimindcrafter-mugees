@extends('layouts.app')
@section('css')
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Activation') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-sliders mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{url('#')}}"> {{ __('General Settings') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Activation') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<div class="row justify-content-center">
		<div class="col-xl-4 col-lg-4 col-sm-12">
			<div class="card border-0">				
				<div class="card-body pt-7 pl-7 pr-7 pb-6">
					<form method="POST" action="{{ route('admin.settings.activation.store') }}" enctype="multipart/form-data">
						@csrf
						
						<div class="row">

							<div class="col-sm-12">								
								<div class="text-center mb-7">
									<div class="mb-7">
										<img src="{{ URL::asset('/img/files/lock.webp') }}" alt="" style="width:200px">
									</div>
									<h3 class="card-title fs-18">{{__('License Status') }}: @if ($notification) <span class="text-success font-weight-bold">{{ __('Activated') }}</span> @else <span class="text-danger fs-24 font-weight-bold">{{ __('Not Activated') }}</span>@endif</h3>
									<h3 class="card-title fs-12 mt-6 font-weight-bold">{{__('License Type') }}: <span class="text-primary font-weight-bold" style="padding: 0.2rem 1.5rem; margin-left: 0.5rem; border-radius: 1rem; background:#e1f0ff; ">{{ $type }}</span></h3>
								</div>
							</div>

							<div class="col-sm-12 col-md-12">
								<div class="input-box">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold text-muted">{{ __('Your Activation Code') }}</label>
										<input type="text" class="form-control @error('license') is-danger @enderror" name="license" value="{{ $information['license'] }}" required>
										@error('license')
											<p class="text-danger">{{ $errors->first('license') }}</p>
										@enderror									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-12">
								<div class="input-box mb-1">
									<div class="form-group mb-1">
										<label class="form-label fs-12 font-weight-bold text-muted">{{ __('Your Envato Username') }}</label>
										<input type="text" class="form-control @error('username') is-danger @enderror" name="username" value="{{ $information['username'] }}" required>
										@error('username')
											<p class="text-danger">{{ $errors->first('username') }}</p>
										@enderror									
									</div>
								</div>
								
							</div>
						</div>
						<div class="card-footer border-0 text-center pb-2 pt-5 pr-0">							
							@if (!$notification)
								<button type="submit" class="btn btn-primary pl-7 pr-7">{{ __('Activate') }}</button>						
							@else
								<a class="btn btn-primary pl-7 pr-7" id="deactivateButton">{{ __('Deactivate') }}</a>
							@endif							
						</div>		
					</form>
				</div>
			</div>
		</div>
	</div>
	
@endsection

@section('js')
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";


			$(document).on('click', '#deactivateButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm License Deactivation') }}',
					text: '{{ __('Are you sure you want to deactivate your license?') }}',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: '{{ __('Yes, Deactivate') }}',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: '/admin/settings/activation/destroy',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{ __('License Deactivated') }}', '{{ __('License has been successfully deactivated') }}', 'success');	
									setTimeout(function(){
										window.location.reload();
									}, 2000);							
								} else {
									Swal.fire('{{ __('Dectivation Failed') }}', '{{ __('There was an error while deactivating your license') }}', 'error');
								}      
							},
							error: function(data) {
								Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
							}
						})
					} 
				})
			});
	
		});
	</script>
@endsection
