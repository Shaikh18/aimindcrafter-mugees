@extends('layouts.app')
@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!-- PAGE HEADER -->
<div class="page-header mt-5-7 justify-content-center">
	<div class="page-leftheader text-center">
		<h4 class="page-title mb-0">{{ __('Brand Voice') }}</h4>
		<h6 class="text-muted">{{ __('Create unique AI-generated content tailored specifically for your brand, eliminating the need for repetitive company introductions.') }}</h6>
		<ol class="breadcrumb mb-2 justify-content-center">
			<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fa-solid fa-signature mr-2 fs-12"></i>{{ __('User') }}</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Brand Voice') }}</a></li>
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
					<h3 class="card-title"><i class="fa-solid fa-signature mr-2 text-primary"></i>{{ __('My Brand Voices') }}</h3>
					<a href="{{ route('user.brand.create') }}" class="btn btn-primary ripple" style="margin-left: auto">{{ __('Add New Brand') }}</a>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='allTemplates' class='table' width='100%'>
						<thead>
							<tr>							
								<th width="8%">{{ __('Brand Name') }}</th>
								<th width="12%">{{ __('Description') }}</th>	
								<th width="3%">{{ __('Products') }}</th> 									   										 						           	
								<th width="4%">{{ __('Actions') }}</th>
							</tr>
						</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			let table = $('#allTemplates').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				"order": [[3, "asc"]],
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('No brand voices created yet') }}</div>",
					"info": "{{ __('Showing page') }} _PAGE_ {{ __('of') }} _PAGES_",
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
				ajax: "{{ route('user.brand') }}",
				columns: [					
					{
						data: 'custom-name',
						name: 'custom-name',
						orderable: true,
						searchable: true
					},				
					{
						data: 'description',
						name: 'description',
						orderable: true,
						searchable: true
					},	
					{
						data: 'total',
						name: 'total',
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

			// DELETE CUSTOM TEMPLATE
			$(document).on('click', '.deleteTemplate', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Brand Voice Deletion') }}',
					text: '{{ __('It will permanently delete this brand voice') }}',
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
							url: 'brand/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{__('Brand Voice Deleted')}}', '{{ __('Brand Voice has been successfully deleted') }}', 'success');	
									$("#allTemplates").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{ __('Brand Voice Delete Failed') }}', '{{ __('There was an error while deleting this brand voice') }}', 'error');
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