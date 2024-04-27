@extends('layouts.app')

@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header justify-content-center mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Affiliate Program') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-badge-dollar mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('user.referral') }}"> {{ __('Affiliate Program') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')	
	<div class="row justify-content-center">
		<div class="col-lg-10 col-md-12 col-sm-12 mb-4">
			<div class="card overflow-hidden border-0">
				<div class="card-body p-6 referral-body-bg">
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<div>
								<h3 class="card-title fs-20"><i class="fa-solid fa-badge-dollar mr-2 text-yellow"></i>{{ __('Affiliate Program') }}</h3>
								@if (config('payment.referral.payment.policy') == 'first')
									<p class="fs-14 text-muted">{{ __('Invite your friends and earn commissions from the first purchase they make') }}.</p>
								@else
									<p class="fs-14 text-muted">{{ __('Invite your friends and earn lifelong recurring commissions from every purchase they make') }}.</p>
								@endif								
							</div>
							<div class="mt-6">						
								<div class="input-box mb-0">		
									<h6 class="fs-12 font-weight-bold poppins">{{ __('Referral Link') }}</h6>							
									<div class="form-group d-flex referral-social-icons">									 							    
										<input type="text" class="form-control" id="email" readonly value="{{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}">
										<div class="ml-2">
											<a href="" class="btn create-project pb-2" id="actions-copy" data-link="{{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}" data-tippy-content="{{ __('Copy Referral Link') }}"><i class="fa fa-link"></i></a>										
										</div>
									</div> 
								</div>							
							</div>
						</div>
						<div class="col-md-7 col-sm-12 justify-content-center">
							<div class="row justify-content-center pt-4">
								<div class="col-md-2"></div>
								<div class="col-md-4 col-sm-12 text-center">
									<h6 class="referral-value-heading fs-13 mb-0 font-weight-bold">{{ __('Referred') }}</h6>
									<p class="referral-value fs-55 mb-0 font-weight-bold">{{ $total_referred[0]['data'] }}</p>
									<p class="referral-value-footer fs-13 text-muted mb-0">{{ __('Total Referred Users') }}</p>
									<p class="referral-value-footer fs-13 text-muted mb-0">{{ __('Invite more to Earn more') }}</p>
								</div>
								<div class="col-md-6 col-sm-12 text-center">
									<h6 class="referral-value-heading fs-13 mb-0 font-weight-bold">{{ __('Earnings') }}</h6>
									<p class="referral-value fs-55 mb-0 font-weight-bold">{!! config('payment.default_system_currency_symbol') !!}{{ number_format((float)$total_commission[0]['data'], 2, '.', '') }} {{ config('payment.default_currency') }}</p>
									<p class="referral-value-footer fs-13 mb-0"><span class="text-muted">{{ __('Referral Commission Rate') }}:</span> <span class="font-weight-bold">{{ config('payment.referral.payment.commission') }}%</span></p>
									<p class="referral-value-footer fs-13 mb-0"><span class="text-muted">{{ __('Referral Program') }}:</span>
										@if (config('payment.referral.payment.policy') == 'first')
											<span class="font-weight-bold">{{ __('First Purchase') }}</span>
										@else
											<span class="font-weight-bold">{{ __('All Purchases') }}</span>
										@endif
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-5 col-md-12 col-sm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body p-6">
					<h3 class="card-title fs-20 text-center">{{ __('How it Works') }}</h3>
					
					<div class="row text-center justify-content-md-center mt-6">
						<div class="col-lg-3 col-md-3 col-sm-4">
							<div class="referral-icon-user">
								<i class="fa-solid fa-message-check"></i>
								<h6 class="mt-3 fs-12 font-weight-bold">1. {{ __('Send Invitation') }}</h6>
								<p class="fs-12">{{ __('Send your referral link to your friends and tell them how cool is') }} {{ config('app.name') }}!</p>
							</div>							
						</div>
						<div class="col-lg-3 col-md-3 col-sm-4">
							<div class="referral-icon-user">
								<i class="fa-solid fa-user-check"></i>
								<h6 class="mt-3 fs-12 font-weight-bold">2. {{ __('Registration') }}</h6>
								<p class="fs-12">{{ __('Let your friends register using your referral link that you shared') }}.</p>
							</div>														
						</div>
						<div class="col-lg-3 col-md-3 col-sm-4">
							<div class="referral-icon-user">
								<i class="fa-solid fa-badge-percent"></i>
								<h6 class="mt-3 fs-12 font-weight-bold">3. {{ __('Get Commissions') }}</h6>
								@if (config('payment.referral.payment.policy') == 'first')
									<p class="fs-12">{{ __('Earn commission for their first subscription plan payments') }}.</p>
								@else
									<p class="fs-12">{{ __('Earn commission for all their subscription plan payments') }}.</p>
								@endif
							</div>							
						</div>
					</div>

					<form id="" action="{{ route('user.referral.email') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="row mt-6 ml-4 mr-4">
							<div class="col-md-12">
								<h6 class="fs-12 font-weight-bold">{{ __('Invite your friends') }}</h6>
								<div class="input-box">								
									<p class="fs-12 text-muted mb-2">{{ __('Insert your friends email address and send him an invitations to join') }} {{ config('app.name') }}</p> 
									<div class="input-group file-browser">							    
										<input type="email" class="form-control @error('email') is-danger @enderror border-right-0 browse-file" id="email" name="email" placeholder="{{ __('Email address') }}" style="margin-right: 80px;">
										<label class="input-group-btn">
											<button class="btn btn-primary special-btn" id="invite-friends-button">
												{{ __('Invite') }}
											</button>
										</label>
									</div> 
									@error('email')
										<p class="text-danger">{{ $errors->first('email') }}</p>
									@enderror
								</div>

								<input type="text" name="subject" value="Introduction to join {{ config('app.name') }}" hidden>
								<input type="text" name="message" value="I want to refer you to this awesome website that I'm using! You can register via this link: {{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}" hidden>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>

		<div class="col-lg-5 col-md-12 col-sm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body p-6">
					<h3 class="card-title fs-20 text-center">{{ __('Payout Request') }}</h3>
					<p class="fs-12 text-center mb-2">{{ __('Set your payout details and receive your commissions') }}</p>

					<form action="{{ route('user.referral.payout.store') }}" method="POST" enctype="multipart/form-data">
						@csrf				

							<div class="row text-center">
								<div class="col-md-12 col-sm-12">
									<div id="storage-type-radio" role="radiogroup">
										<div class="radio-control">
											<input type="radio" name="payment_method" class="input-control" id="PayPal" value="PayPal" @if (auth()->user()->referral_payment_method == 'PayPal') checked @endif style="vertical-align: middle;">
											<label for="PayPal" class="label-control fs-12">PayPal</label>
										</div>	
										<span  id="option-bank">
											<div class="radio-control">
												<input type="radio" name="payment_method" class="input-control" id="BankTransfer" value="BankTransfer" @if (auth()->user()->referral_payment_method == 'BankTransfer') checked @endif style="vertical-align: middle;">
												<label for="BankTransfer" class="label-control fs-12">{{ __('Bank Transfer') }}</label>
											</div>
										</span>									
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12">								
									<div class="input-box">								
										<h6>{{ __('PayPal ID') }}</h6>
										<div class="form-group">							    
											<input type="text" class="form-control @error('paypal') is-danger @enderror" id="paypal" name="paypal" value="{{ auth()->user()->referral_paypal }}">
										</div> 
										@error('paypal')
											<p class="text-danger">{{ $errors->first('paypal') }}</p>
										@enderror
									</div> 
								</div>

								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="input-box">								
										<h6>{{ __('Bank Account Requisites') }}</h6> 
										<textarea class="form-control" name="bank_requisites" id="bank_requisites" rows="2" placeholder="Bank Name: 
