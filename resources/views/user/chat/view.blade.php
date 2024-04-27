@extends('layouts.app')
@section('css')
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/highlight/highlight.dark.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __($chat->name) }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-messages-question mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{route('user.chat')}}"> {{ __('AI Chat Assistants') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __($chat->name) }}</a></li>
			</ol>
		</div>
		<div class="page-rightheader">
			<div id="balance-status">
				<span class="fs-11 text-muted pl-3"><i class="fa-sharp fa-solid fa-bolt-lightning mr-2 text-primary"></i>{{ __('Your Balance is') }} <span class="font-weight-semibold" id="balance-number">@if (auth()->user()->available_words == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_words + auth()->user()->available_words_prepaid) }} {{ __('Words') }}@endif</span></span>
			</div>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<form id="openai-form" action="" method="GET" enctype="multipart/form-data">		
		@csrf
		<div class="row justify-content-md-center">
			
			<div class="chat-main-container">
				<div class="chat-sidebar-container">
					<div class="chat-sidebar-search">	
						<div class="input-box relative">				
							<input id="chat-search" class="form-control" type="text" placeholder="{{ __('Search') }}">	
							<i class="fa-solid fa-magnifying-glass fs-14 text-muted chat-search-icon"></i>	
						</div>			
					</div>
				
					@if ($internet)
						<div class="form-group" style="padding:15px 0 0 20px">
							<label class="custom-switch mb-0">
								<input type="checkbox" name="google-search" class="custom-switch-input" id="google-search">
								<span class="custom-switch-indicator"></span>
								<span class="custom-switch-description">{{ __('Use Real-Time Data') }}</span>
							</label>
						</div>
					@endif				

					<div class="chat-sidebar-messages">						
						@foreach ($messages as $key => $message)
							<div class="chat-sidebar-message @if ($loop->first) selected-message @endif" id="{{ $message->conversation_id }}">
								<h6 class="chat-title" id="title-{{ $message->conversation_id }}">
									{{ __($message->title) }}
								</h6>
								<div class="chat-info">
									<div class="chat-count"><span>{{ $message->messages }}</span> {{ __('messages') }}</div>
									<div class="chat-date">{{ \Carbon\Carbon::parse($message->updated_at)->diffForhumans() }}</div>
								</div>
								<div class="chat-actions d-flex">
									<a href="#" class="chat-edit fs-12" id="{{ $message->conversation_id }}"><i class="fa-sharp fa-solid fa-pen-to-square" data-tippy-content="{{ __('Edit Name') }}"></i></a>
									<a href="#" class="chat-delete fs-12 ml-2" id="{{ $message->conversation_id }}"><i class="fa-sharp fa-solid fa-trash" data-tippy-content="{{ __('Delete Chat') }}"></i></a>
								</div>
							</div>						
						@endforeach						
					</div>
					<div class="card-footer">
						<div class="row text-center">						
							<div class="col-sm-12">									
								<a class="btn btn-primary pl-6 pr-6 fs-12" id="new-chat-button">{{ __('New Conversation') }}</a>
							</div>
						</div>
					</div>
				</div>

				<div class="chat-message-container" id="chat-system">
					<div class="card-header">
						<div class="w-100 pt-2 pb-2">
							<div class="d-flex">
								<div class="overflow-hidden mr-4"><img alt="Avatar" class="chat-avatar" src="{{ URL::asset($chat->logo) }}"></div>
								<div class="widget-user-name"><span class="font-weight-bold">{{ __($chat->name) }}</span><br><span class="text-muted">{{ __($chat->sub_name) }}</span></div>
							</div>
						</div>
						<div class="w-50 text-right pt-2 pb-2">				
							<a id="expand" class="template-button" href="#"><i class="fa-solid fa-bars table-action-buttons table-action-buttons-big edit-action-button" data-tippy-content="{{ __('Show Chat Conversations') }}"></i></a>
							<a id="export-word" class="template-button mr-2" onclick="exportWord();" href="#"><i class="fa-solid fa-file-word table-action-buttons table-action-buttons-big edit-action-button" data-tippy-content="{{ __('Export Chat Conversation as Word File') }}"></i></a>
							<a id="export-pdf" class="template-button mr-2" onclick="exportPDF();" href="#"><i class="fa-solid fa-file-pdf table-action-buttons table-action-buttons-big edit-action-button" data-tippy-content="{{ __('Export Chat Conversation as PDF File') }}"></i></a>
							<a id="export-txt" class="template-button mr-2" onclick="exportTXT();" href="#"><i class="fa-solid fa-file-lines table-action-buttons table-action-buttons-big edit-action-button" data-tippy-content="{{ __('Export Chat Conversation Text File') }}"></i></a>
							{{-- <a id="clear" class="template-button" onclick="return clearConversation();" href="#"><i class="fa-solid fa-message-xmark table-action-buttons table-action-buttons-big delete-action-button" data-tippy-content="{{ __('Clear Chat Conversation') }}"></i></a> --}}
						</div>
					</div>
					<div class="card-body pl-0 pr-0">
						<div class="row">						
							<div class="col-md-12 col-sm-12" >									
								<div id="chat-container">
									<div class="msg left-msg">
										<div class="message-img" style="background-image: url({{ $chat->logo }})"></div>
										<div class="message-bubble">					
											<div class="msg-text">{{ __($chat->description) }}</div>
										</div>
									</div>

									<div id="dynamic-inputs"></div>
									<div id="generating-status" class="text-center">
										<img src='{{ URL::asset("/img/svgs/code.svg") }}'>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">						
							<div class="col-sm-12">	
								
								<div class="input-box mb-0">								
									<div class="chat-controllers">	
										@if (config('settings.vision_for_chat_feature_user') == 'allow')
											<input type="file" id="image-input" style="display: none;" accept="image/png, image/jpeg, image/webp">
											<div class="upload-button-nonresponsive"><a class="btn chat-button-icon" href="javascript:void(0)" id="upload-button" data-tippy-content="{{ __('Select your Image') }}"><i class="fa-solid fa-image"></i></a></div>				    					    
										@endif
										<textarea type="message" class="form-control @error('message') is-danger @enderror" rows="1" id="message" name="message" placeholder="{{ __('Type your message here...') }}"></textarea>
										@if (config('settings.vision_for_chat_feature_user') == 'allow')
											<div class="chat-button-box upload-button-responsive"><a class="btn chat-button-icon" href="javascript:void(0)" id="upload-button-mobile" data-tippy-content="{{ __('Select your Image') }}"><i class="fa-solid fa-image"></i></a></div>				    					    
										@endif
										<div class="chat-button-box"><a class="btn chat-button-icon" href="javascript:void(0)" id="prompt-button" data-bs-toggle="modal" data-bs-target="#promptModal" data-tippy-content="{{ __('Prompt Library') }}"><i class="fa-solid fa-notebook"></i></a></div>
										<div class="chat-button-box"><a class="btn chat-button-icon" href="javascript:void(0)" id="mic-button"><i class="fa-solid fa-microphone"></i></a></div>
										<div class="chat-button-box no-margin-right"><a class="btn chat-button-icon" href="javascript:void(0)" id="stop-button"><i class="fa-solid fa-circle-stop"></i></a></div>
										<div><button class="btn chat-button" id="chat-button">{{ __('Send') }} <i class="fa-solid fa-paper-plane-top ml-1"></i></button></div>
									</div> 
									@error('message')
										<p class="text-danger">{{ $errors->first('message') }}</p>
									@enderror
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="modal fade" id="promptModal" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		  	<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body pl-5 pr-5">
					<h6 class="text-center font-weight-extra-bold fs-16"><i class="fa-solid fa-notebook mr-2"></i> {{ __('Prompt Library') }}</h6>

					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 p-4">
							<div id="chat-search-panel">
								<div class="search-template">
									<div class="input-box">								
										<div class="form-group prompt-search-bar-dark">							    
											<input type="text" class="form-control" id="search-template" placeholder="{{ __('Search for prompts...') }}">
										</div> 
									</div> 
								</div>
							</div>
						</div>	
					</div>				
					
					<div class="prompts-panel">
			
						<div class="tab-content" id="myTabContent">
			
							<div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
								<div class="row" id="templates-panel">			
									@foreach ($prompts as $prompt)
										<div class="col-md-6 col-sm-12" id="{{ $prompt->group }}">
											<div class="prompt-boxes">
												<div class="card border-0" onclick='applyPrompt("{{ __($prompt->prompt) }}")'>
													<div class="card-body pt-3">
														<div class="template-title">
															<h6 class="mb-2 fs-15 number-font">{{ __($prompt->title) }}</h6>
														</div>
														<div class="template-info">
															<p class="fs-13 text-muted mb-2">{{ __($prompt->prompt) }}</p>
														</div>							
													</div>
												</div>
											</div>							
										</div>
									@endforeach
								</div>
							</div>
			
						</div>
					</div>
					
				</div>
		  	</div>
		</div>
	  </div>

