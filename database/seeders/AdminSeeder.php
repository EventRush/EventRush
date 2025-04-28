<?php

namespace Database\Seeders;

use App\Models\PlansSouscription;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 
        Utilisateur::updateOrCreate(
            ['email' => 'towanouluc@gmail.com'],
            [
                'nom' => 'Admin Principal',
                'password' => Hash::make('Chrislenne175'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        // Utilisateur::updateOrCreate(
        //     ['email' => 'luctowanou@gmail.com'],
        //     [
        //         'nom' => 'Admin Secondaire',
        //         'password' => Hash::make('chrislenn175'),
        //         'role' => 'admin'
        //     ]
        // );
    
    

    }
}
