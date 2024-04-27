@extends('layouts.app')
@section('css')
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />

@endsection

@section('content')

<form id="main-form" action="" method="post" enctype="multipart/form-data" class="mt-24">		
	@csrf
	<div class="row">	
		@if ($type == 'Regular License' || $type == '')
			<div class="row text-center justify-content-center">
				<p class="fs-14" style="background:#FFE2E5; color:#ff0000; padding:1rem 2rem; border-radius: 0.5rem; max-width: 1200px;">{{ __('Extended License is required in order to have access to these features') }}</p>
			</div>	
		@else
			<div class="col-lg-6 col-md-12 col-sm-12">
				<div class="card border-0" id="template-input">
					<div class="card-body p-6 pb-0">

						<div class="row text-center">
							<div class="template-view text-center">
								<div class="template-icon mb-2 d-flex justify-content-center">
									<div>
										<i class="fa-solid fa-shield-check blog-icon"></i>
									</div>
									<div>
										<h6 class="mt-1 ml-3 fs-16 number-font">{{ __('AI Content Detector') }}</h6>
									</div>									
								</div>								
								<div class="template-info">
									<p class="fs-12 text-muted mb-4">{{ __('Identify AI Content in your target text') }}</p>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12 text-center">
								<div class="input-box">	
									<div id="audio-format" role="radiogroup">
										<div class="radio-control">
											<input type="radio" name="format" class="input-control" id="basic" value="basic" checked>
											<label for="basic" class="label-control">{{ __('Basic Analysis') }}</label>
										</div>	
										<div class="radio-control">
											<input type="radio" name="format" class="input-control" id="deep" value="deep">
											<label for="deep" class="label-control">{{ __('Deep Analysis') }}</label>
										</div>																							
									</div>
								</div>											
							</div>	
							<div class="col-sm-12">								
								<div class="input-box">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Target Text') }}  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<span id="text-length" style="display: none">5000</span>									
									<div class="form-group">						    
										<textarea rows="27" cols="50" type="text" class="form-control @error('text') is-danger @enderror" id="text" name="text" placeholder="{{ __('Include your text that you would like to verify for AI content presence...') }}" required></textarea>
										@error('text')
											<p class="text-danger">{{ $errors->first('text') }}</p>
										@enderror
									</div> 
								</div> 
							</div>
						</div>						

						<div class="card-footer border-0 text-center p-0">
							<div class="w-100 pt-2 pb-2">
								<div class="text-center">
									<button type="submit" name="submit" class="btn btn-primary  pl-7 pr-7 fs-11 pt-2 pb-2" id="generate" style="min-width:200px">{{ __('Scan for AI Content') }}</button>
								</div>
							</div>							
						</div>	
				
					</div>
				</div>			
			</div>

			<div class="col-lg-6 col-md-12 col-sm-12">
				<div class="card border-0" id="template-output">
					<div class="card-body pl-7 pr-7">
						<div class="text-center mt-6">
							<h4 class="font-weight-bold">{{ __('AI Content Report') }}</h6>
						</div>
						<div class="pt-2" style="position: relative">
							<div id="status-bar"></div>
							<span id="zero-percent" class="text-muted">0%</span>
							<span id="hundred-percent" class="text-muted">100%</span>
							<div class="text-center mt-4">
								<p class="fs-14 font-weight-bold" id="check-status"><span class="text-success">100%</span> {{ __('Human Text') }}</p>
							</div>
						</div>
						<div style="border-top: 1px solid #ebecf1; border-bottom: 1px solid #ebecf1" class="pt-4 pb-2">
							<h6 class="font-weight-bold text-muted fs-14">{{ __('Report Details') }}</h6>
						</div>
					</div>
				</div>
			</div>
		@endif
	</div>
</form>
@endsection