Account Name/Full Name:
Account Number/IBAN:
BIC/Swift:
Routing Number:">{{ auth()->user()->referral_bank_requisites }}</textarea>
										@error('bank_requisites')
											<p class="text-danger">{{ $errors->first('bank_requisites') }}</p>
										@enderror
									</div> 
								</div>	

								<div class="col-lg-12 col-md-12 col-sm-12">								
									<div class="input-box">								
										<h6>{{ __('Requested Amount') }}</h6>
										<div class="form-group">							    
											<input type="number" class="form-control @error('payout') is-danger @enderror" id="payout" name="payout" value="{{ old('payout') }}" placeholder="{{ __('Minimum allowed amount is ') }}{!! config('payment.default_system_currency_symbol') !!}{{ config('payment.referral.payment.threshold') }}">
										</div> 
										@error('payout')
											<p class="text-danger">{{ $errors->first('payout') }}</p>
										@enderror
									</div> 
								</div>

								<div class="col-md-12">
									<div class="border-0 text-center">
										<button type="submit" class="btn btn-primary">{{ __('Request') }}</button>							
									</div>
								</div>
								
							</div>

					</form>

				</div>
			</div>
		</div>

		<div class="col-lg-10 col-md-12 col-sm-12 mt-4">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('My Payout Requests') }} <span class="text-muted">({{ __('All Time') }})</h3>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='payoutsTable' class='table' width='100%'>
						<thead>
							<tr>
								<th width="10%">{{ __('Requested Date') }}</th>
								<th width="10%">{{ __('Request ID') }}</th>																	
								<th width="7%">{{ __('Total Amount') }} ({{ config('payment.default_system_currency') }})</th>																									
								<th width="10%">{{ __('Preferred Payment Gateway') }}</th>
								<th width="10%">{{ __('Status') }}</th>
								<th width="5%">{{ __('Actions') }}</th>
							</tr>
						</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>

		<div class="col-lg-10 col-md-12 col-sm-12 mt-4">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Earned Commissions') }} <span class="text-muted">({{ __('All Time') }})</span></h3>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='paymentsReferralTable' class='table' width='100%'>
						<thead>
							<tr>
								<th width="10%" class="fs-10">{{ __('Purchase Date') }}</th>
								<th width="12%" class="fs-10">{{ __('Order ID') }}</th>																
								<th width="10%" class="fs-10">{{ __('Total Payment') }} ({{ config('payment.default_system_currency') }})</th>																									
								<th width="10%" class="fs-10">{{ __('Commision Rate') }}</th>																									
								<th width="7%" class="fs-10">{{ __('Earned Commissions') }} ({{ config('payment.default_system_currency') }})</th>
							</tr>
						</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>
	</div>

	<!-- MODAL -->
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel"><i class="mdi mdi-alert-circle-outline color-red"></i> {{ __('Confirm Payout Request Cancellation') }}</h4>
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="deleteModalBody">
					<div>
						<!-- DELETE CONFIRMATION -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END MODAL -->
