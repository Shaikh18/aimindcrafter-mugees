@extends('layouts.app')
@section('css')
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
	<!-- RichText CSS -->
	<link href="{{URL::asset('plugins/richtext/richtext.min.css')}}" rel="stylesheet" />
@endsection

@section('content')

<form id="wizard-form" action="" method="post" enctype="multipart/form-data" class="mt-24"> 		
	@csrf
	<div class="row justify-content-center">	
		<div class="col-sm-12 text-center">
			<h3 class="card-title fs-20 mb-0 super-strong"><i class="fa-solid fa-sharp fa-sparkles mr-2 text-primary"></i>{{ __('AI Article Wizard') }}</h3>
			<div class="mb-4" id="balance-status">
				<span class="fs-11 text-muted pl-3"><i class="fa-sharp fa-solid fa-bolt-lightning mr-2 text-primary"></i>{{ __('Your Balance is') }} <span class="font-weight-semibold" id="balance-number">@if (auth()->user()->available_words == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_words + auth()->user()->available_words_prepaid) }}@endif</span> {{ __('Words') }}</span>
			</div>	
		</div>

		<div class="col-lg-7 col-md-10 col-sm-12 mb-7">
			<div class="wizard-nav">
				<div class="wizard-nav-inner">					
					<div class="row text-center justify-content-center">
						<div class="col-3">
							<div class="d-flex wizard-nav-text">
								<div class="wizard-step-number current-step mr-3 fs-14" id="step-one-number">1</div>
								<div class="wizard-step-title"><span class="font-weight-bold fs-14">{{ __('Get Ideas') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 1') }}</span></div>
							</div>
							<div>
								<i class="fa-solid fa-chevrons-right wizard-nav-chevron" id="step-one-icon"></i>
							</div>									
						</div>	
						<div class="col-3">
							<div class="d-flex wizard-nav-text">
								<div class="wizard-step-number mr-3 fs-14" id="step-two-number">2</div>
								<div class="wizard-step-title responsive"><span class="font-weight-bold fs-14">{{ __('Outlines') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 2') }}</span></div>
							</div>	
							<div>
								<i class="fa-solid fa-chevrons-right wizard-nav-chevron" id="step-two-icon"></i>
							</div>								
						</div>
						<div class="col-3">
							<div class="d-flex wizard-nav-text">
								<div class="wizard-step-number mr-3 fs-14" id="step-three-number">3</div>
								<div class="wizard-step-title"><span class="font-weight-bold fs-14">{{ __('Talking Points') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 3') }}</span></div>
							</div>	
							<div>
								<i class="fa-solid fa-chevrons-right wizard-nav-chevron" id="step-three-icon"></i>
							</div>								
						</div>
						<div class="col-3">
							<div class="d-flex wizard-nav-text">
								<div class="wizard-step-number mr-3 fs-14" id="step-four-number">4</div>
								<div class="wizard-step-title responsive"><span class="font-weight-bold fs-14">{{ __('Images') }}</span> <br> <span class="text-muted wizard-step-title-number fs-11 float-left">{{ __('STEP 4') }}</span></div>
							</div>									
						</div>
					</div>					
				</div>
			</div>			
			<a href="#" id="new-wizard" data-tippy-content="{{ __('New Article Wizard') }}"><i class="fa-solid fa-layer-plus"></i></a>			
		</div>

		<div class="row justify-content-center">
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="card border-0" id="all-wizard-options">
					<div class="card-body p-5 pb-0">

						<div class="row">						
							<div class="col-sm-12 step-one">								
								<div class="input-box">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Topic') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>	
									<span id="topic-length" style="display:none">2000</span>								
									<div class="form-group">						  
										<textarea type="text" rows=5 class="form-control" id="topic" name="topic" placeholder="{{ __('Describe what your article is about') }}" required></textarea>								 
									</div> 
								</div> 
							</div>	

							<div class="col-sm-12 general hidden">								
								<div class="input-box">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Title') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>	
									<span id="title-length" style="display:none">2000</span>								
									<div class="form-group">						  
										<textarea type="text" rows=1 class="form-control" id="title" name="title" placeholder="{{ __('Provide your article title') }}" required></textarea>								 
									</div> 
								</div> 
							</div>	

							<div class="col-md-6 step-one">
								<div class="input-box mb-4">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Number of Topics') }}</h6>									
									<div class="form-group">				  
										<input type="number" class="form-control" id="topics-numbers" min="1" name="topics_number" value="3">
									</div> 
								</div> 
							</div>

							<div class="col-md-6 step-one">
								<div class="input-box mb-4">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Maximum Topic Words') }}</h6>									
									<div class="form-group">				  
										<input type="number" class="form-control" id="topic-length" min="1" name="topic_length" value="20">
									</div> 
								</div> 
							</div>
						
							<div class="col-sm-12 last-step-extra">								
								<div class="input-box">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Keywords') }}</h6>									
									<span id="keywords-length" style="display:none">1000</span>
									<div class="form-group">				  
										<input type="text" class="form-control" id="keywords" name="keywords" placeholder="{{ __('Type your keywords') }}">
									</div> 
								</div> 
							</div>	
							
							<div class="text-center step-one">
								<p class="text-muted fs-11 mb-1">{{ __('or') }}</p>
							</div>							
							
							<div class="col-sm-12 step-one">
								<div class="input-box mb-4">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Number of Keywords') }}</h6>									
									<div class="form-group">				  
										<input type="number" class="form-control" id="keywords-numbers" name="keywords_numbers" min="1" value="10">
									</div> 
								</div> 
							</div>
							<div class="col-sm-12 step-one">
								<div class="text-center">
									<button class="btn btn-keywords ripple fs-11 pl-6 pr-6 pt-2 pb-2 fs-11" id="generate-keywords">{{ __('Generate Keywords') }}</button>
								</div>
							</div>

							<div class="col-sm-12 step-two hidden">
								<div class="input-box mb-4">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Number of Outlines') }}</h6>									
									<div class="form-group">				  
										<input type="number" class="form-control" id="outline-number" min="3" name="outline_number" value="3">
									</div> 
								</div> 
							</div>

							<div class="col-sm-12 step-two hidden">
								<div class="input-box mb-4">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Number of Subtitles') }}</h6>									
									<div class="form-group">				  
										<input type="number" class="form-control" id="outline-subtitles" min="5" name="outline_subtitles" value="5">
									</div> 
								</div> 
							</div>

							<div class="col-sm-12 step-three hidden">								
								<div>	
									<h6 class="fs-11 mb-0 font-weight-semibold">{{ __('Article Outline') }}</h6>	
									<span class="fs-11 text-muted">{{ __('You can drag and drop to rearrange sections') }}</span>								
								</div> 
								<div id="outline-results-wrapper">
									<ul id="outline-results-list"></ul>
									<a href="#" id="add-new-section" class="fs-11 text-muted"><i class="fa-solid fa-circle-plus mr-2"></i> {{ __('Add New Section') }}</a>
								</div>
							</div>

							<div class="col-sm-12 step-three hidden">
								<div class="input-box mb-4 mt-3">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Number of Talking Points per Outline') }}</h6>									
									<div class="form-group">				  
										<input type="number" class="form-control" id="points-number" min="1" name="points_number" value="2">
									</div> 
								</div> 
							</div>

							<div class="col-sm-12 step-three hidden">
								<div class="input-box mb-4 mt-2">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Maximum Talking Point Words') }}</h6>									
									<div class="form-group">				  
										<input type="number" class="form-control" id="points-length" min="1" name="points_length" value="10">
									</div> 
								</div> 
							</div>

							<div class="col-sm-12 step-four hidden">								
								<div class="input-box">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Image Description') }}</h6>	
									<span id="image-length" style="display:none">2000</span>								
									<div class="form-group">						  
										<textarea type="text" rows=5 class="form-control" id="image" name="image_description"></textarea>								 
									</div> 
								</div> 
							</div>	

							<div class="col-sm-12 step-four hidden">
								<div id="form-group" class="mb-4">
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Image Size') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('The image resolution of the generated images') }}"></i></h6>
									<select id="image_size" name="image_size" class="form-select">																	
										@if (config('settings.wizard_image_vendor') == 'dall-e-2')
											<option value='256x256' selected>256 x 256px</option>
											<option value='512x512'>512 x 512px</option>	
											<option value='1024x1024'>1024 x 1024px</option>
										@elseif (config('settings.wizard_image_vendor') == 'dall-e-3' || config('settings.wizard_image_vendor') == 'dall-e-3-hd')			
											<option value='1024x1024' selected>1024 x 1024px</option>																												
											<option value='1024x1792'>1024 x 1792px</option>																												
											<option value='1792x1024'>1792 x 1024px</option>
										@elseif (config('settings.wizard_image_vendor') == 'stable-diffusion-v1-6')
											<option value='1024x512'>1024 x 512px</option>
											<option value='896x512'>896 x 512px</option>
											<option value='768x512'>768 x 512px</option>
											<option value='512x512' selected>512 x 512px</option>
											<option value='512x768'>512 x 768px</option>	
											<option value='512x896'>512 x 896px</option>	
											<option value='512x1024'>512 x 1024px</option>	
										@elseif (config('settings.wizard_image_vendor') == 'stable-diffusion-xl-1024-v1-0')
											<option value='1536x640'>1536 x 640px</option>
											<option value='1344x768'>1344 x 768px</option>
											<option value='1216x832'>1216 x 832px</option>
											<option value='1152x896'>1152 x 896px</option>
											<option value='1024x1024' selected>1024 x 1024px</option>
											<option value='896x1152'>896 x 1152px</option>
											<option value='832x1216'>832 x 1216px</option>
											<option value='768x1344'>768 x 1344px</option>
											<option value='640x1536'>640 x 1536px</option>
										@elseif (config('settings.wizard_image_vendor') == 'none')
											<option value='none'>{{ __('Image generation feature is disabled') }}</option>
										@endif																																																																																																																																							
									</select>
								</div>
							</div>

							{{-- <div class="col-sm-12 step-four hidden">
								<div class="input-box mb-4 mt-2">	
									<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Number of Images') }}</h6>									
									<div class="form-group">				  
										<input type="number" class="form-control" id="images-number" min="1" max="5" name="images_number" value="1">
									</div> 
								</div> 
							</div> --}}
						

							<div class="col-sm-12">
								<div class="divider mt-0" id="wizard-advanced">
									<div class="divider-text text-muted">
										<a class="fs-11 text-muted" id="advanced-settings-toggle" href="#">{{ __('Advanced Options') }} <span>+</span></a>
									</div>
								</div>
							</div>

							<div id="wizard-advanced-wrapper" class="no-gutters">
								<div class="col-md-12 col-sm-12">
									<div id="form-group">
										<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Writing Tone') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('Set result tone of the text as needed') }}"></i></h6>
										<select id="tone" name="tone" class="form-select" >
											<option value="Professional" @if ($wizard['tone'] == 'Professional') selected @endif> {{ __('Professional') }}</option>	
											<option value="Exciting" @if ($wizard['tone'] == 'Exciting') selected @endif> {{ __('Exciting') }}</option>	
											<option value="Friendly" @if ($wizard['tone'] == 'Friendly') selected @endif> {{ __('Friendly') }}</option>	
											<option value="Witty" @if ($wizard['tone'] == 'Witty') selected @endif> {{ __('Witty') }}</option>	
											<option value="Humorous" @if ($wizard['tone'] == 'Humorous') selected @endif> {{ __('Humorous') }}</option>	
											<option value="Convincing" @if ($wizard['tone'] == 'Convincing') selected @endif> {{ __('Convincing') }}</option>	
											<option value="Empathetic" @if ($wizard['tone'] == 'Empathetic') selected @endif> {{ __('Empathetic') }}</option>	
											<option value="Inspiring" @if ($wizard['tone'] == 'Inspiring') selected @endif> {{ __('Inspiring') }}</option>	
											<option value="Supportive" @if ($wizard['tone'] == 'Supportive') selected @endif> {{ __('Supportive') }}</option>	
											<option value="Trusting" @if ($wizard['tone'] == 'Trusting') selected @endif> {{ __('Trusting') }}</option>	
											<option value="Playful" @if ($wizard['tone'] == 'Playful') selected @endif> {{ __('Playful') }}</option>	
											<option value="Excited" @if ($wizard['tone'] == 'Excited') selected @endif> {{ __('Excited') }}</option>	
											<option value="Positive" @if ($wizard['tone'] == 'Positivite') selected @endif> {{ __('Positive') }}</option>	
											<option value="Negative" @if ($wizard['tone'] == 'Negative') selected @endif> {{ __('Negative') }}</option>	
											<option value="Engaging" @if ($wizard['tone'] == 'Engaging') selected @endif> {{ __('Engaging') }}</option>	
											<option value="Worried" @if ($wizard['tone'] == 'Worried') selected @endif> {{ __('Worried') }}</option>	
											<option value="Urgent" @if ($wizard['tone'] == 'Urgent') selected @endif> {{ __('Urgent') }}</option>	
											<option value="Passionate" @if ($wizard['tone'] == 'Passionate') selected @endif> {{ __('Passionate') }}</option>	
											<option value="Informative" @if ($wizard['tone'] == 'Informative') selected @endif> {{ __('Informative') }}</option>
											<option value="Funny" @if ($wizard['tone'] == 'Funny') selected @endif>{{ __('Funny') }}</option>
											<option value="Casual" @if ($wizard['tone'] == 'Casual') selected @endif> {{ __('Casual') }}</option>																																																														
											<option value="Sarcastic" @if ($wizard['tone'] == 'Sarcastic') selected @endif> {{ __('Sarcastic') }}</option>																																																																																												
											<option value="Dramatic" @if ($wizard['tone'] == 'Dramatic') selected @endif> {{ __('Dramatic') }}</option>																																																													
										</select>
									</div>
								</div>

								<div class="col-md-12 col-sm-12 mt-5">
									<div id="form-group">
										<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Creativity') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('Increase or decrease the creativity level to get variety in generated results') }}"></i></h6>
										<select id="creativity" name="creativity" class="form-select">
											@if (!is_null($wizard['creativity']))
												<option value=0 @if ($wizard['creativity'] == 0) selected @endif>{{ __('Repetitive') }}</option>
												<option value=0.25 @if ($wizard['creativity'] == 0.25) selected @endif> {{ __('Deterministic') }}</option>																															
												<option value=0.5 @if ($wizard['creativity'] == 0.5) selected @endif> {{ __('Original') }}</option>																															
												<option value=0.75 @if ($wizard['creativity'] == 0.75) selected @endif> {{ __('Creative') }}</option>																															
												<option value=1 @if ($wizard['creativity'] == 1) selected @endif> {{ __('Imaginative') }}</option>		
											@else
												<option value=0>{{ __('Repetitive') }}</option>
												<option value=0.25> {{ __('Deterministic') }}</option>																															
												<option value=0.5> {{ __('Original') }}</option>																															
												<option value=0.75> {{ __('Creative') }}</option>																															
												<option value=1 selected> {{ __('Imaginative') }}</option>	
											@endif
																																									
										</select>
									</div>
								</div>

								<div class="col-md-12 col-sm-12 mt-5">
									<div id="form-group">
										<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Point of View') }}</h6>
										<select id="view_point" name="view_point" class="form-select">
											<option value="first" @if ($wizard['view_point'] == 'first') selected @endif>{{ __('First Person') }}</option>
											<option value="second" @if ($wizard['view_point'] == 'second') selected @endif> {{ __('Second Person') }}</option>																															
											<option value="third" @if ($wizard['view_point'] == 'third') selected @endif> {{ __('Third Person') }}</option>																																															
										</select>
									</div>
								</div>

								<div class="col-md-12 col-sm-12">								
									<div class="input-box mt-5">								
										<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Article Length') }}</h6>
										<div class="form-group">							    
											<input type="number" class="form-control @error('words') is-danger @enderror" id="words" name="words" placeholder="e.g. 1000" @if (!is_null($wizard['max_words'])) value="{{ $wizard['max_words'] }}" @endif>
											@error('words')
												<p class="text-danger">{{ $errors->first('words') }}</p>
											@enderror
										</div> 
									</div> 
								</div>

								<div class="col-md-12 col-sm-12">
									<div class="form-group">	
										<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Language') }}</h6>								
										<select id="language" name="language" class="form-select" data-placeholder="{{ __('Select input language') }}">		
											@foreach ($languages as $language)
												@if (!is_null($wizard['language']))
													<option value="{{ $language->language_code }}" data-img="{{ URL::asset($language->language_flag) }}" @if ($wizard['language'] == $language->language) selected @endif> {{ $language->language }}</option>
												@else
													<option value="{{ $language->language_code }}" data-img="{{ URL::asset($language->language_flag) }}" @if (auth()->user()->default_template_language == $language->language_code) selected @endif> {{ $language->language }}</option>
												@endif
												
											@endforeach									
										</select>
										@error('language')
											<p class="text-danger">{{ $errors->first('language') }}</p>
										@enderror	
									</div>
								</div>
							</div>

						</div>						

						<div class="card-footer border-0 text-center p-0">						
							<div class="w-100 pt-2 pb-2">
								<div class="text-center">
									<button type="button" class="btn btn-primary ripple pl-7 pr-7 fs-11 pt-2 pb-2" id="generate-ideas">{{ __('Generate Ideas') }}</button>
									<button type="button" class="btn btn-primary ripple pl-7 pr-7 fs-11 pt-2 pb-2 hidden" id="generate-outlines">{{ __('Generate Outlines') }}</button>
									<button type="button" class="btn btn-primary ripple pl-7 pr-7 fs-11 pt-2 pb-2 hidden" id="generate-points">{{ __('Generate Talking Points') }}</button>
									<button type="button" class="btn btn-primary ripple pl-7 pr-7 fs-11 pt-2 pb-2 hidden" id="generate-images">{{ __('Generate Image') }}</button>
									<button type="button" class="btn btn-primary ripple pl-7 pr-7 fs-11 pt-2 pb-2 hidden" id="skip-step">{{ __('Skip this step') }}</button>
								</div>
							</div>							
						</div>							
					</div>
				</div>	
				<div id="final-wizard-status" class="hidden">
					<div class="row justify-content-center mt-6">
						<div class="col-sm-12 text-center">
							<i class="fa-solid fa-sharp fa-sparkles mb-7 fs-24 text-primary"></i>
							<h4 class="fs-22 font-weight-bold" id="result-processing">{{ __('Generating the article...') }}</h4>
							<h4 class="fs-22 font-weight-bold hidden" id="result-processed">{{ __('Successfully Generated') }}</h4>
							<h4 class="fs-22 font-weight-bold hidden" id="result-cancelled">{{ __('Generating is stopped') }}</h4>
							<p class="text-muted fs-12">{{ __('You can edit your article in documents once it is generated.') }}</p>
							<div class="w-100 mt-4">
								<div class="text-center">
									<button type="button" class="btn btn-primary ripple pl-7 pr-7 fs-11 pt-2 pb-2" id="new-wizard-large">{{ __('Create New') }}</button>
								</div>
							</div>	
						</div>
					</div>
					
				</div>		
			</div>

			<div class="col-lg-6 col-md-12 col-sm-12" id="wizard-results-wrapper">
				<div class="row">
					<div class="col-sm-12">
						<div class="card border-0 mb-2">
							<div class="card-body">
								<div class="wizard-content-heading">
									<div class="d-flex wizard-nav-text">
										<div class="wizard-step-number mr-3 fs-12 font-weight-bold" id="main-step-number">1</div>
										<div class="wizard-step-title"><span class="font-weight-bold fs-14" id="main-step-name">{{ __('Ideas List') }}</span></div>
									</div>	
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-12 hidden" id="keywords-section">
						<div class="keywords-title text-center">
							<h6 class="font-weight-bold fs-12">{{ __('Choose your Keywords') }}</h6>
						</div>
						<div id="keywords-wrapper"></div>
						<div class="keywords-actions mb-2">
							<a href="#" id="select-all-keywords" class="font-weight-bold fs-12">{{ __('Select All') }}</a>
							<a href="#" id="unselect-all-keywords" class="font-weight-bold fs-12 hidden">{{ __('Unselect All') }}</a>
						</div>
					</div>

					<div class="col-sm-12 hidden" id="ideas-section">
						<div id="ideas-wrapper"></div>
					</div>

					<div class="col-sm-12 hidden" id="outlines-section">
						<div id="outlines-wrapper"></div>
					</div>

					<div class="col-sm-12 hidden" id="points-section">
						<div class="card border-0 mt-3 mb-4 hidden" id="points-section-bg">
							<div class="card-body p-5">
								<div id="points-wrapper">
									<ul id="outlines-list">
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-12 hidden" id="images-section">
						<div id="images-wrapper" class="row mt-4"></div>
					</div>

					<div class="col-sm-12 hidden" id="final-section">
						<div id="final-wrapper"></div>
					</div>

					<div class="card-footer border-0 text-center p-0">						
						<div class="w-100 pt-2 pb-2">
							<div class="text-center">
								<button type="button" class="btn btn-primary ripple pl-7 pr-7 fs-11 pt-2 pb-2 hidden" id="next-step">{{ __('Next Step') }} <i class="fa-solid fa-circle-chevron-right"></i></button>
							</div>
						</div>							
					</div>	
				</div>
			</div>
		</div>
	</div>
</form>
@endsection

@section('js')
<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
<script src="{{URL::asset('plugins/character-count/jquery-simple-txt-counter.min.js')}}"></script>
<script src="{{URL::asset('js/export.js')}}"></script>
<script src="https://unpkg.com/sortablejs-make/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>
<!-- RichText JS -->
<script src="{{URL::asset('plugins/richtext/jquery.richtext.min.js')}}"></script>
<script src="{{URL::asset('plugins/pdf/html2canvas.min.js')}}"></script>
<script src="{{URL::asset('plugins/pdf/jspdf.umd.min.js')}}"></script>
<script type="text/javascript">
	let CURRENT_STEP = 1;	
	let final_title = '';
	let total_outlines = [];
	let total_points = [];
	let outlines_array = [];
	let outlines_selected = [];
	let image_selected = '';
	let final_outlines_selected = [];
	let final_talking_points_selected = [];
	let wizard_id;

	$(function () {

		"use strict";

		let wizard = <?php echo json_encode($wizard); ?>;
		let keywords_index = 1;
		let ideas_index = 1;
		let outlines_index = 1;
		let image_index = 1000;
		wizard_id = wizard.id;
		CURRENT_STEP = wizard.current_step;

		@if (isset($wizard))
            updateStatus(wizard);
        @endif


		$('#outline-results-list').sortable({
			group: 'list',
    		animation: 200,
    		ghostClass: 'ghost',
		});


		$('#outlines-list').sortable({
			group: 'list',
    		animation: 200,
    		ghostClass: 'ghost',
		});


		$("#new-wizard").on('click', newArticleWizard);
		$("#new-wizard-large").on('click', newArticleWizard);


		$(document).ready(function() {
			if (document.getElementById('topic')) {
				let value = document.getElementById('topic-length').innerHTML;
				$('#topic').simpleTxtCounter({
					maxLength: value,
					countElem: '<div class="form-text"></div>',
					lineBreak: false,
				});
			} 

			if (document.getElementById('title')) {
				let value = document.getElementById('title-length').innerHTML;
				$('#title').simpleTxtCounter({
					maxLength: value,
					countElem: '<div class="form-text"></div>',
					lineBreak: false,
				});
			} 

			if (document.getElementById('keywords')) {
				let value = document.getElementById('keywords-length').innerHTML;
				$('#keywords').simpleTxtCounter({
					maxLength: value,
					countElem: '<div class="form-text"></div>',
					lineBreak: false,
				});
			} 

			if (document.getElementById('image')) {
				let value = document.getElementById('image-length').innerHTML;
				$('#image').simpleTxtCounter({
					maxLength: value,
					countElem: '<div class="form-text"></div>',
					lineBreak: false,
				});
			} 
			
		});	


		// Generate Keywords
		$('#generate-keywords').on('click', function(e) {

			e.preventDefault();

			let form = new FormData(document.getElementById('wizard-form'));
			form.append('wizard', wizard_id);

			if(document.getElementById("topic").value == '') {
				toastr.warning('{{ __('Please provide your topic description first') }}');
			} else {
				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'POST',
					url: 'wizard/generate/keywords',
					data: form,
					contentType: false,
					processData: false,
					cache: false,
					beforeSend: function() {
						$('#generate-keywords').prop('disabled', true);
						$('#generate-keywords').html('{{ __('Please Wait...') }}');          
					},			
					success: function (data) {	

						if (data['status'] == 'error') {
							
							Swal.fire('{{ __('Keywords Generation Issue') }}', data['message'], 'warning');
							$('#generate-keywords').prop('disabled', false);
							$('#generate-keywords').html('{{ __('Generate Keywords') }}'); 

						} else {	
							let keywords = data['result'].split(',');

							$('#keywords-section').removeClass('hidden');
							$('#ideas-section').addClass('hidden');
							$('#ideas-wrapper').html('');
							$('#next-step').addClass('hidden');

							for (let i in keywords) {			
								keywords_index = parseInt(i) + parseInt(keywords_index + 777);
								let key = '<div><input type="checkbox" name="checkbox-keyword" id=' + keywords_index + ' class="checkbox-btn" value="' + keywords[i].trim() + '"/><label class="keyword" for=' + keywords_index +' data-id=' + keywords_index + '>' + keywords[i].trim() + '</label></div>';
								$('#keywords-wrapper').append(key); 
							}

							$('#generate-keywords').prop('disabled', false);
							$('#generate-keywords').html('{{ __('Generate Keywords') }}'); 

							if (data['balance']['type'] == 'counted') {
								animateValue("balance-number", data['balance']['old'], data['balance']['current'], 300);
							}

						}
					},
				
					error: function(data) {
						$('#generate-keywords').prop('disabled', false);
						$('#generate-keywords').html('{{ __('Generate Keywords') }}');
						console.log(data)
					}
				});	
			}
		});


		// Generate Ideas
		$('#generate-ideas').on('click', function(e) {

			e.preventDefault();

			let form = new FormData(document.getElementById('wizard-form'));
			form.append('wizard', wizard_id);

			if(document.getElementById("topic").value == '' && document.getElementById("keywords").value == '') {
				toastr.warning('{{ __('Please provide topic description or some keywords first') }}');
			} else {

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'POST',
					url: 'wizard/generate/ideas',
					data: form,
					contentType: false,
					processData: false,
					cache: false,
					beforeSend: function() {
						$('#generate-ideas').prop('disabled', true);
						$('#generate-ideas').html('{{ __('Please Wait...') }}');          
					},			
					success: function (data) {	

						if (data['status'] == 'error') {
							
							Swal.fire('{{ __('Ideas Generation Error') }}', data['message'], 'warning');
							$('#generate-ideas').prop('disabled', false);
							$('#generate-ideas').html('{{ __('Generate Ideas') }}'); 

						} else {					
							let ideas = data['result'].split(',');							

							$('#keywords-section').addClass('hidden');
							$('#ideas-section').removeClass('hidden');

							for (let i in ideas) {			
								ideas_index = parseInt(i) + parseInt(ideas_index + 1);
								let idea = ideas[i].replace(/['"]+/g, '');
								let key = '<div><input type="radio" name="radio-ideas" id=' + ideas_index + ' class="radio-btn" value="' + idea.trim() + '"/><label class="idea" for=' + ideas_index +' data-id=' + ideas_index + ' data-text="' + idea.trim() + '"><i class="fa-regular fa-circle-check idea-icon"></i>' + idea.trim() + '</label></div>';
								$('#ideas-wrapper').prepend(key); 
							}

							$('#generate-ideas').prop('disabled', false);
							$('#generate-ideas').html('{{ __('Generate Ideas') }}'); 

							if (data['balance']['type'] == 'counted') {
								animateValue("balance-number", data['balance']['old'], data['balance']['current'], 300);
							}
						}
					},
					
					error: function(data) {
						$('#generate-ideas').prop('disabled', false);
						$('#generate-ideas').html('{{ __('Generate Ideas') }}'); 
						console.log(data)
					}
				});	
			}
			
		});


		// Generate Outlines
		$('#generate-outlines').on('click', function(e) {

			e.preventDefault();

			let form = new FormData(document.getElementById('wizard-form'));
			form.append('wizard', wizard_id);

			if(document.getElementById("title").value == '') {
				toastr.warning('{{ __('Please provide your article title first') }}');
			} else {

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'POST',
					url: 'wizard/generate/outlines',
					data: form,
					contentType: false,
					processData: false,
					cache: false,
					beforeSend: function() {
						$('#generate-outlines').prop('disabled', true);
						$('#generate-outlines').html('{{ __('Please Wait...') }}');          
					},			
					success: function (data) {	
						if (data['status'] == 'error') {							
							Swal.fire('{{ __('Outlines Generation Error') }}', data['message'], 'warning');	
							$('#generate-outlines').prop('disabled', false);
							$('#generate-outlines').html('{{ __('Generate Outlines') }}'); 

						} else {	
							if (data['result'] !== null) {
								$('#outlines-section').removeClass('hidden');

								total_outlines = data['result'];

								for (let i = 0; i < total_outlines.length; i++) {
									outlines_index = parseInt(i) + parseInt(outlines_index + 100);
									outlines_array[i] = total_outlines[i];

									let outline = '<div><input type="radio" name="radio-outlines" id="' + outlines_index + '" class="outline-btn" /><label for="' + outlines_index +'"><ul class="outline" data-id="' + i + '">';
									
										for (let j = 0; j < total_outlines[i].length; j++) {
										outline += '<li>' + total_outlines[i][j] + '</li>'
									}

									outline += '</ul></label></div>';
									$('#outlines-wrapper').prepend(outline); 							
								}

								$('#generate-outlines').prop('disabled', false);
								$('#generate-outlines').html('{{ __('Generate Outlines') }}'); 
								
								if (data['balance']['type'] == 'counted') {
									animateValue("balance-number", data['balance']['old'], data['balance']['current'], 300);
								}
							
							} else {
								toastr.warning('{{ __('Outline generation is taking longer than expected, please try again') }}');

								$('#generate-outlines').prop('disabled', false);
								$('#generate-outlines').html('{{ __('Generate Outlines') }}'); 
							}							
						}
					},
					
					error: function(data) {
						$('#generate-outlines').prop('disabled', false);
						$('#generate-outlines').html('{{ __('Generate Outlines') }}'); 
						console.log(data)
					}
				});	
			}

		});


		// Generate Talking Points
		$('#generate-points').on('click', function(e) {

			e.preventDefault();

			let form = new FormData(document.getElementById('wizard-form'));
			var outlines = [];
			$("#outline-results-list :input").each(function(index, item) {
				outlines.push(item.value);
			});
			form.append('target_outlines', JSON.stringify(outlines));;
			form.append('wizard', wizard_id);

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: 'wizard/generate/talking-points',
				data: form,
				contentType: false,
				processData: false,
				cache: false,
				beforeSend: function() {
					$('#generate-points').prop('disabled', true);
					$('#generate-points').html('{{ __('Please Wait...') }}');          
				},			
				success: function (data) {	
					if (data['status'] == 'error') {							
						Swal.fire('{{ __('Talking Points Generation Error') }}', data['message'], 'warning');	
						$('#generate-points').prop('disabled', false);
						$('#generate-points').html('{{ __('Generate Talking Points') }}'); 

					} else {			
						if (data['result'] !== null) {
							let talking_points = JSON.parse(data['result']);
							let outlines = JSON.parse(data['input']);
							
							let list = '';
							for (let i in outlines) {							
								list += '<li class="drag-item pl-0 pr-2 pb-2 pt-2 tp-main-li tp-main-li-'+i+'" draggable="true">' +
											'<div class="d-flex w-100 outlines-outer">' +
												'<div class="items-center mr-3 cursor-grab">' +
													'<span class="text-muted"><i class="fa-solid fa-grip-dots-vertical"></i></span>' +
												'</div>' +
												'<div class="w-100 input-box mb-0">' +
													'<div class="form-group tp-section-title">' +
														'<input type="text" class="form-control" placeholder="{{ __('Section Title') }}" value="' + outlines[i] + '">' +
													'</div>' +
												'</div>' +
												'<div class="items-center ml-3">' +
													'<a href="#" class="text-muted remove-section"><i class="fa-solid fa-xmark fs-14"></i></a>' +
												'</div>' +
											'</div>' +
											'<div>' +
											'<ul class="pl-5 pt-4 pb-0 pr-4">'; 
								for (let j in talking_points) {
									if (j == i) {
										for (let k = 0; k < talking_points[j].length; k++) {
											list += '<li class="outline-item pl-0 pr-2">' +
														'<div class="d-flex w-100 outlines-outer">' +
															'<div class="w-100 input-box mb-0">' +
																'<div class="form-group tp-talking-point-'+i+'">' +
																	'<input type="text" class="form-control talking-point-result-input" placeholder="{{ __('Talking Points') }}" value="' + talking_points[j][k] + '">' +
																'</div>' +
															'</div>' +
															'<div class="items-center ml-3">' +
																'<a href="#" class="text-muted remove-section outline-item-close"><i class="fa-solid fa-xmark fs-12"></i></a>' +
															'</div>' +
														'</div>' + 
													'</li>';
										}
									}
								}
								
								list += '<a href="#" class="fs-11 pl-6 text-muted add-new-talking-point" data-id="'+i+'"><i class="fa-solid fa-circle-plus mr-2"></i> {{ __('Add New Talking Point') }}</a>' +
										'</ul>' +										
										'</div></li>';							
							}

							$('#points-section').removeClass('hidden');
							$('#points-section-bg').removeClass('hidden');
							$('#outlines-list').html(list); 

							$('#next-step').removeClass('hidden');

							$('#generate-points').prop('disabled', false);
							$('#generate-points').html('{{ __('Generate Talking Points') }}'); 

							if (data['balance']['type'] == 'counted') {
								animateValue("balance-number", data['balance']['old'], data['balance']['current'], 300);
							}
						} else {
							toastr.warning('{{ __('Talking points generation is taking longer than expected, please try again') }}');

							$('#generate-outlines').prop('disabled', false);
							$('#generate-outlines').html('{{ __('Generate Outlines') }}'); 
						}
					}
				},
				
				error: function(data) {
					$('#generate-points').prop('disabled', false);
					$('#generate-points').html('{{ __('Generate Talking Points') }}'); 
					console.log(data)
				}
			});		
		});


		// Generate Images
		$('#generate-images').on('click', function(e) {

			e.preventDefault();

			let form = new FormData(document.getElementById('wizard-form'));
			form.append('wizard', wizard_id);
			form.append('final_outlines', JSON.stringify(final_outlines_selected));
			form.append('final_talking_points', JSON.stringify(final_talking_points_selected));

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: 'wizard/generate/images',
				data: form,
				contentType: false,
				processData: false,
				cache: false,
				beforeSend: function() {
					$('#generate-images').prop('disabled', true);
					$('#generate-images').html('{{ __('Please Wait...') }}');          
				},			
				success: function (data) {	

					if (data['status'] == 'error') {
						
						Swal.fire('{{ __('Images Generation Error') }}', data['message'], 'warning');
						$('#generate-images').prop('disabled', false);
						$('#generate-images').html('{{ __('Generate Images') }}'); 

					} else {					

						$('#images-section').removeClass('hidden');
						let url = data['result'];
						image_index += 1;
						let image = '<div class="col-md-4 col-sm-6"><input type="radio" name="image-ideas" id=' + image_index + ' class="image-btn"/><label class="image" for=' + image_index +' data-url="' + url + '"><img class="wizard-image" src="' + url + '"></label></div>';

						$('#images-wrapper').prepend(image);

						$('#generate-images').prop('disabled', false);
						$('#generate-images').html('{{ __('Generate Images') }}'); 
					}
				},
				
				error: function(data) {
					$('#generate-images').prop('disabled', false);
					$('#generate-images').html('{{ __('Generate Images') }}'); 
					console.log(data)
				}
			});	
			

		});


		// Next Step
		$('#next-step').on('click', function(e) {

			e.preventDefault();

			if (CURRENT_STEP == 1) {
				CURRENT_STEP = 2;
				$('.general').removeClass('hidden');
				$('.step-one').addClass('hidden');
				$('.step-two').removeClass('hidden');
				$('#step-one-number').html('<i class="fa-solid fa-check"></i>');
				$('#step-one-icon').addClass('current-sign');
				$('#step-two-number').addClass('current-step');
				$('#next-step').addClass('hidden');
				$('#keywords-section').addClass('hidden');
				$('#ideas-section').addClass('hidden');
				$('#generate-ideas').addClass('hidden');
				$('#generate-outlines').removeClass('hidden');
				$('#main-step-number').html('2');
				$('#main-step-name').html('{{ __('Outlines List') }}');
			} else if (CURRENT_STEP == 2) {
				CURRENT_STEP = 3;
				$('.step-two').addClass('hidden');
				$('.step-three').removeClass('hidden');
				$('#step-two-number').html('<i class="fa-solid fa-check"></i>');
				$('#step-two-icon').addClass('current-sign');
				$('#step-three-number').addClass('current-step');
				$('#next-step').addClass('hidden');
				$('#generate-outlines').addClass('hidden');
				$('#generate-points').removeClass('hidden');
				$('#outlines-section').addClass('hidden');
				$('#points-section').removeClass('hidden');
				$('#main-step-number').html('3');
				$('#main-step-name').html('{{ __('Talking Points List') }}');

				let list = '';
				for (let i = 0; i < outlines_selected.length; i++) {
					list += '<li class="drag-item pl-0 pr-2 pb-2 pt-2 mb-2" draggable="true" id="list-'+i+'">' +
								'<div class="d-flex w-100 outlines-outer">' +
									'<div class="items-center mr-3 cursor-grab">' +
										'<span class="text-muted"><i class="fa-solid fa-grip-dots-vertical"></i></span>' +
									'</div>' +
									'<div class="w-100 input-box mb-0">' +
										'<div class="form-group">' +
											'<input type="text" class="form-control" placeholder="{{ __('Section Title') }}" value="' + outlines_selected[i] + '">' +
										'</div>' +
									'</div>' +
									'<div class="items-center ml-3">' +
										'<a href="#" class="text-muted remove-section"><i class="fa-solid fa-xmark fs-14"></i></a>' +
									'</div>'
								'</div>' + 
							'</li>';
				}
				$('#outline-results-list').append(list);
			
			} else if (CURRENT_STEP == 3) {
				CURRENT_STEP = 4;
				$('.step-three').addClass('hidden');
				$('.step-four').removeClass('hidden');
				$('.last-step-extra').addClass('hidden');
				$('#step-three-number').html('<i class="fa-solid fa-check"></i>');
				$('#step-three-icon').addClass('current-sign');
				$('#step-four-number').addClass('current-step');
				$('#next-step').addClass('hidden');
				$('#generate-points').addClass('hidden');
				$('#points-section').addClass('hidden');
				$('#generate-images').removeClass('hidden');
				$('#skip-step').removeClass('hidden');
				$('#main-step-number').html('4');
				$('#main-step-name').html('{{ __('Images List') }}');

				$(".tp-section-title :input").each(function(index, item) {
					final_outlines_selected.push(item.value);
				});

				let talking_points = $('.tp-main-li').length;
				for (let i = 0; i < talking_points; i++) { 
					$(".tp-main-li-" + i).each(function(index, item) {
						let temp = [];
						$(".tp-talking-point-"+i+" :input").each(function(index, item) {
							temp.push(item.value);
						});
						final_talking_points_selected.push(temp);
					});
				}

			} else if (CURRENT_STEP == 4) {
				CURRENT_STEP = 5;
				$('#final-section').removeClass('hidden');
				$('#main-step-number').css('display', 'none');
				$('#main-step-name').html('{{ __('Final Article') }}');
				$('#step-four-number').html('<i class="fa-solid fa-check"></i>');
				$('#generate-images').addClass('hidden');
				$('#skip-step').addClass('hidden');
				$('#next-step').addClass('hidden');
				$('#images-section').addClass('hidden');
				$('#all-wizard-options').addClass('hidden');
				$('#final-wizard-status	').removeClass('hidden');

				processWizard();
			}
		});


		// Skip Step
		$('#skip-step').on('click', function(e) {

			e.preventDefault();

			CURRENT_STEP = 5;
			$('#final-section').removeClass('hidden');
			$('#main-step-number').css('display', 'none');
			$('#main-step-name').html('{{ __('Final Article') }}');
			$('#step-four-number').html('<i class="fa-solid fa-check"></i>');
			$('#generate-images').addClass('hidden');
			$('#skip-step').addClass('hidden');
			$('#next-step').addClass('hidden');
			$('#images-section').addClass('hidden');
			$('#all-wizard-options').addClass('hidden');			
			$('#final-wizard-status	').removeClass('hidden');	
			
			processWizard();
		});


		// Select All Keywords
		$('#select-all-keywords').on('click', function(e) {
			e.preventDefault();
			let checkboxes = document.querySelectorAll('input[name="checkbox-keyword"]');
			let keywords = '';

			for (let i = 0; i < checkboxes.length; i++) {				
				checkboxes[i].checked = true;
				keywords += checkboxes[i].value + ', ';
			}

			document.getElementById("keywords").value = keywords;
			$('#keywords').keypress();

			$(this).addClass('hidden');
			$('#unselect-all-keywords').removeClass('hidden');
		});


		// Unselect All Keywords
		$('#unselect-all-keywords').on('click', function(e) {
			e.preventDefault();
			let checkboxes = document.querySelectorAll('input[name="checkbox-keyword"]');
			for (let i = 0; i < checkboxes.length; i++) {
				checkboxes[i].checked = false;
			}

			document.getElementById("keywords").value = '';

			$(this).addClass('hidden');
			$('#select-all-keywords').removeClass('hidden');
		});


		// Keyword selected
		$(document).on("click",".keyword" , function() { 
			let keywords = document.getElementById("keywords").value;
			let id = $(this).data('id');

			let val = $('#' + id).is(":checked");

			if (!val) {
				if (keywords.trim() == '') {
					document.getElementById("keywords").value = this.innerText;
				} else {
					document.getElementById("keywords").value = keywords + ', ' + this.innerText;
				}
			} else {
				if (keywords == this.innerText) {
					document.getElementById("keywords").value = '';
				} else {
					let temp = keywords.replace(this.innerText + ',', '');
					temp = temp.replace(this.innerText, '');
					document.getElementById("keywords").value = temp.trim();
				}
			}
		});


		// Idea selected
		$(document).on("click",".idea" , function() { 
			let text = $(this).data('text');
			document.getElementById("title").value = text;
			final_title = text;

			$('#next-step').removeClass('hidden');
		});


		// Outline selected
		$(document).on("click",".outline" , function() { 
			let id = $(this).data('id');
			outlines_selected = outlines_array[id];

			$('#next-step').removeClass('hidden');
		});


		// Image selected
		$(document).on("click",".image" , function() { 
			let url = $(this).data('url');
			image_selected = url;

			$('#next-step').removeClass('hidden');
		});


		// Advanced Options toggle
		$('#advanced-settings-toggle').on('click', function (e) {
            e.preventDefault();
            $('#wizard-advanced-wrapper').slideToggle();
            let $plus = $(this).find('span');
            if($plus.text() === '+'){
                $plus.text('-')
            } else {
                $plus.text('+')
            }
        });


		// Add new section
		$('#add-new-section').on("click", function(e) { 
			e.preventDefault();
			let list = '<li class="drag-item pl-0 pr-2 pb-2 pt-2 mb-2" draggable="true">' +
						'<div class="d-flex w-100 outlines-outer">' +
							'<div class="items-center mr-3 cursor-grab">' +
								'<span class="text-muted"><i class="fa-solid fa-grip-dots-vertical"></i></span>' +
							'</div>' +
							'<div class="w-100 input-box mb-0">' +
								'<div class="form-group">' +
									'<input type="text" class="form-control" placeholder="{{ __('Section Title') }}">' +
								'</div>' +
							'</div>' +
							'<div class="items-center ml-3">' +
								'<a href="#" class="text-muted remove-section"><i class="fa-solid fa-xmark"></i></a>' +
							'</div>'
						'</div>' + 
					'</li>';
				
			$('#outline-results-list').append(list);
		});


		// Add new talking point
		$(document).on("click",".add-new-talking-point" , function(e) { 
			e.preventDefault();
			let id = $(this).data('id');
			let list = '<li class="outline-item pl-0 pr-2">' +
						'<div class="d-flex w-100 outlines-outer">' +
							'<div class="w-100 input-box mb-0">' +
								'<div class="form-group tp-talking-point-'+id+'">' +
									'<input type="text" class="form-control talking-point-result-input" placeholder="{{ __('Talking Points') }}">' +
								'</div>' +
							'</div>' +
							'<div class="items-center ml-3">' +
								'<a href="#" class="text-muted remove-section outline-item-close"><i class="fa-solid fa-xmark fs-12"></i></a>' +
							'</div>' +
						'</div>' + 
					'</li>';

			$(this).before(list);
				
		});


		// Remove section
		$(document).on("click", '.remove-section',  function(e) { 
			e.preventDefault();
			$(this).closest('li').remove();
		});

	});

	
	function updateStatus(wizard) {
		
		document.getElementById("title").value = wizard.selected_title;
		document.getElementById("keywords").value = wizard.selected_keywords;

		if (CURRENT_STEP == 2) {
			$('.general').removeClass('hidden');
			$('.step-one').addClass('hidden');
			$('.step-two').removeClass('hidden');
			$('#step-one-number').html('<i class="fa-solid fa-check"></i>');
			$('#step-one-icon').addClass('current-sign');
			$('#step-two-number').addClass('current-step');
			$('#next-step').addClass('hidden');
			$('#keywords-section').addClass('hidden');
			$('#ideas-section').addClass('hidden');
			$('#generate-ideas').addClass('hidden');
			$('#generate-outlines').removeClass('hidden');
			$('#main-step-number').html('2');
			$('#main-step-name').html('{{ __('Outlines List') }}');
		} else if (CURRENT_STEP == 3) {
			$('.general').removeClass('hidden');
			$('.step-one').addClass('hidden');
			$('.step-two').addClass('hidden');
			$('.step-three').removeClass('hidden');
			$('#step-one-number').html('<i class="fa-solid fa-check"></i>');
			$('#step-one-icon').addClass('current-sign');
			$('#step-two-number').addClass('current-step');
			$('#step-two-number').html('<i class="fa-solid fa-check"></i>');
			$('#step-two-icon').addClass('current-sign');
			$('#step-three-number').addClass('current-step');
			$('#next-step').addClass('hidden');
			$('#generate-ideas').addClass('hidden');
			$('#generate-outlines').addClass('hidden');
			$('#generate-points').removeClass('hidden');
			$('#outlines-section').addClass('hidden');
			$('#points-section').removeClass('hidden');
			$('#main-step-number').html('3');
			$('#main-step-name').html('{{ __('Talking Points List') }}');

			let list = '';
			let outlines = JSON.parse(wizard.outlines);
			for (let i = 0; i < outlines.length; i++) {
				list += '<li class="drag-item pl-0 pr-2 pb-2 pt-2 mb-2" draggable="true" id="list-'+i+'">' +
							'<div class="d-flex w-100 outlines-outer">' +
								'<div class="items-center mr-3 cursor-grab">' +
									'<span class="text-muted"><i class="fa-solid fa-grip-dots-vertical"></i></span>' +
								'</div>' +
								'<div class="w-100 input-box mb-0">' +
									'<div class="form-group">' +
										'<input type="text" class="form-control" placeholder="{{ __('Section Title') }}" value="' + outlines[i] + '">' +
									'</div>' +
								'</div>' +
								'<div class="items-center ml-3">' +
									'<a href="#" class="text-muted remove-section"><i class="fa-solid fa-xmark fs-14"></i></a>' +
								'</div>'
							'</div>' + 
						'</li>';
			}
			$('#outline-results-list').append(list);

		} else if (CURRENT_STEP == 4) {
			$('.general').removeClass('hidden');
			$('.step-one').addClass('hidden');
			$('.step-two').addClass('hidden');
			$('.step-three').removeClass('hidden');
			$('.step-four').removeClass('hidden');
			$('.last-step-extra').addClass('hidden');
			$('#step-one-number').html('<i class="fa-solid fa-check"></i>');
			$('#step-one-icon').addClass('current-sign');
			$('#step-two-number').addClass('current-step');
			$('#step-two-number').html('<i class="fa-solid fa-check"></i>');
			$('#step-two-icon').addClass('current-sign');
			$('#step-three-number').addClass('current-step');
			$('#step-four-number').addClass('current-step');
			$('#next-step').addClass('hidden');
			$('#generate-points').addClass('hidden');
			$('#points-section').addClass('hidden');
			$('#generate-images').removeClass('hidden');
			$('#skip-step').removeClass('hidden');
			$('#main-step-number').html('4');
			$('#main-step-name').html('{{ __('Images List') }}');

		} 
	}

	
	function processWizard() {
	
			let form = new FormData(document.getElementById('wizard-form'));
			form.append('wizard', wizard_id);
			form.append('final_outlines', JSON.stringify(final_outlines_selected));
			form.append('final_talking_points', JSON.stringify(final_talking_points_selected));
			form.append('image_url', image_selected);

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: 'wizard/generate/prepare',
				data: form,
				contentType: false,
				processData: false,
				cache: false,
				beforeSend: function() {
					// $('#generate').html('');
					// $('#generate').prop('disabled', true);
					// $('#processing').show().clone().appendTo('#generate'); 
					// $('#processing').hide();          
				},			
				success: function (data) {	

					if (data['status'] == 'error') {
						
						Swal.fire('{{ __('Article Generation Error') }}', data['message'], 'warning');

					} else {		
						$('#final-wrapper').append('<div style="display: flex; justify-content: center;"><img style="width: 50%; border-radius: 20px; margin-bottom:1.5rem;" src="' + image_selected + '"></div>');			
						$('#final-wrapper').append('<div style="display: flex; justify-content: center;"><h2 style="font-weight: 700; margin-bottom:1.5rem; margin-top:0.5rem; font-size:18px;" >' + final_title + '</h2>');			
						
						const eventSource = new EventSource( "/user/wizard/generate/process?wizard=" + data.wizard_id+"&content=" + data.content_id);
						let editor = document.getElementById('final-wrapper');

						eventSource.onmessage = function (e) {
							if ( e.data == '[DONE]' ) {	
								$('#result-processing').addClass('hidden');
								$('#result-processed').removeClass('hidden');
								toastr.success('{{ __('Article Wizard completed and saved in documents successfully') }}');
								eventSource.close();
								editor.innerHTML += '<br>';
							
							} else if (e.data == '[ERROR]') {
								console.log(e.data)
							} else {
								let stream = e.data
								if (stream !== '[DONE]') {
									let temp = '';
									temp += editor.innerHTML;

									let result = temp + stream;
									result = result.replace(/\*\*\*(.*?)\*\*\*/g, '<h3 class="fs-14">$1</h3>');
									result = result.replace(/<h3>/g, '<br><br><h3>');
									result = result.replace(/(<\/h3>\s*)(<br\s*\/?>\s*)+(?=\S)/g, '$1');

									editor.innerHTML = result;
								}
							}
							
						};
						eventSource.onerror = function (e) {
							console.log(e);
							$('#result-processing').addClass('hidden');
							$('#result-processed').addClass('hidden');
							$('#result-cancelled').addClass('hidden');
							eventSource.close();
						};
					}
				},
				
				error: function(data) {
					console.log(data)
				}
			});	
			
		
	}


	function newArticleWizard() {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'POST',
			url: 'wizard/generate/clear',
            success: function (data) {
                location.href = '/user/wizard';
            },
            error: function (data) {
				console.log(data)
            }
        });
    }


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


</script>
@endsection