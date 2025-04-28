<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UtilisateursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Utilisateur::insert([
            [
                'nom' => 'Merveille',
                'email' => 'merveille@gmail.com',
                'password' => Hash::make('Faker0001'),
                'role' => 'organisateur',
                'email_verified_at' => now(),

            ],
            [
                'nom' => 'Socrates',
                'email' => 'socrates@gmail.com',
                'password' => Hash::make('Faker0002'),
                'role' => 'organisateur',
                'email_verified_at' => now(),

            ],
            [
                'nom' => 'Samuel',
                'email' => 'samuel@gmail.com',
                'password' => Hash::make('Faker0003'),
                'role' => 'client',
                'email_verified_at' => now(),

            ],[
                'nom' => 'Malik',
                'email' => 'malik@gmail.com',
                'password' => Hash::make('Faker0004'),
                'role' => 'client',
                'email_verified_at' => now(),

            ],[
                'nom' => 'Femi',
                'email' => 'femi@gmail.com',
                'password' => Hash::make('Faker0005'),
                'role' => 'client',
                'email_verified_at' => now(),

            ],
        ]);
    }
}