@endsection

@section('js')
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script src="{{URL::asset('js/link-share.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			// INITILIZE DATATABLE
			var table = $('#payoutsTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				"order": [[ 0, "desc" ]],
				language: {
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
				ajax: "{{ route('user.referral.payout') }}",
				columns: [{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},
					{
						data: 'request_id',
						name: 'request_id',
						orderable: true,
						searchable: true
					},															
					{
						data: 'custom-total',
						name: 'custom-total',
						orderable: true,
						searchable: true
					},	
					{
						data: 'gateway',
						name: 'gateway',
						orderable: true,
						searchable: true
					},	
					{
						data: 'custom-status',
						name: 'custom-status',
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

			// INITILIZE DATATABLE
			var table = $('#paymentsReferralTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				"order": [[ 0, "desc" ]],
				language: {
					search: "<i class='fa fa-search search-icon'></i>",
					"info": "{{ __('Showing page') }} _PAGE_ {{ __('of') }} _PAGES_",
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
				ajax: "{{ route('user.referral.referrals') }}",
				columns: [{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},
					{
						data: 'order_id',
						name: 'order_id',
						orderable: true,
						searchable: true
					},									
					{
						data: 'custom-payment',
						name: 'custom-payment',
						orderable: true,
						searchable: true
					},	
					{
						data: 'custom-rate',
						name: 'custom-rate',
						orderable: true,
						searchable: true
					},					
					{
						data: 'custom-commission',
						name: 'custom-commission',
						orderable: true,
						searchable: true
					},				

				]
			});

			// DELETE CONFIRMATION MODAL
			$(document).on('click', '#deletePayoutButton', function(event) {
				event.preventDefault();
				let href = $(this).attr('data-attr');
				$.ajax({
					url: href
					, beforeSend: function() {
						$('#loader').show();
					},
					// return the result
					success: function(result) {
						$('#deleteModal').modal("show");
						$('#deleteModalBody').html(result).show();
					}
					, error: function(jqXHR, testStatus, error) {
						console.log(error);
						alert("Page " + href + " cannot open. Error:" + error);
						$('#loader').hide();
					}
					, timeout: 8000
				})
			});

		});
	</script>
@endsection