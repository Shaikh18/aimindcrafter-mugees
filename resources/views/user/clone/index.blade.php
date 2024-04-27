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
					<div class="card-header pt-4 border-0" id="voiceover-character-counter-top">
						<h3 class="card-title"><i class="fa-sharp fa-solid fa-user-music mr-4 text-info"></i>{{ __('Instant Voice Cloning') }} </h3>
					</div>
					<form id="create-voice-form" action="{{ route('user.voiceover.clone.create') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="card-body pt-2 pl-6 pr-6 pb-6" id="">

							<div class="input-box" style="position: relative">	
								<h6>{{ __('New Voice Name') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
								<input type="text" class="form-control @error('name') is-danger @enderror" id="name" name="name" value="{{ old('name') }}" required>
							</div>
							
							<div class="row">
								<div class="col-lg-10 col-md-10 col-sm-10">
									<div class="input-box">
										<h6>{{ __('Re-Train Existing Voice') }} <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('For re-training your existing custom voice clone, select the target voice from the list and upload additional audio samples to further train the selected voice and click the Train Voice button.') }}."></i></h6>
										<select id="train" name="train" class="form-select">	
											<option value="none" selected> {{ __('Select your existing voice clone') }}</option>	
											@foreach ($voices as $voice)																			
												<option value="{{ $voice->voice_id }}"> {{ ucfirst($voice->voice) }}</option>
											@endforeach																					
										</select>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2">
									<div class="dropdown w-100 mt-4">											
										<button class="btn btn-special create-project" type="button" id="delete-voice" data-tippy-content="{{ __('Delete Voice Clone') }}"><i class="fa-solid fa-music-note-slash"></i></button>												
									</div>
								</div>
							</div>

							<div class="input-box" style="position: relative">
								<h6 class="mb-0">{{ __('Audio Samples') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
								<div id="image-drop-box">
									<div class="image-drop-area text-center mt-2 file-drop-border">
										<input type="file" class="main-image-input" name="file[]" id="file" multiple accept=".mp3" required>
										<div class="image-drop-icon">
											<i class="fa-sharp fa-solid fa-user-music fs-25 text-muted"></i>
										</div>
										<p class="text-dark fw-bold mb-0 mt-3">
											{{ __('Drag and drop your audio samples or') }}
											<a href="javascript:void(0);" class="text-primary">{{ __('Browse') }}</a>
										</p>
										<p class="mb-5 file-name fs-12 text-muted">
											<small>{{ __('Approximately 1-2 minutes of clear audio without any background noise') }}</small><br>
											<small>{{ __('Sample quality is more important than quantity') }}</small><br>
											<small>{{ __('Up to 10MB of MP3 format only') }}</small>
										</p>
									</div>
								</div>
							</div>

							<div id="uploaded-samples" class="mb-4"></div>

							<div class="input-box">	
								<h6 class="task-heading mb-2">{{ __('Gender') }}</h6>
								<div id="audio-format" role="radiogroup">
									<div class="radio-control">
										<input type="radio" name="gender" class="input-control" id="male" value="male" checked>
										<label for="male" class="label-control">{{ __('Male') }}</label>
									</div>	
									<div class="radio-control">
										<input type="radio" name="gender" class="input-control" id="female" value="female">
										<label for="female" class="label-control">{{ __('Female') }}</label>
									</div>																							
								</div>
							</div>	

							<div class="input-box">	
								<h6>{{ __('Description') }} <span class="text-muted">({{ __('Optional') }})</span></h6>							
								<textarea class="form-control" name="description" rows="4" id="description" placeholder="{{ __('How would you describe the voice?') }} e.g. &quot;{{ __('An old British male voice with a slight hoarseness in his throat. Perfect for news.') }}&quot;"></textarea>	
							</div>	

							<div class="text-center">
								<button type="button" class="btn btn-primary ripple main-action-button" id="create-voice" style="text-transform: none; min-width: 200px;">{{ __('Create Voice') }}</button>
								<button type="button" class="btn btn-primary ripple main-action-button" id="train-voice" style="text-transform: none; min-width: 200px;">{{ __('Re-Train Voice') }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="col-lg-8 col-md-12 col-xm-12">
				<div class="card border-0">
					<div class="card-header pt-4 border-0" id="voiceover-character-counter-top">
						<h3 class="card-title"><i class="fa-sharp fa-solid fa-waveform-lines mr-4 text-info"></i>{{ __('AI Text to Speech') }} </h3>
						<span class="fs-11 text-muted pl-3" id="voiceover-character-counter"><i class="fa-sharp fa-solid fa-bolt-lightning mr-2 text-primary"></i>{{ __('Your Balance is') }} <span class="font-weight-semibold" id="balance-number">@if (auth()->user()->available_chars == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_chars + auth()->user()->available_chars_prepaid) }} {{ __('Characters') }} @endif</span></span>
					</div>
					<div class="card-body pt-2 pl-6 pr-6 pb-4" id="tts-body-minify">
					
							<form id="synthesize-text-form" action="{{ route('user.voiceover.clone.synthesize') }}" listen="{{ route('user.voiceover.clone.listen') }}" method="POST" enctype="multipart/form-data">
								@csrf							

								<div class="row mb-4" id="tts-awselect">
									<div class="col-lg-6 col-md-12 col-sm-12 mb-4">
										<div class="input-box mb-0" id="textarea-box">
											<input type="text" class="form-control" name="title" id="title"  value="{{ __('New Audio') }}">
										</div>
									</div>

									<div class="col-lg-6 col-md-12 col-sm-12 mb-4">
										<div class="form-group">
											<select id="project" name="project" class="form-select" data-placeholder="{{ __('Select Workbook Name') }}">	
												<option value="all"> {{ __('All Workbooks') }}</option>
												@foreach ($projects as $project)
													<option value="{{ $project->name }}" @if (strtolower(auth()->user()->project) == strtolower($project->name)) selected @endif> {{ ucfirst($project->name) }}</option>
												@endforeach											
											</select>
										</div>								
									</div>

									<div class="col-lg-6 col-md-12 col-sm-12">
										<div class="form-group">									
											<select id="languages" name="language" class="form-select" data-placeholder="{{ __('Pick Your Language') }}:">	
												<option value="ar-AE" data-img="{{ URL::asset('/img/flags/ae.svg') }}" @if (auth()->user()->default_voiceover_language == 'ar-AE') selected @endif> {{ __('Arabic') }}</option>										
												<option value="bg-BG" data-img="{{ URL::asset('/img/flags/bg.svg') }}" @if (auth()->user()->default_voiceover_language == 'bg-BG') selected @endif> {{ __('Bulgarian (Bulgaria)') }}</option>										
												<option value="cmn-CN" data-img="{{ URL::asset('/img/flags/cn.svg') }}" @if (auth()->user()->default_voiceover_language == 'cmn-CN') selected @endif> {{ __('Chinese (Mandarin)') }}</option>										
												<option value="hr-HR" data-img="{{ URL::asset('/img/flags/hr.svg') }}" @if (auth()->user()->default_voiceover_language == 'hr-HR') selected @endif> {{ __('Croatian (Croatia)') }}</option>										
												<option value="cs-CZ" data-img="{{ URL::asset('/img/flags/cz.svg') }}" @if (auth()->user()->default_voiceover_language == 'cs-CZ') selected @endif> {{ __('Czech (Czech Republic)') }}</option>										
												<option value="da-DK" data-img="{{ URL::asset('/img/flags/dk.svg') }}" @if (auth()->user()->default_voiceover_language == 'da-DK') selected @endif> {{ __('Danish (Denmark)') }}</option>										
												<option value="nl-NL" data-img="{{ URL::asset('/img/flags/nl.svg') }}" @if (auth()->user()->default_voiceover_language == 'nl-NL') selected @endif> {{ __('Dutch (Netherlands)') }}</option>										
												<option value="en-US" data-img="{{ URL::asset('/img/flags/us.svg') }}" @if (auth()->user()->default_voiceover_language == 'en-US') selected @endif> {{ __('English (USA)') }}</option>										
												<option value="fil-PH" data-img="{{ URL::asset('/img/flags/ph.svg') }}" @if (auth()->user()->default_voiceover_language == 'fil-PH') selected @endif> {{ __('Filipino (Philippines)') }}</option>										
												<option value="fi-FI" data-img="{{ URL::asset('/img/flags/fi.svg') }}" @if (auth()->user()->default_voiceover_language == 'fi-FI') selected @endif> {{ __('Finnish (Finland)') }}</option>										
												<option value="fr-FR" data-img="{{ URL::asset('/img/flags/fr.svg') }}" @if (auth()->user()->default_voiceover_language == 'fr-FR') selected @endif> {{ __('French (France)') }}</option>										
												<option value="de-DE" data-img="{{ URL::asset('/img/flags/de.svg') }}" @if (auth()->user()->default_voiceover_language == 'de-DE') selected @endif> {{ __('German (Germany)') }}</option>										
												<option value="el-GR" data-img="{{ URL::asset('/img/flags/gr.svg') }}" @if (auth()->user()->default_voiceover_language == 'el-GR') selected @endif> {{ __('Greek (Greece)') }}</option>										
												<option value="hi-IN" data-img="{{ URL::asset('/img/flags/in.svg') }}" @if (auth()->user()->default_voiceover_language == 'hi-IN') selected @endif> {{ __('Hindi (India)') }}</option>										
												<option value="id-ID" data-img="{{ URL::asset('/img/flags/id.svg') }}" @if (auth()->user()->default_voiceover_language == 'id-ID') selected @endif> {{ __('Indonesian (Indonesia)') }}</option>										
												<option value="it-IT" data-img="{{ URL::asset('/img/flags/it.svg') }}" @if (auth()->user()->default_voiceover_language == 'it-IT') selected @endif> {{ __('Italian (Italy)') }}</option>										
												<option value="ja-JP" data-img="{{ URL::asset('/img/flags/jp.svg') }}" @if (auth()->user()->default_voiceover_language == 'ja-JP') selected @endif> {{ __('Japanese (Japan)') }}</option>										
												<option value="ko-KR" data-img="{{ URL::asset('/img/flags/kr.svg') }}" @if (auth()->user()->default_voiceover_language == 'ko-KR') selected @endif> {{ __('Korean (South Korea)') }}</option>										
												<option value="ms-MY" data-img="{{ URL::asset('/img/flags/my.svg') }}" @if (auth()->user()->default_voiceover_language == 'ms-MY') selected @endif> {{ __('Malay (Malaysia)') }}</option>										
												<option value="pl-PL" data-img="{{ URL::asset('/img/flags/pl.svg') }}" @if (auth()->user()->default_voiceover_language == 'pl-PL') selected @endif> {{ __('Polish (Poland)') }}</option>										
												<option value="pt-PT" data-img="{{ URL::asset('/img/flags/pt.svg') }}" @if (auth()->user()->default_voiceover_language == 'pt-PT') selected @endif> {{ __('Portuguese (Portugal)') }}</option>										
												<option value="ro-RO" data-img="{{ URL::asset('/img/flags/ro.svg') }}" @if (auth()->user()->default_voiceover_language == 'ro-RO') selected @endif> {{ __('Romanian (Romania)') }}</option>										
												<option value="ru-RU" data-img="{{ URL::asset('/img/flags/ru.svg') }}" @if (auth()->user()->default_voiceover_language == 'ru-RU') selected @endif> {{ __('Russian (Russia)') }}</option>										
												<option value="sk-SK" data-img="{{ URL::asset('/img/flags/sk.svg') }}" @if (auth()->user()->default_voiceover_language == 'sk-SK') selected @endif> {{ __('Slovak (Slovakia)') }}</option>										
												<option value="es-ES" data-img="{{ URL::asset('/img/flags/es.svg') }}" @if (auth()->user()->default_voiceover_language == 'es-ES') selected @endif> {{ __('Spanish (Spain)') }}</option>										
												<option value="sv-SE" data-img="{{ URL::asset('/img/flags/se.svg') }}" @if (auth()->user()->default_voiceover_language == 'sv-SE') selected @endif> {{ __('Swedish (Sweden)') }}</option>										
												<option value="ta-MY" data-img="{{ URL::asset('/img/flags/my.svg') }}" @if (auth()->user()->default_voiceover_language == 'ta-MY') selected @endif> {{ __('Tamil (Malaysia)') }}</option>										
												<option value="tr-TR" data-img="{{ URL::asset('/img/flags/tr.svg') }}" @if (auth()->user()->default_voiceover_language == 'tr-TR') selected @endif> {{ __('Turkish (Turkey)') }}</option>										
												<option value="uk-UA" data-img="{{ URL::asset('/img/flags/ua.svg') }}" @if (auth()->user()->default_voiceover_language == 'uk-UA') selected @endif> {{ __('Ukrainian (Ukraine)') }}</option>																	
											</select>
										</div>
									</div>

									<div class="col-lg-6 col-md-12 col-sm-12">
										<div class="form-group">								
											<select id="voices" name="voice" class="form-select" data-placeholder="{{ __('Choose Your Voice') }}:" data-callback="voice_select">
												@foreach ($voices as $voice)
													<option value="{{ $voice->voice_id }}" 
														id="{{ $voice->voice_id }}"
														data-img="@if ($voice->vendor == 'elevenlabs') {{ $voice->avatar_url }} @else {{ URL::asset($voice->avatar_url) }} @endif"
														data-id="{{ $voice->voice_id }}" 
														data-type="{{ __('Custom Voice') }}"
														data-gender="{{ ucfirst(__($voice->gender)) }}"	
														data-voice="{{ $voice->voice }}"																							
														> 
														{{ ucfirst($voice->voice) }} 														
													</option>
												@endforeach									
											</select>
										</div>	
									</div>							
								</div>					

								<div class="row">
									<div class="col-md-12">
										<div id="textarea-outer-box" class="p-2">
											<label class="input-label">
												<span class="input-label-content input-label-main">{{ __('Text to Speech') }}</span>
											</label>
											<div id="textarea-container">
												<div id="textarea-row-box">
													<div class="textarea-row pl-2 pr-0" id="maintextarea">
														<div class="textarea-voice pl-0 mr-2">
															<div class="ml-1 mt-1 voicee"><img src="{{ URL::asset('img/files/avatar.webp') }}" id="ZZZOOOVVVIMG"  data-tippy-content=""></div>
														</div>
														<div class="textarea-text ml-0 mr-3">
															<textarea class="form-control textarea" name="textarea[]" id="ZZZOOOVVVZ" data-voice="" onkeyup="countCharacters();" onmousedown="mouseDown(this);" rows="1" placeholder="{{ __('Enter your text here to synthesize') }}..." maxlength="5000"></textarea>
														</div>
														<div class="textarea-actions">
															<div class="textarea-buttons">
																<button class="btn buttons addPause mr-0" id="ZZZOOOVVVP" onclick="addPause(this); return false;" data-tippy-content="{{ __('Add Pause After Text') }}"><i class="fa-regular fa-hourglass-clock"></i></button>
																<button type="button" class="btn buttons deleteText mr-0" id="ZZZOOOVVVDEL" onclick="deleteRow(this); return false;" data-tippy-content="{{ __('Delete This Text Block') }}"><i class="fa-solid fa-trash"></i></button>
															</div>
														</div>
													</div>
												</div>
												<div class="textarea-add text-center mt-2">
													<button class="btn" id="addTextRow" data-tippy-content="{{ __('Add New Text Block') }}"><i class="fa-solid fa-layer-plus"></i></button>
												</div>
											</div>
											<div id="textarea-settings">								
												<div class="character-counter">
													<span id="total-characters">0 {{ __('characters') }}, 1 {{ __('line') }}</span>
												</div>
		
												<div class="clear-button">
													<button type="button" id="delete-all-lines">{{ __('Delete All Lines') }}</button>
													<button type="button" id="clear-text">{{ __('Clear Text') }}</button>
												</div>
											</div>
										</div>
									</div>
								</div>			
								
								<div class="mt-5 text-center" id="waveform-box">      
									<div class="row">
										<div class="col-sm-12">
											<div id="waveform">
												<audio style="display:none" id="media-element" src="" type=""></audio>
											</div> 
											<div id="wave-timeline"></div>
										</div>
										<div class="col-sm-12">
											<div id="controls" class="mt-4 mb-3">
												<button id="backwardBtn" class="result-play result-play-sm mr-2"><i class="fa fa-backward"></i></button>
												<button id="playBtn" class="result-play result-play-sm mr-2"><i class="fa fa-play"></i></button>
												<button id="stopBtn" class="result-play result-play-sm mr-2"><i class="fa fa-stop"></i></button>
												<button id="forwardBtn" class="result-play result-play-sm mr-2"><i class="fa fa-forward"></i></button>							
												<a id="downloadBtn" class="result-play result-play-sm" href="" download><i class="fa fa-download"></i></a>						
											</div> 
										</div>
									</div>                                            
								</div>

								<div class="card-footer border-0 text-center mt-3">
									<button type="button" class="btn btn-primary ripple main-action-button mr-2" id="listen-text" style="text-transform: none; min-width: 160px;">{{ __('Listen') }}</button>
									<button type="button" class="btn btn-primary ripple main-action-button" id="synthesize-text" style="text-transform: none; min-width: 160px;">{{ __('Synthesize') }}</button>								
								</div>							

							</form>
						
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-4" id="results-header">
			<div class="col-lg-12 col-md-12 col-xm-12">
				<div class="card border-0">
					<div class="card-header">
						<h3 class="card-title">{{ __('AI Text to Speech Results') }} </h3>
					</div>
					<div class="card-body pt-2">
						<!-- SET DATATABLE -->
						<table id='resultTable' class='table' width='100%'>
								<thead>
									<tr>
										<th width="3%"></th>
										<th width="10%">{{ __('Created On') }}</th> 
										<th width="8%">{{ __('Language') }}</th>
										<th width="7%">{{ __('Voice') }}</th>
										<th width="7%">{{ __('Gender') }}</th>		
										<th width="4%"><i class="fa fa-music fs-14"></i></th>							
										<th width="4%"><i class="fa fa-cloud-download fs-14"></i></th>								
										<th width="5%">{{ __('Format') }}</th>																	           	
										<th width="5%">{{ __('Chars') }}</th>																	           	
										<th width="9%">{{ __('Workbook') }}</th>     						           	
										<th width="5%">{{ __('Actions') }}</th>
									</tr>
								</thead>
						</table> <!-- END SET DATATABLE -->
					</div>
				</div>
			</div>
		</div>
	@endif
</div>

<!-- DELETE VOICE MODAL -->
<div class="modal fade" id="deleteVoiceModal" tabindex="-1" role="dialog" aria-labelledby="projectModalLabel" aria-hidden="true" data-bs-keyboard="false">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header mb-1">
				<h4 class="modal-title" id="myModalLabel"><i class="fa-solid fa-music-note-slash"></i> {{ __('Delete Voice Clone') }}</h4>
				<button type="button" class="btn-close fs-12" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body pb-0 pl-6 pr-6">       
				<p class="text-danger mb-3 fs-12">{{ __('Warning! This will permanently delete this custom voice clone') }}</p> 
				<div class="input-box">	
					<select id="del-voice" class="form-select">
						<option value="none" selected> {{ __('Select voice clone to delete') }}</option>				
						@foreach ($voices as $voice)																			
							<option value="{{ $voice->voice_id }}"> {{ ucfirst($voice->voice) }}</option>
						@endforeach	
					</select>
				</div>
			</div>
			<div class="modal-footer pr-6 pb-3 modal-footer-awselect">
				<button type="button" class="btn btn-cancel mb-4" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
				<button type="submit" class="btn btn-confirm mb-4" id="del-voice-button">{{ __('Delete') }}</button>
			</div>						
		</div>
	</div>
</div>
<!-- END DELETE VOICE MODAL -->
@endsection
@section('js')
	<!-- Green Audio Players JS -->
	<script src="{{ URL::asset('plugins/audio-player/green-audio-player.js') }}"></script>
	<script src="{{ URL::asset('js/audio-player.js') }}"></script>
	<script src="{{ URL::asset('js/wavesurfer.min.js') }}"></script>
	<script src="{{ URL::asset('plugins/wavesurfer/wavesurfer.cursor.min.js') }}"></script>
	<script src="{{ URL::asset('plugins/wavesurfer/wavesurfer.timeline.min.js') }}"></script>
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script src="{{URL::asset('js/dashboard-clone.js')}}"></script>
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
			let samples = [];

			$("input[type=file]").on('change',function(){

				for (let i = 0; i < this.files.length; i++) {
					samples.push(this.files[i]);
					let url = URL.createObjectURL(this.files[i]);
					let id = Math.floor(Math.random() * 20)
					let newRow = '<div class="sample-line">' +
									'<div class="fs-12 text-muted mb-2" style="position:relative">'+
										'<span>'+ this.files[i].name.slice(0, 50) +'...</span><button type="button" id="'+ id +'"class="result-play sample-audio-player p-0" onclick="resultPlay(this)" src="' + url + '" type="audio/mpeg"><i class="fa fa-play table-action-buttons view-action-button"></i></button>'+
									'</div>' +								
								'</div>';

					$("#uploaded-samples").append(newRow);
				}

			});
			
			function format(d) {
				// `d` is the original data object for the row
				return '<div class="slider">'+
							'<table class="details-table">'+
								'<tr>'+
									'<td class="details-title" width="10%">Title:</td>'+
									'<td>'+ ((d.title == null) ? '' : d.title) +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-title" width="10%">Text Clean:</td>'+
									'<td>'+ d.text +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-title" width="10%">Text Raw:</td>'+
									'<td>'+ d.text_raw +'</td>'+
								'</tr>'+
								'<tr>'+
									'<td class="details-result" width="10%">Synthesized Result:</td>'+
									'<td><audio controls preload="none">' +
										'<source src="'+ d.result +'" type="'+ d.audio_type +'">' +
									'</audio></td>'+
								'</tr>'+
							'</table>'+
						'</div>';
			}


			var table = $('#resultTable').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				colReorder: true,
				language: {
					"emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('No synthesized text results yet') }}</div>",
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
				ajax: "{{ route('user.voiceover.clone') }}",
				columns: [{
						"className":      'details-control',
						"orderable":      false,
						"searchable":     false,
						"data":           null,
						"defaultContent": ''
					},
					{
						data: 'created-on',
						name: 'created-on',
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
						data: 'result_ext',
						name: 'result_ext',
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
						data: 'project',
						name: 'project',
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
			

			$('#resultTable tbody').on('click', 'td.details-control', function () {
				var tr = $(this).closest('tr');
				var row = table.row( tr );
		
				if ( row.child.isShown() ) {
					// This row is already open - close it
					$('div.slider', row.child()).slideUp( function () {
						row.child.hide();
						tr.removeClass('shown');
					} );
				}
				else {
					// Open this row
					row.child( format(row.data()), 'no-padding' ).show();
					tr.addClass('shown');
		
					$('div.slider', row.child()).slideDown();
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
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: '/user/text-to-speech/clone/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									toastr.success('{{ __('Synthesize result has been successfully deleted') }}');
									$("#resultTable").DataTable().ajax.reload();								
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


			/*************************************************
			 *  Process File Synthesize Mode
			 *************************************************/
			$('#synthesize-text').on('click',function(e) {

				"use strict";

				e.preventDefault()

				let map = new Map();
				let textarea = document.getElementsByTagName("textarea");
				let full_textarea = textarea.length;
				let full_text = '';

				if (textarea.length == 1) {
					let value = document.getElementById('ZZZOOOVVVZ').value;
					let voice = document.getElementById('ZZZOOOVVVZ').getAttribute('data-voice');

					if (value.length == 0) {
						Swal.fire('{{ __('Missing Input Text') }}', '{{ __('Enter your text that you want to synthezise before processing') }}', 'warning');
					} else if (value.length > text_length_limit) { 
						Swal.fire('{{ __('Text to Speech Notification') }}', '{{ __('Text exceeded allowed length, maximum allowed text length is ') }}' + text_length_limit + '{{__(' characters. Please decrease the overall text length.')}}', 'warning'); 
					} else {
						map.set(voice, value);
						startSynthesizeMode(1, map, value);
					}

				} else {

					for (let i = 0; i < textarea.length; i++) {

						let value = textarea[i].value;
						let voice = textarea[i].getAttribute('data-voice');
						let distinct = generateID(3);
						
						if (value != '') {
							map.set(voice +'___'+ distinct, value);
							full_text +=value;
						} else {
							full_textarea--;
						}
					}

					if (full_text.length == 0) {
						Swal.fire('{{ __('Missing Input Text') }}', '{{ __('Enter your text that you want to synthezise before processing') }}', 'warning');
					} else if (full_text.length > text_length_limit) { 
						Swal.fire('{{ __('Text to Speech Notification') }}', '{{ __('Text exceeded allowed length, maximum allowed total text length is ') }}' + text_length_limit + '{{__(' characters. Please decrease the text length.')}}', 'warning'); 
					} else {
						startSynthesizeMode(full_textarea, map, full_text);
					}    
				}
			});


			/*************************************************
			 *  Process Live Synthesize Listen Mode
			 *************************************************/
			$('#listen-text').on('click', function(e) {

				"use strict";

				e.preventDefault()

				let map = new Map();
				let textarea = document.getElementsByTagName("textarea");
				let full_textarea = textarea.length;
				let full_text = '';

				if (textarea.length == 1) {
					let value = document.getElementById('ZZZOOOVVVZ').value;
					let voice = document.getElementById('ZZZOOOVVVZ').getAttribute('data-voice');

					if (value.length == 0) {
						Swal.fire('{{ __('Missing Input Text') }}', '{{ __('Enter your text that you want to synthezise before processing') }}', 'warning');
					} else if (value.length > text_length_limit) { 
						Swal.fire('{{ __('Text to Speech Notification') }}', '{{ __('Text exceeded allowed length, maximum allowed text length is ') }}' + text_length_limit + '{{__(' characters. Please decrease the text length.')}}', 'warning'); 
					} else {
						map.set(voice, value);
						startListenMode(1, map, value);
					}

				} else {

					for (let i = 0; i < textarea.length; i++) {

						let value = textarea[i].value;
						let voice = textarea[i].getAttribute('data-voice');
						let distinct = generateID(3);
						
						if (value != '') {
							map.set(voice +'___'+ distinct, value);
							full_text +=value;
						} else {
							full_textarea--;
						}
					}

					if (full_text.length == 0) {
						Swal.fire('{{ __('Missing Input Text') }}', '{{ __('Enter your text that you want to synthezise before processing') }}', 'warning');
					} else if (full_text.length > text_length_limit) { 
						Swal.fire('{{ __('Text to Speech Notification') }}', '{{ __('Text exceeded allowed length, maximum allowed total text length is ') }}' + text_length_limit + '{{__(' characters. Please decrease the overall text length.')}}', 'warning'); 
					} else {
						startListenMode(full_textarea, map, full_text);
					}    
				}
			});


			$('#create-voice').on('click',function(e) {

				if(document.getElementById("name").value.trim().length == 0) {
					toastr.warning('{{ __('Enter a voice name first') }}');
    				$('#create-voice').prop('disabled', false);
					let btn = document.getElementById('create-voice');					
					btn.innerHTML = '{{ __('Create Voice') }}';
					document.querySelector('#loader-line')?.classList?.remove('hidden');
					return;
				}

				if(samples.length == 0) {
					toastr.warning('{{ __('Upload your audio samples first') }}');
    				$('#create-voice').prop('disabled', false);
					let btn = document.getElementById('create-voice');					
					btn.innerHTML = '{{ __('Create Voice') }}';
					document.querySelector('#loader-line')?.classList?.add('hidden'); 
					return;
				}

				const form = document.getElementById("create-voice-form");
				let data = new FormData(form);
				
				for (var x = 0; x < samples.length; x++) {
					data.append("samples[]", samples[x]);
				}

				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: "POST",
					url: 'clone/create',
					data: data,
					processData: false,
					contentType: false,
					beforeSend: function() {
						$('#create-voice').prop('disabled', true);
						let btn = document.getElementById('create-voice');					
						btn.innerHTML = loading;  
						document.querySelector('#loader-line')?.classList?.remove('hidden');  
						$('#uploaded-samples').html('');     
					},
					complete: function() {
						$('#create-voice').prop('disabled', false);
						let btn = document.getElementById('create-voice');					
						btn.innerHTML = '{{ __('Create Voice') }}';
						document.querySelector('#loader-line')?.classList?.add('hidden');               
					},
					success: function(data) {
						if (data['status'] == 200) {
							toastr.success('{{ __('New Custom Voice successfully created') }}');
							location.reload();
						} else if(data['status'] == 400) {
							toastr.warning(data['message']);
						}
						
						document.getElementById("name").value = '';
						samples = [];
					},
					error: function(data) {
						if (data.responseJSON['error']) {
							Swal.fire('Text to Speech Notification', data.responseJSON['error'], 'warning');
						}

						$('#create-voice').prop('disabled', false);
						let btn = document.getElementById('create-voice');					
						btn.innerHTML = '{{ __('Create Voice') }}';
						document.querySelector('#loader-line')?.classList?.add('hidden');
						document.getElementById("name").value = '';
						samples = [];          
					}
				}).done(function(data) {})
			});


			$('#train-voice').on('click',function(e) {

				let train = document.getElementById("train").value;

				if (train == 'none') {
					toastr.warning('{{ __('You need to select a voice clone to re-train first') }}');
				} else {

					if(samples.length == 0) {
						toastr.warning('{{ __('Upload your audio samples for re-training first') }}');
						$('#train-voice').prop('disabled', false);
						let btn = document.getElementById('train-voice');					
						btn.innerHTML = '{{ __('Re-Train Voice') }}';
						document.querySelector('#loader-line')?.classList?.add('hidden'); 
						return;
					}

					const form = document.getElementById("create-voice-form");
					let data = new FormData(form);

					for (var x = 0; x < samples.length; x++) {
						data.append("samples[]", samples[x]);
					}

					$.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						type: "POST",
						url: 'clone/edit',
						data: data,
						processData: false,
						contentType: false,
						beforeSend: function() {
							$('#train-voice').prop('disabled', true);
							let btn = document.getElementById('train-voice');					
							btn.innerHTML = loading;  
							document.querySelector('#loader-line')?.classList?.remove('hidden');  
							$('#uploaded-samples').html('');     
						},
						complete: function() {
							$('#train-voice').prop('disabled', false);
							let btn = document.getElementById('train-voice');					
							btn.innerHTML = '{{ __('Re-Train Voice') }}';
							document.querySelector('#loader-line')?.classList?.add('hidden');                
						},
						success: function(data) {
							if (data['status'] == 200) {
								toastr.success('{{ __('Voice clone has been re-trained successfully') }}');
								location.reload();
							} else if(data['status'] == 400) {
								toastr.warning(data['message']);
							}
							
							document.getElementById("name").value = '';
							samples = [];
						},
						error: function(data) {
							if (data.responseJSON['error']) {
								Swal.fire('Text to Speech Notification', data.responseJSON['error'], 'warning');
							}

							$('#train-voice').prop('disabled', false);
							let btn = document.getElementById('train-voice');					
							btn.innerHTML = '{{ __('Re-Train Voice') }}';
							document.querySelector('#loader-line')?.classList?.add('hidden');  
							document.getElementById("name").value = '';
							samples = [];          
						}
					}).done(function(data) {})
				}
			});


			$('#delete-voice').on('click', function() {
				$('#deleteVoiceModal').modal('show');
			});


			$('#del-voice-button').on('click',function(e) {

				let voice = document.getElementById("del-voice").value;

				if (voice == 'none') {
					toastr.warning('{{ __('You need to select a voice clone to delete first') }}');
				} else {
					$.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						type: "POST",
						url: '/user/text-to-speech/clone/voice/remove',
						data: { 'id': voice},
						success: function(data) {
							if (data == 'success') {
								toastr.success('{{ __('Voice clone was successfully deleted') }}');
								$('#deleteVoiceModal').modal('hide');
								location.reload();
							} else if(data == 'error') {
								toastr.error('{{ __('There was an issue with voice clone deletion') }}');
							}
							
						},
						error: function(data) {
							if (data.responseJSON['error']) {
								toastr.error('{{ __('There was an issue with voice clone deletion') }}');
							}       
						}
					}).done(function(data) {})
				}
			});
		});	
		
		
		/*===========================================================================
		*
		*  Listen Row 
		*
		*============================================================================*/
		function deleteRow(row) {
			let id = row.id;

			if(id != 'ZZZOOOVVVDEL') {
				id = id.slice(0, -3);
				$('#' + id).remove();
				total_rows--;
				countCharacters();

			} else {
				let main_img = document.getElementById('ZZZOOOVVVIMG');
				main_img.setAttribute('src', textarea_img);

				let main_voice = document.getElementById('ZZZOOOVVVZ');
				main_voice.setAttribute('data-voice', textarea_voice_id);

				let instance = tippy(document.getElementById('ZZZOOOVVVIMG'));
				instance.setProps({
					animation: 'scale-extreme',
					theme: 'material',
					content: textarea_voice_details,
				});

				main_voice.value = "";
				if (total_rows == 1) {
					$('#total-characters').text('0 characters, 1 line');
				}

				Swal.fire('{{ __('Main Text Line') }}', '{{ __('Main text line cannot be deleted, line voice will change to the main selected one') }}', 'warning');
			}
		}
	</script>
@endsection