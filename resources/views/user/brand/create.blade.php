@extends('layouts.app')
@section('css')
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!-- PAGE HEADER -->
<div class="page-header mt-5-7 justify-content-center">
	<div class="page-leftheader text-center">
		<h4 class="page-title mb-0">{{ __('New Brand Voice') }}</h4>
		<ol class="breadcrumb mb-2">
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
					<h3 class="card-title"><i class="fa-solid fa-signature mr-2 text-primary"></i>{{ __('Brand Information') }}</h3>
					<a href="{{ route('user.brand') }}" class="btn btn-cancel mr-2 pl-5 pr-5" style="margin-left: auto">{{ __('Back to Brands List') }}</a>
				</div>			
				<div class="card-body pt-5">
					<form class="w-100" action="{{ route('user.brand.store') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row">	

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6>{{ __('Company / Brand Name') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control @error('name') is-danger @enderror" id="name" name="name" placeholder="{{ __('Provide company / brand name') }}">
										@error('name')
											<p class="text-danger">{{ $errors->first('name') }}</p>
										@enderror
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6>{{ __('Website') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control @error('website') is-danger @enderror" id="website" name="website" placeholder="{{ __('Enter company website URL') }}">
										@error('website')
											<p class="text-danger">{{ $errors->first('website') }}</p>
										@enderror
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6>{{ __('Industry') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control @error('industry') is-danger @enderror" id="industry" name="industry" placeholder="{{ __('List your company / brand industries that you focus on') }}">
										@error('industry')
											<p class="text-danger">{{ $errors->first('industry') }}</p>
										@enderror
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6>{{ __('Tagline') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control @error('tagline') is-danger @enderror" id="tagline" name="tagline" placeholder="{{ __('Provide a catchy tagline for your company / brand') }}">
										@error('tagline')
											<p class="text-danger">{{ $errors->first('tagline') }}</p>
										@enderror
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">													
								<div class="input-box">								
									<h6>{{ __('Target Audience') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control @error('audience') is-danger @enderror" id="audience" name="audience" placeholder="{{ __('Describe the primary target audience for your company / brand') }}">
										@error('audience')
											<p class="text-danger">{{ $errors->first('audience') }}</p>
										@enderror
									</div> 
								</div> 
							</div>

							<div class="col-lg-6 col-md-12 col-sm-12">
								<div class="input-box">
									<h6 >{{ __('Tone of Voice') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select name="tone" class="form-select">
										<option value="Professional" selected> {{ __('Professional') }}</option>	
										<option value="Exciting"> {{ __('Exciting') }}</option>	
										<option value="Friendly"> {{ __('Friendly') }}</option>	
										<option value="Witty"> {{ __('Witty') }}</option>	
										<option value="Humorous"> {{ __('Humorous') }}</option>	
										<option value="Convincing"> {{ __('Convincing') }}</option>	
										<option value="Empathetic"> {{ __('Empathetic') }}</option>	
										<option value="Inspiring"> {{ __('Inspiring') }}</option>	
										<option value="Supportive"> {{ __('Supportive') }}</option>	
										<option value="Trusting"> {{ __('Trusting') }}</option>	
										<option value="Playful"> {{ __('Playful') }}</option>	
										<option value="Excited"> {{ __('Excited') }}</option>	
										<option value="Positive"> {{ __('Positive') }}</option>	
										<option value="Negative"> {{ __('Negative') }}</option>	
										<option value="Engaging"> {{ __('Engaging') }}</option>	
										<option value="Worried"> {{ __('Worried') }}</option>	
										<option value="Urgent"> {{ __('Urgent') }}</option>	
										<option value="Passionate"> {{ __('Passionate') }}</option>	
										<option value="Informative"> {{ __('Informative') }}</option>
										<option value="Funny">{{ __('Funny') }}</option>
										<option value="Casual"> {{ __('Casual') }}</option>																																																														
										<option value="Sarcastic"> {{ __('Sarcastic') }}</option>																																																																																												
										<option value="Dramatic"> {{ __('Dramatic') }}</option>	
									</select>
								</div>
							</div>

							<div class="col-sm-12">								
								<div class="input-box">								
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Company / Brand Description') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">						    
										<textarea type="text" rows=5 class="form-control @error('description') is-danger @enderror" id="description" name="description" placeholder="{{ __('Provide a brief description of your company / brand') }}"></textarea>
										@error('description')
											<p class="text-danger">{{ $errors->first('description') }}</p>
										@enderror
									</div> 
								</div> 
							</div>								
						</div>

					
						<h3 class="card-title mt-3"><i class="fa-solid fa-grid-horizontal mr-2 text-primary"></i>{{ __('Products or Services Information') }}</h3>
					

						<div class="row">
							<div class="col-sm-12 mt-4 mb-5">
								<div class="form-group">								
									<div class="field input-group mb-4">
										<input type="text" class="form-control" name="names[]" placeholder="{{ __('Provide product / service name') }}" id="input-field-1">
										<input type="text" class="form-control" name="descriptions[]" placeholder="{{ __('Provide brief product / service description') }}">
										<select class="form-select mr-4" name="types[]">
											<option value="product" selected>{{ __('Product') }}</option>
											<option value="service">{{ __('Service') }}</option>
											<option value="other">{{ __('Other') }}</option>
										</select>
										<span onclick="addField(this)" class="btn btn-primary">
											<i class="fa fa-btn fa-plus"></i>
										</span>
										<span onclick="removeField(this)" class="btn btn-primary">
											<i class="fa fa-btn fa-minus"></i>
										</span>										
									</div>
									<div id="field-container"></div>
								</div>
							</div>

							
				
							<div class="col-md-12 col-sm-12 text-center mb-2">
								<button type="submit" class="btn btn-primary ripple pl-8 pr-8">{{ __('Create') }}</button>	
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
	<script type="text/javascript">

		let i = 2;
		function addField(plusElement){

			let field_type = plusElement.previousElementSibling;
			let placeholder = field_type.previousElementSibling;	
			let name = placeholder.previousElementSibling;	
		
			// Stopping the function if the input field has no value.
			if(placeholder.previousElementSibling.value.trim() === ""){
				toastr.warning('{{ __('Please fill in all product / service information first') }}');
				return false;
			} else if (field_type.previousElementSibling.value.trim() === "") {
				toastr.warning('{{ __('Please fill in all product / service information first') }}');
				return false;
			}

			createButton(name.id);
			
			let new_field ='<div class="field input-group mb-4">' +
								'<input type="text" class="form-control" name="names[]" id="input-field-' + i + '" placeholder="{{ __('Provide product / service name') }}">' +
								'<input type="text" class="form-control" placeholder="{{ __('Provide bried product / service description') }}" name="descriptions[]">' +								
								'<select class="form-select mr-4" name="types[]">' +
									'<option value="product" selected>{{ __('Product') }}</option>' +
									'<option value="service">{{ __('Service') }}</option>' +
									'<option value="other">{{ __('Other') }}</option>' +
								'</select>' +
								'<span onclick="addField(this)" class="btn btn-primary">' +
									'<i class="fa fa-btn fa-plus"></i>' +
								'</span>' +
								'<span onclick="removeField(this)" class="btn btn-primary">' +
									'<i class="fa fa-btn fa-minus"></i>' +
								'</span>' +
							'</div>';
			i++;
   			$("#field-container").append(new_field);

			// Un hiding the minus sign.
			plusElement.nextElementSibling.style.display = "block"; 
			// Hiding the plus sign.
			plusElement.style.display = "none"; 
		}

		function removeField(minusElement){
			let plusElement = minusElement.previousElementSibling;
			let field_type = plusElement.previousElementSibling;
			let placeholder = field_type.previousElementSibling;	
			let name = placeholder.previousElementSibling;	

			minusElement.parentElement.remove();
		}

		function createButton(id) {
			let new_button = '<span onclick="insertText(this)" id="' + id+'-button" class="btn btn-primary mr-4 mb-2">' + id + '</span>';
		}



	</script>
@endsection