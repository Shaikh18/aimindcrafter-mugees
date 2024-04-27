<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatCategory;

class ChatCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ads = [
            ['id' => 1, 'name' => 'Business', 'code' => 'business', 'type' => 'original'],
            ['id' => 2, 'name' => 'Coach', 'code' => 'coach', 'type' => 'original'],
            ['id' => 3, 'name' => 'Education', 'code' => 'education', 'type' => 'original'],
            ['id' => 4, 'name' => 'Health', 'code' => 'health', 'type' => 'original'],
            ['id' => 5, 'name' => 'Leisure', 'code' => 'leisure', 'type' => 'original'],
            ['id' => 6, 'name' => 'Specialist', 'code' => 'specialist', 'type' => 'original'],
            ['id' => 7, 'name' => 'Other', 'code' => 'other', 'type' => 'original'],


        ];

        foreach ($ads as $ad) {
            ChatCategory::updateOrCreate(['id' => $ad['id']], $ad);
        }
    }
}
