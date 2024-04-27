<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FrontendTool;

class FrontendToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inputs = [
            ['id' => 1, 'tool_code' => 'ai-chat', 'tool_name' => 'AI Chat Bots', 'title_icon' => 'fa-message-captions purple-icon', 'title_meta' => 'AI Chat Bots', 'title' => 'Creative Virtual AI Assistants', 'description' => 'We have trained our AI Chat Bots with the knowledge of industry experts and conversion experts so you can be sure it knows how to do its job and answer all your questions instantly and provide requested information', 'image' => 'img/frontend/features/chat.webp', 'image_footer' => 'Train. Deploy. Enjoy.', 'status' => true],
            ['id' => 2, 'tool_code' => 'ai-content', 'tool_name' => 'AI Contents', 'title_icon' => 'fa-microchip-ai yellow-icon', 'title_meta' => 'AI Content Generation', 'title' => 'Create amazing content 10X faster', 'description' => 'Davinci can help you with a variety of writing tasks, from writing blog post, creating better resumes and job descriptions to composing emails and social media content, and many more. With 70+ templates, we can save you time and improve your writing skills.', 'image' => 'img/frontend/features/main-templates.png', 'image_footer' => 'Select. Create. Download.', 'status' => true],
            ['id' => 3, 'tool_code' => 'ai-image', 'tool_name' => 'AI Images', 'title_icon' => 'fa-camera-viewfinder blue-icon', 'title_meta' => 'AI Image Creation', 'title' => 'Use AI to create any art or image', 'description' => 'Are you looking for a tool to help you create unique beautiful artwork and images quickly and easily? Look no further! Our AI-powered software makes it simple to generate high-quality art and images with just a few clicks. With our intuitive interface and powerful technology, you can create stunning visuals in minutes instead of hours.', 'image' => 'img/frontend/features/image.webp', 'image_footer' => '', 'status' => true],
            ['id' => 4, 'tool_code' => 'ai-voiceover', 'tool_name' => 'AI Voiceovers', 'title_icon' => 'fa-waveform-lines yellow-icon', 'title_meta' => 'AI Voiceover Synthesize', 'title' => 'Make studio-quality voiceovers in minutes', 'description' => 'Truly human emotions in every voice over generated, breathing life into your voice overs. Our AI voices have elements that make a voice sound NATURAL and have all the expressions and tone inflections that are needed to make people more engaged in your content', 'image' => 'img/frontend/features/voiceover.webp', 'image_footer' => 'Select. Type. Listen.', 'status' => true],
            ['id' => 5, 'tool_code' => 'ai-speech', 'tool_name' => 'AI Speech to Text', 'title_icon' => 'fa-music blue-icon', 'title_meta' => 'AI Speech to Text Transcribe', 'title' => 'Transcribe accurately your audio', 'description' => 'Accurately transcribe audio content in various formats. Enable transcription of your audio files in multiple languages, as well as translation from those languages into English.', 'image' => 'img/frontend/features/transcribe.webp', 'image_footer' => 'Choose. Upload. Transcribe.', 'status' => true],
            ['id' => 6, 'tool_code' => 'ai-code', 'tool_name' => 'AI Codes', 'title_icon' => 'fa-square-code black-icon', 'title_meta' => 'AI Code Generation', 'title' => 'Write code like a Pro', 'description' => 'Generate complex algorithms simply by using natural language to explain what you are after, we will take care rest for you. Write code like Pro in Python, Flutter, PHP, JavaScript, Ruby and other programming languages.', 'image' => 'img/frontend/features/code.webp', 'image_footer' => 'Think. Generate. Use.', 'status' => true],
        ];

        foreach ($inputs as $input) {
            FrontendTool::updateOrCreate(['id' => $input['id']], $input);
        }
    }
}
