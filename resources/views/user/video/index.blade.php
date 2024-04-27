@extends('layouts.app')
@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Green Audio Players CSS -->
	<link href="{{ URL::asset('plugins/audio-player/green-audio-player.css') }}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
	<div class="row mt-24">

		@if ($type == 'Regular License' || $type == '')
			<div class="row text-center justify-content-center">
				<p class="fs-14" style="background:#FFE2E5; color:#ff0000; padding:1rem 2rem; border-radius: 0.5rem; max-width: 1200px;">{{ __('Extended License is required in order to have access to these features') }}</p>
			</div>			
		@else
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="card border-0">
					<div class="card-header pt-4 border-0" id="video-image-counter">
						<h3 class="card-title"><i class="fa-sharp fa-solid fa-video mr-4 text-info"></i>{{ __('AI Image to Video') }} </h3>
						<p class="fs-11 text-muted mb-0 text-right"><i class="fa-sharp fa-solid fa-bolt-lightning mr-2 text-primary"></i>{{ __('Your Balance is') }} <span class="font-weight-semibold" id="balance-number">@if (auth()->user()->available_sd_images == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_sd_images + auth()->user()->available_sd_images_prepaid) }} {{ __('SD Images') }}@endif</span></p>
					</div>
					<form id="create-video-form" action="{{ route('user.video.create') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="card-body pt-2 pl-6 pr-6 pb-5" id="">
							<div class="input-box" style="position: relative">
								<h6 class="mb-0">{{ __('Target Image') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
								<div id="image-drop-box">
									<div class="image-drop-area text-center mt-2 file-drop-border">
										<input type="file" class="main-image-input" name="image" id="image" accept="image/png, image/jpeg" onchange="loadImage(event)" required>
										<div class="image-upload-icon">
											<i class="fa-solid fa-image-landscape fs-28 text-muted"></i>
										</div>
										<p class="text-dark fw-bold mb-0 mt-1">
											{{ __('Drag and drop your image or') }}
											<a href="javascript:void(0);" class="text-primary">{{ __('Browse') }}</a>
										</p>
										<p class="mb-5 file-name fs-12 text-muted">
											<small>{{ __('Supported dimensions:') }} 1024x576 | 576x1024 | 768x768</small><br>
											<small>{{ __('PNG and JPG') }}</small>
										</p>
									</div>

									<img id="source-image-variations" class="mb-4">
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12">
									<div class="input-box">	
										<h6>{{ __('Seed') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('A specific value that is used to guide the randomness of the generation. Use 0 to get a random seed.') }}"></i></h6>
										<input type="number" class="form-control" name="seed" value="0">
									</div>		
								</div>
								<div class="col-lg-6 col-md-12 col-sm-12">
									<div class="video-settings-wrapper">
										<div id="form-group" class="mb-5 mt-3">
											<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Image Strength') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('How strongly the video sticks to the original image. Use lower values to allow the model more freedom to make changes and higher values to correct motion distortions.') }}"></i></h6>
											<div class="range">
												<div class="range_in">
													<input type="range" min="1" max="10" value="2" name="cfg_scale">
													<div class="slider" style="width: 20%;"></div>
												</div>
												<div class="value">2</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-12 col-sm-12">
									<div class="video-settings-wrapper">
										<div id="form-group" class="mb-5 mt-3">
											<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Motion Bucket') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('Lower values generally result in less motion in the output video, while higher values generally result in more motion.') }}"></i></h6>
											<div class="range">
												<div class="range_in">
													<input type="range" min="1" max="255" value="127" name="motion_bucket_id">
													<div class="slider" style="width: 50%;"></div>
												</div>
												<div class="value">127</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							

							<div class="text-center mt-3 mb-2">
								<button type="submit" class="btn btn-primary ripple main-action-button" id="create-video" style="text-transform: none; min-width: 200px;">{{ __('Create Video') }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="col-lg-8 col-md-12 col-xm-12">
				<div class="card border-0">
					<div class="card-header">
						<h3 class="card-title">{{ __('AI Image to Video Results') }} </h3>
						<a id="refresh-button" href="#" data-tippy-content="{{ __('Update Status') }}"><i class="fa fa-refresh table-action-buttons edit-action-button"></i></a>
					</div>
					<div class="card-body pt-2">
						<!-- SET DATATABLE -->
						<table id='resultTable' class='table' width='100%'>
								<thead>
									<tr>
										<th width="7%">{{ __('Image') }}</th>
										<th width="10%">{{ __('Video') }}</th>
										<th width="2%">{{ __('Status') }}</th>										
										<th width="1%"><i class="fa fa-cloud-download fs-14"></i></th>	
										<th width="3%">{{ __('Created On') }}</th>      						           	
										<th width="1%">{{ __('Actions') }}</th>
									</tr>
								</thead>
						</table> <!-- END SET DATATABLE -->
					</div>
				</div>
			</div>
		@endif

	</div>
</div>
@endsection
@section('js')
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script type="text/javascript">
		let loading = `<span class="loading">
						<span style="background-color: #fff;"></span>
						<span style="background-color: #fff;"></span>
						<span style="background-color: #fff;"></span>
						</span>`;

		$(function () {

			"use strict";

			var table = $('#resultTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				colReorder: true,
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('No image to video results created yet') }}</div>",
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
				ajax: "{{ route('user.video') }}",
				columns: [
					{
						data: 'custom-image',
						name: 'custom-image',
						orderable: true,
						searchable: true
					},																									
					{
						data: 'custom-video',
						name: 'custom-video',
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
						data: 'download',
						name: 'download',
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

			// DELETE SYNTHESIZE RESULT
			$(document).on('click', '.deleteResultButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Result Deletion') }}',
					text: '{{ __('It will permanently delete this text to video result') }}',
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
							url: '/user/video/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data['status'] == 'success') {
									Swal.fire('{{ __('Result Deleted') }}', '{{ __('Text to video result has been successfully deleted') }}', 'success');	
									$("#resultTable").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this result') }}', 'error');
								}      
							},
							error: function(data) {
								Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
							}
						})
					} 
				})
			});


			$('#create-video-form').on('submit', function(e) {

				e.preventDefault();
				
				let data = new FormData(this);

				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: "POST",
					url: 'video/create',
					data: data,
					processData: false,
					contentType: false,
					beforeSend: function() {
						$('#create-video').prop('disabled', true);
						let btn = document.getElementById('create-video');					
						btn.innerHTML = loading;  
						document.querySelector('#loader-line')?.classList?.remove('hidden');  
					},
					complete: function() {
						$('#create-video').prop('disabled', false);
						let btn = document.getElementById('create-video');					
						btn.innerHTML = '{{ __('Create Video') }}';
						document.querySelector('#loader-line')?.classList?.add('hidden');                
					},
					success: function(data) {
						console.log(data)
						if (data['status'] == 'success') {
							toastr.success(data['message']);

							if (data['balance'] != 'unlimited') {
								animateValue("balance-number", data['old'], data['current'], 2000);	
								$("#resultTable").DataTable().ajax.reload();	
							}
						} else {
							toastr.warning(data['message']);
						}
						
					},
					error: function(data) {
						toastr.error('{{ __('There was an error, please contact support') }}');

						$('#create-video').prop('disabled', false);
						let btn = document.getElementById('create-video');					
						btn.innerHTML = '{{ __('Create Video') }}';
						document.querySelector('#loader-line')?.classList?.add('hidden');          
					}
				}).done(function(data) {})
			});

			$(".range").each(function() {
				let t = $(this),
					a = t.find("input"),
					o = a.val(),
					n = t.find(".value"),
					s = a.attr("min"),
					i = a.attr("max"),
					r = t.find(".slider");
				r.css({
					width: o * (100 * s) / i + "%"
				}), a.on("input", function() {
					o = $(this).val(), n.text(o), r.css({
						width: o * (100 * s) / i + "%"
					})
				})
			});
		});	

		var loadImage = function(event) {
			var output = document.getElementById('source-image-variations');
			output.style.display = 'block';
			output.src = URL.createObjectURL(event.target.files[0]);
			output.onload = function() {
				URL.revokeObjectURL(output.src) // free memory
			}
		};

		function animateValue(id, start, end, duration) {
			if (start === end) return;
			var range = end - start;
			var current = start;
			var increment = end > start? 1 : -1;
			var stepTime = Math.abs(Math.floor(duration / range));
			var obj = document.getElementById(id);
			var timer = setInterval(function() {
				current += increment;
				obj.innerHTML = current;
				if (current == end) {
					clearInterval(timer);
				}
			}, stepTime);
		}

		$('#refresh-button').on('click', function(e){

			e.preventDefault();

			$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: "POST",
					url: 'video/refresh',
					success: function(data) {
						$("#resultTable").DataTable().ajax.reload();
					},
					error: function(data) {          
					}
				}).done(function(data) {})

		});
		
	</script>
@endsection