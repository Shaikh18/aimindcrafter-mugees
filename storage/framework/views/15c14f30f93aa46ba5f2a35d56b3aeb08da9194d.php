

<?php $__env->startSection('css'); ?>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>					
	<div class="row justify-content-center mt-24">
		<div class="col-sm-12 text-center">
			<h3 class="card-title fs-20 mb-3 super-strong"><i class="fa-solid fa-microchip-ai mr-2 text-primary"></i><?php echo e(__('Davinci Settings')); ?></h3>
			<h6 class="mb-6 fs-12 text-muted"><?php echo e(__('Control all AI settings from one place')); ?></h6>
		</div>

		<div class="col-lg-10 col-sm-12 mb-5">
			<div class="templates-nav-menu">
				<div class="template-nav-menu-inner">
					<ul class="nav nav-tabs" id="myTab" role="tablist" style="padding: 3px">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true"><?php echo e(__('General AI Settings')); ?></button>
						</li>
						<li class="nav-item category-check" role="presentation">
							<button class="nav-link" id="api-tab" data-bs-toggle="tab" data-bs-target="#api" type="button" role="tab" aria-controls="api" aria-selected="false"><?php echo e(__('AI Vendor API Keys')); ?></button>
						</li>
						<li class="nav-item category-check" role="presentation">
							<button class="nav-link" id="extended-tab" data-bs-toggle="tab" data-bs-target="#extended" type="button" role="tab" aria-controls="extended" aria-selected="false"><?php echo e(__('Extended License Features')); ?></button>
						</li>				
					</ul>
				</div>
			</div>
		</div>

		<div class="col-lg-10 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-body pt-6 pl-6 pr-6 pb-4">				
					<div class="tab-content" id="myTabContent">

						<div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
							<form id="general-features-form" action="<?php echo e(route('admin.davinci.configs.store')); ?>" method="POST" enctype="multipart/form-data">
								<?php echo csrf_field(); ?>

								<div class="row">							

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">	
											<h6><?php echo e(__('Default OpenAI Model')); ?> <span class="text-muted">(<?php echo e(__('For Admin Group')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<select id="default-model-admin" name="default-model-admin" class="form-select" data-placeholder="<?php echo e(__('Select Default Model')); ?>:">			
												<option value="gpt-3.5-turbo" <?php if( config('settings.default_model_admin')  == 'gpt-3.5-turbo'): ?> selected <?php endif; ?>><?php echo e(__('GPT 3.5 Turbo')); ?></option>
												<option value="gpt-3.5-turbo-16k" <?php if( config('settings.default_model_admin')  == 'gpt-3.5-turbo-16k'): ?> selected <?php endif; ?>><?php echo e(__('GPT 3.5 Turbo')); ?> (<?php echo e(__('16K')); ?>)</option>
												<option value="gpt-4" <?php if( config('settings.default_model_admin')  == 'gpt-4'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4')); ?> (<?php echo e(__('8K')); ?>)</option>
												<option value="gpt-4-32k" <?php if( config('settings.default_model_admin')  == 'gpt-4-32k'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4')); ?> (<?php echo e(__('32K')); ?>)</option>
												<option value="gpt-4-1106-preview" <?php if( config('settings.default_model_admin')  == 'gpt-4-1106-preview'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4 Turbo')); ?> (<?php echo e(__('Preview')); ?>)</option>
												<option value="gpt-4-0125-preview" <?php if( config('settings.default_model_admin')  == 'gpt-4-0125-preview'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4 Turbo')); ?> (<?php echo e(__('gpt-4-0125-preview')); ?>)</option>
												<option value="gpt-4-vision-preview" <?php if( config('settings.default_model_admin')  == 'gpt-4-vision-preview'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4 Turbo with Vision')); ?> (<?php echo e(__('Preview')); ?>)</option>
												<?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<option value="<?php echo e($model->model); ?>" <?php if( config('settings.default_model_admin')  == $model->model): ?> selected <?php endif; ?>><?php echo e($model->description); ?> (Fine Tune Model)</option>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</select>
										</div>								
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">	
											<h6><?php echo e(__('Default Embedding Model')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<select id="default-embedding-model" name="default-embedding-model" class="form-select">			
												<option value="text-embedding-ada-002" <?php if( config('settings.default_embedding_model')  == 'text-embedding-ada-002'): ?> selected <?php endif; ?>><?php echo e(__('Embedding V2 Ada')); ?></option>
												<option value="text-embedding-3-small" <?php if( config('settings.default_embedding_model')  == 'text-embedding-3-small'): ?> selected <?php endif; ?>><?php echo e(__('Embedding V3 Small')); ?></option>
												<option value="text-embedding-3-large" <?php if( config('settings.default_embedding_model')  == 'text-embedding-3-large'): ?> selected <?php endif; ?>><?php echo e(__('Embedding V3 Large')); ?></option>
											</select>
										</div>								
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">	
											<h6><?php echo e(__('Default Templates Result Language')); ?> <span class="text-muted">(<?php echo e(__('For New Registrations Only')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<select id="default-language" name="default-language" class="form-select" data-placeholder="<?php echo e(__('Select Default Template Language')); ?>:">	
													<?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<option value="<?php echo e($language->language_code); ?>" data-img="<?php echo e(URL::asset($language->language_flag)); ?>" <?php if(config('settings.default_language') == $language->language_code): ?> selected <?php endif; ?>> <?php echo e($language->language); ?></option>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</select>
										</div>								
									</div>	

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('Templates Category Access')); ?> <span class="text-muted">(<?php echo e(__('For Admin Group')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<select id="templates-admin" name="templates-admin" class="form-select" data-placeholder="<?php echo e(__('Set Templates Access')); ?>">
												<option value="all" <?php if(config('settings.templates_access_admin') == 'all'): ?> selected <?php endif; ?>><?php echo e(__('All Templates')); ?></option>
												<option value="free" <?php if(config('settings.templates_access_admin') == 'free'): ?> selected <?php endif; ?>><?php echo e(__('Only Free Templates')); ?></option>
												<option value="standard" <?php if(config('settings.templates_access_admin') == 'standard'): ?> selected <?php endif; ?>> <?php echo e(__('Up to Standard Templates')); ?></option>
												<option value="professional" <?php if(config('settings.templates_access_admin') == 'professional'): ?> selected <?php endif; ?>> <?php echo e(__('Up to Professional Templates')); ?></option>																																		
												<option value="premium" <?php if(config('settings.templates_access_admin') == 'premium'): ?> selected <?php endif; ?>> <?php echo e(__('Up to Premium Templates')); ?> (<?php echo e(__('All')); ?>)</option>																																																																																																									
											</select>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('AI Article Wizard Image Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<select id="wizard-image-vendor" name="wizard-image-vendor" class="form-select">
												<option value='none' <?php if(config('settings.wizard_image_vendor') == 'none'): ?> selected <?php endif; ?>><?php echo e(__('Disable Image Generation for AI Article Wizard')); ?></option>
												<option value='dall-e-2' <?php if(config('settings.wizard_image_vendor') == 'dall-e-2'): ?> selected <?php endif; ?>> <?php echo e(__('Dalle 2')); ?></option>																															
												<option value='dall-e-3' <?php if(config('settings.wizard_image_vendor') == 'dall-e-3'): ?> selected <?php endif; ?>> <?php echo e(__('Dalle 3')); ?></option>																															
												<option value='dall-e-3-hd' <?php if(config('settings.wizard_image_vendor') == 'dall-e-3-hd'): ?> selected <?php endif; ?>> <?php echo e(__('Dalle 3 HD')); ?></option>																															
												<option value='stable-diffusion-v1-6' <?php if(config('settings.wizard_image_vendor') == 'stable-diffusion-v1-6'): ?> selected <?php endif; ?>> <?php echo e(__('Stable Diffusion v1.6')); ?></option>																															
												<option value='stable-diffusion-xl-1024-v1-0' <?php if(config('settings.wizard_image_vendor') == 'stable-diffusion-xl-1024-v1-0'): ?> selected <?php endif; ?>> <?php echo e(__('Stable Diffusion XL v1.0')); ?></option>																															
												<option value='stable-diffusion-xl-beta-v2-2-2' <?php if(config('settings.wizard_image_vendor') == 'stable-diffusion-xl-beta-v2-2-2'): ?> selected <?php endif; ?>> <?php echo e(__('Stable Diffusion XL v2.2.2 Beta')); ?></option>																															
											</select>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">	
											<h6><?php echo e(__('Maximum Result Length')); ?> <span class="text-muted">(<?php echo e(__('In Words')); ?>) (<?php echo e(__('For Admin Group')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('OpenAI has a hard limit based on Token limits for each model. Refer to OpenAI documentation to learn more. As a recommended by OpenAI, max result length is capped at 1500 words.')); ?>"></i></h6>
											<input type="number" class="form-control <?php $__errorArgs = ['max-results-admin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="max-results-admin" name="max-results-admin" placeholder="Ex: 10" value="<?php echo e(config('settings.max_results_limit_admin')); ?>" required>
											<?php $__errorArgs = ['max-results-admin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<p class="text-danger"><?php echo e($errors->first('max-results-admin')); ?></p>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>								
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">	
											<h6><?php echo e(__('Custom Chats for Users')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<select id="custom-chats" name="custom-chats" class="form-select">			
												<option value="anyone" <?php if( config('settings.custom_chats')  == 'anyone'): ?> selected <?php endif; ?>><?php echo e(__('Available to Anyone')); ?></option>												
												<option value="subscription" <?php if( config('settings.custom_chats')  == 'subscription'): ?> selected <?php endif; ?>><?php echo e(__('Available only via Subscription Plan')); ?></option>												
											</select>
										</div>								
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">	
											<h6><?php echo e(__('Custom Templates for Users')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<select id="custom-templates" name="custom-templates" class="form-select">			
												<option value="anyone" <?php if( config('settings.custom_templates')  == 'anyone'): ?> selected <?php endif; ?>><?php echo e(__('Available to Anyone')); ?></option>												
												<option value="subscription" <?php if( config('settings.custom_templates')  == 'subscription'): ?> selected <?php endif; ?>><?php echo e(__('Available only via Subscription Plan')); ?></option>												
											</select>
										</div>								
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('AI Article Wizard Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">
												<label class="custom-switch">
													<input type="checkbox" name="wizard-feature-user" class="custom-switch-input" <?php if( config('settings.wizard_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
													<span class="custom-switch-indicator"></span>
												</label>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('Smart Editor Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">
												<label class="custom-switch">
													<input type="checkbox" name="smart-editor-feature-user" class="custom-switch-input" <?php if( config('settings.smart_editor_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
													<span class="custom-switch-indicator"></span>
												</label>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('AI ReWriter Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">
												<label class="custom-switch">
													<input type="checkbox" name="rewriter-feature-user" class="custom-switch-input" <?php if( config('settings.rewriter_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
													<span class="custom-switch-indicator"></span>
												</label>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('AI Vision Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">
												<label class="custom-switch">
													<input type="checkbox" name="vision-feature-user" class="custom-switch-input" <?php if( config('settings.vision_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
													<span class="custom-switch-indicator"></span>
												</label>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('AI Vision for AI Chat')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">
												<label class="custom-switch">
													<input type="checkbox" name="vision-for-chat-user" class="custom-switch-input" <?php if( config('settings.vision_for_chat_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
													<span class="custom-switch-indicator"></span>
												</label>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('AI File Chat Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">
												<label class="custom-switch">
													<input type="checkbox" name="chat-file-feature-user" class="custom-switch-input" <?php if( config('settings.chat_file_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
													<span class="custom-switch-indicator"></span>
												</label>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('AI Web Chat Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">
												<label class="custom-switch">
													<input type="checkbox" name="chat-web-feature-user" class="custom-switch-input" <?php if( config('settings.chat_web_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
													<span class="custom-switch-indicator"></span>
												</label>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('AI Chat Image Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">
												<label class="custom-switch">
													<input type="checkbox" name="chat-image-feature-user" class="custom-switch-input" <?php if( config('settings.chat_image_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
													<span class="custom-switch-indicator"></span>
												</label>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">
											<h6><?php echo e(__('AI Code Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">
												<label class="custom-switch">
													<input type="checkbox" name="code-feature-user" class="custom-switch-input" <?php if( config('settings.code_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
													<span class="custom-switch-indicator"></span>
												</label>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-sm-12">
										<div class="input-box">	
											<h6><?php echo e(__('Team Members Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span></h6>
											<div class="form-group">
												<label class="custom-switch">
													<input type="checkbox" name="team-members-feature" class="custom-switch-input" <?php if( config('settings.team_members_feature')  == 'allow'): ?> checked <?php endif; ?>>
													<span class="custom-switch-indicator"></span>
												</label>
											</div>
										</div> 						
									</div>	
								</div>


								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><i class="fa fa-gift text-warning fs-14 mr-2"></i><?php echo e(__('Free Trial Options')); ?> <span class="text-muted">(<?php echo e(__('Free Tier User Group Only')); ?>)</span></h6>

										<div class="row">			

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('Templates Category Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<select id="templates-user" name="templates-user" class="form-select" data-placeholder="<?php echo e(__('Set Templates Access')); ?>">
														<option value="all" <?php if(config('settings.templates_access_user') == 'all'): ?> selected <?php endif; ?>><?php echo e(__('All Templates')); ?></option>	
														<option value="free" <?php if(config('settings.templates_access_user') == 'free'): ?> selected <?php endif; ?>><?php echo e(__('Only Free Templates')); ?></option>																																									
														<option value="standard" <?php if(config('settings.templates_access_user') == 'standard'): ?> selected <?php endif; ?>> <?php echo e(__('Up to Standard Templates')); ?></option>	
														<option value="professional" <?php if(config('settings.templates_access_user') == 'professional'): ?> selected <?php endif; ?>> <?php echo e(__('Up to Professional Templates')); ?></option>	
														<option value="premium" <?php if(config('settings.templates_access_user') == 'premium'): ?> selected <?php endif; ?>> <?php echo e(__('Up to Premium Templates')); ?> (<?php echo e(__('All')); ?>)</option>																																																													
													</select>
												</div>
											</div>				
											
											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Chat Package Type Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<select id="chats" name="chat-user" class="form-select" data-placeholder="<?php echo e(__('Set AI Chat Package Type Access')); ?>">
														<option value="all" <?php if(config('settings.chats_access_user') == 'all'): ?> selected <?php endif; ?>><?php echo e(__('All Chat Types')); ?></option>
														<option value="free" <?php if(config('settings.chats_access_user') == 'free'): ?> selected <?php endif; ?>><?php echo e(__('Only Free Chat Types')); ?></option>
														<option value="standard" <?php if(config('settings.chats_access_user') == 'standard'): ?> selected <?php endif; ?>> <?php echo e(__('Up to Standard Chat Types')); ?></option>
														<option value="professional" <?php if(config('settings.chats_access_user') == 'professional'): ?> selected <?php endif; ?>> <?php echo e(__('Up to Professional Chat Types')); ?></option>																																		
														<option value="premium" <?php if(config('settings.chats_access_user') == 'premium'): ?> selected <?php endif; ?>> <?php echo e(__('Up to Premium Chat Types')); ?></option>																																																																																																									
													</select>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">	
													<h6><?php echo e(__('Default OpenAI Model')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<select id="default-model-user" name="default-model-user" class="form-select" data-placeholder="<?php echo e(__('Select Default Model')); ?>:">			
														<option value="gpt-3.5-turbo" <?php if( config('settings.default_model_user')  == 'gpt-3.5-turbo'): ?> selected <?php endif; ?>><?php echo e(__('GPT 3.5 Turbo')); ?></option>
														<option value="gpt-3.5-turbo-16k" <?php if( config('settings.default_model_user')  == 'gpt-3.5-turbo-16k'): ?> selected <?php endif; ?>><?php echo e(__('GPT 3.5 Turbo')); ?> (<?php echo e(__('16K')); ?>)</option>
														<option value="gpt-4" <?php if( config('settings.default_model_user')  == 'gpt-4'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4')); ?> (<?php echo e(__('8K')); ?>)</option>
														<option value="gpt-4-32k" <?php if( config('settings.default_model_user')  == 'gpt-4-32k'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4')); ?> (<?php echo e(__('32K')); ?>)</option>												
														<option value="gpt-4-1106-preview" <?php if( config('settings.default_model_user')  == 'gpt-4-1106-preview'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4 Turbo')); ?> (<?php echo e(__('Preview')); ?>)</option>
														<option value="gpt-4-0125-preview" <?php if( config('settings.default_model_user')  == 'gpt-4-0125-preview'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4 Turbo')); ?> (<?php echo e(__('gpt-4-0125-preview')); ?>)</option>
														<option value="gpt-4-vision-preview" <?php if( config('settings.default_model_user')  == 'gpt-4-vision-preview'): ?> selected <?php endif; ?>><?php echo e(__('GPT 4 Turbo with Vision')); ?> (<?php echo e(__('Preview')); ?>)</option>
														<?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
															<option value="<?php echo e($model->model); ?>" <?php if( config('settings.default_model_user')  == $model->model): ?> selected <?php endif; ?>><?php echo e($model->description); ?> (Fine Tune Model)</option>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													</select>
												</div>								
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Voiceover Vendors Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('Only listed TTS voices of the listed vendors will be available for the subscriber. Make sure to include respective vendor API keys in the Davinci settings page.')); ?>."></i></h6>
													<select class="form-select" id="voiceover-vendors" name="voiceover_vendors[]" data-placeholder="<?php echo e(__('Choose Voiceover vendors')); ?>" multiple>
														<option value='aws' <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($value == 'aws'): ?> selected <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>><?php echo e(__('AWS')); ?></option>																															
														<option value='azure' <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($value == 'azure'): ?> selected <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>><?php echo e(__('Azure')); ?></option>																																																														
														<option value='gcp' <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($value == 'gcp'): ?> selected <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>><?php echo e(__('GCP')); ?></option>																																																														
														<option value='openai' <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($value == 'openai'): ?> selected <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>><?php echo e(__('OpenAI')); ?></option>																																																														
														<option value='elevenlabs' <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($value == 'elevenlabs'): ?> selected <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>><?php echo e(__('ElevenLabs')); ?></option>																																																																																																																											
													</select>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Article Wizard Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="wizard-user-access" class="custom-switch-input" <?php if( config('settings.wizard_access_user')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('Smart Editor Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="smart-editor-user-access" class="custom-switch-input" <?php if( config('settings.smart_editor_user_access')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI ReWriter Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="rewriter-user-access" class="custom-switch-input" <?php if( config('settings.rewriter_user_access')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Vision Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="vision-user-access" class="custom-switch-input" <?php if( config('settings.vision_access_user')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI File Chat Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="chat-file-user-access" class="custom-switch-input" <?php if( config('settings.chat_file_user_access')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Web Chat Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="chat-web-user-access" class="custom-switch-input" <?php if( config('settings.chat_web_user_access')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>
				
											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Chat Image Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="chat-image-user-access" class="custom-switch-input" <?php if( config('settings.chat_image_user_access')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">	
													<h6><?php echo e(__('Brand Voice Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="brand-voice-user-access" class="custom-switch-input" <?php if( config('settings.brand_voice_user_access')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div> 						
											</div>	

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('Internet Real Time Data Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="internet-user-access" class="custom-switch-input" <?php if( config('settings.internet_user_access')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>

											<div class="row">

												<h6 class="fs-12 font-weight-bold mb-6"><i class="fa fa-gift text-warning fs-14 mr-2"></i><?php echo e(__('Welcome Credits & Limits for Non-Subscribers')); ?></h6>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">								
														<h6><?php echo e(__('Number of Words as a Gift upon Registration')); ?> <span class="text-muted">(<?php echo e(__('One Time')); ?>)<span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span></h6>
														<div class="form-group">							    
															<input type="number" class="form-control <?php $__errorArgs = ['free-tier-words'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="free-tier-words" name="free-tier-words" placeholder="Ex: 1000" value="<?php echo e(config('settings.free_tier_words')); ?>" required>
															<span class="text-muted fs-10"><?php echo e(__('Set as -1 for unlimited words')); ?>.</span>
															<?php $__errorArgs = ['free-tier-words'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																<p class="text-danger"><?php echo e($errors->first('free-tier-words')); ?></p>
															<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
														</div> 
													</div>							
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">								
														<h6><?php echo e(__('Number of Characters for AI Voiceover as a Gift upon Registration')); ?> <span class="text-muted">(<?php echo e(__('One Time')); ?>)<span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span></h6>
														<div class="form-group">							    
															<input type="number" class="form-control <?php $__errorArgs = ['set-free-chars'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="set-free-chars" name="set-free-chars" placeholder="Ex: 1000" value="<?php echo e(config('settings.voiceover_welcome_chars')); ?>" required>
															<span class="text-muted fs-10"><?php echo e(__('Set as -1 for unlimited characters')); ?>.</span>
															<?php $__errorArgs = ['set-free-chars'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																<p class="text-danger"><?php echo e($errors->first('set-free-chars')); ?></p>
															<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
														</div> 
													</div>							
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">								
														<h6><?php echo e(__('Number of Minutes for AI Speech to Text as a Gift upon Registration')); ?> <span class="text-muted">(<?php echo e(__('One Time')); ?>)<span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span></h6>
														<div class="form-group">							    
															<input type="number" class="form-control <?php $__errorArgs = ['set-free-minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="set-free-minutes" name="set-free-minutes" placeholder="Ex: 1000" value="<?php echo e(config('settings.whisper_welcome_minutes')); ?>" required>
															<span class="text-muted fs-10"><?php echo e(__('Set as -1 for unlimited minutes')); ?>.</span>
															<?php $__errorArgs = ['set-free-minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																<p class="text-danger"><?php echo e($errors->first('set-free-minutes')); ?></p>
															<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
														</div> 
													</div>							
												</div>	

												<div class="col-lg-6 col-md-6 col-sm-12">							
													<div class="input-box">								
														<h6><?php echo e(__('Number of Dalle Images as a Gift upon Registration')); ?> <span class="text-muted">(<?php echo e(__('One Time')); ?>)<span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span></h6>
														<div class="form-group">							    
															<input type="number" class="form-control" id="free-tier-dalle-images" name="free-tier-dalle-images" value="<?php echo e(config('settings.free_tier_dalle_images')); ?>">
															<span class="text-muted fs-10"><?php echo e(__('Valid for all image sizes')); ?>. <?php echo e(__('Set as -1 for unlimited images')); ?>.</span>
														</div> 
														<?php $__errorArgs = ['free-tier-dalle-images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('free-tier-dalle-images')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 						
												</div>
			
												<div class="col-lg-6 col-md-6 col-sm-12">							
													<div class="input-box">								
														<h6><?php echo e(__('Number Stable Diffusion Images as a Gift upon Registration')); ?> <span class="text-muted">(<?php echo e(__('One Time')); ?>)<span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </span></h6>
														<div class="form-group">							    
															<input type="number" class="form-control" id="free-tier-sd-images" name="free-tier-sd-images" value="<?php echo e(config('settings.free_tier_sd_images')); ?>">
															<span class="text-muted fs-10"><?php echo e(__('Valid for all image sizes')); ?>. <?php echo e(__('Set as -1 for unlimited images')); ?>.</span>
														</div> 
														<?php $__errorArgs = ['free-tier-sd-images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('free-tier-sd-images')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 						
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">	
														<h6><?php echo e(__('Maximum Result Length')); ?> <span class="text-muted">(<?php echo e(__('In Words')); ?>) (<?php echo e(__('For Non-Subscribers')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('OpenAI has a hard limit based on Token limits for each model. Refer to OpenAI documentation to learn more. As a recommended by OpenAI, max result length is capped at 1500 words.')); ?>"></i></h6>
														<input type="number" class="form-control <?php $__errorArgs = ['max-results-user'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="max-results-user" name="max-results-user" placeholder="Ex: 10" value="<?php echo e(config('settings.max_results_limit_user')); ?>" required>
														<?php $__errorArgs = ['max-results-user'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('max-results-user')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div>								
												</div>
												
												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">	
														<h6><?php echo e(__('Maximum Allowed PDF File Size')); ?> <span class="text-muted">(<?php echo e(__('In MB')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('Set the maximum PDF file size limit for free tier user for AI File Chat feature')); ?>"></i></h6>
														<input type="number" class="form-control <?php $__errorArgs = ['max-pdf-size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="max-pdf-size" name="max-pdf-size" placeholder="Ex: 10" min="0.1" step="0.1" value="<?php echo e(config('settings.chat_pdf_file_size_user')); ?>" required>
														<?php $__errorArgs = ['max-pdf-size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('max-pdf-size')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div>								
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">	
														<h6><?php echo e(__('Maximum Allowed CSV File Size')); ?> <span class="text-muted">(<?php echo e(__('In MB')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('Set the maximum CSV file size limit for free tier user for AI File Chat feature')); ?>"></i></h6>
														<input type="number" class="form-control <?php $__errorArgs = ['max-csv-size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="max-csv-size" name="max-csv-size" placeholder="Ex: 10" min="0.1" step="0.1" value="<?php echo e(config('settings.chat_csv_file_size_user')); ?>" required>
														<?php $__errorArgs = ['max-csv-size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('max-csv-size')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div>								
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">	
														<h6><?php echo e(__('Maximum Allowed Word File Size')); ?> <span class="text-muted">(<?php echo e(__('In MB')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('Set the maximum Word file size limit for free tier user for AI File Chat feature')); ?>"></i></h6>
														<input type="number" class="form-control <?php $__errorArgs = ['max-word-size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="max-word-size" name="max-word-size" placeholder="Ex: 10" min="0.1" step="0.1" value="<?php echo e(config('settings.chat_word_file_size_user')); ?>" required>
														<?php $__errorArgs = ['max-word-size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('max-word-size')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div>								
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">	
														<h6><?php echo e(__('Team Members Quantity')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<input type="number" class="form-control <?php $__errorArgs = ['team-members-quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="team-members-quantity" name="team-members-quantity" placeholder="Ex: 5" value="<?php echo e(config('settings.team_members_quantity_user')); ?>">
													</div> 						
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">							
													<div class="input-box">								
														<h6><?php echo e(__('Image/Video/Voiceover Results Storage Period')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('After set days file results will be deleted via CRON task')); ?>."></i></h6>
														<div class="form-group">							    
															<input type="number" class="form-control" id="file-result-duration" name="file-result-duration" value="<?php echo e(config('settings.file_result_duration_user')); ?>">
															<span class="text-muted fs-10"><?php echo e(__('In Days')); ?>. <?php echo e(__('Set as -1 for unlimited storage duration')); ?>.</span>
														</div> 
														<?php $__errorArgs = ['file-result-duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('file-result-duration')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 						
												</div>
		
												<div class="col-lg-6 col-md-6 col-sm-12">							
													<div class="input-box">								
														<h6><?php echo e(__('Generated Text Content Results Storage Period')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('After set days results will be deleted from database via CRON task')); ?>."></i></h6>
														<div class="form-group">							    
															<input type="number" class="form-control" id="document-result-duration" name="document-result-duration" value="<?php echo e(config('settings.document_result_duration_user')); ?>">
															<span class="text-muted fs-10"><?php echo e(__('In Days')); ?>. <?php echo e(__('Set as -1 for unlimited storage duration')); ?>.</span>
														</div> 
														<?php $__errorArgs = ['document-result-duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('document-result-duration')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 						
												</div>												
											</div>

											
										</div>	
									</div>
								</div>


								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><i class="fa-sharp fa-solid fa-message-captions text-info fs-14 mr-2"></i><?php echo e(__('AI Chat Settings')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span></h6>

										<div class="row">

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Chat Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="chat-feature-user" class="custom-switch-input" <?php if( config('settings.chat_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">	
													<h6><?php echo e(__('AI Chat Default Voice')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<select id="chat-default-voice" name="chat-default-voice" class="form-select">			
														<option value="alloy" <?php if( config('settings.chat_default_voice')  == 'alloy'): ?> selected <?php endif; ?>><?php echo e(__('Alloy')); ?> (<?php echo e(__('Male')); ?>)</option>
														<option value="echo" <?php if( config('settings.chat_default_voice')  == 'echo'): ?> selected <?php endif; ?>><?php echo e(__('Echo')); ?> (<?php echo e(__('Male')); ?>)</option>
														<option value="fable" <?php if( config('settings.chat_default_voice')  == 'fable'): ?> selected <?php endif; ?>><?php echo e(__('Fable')); ?> (<?php echo e(__('Male')); ?>)</option>
														<option value="onyx" <?php if( config('settings.chat_default_voice')  == 'onyx'): ?> selected <?php endif; ?>><?php echo e(__('Onyx')); ?> (<?php echo e(__('Male')); ?>)</option>
														<option value="nova" <?php if( config('settings.chat_default_voice')  == 'nova'): ?> selected <?php endif; ?>><?php echo e(__('Nova')); ?> (<?php echo e(__('Female')); ?>)</option>
														<option value="shimmer" <?php if( config('settings.chat_default_voice')  == 'shimmer'): ?> selected <?php endif; ?>><?php echo e(__('Shimmer')); ?> (<?php echo e(__('Female')); ?>)</option>
													</select>
												</div>								
											</div>				
										</div>		
									</div>
								</div>


								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><i class="fa-sharp fa-solid fa-camera-viewfinder text-info fs-14 mr-2"></i><?php echo e(__('AI Image Settings')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span></h6>

										<div class="row">

											<div class="col-lg-12 col-md-12 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Image Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="image-feature-user" class="custom-switch-input" <?php if( config('settings.image_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Image Service Vendor')); ?> </h6>
													<select id="image-vendor" name="image-vendor" class="form-select" data-placeholder="<?php echo e(__('Set AI Image Service Vendor')); ?>">
														<option value='openai' <?php if(config('settings.image_vendor') == 'openai'): ?> selected <?php endif; ?>><?php echo e(__('OpenAI Dalle')); ?></option>
														<option value='stable_diffusion' <?php if(config('settings.image_vendor') == 'stable_diffusion'): ?> selected <?php endif; ?>> <?php echo e(__('Stable Diffusion')); ?></option>																															
														<option value='both' <?php if(config('settings.image_vendor') == 'both'): ?> selected <?php endif; ?>> <?php echo e(__('Both (OpenAI Dalle & Stable Diffusion)')); ?></option>																															
													</select>
												</div>
											</div>
				
											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">	
													<h6><?php echo e(__('Default Storage for AI Images')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<select id="storage" name="default-storage" class="form-select" data-placeholder="<?php echo e(__('Set Default Storage for AI Images')); ?>:">			
														<option value="local" <?php if( config('settings.default_storage')  == 'local'): ?> selected <?php endif; ?>><?php echo e(__('Local Server')); ?></option>
														<option value="aws" <?php if( config('settings.default_storage')  == 'aws'): ?> selected <?php endif; ?>><?php echo e(__('Amazon Web Services')); ?></option>
														<option value="wasabi" <?php if( config('settings.default_storage')  == 'wasabi'): ?> selected <?php endif; ?>><?php echo e(__('Wasabi Cloud')); ?></option>
														<option value="gcp" <?php if( config('settings.default_storage')  == 'gcp'): ?> selected <?php endif; ?>><?php echo e(__('Google Cloud Platform')); ?></option>
														<option value="storj" <?php if( config('settings.default_storage')  == 'storj'): ?> selected <?php endif; ?>><?php echo e(__('Storj')); ?></option>
														<option value="dropbox" <?php if( config('settings.default_storage')  == 'dropbox'): ?> selected <?php endif; ?>><?php echo e(__('Dropbox')); ?></option>
														<option value="r2" <?php if( config('settings.default_storage')  == 'r2'): ?> selected <?php endif; ?>><?php echo e(__('Cloudflare R2')); ?></option>
													</select>
												</div>								
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('Default OpenAI Dalle Engine')); ?> </h6>
													<select id="dalle-engine" name="dalle-engine" class="form-select">
														<option value='dall-e-2' <?php if(config('settings.image_dalle_engine') == 'dall-e-2'): ?> selected <?php endif; ?>><?php echo e(__('Dalle 2')); ?></option>
														<option value='dall-e-3' <?php if(config('settings.image_dalle_engine') == 'dall-e-3'): ?> selected <?php endif; ?>> <?php echo e(__('Dalle 3')); ?></option>																															
														<option value='dall-e-3-hd' <?php if(config('settings.image_dalle_engine') == 'dall-e-3-hd'): ?> selected <?php endif; ?>> <?php echo e(__('Dalle 3 HD')); ?></option>																															
													</select>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('Default Stable Diffusion Engine ID')); ?> </h6>
													<select id="stable-diffusion-engine" name="stable-diffusion-engine" class="form-select" data-placeholder="<?php echo e(__('Set Stable Diffusion Engine ID')); ?>">
														<option value='stable-diffusion-v1-6' <?php if(config('settings.image_stable_diffusion_engine') == 'stable-diffusion-v1-6'): ?> selected <?php endif; ?>><?php echo e(__('Stable Diffusion v1.6')); ?></option>
														<option value='stable-diffusion-xl-1024-v1-0' <?php if(config('settings.image_stable_diffusion_engine') == 'stable-diffusion-xl-1024-v1-0'): ?> selected <?php endif; ?>> <?php echo e(__('SDXL v1.0')); ?></option>																															
														<option value='stable-diffusion-xl-beta-v2-2-2' <?php if(config('settings.image_stable_diffusion_engine') == 'stable-diffusion-xl-beta-v2-2-2'): ?> selected <?php endif; ?>> <?php echo e(__('SDXL v2.2.2 Beta')); ?></option>																															
													</select>
												</div>
											</div>
				
										</div>		
									</div>
								</div>


								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><i class="fa-sharp fa-solid fa-waveform-lines text-info fs-14 mr-2"></i><?php echo e(__('AI Voiceover Settings')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span></h6>

										<div class="row">

											<div class="col-lg-12 col-md-12 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Voiceover Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="voiceover-feature-user" class="custom-switch-input" <?php if( config('settings.voiceover_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>	

											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- EFFECTS -->
												<div class="input-box">	
													<h6><?php echo e(__('SSML Effects')); ?></h6>
													<select id="set-ssml-effects" name="set-ssml-effects" class="form-select" data-placeholder="<?php echo e(__('Configure SSML Effects')); ?>">			
														<option value="enable" <?php if( config('settings.voiceover_ssml_effect')  == 'enable'): ?> selected <?php endif; ?>><?php echo e(__('Enable All')); ?></option>
														<option value="disable" <?php if( config('settings.voiceover_ssml_effect')  == 'disable'): ?> selected <?php endif; ?>><?php echo e(__('Disable All')); ?></option>
													</select>
												</div> <!-- END EFFECTS -->							
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- LANGUAGE -->
												<div class="input-box">	
													<h6><?php echo e(__('Default Language')); ?></h6>
													<select id="languages" name="language" class="form-select" data-placeholder="<?php echo e(__('Select Default Language')); ?>" data-callback="language_select">			
														<?php $__currentLoopData = $voiceover_languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
															<option value="<?php echo e($language->language_code); ?>" data-img="<?php echo e(URL::asset($language->language_flag)); ?>" <?php if(config('settings.voiceover_default_language') == $language->language_code): ?> selected <?php endif; ?>> <?php echo e($language->language); ?></option>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													</select>
												</div> <!-- END LANGUAGE -->							
											</div>
				
											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- VOICE -->
												<div class="input-box">	
													<h6><?php echo e(__('Default Voice')); ?></h6>
													<select id="voices" name="voice" class="form-select" data-placeholder="<?php echo e(__('Select Default Voice')); ?>" data-callback="default_voice">			
														<?php $__currentLoopData = $voices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
															<option value="<?php echo e($voice->voice_id); ?>" 	
																data-img="<?php echo e(URL::asset($voice->avatar_url)); ?>"										
																data-id="<?php echo e($voice->voice_id); ?>" 
																data-lang="<?php echo e($voice->language_code); ?>" 
																data-type="<?php echo e($voice->voice_type); ?>"
																data-gender="<?php echo e($voice->gender); ?>"
																<?php if(config('settings.voiceover_default_voice') == $voice->voice_id): ?> selected <?php endif; ?>
																data-class="<?php if(config('settings.voiceover_default_language') !== $voice->language_code): ?> remove-voice <?php endif; ?>"> 
																<?php echo e($voice->voice); ?>  														
															</option>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													</select>
												</div> <!-- END VOICE -->							
											</div>
																			
											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- MAX CHARACTERS -->
												<div class="input-box">								
													<h6><?php echo e(__('Maximum Total Characters Synthesize Limit')); ?> <i class="ml-2 fa fa-info info-notification" data-tippy-content="<?php echo e(__('Maximum supported characters per single synthesize task can be up to 100000 characters. Each voice (textarea) has a limitation of up to 5000 characters, and you can combine up to 20 voices in a single task (20 voices x 5000 textarea limit = 100000)')); ?>."></i></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-max-chars'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="set-max-chars" name="set-max-chars" placeholder="Ex: 3000" value="<?php echo e(config('settings.voiceover_max_chars_limit')); ?>">
														<?php $__errorArgs = ['set-max-chars'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-max-chars')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END MAX CHARACTERS -->							
											</div>
				
											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">								
													<h6><?php echo e(__('Maximum Concurrent Voices Limit')); ?> <i class="ml-2 fa fa-info info-notification" data-tippy-content="<?php echo e(__('You can mix up to 20 different voices in a single synthesize task. Each voice can synthesize up to 5000 characters, total characters can not exceed the limit set by Maximum Characters Synthesize Limit field.')); ?>"></i></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-max-voices'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="set-max-voices" name="set-max-voices" placeholder="Ex: 5" value="<?php echo e(config('settings.voiceover_max_voice_limit')); ?>">
														<?php $__errorArgs = ['set-max-voices'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-max-voices')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div>							
											</div>	
											
											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- STORAGE OPTION -->
												<div class="input-box">	
													<h6><?php echo e(__('Default Storage for AI Voiceovers')); ?></h6>
													<select id="set-storage-option" name="set-storage-option" class="form-select" data-placeholder="<?php echo e(__('Select Default Storage for AI Voiceover')); ?>">			
														<option value="local" <?php if( config('settings.voiceover_default_storage')  == 'local'): ?> selected <?php endif; ?>><?php echo e(__('Local Server Storage')); ?></option>
														<option value="aws" <?php if( config('settings.voiceover_default_storage')  == 'aws'): ?> selected <?php endif; ?>>Amazon Web Services</option>
														<option value="wasabi" <?php if( config('settings.voiceover_default_storage')  == 'wasabi'): ?> selected <?php endif; ?>>Wasabi Cloud</option>
														<option value="gcp" <?php if( config('settings.voiceover_default_storage')  == 'gcp'): ?> selected <?php endif; ?>><?php echo e(__('Google Cloud Platform')); ?></option>
														<option value="storj" <?php if( config('settings.voiceover_default_storage')  == 'storj'): ?> selected <?php endif; ?>><?php echo e(__('Storj')); ?></option>
														<option value="dropbox" <?php if( config('settings.voiceover_default_storage')  == 'dropbox'): ?> selected <?php endif; ?>><?php echo e(__('Dropbox')); ?></option>
														<option value="r2" <?php if( config('settings.voiceover_default_storage')  == 'r2'): ?> selected <?php endif; ?>><?php echo e(__('Cloudflare R2')); ?></option>
													</select>
												</div> <!-- END STORAGE OPTION -->							
											</div>
				
										</div>		
									</div>
								</div>


								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><i class="fa-sharp fa-solid fa-folder-music text-info fs-14 mr-2"></i><?php echo e(__('AI Speech to Text Settings')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span></h6>

										<div class="row">

											<div class="col-lg-12 col-md-12 col-sm-12">
												<div class="input-box">
													<h6><?php echo e(__('AI Speech to Text Feature')); ?> <span class="text-muted">(<?php echo e(__('For User & Subscriber Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">
														<label class="custom-switch">
															<input type="checkbox" name="whisper-feature-user" class="custom-switch-input" <?php if( config('settings.whisper_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
															<span class="custom-switch-indicator"></span>
														</label>
													</div>
												</div>
											</div>
																			
											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- MAX CHARACTERS -->
												<div class="input-box">								
													<h6><?php echo e(__('Maximum Allowed Audio File Size')); ?> <i class="ml-2 fa fa-info info-notification" data-tippy-content="<?php echo e(__('OpenAI supports audio files only up to 25MB')); ?>."></i></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-max-audio-size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="set-max-audio-size" name="set-max-audio-size" placeholder="Ex: 25" value="<?php echo e(config('settings.whisper_max_audio_size')); ?>">
														<?php $__errorArgs = ['set-max-audio-size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-max-audio-size')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END MAX CHARACTERS -->							
											</div>
				
											
											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- STORAGE OPTION -->
												<div class="input-box">	
													<h6><?php echo e(__('Default Storage for AI Speech to Text')); ?></h6>
													<select id="set-whisper-storage-option" name="set-whisper-storage-option" class="form-select" data-placeholder="<?php echo e(__('Select Default Storage for AI Speech to Text')); ?>">			
														<option value="local" <?php if( config('settings.whisper_default_storage')  == 'local'): ?> selected <?php endif; ?>><?php echo e(__('Local Server Storage')); ?></option>
														<option value="aws" <?php if( config('settings.whisper_default_storage')  == 'aws'): ?> selected <?php endif; ?>>Amazon Web Services</option>
														<option value="wasabi" <?php if( config('settings.whisper_default_storage')  == 'wasabi'): ?> selected <?php endif; ?>>Wasabi Cloud</option>
														<option value="gcp" <?php if( config('settings.whisper_default_storage')  == 'gcp'): ?> selected <?php endif; ?>><?php echo e(__('Google Cloud Platform')); ?></option>
														<option value="storj" <?php if( config('settings.whisper_default_storage')  == 'storj'): ?> selected <?php endif; ?>><?php echo e(__('Storj')); ?></option>
														<option value="dropbox" <?php if( config('settings.whisper_default_storage')  == 'dropbox'): ?> selected <?php endif; ?>><?php echo e(__('Dropbox')); ?></option>
														<option value="r2" <?php if( config('settings.whisper_default_storage')  == 'r2'): ?> selected <?php endif; ?>><?php echo e(__('Cloudflare R2')); ?></option>
													</select>
												</div> <!-- END STORAGE OPTION -->							
											</div>								
										</div>		
									</div>
								</div>


								<div class="card border-0 special-shadow ">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><i class="fa-sharp fa-solid fa-sliders text-info fs-14 mr-2"></i><?php echo e(__('Miscellaneous')); ?></h6>

										<div class="row">
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12">	
													<div class="input-box">	
														<h6><?php echo e(__('Sensitive Words Filter')); ?> <span class="text-muted">(<?php echo e(__('Comma Separated')); ?>)</span></h6>							
														<textarea class="form-control" name="words-filter" rows="6" id="words-filter"><?php echo e($settings->value); ?></textarea>	
													</div>											
												</div>
											</div>							
										</div>
			
									</div>
								</div>

								<!-- SAVE CHANGES ACTION BUTTON -->
								<div class="border-0 text-center mb-2 mt-1">
									<button type="button" class="btn ripple btn-primary" style="min-width: 200px;" id="general-settings"><?php echo e(__('Save')); ?></button>							
								</div>				
							</form>
						</div>

						<div class="tab-pane fade" id="api" role="tabpanel" aria-labelledby="api-tab">
							<form id="api-features-form" action="<?php echo e(route('admin.davinci.configs.store.api')); ?>" method="POST" enctype="multipart/form-data">
								<?php echo csrf_field(); ?>

								<div class="card border-0 special-shadow mt-0 mb-7">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/openai-sm.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('OpenAI')); ?></h6>

										<div class="row">
											<div class="col-lg-12 col-md-6 col-sm-12">
												<div class="row">								
													<div class="col-md-6 col-sm-12">
														<div class="input-box mb-0">								
															<h6><?php echo e(__('OpenAI Secret Key')); ?> <span class="text-muted">(<?php echo e(__('Main API Key')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
															<div class="form-group">							    
																<input type="text" class="form-control <?php $__errorArgs = ['secret-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="secret-key" name="secret-key" value="<?php echo e(config('services.openai.key')); ?>" autocomplete="off">
																<?php $__errorArgs = ['secret-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p class="text-danger"><?php echo e($errors->first('secret-key')); ?></p>
																<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
															</div> 												
														</div> 
													</div>

													<div class="col-md-6 col-sm-12">
														<div class="input-box">
															<h6><?php echo e(__('Personal OpenAI API Key')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('If enabled, all users will be required to include their Personal OpenAi API keys in their profile pages. You can also enable it via Subscription plans only.')); ?>"></i></h6>
															<select id="personal-openai-api" name="personal-openai-api" class="form-select">
																<option value="allow" <?php if(config('settings.personal_openai_api') == 'allow'): ?> selected <?php endif; ?>><?php echo e(__('Allow')); ?></option>
																<option value="deny" <?php if(config('settings.personal_openai_api') == 'deny'): ?> selected <?php endif; ?>><?php echo e(__('Deny')); ?></option>																																																																																																								
															</select>
														</div>
													</div>				
													
													<div class="col-md-6 col-sm-12">
														<div class="input-box mb-0">								
															<h6><?php echo e(__('Openai API Key Usage Model')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
															<select id="openai-key-usage" name="openai-key-usage" class="form-select" data-placeholder="<?php echo e(__('Set API Key Usage Model')); ?>">
																<option value="main" <?php if(config('settings.openai_key_usage') == 'main'): ?> selected <?php endif; ?>><?php echo e(__('Only Main API Key')); ?></option>
																<option value="random" <?php if(config('settings.openai_key_usage') == 'random'): ?> selected <?php endif; ?>><?php echo e(__('Random API Key')); ?></option>																																																																																																									
															</select>
														</div> 
													</div>
												</div>
												<a href="<?php echo e(route('admin.davinci.configs.keys')); ?>" class="btn btn-primary mt-4 mr-4" style="padding-left: 25px; padding-right: 25px;"><?php echo e(__('Store additional OpenAI API Keys')); ?></a>
												<a href="<?php echo e(route('admin.davinci.configs.fine-tune')); ?>" class="btn btn-primary mt-4" style="width: 223px;"><?php echo e(__('Fine Tune Models')); ?></a>
											</div>							
										</div>

										<div class="row">
											<h6 class="fs-12 font-weight-bold mb-4 mt-4"><?php echo e(__('OpenAI Voiceover Settings')); ?></h6>

											<div class="col-md-6 col-sm-12">
												<div class="form-group">
													<label class="custom-switch">
														<input type="checkbox" name="enable-openai-std" class="custom-switch-input" <?php if( config('settings.enable.openai_std')  == 'on'): ?> checked <?php endif; ?>>
														<span class="custom-switch-indicator"></span>
														<span class="custom-switch-description"><?php echo e(__('Activate OpenAI Standard Voices')); ?></span>
													</label>
												</div>
											</div>	
											<div class="col-md-6 col-sm-12">
												<div class="form-group">
													<label class="custom-switch">
														<input type="checkbox" name="enable-openai-nrl" class="custom-switch-input" <?php if( config('settings.enable.openai_nrl')  == 'on'): ?> checked <?php endif; ?>>
														<span class="custom-switch-indicator"></span>
														<span class="custom-switch-description"><?php echo e(__('Activate OpenAI Neural Voices')); ?></span>
													</label>
												</div>
											</div>							
										</div>	
									</div>
								</div>
								
								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/stability-sm.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('Stable Diffusion')); ?></h6>

										<div class="row">
											<div class="col-lg-12 col-md-6 col-sm-12 no-gutters">
												<div class="row">							
													<div class="col-md-6 col-sm-12">
														<div class="input-box mb-0">								
															<h6><?php echo e(__('Stable Diffusion API Key')); ?> <span class="text-muted">(<?php echo e(__('Main API Key')); ?>)</span><span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
															<div class="form-group">							    
																<input type="text" class="form-control <?php $__errorArgs = ['stable-diffusion-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="stable-diffusion-key" name="stable-diffusion-key" value="<?php echo e(config('services.stable_diffusion.key')); ?>" autocomplete="off">
																<?php $__errorArgs = ['stable-diffusion-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p class="text-danger"><?php echo e($errors->first('stable-diffusion-key')); ?></p>
																<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>												
															</div> 
														</div> 
													</div>

													<div class="col-md-6 col-sm-12">
														<div class="input-box">
															<h6><?php echo e(__('Personal Stable Diffusion API Key')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('If enabled, all users will be required to include their Personal Stable Diffusion API keys in their profile pages. You can also enable it via Subscription plans only.')); ?>"></i></h6>
															<select id="personal-sd-api" name="personal-sd-api" class="form-select">
																<option value="allow" <?php if(config('settings.personal_sd_api') == 'allow'): ?> selected <?php endif; ?>><?php echo e(__('Allow')); ?></option>
																<option value="deny" <?php if(config('settings.personal_sd_api') == 'deny'): ?> selected <?php endif; ?>><?php echo e(__('Deny')); ?></option>																																																																																																								
															</select>
														</div>
													</div>

													<div class="col-md-6 col-sm-12">
														<div class="input-box mb-0">								
															<h6><?php echo e(__('SD API Key Usage Model')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
															<select id="sd-key-usage" name="sd-key-usage" class="form-select" data-placeholder="<?php echo e(__('Set API Key Usage Model')); ?>">
																<option value="main" <?php if(config('settings.sd_key_usage') == 'main'): ?> selected <?php endif; ?>><?php echo e(__('Only Main API Key')); ?></option>
																<option value="random" <?php if(config('settings.sd_key_usage') == 'random'): ?> selected <?php endif; ?>><?php echo e(__('Random API Key')); ?></option>																																																																																																									
															</select>
														</div> 
													</div>
												</div>
												<a href="<?php echo e(route('admin.davinci.configs.keys')); ?>" class="btn btn-primary mt-4 mb-2" style="padding-left: 25px; padding-right: 25px; width: 223px;"><?php echo e(__('Store additional SD API Keys')); ?></a>
											</div>							
										</div>
			
									</div>
								</div>	

								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/azure-sm.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('Azure Voiceover Settings')); ?></h6>

										<div class="form-group mb-3">
											<label class="custom-switch">
												<input type="checkbox" name="enable-azure" class="custom-switch-input" <?php if( config('settings.enable.azure')  == 'on'): ?> checked <?php endif; ?>>
												<span class="custom-switch-indicator"></span>
												<span class="custom-switch-description"><?php echo e(__('Activate Azure Voices')); ?></span>
											</label>
										</div>

										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Azure Key')); ?></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-azure-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="set-azure-key" name="set-azure-key" value="<?php echo e(config('services.azure.key')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-azure-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-azure-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- AZURE REGION -->
												<div class="input-box">	
													<h6><?php echo e(__('Azure Region')); ?></h6>
													<select id="set-azure-region" name="set-azure-region" class="form-select" data-placeholder="Select Azure Region:">			
														<option value="australiaeast" <?php if( config('services.azure.region')  == 'australiaeast'): ?> selected <?php endif; ?>>Australia East (australiaeast)</option>
														<option value="brazilsouth" <?php if( config('services.azure.region')  == 'brazilsouth'): ?> selected <?php endif; ?>>Brazil South (brazilsouth)</option>
														<option value="canadacentral" <?php if( config('services.azure.region')  == 'canadacentral'): ?> selected <?php endif; ?>>Canada Central (canadacentral)</option>
														<option value="centralus" <?php if( config('services.azure.region')  == 'centralus'): ?> selected <?php endif; ?>>Central US (centralus)</option>
														<option value="eastasia" <?php if( config('services.azure.region')  == 'eastasia'): ?> selected <?php endif; ?>>East Asia (eastasia)</option>
														<option value="eastus" <?php if( config('services.azure.region')  == 'eastus'): ?> selected <?php endif; ?>>East US (eastus)</option>
														<option value="eastus2" <?php if( config('services.azure.region')  == 'eastus2'): ?> selected <?php endif; ?>>East US 2 (eastus2)</option>
														<option value="francecentral" <?php if( config('services.azure.region')  == 'francecentral'): ?> selected <?php endif; ?>>France Central (francecentral)</option>
														<option value="centralindia" <?php if( config('services.azure.region')  == 'centralindia'): ?> selected <?php endif; ?>>India Central (centralindia)</option>
														<option value="japaneast" <?php if( config('services.azure.region')  == 'japaneast'): ?> selected <?php endif; ?>>Japan East (japaneast)</option>
														<option value="japanwest" <?php if( config('services.azure.region')  == 'japanwest'): ?> selected <?php endif; ?>>Japan West (japanwest)</option>
														<option value="koreacentral" <?php if( config('services.azure.region')  == 'koreacentral'): ?> selected <?php endif; ?>>Korea Central (koreacentral)</option>
														<option value="northcentralus" <?php if( config('services.azure.region')  == 'northcentralus'): ?> selected <?php endif; ?>>North Central US (northcentralus)</option>
														<option value="northeurope" <?php if( config('services.azure.region')  == 'northeurope'): ?> selected <?php endif; ?>>North Europe (northeurope)</option>
														<option value="southcentralus" <?php if( config('services.azure.region')  == 'southcentralus'): ?> selected <?php endif; ?>>South Central US (southcentralus)</option>
														<option value="southeastasia" <?php if( config('services.azure.region')  == 'southeastasia'): ?> selected <?php endif; ?>>Southeast Asia (southeastasia)</option>
														<option value="uksouth" <?php if( config('services.azure.region')  == 'uksouth'): ?> selected <?php endif; ?>>UK South (uksouth)</option>
														<option value="westcentralus" <?php if( config('services.azure.region')  == 'westcentralus'): ?> selected <?php endif; ?>>West Central US (westcentralus)</option>
														<option value="westeurope" <?php if( config('services.azure.region')  == 'westeurope'): ?> selected <?php endif; ?>>West Europe (westeurope)</option>
														<option value="westus" <?php if( config('services.azure.region')  == 'westus'): ?> selected <?php endif; ?>>West US (westus)</option>
														<option value="westus2" <?php if( config('services.azure.region')  == 'westus2'): ?> selected <?php endif; ?>>West US 2 (westus2)</option>
													</select>
												</div> <!-- END AZURE REGION -->									
											</div>

										</div>
			
									</div>
								</div>

								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/elevenlabs-sm.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('ElevenLabs Voiceover Settings')); ?></h6>

										<div class="form-group mb-3">
											<label class="custom-switch">
												<input type="checkbox" name="enable-elevenlabs" class="custom-switch-input" <?php if( config('settings.enable.elevenlabs')  == 'on'): ?> checked <?php endif; ?>>
												<span class="custom-switch-indicator"></span>
												<span class="custom-switch-description"><?php echo e(__('Activate ElevenLabs Voices')); ?></span>
											</label>
										</div>

										<div class="row">
											<div class="col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('ElevenLabs API Key')); ?></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-elevenlabs-api'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="set-elevenlabs-api" name="set-elevenlabs-api" value="<?php echo e(config('services.elevenlabs.key')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-elevenlabs-api'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-elevenlabs-api')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>								
										</div>
			
									</div>
								</div>	

								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">

										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/gcp-sm.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('GCP Voiceover Settings')); ?></h6>

										<div class="form-group mb-3">
											<label class="custom-switch">
												<input type="checkbox" name="enable-gcp" class="custom-switch-input" <?php if( config('settings.enable.gcp')  == 'on'): ?> checked <?php endif; ?>>
												<span class="custom-switch-indicator"></span>
												<span class="custom-switch-description"><?php echo e(__('Activate GCP Voices')); ?></span>
											</label>
										</div>

										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('GCP Configuration File Path')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['gcp-configuration-path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="gcp-configuration-path" name="gcp-configuration-path" value="<?php echo e(config('services.gcp.key_path')); ?>" autocomplete="off">
														<?php $__errorArgs = ['gcp-configuration-path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('gcp-configuration-path')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>	
											<div class="col-lg-6 col-md-6 col-sm-12">								
												<div class="input-box">								
													<h6><?php echo e(__('GCP Storage Bucket Name')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['gcp-bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="gcp-bucket" name="gcp-bucket" value="<?php echo e(config('services.gcp.bucket')); ?>" autocomplete="off">
														<?php $__errorArgs = ['gcp-bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('gcp-bucket')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> 
											</div>							
										</div>
			
									</div>
								</div>	

								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">
										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/aws-sm.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('Amazon Web Services')); ?></h6>

										<div class="row">
											<div class="col-md-6 col-sm-12">
												<div class="form-group mb-3">
													<label class="custom-switch">
														<input type="checkbox" name="enable-aws-std" class="custom-switch-input" <?php if( config('settings.enable.aws_std')  == 'on'): ?> checked <?php endif; ?>>
														<span class="custom-switch-indicator"></span>
														<span class="custom-switch-description"><?php echo e(__('Activate AWS Standard Voices')); ?></span>
													</label>
												</div>
											</div>	
											<div class="col-md-6 col-sm-12">
												<div class="form-group mb-3">
													<label class="custom-switch">
														<input type="checkbox" name="enable-aws-nrl" class="custom-switch-input" <?php if( config('settings.enable.aws_nrl')  == 'on'): ?> checked <?php endif; ?>>
														<span class="custom-switch-indicator"></span>
														<span class="custom-switch-description"><?php echo e(__('Activate AWS Neural Voices')); ?></span>
													</label>
												</div>
											</div>	

											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('AWS Access Key')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-aws-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="aws-access-key" name="set-aws-access-key" value="<?php echo e(config('services.aws.key')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-aws-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-aws-access-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- SECRET ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('AWS Secret Access Key')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6> 
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-aws-secret-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="aws-secret-access-key" name="set-aws-secret-access-key" value="<?php echo e(config('services.aws.secret')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-aws-secret-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-aws-secret-access-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END SECRET ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Amazon S3 Bucket Name')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-aws-bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="aws-bucket" name="set-aws-bucket" value="<?php echo e(config('services.aws.bucket')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-aws-bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-aws-bucket')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- AWS REGION -->
												<div class="input-box">	
													<h6><?php echo e(__('Set AWS Region')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<select id="set-aws-region" name="set-aws-region" class="form-select" data-placeholder="Select Default AWS Region:">			
														<option value="us-east-1" <?php if( config('services.aws.region')  == 'us-east-1'): ?> selected <?php endif; ?>><?php echo e(__('US East (N. Virginia) us-east-1')); ?></option>
														<option value="us-east-2" <?php if( config('services.aws.region')  == 'us-east-2'): ?> selected <?php endif; ?>><?php echo e(__('US East (Ohio) us-east-2')); ?></option>
														<option value="us-west-1" <?php if( config('services.aws.region')  == 'us-west-1'): ?> selected <?php endif; ?>><?php echo e(__('US West (N. California) us-west-1')); ?></option>
														<option value="us-west-2" <?php if( config('services.aws.region')  == 'us-west-2'): ?> selected <?php endif; ?>><?php echo e(__('US West (Oregon) us-west-2')); ?></option>
														<option value="ap-east-1" <?php if( config('services.aws.region')  == 'ap-east-1'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific (Hong Kong) ap-east-1')); ?></option>
														<option value="ap-south-1" <?php if( config('services.aws.region')  == 'ap-south-1'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific (Mumbai) ap-south-1')); ?></option>
														<option value="ap-northeast-3" <?php if( config('services.aws.region')  == 'ap-northeast-3'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific (Osaka) ap-northeast-3')); ?></option>
														<option value="ap-northeast-2" <?php if( config('services.aws.region')  == 'ap-northeast-2'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific (Seoul) ap-northeast-2')); ?></option>
														<option value="ap-southeast-1" <?php if( config('services.aws.region')  == 'ap-southeast-1'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific (Singapore) ap-southeast-1')); ?></option>
														<option value="ap-southeast-2" <?php if( config('services.aws.region')  == 'ap-southeast-2'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific (Sydney) ap-southeast-2')); ?></option>
														<option value="ap-northeast-1" <?php if( config('services.aws.region')  == 'ap-northeast-1'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific (Tokyo) ap-northeast-1')); ?></option>
														<option value="ap-northeast-1" <?php if( config('services.aws.region')  == 'ap-south-2'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific (Hyderabad) ap-south-2')); ?></option>
														<option value="ap-northeast-1" <?php if( config('services.aws.region')  == 'ap-southeast-3'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific (Jakarta) ap-southeast-3')); ?></option>
														<option value="eu-central-1" <?php if( config('services.aws.region')  == 'eu-central-1'): ?> selected <?php endif; ?>><?php echo e(__('Europe (Frankfurt) eu-central-1')); ?></option>
														<option value="eu-central-1" <?php if( config('services.aws.region')  == 'eu-central-2'): ?> selected <?php endif; ?>><?php echo e(__('Europe (Zurich) eu-central-2')); ?></option>
														<option value="eu-west-1" <?php if( config('services.aws.region')  == 'eu-west-1'): ?> selected <?php endif; ?>><?php echo e(__('Europe (Ireland) eu-west-1')); ?></option>
														<option value="eu-west-2" <?php if( config('services.aws.region')  == 'eu-west-2'): ?> selected <?php endif; ?>><?php echo e(__('Europe (London) eu-west-2')); ?></option>
														<option value="eu-south-1" <?php if( config('services.aws.region')  == 'eu-south-1'): ?> selected <?php endif; ?>><?php echo e(__('Europe (Milan) eu-south-1')); ?></option>
														<option value="eu-south-1" <?php if( config('services.aws.region')  == 'eu-south-1'): ?> selected <?php endif; ?>><?php echo e(__('Europe (Spain) eu-south-2')); ?></option>
														<option value="eu-west-3" <?php if( config('services.aws.region')  == 'eu-west-3'): ?> selected <?php endif; ?>><?php echo e(__('Europe (Paris) eu-west-3')); ?></option>
														<option value="eu-north-1" <?php if( config('services.aws.region')  == 'eu-north-1'): ?> selected <?php endif; ?>><?php echo e(__('Europe (Stockholm) eu-north-1')); ?></option>
														<option value="me-south-1" <?php if( config('services.aws.region')  == 'me-south-1'): ?> selected <?php endif; ?>><?php echo e(__('Middle East (Bahrain) me-south-1')); ?></option>
														<option value="me-south-1" <?php if( config('services.aws.region')  == 'me-central-1'): ?> selected <?php endif; ?>><?php echo e(__('Middle East (UAE) me-central-1')); ?></option>
														<option value="sa-east-1" <?php if( config('services.aws.region')  == 'sa-east-1'): ?> selected <?php endif; ?>><?php echo e(__('South America (São Paulo) sa-east-1')); ?></option>
														<option value="ca-central-1" <?php if( config('services.aws.region')  == 'ca-central-1'): ?> selected <?php endif; ?>><?php echo e(__('Canada (Central) ca-central-1')); ?></option>
														<option value="af-south-1" <?php if( config('services.aws.region')  == 'af-south-1'): ?> selected <?php endif; ?>><?php echo e(__('Africa (Cape Town) af-south-1')); ?></option>
													</select>
												</div> <!-- END AWS REGION -->									
											</div>									
				
										</div>
			
									</div>
								</div>	

								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">
										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/storj-ssm.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('Storj Cloud')); ?></h6>

										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Storj Access Key')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-storj-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="storj-access-key" name="set-storj-access-key" value="<?php echo e(config('services.storj.key')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-storj-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-storj-access-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- SECRET ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Storj Secret Access Key')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6> 
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-storj-secret-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="storj-secret-access-key" name="set-storj-secret-access-key" value="<?php echo e(config('services.storj.secret')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-storj-secret-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-storj-secret-access-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END SECRET ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Storj Bucket Name')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-storj-bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="storj-bucket" name="set-storj-bucket" value="<?php echo e(config('services.storj.bucket')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-storj-bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-storj-bucket')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>									
				
										</div>
			
									</div>
								</div>

								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">
										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/dropbox-ssm.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('Dropbox')); ?></h6>

										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Dropbox App Key')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-dropbox-app-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="dropbox-app-key" name="set-dropbox-app-key" value="<?php echo e(config('services.dropbox.key')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-dropbox-app-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-dropbox-app-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- SECRET ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Dropbox Secret Key')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6> 
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-dropbox-secret-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="dropbox-secret-key" name="set-dropbox-secret-key" value="<?php echo e(config('services.dropbox.secret')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-dropbox-secret-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-dropbox-secret-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END SECRET ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Dropbox Access Token')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-dropbox-access-token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="dropbox-access-token" name="set-dropbox-access-token" value="<?php echo e(config('services.dropbox.token')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-dropbox-access-token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-dropbox-access-token')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>									
				
										</div>
			
									</div>
								</div>

								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">
										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/wasabi-sm.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('Wasabi Cloud Storage')); ?></h6>

										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Wasabi Access Key')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-wasabi-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="wasabi-access-key" name="set-wasabi-access-key" value="<?php echo e(config('services.wasabi.key')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-wasabi-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-wasabi-access-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- SECRET ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Wasabi Secret Access Key')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6> 
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-wasabi-secret-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="wasabi-secret-access-key" name="set-wasabi-secret-access-key" value="<?php echo e(config('services.wasabi.secret')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-wasabi-secret-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-wasabi-secret-access-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END SECRET ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Wasabi Bucket Name')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-wasabi-bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="wasabi-bucket" name="set-wasabi-bucket" value="<?php echo e(config('services.wasabi.bucket')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-wasabi-bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-wasabi-bucket')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- AWS REGION -->
												<div class="input-box">	
													<h6><?php echo e(__('Set Wasabi Region')); ?>  <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<select id="set-wasabi-region" name="set-wasabi-region" class="form-select" data-placeholder="Select Default Wasabi Region:">			
														<option value="us-west-1" <?php if( config('services.wasabi.region')  == 'us-west-1'): ?> selected <?php endif; ?>><?php echo e(__('US Oregon us-west-1')); ?></option>
														<option value="us-central-1" <?php if( config('services.wasabi.region')  == 'us-central-1'): ?> selected <?php endif; ?>><?php echo e(__('US Texas us-central-1')); ?></option>
														<option value="us-east-1" <?php if( config('services.wasabi.region')  == 'us-east-1'): ?> selected <?php endif; ?>><?php echo e(__('US N.Virginia us-east-1')); ?></option>
														<option value="us-east-2" <?php if( config('services.wasabi.region')  == 'us-east-2'): ?> selected <?php endif; ?>><?php echo e(__('US N.Virginia us-east-2')); ?></option>
														<option value="ap-northeast-1" <?php if( config('services.wasabi.region')  == 'ap-northeast-1'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific Tokyo ap-northeast-1')); ?></option>
														<option value="ap-northeast-2" <?php if( config('services.wasabi.region')  == 'ap-northeast-2'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific Osaka ap-northeast-2')); ?></option>
														<option value="ap-southeast-1" <?php if( config('services.wasabi.region')  == 'ap-southeast-1'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific Singapore ap-southeast-1')); ?></option>
														<option value="ap-southeast-2" <?php if( config('services.wasabi.region')  == 'ap-southeast-2'): ?> selected <?php endif; ?>><?php echo e(__('Asia Pacific Sydney ap-southeast-2')); ?></option>
														<option value="ca-central-1" <?php if( config('services.wasabi.region')  == 'ca-central-1'): ?> selected <?php endif; ?>><?php echo e(__('Canada Toronto ca-central-1')); ?></option>
														<option value="eu-central-1" <?php if( config('services.wasabi.region')  == 'eu-central-1'): ?> selected <?php endif; ?>><?php echo e(__('Europe Amsterdam eu-central-1')); ?></option>
														<option value="eu-central-2" <?php if( config('services.wasabi.region')  == 'eu-central-2'): ?> selected <?php endif; ?>><?php echo e(__('Europe Frankfurt eu-central-2')); ?></option>
														<option value="eu-west-1" <?php if( config('services.wasabi.region')  == 'eu-west-1'): ?> selected <?php endif; ?>><?php echo e(__('Europe London eu-west-1')); ?></option>
														<option value="eu-west-2" <?php if( config('services.wasabi.region')  == 'eu-west-2'): ?> selected <?php endif; ?>><?php echo e(__('Europe Paris eu-west-2')); ?></option>
													</select>
												</div> <!-- END AWS REGION -->									
											</div>								
				
										</div>
			
									</div>
								</div>

								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">
										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/cloudflare-sm.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('Cloudflare R2 Storage')); ?></h6>

										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-12">								
												<!-- ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Cloudflare R2 Access Key')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-r2-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="r2-access-key" name="set-r2-access-key" value="<?php echo e(config('services.r2.key')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-r2-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-r2-access-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<!-- SECRET ACCESS KEY -->
												<div class="input-box">								
													<h6><?php echo e(__('Cloudflare R2 Secret Access Key')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6> 
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-r2-secret-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="r2-secret-access-key" name="set-r2-secret-access-key" value="<?php echo e(config('services.r2.secret')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-r2-secret-access-key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-r2-secret-access-key')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> <!-- END SECRET ACCESS KEY -->
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">								
												<div class="input-box">								
													<h6><?php echo e(__('Cloudflare R2 Bucket Name')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-r2-bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="r2-bucket" name="set-r2-bucket" value="<?php echo e(config('services.r2.bucket')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-r2-bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-r2-bucket')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> 
											</div>		
											
											<div class="col-lg-6 col-md-6 col-sm-12">								
												<div class="input-box">								
													<h6><?php echo e(__('Cloudflare R2 Endpoint')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-r2-endpoint'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="r2-endpoint" name="set-r2-endpoint" value="<?php echo e(config('services.r2.endpoint')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-r2-endpoint'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-r2-endpoint')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 
												</div> 
											</div>
				
										</div>
			
									</div>
								</div>

								<div class="card border-0 special-shadow mb-7">							
									<div class="card-body">
										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/serper.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('Serper Settings')); ?></h6>

										<div class="row">
											<div class="col-md-6 col-sm-12">
												<div class="input-box">								
													<h6><?php echo e(__('Serper API Key')); ?> <span class="text-muted">(<?php echo e(__('Required for Real-Time Internet Access')); ?>)</span></h6>
													<div class="form-group">							    
														<input type="text" class="form-control <?php $__errorArgs = ['set-serper-api'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="set-serper-api" name="set-serper-api" value="<?php echo e(config('services.serper.key')); ?>" autocomplete="off">
														<?php $__errorArgs = ['set-serper-api'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
															<p class="text-danger"><?php echo e($errors->first('set-serper-api')); ?></p>
														<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
													</div> 												
												</div> 
											</div>
										</div>			
									</div>
								</div>

								<div class="card border-0 special-shadow">							
									<div class="card-body">
										<h6 class="fs-12 font-weight-bold mb-4"><img src="<?php echo e(URL::asset('img/csp/plagiarism.png')); ?>" class="fw-2 mr-2" alt=""><?php echo e(__('Plagiarism Check Settings')); ?></h6>

										<?php if($type == 'Regular License' || $type == ''): ?>
											<p class="fs-14 text-center" style="background:#FFE2E5; color:#ff0000; padding:1rem 2rem; border-radius: 0.5rem;"><?php echo e(__('Extended License is required in order to have access to these features')); ?></p>
										<?php else: ?>
											<div class="row">
												<div class="col-md-6 col-sm-12">
													<div class="input-box">								
														<h6><?php echo e(__('Plagiarism Check API Token')); ?></h6>
														<div class="form-group">							    
															<input type="text" class="form-control <?php $__errorArgs = ['set-plagiarism-api'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="set-plagiarism-api" name="set-plagiarism-api" value="<?php echo e(config('services.plagiarism.key')); ?>" autocomplete="off">
															<?php $__errorArgs = ['set-plagiarism-api'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																<p class="text-danger"><?php echo e($errors->first('set-plagiarism-api')); ?></p>
															<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
														</div> 												
													</div> 
												</div>
											</div>
										<?php endif; ?>			
									</div>
								</div>

								<!-- SAVE CHANGES ACTION BUTTON -->
								<div class="border-0 text-center mb-2 mt-1">
									<button type="button" class="btn ripple btn-primary" style="min-width: 200px;" id="api-settings"><?php echo e(__('Save')); ?></button>							
								</div>
							</form>
						</div>

						<div class="tab-pane fade" id="extended" role="tabpanel" aria-labelledby="extended-tab">
							<form id="extended-features-form" action="<?php echo e(route('admin.davinci.configs.store.extended')); ?>" method="POST" enctype="multipart/form-data">
								<?php echo csrf_field(); ?>

								<?php if($type == 'Regular License' || $type == ''): ?>
									<p class="fs-14 text-center" style="background:#FFE2E5; color:#ff0000; padding:1rem 2rem; border-radius: 0.5rem;"><?php echo e(__('Extended License is required in order to have access to these features')); ?></p>
								<?php else: ?>
									<div class="card border-0 special-shadow mt-0 mb-7">							
										<div class="card-body">

											<h6 class="fs-12 font-weight-bold mb-4"><i class="fa-sharp fa-solid fa-video text-danger fs-14 mr-2"></i><?php echo e(__('AI Video Settings')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>) (Stable Diffusion)</span></h6>

											<div class="row">
												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('AI Image to Video Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<div class="form-group mt-3">
															<label class="custom-switch">
																<input type="checkbox" name="video-feature-user" class="custom-switch-input" <?php if( config('settings.video_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
																<span class="custom-switch-indicator"></span>
															</label>
														</div>
													</div>
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('AI Image to Video Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<div class="form-group mt-3">
															<label class="custom-switch">
																<input type="checkbox" name="video-user-access" class="custom-switch-input" <?php if( config('settings.video_user_access')  == 'allow'): ?> checked <?php endif; ?>>
																<span class="custom-switch-indicator"></span>
															</label>
														</div>
													</div>
												</div>	

												<div class="col-md-6 col-sm-12">
													<div class="input-box">								
														<h6><?php echo e(__('Cost per Video')); ?> <span class="text-muted">(<?php echo e(__('Number of Images per Video')); ?>)</span></h6>
														<div class="form-group">							    
															<input type="text" class="form-control <?php $__errorArgs = ['text-to-video-cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="text-to-video-cost" name="text-to-video-cost" value="<?php echo e(config('settings.cost_per_image_to_video')); ?>" autocomplete="off">
															<?php $__errorArgs = ['text-to-video-cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																<p class="text-danger"><?php echo e($errors->first('text-to-video-cost')); ?></p>
															<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
														</div> 												
													</div> 
												</div>			
											</div>		
										</div>
									</div>

									<div class="card border-0 special-shadow mt-0 mb-7">							
										<div class="card-body">

											<h6 class="fs-12 font-weight-bold mb-4"><i class="fa-sharp fa-solid fa-user-music text-danger fs-14 mr-2"></i><?php echo e(__('Voice Cloning Settings')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>) (ElevenLabs)</span></h6>

											<div class="row">

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('Voice Cloning Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<div class="form-group mt-3">
															<label class="custom-switch">
																<input type="checkbox" name="voice-clone-feature-user" class="custom-switch-input" <?php if( config('settings.voice_clone_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
																<span class="custom-switch-indicator"></span>
															</label>
														</div>
													</div>
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('Voice Cloning Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<div class="form-group mt-3">
															<label class="custom-switch">
																<input type="checkbox" name="voice-clone-user-access" class="custom-switch-input" <?php if( config('settings.voice_clone_user_access')  == 'allow'): ?> checked <?php endif; ?>>
																<span class="custom-switch-indicator"></span>
															</label>
														</div>
													</div>
												</div>	
												
												<div class="col-md-6 col-sm-12">
													<div class="input-box">								
														<h6><?php echo e(__('Voice Clone Limit')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span></h6>
														<div class="form-group">							    
															<input type="text" class="form-control <?php $__errorArgs = ['voice-clone-limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="voice-clone-limit" name="voice-clone-limit" value="<?php echo e(config('settings.voice_clone_limit')); ?>" autocomplete="off">
															<?php $__errorArgs = ['voice-clone-limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																<p class="text-danger"><?php echo e($errors->first('voice-clone-limit')); ?></p>
															<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
														</div> 												
													</div> 
												</div>
											</div>		
										</div>
									</div>

									<div class="card border-0 special-shadow mt-0 mb-7">							
										<div class="card-body">

											<h6 class="fs-12 font-weight-bold mb-4"><i class="fa-sharp fa-solid fa-photo-film-music text-danger fs-14 mr-2"></i><?php echo e(__('Sound Studio Settings')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span></h6>

											<div class="row">
												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('Sound Studio Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<div class="form-group mt-3">
															<label class="custom-switch">
																<input type="checkbox" name="sound-studio-feature-user" class="custom-switch-input" <?php if( config('settings.sound_studio_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
																<span class="custom-switch-indicator"></span>
															</label>
														</div>
													</div>
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('Sound Studio Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<div class="form-group mt-3">
															<label class="custom-switch">
																<input type="checkbox" name="sound-studio-user-access" class="custom-switch-input" <?php if( config('settings.sound_studio_user_access')  == 'allow'): ?> checked <?php endif; ?>>
																<span class="custom-switch-indicator"></span>
															</label>
														</div>
													</div>
												</div>	
												
												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('Maximum Number of Audio Files to Merge')); ?> <i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('Maximum Limit is 20 Audio Files that can be merged in a single task.')); ?>"></i></h6>
														<div class="form-group">							    
															<input type="text" class="form-control <?php $__errorArgs = ['max-merge-files'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="max-merge-files" name="max-merge-files" min="1" max="20" value="<?php echo e(config('settings.voiceover_max_merge_files')); ?>" autocomplete="off">
															<?php $__errorArgs = ['max-merge-files'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																<p class="text-danger"><?php echo e($errors->first('max-merge-files')); ?></p>
															<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
														</div> 		
													</div>
												</div>	

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('Maximum Background Music Size')); ?> <i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('Maximum size (in MB) of allowed background music upload for users. In Sound Studio settings page Admin can upload background audio files up to 100MB.')); ?>"></i></h6>
														<div class="form-group">							    
															<input type="text" class="form-control <?php $__errorArgs = ['max-background-audio-size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="1" id="max-background-audio-size" name="max-background-audio-size" value="<?php echo e(config('settings.voiceover_max_background_audio_size')); ?>" autocomplete="off">
															<?php $__errorArgs = ['max-background-audio-size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																<p class="text-danger"><?php echo e($errors->first('max-background-audio-size')); ?></p>
															<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
														</div> 		
													</div>
												</div>	

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('Windows FFmpeg Path')); ?> <i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="<?php echo e(__('In case if you want to test locally on Windows OS, provide FFmpeg bin path. Note: You will need to install FFmpeg on your Windows OS by yourself.')); ?>"></i></h6>
														<div class="form-group">							    
															<input type="text" class="form-control <?php $__errorArgs = ['windows-ffmpeg-path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="windows-ffmpeg-path" name="windows-ffmpeg-path" value="<?php echo e(config('settings.voiceover_windows_ffmpeg_path')); ?>" autocomplete="off">
															<?php $__errorArgs = ['windows-ffmpeg-path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																<p class="text-danger"><?php echo e($errors->first('windows-ffmpeg-path')); ?></p>
															<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
														</div> 		
													</div>
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<a href="<?php echo e(route('admin.studio')); ?>" class="btn btn-primary ripple mt-4 pl-6 pr-6"><?php echo e(__('Default Background Audio Tracks')); ?></a>	
													</div>
												</div>												
											</div>		
										</div>
									</div>

									<div class="card border-0 special-shadow mt-0 mb-7">							
										<div class="card-body">

											<h6 class="fs-12 font-weight-bold mb-4"><i class="fa-sharp fa-solid fa-shield-check text-danger fs-14 mr-2"></i><?php echo e(__('AI Plagiarism Checker Settings')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>) (Plagiarism Check Org)</span></h6>

											<div class="row">

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('AI Plagiarism Checker Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<div class="form-group mt-3">
															<label class="custom-switch">
																<input type="checkbox" name="plagiarism-checker-feature-user" class="custom-switch-input" <?php if( config('settings.plagiarism_checker_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
																<span class="custom-switch-indicator"></span>
															</label>
														</div>
													</div>
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('AI Plagiarism Checker Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<div class="form-group mt-3">
															<label class="custom-switch">
																<input type="checkbox" name="plagiarism-checker-user-access" class="custom-switch-input" <?php if( config('settings.plagiarism_checker_user_access')  == 'allow'): ?> checked <?php endif; ?>>
																<span class="custom-switch-indicator"></span>
															</label>
														</div>
													</div>
												</div>				
											</div>		
										</div>
									</div>

									<div class="card border-0 special-shadow mt-0">							
										<div class="card-body">

											<h6 class="fs-12 font-weight-bold mb-4"><i class="fa-sharp fa-solid fa-user-secret text-danger fs-14 mr-2"></i><?php echo e(__('AI Content Detector Settings')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>) (Plagiarism Check Org)</span></h6>

											<div class="row">

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('AI Content Detector Feature')); ?> <span class="text-muted">(<?php echo e(__('For All Groups')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<div class="form-group mt-3">
															<label class="custom-switch">
																<input type="checkbox" name="ai-detector-feature-user" class="custom-switch-input" <?php if( config('settings.ai_detector_feature_user')  == 'allow'): ?> checked <?php endif; ?>>
																<span class="custom-switch-indicator"></span>
															</label>
														</div>
													</div>
												</div>

												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="input-box">
														<h6><?php echo e(__('AI Content Detector Feature Access')); ?> <span class="text-muted">(<?php echo e(__('For Non-Subscribers')); ?>)</span> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
														<div class="form-group mt-3">
															<label class="custom-switch">
																<input type="checkbox" name="ai-detector-user-access" class="custom-switch-input" <?php if( config('settings.ai_detector_user_access')  == 'allow'): ?> checked <?php endif; ?>>
																<span class="custom-switch-indicator"></span>
															</label>
														</div>
													</div>
												</div>				
											</div>		
										</div>
									</div>

									<!-- SAVE CHANGES ACTION BUTTON -->
									<div class="border-0 text-center mb-2 mt-1">
										<button type="button" class="btn ripple btn-primary" style="min-width: 200px;" id="extended-settings"><?php echo e(__('Save')); ?></button>							
									</div>
								<?php endif; ?>

								
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script> 
	<script src="<?php echo e(URL::asset('js/admin-config.js')); ?>"></script>
	<script type="text/javascript">
		let list = "<?php echo e(config('settings.voiceover_free_tier_vendors')); ?>"
		list = list.split(', ')

		$(function(){
			$("#voiceover-vendors").select2({
				theme: "bootstrap-5",
				containerCssClass: "select2--small",
				dropdownCssClass: "select2--small",
			}).val(list).trigger('change.select2');
		});

		$('#general-settings').on('click',function(e) {

			const form = document.getElementById("general-features-form");
			let data = new FormData(form);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: "POST",
				url: $('#general-features-form').attr('action'),
				data: data,
				processData: false,
				contentType: false,
				success: function(data) {

					if (data['status'] == 200) {
						toastr.success('<?php echo e(__('Settings were successfully updated')); ?>');
					}

				},
				error: function(data) {
					toastr.error('<?php echo e(__('There was an issue with saving the settings')); ?>');
				}
			}).done(function(data) {})
		});


		$('#api-settings').on('click',function(e) {

			const form = document.getElementById("api-features-form");
			let data = new FormData(form);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: "POST",
				url: $('#api-features-form').attr('action'),
				data: data,
				processData: false,
				contentType: false,
				success: function(data) {

					if (data['status'] == 200) {
						toastr.success('<?php echo e(__('Settings were successfully updated')); ?>');
					}

				},
				error: function(data) {
					toastr.error('<?php echo e(__('There was an issue with saving the settings')); ?>');
				}
			}).done(function(data) {})
		});


		$('#extended-settings').on('click',function(e) {

			const form = document.getElementById("extended-features-form");
			let data = new FormData(form);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: "POST",
				url: $('#extended-features-form').attr('action'),
				data: data,
				processData: false,
				contentType: false,
				success: function(data) {

					if (data['status'] == 200) {
						toastr.success('<?php echo e(__('Settings were successfully updated')); ?>');
					}

				},
				error: function(data) {
					toastr.error('<?php echo e(__('There was an issue with saving the settings')); ?>');
				}
			}).done(function(data) {})
		});
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ns1wjmaq/domains/aimindcrafter.com/public_html/resources/views/admin/davinci/configuration/index.blade.php ENDPATH**/ ?>