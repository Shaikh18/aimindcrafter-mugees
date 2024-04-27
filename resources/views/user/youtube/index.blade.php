@extends('layouts.app')
@section('css')
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')

<form id="openai-form" action="" method="post" enctype="multipart/form-data" class="mt-24"> 		
	@csrf
	<div class="row">	
		<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
			<div class="card border-0" id="template-input">
				<div class="card-body p-5 pb-0">

					<div class="row">
						<div class="template-view">
							<div class="template-icon mb-2 d-flex">
								<div>
									<i class="fa-brands fa-youtube rewriter-icon"></i>
								</div>
								<div>
									<h6 class="mt-2 ml-3 fs-16 number-font">{{ __('AI Youtube') }} 										
									</h6>
								</div>									
							</div>								
							<div class="template-info">
								<p class="fs-12 text-muted mb-4">{{ __('Rewrite and improve your content with the help of AI in just a second') }}</p>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<div class="text-left mb-4" id="balance-status">
								<span class="fs-11 text-muted pl-3"><i class="fa-sharp fa-solid fa-bolt-lightning mr-2 text-primary"></i>{{ __('Your Balance is') }} <span class="font-weight-semibold" id="balance-number">@if (auth()->user()->available_words == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_words + auth()->user()->available_words_prepaid) }} {{ __('Words') }} @endif</span></span>
							</div>							
						</div>											

						<div class="col-sm-12">
							<div class="input-box">	
								<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Youtube Video URL') }}  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>									
								<div class="form-group">						    
									<input class="form-control @error('prompt') is-danger @enderror" id="prompt" name="prompt" placeholder="{{ __('Paste your text that you wish to rewrite or improve...') }}" required />
									@error('prompt')
										<p class="text-danger">{{ $errors->first('prompt') }}</p>
									@enderror
								</div> 
							</div> 
						</div>		
						
						<div class="col-sm-12">
							<div class="form-group">	
								<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Language') }}</h6>								
								<select id="language" name="language" class="form-select" data-placeholder="{{ __('Select input language') }}">		
									@foreach ($languages as $language)
										<option value="{{ $language->language_code }}" data-img="{{ URL::asset($language->language_flag) }}" @if (auth()->user()->default_template_language == $language->language_code) selected @endif> {{ $language->language }}</option>
									@endforeach									
								</select>
								@error('language')
									<p class="text-danger">{{ $errors->first('language') }}</p>
								@enderror	
							</div>
						</div>
	
						<div class="col-lg-6 col-md-12 col-sm-12">
							<div id="form-group">
								<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Creativity') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('Increase or decrease the creativity level to get various results') }}"></i></h6>
								<select id="creativity" name="creativity" class="form-select">
									<option value=0>{{ __('Repetitive') }}</option>
									<option value=0.25> {{ __('Deterministic') }}</option>																															
									<option value=0.5 selected> {{ __('Original') }}</option>																															
									<option value=0.75> {{ __('Creative') }}</option>																															
									<option value=1> {{ __('Imaginative') }}</option>																																							
								</select>
							</div>
						</div>

						<div class="col-lg-6 col-md-12 col-sm-12">
							<div id="form-group">
								<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Tone of Voice') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('Set result tone of the text as needed') }}"></i></h6>
								<select id="tone" name="tone" class="form-select" >
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

					</div>						

					<div class="card-footer border-0 text-center p-0">						
						<div class="w-100 pt-2 pb-2">
							<div class="text-center">
								<button type="submit" name="submit" class="btn btn-primary  pl-9 pr-9 fs-11 pt-2 pb-2" id="generate">{{ __('Generate') }}</button>
							</div>
						</div>							
					</div>	
			
				</div>
			</div>			
		</div>

		<div class="col-xl-8 col-lg-6 col-md-12 col-sm-12">
			<div class="card border-0" id="template-output">
				<div class="card-body p-5">
					<div class="row">						
						<div class="col-lg-4 col-md-12 col-sm-12">								
							<div class="input-box mb-2">								
								<div class="form-group">							    
									<input type="text" class="form-control @error('document') is-danger @enderror" id="document" name="document" value="{{ __('New Document') }}">
									@error('document')
										<p class="text-danger">{{ $errors->first('document') }}</p>
									@enderror
								</div> 
							</div> 
						</div>
						<div class="col-lg-4 col-md-12 col-sm-12">
							<div class="form-group">
								<select id="project" name="project" class="form-select" data-placeholder="{{ __('Select Workbook Name') }}">	
									<option value="all"> {{ __('All Workbooks') }}</option>
									@foreach ($workbooks as $workbook)
										<option value="{{ $workbook->name }}" @if (strtolower(auth()->user()->workbook) == strtolower($workbook->name)) selected @endif> {{ ucfirst($workbook->name) }}</option>
									@endforeach											
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 col-sm-12 text-right justify-content-right">
							<div class="d-flex text-right" id="template-buttons-group">	
								<div class="template-action-buttons">
									<div class="btn-group w-100">
										<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" id="export" data-bs-display="static" aria-expanded="false"><i class="fa-solid fa-download table-action-buttons table-action-buttons-big edit-action-button"></i></button>
										<div class="dropdown-menu" aria-labelledby="export" data-popper-placement="bottom-start">								
											<a class="dropdown-item" id="copy-text"><i class="fa-solid fa-copy fs-13 text-muted mr-2"></i> {{ __('Copy Text') }}</a>
											<a class="dropdown-item" id="copy-html"><i class="fa-brands fa-html5 fs-13 text-muted mr-2"></i>{{ __('Copy HTML') }}</a>
											<a class="dropdown-item" id="export-text" onclick="exportTXTEditor();"><i class="fa-solid fa-text-size fs-13 text-muted mr-2"></i>{{ __('Text File') }}</a>								
											<a class="dropdown-item" id="export-word" onclick="exportWordEditor();"><i class="fa-sharp fa-solid fa-file-word fs-13 text-muted mr-2"></i>{{ __('MS Word') }}</a>
											{{-- <a class="dropdown-item" id="export-pdf" onclick="exportPDFEditor();"><i class="fa-sharp fa-solid fa-file-pdf fs-13 text-muted mr-2"></i>{{ __('PDF Document') }}</a> --}}
										</div>
									</div>
								</div>
								<a id="save-button-template" class="template-button" onclick="return saveText(this);" href="#"><i class="fa-solid fa-floppy-disk-pen table-action-buttons table-action-buttons-big delete-action-button" data-tippy-content="{{ __('Save Document') }}"></i></a>				
							</div>
						</div>

					</div>
					<div>						
						<div id="template-textarea">						
							<textarea class="form-control" id="tinymce-editor" rows="25"></textarea>
							<div>
								<p class="text-muted fs-12 total-words-templates-box">{{ __('Total Words') }}: <span id="total-words-templates"></span></p>
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
<script src="{{URL::asset('plugins/tinymce/tinymce.min.js')}}"></script>
<script src="{{URL::asset('js/export.js')}}"></script>
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

		const tinymceOptions = {
			selector: '#tinymce-editor',
			menubar: false,
			statusbar: false,
			toolbar_sticky: true,
			plugins: [
				'advlist', 'autolink', 'lists', 'charmap', 'preview', 'anchor', 'wordcount', 'autosave', 'link', 'image', 'code',
			],
			toolbar: 'AIMain AIOptions | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | forecolor backcolor emoticons | image link code blockquote | undo redo',
			contextmenu: 'customwrite | rewrite summarize improve simplify expand trim fixgrammar tone style translate',
			setup: function ( editor ) {
				const menuItems = {
					'customwrite': {
						icon: 'icon',
						text: '{{ __('Tell AI how to edit or what to generate..') }}',
						onAction: function ( e ) {
							if ( event?.type != 'keydown' || $( event?.srcElement ).attr( 'id' ) != 'custom_prompt' ) {
								return;
							}
							if(editor.selection.getContent().trim().length == 0) {
								toastr.warning('{{ __("Please highlight your target text first") }}');
								return;
							}
							let formData = new FormData();
							formData.append( 'prompt', $(event.srcElement).val() );
							formData.append( 'content', editor.selection.getContent() );
							let language = document.getElementById('language').value;
							formData.append( 'language', language );
							document.querySelector('#loader-line')?.classList?.remove('opacity-on');  
							$.ajax( {
								headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
								type: "post",
								url: "/user/rewriter/custom",
								data: formData,
								contentType: false,
								processData: false,
								success: function ( data ) {

									if (data.status == 'success') {
										editor.selection.setContent( data.message );
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										calculateCredits();  
									} else {
										toastr.warning(data.message);
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
									}
									
								},
								error: function ( data ) {
									toastr.error('There was an unexpected error, please contact support');
									document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
								}
							} );
						},
					},
					'rewrite': {
						icon: 'iconRewrite',
						text: '{{ __('Rewrite') }}',
						onAction: function () {
							if(editor.selection.getContent().trim().length == 0) {
								toastr.warning('{{ __("Please highlight your target text first") }}');
								return;
							}
							let formData = new FormData();
							formData.append( 'prompt', 'Rewrite the following text');
							formData.append( 'content', editor.selection.getContent() );
							let language = document.getElementById('language').value;
							formData.append( 'language', language );
							document.querySelector('#loader-line')?.classList?.remove('opacity-on');
							$.ajax( {
								headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
								type: "post",
								url: "/user/rewriter/custom",
								data: formData,
								contentType: false,
								processData: false,
								success: function ( data ) {

									if (data.status == 'success') {
										editor.selection.setContent( data.message );
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										calculateCredits();  
										let count = tinymce.activeEditor.plugins.wordcount.getCount();
										let words = document.getElementById('total-words-templates');
										words.innerHTML = count;
									} else {
										toastr.warning(data.message);
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
									}
									
								},
								error: function ( data ) {
									toastr.error('There was an unexpected error, please contact support');
									document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
								}
							} );
						}
					},
					'summarize': {
						icon: 'iconSummarize',
						text: '{{ __('Summarize Content') }}',
						onAction: function () {
							if(editor.selection.getContent().trim().length == 0) {
								toastr.warning('{{ __("Please highlight your target text first") }}');
								return;
							}
							let formData = new FormData();
							formData.append( 'prompt', 'Summarize below content professionally' );
							formData.append( 'content', editor.selection.getContent() );
							let language = document.getElementById('language').value;
							formData.append( 'language', language );
							document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
							$.ajax( {
								headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
								type: "post",
								url: "/user/rewriter/custom",
								data: formData,
								contentType: false,
								processData: false,
								success: function ( data ) {

									if (data.status == 'success') {
										editor.selection.setContent( data.message );
										document.querySelector('#loader-line')?.classList?.add('opacity-on');
										calculateCredits();   
										let count = tinymce.activeEditor.plugins.wordcount.getCount();
										let words = document.getElementById('total-words-templates');
										words.innerHTML = count;
									} else {
										toastr.warning(data.message);
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
									}
									
								},
								error: function ( data ) {
									toastr.error('There was an unexpected error, please contact support');
									document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
								}
							} );
						}
					},
					'improve': {
						icon: 'iconImprove',
						text: '{{ __('Improve Writing') }}',
						onAction: function () {
							if(editor.selection.getContent().trim().length == 0) {
								toastr.warning('{{ __("Please highlight your target text first") }}');
								return;
							}
							let formData = new FormData();
							formData.append( 'prompt', 'Rewrite below content professionally' );
							formData.append( 'content', editor.selection.getContent() );
							let language = document.getElementById('language').value;
							formData.append( 'language', language );
							document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
							$.ajax( {
								headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
								type: "post",
								url: "/user/rewriter/custom",
								data: formData,
								contentType: false,
								processData: false,
								success: function ( data ) {

									if (data.status == 'success') {
										editor.selection.setContent( data.message );
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										calculateCredits();  
										let count = tinymce.activeEditor.plugins.wordcount.getCount();
										let words = document.getElementById('total-words-templates');
										words.innerHTML = count;
									} else {
										toastr.warning(data.message);
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
									}
									
								},
								error: function ( data ) {
									toastr.error('There was an unexpected error, please contact support');
									document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
								}
							} );
						}
					},
					'simplify': {
						icon: 'iconSimplify',
						text: '{{ __('Simplify Language') }}',
						onAction: function () {
							if(editor.selection.getContent().trim().length == 0) {
								toastr.warning('{{ __("Please highlight your target text first") }}');
								return;
							}
							let formData = new FormData();
							formData.append( 'prompt', 'Simplify the content below with basic words and make it clear' );
							formData.append( 'content', editor.selection.getContent() );
							let language = document.getElementById('language').value;
							formData.append( 'language', language );
							document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
							$.ajax( {
								headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
								type: "post",
								url: "/user/rewriter/custom",
								data: formData,
								contentType: false,
								processData: false,
								success: function ( data ) {

									if (data.status == 'success') {
										editor.selection.setContent( data.message );
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										calculateCredits(); 
										let count = tinymce.activeEditor.plugins.wordcount.getCount();
										let words = document.getElementById('total-words-templates');
										words.innerHTML = count; 
									} else {
										toastr.warning(data.message);
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
									}
									
								},
								error: function ( data ) {
									toastr.error('There was an unexpected error, please contact support');
									document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
								}
							} );
						}
					},					
					'expand': {
						icon: 'iconExpand',
						text: '{{ __('Expand Content') }}',
						onAction: function () {
							if(editor.selection.getContent().trim().length == 0) {
								toastr.warning('{{ __("Please highlight your target text first") }}');
								return;
							}
							let formData = new FormData();
							formData.append( 'prompt', 'Make below content longer' );
							formData.append( 'content', editor.selection.getContent() );
							let language = document.getElementById('language').value;
							formData.append( 'language', language );
							document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
							$.ajax( {
								headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
								type: "post",
								url: "/user/rewriter/custom",
								data: formData,
								contentType: false,
								processData: false,
								success: function ( data ) {

									if (data.status == 'success') {
										editor.selection.setContent( data.message );
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										calculateCredits();  
										let count = tinymce.activeEditor.plugins.wordcount.getCount();
										let words = document.getElementById('total-words-templates');
										words.innerHTML = count;
									} else {
										toastr.warning(data.message);
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
									}
									
								},
								error: function ( data ) {
									toastr.error('There was an unexpected error, please contact support');
									document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
								}
							} );
						}
					},
					'trim': {
						icon: 'iconTrim',
						text: '{{ __('Shrink Content') }}',
						onAction: function () {
							if(editor.selection.getContent().trim().length == 0) {
								toastr.warning('{{ __("Please highlight your target text first") }}');
								return;
							}
							let formData = new FormData();
							formData.append( 'prompt', 'Make below content shorter' );
							formData.append( 'content', editor.selection.getContent() );
							let language = document.getElementById('language').value;
							formData.append( 'language', language );
							document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
							$.ajax( {
								headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
								type: "post",
								url: "/user/rewriter/custom",
								data: formData,
								contentType: false,
								processData: false,
								success: function ( data ) {

									if (data.status == 'success') {
										editor.selection.setContent( data.message );
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										calculateCredits(); 
										let count = tinymce.activeEditor.plugins.wordcount.getCount();
										let words = document.getElementById('total-words-templates');
										words.innerHTML = count; 
									} else {
										toastr.warning(data.message);
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
									}
									
								},
								error: function ( data ) {
									toastr.error('There was an unexpected error, please contact support');
									document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
								}
							} );
						}
					},	
					'fixgrammar': {
						icon: 'iconFixGrammer',
						text: '{{ __('Check Grammar') }}',
						onAction: function () {
							if(editor.selection.getContent().trim().length == 0) {
								toastr.warning('{{ __("Please highlight your target text first") }}');
								return;
							}
							let formData = new FormData();
							formData.append( 'prompt', 'Fix grammatical mistakes in below content' );
							formData.append( 'content', editor.selection.getContent() );
							let language = document.getElementById('language').value;
							formData.append( 'language', language );
							document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
							$.ajax( {
								headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
								type: "post",
								url: "/user/rewriter/custom",
								data: formData,
								contentType: false,
								processData: false,
								success: function ( data ) {

									if (data.status == 'success') {
										editor.selection.setContent( data.message );
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										calculateCredits();  
										let count = tinymce.activeEditor.plugins.wordcount.getCount();
										let words = document.getElementById('total-words-templates');
										words.innerHTML = count;
									} else {
										toastr.warning(data.message);
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
									}
									
								},
								error: function ( data ) {
									toastr.error('There was an unexpected error, please contact support');
									document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
								}
							} );
						}
					},
					'tone': {
						type: 'nestedmenuitem',
						icon: 'iconTone',
						text: '{{ __('Change Tone') }}',
						getSubmenuItems: function () {
						return [
							{
							type: 'menuitem',
							text: '{{ __('Professional') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in professional tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Casual') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in casual tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Exciting') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in exciting tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Friendly') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in friendly tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Witty') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in witty tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Humorous') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in humorous tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Convincing') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in convincing tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Empathetic') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in empathetic tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Inspiring') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in inspiring tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Supportive') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in supportive tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Trusting') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in trusting tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Playful') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in playful tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Positive') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in positive tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Negative') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in negative tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Engaging') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in engaging tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Worried') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in worried tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Urgent') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in urgent tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Passionate') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in passionate tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Informative') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in informative tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Funny') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in funny tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Sarcastic') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in sarcastic tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Dramatic') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below in dramatic tone' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							}
						];
						}
					},
					'style': {
						type: 'nestedmenuitem',
						icon: 'iconStyle',
						text: '{{ __('Adjust Style') }}',
						getSubmenuItems: function () {
						return [
							{
							type: 'menuitem',
							text: '{{ __('Business') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below using business style of writing' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Legal') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below using legal style of writing' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Journalism') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below using journalist style of writing' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Medical') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below using medical style of writing' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Poetic') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Rewrite the content below using poetic style of writing' );
									formData.append( 'content', editor.selection.getContent() );
									let language = document.getElementById('language').value;
									formData.append( 'language', language );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							}
						];
						}
					},
					'translate': {
						type: 'nestedmenuitem',
						icon: 'iconTranslate',
						text: '{{ __('Translate to') }}',
						getSubmenuItems: function () {
						return [
							{
							type: 'menuitem',
							text: '{{ __('Afrikaans') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Afrikaans' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Arabic') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Arabic' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Armenian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Armenian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();   
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Azerbaijani') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Azerbaijani' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Belarusian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Belarusian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},{
							type: 'menuitem',
							text: '{{ __('Bosnian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Bosnian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Bulgarian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Bulgarian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Catalan') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Catalan' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Chinese') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Chinese' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Croatian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Croatian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Czech') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Czech' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Danish') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Danish' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Dutch') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Dutch' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('English') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to English' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits(); 
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Estonian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Estonian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Finnish') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Finnish' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('French') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to French' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('German') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to German' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Greek') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Greek' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Hebrew') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Hebrew' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Hindi') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Hindi' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Hungarian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Hungarian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Icelandic') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Icelandic' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Indonesian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Indonesian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Italian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Italian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Japanese') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Japanese' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Kazakh') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Kazakh' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Korean') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Korean' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},{
							type: 'menuitem',
							text: '{{ __('Malay') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Malay' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Norwegian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Norwegian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();   
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Persian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Persian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Polish') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Polish' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Portuguese') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Portuguese' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Romanian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Romanian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits(); 
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Russian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Russian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},{
							type: 'menuitem',
							text: '{{ __('Serbian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Serbian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Slovak') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Slovak' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();   
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Slovenian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Slovenian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Spanish') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Spanish' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Swahili') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Swahili' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits(); 
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Tamil') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Tamil' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits(); 
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Thai') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Thai' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Turkish') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Turkish' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Ukrainian') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Ukrainian' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Urdu') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Urdu' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits();  
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Vietnamese') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Vietnamese' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on');
												calculateCredits();   
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count;
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
							{
							type: 'menuitem',
							text: '{{ __('Welsh') }}',
							onAction: function () {
									if(editor.selection.getContent().trim().length == 0) {
										toastr.warning('{{ __("Please highlight your target text first") }}');
										return;
									}
									let formData = new FormData();
									formData.append( 'prompt', 'Translate the text below to Welsh' );
									formData.append( 'content', editor.selection.getContent() );
									document.querySelector('#loader-line')?.classList?.remove('opacity-on'); 
									$.ajax( {
										headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
										type: "post",
										url: "/user/rewriter/custom",
										data: formData,
										contentType: false,
										processData: false,
										success: function ( data ) {

											if (data.status == 'success') {
												editor.selection.setContent( data.message );
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
												calculateCredits(); 
												let count = tinymce.activeEditor.plugins.wordcount.getCount();
												let words = document.getElementById('total-words-templates');
												words.innerHTML = count; 
											} else {
												toastr.warning(data.message);
												document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
											}
											
										},
										error: function ( data ) {
											toastr.error('There was an unexpected error, please contact support');
											document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										}
									} );
								}
							},
						];
						}
					},
				};

				var dialogConfig =  {
					title: '{{ __('AI Assistant') }}',
					body: {
						type: 'panel',
						items: [
						{
							type: 'input',
							name: 'user_prompt',
							placeholder: '{{ __('Tell AI Assistant what to do with entire text...') }}'
						},
						]
					},
					buttons: [
						{
						type: 'cancel',
						name: 'closeButton',
						text: '{{ __('Cancel') }}'
						},
						{
						type: 'submit',
						name: 'submitButton',
						text: '{{ __('Apply') }}',
						primary: true
						}
					],
					onSubmit: function (api) {
						var data = api.getData();

						let formData = new FormData();
						formData.append( 'prompt', data.user_prompt);
						formData.append( 'content', tinymce.activeEditor.getContent( { format: 'text' } ) );
						let language = document.getElementById('language').value;
						formData.append( 'language', language );
						document.querySelector('#loader-line')?.classList?.remove('opacity-on');

						$.ajax( {
								headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
								type: "post",
								url: "/user/rewriter/custom",
								data: formData,
								contentType: false,
								processData: false,
								success: function ( data ) {

									if (data.status == 'success') {
										tinymce.activeEditor.setContent( data.message );
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
										calculateCredits();  
										let count = tinymce.activeEditor.plugins.wordcount.getCount();
										let words = document.getElementById('total-words-templates');
										words.innerHTML = count;
									} else {
										toastr.warning(data.message);
										document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
									}
									
								},
								error: function ( data ) {
									toastr.error('There was an unexpected error, please contact support');
									document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
								}
						
							} );
						api.close();
					}
				};

				editor.ui.registry.addContextToolbar('textselection', {
					predicate: function (node) {
						return !editor.selection.isCollapsed();
					},
					items: 'AIMain AIOptions | bold italic underline | bullist numlist blockquote',
					position: 'selection',
					scope: 'node'
				});

				editor.ui.registry.addIcon( 'icon', '<svg width="24" height="24" focusable="false"><g clip-path="url(#a)"><path fill-rule="evenodd" clip-rule="evenodd" d="M15 6.7a1 1 0 0 0-1.4 0l-9.9 10a1 1 0 0 0 0 1.3l2.1 2.1c.4.4 1 .4 1.4 0l10-9.9c.3-.3.3-1 0-1.4l-2.2-2Zm1.4 2.8-2-2-3 2.7 2.2 2.2 2.8-2.9Z" fill="#007bff"></path><path d="m18.5 7.3-.7-1.5-1.5-.8 1.5-.7.7-1.5.7 1.5 1.5.7-1.5.8-.7 1.5ZM18.5 16.5l-.7-1.6-1.5-.7 1.5-.7.7-1.6.7 1.6 1.5.7-1.5.7-.7 1.6ZM9.7 7.3 9 5.8 7.5 5 9 4.3l.7-1.5.7 1.5L12 5l-1.5.8-.7 1.5Z" fill="#007bff"></path></g><defs><clipPath id="a"><path d="M0 0h24v24H0z"></path></clipPath></defs></svg>' );
				editor.ui.registry.addIcon( 'iconMain', '<svg width="24" height="24" focusable="false"><path fill-rule="evenodd" clip-rule="evenodd" d="M5 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3H5Zm6.8 11.5.5 1.2a68.3 68.3 0 0 0 .7 1.1l.4.1c.3 0 .5 0 .7-.3.2-.1.3-.3.3-.6l-.3-1-2.6-6.2a20.4 20.4 0 0 0-.5-1.3l-.5-.4-.7-.2c-.2 0-.5 0-.6.2-.2 0-.4.2-.5.4l-.3.6-.3.7L5.7 15l-.2.6-.1.4c0 .3 0 .5.3.7l.6.2c.3 0 .5 0 .7-.2l.4-1 .5-1.2h3.9ZM9.8 9l1.5 4h-3l1.5-4Zm5.6-.9v7.6c0 .4 0 .7.2 1l.7.2c.3 0 .6 0 .8-.3l.2-.9V8.1c0-.4 0-.7-.2-.9a1 1 0 0 0-.8-.3c-.2 0-.5.1-.7.3l-.2 1Z" fill="#007bff"></path></svg>' );
				editor.ui.registry.addIcon( 'iconRewrite', '<svg width="16px" height="16px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#007bff" stroke="#007bff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>edit_cover [#1481]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -359.000000)" fill="#007bff"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M384,209.210475 L384,219 L363,219 L363,199.42095 L373.5,199.42095 L373.5,201.378855 L365.1,201.378855 L365.1,217.042095 L381.9,217.042095 L381.9,209.210475 L384,209.210475 Z M370.35,209.51395 L378.7731,201.64513 L380.4048,203.643172 L371.88195,212.147332 L370.35,212.147332 L370.35,209.51395 Z M368.25,214.105237 L372.7818,214.105237 L383.18415,203.64513 L378.8298,199 L368.25,208.687714 L368.25,214.105237 Z" id="edit_cover-[#1481]"> </path> </g> </g> </g> </g></svg>' );
				editor.ui.registry.addIcon( 'iconSummarize', '<svg width="20px" height="20px" viewBox="0 0 24 24" fill="none!important" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect width="24" height="24" fill="white"></rect> <path d="M12 6.90909C10.8999 5.50893 9.20406 4.10877 5.00119 4.00602C4.72513 3.99928 4.5 4.22351 4.5 4.49965C4.5 6.54813 4.5 14.3034 4.5 16.597C4.5 16.8731 4.72515 17.09 5.00114 17.099C9.20405 17.2364 10.8999 19.0998 12 20.5M12 6.90909C13.1001 5.50893 14.7959 4.10877 18.9988 4.00602C19.2749 3.99928 19.5 4.21847 19.5 4.49461C19.5 6.78447 19.5 14.3064 19.5 16.5963C19.5 16.8724 19.2749 17.09 18.9989 17.099C14.796 17.2364 13.1001 19.0998 12 20.5M12 6.90909L12 20.5" stroke="#007bff" stroke-linejoin="round" fill="none"></path> <path d="M19.2353 6H21.5C21.7761 6 22 6.22386 22 6.5V19.539C22 19.9436 21.5233 20.2124 21.1535 20.0481C20.3584 19.6948 19.0315 19.2632 17.2941 19.2632C14.3529 19.2632 12 21 12 21C12 21 9.64706 19.2632 6.70588 19.2632C4.96845 19.2632 3.64156 19.6948 2.84647 20.0481C2.47668 20.2124 2 19.9436 2 19.539V6.5C2 6.22386 2.22386 6 2.5 6H4.76471" stroke="#007bff" stroke-linejoin="round" fill="none"></path> </g></svg>' );
				editor.ui.registry.addIcon( 'iconSimplify', '<svg width="17px" height="17px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="journal" transform="translate(-124 -62)"> <path id="Path_94" data-name="Path 94" d="M130,93a4,4,0,0,0,4-4V63h21V89a4,4,0,0,1-4,4H130a5,5,0,0,1-5-5V82h9" fill="none" stroke="#498efc" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path> <line id="Line_48" data-name="Line 48" x2="17" transform="translate(138 89)" fill="none" stroke="#498efc" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line> <line id="Line_49" data-name="Line 49" x1="9" transform="translate(140 70)" fill="none" stroke="#f1d17c" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></line> <line id="Line_50" data-name="Line 50" x1="9" transform="translate(140 74)" fill="none" stroke="#f1d17c" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></line> <line id="Line_51" data-name="Line 51" x1="9" transform="translate(140 78)" fill="none" stroke="#f1d17c" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></line> <line id="Line_52" data-name="Line 52" x1="9" transform="translate(140 82)" fill="none" stroke="#f1d17c" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></line> </g> </g></svg>' );
				editor.ui.registry.addIcon( 'iconExpand', '<svg style="fill:none;" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_3443_218)"> <path d="M3.1665 12.375H15.8332" stroke="#007bff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M3.1665 4.45833C3.1665 4.24837 3.24991 4.047 3.39838 3.89854C3.54684 3.75007 3.74821 3.66666 3.95817 3.66666H7.12484C7.3348 3.66666 7.53616 3.75007 7.68463 3.89854C7.8331 4.047 7.9165 4.24837 7.9165 4.45833V7.625C7.9165 7.83496 7.8331 8.03632 7.68463 8.18479C7.53616 8.33326 7.3348 8.41666 7.12484 8.41666H3.95817C3.74821 8.41666 3.54684 8.33326 3.39838 8.18479C3.24991 8.03632 3.1665 7.83496 3.1665 7.625V4.45833Z" stroke="#007bff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M3.1665 16.3333H12.6665" stroke="#007bff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_3443_218"> <rect width="19" height="19" fill="white" transform="translate(0 0.5)"/> </clipPath> </defs> </svg>' );
				editor.ui.registry.addIcon( 'iconTrim', '<svg style="fill:none!important;" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_3443_226)"> <path d="M2.25 5.25C2.25 5.84674 2.48705 6.41903 2.90901 6.84099C3.33097 7.26295 3.90326 7.5 4.5 7.5C5.09674 7.5 5.66903 7.26295 6.09099 6.84099C6.51295 6.41903 6.75 5.84674 6.75 5.25C6.75 4.65326 6.51295 4.08097 6.09099 3.65901C5.66903 3.23705 5.09674 3 4.5 3C3.90326 3 3.33097 3.23705 2.90901 3.65901C2.48705 4.08097 2.25 4.65326 2.25 5.25Z" stroke="#007bff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M2.25 12.75C2.25 13.3467 2.48705 13.919 2.90901 14.341C3.33097 14.7629 3.90326 15 4.5 15C5.09674 15 5.66903 14.7629 6.09099 14.341C6.51295 13.919 6.75 13.3467 6.75 12.75C6.75 12.1533 6.51295 11.581 6.09099 11.159C5.66903 10.7371 5.09674 10.5 4.5 10.5C3.90326 10.5 3.33097 10.7371 2.90901 11.159C2.48705 11.581 2.25 12.1533 2.25 12.75Z" stroke="#007bff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M6.4502 6.45L14.2502 14.25" stroke="#007bff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M6.4502 11.55L14.2502 3.75" stroke="#007bff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_3443_226"> <rect width="18" height="18" fill="white"/> </clipPath> </defs> </svg>' );
				editor.ui.registry.addIcon( 'iconImprove', '<svg width="20px" height="20px" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Interface / Check_All_Big"> <path id="Vector" d="M7 12L11.9497 16.9497L22.5572 6.34326M2.0498 12.0503L6.99955 17M17.606 6.39355L12.3027 11.6969" stroke="#007bff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="white"></path> </g> </g></svg>' );
				editor.ui.registry.addIcon( 'iconFixGrammer', '<svg width="20px" height="20px" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M3 12.5L3.84375 9.5M3.84375 9.5L5 5.38889C5 5.38889 5.25 4.5 6 4.5C6.75 4.5 7 5.38889 7 5.38889L8.15625 9.5M3.84375 9.5H8.15625M9 12.5L8.15625 9.5M13 16.8333L15.4615 19.5L21 13.5M12 8.5H15C16.1046 8.5 17 7.60457 17 6.5C17 5.39543 16.1046 4.5 15 4.5H12V8.5ZM12 8.5H16C17.1046 8.5 18 9.39543 18 10.5C18 11.6046 17.1046 12.5 16 12.5H12V8.5Z" stroke="#008bff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="white"></path> </g></svg>' );
				editor.ui.registry.addIcon( 'iconTone', '<svg width="18px" height="18px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#007bff" stroke="#007bff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>edit_text_bar [#1372]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-379.000000, -800.000000)" fill="#007bff"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M327.2,654 L325.1,654 L325.1,646 L327.2,646 L327.2,644 L323,644 L323,656 L327.2,656 L327.2,654 Z M333.5,644 L333.5,646 L341.9,646 L341.9,654 L333.5,654 L333.5,656 L344,656 L344,644 L333.5,644 Z M331.4,658 L333.5,658 L333.5,660 L327.2,660 L327.2,658 L329.3,658 L329.3,642 L327.2,642 L327.2,640 L333.5,640 L333.5,642 L331.4,642 L331.4,658 Z" id="edit_text_bar-[#1372]"> </path> </g> </g> </g> </g></svg>' );
				editor.ui.registry.addIcon( 'iconStyle', '<svg fill="#007bff" width="18px"  height="18px" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg" stroke="#007bff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M517.257 1127.343c72.733 0 148.871 36.586 221.274 107.45 87.455 110.418 114.922 204.135 81.632 278.296-72.733 162.274-412.664 234.897-618.666 259.178 34.609-82.62 75.15-216.88 75.15-394.645 0-97.123 66.47-195.455 157.88-233.689 26.698-11.097 54.494-16.59 82.73-16.59Zm229.404-167.109c54.055 28.895 106.462 65.371 155.133 113.494l13.844 15.6c28.016 35.378 50.649 69.987 70.425 104.155-29.554 26.259-59.878 52.737-90.75 79.545-18.898-35.488-43.069-71.964-72.843-109.319l-4.285-4.834c-48.342-47.683-99.43-83.39-151.727-107.011 26.368-30.653 53.066-61.196 80.203-91.63Zm1046.49-803.133c7.801 7.8 18.129 21.754 16.92 52.187-6.043 155.683-284.338 494.405-740.509 909.266-19.995-32.302-41.969-64.822-67.788-97.453l-22.523-25.27c-49.22-48.671-101.408-88.883-156.012-121.074 350.588-385.855 728.203-734.356 910.254-741.828 30.983-.109 44.497 9.01 59.658 24.172Zm126.678 56.472c2.087-53.615-14.832-99.98-56.142-141.29-34.28-34.279-81.962-51.198-134.588-49.11-304.554 12.414-912.232 683.377-1179.54 996.17-53.616-5.383-106.682 2.088-157.441 23.402-132.61 55.263-225.339 193.038-225.339 334.877 0 268.517-103.935 425.737-104.923 427.275L0 1896.747l110.307-6.153c69.217-3.735 681.29-45.375 810.165-332.46 24.39-54.604 29.225-113.163 15.93-175.239 374.32-321.802 972.11-879.71 983.427-1169.322" fill="#007bff"></path> </g></svg>' );
				editor.ui.registry.addIcon( 'iconTranslate', '<svg fill="#007bff" width="21px" fill="none!important" height="21px" viewBox="0 -64 640 640" xmlns="http://www.w3.org/2000/svg" stroke="#007bff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M152.1 236.2c-3.5-12.1-7.8-33.2-7.8-33.2h-.5s-4.3 21.1-7.8 33.2l-11.1 37.5H163zM616 96H336v320h280c13.3 0 24-10.7 24-24V120c0-13.3-10.7-24-24-24zm-24 120c0 6.6-5.4 12-12 12h-11.4c-6.9 23.6-21.7 47.4-42.7 69.9 8.4 6.4 17.1 12.5 26.1 18 5.5 3.4 7.3 10.5 4.1 16.2l-7.9 13.9c-3.4 5.9-10.9 7.8-16.7 4.3-12.6-7.8-24.5-16.1-35.4-24.9-10.9 8.7-22.7 17.1-35.4 24.9-5.8 3.5-13.3 1.6-16.7-4.3l-7.9-13.9c-3.2-5.6-1.4-12.8 4.2-16.2 9.3-5.7 18-11.7 26.1-18-7.9-8.4-14.9-17-21-25.7-4-5.7-2.2-13.6 3.7-17.1l6.5-3.9 7.3-4.3c5.4-3.2 12.4-1.7 16 3.4 5 7 10.8 14 17.4 20.9 13.5-14.2 23.8-28.9 30-43.2H412c-6.6 0-12-5.4-12-12v-16c0-6.6 5.4-12 12-12h64v-16c0-6.6 5.4-12 12-12h16c6.6 0 12 5.4 12 12v16h64c6.6 0 12 5.4 12 12zM0 120v272c0 13.3 10.7 24 24 24h280V96H24c-13.3 0-24 10.7-24 24zm58.9 216.1L116.4 167c1.7-4.9 6.2-8.1 11.4-8.1h32.5c5.1 0 9.7 3.3 11.4 8.1l57.5 169.1c2.6 7.8-3.1 15.9-11.4 15.9h-22.9a12 12 0 0 1-11.5-8.6l-9.4-31.9h-60.2l-9.1 31.8c-1.5 5.1-6.2 8.7-11.5 8.7H70.3c-8.2 0-14-8.1-11.4-15.9z" fill="#007bff"></path></g></svg>' );

				editor.ui.registry.addButton( 'AIMain', {
					icon: 'iconMain',
					onAction: function () {
						editor.windowManager.open(dialogConfig)
					}
				});

				editor.ui.registry.addMenuButton( 'AIOptions', {
					icon: 'icon',
					fetch: ( callback ) => {
						const items = Object.values( menuItems ).splice( 1 ).map( val => ( { type: 'menuitem', ...val } ) );
						callback( items );
					}
				});

				Object.entries( menuItems ).forEach( ( [ key, val ] ) => {
					editor.ui.registry.addMenuItem( key, val );
				} );

				editor.on( 'ContextMenu', function ( e ) {
					$(".tox-collection").remove();
					setTimeout( () => {
						$( '.tox-collection' ).css( 'width', '240px');
						$('.tox-collection').css('padding', '0px 16px');
						$( $( ".tox-collection__item-label" )[ 0 ] ).html( '<input id="custom_prompt" type="text" class="w-100" placeholder="{{ __('What would you like to do?') }}">' );
						$( $( '.tox-collection__group' )[ 0 ].querySelector( '#custom_label' ) ).remove();
						$( $( '.tox-collection__group' )[ 1 ].querySelector( '#quick_label' ) ).remove();
						$( $( '.tox-collection__group' )[ 0 ] ).prepend( '<p class="tox-custom-label" id="custom_label">{{ __('Custom Action') }}</p>' );
						$( $( '.tox-collection__group' )[ 1 ] ).prepend( '<p class="tox-custom-label mt-2" id="quick_label">{{ __('Quick Actions') }}</p>' );
					}, 0 );
				} );
			}
		};

		if (getCookie('theme') == 'dark') {
			tinymceOptions.skin = 'oxide-dark';
			tinymceOptions.content_css = 'dark';
		}

		tinyMCE.init( tinymceOptions );


		// SUBMIT FORM
		$('#openai-form').on('submit', function(e) {

			e.preventDefault();

			let form = $(this);

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: '/user/rewriter/generate',
				data: form.serialize(),
				beforeSend: function() {					
					$('#generate').prop('disabled', true);
					let btn = document.getElementById('generate');					
					btn.innerHTML = loading;  
					document.querySelector('#loader-line')?.classList?.remove('opacity-on');
				},			
				success: function (data) {	

					if (data['status'] == 'error') {
						$('#generate').prop('disabled', false);
						let btn = document.getElementById('generate');					
						btn.innerHTML = '{{ __('Rewrite') }}';
						toastr.warning(data['message']);
						document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
					} else {					
						const eventSource = new EventSource( "/user/rewriter/process?content_id=" + data.id+"&max_results=" + data.max_results + "&temperature=" + data.temperature + "&language=" + data.language);

						let save = document.getElementById('save-button-template');
						save.setAttribute('target', data['id']);

						eventSource.onmessage = function (e) {
		
							if ( e.data == '[DONE]' ) {	
								eventSource.close();
								$('#generate').prop('disabled', false);
								let btn = document.getElementById('generate');					
								btn.innerHTML = '{{ __('Rewrite') }}'; 
								var $body = $(tinymce.activeEditor.getBody());
								$body.find('p:last').append('<br><br>');
								document.querySelector('#loader-line')?.classList?.add('opacity-on');  
								calculateCredits();  
							
							} else if (e.data == '[ERROR]') {
								console.log(e.data)
								$('#generate').prop('disabled', false);
								let btn = document.getElementById('generate');					
								btn.innerHTML = '{{ __('Rewrite') }}'; 
								document.querySelector('#loader-line')?.classList?.add('opacity-on');   
							} else {

								let stream = e.data
								if ( stream && stream !== '[DONE]') {							
									var $body = $(tinymce.activeEditor.getBody());
									$body.find('p:last').append(stream);
								}

								let count = tinymce.activeEditor.plugins.wordcount.getCount();
								let words = document.getElementById('total-words-templates');
								words.innerHTML = count;

								//editor.scrollTop += 100;
							}
							
						};
						eventSource.onerror = function (e) {
							console.log(e);
							eventSource.close();
							$('#generate').prop('disabled', false);
							let btn = document.getElementById('generate');					
							btn.innerHTML = '{{ __('Rewrite') }}';  
							document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
						};
					}
				},
				
				error: function(data) {
					$('#generate').prop('disabled', false);
					$('#generate').html('{{ __('Rewrite') }}'); 
					document.querySelector('#loader-line')?.classList?.add('opacity-on'); 
					console.log(data)
				}
			});	
			
		});
	}); 


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


	function saveText(event) {

		if(tinymce.activeEditor.getContent().trim().length == 0) {
			toastr.warning('{{ __("Make sure to generate a content before saving") }}');
			return;
		}

		let textarea = tinymce.activeEditor.getContent();
		let workbook = document.getElementById('project').value;
		let language = document.getElementById("language");
		let title = document.getElementById("document").value;
		document.querySelector('#loader-line')?.classList?.remove('opacity-on');  

		if (!event.target) {
			toastr.warning('{{ __('You will need to rewrite an article first before saving your changes') }}');
			document.querySelector('#loader-line')?.classList?.add('opacity-on');  
		} else {
			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: '/user/rewriter/save',
				data: { 'id': event.target, 'text': textarea, 'workbook': workbook, 'language': language.value, 'title': title},
				success: function (data) {					
					if (data['status'] == 'success') {
						toastr.success('{{ __('Successfully saved in the documents') }}');
						document.querySelector('#loader-line')?.classList?.add('opacity-on');  
					} else {						
						toastr.warning('{{ __('There was an issue while saving your changes') }}');
						document.querySelector('#loader-line')?.classList?.add('opacity-on');
					}
				},
				error: function(data) {
					toastr.warning('{{ __('There was an issue while saving your changes') }}');
					document.querySelector('#loader-line')?.classList?.add('opacity-on');
				}
			});

			return false;
		}

	}

	function calculateCredits() {

		let current = document.getElementById('balance-number').innerHTML;

		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'post',
			url: '/user/chat/csv/credits',
			data: 'credit',
			processData: false,
			contentType: false,
			success: function (data) {
				console.log(data)
				if (data['credits'] != 'Unlimited') {
					animateValue("balance-number", parseInt(current.replace(/,/g, '')), data['credits'], 300);
				}
					
			},
			error: function(data) {
				console.log(data)
			}
		})
	}

	$('#copy-html').click(function(e){
		e.preventDefault();
		var content = tinymce.activeEditor.getContent();
		copy_text(content);
	});

	$('#copy-text').click(function(e){
		e.preventDefault();
		var content = tinymce.activeEditor.getContent({format: 'text'});
		copy_text(content);
	});

	function copy_text(text){  
		var node = document.createElement( "textarea" ); 
		node.innerHTML = text;  
		document.body.appendChild( node ); 
		node.select();  

		try{ 
			var success = document.execCommand( "copy" ); 
			if(success){
				toastr.success('{{ __('The content of your article has been copied to your clipboard') }}');
			}
		} 
		catch( e ){ 
			toastr.error('{{ __('Browser not compatible') }}');
		} 
		document.body.removeChild( node );  
	}

	function exportTXTEditor() {
		var content = tinymce.activeEditor.getContent({format: 'text'});
		var link = document.createElement('a');
		var mimeType = 'text/plain';

		link.setAttribute('download', 'document.txt');
		link.setAttribute('href', 'data:' + mimeType  +  ';charset=utf-8,' + encodeURIComponent(content));
		link.click(); 

		toastr.success('{{ __('Text file was created successfully') }}');
	}

	function exportWordEditor(){
		exportWordSmart();
		toastr.success('{{ __('Word document was created successfully') }}');
	}

	function getCookie(cName) {
		const name = cName + "=";
		const cDecoded = decodeURIComponent(document.cookie); //to be careful
		const cArr = cDecoded.split('; ');
		let res;
		cArr.forEach(val => {
			if (val.indexOf(name) === 0) res = val.substring(name.length);
		})
		return res
	}


</script>
@endsection