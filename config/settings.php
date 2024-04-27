<?php

return [

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */

    'registration' => env('GENERAL_SETTINGS_REGISTRATION'),

    'subscribe' => env('GENERAL_SETTINGS_SUBSCRIBE_AT_REGISTRATION'),

    'email_verification' => env('GENERAL_SETTINGS_EMAIL_VERIFICATION'),

    'oauth_login' => env('GENERAL_SETTINGS_OAUTH_LOGIN'),

    'default_user' => env('GENERAL_SETTINGS_DEFAULT_USER_GROUP'),

    'default_country' => env('GENERAL_SETTINGS_DEFAULT_COUNTRY'),

    'support_email' => env('GENERAL_SETTINGS_SUPPORT_EMAIL'),

    'user_notification' => env('GENERAL_SETTINGS_USER_NOTIFICATION'),

    'user_support' => env('GENERAL_SETTINGS_USER_SUPPORT'),

    'live_chat' => env('GENERAL_SETTINGS_LIVE_CHAT'),

    'live_chat_link' => env('GENERAL_SETTINGS_LIVE_CHAT_LINK'),

    'default_theme' => env('GENERAL_SETTINGS_THEME'),

    /*
    |--------------------------------------------------------------------------
    | Davinchi Settings
    |--------------------------------------------------------------------------
    */

    'default_model_admin' => env('DAVINCI_SETTINGS_DEFAULT_MODEL_ADMIN'),
    'default_model_user' => env('DAVINCI_SETTINGS_DEFAULT_MODEL_USER'),
    'default_embedding_model' => env('DAVINCI_SETTINGS_DEFAULT_EMBEDDING_MODEL'),
    'default_language' => env('DAVINCI_SETTINGS_DEFAULT_LANGUAGE'),

    'templates_access_admin' => env('DAVINCI_SETTINGS_TEMPLATES_ACCESS_ADMIN'),
    'templates_access_user' => env('DAVINCI_SETTINGS_TEMPLATES_ACCESS_USER'),

    'chats_access_user' => env('DAVINCI_SETTINGS_CHATS_ACCESS_USER'),
    'vision_access_user' => env('DAVINCI_SETTINGS_VISION_ACCESS_FREE_TIER_USER'),
    'wizard_access_user' => env('DAVINCI_SETTINGS_WIZARD_ACCESS_FREE_TIER_USER'),
    'chat_image_user_access' => env('DAVINCI_SETTINGS_CHAT_IMAGE_ACCESS_FREE_TIER_USER'),
    'internet_user_access' => env('DAVINCI_SETTINGS_INTERNET_ACCESS_FREE_TIER_USER'),
    'chat_web_user_access' => env('DAVINCI_SETTINGS_CHAT_WEB_ACCESS_FREE_TIER_USER'),
    'chat_file_user_access' => env('DAVINCI_SETTINGS_CHAT_FILE_ACCESS_FREE_TIER_USER'),
    'smart_editor_user_access' => env('DAVINCI_SETTINGS_SMART_EDITOR_FREE_TIER_USER'),
    'rewriter_user_access' => env('DAVINCI_SETTINGS_REWRITER_FREE_TIER_USER'),
    'video_user_access' => env('DAVINCI_SETTINGS_VIDEO_FREE_TIER_USER'),
    'voice_clone_user_access' => env('DAVINCI_SETTINGS_VOICE_CLONE_FREE_TIER_USER'),
    'sound_studio_user_access' => env('DAVINCI_SETTINGS_SOUND_STUDIO_FREE_TIER_USER'),
    'plagiarism_checker_user_access' => env('DAVINCI_SETTINGS_PLAGIARISM_CHECKER_FREE_TIER_USER'),
    'ai_detector_user_access' => env('DAVINCI_SETTINGS_AI_DETECTOR_FREE_TIER_USER'),
    'youtube_user_access' => env('DAVINCI_SETTINGS_YOUTUBE_FREE_TIER_USER'),
    'brand_voice_user_access' => env('DAVINCI_SETTINGS_BRAND_VOICE_FREE_TIER_USER'),

    'free_tier_words' => env('DAVINCI_SETTINGS_FREE_TIER_WORDS'),
    'free_tier_dalle_images' => env('DAVINCI_SETTINGS_FREE_TIER_DALLE_IMAGES'),
    'free_tier_sd_images' => env('DAVINCI_SETTINGS_FREE_TIER_SD_IMAGES'),
    'image_feature_user' =>env('DAVINCI_SETTINGS_IMAGE_FEATURE_USER'),
    'image_vendor' =>env('DAVINCI_SETTINGS_IMAGE_SERVICE_VENDOR'),
    'image_stable_diffusion_engine' =>env('DAVINCI_SETTINGS_IMAGE_STABLE_DIFFUSION_ENGINE'),
    'image_dalle_engine' =>env('DAVINCI_SETTINGS_IMAGE_DALLE_ENGINE'),
    'code_feature_user' =>env('DAVINCI_SETTINGS_CODE_FEATURE_USER'),
    'chat_feature_user' =>env('DAVINCI_SETTINGS_CHAT_FEATURE_USER'),
    'chat_default_voice' => env('DAVINCI_SETTINGS_CHAT_DEFAULT_VOICE'),
    'chat_image_feature_user' => env('DAVINCI_SETTINGS_CHAT_IMAGE_FEATURE_USER'),
    'chat_web_feature_user' => env('DAVINCI_SETTINGS_CHAT_WEB_FEATURE_USER'),
    'chat_file_feature_user' => env('DAVINCI_SETTINGS_CHAT_FILE_FEATURE_USER'),
    'chat_pdf_file_size_user' => env('DAVINCI_SETTINGS_CHAT_PDF_FILE_SIZE_USER'), 
    'chat_csv_file_size_user' => env('DAVINCI_SETTINGS_CHAT_CSV_FILE_SIZE_USER'), 
    'chat_word_file_size_user' => env('DAVINCI_SETTINGS_CHAT_WORD_FILE_SIZE_USER'), 

    'voiceover_feature_user' =>env('DAVINCI_SETTINGS_VOICEOVER_FEATURE_USER'),
    'whisper_feature_user' =>env('DAVINCI_SETTINGS_WHISPER_FEATURE_USER'),
    'vision_feature_user' =>env('DAVINCI_SETTINGS_VISION_FEATURE_USER'),
    'vision_for_chat_feature_user' =>env('DAVINCI_SETTINGS_VISION_FOR_CHAT_FEATURE_USER'),
    'wizard_feature_user' =>env('DAVINCI_SETTINGS_WIZARD_FEATURE_USER'),
    'wizard_image_vendor' =>env('DAVINCI_SETTINGS_WIZARD_IMAGE_VENDOR'),
    'smart_editor_feature_user' => env('DAVINCI_SETTINGS_SMART_EDITOR_FEATURE_USER'),
    'rewriter_feature_user' => env('DAVINCI_SETTINGS_REWRITER_FEATURE_USER'),
    'video_feature_user' => env('DAVINCI_SETTINGS_VIDEO_FEATURE_USER'),
    'voice_clone_feature_user' => env('DAVINCI_SETTINGS_VOICE_CLONE_FEATURE_USER'),
    'sound_studio_feature_user' => env('DAVINCI_SETTINGS_SOUND_STUDIO_FEATURE_USER'),
    'plagiarism_checker_feature_user' => env('DAVINCI_SETTINGS_PLAGIARISM_CHECKER_USER'),
    'ai_detector_feature_user' => env('DAVINCI_SETTINGS_AI_DETECTOR_FEATURE_USER'),
    'youtube_feature_user' => env('DAVINCI_SETTINGS_YOUTUBE_FEATURE_USER'),

    'max_results_limit_admin' => env('DAVINCI_SETTINGS_MAX_RESULTS_LIMIT_ADMIN'),
    'max_results_limit_user' => env('DAVINCI_SETTINGS_MAX_RESULTS_LIMIT_USER'),

    'default_storage' => env('DAVINCI_SETTINGS_DEFAULT_STORAGE'),

    'sd_key_usage' => env('DAVINCI_SETTINGS_SD_KEY_USAGE', 'main'),
    'openai_key_usage' => env('DAVINCI_SETTINGS_OPENAI_KEY_USAGE', 'main'),

    'team_members_feature' => env('DAVINCI_SETTINGS_TEAM_MEMBERS_FEATURE'),
    'team_members_quantity_user' => env('DAVINCI_SETTINGS_TEAM_MEMBERS_QUANTITY'),

    'personal_openai_api' => env('DAVINCI_SETTINGS_PERSONAL_OPENAI_API_KEY'),
    'personal_sd_api' => env('DAVINCI_SETTINGS_PERSONAL_SD_API_KEY'),

    'cost_per_image_to_video' => env('DAVINCI_SETTINGS_COST_PER_IMAGE_TO_VIDEO'),
    'voice_clone_limit' => env('DAVINCI_SETTINGS_VOICE_CLONE_LIMIT_FREE_TIER_USER'),

    'custom_chats' => env('DAVINCI_SETTINGS_CUSTOM_CHATS'),
    'custom_templates' => env('DAVINCI_SETTINGS_CUSTOM_TEMPLATES'),

    'file_result_duration_user' => env('DAVINCI_SETTINGS_FILE_RESULT_DURATION_USER'),
    'document_result_duration_user' => env('DAVINCI_SETTINGS_DOCUMENT_RESULT_DURATION_USER'),

    /*
    |--------------------------------------------------------------------------
    | Voiceover Settings
    |--------------------------------------------------------------------------
    */

    'enable' => [
        'azure' => env('DAVINCI_SETTINGS_VOICEOVER_ENABLE_AZURE'),
        'gcp' => env('DAVINCI_SETTINGS_VOICEOVER_ENABLE_GCP'),   
        'elevenlabs' => env('DAVINCI_SETTINGS_VOICEOVER_ENABLE_ELEVENLABS'),   
        'openai_std' => env('DAVINCI_SETTINGS_VOICEOVER_ENABLE_OPENAI_STANDARD'),   
        'openai_nrl' => env('DAVINCI_SETTINGS_VOICEOVER_ENABLE_OPENAI_NEURAL'),   
        'aws_std' => env('DAVINCI_SETTINGS_VOICEOVER_ENABLE_AWS_STANDARD'),   
        'aws_nrl' => env('DAVINCI_SETTINGS_VOICEOVER_ENABLE_AWS_NEURAL'), 
    ],

    'voiceover_default_language' => env('DAVINCI_SETTINGS_VOICEOVER_DEFAULT_LANGUAGE'),
    'voiceover_default_voice' => env('DAVINCI_SETTINGS_VOICEOVER_DEFAULT_VOICE'),
    'voiceover_ssml_effect' => env('DAVINCI_SETTINGS_VOICEOVER_SSML_EFFECT', 'enable'),
    'voiceover_max_chars_limit' => env('DAVINCI_SETTINGS_VOICEOVER_MAX_CHAR_LIMIT', 5000),
    'voiceover_max_voice_limit' => env('DAVINCI_SETTINGS_VOICEOVER_MAX_VOICE_LIMIT', 5),
    'voiceover_default_storage' => env('DAVINCI_SETTINGS_VOICEOVER_DEFAULT_STORAGE', 'local'),
    'voiceover_welcome_chars' => env('DAVINCI_SETTINGS_VOICEOVER_FREE_TIER_WELCOME_CHARS', 0),
    'voiceover_windows_ffmpeg_path' => env('DAVINCI_SETTINGS_WINDOWS_FFMPEG_PATH'),
    'voiceover_max_background_audio_size' => env('DAVINCI_SETTINGS_VOICEOVER_MAX_BACKGROUND_AUDIO_SIZE', 10),
    'voiceover_max_merge_files' => env('DAVINCI_SETTINGS_VOICEOVER_MAX_MERGE_FILES', 10),
    'voiceover_free_tier_vendors' => env('DAVINCI_SETTINGS_VOICEOVER_FREE_TIER_VENDORS'),

    /*
    |--------------------------------------------------------------------------
    | Whisper Settings
    |--------------------------------------------------------------------------
    */

    'whisper_max_audio_size' => env('DAVINCI_SETTINGS_WHISPER_MAX_AUDIO_SIZE', 25),
    'whisper_default_storage' => env('DAVINCI_SETTINGS_WHISPER_DEFAULT_STORAGE', 'local'),
    'whisper_welcome_minutes' => env('DAVINCI_SETTINGS_WHISPER_FREE_TIER_WELCOME_MINUTES', 0),
];
