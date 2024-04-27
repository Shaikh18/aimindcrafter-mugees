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
			<h4 class="page-title mb-0">{{ __('Subscription Plans') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.dashboard') }}"> {{ __('Finance Management') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Subscription Plans') }}</a></li>
			</ol>
		</div>
		<div class="page-rightheader">
			<a href="{{ route('admin.finance.plan.create') }}" class="btn btn-primary mt-1">{{ __('Create New Subscription Plan') }}</a>
		</div>
	</div>	
	<!-- END PAGE HEADER -->
@endsection

@section('content')	
	<div class="row">
		<div class="col-lg-12 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('All Subscription Plans') }}</h3>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='subscriptionPlanTable' class='table' width='100%'>
							<thead>
								<tr>
									<th width="10%">{{ __('Plan Name') }}</th>
									<th width="7%">{{ __('Status') }}</th>
									<th width="5%">{{ __('Subscribers') }}</th>
									<th width="20%">{{ __('Words / DE Images / SD Images / Characters / Minutes') }}</th>																									
									<th width="7%">{{ __('Frequency') }}</th>																		
									<th width="5%">{{ __('Featured') }}</th>
									<th width="5%">{{ __('Free') }}</th>
									<th width="6%">{{ __('Created On') }}</th>
									<th width="7%">{{ __('Actions') }}</th>
								</tr>
							</thead>
					</table> <!-- END SET DATATABLE -->

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
			var table = $('#subscriptionPlanTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				"order": [[ 7, "desc" ]],
				language: {
					"emptyTable": "<div><br>{{ __('There are no subscription plans yet') }}</div>",
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
				ajax: "{{ route('admin.finance.plans') }}",
				columns: [
					{
						data: 'custom-name',
						name: 'custom-name',
						orderable: false,
						searchable: true
					},
					{
						data: 'custom-status',
						name: 'custom-status',
						orderable: true,
						searchable: true
					},		
					{
						data: 'custom-subscribers',
						name: 'custom-subscribers',
						orderable: false,
						searchable: true
					},				
					{
						data: 'custom-credits',
						name: 'custom-credits',
						orderable: true,
						searchable: true
					},
					{
						data: 'frequency',
						name: 'frequency',
						orderable: true,
						searchable: true
					},		
					{
						data: 'custom-featured',
						name: 'custom-featured',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-free',
						name: 'custom-free',
						orderable: true,
						searchable: true
					},
					{
						data: 'created-on',
						name: 'created-on',
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

			
			// DELETE PLAN
			$(document).on('click', '.deletePlanButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Plan Deletion') }}',
					text: '{{ __('It will permanently delete this subscription plan') }}',
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
							url: 'plan/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{ __('Plan Deleted') }}', '{{ __('Subscription plan has been successfully deleted') }}', 'success');	
									$("#subscriptionPlanTable").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this plan') }}', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Oops...','Something went wrong!', 'error')
							}
						})
					} 
				})
			});

		});
	</script>
@endsection