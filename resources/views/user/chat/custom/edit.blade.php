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
		<h4 class="page-title mb-0">{{ __('Update Custom Chat Assistant') }}</h4>
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i>{{ __('User') }}</a></li>
			<li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.chat') }}"> {{ __('AI Chats') }}</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Update Custom Chat Assistant') }}</a></li>
		</ol>
	</div>
</div>
<!-- END PAGE HEADER -->
@endsection
@section('content')	
	<div class="row justify-content-center">
		<div class="col-lg-9 col-md-12 col-sm-12">
			<div class="card border-0">	
				<div class="card-header">
					<h3 class="card-title"><i class="fa-solid fa-microchip-ai mr-2 text-primary"></i>{{ __('Custom Chat Generator') }}</h3>
					<a href="{{ route('user.chat') }}" class="btn btn-cancel ripple" style="margin-left: auto">{{ __('Back to AI Chats') }}</a>
				</div>			
				<div class="card-body pt-5 pb-0 pl-6 pr-6">
					<form class="w-100" action="{{ route('user.chat.custom.update', $id->id) }}" method="POST" enctype="multipart/form-data">
						@method('PUT')
						@csrf

						<div class="row justify-content-center">					  
							<div class="col-sm-12 col-md-12">
							  	<div class="input-box mb-4">
									<label class="form-label fs-12 font-weight-semibold">{{ __('Select Chat Assistant Avatar') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></label>
									<div class="input-group file-browser" id="create-new-chat">									
										<input type="text" class="form-control border-right-0 browse-file" placeholder="{{ __('Minimum 60px by 60px image') }}" readonly>
										<label class="input-group-btn">
											<span class="btn btn-primary special-btn">
											{{ __('Browse') }} <input type="file" name="logo" style="display: none;" accept=".jpg, .png, .webp">
											</span>
										</label>
									</div>
									@error('logo')
										<p class="text-danger">{{ $errors->first('logo') }}</p>
									@enderror
							  	</div>
							</div>				
						</div>
						
						<div class="col-md-12 col-sm-12 mt-2 mb-4 pl-0">
							<div class="form-group">
							  	<label class="custom-switch">
									<input type="checkbox" name="activate" class="custom-switch-input" @if ($id->status) checked @endif>
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">{{ __('Activate Chat Assistant') }}</span>
							  	</label>
							</div>
						</div>
						  
						<div class="row">
							<div class="col-md-6 col-sm-12">													
							  	<div class="input-box">								
									<h6 class="fs-12">{{ __('Chat Assistant Name') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control @error('name') is-danger @enderror" id="name" name="name" value="{{ $id->name }}" required>
										@error('name')
											<p class="text-danger">{{ $errors->first('name') }}</p>
										@enderror
									</div> 
							  	</div> 
							</div>

							<div class="col-md-6 col-sm-12">													
								<div class="input-box">								
								  <h6 class="fs-12">{{ __('Chat Assistant Role Description') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
								  <div class="form-group">							    
									  <input type="text" class="form-control @error('sub_name') is-danger @enderror" id="sub_name" name="sub_name" value="{{ $id->sub_name }}" placeholder="{{ __('Finance Expert') }}">
									  @error('sub_name')
										  <p class="text-danger">{{ $errors->first('sub_name') }}</p>
									  @enderror
								  </div> 
								</div> 
						  	</div>

							<div class="col-md-6 col-sm-12">													
								<div class="input-box">								
								  <h6 class="fs-12">{{ __('Chat Assistant Welcome Message') }} <span class="text-muted">({{ __('Optional') }})</span></h6>
								  <div class="form-group">							    
									  <input type="text" class="form-control @error('character') is-danger @enderror" id="character" name="character" placeholder="{{ __('Hey there! Let me help you with your finance questions today...') }}" value="{{ $id->description }}">
									  @error('character')
										  <p class="text-danger">{{ $errors->first('character') }}</p>
									  @enderror
								  </div> 
								</div> 
						  	</div>

							<div class="col-md-6 col-sm-12">
								<div class="input-box">
								  	<h6 class="fs-12">{{ __('Chat Assistant Group') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
								  	<select id="group" name="group" class="form-control">
										@foreach ($categories as $category)
											<option value="{{ $category->code }}" @if ($id->group == $category->code) selected @endif>{{ __($category->name) }}</option>
										@endforeach																																																													
								  	</select>
								</div>
							</div>
  
							<div class="col-sm-12">								
							  	<div class="input-box">								
								<h6 class="fs-12 mb-2 font-weight-semibold">{{ __('Instructions') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
								<div class="form-group">
									<div id="field-buttons"></div>							    
									<textarea type="text" rows=8 class="form-control @error('instructions') is-danger @enderror" id="instructions" name="instructions" placeholder="{{ __('Explain in details what AI Chat Assistant needs to do...') }}" required>{{ $id->prompt }}</textarea>
									@error('instructions')
										<p class="text-danger">{{ $errors->first('instructions') }}</p>
									@enderror
								</div> 
							  	</div> 
							</div>	

							<div class="col-md-6 col-sm-12 mt-2 mb-4 pl-0 text-center">
								<div class="form-group">
								  	<label class="custom-switch">
										<input type="checkbox" name="retrieval" class="custom-switch-input" @if ($id->retrieval) checked @endif>
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description">{{ __('Enable Knowledge Retrieval Tool') }} <i class="ml-1 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Knowledge Retrieval Tool must be enabled if you want Chat Assistant to consider your uploaded file or let upload a file during chat') }}"></i></span>
								  	</label>
								</div>
							</div>

							<div class="col-md-6 col-sm-12 mt-2 mb-4 pl-0 text-center">
								<div class="form-group">
								  	<label class="custom-switch">
										<input type="checkbox" name="code" class="custom-switch-input" @if ($id->code_interpreter) checked @endif>
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description">{{ __('Enable Code Enterpreter Tool') }}</span>
								  	</label>
								</div>
							</div>
							
							<div class="col-sm-12 col-md-12">
								<div class="input-box">
								  	<label class="form-label fs-12 font-weight-semibold">{{ __('File Access') }} <span class="text-muted">({{ __('Optional') }})</span> <i class="ml-1 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Knowledge Retrieval Tool must be enabled if you want Chat Assistant to consider your uploaded file') }}"></i></label>
									<div class="input-group file-browser" id="create-new-chat">									
										<input type="text" class="form-control border-right-0 browse-file" placeholder="{{ __('Include your file for which you want your AI Chat Assistant to have access') }}" readonly>
										<label class="input-group-btn">
										<span class="btn btn-primary special-btn">
											{{ __('Browse') }} <input type="file" name="file" style="display: none;" accept=".c, .cpp, .docx, .html, .java, .md, .php, .pptx, .py, .rb, .tex, .css, .js, .gif, .tar, .ts, .xlsx, .xml, .zip, .pdf, .csv, .txt, .json">
										</span>
										</label>
									</div>
								</div>
							</div>
						</div>
						
						<div class="modal-footer d-inline">
							<div class="row text-center">
							  	<div class="col-md-12">
									<button type="submit" class="btn btn-primary ripple pl-6 pr-6">{{ __('Update Chat Assistant') }}</button>
							  	</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
@endsection