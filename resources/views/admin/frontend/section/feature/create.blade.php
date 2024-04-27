@extends('layouts.app')

@section('css')
	<!-- RichText CSS -->
	<link href="{{URL::asset('plugins/richtext/richtext.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('New Feature') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-globe mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{url('#')}}"> {{ __('Frontend Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.settings.feature') }}"> {{ __('Features Section') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('New Feature') }}</a></li>
			</ol>
		</div>
		
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')						
	<!-- FAQ -->
	<div class="row">
		<div class="col-lg-8 col-md-8 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Create New Feature') }}</h3>								
				</div>
				<div class="card-body pt-5">									
					<form id="" action="{{ route('admin.settings.feature.store') }}" method="post" enctype="multipart/form-data">
						@csrf

						<div class="row">							
							<div class="col-sm-12">							
								<div class="input-box">								
									<h6>{{ __('Feature Title') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
									</div> 
									@error('title')
										<p class="text-danger">{{ $errors->first('title') }}</p>
									@enderror	
								</div>						
							</div>

							<div class="col-sm-12">
								<div class="input-box">
									<h6>{{ __('Feature Banner') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="input-group file-browser">									
										<input type="text" class="form-control border-right-0 browse-file" placeholder="{{ __('Image File Name') }}" readonly required>
										<label class="input-group-btn">
											<span class="btn btn-primary special-btn">
												{{ __('Browse') }} <input type="file" name="logo" style="display: none;">
											</span>
										</label>
									</div>
									@error('image')
										<p class="text-danger">{{ $errors->first('image') }}</p>
									@enderror
								</div>
							</div>	

							<div class="col-sm-12">							
								<div class="input-box">								
									<h6>{{ __('Feature Status') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="col-md-12 col-sm-12 mt-2 mb-4 pl-0">
										<div class="form-group">
										  	<label class="custom-switch">
												<input type="checkbox" name="activate" class="custom-switch-input" checked>
												<span class="custom-switch-indicator"></span>
												<span class="custom-switch-description">{{ __('Activate Feature') }}</span>
										  	</label>
										</div>
									  </div>
								</div>						
							</div>					
						</div>

						<div class="row mt-2">
							<div class="col-lg-12 col-md-12 col-sm-12">	
								<div class="input-box">	
									<h6>{{ __('Feature Description') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>							
									<textarea class="form-control" name="description" rows="12" id="richtext" required>{{ old('description') }}</textarea>
									@error('description')
										<p class="text-danger">{{ $errors->first('description') }}</p>
									@enderror	
								</div>											
							</div>
						</div>

						<!-- ACTION BUTTON -->
						<div class="border-0 text-right mb-2 mt-1">
							<a href="{{ route('admin.settings.faq') }}" class="btn btn-cancel mr-2">{{ __('Cancel') }}</a>
							<button type="submit" class="btn btn-primary">{{ __('Create') }}</button>							
						</div>				

					</form>					
				</div>
			</div>
		</div>
	</div>
	<!-- END -->
@endsection

@section('js')
	<!-- RichText JS -->
	<script src="{{URL::asset('plugins/richtext/jquery.richtext.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			$('#richtext').richText({

				// text formatting
				bold: true,
				italic: true,
				underline: true,

				// text alignment
				leftAlign: true,
				centerAlign: true,
				rightAlign: true,
				justify: true,

				// lists
				ol: true,
				ul: true,

				// title
				heading: true,

				// fonts
				fonts: true,
				fontList: [
					"Arial", 
					"Arial Black", 
					"Comic Sans MS", 
					"Courier New", 
					"Geneva", 
					"Georgia", 
					"Helvetica", 
					"Impact", 
					"Lucida Console", 
					"Tahoma", 
					"Times New Roman",
					"Verdana"
				],
				fontColor: true,
				fontSize: true,

				// uploads
				imageUpload: false,
				fileUpload: false,

				// media
				videoEmbed: false,

				// link
				urls: true,

				// tables
				table: false,

				// code
				removeStyles: true,
				code: false,

				// colors
				colors: [],

				// dropdowns
				fileHTML: '',
				imageHTML: '',

				// translations
				translations: {
					'title': 'Title',
					'white': 'White',
					'black': 'Black',
					'brown': 'Brown',
					'beige': 'Beige',
					'darkBlue': 'Dark Blue',
					'blue': 'Blue',
					'lightBlue': 'Light Blue',
					'darkRed': 'Dark Red',
					'red': 'Red',
					'darkGreen': 'Dark Green',
					'green': 'Green',
					'purple': 'Purple',
					'darkTurquois': 'Dark Turquois',
					'turquois': 'Turquois',
					'darkOrange': 'Dark Orange',
					'orange': 'Orange',
					'yellow': 'Yellow',
					'imageURL': 'Image URL',
					'fileURL': 'File URL',
					'linkText': 'Link text',
					'url': 'URL',
					'size': 'Size',
					'responsive': 'Responsive',
					'text': 'Text',
					'openIn': 'Open in',
					'sameTab': 'Same tab',
					'newTab': 'New tab',
					'align': 'Align',
					'left': 'Left',
					'center': 'Center',
					'right': 'Right',
					'rows': 'Rows',
					'columns': 'Columns',
					'add': 'Add',
					'pleaseEnterURL': 'Please enter an URL',
					'videoURLnotSupported': 'Video URL not supported',
					'pleaseSelectImage': 'Please select an image',
					'pleaseSelectFile': 'Please select a file',
					'bold': 'Bold',
					'italic': 'Italic',
					'underline': 'Underline',
					'alignLeft': 'Align left',
					'alignCenter': 'Align centered',
					'alignRight': 'Align right',
					'addOrderedList': 'Add ordered list',
					'addUnorderedList': 'Add unordered list',
					'addHeading': 'Add Heading/title',
					'addFont': 'Add font',
					'addFontColor': 'Add font color',
					'addFontSize' : 'Add font size',
					'addImage': 'Add image',
					'addVideo': 'Add video',
					'addFile': 'Add file',
					'addURL': 'Add URL',
					'addTable': 'Add table',
					'removeStyles': 'Remove styles',
					'code': 'Show HTML code',
					'undo': 'Undo',
					'redo': 'Redo',
					'close': 'Close'
				},
						
				// privacy
				youtubeCookies: false,

				// developer settings
				useSingleQuotes: false,
				height: 0,
				heightPercentage: 0,
				id: "",
				class: "",
				useParagraph: false,
				maxlength: 0,
				callback: undefined,
				useTabForNext: false
			});

		});
	</script>
@endsection
