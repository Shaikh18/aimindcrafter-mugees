<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FrontendFeature;

class FrontendFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inputs = [
            ['id' => 1, 'title' => 'Customize Templates', 'description' => 'Create and train your unique custom template and enjoy', 'image' => 'img/frontend/features/templates.webp', 'status' => true],
            ['id' => 2, 'title' => 'Variety of Languages', 'description' => 'Generate AI content in more than 54 languages and increasing', 'image' => 'img/frontend/features/languages.webp', 'status' => true],
            ['id' => 3, 'title' => '144+ AI Voiceover Languages', 'description' => 'Wide variety of languages and dialects for AI Voiceovers', 'image' => 'img/frontend/features/voiceover-languages.webp', 'status' => true],
            ['id' => 4, 'title' => 'Mix up to 20 voices', 'description' => 'Select from more than 540+ AI Neural Voices and mix in a single text synthesize task', 'image' => 'img/frontend/features/voices.webp', 'status' => true],
            ['id' => 5, 'title' => 'SSML & Tones', 'description' => 'Configure flow of the speech & text with SSML tags and tones', 'image' => 'img/frontend/features/ssml.webp', 'status' => true],
            ['id' => 6, 'title' => 'Convenient Payments', 'description' => '12 different payment gateways that you can use at anytime', 'image' => 'img/frontend/features/gateways.webp', 'status' => true],
            ['id' => 7, 'title' => 'Earn with Referrals', 'description' => 'Bring your friends and earn when they subscribe', 'image' => 'img/frontend/features/referral.webp', 'status' => true],
            ['id' => 8, 'title' => 'Enhanced Security and Support', 'description' => 'Secure 2FA authentication and 24/7 customer support to address any concerns', 'image' => 'img/frontend/features/security.webp', 'status' => true],
        ];

        foreach ($inputs as $input) {
            FrontendFeature::updateOrCreate(['id' => $input['id']], $input);
        }
    }
}
