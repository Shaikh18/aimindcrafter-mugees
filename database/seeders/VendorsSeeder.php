<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendors = [
            ['id' => 1, 'vendor_id' => 'azure_nrl', 'enabled' => 0, 'cost' => 0.000016],
            ['id' => 2, 'vendor_id' => 'gcp_nrl', 'enabled' => 0, 'cost' => 0.000016],
            ['id' => 3, 'vendor_id' => 'openai_std', 'enabled' => 0, 'cost' => 0.000015],
            ['id' => 4, 'vendor_id' => 'openai_nrl', 'enabled' => 0, 'cost' => 0.00003],
            ['id' => 5, 'vendor_id' => 'elevenlabs_nrl', 'enabled' => 0, 'cost' => 0.00005],
            ['id' => 6, 'vendor_id' => 'aws_std', 'enabled' => 0, 'cost' => 0.000004],
            ['id' => 7, 'vendor_id' => 'aws_nrl', 'enabled' => 0, 'cost' => 0.000016],
        ];

        foreach ($vendors as $vendor) {
            Vendor::updateOrCreate(['id' => $vendor['id']], $vendor);
        }
    }
}