@section('js')
<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
<script src="{{URL::asset('plugins/progressbar/progressbar.min.js')}}"></script>
<script src="{{URL::asset('plugins/character-count/jquery-simple-txt-counter.min.js')}}"></script>
<script type="text/javascript">
	$(function () {
		let loading = `<span class="loading">
						<span style="background-color: #fff;"></span>
						<span style="background-color: #fff;"></span>
						<span style="background-color: #fff;"></span>
						</span>`;
		let loading_dark = `<span class="loading">
						<span style="background-color: #1e1e2d;"></span>
						<span style="background-color: #1e1e2d;"></span>
						<span style="background-color: #1e1e2d;"></span>
						</span>`;

		"use strict";

		if (document.getElementById('text')) {
			let value = document.getElementById('text-length').innerHTML;
			$('#text').simpleTxtCounter({
				maxLength: value,
				countElem: '<div class="form-text"></div>',
				lineBreak: false,
			});
		} 

		var bar = new ProgressBar.Line('#status-bar', {
			strokeWidth: 15,
			easing: 'easeInOut',
			duration: 1400,
			color: '#FFEA82',
			trailColor: '#eee',
			trailWidth: 15,
			svgStyle: {width: '100%', height: '100%'},
			from: {color: '#ef4b4b'},
			to: {color: '#38cb89'},
			step: (state, bar) => {
				bar.path.setAttribute('stroke', state.color);
			}
		});

		bar.animate(1.0);  // Number from 0.0 to 1.0

		
		// SUBMIT FORM
		$('#main-form').on('submit', function(e) {

			e.preventDefault();

			let input = document.getElementById('text').value;
			let length = input.trim().length;

			if (length < 100) {
				toastr.warning('{{ __('Please enter at least 100 characters') }}');
			} else {
				let form = $(this);

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'POST',
					url: 'detector/process',
					data: form.serialize(),
					beforeSend: function() {
						$('#generate').prop('disabled', true);
						let btn = document.getElementById('generate');					
						btn.innerHTML = loading;  
						document.querySelector('#loader-line')?.classList?.remove('hidden');       
					},
					complete: function() {
						$('#generate').prop('disabled', false);
						let btn = document.getElementById('generate');					
						btn.innerHTML = '{{ __('Scan for AI Content') }}';
						document.querySelector('#loader-line')?.classList?.add('hidden');  
					},
					success: function (data) {		

						if (data['status'] == 200) {
							let response = JSON.parse(data['report']);

							if (data['percentage'] != 0) {
								bar.destroy();   
								bar = new ProgressBar.Line('#status-bar', {
									strokeWidth: 15,
									easing: 'easeInOut',
									duration: 1400,
									color: '#FFEA82',
									trailColor: '#eee',
									trailWidth: 15,
									svgStyle: {width: '100%', height: '100%'},
									from: {color: '#38cb89'},
									to: {color: '#ef4b4b'},
									step: (state, bar) => {
										bar.path.setAttribute('stroke', state.color);
									}
								});       

								bar.animate(data['percentage']/100);

								$('#check-status').html('<span class="text-danger">'+data['percentage']+'%</span> {{ __('Plagiarized') }}');
								
							} else {
								bar.destroy(); 
								bar = new ProgressBar.Line('#status-bar', {
									strokeWidth: 15,
									easing: 'easeInOut',
									duration: 1400,
									color: '#FFEA82',
									trailColor: '#eee',
									trailWidth: 15,
									svgStyle: {width: '100%', height: '100%'},
									from: {color: '#ef4b4b'},
									to: {color: '#38cb89'},
									step: (state, bar) => {
										bar.path.setAttribute('stroke', state.color);
									}
								});

								bar.animate(1.0);
							}

							toastr.success('{{ __('Analyze task successfully completed') }}');
			
						} else {						
							toastr.error('{{ __('There was an error during analyze task') }}');
						}

					},
					error: function(data) {
						$('#generate').prop('disabled', false);
						let btn = document.getElementById('generate');					
						btn.innerHTML = '{{ __('Scan for AI Content') }}';
						document.querySelector('#loader-line')?.classList?.add('hidden');  
					}
				});
			}			
		});
	});


</script>
@endsection