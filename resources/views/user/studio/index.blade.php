@extends('layouts.app')
@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/datatable/dataTables.checkboxes.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/datatable/rowReorder.dataTables.min.css')}}" rel="stylesheet" />
	<!-- Green Audio Player CSS -->
	<link href="{{ URL::asset('plugins/audio-player/green-audio-player.css') }}" rel="stylesheet" />
	<!-- FilePond CSS -->
	<link href="{{URL::asset('plugins/filepond/filepond.css')}}" rel="stylesheet" />	
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
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card border-0">	
					<div class="card-header">
						<h3 class="card-title"><i class="fa-solid fa-photo-film-music mr-2 text-primary"></i> {{ __('Sound Studio') }}</h3>
					</div>			
					<div class="card-body pt-5">
						<div class="row">
							<div class="col-md-3 col-sm-12">
								<div class="row">
									<div class="col-md-12 col-sm-12">
										<!-- CONTAINER FOR AUDIO FILE UPLOADS-->
										<div id="audio-upload-container" class="mb-6">							
											
											<!-- DRAG & DROP MEDIA FILES -->
											<div class="select-file">
												<input type="file" name="filepond" id="filepond" class="filepond"/>	
											</div>
											@error('filepond')
												<p class="text-danger">{{ $errors->first('filepond') }}</p>
											@enderror	

										</div> <!-- END CONTAINER FOR AUDIO FILE UPLOADS-->
									</div>
									<div class="col-md-12 col-sm-12 text-center">
										<div class="dropdown mb-5">	
											<button class="btn btn-primary ripple fs-11 pl-5 pr-5 mr-4" style="text-transform: none; min-width: 144px;" type="button" id="upload-music" data-tippy-content="{{ __('Upload Background Music Audio File') }}">{{ __('Upload Music File') }}</button>
											<a class="btn btn-primary ripple fs-11 pl-5 pr-5" style="text-transform: none; min-width: 144px;" href="{{ route('user.music.list') }}" data-tippy-content="{{ __('View All Your Uploaded Background Music Audio Files') }}">{{ __('View Music Files') }}</a>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-3 col-sm-12 pr-5 pr-minify">
								<div class="row">
									<div class="col-md-12 col-sm-12">
										<div class="row">
											<div class="col-md-10 pr-0 pr-minify">
												<div class="input-box">	
													<h6 class="task-heading">{{ __('Select Backround Music') }}</h6>
													<select id="bg-music" name="background-music" class="form-select">	
														<option value="none" id="none" data-url="none" selected>{{ __('None') }}</option>		
														@foreach ($musics as $music)
															<option value="{{ $music->id }}" id="{{ $music->id }}" data-url="{{ URL::asset($music->url) }}"> {{ ucfirst($music->name) }}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-md-2 pt-5" id="listen-minify">
												<div class="dropdown">
													<button class="btn btn-special create-project" type="button" onclick="previewMusic(this)" src="" id="listen-music" data-tippy-content="{{ __('Play Selected Background Music') }}"><i class="fa fa-play"></i></button>
												</div>
											</div>
										</div>											
									</div>
									<div class="col-md-12 col-sm-12">
										<div class="input-box">	
											<h6 class="task-heading">{{ __('Set Background Music Volume') }}</h6>
											<select id="bg-volume" name="background-volume" class="form-select">	
												<option value="0.25">{{ __('x-Quiet') }}</option>											
												<option value="0.5">{{ __('Quiet') }}</option>																						
												<option value="1.0" selected>{{ __('Default') }}</option>											
												<option value="1.5">{{ __('Loud') }}</option>											
												<option value="2">{{ __('x-Loud') }}</option>											
											</select>
										</div>												
									</div>
								</div>
							</div>

							<div class="col-md-3 col-sm-12 pl-5 pl-minify">
								<div class="row">
									<div class="col-md-12 col-sm-12">
										<div class="input-box">	
											<h6 class="task-heading">{{ __('Set Final Result Volume') }}</h6>
											<select id="audio-volume" name="audio-volume" class="form-select">			
												<option value="0.25">{{ __('x-Quiet') }}</option>											
												<option value="0.5">{{ __('Quiet') }}</option>																						
												<option value="1.0" selected>{{ __('Default') }}</option>											
												<option value="1.5">{{ __('Loud') }}</option>											
												<option value="2">{{ __('x-Loud') }}</option>
											</select>
										</div>												
									</div>
									<div class="col-md-12 col-sm-12">
										<div class="input-box">	
											<h6 class="task-heading">{{ __('Set Result Title') }}</h6>
											<div class="form-group">
												<input type="text" id="title" class="form-control @error('title') is-danger @enderror" name="title">
												@error('title')
													<p class="text-danger">{{ $errors->first('title') }}</p>
												@enderror
											</div>
										</div>												
									</div>
								</div>
							</div>

							<div class="col-md-3 col-sm-12 pl-5 pl-minify">
								<div class="row">			
									<div class="col-md-12 col-sm-12 mt-8 text-center" id="audio-format-minify">
										<div class="input-box">	
											<h6 class="task-heading">{{ __('Audio File Format') }}</h6>
											<div id="audio-format" role="radiogroup">
												<div class="radio-control">
													<input type="radio" name="format" class="input-control" id="mp3" value="mp3" checked>
													<label for="mp3" class="label-control">MP3</label>
												</div>	
												<div class="radio-control">
													<input type="radio" name="format" class="input-control" id="wav" value="wav">
													<label for="wav" class="label-control">WAV</label>
												</div>																
												<div class="radio-control">
													<input type="radio" name="format" class="input-control" id="ogg" value="ogg">
													<label for="ogg" class="label-control">OGG</label>
												</div>								
											</div>
										</div>											
									</div>								
								</div>
							</div>
						</div>

						<div class="row mt-3">
							<div class="col-md-12 col-sm-12 text-center">
								<div class="input-box mb-4">	
									<button class="btn btn-primary ripple fs-11 pl-7 pr-7" type="button" id="merge-button" style="text-transform: none; min-width: 184px;">{{ __('Merge Audio Files') }}</button>
								</div>												
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card border-0">
					
					<div class="card-body pt-2">
						<span class="text-muted fs-11">{{ __('Maximum rows to process is') }} {{ $row_limit }} <i class="ml-2 fa fa-info fs-8 info-notification" data-tippy-content="Select rows that you want to merge together. Click on checkboxes to change the order of rows."></i></span>
						<!-- SET DATATABLE -->
						<table id='resultsTable' class='table' width='100%'>
								<thead>
									<tr>
										<th width="1%"></th>
										<th width="9%">{{ __('Created On') }}</th> 
										<th width="9%">{{ __('Project') }}</th> 
										<th width="9%">{{ __('Title') }}</th> 
										<th width="9%">{{ __('Language') }}</th>
										<th width="5%">{{ __('Voice') }}</th>
										<th width="5%">{{ __('Gender') }}</th>
										<th width="7%">{{ __('Voice Engine') }}</th>
										<th width="4%"><i class="fa fa-music fs-14"></i></th>							
										<th width="4%"><i class="fa fa-cloud-download fs-14"></i></th>								
										<th width="4%">{{ __('Format') }}</th>	
										<th width="4%">{{ __('Chars') }}</th>								           								    						           	
										<th width="5%">{{ __('Actions') }}</th>
									</tr>
								</thead>							
						</table> <!-- END SET DATATABLE -->					
					</div>
				</div>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12 mt-4">
				<div class="card border-0">
					<div class="card-header" id="sound-studio-header">
						<h3 class="card-title">{{ __('Sound Studio Results') }}</h3>
					</div>
					<div class="card-body pt-2">
						<!-- SET DATATABLE -->
						<table id='studioResultsTable' class='table' width='100%'>
								<thead>
									<tr>
										<th width="6%">{{ __('Created On') }}</th> 
										<th width="10%">{{ __('Result Title') }}</th> 
										<th width="4%"><i class="fa fa-music fs-14"></i></th>							
										<th width="4%"><i class="fa fa-cloud-download fs-14"></i></th>								
										<th width="4%">{{ __('Format') }}</th>	
										<th width="4%">{{ __('Total Characters') }}</th>								           								    						           	
										<th width="5%">{{ __('# Merged Files') }}</th>								           								    						           	
										<th width="3%">{{ __('Actions') }}</th>
									</tr>
								</thead>
						</table> <!-- END SET DATATABLE -->
					</div>
				</div>
			</div>
		@endif
	</div>
