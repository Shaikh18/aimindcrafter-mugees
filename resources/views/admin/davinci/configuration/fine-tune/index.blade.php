@extends('layouts.app')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Fine Tune Models Manager') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.davinci.dashboard') }}"> {{ __('Davinci Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.davinci.configs') }}"> {{ __('Davinci Settings') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Fine Tune Models') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')	
	<div class="row justify-content-center">
		<div class="col-lg-10 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Fine Tune Models') }}</h3>
					<a href="javascript:void(0)" id="createButton" data-bs-toggle="modal" data-bs-target="#finetuneModal"  class="btn btn-primary text-right right">{{ __('Add New Fine Tune Model') }}</a>
				</div>
				<div class="card-body pt-2">
					<!-- BOX CONTENT -->
					<div class="box-content">
						<!-- SET DATATABLE -->
						<table id='allTemplates' class='table' width='100%'>
								<thead>
									<tr>									
										<th width="5%">{{ __('Custom Name') }}</th> 
										<th width="7%">{{ __('Fine Tune Model') }}</th>				
										<th width="5%">{{ __('Base Model') }}</th> 	
										<th width="5%">{{ __('File Name') }}</th> 													    		 						           	
										<th width="3%">{{ __('Bytes') }}</th> 														    		 						           	
										<th width="3%">{{ __('Status') }}</th>	    										 						           	
										<th width="3%">{{ __('Actions') }}</th>
									</tr>
								</thead>
						</table> <!-- END SET DATATABLE -->
					</div> <!-- END BOX CONTENT -->

					<div class="col-md-12 col-sm-12 text-center mb-2">
						<a href="{{ route('admin.davinci.configs') }}" class="btn btn-cancel">{{ __('Return') }}</a>
					</div>	
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="finetuneModal" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
		  	<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body pl-5 pr-5">

					<h6 class="text-center font-weight-extra-bold fs-16"><i class="fa-solid fa-microchip-ai mr-2"></i> {{ __('Create Fine Tune Model') }}</h6>

					<form id="" action="{{ route('admin.davinci.configs.fine-tune.create') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-sm-12 mt-4">
								<div class="input-box">	
									<h6>{{ __('Model Name') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<input type="text" class="form-control @error('model-name') is-danger @enderror" id="name" name="name" placeholder="{{ __('Name') }}" required>
									@error('model-name')
										<p class="text-danger">{{ $errors->first('model-name') }}</p>
									@enderror
								</div>								
							</div>
							<div class="col-sm-12 mt-2">
								<div class="input-box">
									<h6>{{ __('Target Base Model') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="model" name="model" class="form-select">
										<option value='gpt-3.5-turbo-1106' selected >gpt-3.5-turbo-1106</option>
										@foreach ($models as $model)
											<option value={{ $model->model }}>{{ $model->description }} (Fine Tune Model)</option>
										@endforeach																														
									</select>
								</div>
							</div>
							<div class="col-sm-12 mt-2">
								<div class="input-box">
									<h6 class="mb-0">{{ __('Traning Data') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<span class="text-muted fs-12">{{ __('Add a jsonl file to use for training') }}</span>
									<div id="image-drop-box">
										<div class="image-drop-area text-center mt-2 file-drop-border">
											<input type="file" class="main-image-input" name="file" id="file" accept=".jsonl" required>
											<div class="image-drop-icon">
												<i class="fa-solid fa-file-lines fs-40"></i>
											</div>
											<p class="text-dark fw-bold mb-2 mt-3">
												{{ __('Drag and drop your training file or') }}
												<a href="javascript:void(0);" class="text-primary">{{ __('Browse') }}</a>
											</p>
											<p class="mb-0 file-name text-muted">
												<small>(.jsonl)</small>
											</p>
											<div>
												<img src="" id="main_image_preview">
											</div>
										</div>
									</div>
								</div>
							</div>		
						</div>
						<!-- ACTION BUTTON -->
						<div class="border-0 text-center">
							<button type="submit" class="btn btn-primary">{{ __('Create') }}</button>							
						</div>		
					</form>		
				</div>
		  	</div>
		</div>
	</div>
@endsection

@section('js')
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			// INITILIZE DATATABLE
			var table = $('#allTemplates').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				colReorder: true,
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('No Fine Tuned Models yet') }}</div>",
					search: "<i class='fa fa-search search-icon'></i>",
					lengthMenu: '_MENU_ ',
					paginate : {
						first    : '<i class="fa fa-angle-double-left"></i>',
						last     : '<i class="fa fa-angle-double-right"></i>',
						previous : '<i class="fa fa-angle-left"></i>',
						next     : '<i class="fa fa-angle-right"></i>'
					}
				},
				pagingType : 'full_numbers',
				processing: true,
				serverSide: true,
				ajax: "{{ route('admin.davinci.configs.fine-tune') }}",
				columns: [
					{
						data: 'model_name',
						name: 'model_name',
						orderable: true,
						searchable: true
					},	
					{
						data: 'result_model',
						name: 'result_model',
						orderable: true,
						searchable: true
					},
					{
						data: 'base_model',
						name: 'base_model',
						orderable: true,
						searchable: true
					},
					{
						data: 'file_name',
						name: 'file_name',
						orderable: true,
						searchable: true
					},	
					{
						data: 'bytes',
						name: 'bytes',
						orderable: true,
						searchable: true
					},	
					{
						data: 'status',
						name: 'status',
						orderable: true,
						searchable: true
					},																					
					{
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
					},
				]
			});


			// DELETE MODEL
			$(document).on('click', '.deleteButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Fine Tune Model Deletion') }}',
					text: '{{ __('It will permanently delete this fine tuned model and associated training file') }}',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: '{{ __('Delete') }}',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'fine-tune/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{ __('Fine Tune Model Deleted') }}', '{{ __('Fine Tuned model has been successfully deleted') }}', 'success');	
									$("#allTemplates").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this fine tuned model') }}', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Oops...','Something went wrong!', 'error')
							}
						})
					} 
				})
			});


			// ACTIVATE KEY
			$(document).on('click', '.activateButton', function(e) {

				e.preventDefault();

				var formData = new FormData();
				formData.append("id", $(this).attr('id'));

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: 'keys/activate',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'active') {
							Swal.fire('{{ __('API Key Activated') }}', '{{ __('API Key has been activated successfully') }}', 'success');
							$("#allTemplates").DataTable().ajax.reload();
						} else {
							Swal.fire('{{ __('API Key Deactivated') }}', '{{ __('API Key has been deactivated successfully') }}', 'success');
							$("#allTemplates").DataTable().ajax.reload();
						}      
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});

		});
	</script>
@endsection