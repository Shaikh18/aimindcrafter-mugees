<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FrontendStep;

class FrontendStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inputs = [
            ['id' => 1, 'order' => 1, 'title' => 'Select a writing tool', 'description' => 'Choose from a wide array of AI tools to write social media ads, hero sections, blog posts, essays, etc...'],
            ['id' => 2, 'order' => 2, 'title' => 'Tell us what to write about', 'description' => 'Explain with as many details as possible to the AI what you would like to write about.'],
            ['id' => 3, 'order' => 3, 'title' => 'Generate AI content', 'description' => 'Our highly trained AI understands your details and generate unique and human-like content in seconds.'],
        ];

        foreach ($inputs as $input) {
            FrontendStep::updateOrCreate(['id' => $input['id']], $input);
        }
    }
}
