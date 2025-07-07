<?php

namespace Database\Seeders;

use App\Models\Test;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;


class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        for ($i = 0; $i < 20; $i++) {
            Test::create([
                'qr_code' => Str::uuid(),
                'status_scan' => 'non_scanné',
                'event_id' => $faker ->numberBetween(0, 20),
            ]);
        }
    }
}