@endsection

@section('js')
<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
<script src="{{URL::asset('plugins/pdf/html2canvas.min.js')}}"></script>
<script src="{{URL::asset('plugins/pdf/jspdf.umd.min.js')}}"></script>
<script src="{{URL::asset('plugins/highlight/highlight.min.js')}}"></script>
<script src="{{URL::asset('plugins/highlight/showdown.min.js')}}"></script>
<script src="{{URL::asset('js/export-chat.js')}}"></script>
<script type="text/javascript">
	const main_form = get("#openai-form");
	const input_text = get("#message");
	const msgerChat = get("#chat-container");
	const dynamicList = get("#dynamic-inputs");
	const msgerSendBtn = get("#chat-button");
	const bot_avatar = "{{ $chat->logo }}";
	const user_avatar = "{{ URL::asset(auth()->user()->profile_photo_path) }}";	
	const mic = document.querySelector('#mic-button');
	let eventSource = null;
	let isTranscribing = false;
	let chat_code = "{{ $chat->chat_code }}";	
	let active_id;
	let default_message;
	let uploaded_image = '';

	// Process deault conversation
	$(document).ready(function() {
		$(".chat-sidebar-message").first().focus().trigger('click');

		let check_messages = document.querySelectorAll('.chat-sidebar-message').length;
		if (check_messages == 0) {
			let id = makeid(10);

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: '/user/chat/conversation',
				data: { 'conversation_id': id, 'chat_code': chat_code},
				success: function (data) {

					if (data == 'success') {
						$('#dynamic-inputs').html('');

						$('.chat-sidebar-messages').prepend(`<div class="chat-sidebar-message selected-message" id=${id}>
								<div class="chat-title" id="title-${id}">
									{{ __('New Chat') }}
								</div>
								<div class="chat-info">
									<div class="chat-count"><span>0</span> {{ __('messages') }}</div>
									<div class="chat-date">{{ __('Now') }}</div>
								</div>
								<div class="chat-actions d-flex">
									<a href="#" class="chat-edit fs-12" id="${id}"><i class="fa-sharp fa-solid fa-pen-to-square" data-tippy-content="{{ __('Edit Name') }}"></i></a>
									<a href="#" class="chat-delete fs-12 ml-2"  id="${id}"><i class="fa-sharp fa-solid fa-trash" data-tippy-content="{{ __('Delete Chat') }}"></i></a>
								</div>
							</div>`);
						active_id = id;	
					} else {
						toastr.warning('{{ __('There was an issue while deleting chat conversation') }}');
					}		
								
				},
				error: function(data) {
					toastr.warning('{{ __('There was an issue while deleting chat conversation') }}');
				}
			});
		}

		
	});
	

	// Create new chat conversation
	$("#new-chat-button").on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
		let id = makeid(10);
		var element = document.getElementById(active_id);
		if (element) {
			element.classList.remove("selected-message");
		}

		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'POST',
			url: '/user/chat/conversation',
			data: { 'conversation_id': id, 'chat_code': chat_code},
			success: function (data) {

				if (data == 'success') {
					$('#dynamic-inputs').html('');

					$('.chat-sidebar-messages').prepend(`<div class="chat-sidebar-message selected-message" id=${id}>
							<div class="chat-title" id="title-${id}">
								{{ __('New Chat') }}
							</div>
							<div class="chat-info">
								<div class="chat-count"><span>0</span> {{ __('messages') }}</div>
								<div class="chat-date">{{ __('Now') }}</div>
							</div>
							<div class="chat-actions d-flex">
								<a href="#" class="chat-edit fs-12" id="${id}"><i class="fa-sharp fa-solid fa-pen-to-square" data-tippy-content="{{ __('Edit Name') }}"></i></a>
								<a href="#" class="chat-delete fs-12 ml-2"  id="${id}"><i class="fa-sharp fa-solid fa-trash" data-tippy-content="{{ __('Delete Chat') }}"></i></a>
							</div>
						</div>`);
					active_id = id;	
				} else {
					toastr.warning('{{ __('There was an issue while creating chat conversation') }}');
				}		
							
			},
			error: function(data) {
				toastr.warning('{{ __('There was an issue while creating chat conversation') }}');
			}
		});
    });


	// Show chat history for conversation
	$(document).on('click', ".chat-sidebar-message", function (e) { 

		$('.chat-sidebar-message').removeClass('selected-message');
		$(this).addClass('selected-message');
		$('#dynamic-inputs').html('');
		$('#generating-status').addClass('show-chat-loader');
		active_id = this.id;
		let code = makeid(10);

		$('.chat-sidebar-container').removeClass('extend');

		$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: '/user/chat/history',
				data: { 'conversation_id': active_id,},
				success: function (data) {

					$('#dynamic-inputs').html('');
					$('#generating-status').removeClass('show-chat-loader');

					for (const key in data) {

						if(data[key]['prompt']) {
							appendMessage(user_avatar, "right", data[key]['prompt'], '', data[key]['images']);
						}

						if (data[key]['response']) {
							appendMessageSpecial(bot_avatar, "left", data[key]['response'], code);
						}
					}		
					
					hljs.highlightAll();
				},
				error: function(data) {
					toastr.warning('{{ __('There was an issue while retrieving chat history') }}');
				}
			});
	});


	// Rename conversation title
	$(document).on('click', '.chat-edit', function(e) {

		e.preventDefault();

		Swal.fire({
			title: '{{ __('Rename Chat Title') }}',
			showCancelButton: true,
			confirmButtonText: '{{ __('Rename') }}',
			reverseButtons: true,
			input: 'text',
		}).then((result) => {
			if (result.value) {
				var formData = new FormData();
				formData.append("name", result.value);
				formData.append("conversation_id", $(this).attr('id'));
				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: '/user/chat/rename',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data['status'] == 'success') {
							toastr.success('{{ __('Chat title has been updated successfully') }}');
							document.getElementById("title-"+data['conversation_id']).innerHTML =  result.value;
						} else {
							toastr.error('{{ __('Chat title was not updated correctly') }}');
						}      
					},
					error: function(data) {
						Swal.fire('Update Error', data.responseJSON['error'], 'error');
					}
				})
			} else if (result.dismiss !== Swal.DismissReason.cancel) {
				Swal.fire('{{ __('No Title Entered') }}', '{{ __('Make sure to provide a new chat title before updating') }}', 'warning')
			}
		})
	});


	// Delete conversation	
	$(document).on('click', '.chat-delete', function(e) {

		e.preventDefault();

		Swal.fire({
			title: '{{ __('Confirm Chat Deletion') }}',
			text: '{{ __('It will permanently delete this chat history') }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: '{{ __('Delete') }}',
			reverseButtons: true,
		}).then((result) => {
			if (result.isConfirmed) {
				var formData = new FormData();
				formData.append("conversation_id", $(this).attr('id'));
				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: '/user/chat/delete',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						
						if (data['status'] == 'success') {
							toastr.success('{{ __('Chat history has been successfully deleted') }}');

							$("#" + active_id).remove();	
							$('#dynamic-inputs').html('');	
							$(".chat-sidebar-message").first().focus().trigger('click');
							let check_messages = document.querySelectorAll('.chat-sidebar-message').length;

							if (check_messages == 0) {
								let id = makeid(10);

								$.ajax({
									headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
									method: 'POST',
									url: '/user/chat/conversation',
									data: { 'conversation_id': id, 'chat_code': chat_code},
									success: function (data) {

										if (data == 'success') {
											$('#dynamic-inputs').html('');

											$('.chat-sidebar-messages').prepend(`<div class="chat-sidebar-message selected-message" id=${id}>
													<div class="chat-title" id="title-${id}">
														{{ __('New Chat') }}
													</div>
													<div class="chat-info">
														<div class="chat-count"><span>0</span> {{ __('messages') }}</div>
														<div class="chat-date">{{ __('Now') }}</div>
													</div>
													<div class="chat-actions d-flex">
														<a href="#" class="chat-edit fs-12" id="${id}"><i class="fa-sharp fa-solid fa-pen-to-square" data-tippy-content="{{ __('Edit Name') }}"></i></a>
														<a href="#" class="chat-delete fs-12 ml-2"  id="${id}"><i class="fa-sharp fa-solid fa-trash" data-tippy-content="{{ __('Delete Chat') }}"></i></a>
													</div>
												</div>`);
											active_id = id;	
										} else {
											toastr.warning('{{ __('There was an issue while deleting chat conversation') }}');
										}		
													
									},
									error: function(data) {
										toastr.warning('{{ __('There was an issue while deleting chat conversation') }}');
									}
								});
							}						
						} else if (data['status'] == 'empty') { 
							$('#dynamic-inputs').html('');	
								
						}else {
							toastr.warning('{{ __('There was an issue while deleting chat conversation') }}');
						}      
					},
					error: function(data) {
						Swal.fire('Oops...','Something went wrong!', 'error')
					}
				})
			} 
		})
	});

	// Check textarea input
	$(function () {		
		main_form.addEventListener("submit", event => {
			event.preventDefault();
			const message = input_text.value;
			if (!message) {
				toastr.warning('{{ __('Type your message first before sending') }}');
				return;
			}

			appendMessage(user_avatar, "right", message, '', uploaded_image);
			input_text.value = "";
			process(message)
		});

	});


	// Send chat message
	function process(message) {
		msgerSendBtn.disabled = true;
		let google = '';
		if ($('#google-search').is(':checked')) {
			google = 'on';
		} else {
			google = '';
		}
		let formData = new FormData();
		formData.append('message', message);
		formData.append('chat_code', chat_code);
		formData.append('conversation_id', active_id);
		formData.append('image', uploaded_image);
		formData.append('google_search', google);
		let code = makeid(10);
		appendMessage(bot_avatar, "left", "", code);
        let $msg_txt = $("#" + code);
		let $div = $("#chat-bubble-" + code);
		fetch('/user/chat/process', {
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: 'POST', 
				body: formData
			})		
			.then(response => response.json())
			.then(function(result){

				if (result['old'] && result['current']) {
					animateValue("balance-number", result['old'], result['current'], 300);
				}
		
				if (result['status'] == 'error') {
					Swal.fire('{{ __('Chat Notification') }}', result['message'], 'warning');
					clearConversationInvalid();
				}
			})	
			.then(data => {
				
				eventSource = new EventSource("/user/chat/generate?conversation_id=" + active_id);				
				const response = document.getElementById(code);
				const chatbubble = document.getElementById('chat-bubble-' + code);
				let msg = '';
                let i = 0;

				eventSource.onopen = function(e) {
					response.innerHTML = '';					
				};

				eventSource.onmessage = function (e) {

					if (e.data == "[DONE]") {
						msgerSendBtn.disabled = false
						eventSource.close();
						$msg_txt.html(escape_html(msg));
						$div.data('message', msg);
						hljs.highlightAll();
						uploaded_image = '';

					} else {
						let txt;
						if (uploaded_image == '') {
							txt = JSON.parse(e.data).choices[0].delta.content;
						} else {
							txt = e.data
						}

						if (txt !== undefined) {
							msg = msg + txt;

							let str = msg;
							if(str.indexOf('<') === -1){
								str = escape_html(msg)
							} else {
								str = str.replace(/[&<>"'`{}()\[\]]/g, (match) => {
									switch (match) {
										case '<':
											return '&lt;';
										case '>':
											return '&gt;';
										case '{':
											return '&#123;';
										case '}':
											return '&#125;';
										case '(':
											return '&#40;';
										case ')':
											return '&#41;';
										case '[':
											return '&#91;';
										case ']':
											return '&#93;';
										default:
											return match;
									}
								});
								str = str.replace(/(?:\r\n|\r|\n)/g, '<br>');
							}

							$msg_txt.html(str);
                            hljs.highlightAll();

							//response.innerHTML += txt.replace(/(?:\r\n|\r|\n)/g, '<br>');
						}
						msgerChat.scrollTop += 100;
					}
				};
				eventSource.onerror = function (e) {
					msgerSendBtn.disabled = false
					console.log(e);
					eventSource.close();
				};
				
			})
			.catch(function (error) {
				console.log(error);
				msgerSendBtn.disabled = false
			});

	}

	function clearConversation() {
		document.getElementById("dynamic-inputs").innerHTML = "";

		fetch('/user/chat/clear', {
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: 'POST', 
			})		
			.then(response => response.json())
			.then(function(result){

				if (result.status == 'success') {
					toastr.success('{{ __('Chat conversation has been cleared successfully') }}');
				}

			})	
			.catch(function (error) {
				console.log(error);
				msgerSendBtn.disabled = false
			});
	}

	function clearConversationInvalid() {
		document.getElementById("dynamic-inputs").innerHTML = "";

		fetch('/user/chat/clear', {
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: 'POST', 
			})		
			.then(response => response.json())
			.then(function(result){})	
			.catch(function (error) {
				console.log(error);
				msgerSendBtn.disabled = false
			});
	}

	// Counter for words
	function animateValue(id, start, end, duration) {
		if (start === end) return;
		var range = end - start;
		var current = start;
		var increment = end > start? 1 : -1;
		var stepTime = Math.abs(Math.floor(duration / range));
		var obj = document.getElementById(id);
		var timer = setInterval(function() {
			current += increment;
			if (current > 0) {
				obj.innerHTML = current;
			} else {
				obj.innerHTML = 0;
			}
			
			if (current == end) {
				clearInterval(timer);
			}
		}, stepTime);
	}

	// Display chat messages (bot and user)
	function appendMessage(img, side, text, code, url) {
		let msgHTML;
		text = escape_html(text);

		if (side == 'left' && text == '') {
			msgHTML = `
			<div class="msg ${side}-msg">
			<div class="message-img" style="background-image: url(${img})"></div>
			<div class="message-bubble" id="chat-bubble-${code}" data-message="${text}">
				<div class="msg-text" id="${code}"><img src='{{ URL::asset("/img/svgs/chat.svg") }}'></div>
				<a href="#" class="copy"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" fill="currentColor" width="20"> <path d="M180 975q-24 0-42-18t-18-42V312h60v603h474v60H180Zm120-120q-24 0-42-18t-18-42V235q0-24 18-42t42-18h440q24 0 42 18t18 42v560q0 24-18 42t-42 18H300Zm0-60h440V235H300v560Zm0 0V235v560Z"></path> </svg></a>
				<a href="#" class="listen"><svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 9C16.5 9.5 17 10.5 17 12C17 13.5 16.5 14.5 16 15M19 6C20.5 7.5 21 10 21 12C21 14 20.5 16.5 19 18M13 3L7 8H5C3.89543 8 3 8.89543 3 10V14C3 15.1046 3.89543 16 5 16H7L13 21V3Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></a>	
			</div>
			</div>`;
		} else {
			if (side == 'left') {
				msgHTML = `
				<div class="msg ${side}-msg">
				<div class="message-img" style="background-image: url(${img})"></div>
				<div class="message-bubble" id="chat-bubble-${code}" data-message="${text}">
					<div class="msg-text" id="${code}">${text}</div>
					<a href="#" class="copy"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" fill="currentColor" width="20"> <path d="M180 975q-24 0-42-18t-18-42V312h60v603h474v60H180Zm120-120q-24 0-42-18t-18-42V235q0-24 18-42t42-18h440q24 0 42 18t18 42v560q0 24-18 42t-42 18H300Zm0-60h440V235H300v560Zm0 0V235v560Z"></path> </svg></a>
					<a href="#" class="listen"><svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 9C16.5 9.5 17 10.5 17 12C17 13.5 16.5 14.5 16 15M19 6C20.5 7.5 21 10 21 12C21 14 20.5 16.5 19 18M13 3L7 8H5C3.89543 8 3 8.89543 3 10V14C3 15.1046 3.89543 16 5 16H7L13 21V3Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></a>	
				</div>
				</div>`;
			} else {
				if (url == '' || url == null) {
					msgHTML = `
					<div class="msg ${side}-msg">
					<div class="message-img" style="background-image: url(${img})"></div>
					<div class="message-bubble" id="chat-bubble-${code}">
						<div class="msg-text" id="${code}">${text}</div>
					</div>
					</div>`;
				} else {
					msgHTML = `
					<div class="msg ${side}-msg">
					<div class="message-img" style="background-image: url(${img})"></div>
					<div class="message-bubble" id="chat-bubble-${code}">
						<div class="msg-text" id="${code}">${text}</div>
						<div class="msg-image mt-2 text-center"><img src="${url}" style="height:200px; border-radius:10px;"></div>						
					</div>
					
					</div>`;
				}
			}
			
		}

		dynamicList.insertAdjacentHTML("beforeend", msgHTML);
		msgerChat.scrollTop += 500;
	}

	function appendMessageSpecial(img, side, text, code, code) {
		let msgHTML;
		let copy_text = text;
		text = escape_html(text);

		msgHTML = `
		<div class="msg ${side}-msg">
		<div class="message-img" style="background-image: url(${img})"></div>
		<div class="message-bubble" id="chat-bubble-${code}" data-message="${copy_text}">
			<div class="msg-text" id="${code}">${text}</div>
			<a href="#" class="copy"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" fill="currentColor" width="20"> <path d="M180 975q-24 0-42-18t-18-42V312h60v603h474v60H180Zm120-120q-24 0-42-18t-18-42V235q0-24 18-42t42-18h440q24 0 42 18t18 42v560q0 24-18 42t-42 18H300Zm0-60h440V235H300v560Zm0 0V235v560Z"></path> </svg></a>
			<a href="#" class="listen"><svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 9C16.5 9.5 17 10.5 17 12C17 13.5 16.5 14.5 16 15M19 6C20.5 7.5 21 10 21 12C21 14 20.5 16.5 19 18M13 3L7 8H5C3.89543 8 3 8.89543 3 10V14C3 15.1046 3.89543 16 5 16H7L13 21V3Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></a>	
		</div>
		</div>`;
			
		dynamicList.insertAdjacentHTML("beforeend", msgHTML);
		msgerChat.scrollTop += 500;
	}

	function get(selector, root = document) {
		return root.querySelector(selector);
	}

	// Generate a random value
	function makeid(length) {
		let result = '';
		const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		const charactersLength = characters.length;
		let counter = 0;
		while (counter < length) {
			result += characters.charAt(Math.floor(Math.random() * charactersLength));
			counter += 1;
		}
		return result;
	}

	function nl2br (str, is_xhtml) {
     	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
     	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
  	} 

	$('#upload-button').click(function() {
        $('#image-input').click();
    });

	$('#upload-button-mobile').click(function() {
        $('#image-input').click();
    });

	$("#expand").on('click', function (e) {
        $('.chat-sidebar-container').toggleClass('extend');
    });

	// Process image upload
	const image_input = document.getElementById("image-input");

	const convertBase64 = (file) => {
		return new Promise((resolve, reject) => {
			const fileReader = new FileReader();
			fileReader.readAsDataURL(file);

			fileReader.onload = () => {
				//resolve(fileReader.result);
				var img = new Image();
				img.src = fileReader.result;
				img.onload = function() {
					var canvas = document.createElement('canvas');
					var ctx = canvas.getContext('2d');
					canvas.height = img.height * 500 / img.width;
					canvas.width = 500;
					ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
					var base64 = canvas.toDataURL('image/png');
					uploaded_image = base64;
				}
			};

			fileReader.onerror = (error) => {
				reject(error);
			};
		});
	};

	const uploadImage = async (event) => {
		const file = event.target.files[0];
		const base64 = await convertBase64(file);
	};

	if (image_input) {
		image_input.addEventListener("change", (e) => {
			uploadImage(e);
		});
	}
	
	// Search chat history
	$('#chat-search').on('keyup', function () {
        var search = $(this).val().toLowerCase();
        $('.chat-sidebar-messages').find('.chat-sidebar-message').each(function () {
            if ($(this).filter(function() {
                return $(this).find('h6').text().toLowerCase().indexOf(search) > -1;
            }).length > 0 || search.length < 1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });


	// Send via keyboard shortcuts
	$('#message').on('keypress', function (e) {
		if (e.keyCode == 13 && !e.shiftKey) {
			e.preventDefault();
			const message = input_text.value;
			if (!message) {
				toastr.warning('{{ __('Type your message first before sending') }}');
				return;
			}			

			appendMessage(user_avatar, "right", message, '', uploaded_image);
			input_text.value = "";
			process(message)
		}
    });


	// Capture input text via microphone
    if(mic) {
        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
            const speechRecognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();

            speechRecognition.continuous = true;

            speechRecognition.addEventListener('start', () => {
                $("#mic-button").find('i').removeClass('fa-microphone').addClass('fa-stop-circle');
            });

            speechRecognition.addEventListener('result', (event) => {
                const transcript = event.results[0][0].transcript;
                $("#message").val($("#message").val() + transcript + ' ');

                mic.click();
            });

            speechRecognition.addEventListener('end', () => {
                $("#mic-button").find('i').addClass('fa-microphone').removeClass('fa-stop-circle');
                isTranscribing = false;
            });

            mic.addEventListener('click', () => {
                if (!isTranscribing) {
                    speechRecognition.start();
                    isTranscribing = true;
                } else {
                    speechRecognition.stop();
                    isTranscribing = false;
                }
            });
        } else {
            console.log('Web Speech Recognition API not supported by this browser');
            $("#mic-button").hide()
        }
    }


	// Stop chat response
	$('#stop-button').on('click', function(e){
        e.preventDefault();

        if(eventSource){
            eventSource.close();
			msgerSendBtn.disabled = false
        }
    });


	// Apply prompt
	function applyPrompt(prompt) {
		$('#message').text(prompt);
	}


	// Search prompt
	$(document).on('keyup', '#search-template', function () {
		var searchTerm = $(this).val().toLowerCase();
		$('#templates-panel').find('> div').each(function () {
			if ($(this).filter(function() {
				return (($(this).find('h6').text().toLowerCase().indexOf(searchTerm) > -1) || ($(this).find('p').text().toLowerCase().indexOf(searchTerm) > -1));
			}).length > 0 || searchTerm.length < 1) {
				$(this).show();
			} else {
				$(this).hide();
			}
		});
	});


	function escape_html (str) {
        let converter = new showdown.Converter({openLinksInNewWindow: true});
        converter.setFlavor('github');
        str = converter.makeHtml(str);

        /* add copy button */
        str = str.replaceAll('</code></pre>', '</code><button type="button" class="copy-code" onclick="copyCode(this)"><span class="label-copy-code">{{ __('Copy') }}</span></button></pre>');

        return str;
    }

	function copyCode(button) {
		const pre = button.parentElement;
		const code = pre.querySelector('code');
		const range = document.createRange();
		range.selectNode(code);
		window.getSelection().removeAllRanges();
		window.getSelection().addRange(range);
		document.execCommand("copy");
		window.getSelection().removeAllRanges();
		toastr.success('{{ __('Code has been copied successfully') }}');
	}

	$(document).on('click', ".copy", function (e) {

		e.preventDefault();

		var textArea = document.createElement("textarea");
		textArea.value = $(this).parents('.message-bubble').data('message');
		textArea.style.top = "0";
		textArea.style.left = "0";
		textArea.style.position = "fixed";
		document.body.appendChild(textArea);
		textArea.focus();
		textArea.select();

		try {
			document.execCommand('copy');
		} catch (err) {
		}

		document.body.removeChild(textArea);
		toastr.success('{{ __('Response has been copied successfully') }}');
	});

	var sound = document.createElement('audio');
	sound.id="msg-player";
 	sound.setAttribute('autoplay','false');
 	sound.setAttribute('src','');
	document.body.appendChild(sound);

	$(document).on('click', ".listen", function (e) {

		e.preventDefault();

		let text = $(this).parents('.message-bubble').data('message');		

		var formData = new FormData();
		formData.append("text", text);
		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'post',
			url: '/user/chat/listen',
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				if (data['status'] == 'success') {
 					sound.setAttribute("src", data['url']);
 					$('#msg-player')[0].play();

				} else if (data['status'] == 'error') {
					toastr.error(data['message']);
				} else {
					toastr.error('{{ __('Audio player is not available, please try again or contact support') }}');
				}      
			},
			error: function(data) {
				toastr.error('{{ __('Audio player is not available, please try again or contact support') }}');
			}
		})


	});


</script>
@endsection