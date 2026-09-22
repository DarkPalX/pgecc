<?php

namespace Database\Seeders;

use App\Models\MemberClass;
use Illuminate\Database\Seeder;

class MemberClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['name' => 'COPPER', 'carenderia_limit' => 1000, 'consumer_limit' => 2000, 'maximum_loan' => 4000],
            ['name' => 'BRONZE', 'carenderia_limit' => 2000, 'consumer_limit' => 3000, 'maximum_loan' => 10000],
            ['name' => 'SILVER', 'carenderia_limit' => 2000, 'consumer_limit' => 4000, 'maximum_loan' => 15000],
            ['name' => 'GOLD', 'carenderia_limit' => 2000, 'consumer_limit' => 5000, 'maximum_loan' => 20000],
            ['name' => 'DIAMOND', 'carenderia_limit' => 2000, 'consumer_limit' => 5000, 'maximum_loan' => 25000],
            ['name' => 'TITANIUM', 'carenderia_limit' => 3000, 'consumer_limit' => 8000, 'maximum_loan' => 30000],
            ['name' => 'PLATINUM', 'carenderia_limit' => 5000, 'consumer_limit' => 10000, 'maximum_loan' => 35000],
        ];

        foreach ($classes as $class) {
            MemberClass::updateOrCreate(['name' => $class['name']], $class);
        }
    }
}
