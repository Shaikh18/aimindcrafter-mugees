@extends('layouts.app')
@section('css')
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!-- PAGE HEADER -->
<div class="page-header mt-5-7">
	<div class="page-leftheader">
		<h4 class="page-title mb-0">{{ __('All Image') }}</h4>
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-folder-bookmark mr-2 fs-12"></i>{{ __('AI Panel') }}</a></li>
			<li class="breadcrumb-item"><a href="{{route('user.documents')}}"> {{ __('Documents') }}</a></li>
			<li class="breadcrumb-item active"><a href="{{url('#')}}"> {{ __('All Image') }}</a></li>
		</ol>
	</div>
</div>
<!-- END PAGE HEADER -->
@endsection
@section('content')
	<div class="row mt-5">
		@foreach ($data as $image)
			<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 image-container">				
				<div class="grid-item">
					<div class="grid-image-wrapper">
						<div class="flex grid-buttons text-center">
							<a href="{{ url($image->image) }}" class="grid-image-view text-center" download><i class="fa-sharp fa-solid fa-arrow-down-to-line" title="{{ __('Download Image') }}"></i></a>
							<a href="#" class="grid-image-view text-center viewImageResult" id="{{ $image->id }}"><i class="fa-sharp fa-solid fa-camera-viewfinder" title="{{ __('View Image') }}"></i></a>
							<a href="#" class="grid-image-view text-center deleteResultButton" id="{{ $image->id }}"><i class="fa-solid fa-trash-xmark" title="{{ __('Delete Image') }}"></i></a>							
						</div>
						<div>
							<span class="grid-image">
								<img class="loaded" src="@if($image->storage == 'local') {{ URL::asset($image->image) }} @else {{ $image->image }} @endif" alt="" >
							</span>
						</div>
						<div class="grid-description">
							<span class="fs-9 text-primary">@if ($image->vendor == 'sd') {{ __('Stable Diffusion') }} @else {{ __('Dalle') }} @endif</span>
							<p class="fs-10 mb-0">{{ substr($image->description, 0, 63) }}...</p>
						</div>
					</div>
				</div>
			</div>
		@endforeach

		<input type="hidden" id="start" name="start" value="12">
		<input type="hidden" id="rowperpage" value="6">
		<input type="hidden" id="totalrecords" value="{{ $records }}">
	</div>

	<div class="image-modal">
		<div class="modal fade" id="image-view-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h6>{{ __('Image View') }}</h6>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					
				</div>
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

		checkWindowSize();

		$(document).on('click', '.viewImageResult', function(e) {

			"use strict";

			e.preventDefault();

			var id = $(this).attr("id");

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'post',
				url: 'images/view',
				data:{
					id: id,
				},
				success:function(data) {   
					if (data['status'] == 'success') {
						$("#image-view-modal .modal-body").html(data['modal']);
						var myModal = new bootstrap.Modal(document.getElementById('image-view-modal'))
						myModal.show();
					} else {
						toastr.error(data['message']);
					}
				
				}
			});
		});


		// DELETE SYNTHESIZE RESULT
		$(document).on('click', '.deleteResultButton', function(e) {

			e.preventDefault();

			Swal.fire({
				title: '{{ __('Confirm Image Deletion') }}',
				text: '{{ __('It will permanently delete this image') }}',
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
						url: '/user/images/delete',
						data: formData,
						processData: false,
						contentType: false,
						success: function (data) {
							if (data['status'] == 'success') {
								toastr.success('{{ __('Selected image has been successfully deleted') }}');	
								location.replace(location.href);								
							} else {
								toastr.error('{{ __('There was an error while deleting this image') }}');
							}       
						},
						error: function(data) {
							Swal.fire('Oops...','{{ __('Something went wrong') }}!', 'error')
						}
					})
				} 
			})
		});


		// FETCH IMAGES FOR MOBILE
		$(document).on('touchmove', onScroll);         
				
		$(window).scroll(function(){
			let position = $(window).scrollTop();
			let bottom = $(document).height() - $(window).height();	
			if( position == bottom ){
				fetchData(); 
			}
		});
		
	});

	function onScroll(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			fetchData(); 
		}
	}

	function getFile(uri) {
		//window.open(data,'_blank');
		// window.location.href = data;
		var link = document.createElement("a");
            link.href = uri;
            link.setAttribute("download", "download");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            delete link;
		return false;
	}

	// Check if the page has enough content or not. If not then fetch records
    function checkWindowSize(){
        if($(window).height() >= $(document).height()){
            fetchData();
        }
    }
 
    // Fetch records
    function fetchData(){
        var start = Number($('#start').val());
        var allcount = Number($('#totalrecords').val());
        var rowperpage = Number($('#rowperpage').val());
        start = start + rowperpage;
 
        if(start <= allcount){
            $('#start').val(start);
 
            $.ajax({
                url:"{{route('user.images.load')}}",
                data: {start:start},
                dataType: 'json',
                    success: function(response){
                    $(".image-container:last").after(response.html).show().fadeIn("slow");
 
                    // Check if the page has enough content or not. If not then fetch records
                    checkWindowSize();
                }
            });
        }
    }

</script>
@endsection