<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class RemodelingReleaseOneSeeder extends Seeder
{
    public function run(): void
    {
        Industry::updateOrCreate(['slug' => 'construction-renovation'], ['name' => 'Construction / Renovation', 'is_active' => true]);
    }
}
