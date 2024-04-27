@extends('layouts.app')
@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Awselect CSS -->
	<link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
	<!-- Green Audio Player CSS -->
	<link href="{{ URL::asset('plugins/audio-player/green-audio-player.css') }}" rel="stylesheet" />
	<!-- FilePond CSS -->
	<link href="{{URL::asset('plugins/filepond/filepond.css')}}" rel="stylesheet" />	
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!-- PAGE HEADER -->
<div class="page-header mt-5-7">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">{{ __('Sound Studio') }}</h4>
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.davinci.dashboard') }}"> {{ __('Davinci Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.davinci.configs') }}"> {{ __('Davinci Settings') }}</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="{{url('/' . $page='#')}}"> {{ __('Sound Studio Settings') }}</a></li>
		</ol>
	</div>
</div>
<!-- END PAGE HEADER -->
@endsection
@section('content')	
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-12">
			<div class="card border-0">	
				<div class="card-header">
					<h3 class="card-title"><i class="fa-solid fa-photo-film-music mr-2 text-primary"></i>{{ __('Sound Studio Settings') }}</h3>
				</div>			
				<div class="card-body pt-5">
					<div class="row">
						<div class="col-sm-12">
							<!-- CONTAINER FOR AUDIO FILE UPLOADS-->
							<div id="upload">							
								
								<!-- DRAG & DROP MEDIA FILES -->
								<div class="select-file">
									<input type="file" name="filepond" id="filepond" class="filepond"/>	
								</div>
								@error('filepond')
									<p class="text-danger">{{ $errors->first('filepond') }}</p>
								@enderror	

							</div> <!-- END CONTAINER FOR AUDIO FILE UPLOADS-->

							<div class="text-center mb-4">
								<button class="btn btn-primary ripple pl-6 pr-6" type="button" id="upload-music" data-tippy-content="{{ __('Upload Public Background Music Audio File') }}">{{ __('Upload Music File') }}</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-lg-12 col-md-12 col-xm-12 mt-4">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Background Music Files') }}</h3>
				</div>
				<div class="card-body pt-2">
					<!-- SET DATATABLE -->
					<table id='backgroundMusicTable' class='table' width='100%'>
						<thead>
							<tr>
								<th width="8%">{{ __('Uploaded On') }}</th>
								<th width="10%">{{ __('Uploaded By') }}</th>																
								<th width="15%">{{ __('File Name') }}</th>																
								<th width="5%">{{ __('Public') }}</th>
								<th width="5%">{{ __('Duration') }}</th>
								<th width="5%">{{ __('Listen') }}</th>
								<th width="5%">{{ __('Download') }}</th>
								<th width="5%">{{ __('File Size') }}</th>
								<th width="5%">{{ __('Format') }}</th>
								<th width="6%">{{ __('Actions') }}</th>
							</tr>
						</thead>
					</table> <!-- END SET DATATABLE -->
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<!-- Green Audio Player JS -->
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script src="{{ URL::asset('plugins/audio-player/green-audio-player.js') }}"></script>
	<script src="{{ URL::asset('js/audio-player.js') }}"></script>
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<!-- FilePond JS -->
	<script src={{ URL::asset('plugins/filepond/filepond.min.js') }}></script>
	<script src={{ URL::asset('plugins/filepond/filepond-plugin-file-validate-size.min.js') }}></script>
	<script src={{ URL::asset('plugins/filepond/filepond-plugin-file-validate-type.min.js') }}></script>	
	<script src={{ URL::asset('plugins/filepond/filepond.jquery.js') }}></script>
	<script src="{{URL::asset('js/sound-studio.js')}}"></script>
	<!-- Awselect JS -->
	<script src="{{URL::asset('plugins/awselect/awselect.min.js')}}"></script>
	<script src="{{URL::asset('js/awselect.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			// INITILIZE DATATABLE
			let list = $('#backgroundMusicTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: true,
				colReorder: true,
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('There are no music files uploaded yet') }}</div>",
					search: "<i class='fa fa-search search-icon'></i>",
					lengthMenu: '_MENU_ ',
					paginate : {
						first    : '<i class="fa fa-angle-double-left"></i>',
						last     : '<i class="fa fa-angle-double-right"></i>',
						previous : '<i class="fa fa-angle-left"></i>',
						next     : '<i class="fa fa-angle-right"></i>'
					}
				},
				"order": [[ 0, "desc" ]],
				pagingType : 'full_numbers',
				processing: true,
				serverSide: true,
				ajax: "{{ route('admin.studio.music') }}",
				columns: [{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},
					{
						data: 'username',
						name: 'username',
						orderable: true,
						searchable: true
					},
					{
						data: 'name',
						name: 'name',
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
						data: 'duration',
						name: 'duration',
						orderable: true,
						searchable: true
					},	
					{
						data: 'play',
						name: 'play',
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
						data: 'custom-size',
						name: 'custom-size',
						orderable: true,
						searchable: true
					},
					{
						data: 'type',
						name: 'type',
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


			// MAKE MUSIC PRIVATE
			$(document).on('click', '.makePrivateButton', function(e) {

				e.preventDefault();

				var formData = new FormData();
				formData.append("id", $(this).attr('id'));

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: 'studio/music/private',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('{{ __('Background Music is Private Now') }}', '{{ __('Selected audio file is set to private successfully') }}', 'success');
							$("#backgroundMusicTable").DataTable().ajax.reload();
						} else {
							Swal.fire('{{ __('Background Music is already Private') }}', '{{ __('Selected audio file is already set to private') }}', 'error');
						}      
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});


			// MAKE MUSIC PUBLIC
			$(document).on('click', '.makePublicButton', function(e) {

				e.preventDefault();

				var formData = new FormData();
				formData.append("id", $(this).attr('id'));

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: 'studio/music/public',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'success') {
							Swal.fire('{{ __('Background Music is Public Now') }}', '{{ __('Selected audio file is set to public successfully') }}', 'success');
							$("#backgroundMusicTable").DataTable().ajax.reload();
						} else {
							Swal.fire('{{ __('Background Music is already Public') }}', '{{ __('Selected audio file is already set to public') }}', 'error');
						}      
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});


			// DELETE SYNTHESIZE RESULT
			$(document).on('click', '.deleteMusicButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Audio File Deletion') }}',
					text: '{{ __('It will permanently delete this background audio file') }}',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Delete',
					reverseButtons: true,
					closeOnCancel: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'studio/music/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{ __('Audio File Deleted') }}', '{{ __('Selected background audio file has been successfully deleted') }}', 'success');	
									$("#backgroundMusicTable").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this audio file') }}', 'error');
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