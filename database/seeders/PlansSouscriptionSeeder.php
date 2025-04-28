<?php

namespace Database\Seeders;

use App\Models\PlansSouscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlansSouscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 
        PlansSouscription::insert([
            [
                'nom' => 'Basique',
                'prix' => 5000,
                'duree_jours' => 15,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'nom' => 'Pro',
                'prix' => 10000,
                'duree_jours' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Premium',
                'prix' => 20000,
                'duree_jours' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