@endsection

@section('js')
	<!-- Green Audio Player JS -->
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script src="{{ URL::asset('plugins/audio-player/green-audio-player.js') }}"></script>
	<script src="{{ URL::asset('js/audio-player.js') }}"></script>
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script src="{{URL::asset('plugins/datatable/dataTables.checkboxes.min.js')}}"></script>
	<script src='{{URL::asset('plugins/datatable/dataTables.rowReorder.min.js')}}'></script>
	<!-- FilePond JS -->
	<script src={{ URL::asset('plugins/filepond/filepond.min.js') }}></script>
	<script src={{ URL::asset('plugins/filepond/filepond-plugin-file-validate-size.min.js') }}></script>
	<script src={{ URL::asset('plugins/filepond/filepond-plugin-file-validate-type.min.js') }}></script>	
	<script src={{ URL::asset('plugins/filepond/filepond.jquery.js') }}></script>
	<script type="text/javascript">
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
		$(function () {

			"use strict";

			let table = $('#resultsTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				rowReorder: true,
				"order": [[ 1, "desc" ]],
				rowReorder: {
					update: false
				},	
				'columnDefs': [
					{
						'targets': 0,
						'checkboxes': {
							'selectRow': true
						}
					},
				],
				select: {
					style: 'multi'
				},
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('Studio does not have any synthesized results yet') }}</div>",
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
				ajax: "{{ route('user.studio') }}",
				columns: [
					{
						data: 'id',
						name: 'id',
						orderable: false,
						searchable: false
					},
					{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},		
					{
						data: 'project',
						name: 'project',
						orderable: true,
						searchable: true
					},
					{
						data: 'title',
						name: 'title',
						orderable: true,
						searchable: true
					},			
					{
						data: 'custom-language',
						name: 'custom-language',
						orderable: true,
						searchable: true
					},
					{
						data: 'voice',
						name: 'voice',
						orderable: true,
						searchable: true
					},
					{
						data: 'gender',
						name: 'gender',
						orderable: true,
						searchable: true
					},
					{
						data: 'custom-voice-type',
						name: 'custom-voice-type',
						orderable: true,
						searchable: true
					},
					{
						data: 'single',
						name: 'single',
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
						data: 'custom-extension',
						name: 'custom-extension',
						orderable: true,
						searchable: true
					},
					{
						data: 'characters',
						name: 'characters',
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


			let studio = $('#studioResultsTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				"order": [[ 0, "desc" ]],
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('Studio does not have any synthesized results yet') }}</div>",
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
				ajax: "{{ route('user.studio.results') }}",
				columns: [
					{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},		
					{
						data: 'title',
						name: 'title',
						orderable: true,
						searchable: true
					},		
					{
						data: 'single',
						name: 'single',
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
						data: 'custom-extension',
						name: 'custom-extension',
						orderable: true,
						searchable: true
					},
					{
						data: 'characters',
						name: 'characters',
						orderable: true,
						searchable: true
					},	
					{
						data: 'files',
						name: 'files',
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

			let row_limit = 10; //JSON.parse(`<?php echo $js['row_limit']; ?>`);

			$('#merge-button').on('click', function() {

				let checkedRows = [];
				let bg_audio = $("#bg-music").val();
				let bg_volume = $("#bg-volume").val();
				let audio_volume = $("#audio-volume").val();
				let title = $("#title").val();
				let format = $("input[type='radio'][name='format']:checked").val();
				let process = true;

				$.each($("input:checked"), function(){
				
					let row = $(this).closest( 'tr' );
					let data = table.row(row).data();
					if (data !== undefined) {
						let user = Object.values(data);

						if (user[11] == format) {
							checkedRows.push(user[0]);							
						} else {
							Swal.fire('{{ __('Incorrect Format Included') }}', '{{ __('Mixing audio file formats is not allowed, select exact audio formats as checked under Audio File Format section above.') }}', 'warning');	
							process = false;
							return false;
						}
					}
	
				});

				if (process) {

					if (checkedRows.length == 0) {
					
						Swal.fire('{{ __('Select Synthesize Result') }}', '{{ __('Please select at least 1 text synthesize result to merge it with a background music or select 2 or more text synthesize results to merge them together') }}', 'warning');	
					
					} else if (checkedRows.length > row_limit) {

						Swal.fire('{{ __('Too Many Files Selected') }}', '{{ __('You can merge up to ') }}' + row_limit + '{{__(' audio files with same format in a single merge task. You have selected ')}}' + checkedRows.length + '{{__(' audio files.')}}', 'warning');	
					
					} else {

						let data = new FormData();
						data.append("rows", checkedRows);
						data.append("format", format);
						data.append("title", title);
						data.append("background_audio", bg_audio);
						data.append("background_volume", bg_volume);
						data.append("audio_volume", audio_volume);

						$.ajax({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							type: "POST",
							url: 'studio/music/merge',
							data: data,
							processData: false,
							contentType: false,
							beforeSend: function() {
								$('#merge-button').prop('disabled', true);
								let btn = document.getElementById('merge-button');					
								btn.innerHTML = loading;  
								document.querySelector('#loader-line')?.classList?.remove('hidden');       
							},
							complete: function() {
								$('#merge-button').prop('disabled', false);
								let btn = document.getElementById('merge-button');					
								btn.innerHTML = '{{ __('Merge Audio Files') }}';
								document.querySelector('#loader-line')?.classList?.add('hidden'); 
							},
							success: function(data) {
								$("html, body").animate({scrollTop: $("#sound-studio-header").offset().top}, 200);
								toastr.success('{{ __('Audio files where successfully merged') }}');
								$("#studioResultsTable").DataTable().ajax.reload();
							},
							error: function(data) {
								if (data.responseJSON['error']) {
									Swal.fire('Text to Speech Notification', data.responseJSON['error'], 'warning');
								}

								$('#merge-button').prop('disabled', false);
								let btn = document.getElementById('merge-button');					
								btn.innerHTML = '{{ __('Merge Audio Files') }}';
								document.querySelector('#loader-line')?.classList?.add('hidden');    
			
							}
						}).done(function(data) {
						})

					}
				}
				
			});


			// DELETE SYNTHESIZE RESULT
			$(document).on('click', '.deleteResultButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Result Deletion') }}',
					text: '{{ __('It will permanently delete this synthesize result') }}',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: '{{ __('Delete') }}',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						let formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'studio/result/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									toastr.success('{{ __('Synthesize result has been successfully deleted') }}');
									$("#resultsTable").DataTable().ajax.reload();								
								} else {
									toastr.error('{{ __('There was an error while deleting this result') }}');
								}      
							},
							error: function(data) {
								Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
							}
						})
					} 
				})
			});


			// DELETE STUDIO RESULT
			$(document).on('click', '.deleteStudioResultButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Studio Result Deletion') }}',
					text: '{{ __('It will permanently delete this merged audio files result') }}',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: '{{ __('Delete') }}',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						let formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'studio/final/result/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									toastr.success('{{ __('Sound Studio result has been successfully deleted') }}');
									$("#studioResultsTable").DataTable().ajax.reload();								
								} else {
									toastr.error('{{ __('There was an error while deleting this sound studio result') }}');
								}      
							},
							error: function(data) {
								Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
							}
						})
					} 
				})
			});

			document.getElementById("bg-music").onchange = function(event) {
				let url = event.target.options[event.target.selectedIndex].dataset.url;
				document.getElementById('listen-music').setAttribute("src", url);
			};

		});

		let currentAudio = '';
		let audioButton = new Audio();

		function previewMusic(element){
			let src = $(element).attr('src');
			let id = $(element).attr('id');

			let isPlaying = false;
			
			if (src == '') {

				Swal.fire('{{ __('Background Audio Not Selected') }}', '{{ __('Select preferred background audio first before listening it') }}', 'warning');
			
			} else {

				audioButton.src = src; 

				if (currentAudio == id) {
					audioButton.pause();
					isPlaying = false;
					document.getElementById(id).innerHTML = '<i class="fa fa-play"></i>';
					currentAudio = '';

				} else {    
					if(isPlaying) {
						audioButton.pause();
						isPlaying = false;
						document.getElementById(id).innerHTML = '<i class="fa fa-play"></i>';
						currentAudio = '';
					} else {
						audioButton.play();
						isPlaying = true;
						if (currentAudio) {
							document.getElementById(currentAudio).innerHTML = '<i class="fa fa-play"></i>';
						}
						document.getElementById(id).innerHTML = '<i class="fa fa-stop"></i>';
						currentAudio = id;
					}
				}

				audioButton.addEventListener('ended', (event) => {
					document.getElementById(id).innerHTML = '<i class="fa fa-play"></i>';
					isPlaying = false;
					currentAudio = '';
				});      
			}
		}

		$('#upload-music').on('click',function(e) {

			"use strict";

			e.preventDefault();

			var inputAudio = [];
			var duration;


			if (pond.getFiles().length !== 0) {   
				pond.getFiles().forEach(function(file) {
				inputAudio.push(file);
			});

			var audio = document.createElement('audio');
			var objectUrl = URL.createObjectURL(inputAudio[0].file);

			audio.src = objectUrl;
			audio.addEventListener('loadedmetadata', function(){
				duration = audio.duration;
			},false);

			var formData = new FormData();

			setTimeout(function() {

				
				formData.append('audiofile', inputAudio[0].file);
				formData.append('audiolength', duration);

				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: "POST",
					url: 'studio/music/upload',
					data: formData,
					contentType: false,
					processData: false,
					cache: false,
					beforeSend: function() {
						$('#upload-music').prop('disabled', true);
						let btn = document.getElementById('upload-music');					
						btn.innerHTML = loading;  
						document.querySelector('#loader-line')?.classList?.remove('hidden');         
					},
					complete: function() {
						$('#upload-music').prop('disabled', false);
						let btn = document.getElementById('upload-music');					
						btn.innerHTML = '{{ __('Upload Audio') }}';
						document.querySelector('#loader-line')?.classList?.add('hidden');            
					},
					success: function(data) {
					if (!data) {
						toastr.error('{{ __('Selected background music upload failed, please try again') }}');
					} else {
						toastr.success('{{ __('Selected background music file was uploaded successfully') }}');
					}
					},
					error: function(data) {

						if (!data) {
						Swal.fire('{{ __('File Upload Error') }}', '{{ __('Selected background music upload failed, please try again') }}', 'error');
						}

						$('#upload-music').prop('disabled', false);
						let btn = document.getElementById('upload-music');					
						btn.innerHTML = '{{ __('Upload Audio') }}';
						document.querySelector('#loader-line')?.classList?.add('hidden');  
						
						if (pond.getFiles().length != 0) {
							for (var j = 0; j <= pond.getFiles().length - 1; j++) {
								pond.removeFiles(pond.getFiles()[j].id);
							}
						}
					
						inputAudio = [];
					}
				}).done(function(data) {
				if (pond.getFiles().length != 0) {
					for (var j = 0; j <= pond.getFiles().length - 1; j++) {
						pond.removeFiles(pond.getFiles()[j].id);
					}
				}
				
				inputAudio = [];

				location.reload();
				})

			}, 500);
			
			} else {
				Swal.fire('{{ __('Missing Audio File') }}', '{{ __('Selected background audio file before uploading') }}', 'warning');
				return;
			}

		});

		FilePond.registerPlugin( 

		FilePondPluginFileValidateSize,
		FilePondPluginFileValidateType

		);

		var pond = FilePond.create(document.querySelector('.filepond'));
		var all_types;
		var maxFileSize;

		maxFileSize = 10 + 'MB';

		FilePond.setOptions({
			
			allowMultiple: false,
			maxFiles: 1,
			allowReplace: true,
			maxFileSize: maxFileSize,
			labelIdle: "{{ __('Drag & Drop your music file or') }} <span class=\"filepond--label-action\">{{ __('Browse') }}</span><br><span class='restrictions'>[<span class='restrictions-highlight'>" + maxFileSize + "</span>: MP3 | WAV | OGG]</span>",
			required: false,
			instantUpload: false,
			storeAsFile: true,
			acceptedFileTypes: ['audio/ogg', 'audio/mpeg', 'audio/wav'],
			labelFileProcessingError: (error) => {
				console.log(error);
			}
		});

	</script>
@endsection